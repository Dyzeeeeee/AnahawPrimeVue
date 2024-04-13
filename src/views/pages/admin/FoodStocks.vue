<script setup>
import { onMounted, reactive, ref, watch, computed } from "vue";
import FoodStockService from "@/service/FoodStockService";
import { useToast } from "primevue/usetoast";

const categories = ref([]);
const toast = useToast();

const selectedCategory = ref(null);
const newMenuVisible = ref(false);
const newMenuItem = ref({
  name: "",
  description: "",
  price: "",
  category_id: selectedCategory,
});

const getBarColor = (quantity, ideal) => {
  const ratio = quantity / ideal;
  if (ratio < 0.5) return '#ff5252'; // Red for less than 50%
  if (ratio < 0.75) return '#ffb142'; // Orange for less than 75%
  return '#4caf50'; // Green otherwise
};


const newCategory = ref({
  name: "",
});

const newCategoryVisible = ref(false);

const isArchiveButtonDisabled = computed(() => {
  return selectedFilter.value.name === "All";
});

const filteredFoodStocks = computed(() => {
  switch (selectedFilter.value.name) {
    case "Active":
      return foodStocks.value.filter((item) => !item.archived_at);
    case "Archived":
      return foodStocks.value.filter((item) => item.archived_at);
    case "All":
    default:
      return foodStocks.value;
  }
});

const archiveButtonLabel = computed(() => {
  return selectedFilter.value.name === "Archived" ? "Unarchive" : "Archive";
});

const archiveButtonClickHandler = computed(() => {
  return selectedFilter.value.name === "Archived" ? unarchiveSelected : archiveSelected;
});

const viewMode = ref("list");
const changeViewMode = () => {
  viewMode.value = viewMode.value === "grid" ? "list" : "grid";
};



const menu = ref();
const items = ref([
  {
    label: "Actions",
    items: [
      {
        label: "Edit",
        icon: "pi pi-pencil",
      },

      {
        label: "Archive",
        icon: "pi pi-box",
      },
      {
        label: "Recipe",
        icon: "pi pi-book",
      },
    ],
  },
]);

const toggle = (event) => {
  menu.value.toggle(event);
  console.log(selectedFoodStock.value); // Log selected menu items to the console
};


const selectedFoodStock = ref();
const selectedFilter = ref({ name: "Active" });
const filters = ref([{ name: "Active" }, { name: "Archived" }, { name: "All" }]);

const foodStocks = ref([]);

onMounted(async () => {
  try {
    foodStocks.value = await FoodStockService.getAllFoodStocks();
  } catch (error) {
    console.error("Error fetching foodstocks items:", error);
    toast.add({
      severity: "error",
      summary: "Error",
      detail: "Failed to fetch menu items",
    });
  }
});


const archiveSelected = async () => {
  try {
    for (const selectedItem of selectedFoodStock.value) {
      console.log("Selected ID:", selectedItem.id); // Add this line to log the ID
      await MenuService.archiveMenuItem(selectedItem.id);
      selectedFoodStock.value = null;
    }
    toast.add({
      severity: "success",
      summary: "Success",
      detail: "Menu items archived successfully",
      life: "3000",
    });
    // Refresh the menu items after archiving
    menuItems.value = await MenuService.getAllMenuItems();
  } catch (error) {
    console.error("Error archiving menu items:", error);
    toast.add({
      severity: "warn",
      summary: "Nothing to archive",
      detail: "Select an item to archive",
      life: "3000",
    });
  }
};

const unarchiveSelected = async () => {
  try {
    for (const selectedItem of selectedFoodStock.value) {
      console.log("Selected ID:", selectedItem.id); // Add this line to log the ID
      await MenuService.unarchiveMenuItem(selectedItem.id);
      selectedFoodStock.value = null;
    }
    toast.add({
      severity: "success",
      summary: "Success",
      detail: "Menu items unarchived successfully",
      life: "3000",
    });
    menuItems.value = await MenuService.getAllMenuItems();
  } catch (error) {
    console.error("Error archiving menu items:", error);
    toast.add({
      severity: "warn",
      summary: "Nothing to unarchive",
      detail: "Select an item to unarchive",
      life: "3000",
    });
  }
};

</script>

