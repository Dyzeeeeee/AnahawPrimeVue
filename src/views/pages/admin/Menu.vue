<script setup>
import { onMounted, reactive, ref, watch } from "vue";
import { MenuService } from "@/service/MenuService";
import { useToast } from "primevue/usetoast";
const menuItems = ref();
// const expandedRows = ref([]);
const toast = useToast();
const viewMode = ref('list');
const changeViewMode = () => {
  viewMode.value = viewMode.value === "grid" ? "list" : "grid";
};
const menu = ref();
const items = ref([
  {
    label: 'Actions',
    items: [
      {
        label: 'Edit',
        icon: 'pi pi-pencil'
      },

      {
        label: 'Archive',
        icon: 'pi pi-box'
      },
      {
        label: 'Recipe',
        icon: 'pi pi-book'
      }
    ]
  }
]);
const toggle = (event) => {
  menu.value.toggle(event);
};
onMounted(() => {
  menuItems.value = MenuService.getMenuData();
});
const selectedMenuItem = ref();




const selectedFilter = ref({ name: "Active" });
const filters = ref([{ name: "Active" }, { name: "Archived" }, { name: "All" }]);

</script>

<template>
  <Toast />

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
          <Dropdown v-model="selectedFilter" :options="filters" optionLabel="name" class="w-10rem" />
        </div>
      </template>
      <template #center> </template>
      <template #end>
        <div class="flex gap-2">

          <Button class="p-button-help font-bold gap-2" disabled>
            <Icon icon="bx:archive-in" width="1.5rem" height="1.5rem" />
            Archive
          </Button>
          <Button class="p-button-primary font-bold gap-2" icon="pi pi-plus" label="New Menu Item">
          </Button>
        </div>
      </template>
    </Toolbar>
    <template v-if="viewMode === 'list'">
      <div style="height: 70vh"
        class="border-2 border-dashed surface-border flex flex-wrap gap-3 overflow-y-scroll p-2">
        <DataTable :value="menuItems" dataKey="id" class="w-full" tableStyle="min-width: 60rem" selectionMode="multiple"
          v-model:selection="selectedMenuItem" stripedRows>

          <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
          <!-- <Column expander style="width: 5rem" /> -->
          <Column field="id" header="id">
          </Column>
          <Column header="Image">
            <template #body="slotProps">
              <img :src="`https://primefaces.org/cdn/primevue/images/product/${slotProps.data.image}`"
                :alt="slotProps.data.image" class="w-6rem border-round" />
            </template>
          </Column>
          <Column field="name" header="name">
          </Column>
          <Column field="description" header="description"></Column>
          <Column field="price" header="price">

          </Column>
          <Column field="category" header="category">
          </Column>
          <Column field="quantity" header="quantity">
          </Column>

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
      <div style="height: 70vh"
        class="border-2 border-dashed surface-border flex flex-wrap gap-3 overflow-y-scroll p-2">
        <div class="text-center align-items-center flex-1 flex justify-content-center text-6xl font-bold ">
          Katamad saka na 'to hahaha 😘
        </div>
      </div>
    </template>

  </div>
</template>