<template>


  <div class="surface-card p-4 shadow-2 border-round main-view">
    <Toolbar style="border: none">
      <template #start>
        <div class="gap-2 flex">
          <Button icon="pi pi-filter-fill"> </Button>
          <IconField iconPosition="left">
            <InputIcon>
              <i class="pi pi-search" />
            </InputIcon>
            <InputText placeholder="Search" class="w-15rem" />
          </IconField>
          <Button @click="changeViewMode" :icon="viewMode === 'grid' ? 'pi pi-table' : 'pi pi-list'">
          </Button>
          <Dropdown v-model="selectedFilter" :options="filters" optionLabel="name" class="w-10rem"
            placeholder="Category" />
        </div>
      </template>
      <template #center> </template>
      <template #end>
        <div class="flex gap-2">
          <Button class="p-button-help font-bold gap-2" @click="archiveButtonClickHandler"
            :disabled="isArchiveButtonDisabled">
            <Icon icon="bx:archive-in" width="1.5rem" height="1.5rem" />
            {{ archiveButtonLabel }}
          </Button>

          <Button class="p-button-primary font-bold gap-2" icon="pi pi-plus" label="New Menu Item"
            @click="newMenuVisible = true">
          </Button>
        </div>
      </template>
    </Toolbar>
    <template v-if="viewMode === 'list'">
      <div style="height: 65vh"
        class="border-2 border-dashed surface-border flex flex-wrap gap-3 overflow-y-scroll p-2">
        <DataTable :value="filteredFoodStocks" dataKey="id" class="w-full" tableStyle="min-width: 60rem"
          selectionMode="multiple" v-model:selection="selectedFoodStock" stripedRows>
          <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
          <!-- <Column expander style="width: 5rem" /> -->

          <Column field="ingredient_name" header="Name"> </Column>
          <Column field="quantity" header="Remaining">
            <template #body="slotProps">
              <div
                style="width: 100%; background-color: #e0e0e0; border-radius: 5px; position: relative; height: 20px; display: flex; align-items: center;">
                <div
                  :style="{ width: ((slotProps.data.quantity / slotProps.data.ideal) * 100) + '%', backgroundColor: getBarColor(slotProps.data.quantity, slotProps.data.ideal), height: '100%', borderRadius: '5px' }">
                </div>
                <span style="position: absolute; width: 100%; text-align: center; color: black; font-size: 0.75rem;">
                  {{ (slotProps.data.quantity / slotProps.data.ideal * 100).toFixed(0) }}%
                </span>
              </div>
            </template>
          </Column>


          <Column field="quantity" header="Quantity"></Column>
          <Column field="ideal" header="Ideal"> </Column>
          <Column field="unit_of_measurement" header="Unit"> </Column>
          <Column field="purchase_date" header="Purchase Date"> </Column>
          <Column field="expiration_date" header="Expiration Date"> </Column>

          <Column>
            <template #body="slotProps">
              <Button icon="pi pi-ellipsis-v" text plain rounded @click="toggle" aria-haspopup="true"
                aria-controls="overlay_menu"></Button>
              <Menu ref="menu" id="overlay_menu" :model="items" :popup="true" />
            </template>
          </Column>
          <!-- <template #expansion="slotProps">
            <div class="px-5 mx-5">
              <DataTable :value="slotProps.data.details" selectionMode="multiple">
                <Column field="id" header="Id" sortable></Column>
                <Column field="menu_item_id" header="menu_item_id" sortable></Column>
                <Column field="quantity" header="quantity" sortable>
                </Column>
                <Column field="subtotal" header="subtotal" sortable></Column>
              </DataTable>
            </div>
          </template> -->
        </DataTable>
      </div>
    </template>
    <template v-if="viewMode === 'grid'">
      <div style="height: 65vh"
        class="border-2 border-dashed surface-border flex flex-wrap gap-3 overflow-y-scroll p-2">
        <div class="text-center align-items-center flex-1 flex justify-content-center text-6xl font-bold">
          Katamad saka na 'to hahaha 😘
        </div>
      </div>
    </template>
  </div>

  <Dialog v-model:visible="newMenuVisible" modal header="New Menu Item" :style="{ width: '40rem' }">
    <div class="flex-row align-items-center gap-3 mb-3 mt-4 w-full">
      <FloatLabel>
        <InputText v-model="newMenuItem.name" class="w-full" />
        <label for="name">Name </label>
      </FloatLabel>
    </div>
    <div class="flexr-row align-items-center gap-3 mb-3 mt-4 w-full">
      <FloatLabel>
        <Textarea v-model="newMenuItem.description" rows="2" style="width: 100%" />
        <label>Description</label>
      </FloatLabel>
    </div>
    <div class="flex align-items-center gap-3 mb-3 mt-4 w-full">
      <FloatLabel class="w-full">
        <InputText v-model="newMenuItem.price" class="w-full" />
        <label for="price">Price </label>
      </FloatLabel>
      <InputGroup>
        <FloatLabel class="w-full">
          <Dropdown v-model="selectedCategory" :options="categories" optionLabel="name" class="w-full" />
          <label for="dd-city">Category</label>
        </FloatLabel>
        <Button size="sm" icon="pi pi-plus" @click="newCategoryVisible = true"></Button>
      </InputGroup>
    </div>
    <div class="flex justify-content-end gap-2">
      <Button type="button" label="Cancel" severity="secondary" @click="newMenuVisible = false"></Button>
      <Button type="button" label="Add" @click="addMenuItem"></Button>
    </div>
  </Dialog>

  <Dialog v-model:visible="newCategoryVisible" modal header="New Category" :style="{ width: '30rem' }">
    <div class="flex-row align-items-center gap-3 mb-3 mt-4 w-full">
      <FloatLabel>
        <InputText v-model="newCategory.name" class="w-full" />
        <label for="name">Category Name </label>
      </FloatLabel>
    </div>
    <div class="flex justify-content-end gap-2">
      <Button type="button" label="Cancel" severity="secondary" @click="newCategoryVisible = false"></Button>
      <Button type="button" label="Add" @click="addCategory"></Button>
    </div>
  </Dialog>
</template>
