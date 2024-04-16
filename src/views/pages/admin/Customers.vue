<script setup>
import { onMounted, computed, ref, watch } from "vue";
import CustomerService2 from "@/service/CustomerService2";
import { useToast } from "primevue/usetoast";

const customers = ref([]);
// const expandedRows = ref([]);
const toast = useToast();
const newCustomerVisible = ref(false);
const newCustomer = ref({
  firstname: '',
  lastname: '',
  phone: '',
  email: ''
});
const viewMode = ref('list');
const changeViewMode = () => {
  viewMode.value = viewMode.value === "grid" ? "list" : "grid";
};

const addCustomer = async () => {
  try {
    await CustomerService2.addCustomer(newCustomer.value);
    toast.add({ severity: 'success', summary: 'Success', detail: 'Customer added successfully', life: '3000' });
    // Fetch updated menu items
    customers.value = await CustomerService2.getAllCustomers();

    newCustomerVisible.value = false;
  } catch (error) {
    console.error('Error adding menu item:', error);
    toast.add({ severity: 'error', summary: 'Error', detail: 'Select an item to Archive', life: '3000' });
  }
};

onMounted(async () => {
  try {
    customers.value = await CustomerService2.getAllCustomers();
  } catch (error) {
    console.error('Error fetching sessions:', error);
    toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to fetch sessions' });
  }
});
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
        label: 'Orders',
        icon: 'pi pi-history'
      }
    ]
  }
]);
const toggle = (event) => {
  menu.value.toggle(event);
  console.log(selectedCustomer.value); // Log selected menu items to the console
};
const selectedCustomer = ref();



const selectedFilter = ref({ name: "Active" });
const filters = ref([{ name: "Active" }, { name: "Archived" }, { name: "All" }]);


const isArchiveButtonDisabled = computed(() => {
  return selectedFilter.value.name === "All";
});

const filteredCustomers = computed(() => {
  switch (selectedFilter.value.name) {
    case "Active":
      return customers.value.filter((item) => !item.archived_at);
    case "Archived":
      return customers.value.filter((item) => item.archived_at);
    case "All":
    default:
      return customers.value;
  }
});

const archiveButtonLabel = computed(() => {
  return selectedFilter.value.name === "Archived" ? "Unarchive" : "Archive";
});

const archiveButtonClickHandler = computed(() => {
  return selectedFilter.value.name === "Archived" ? unarchiveSelected : archiveSelected;
});

const archiveSelected = async () => {
  try {
    for (const selectedItem of selectedCustomer.value) {
      await CustomerService2.archiveCustomer(selectedItem.id);  // This should set `archived_at`
      selectedItem.archived_at = new Date();  // Assume immediate effect for UI, remove if backend handles it
    }
    toast.add({
      severity: "success",
      summary: "Success",
      detail: "Customers archived successfully",
      life: "3000",
    });
    customers.value = await CustomerService2.getAllCustomers();  // Refresh from backend
  } catch (error) {
    console.error("Error archiving customers:", error);
    toast.add({
      severity: "warn",
      summary: "Error",
      detail: "Failed to archive customers",
      life: "3000",
    });
  }
};

const unarchiveSelected = async () => {
  try {
    for (const selectedItem of selectedCustomer.value) {
      await CustomerService2.unarchiveCustomer(selectedItem.id);  // This should clear `archived_at`
      selectedItem.archived_at = null;  // Assume immediate effect for UI, remove if backend handles it
    }
    toast.add({
      severity: "success",
      summary: "Success",
      detail: "Customers unarchived successfully",
      life: "3000",
    });
    customers.value = await CustomerService2.getAllCustomers();  // Refresh from backend
  } catch (error) {
    console.error("Error unarchiving customers:", error);
    toast.add({
      severity: "warn",
      summary: "Error",
      detail: "Failed to unarchive customers",
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
          <Dropdown v-model="selectedFilter" :options="filters" optionLabel="name" class="w-10rem" />
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
          <Button class="p-button-primary font-bold gap-2" icon="pi pi-plus" label="New Customer"
            @click="newCustomerVisible = true">
          </Button>
        </div>
      </template>
    </Toolbar>
    <template v-if="viewMode === 'list'">
      <div style="height: 74vh"
        class="border-2 border-dashed surface-border flex flex-wrap gap-3 overflow-y-scroll p-2">
        <DataTable :value="filteredCustomers" dataKey="id" class="w-full" tableStyle="min-width: 60rem" selectionMode="multiple"
          v-model:selection="selectedCustomer" stripedRows>

          <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
          <Column field="name" header="Name">
          </Column>
          <Column field="email" header="Email Address"></Column>
          <Column field="phone" header="Phone Number">
          </Column>
          <Column header="Total Orders">
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
      <div style="height: 65vh"
        class="border-2 border-dashed surface-border flex flex-wrap gap-3 overflow-y-scroll p-2">
        <div class="text-center align-items-center flex-1 flex justify-content-center text-6xl font-bold ">
          Katamad saka na 'to hahaha 😘
        </div>
      </div>
    </template>

  </div>

  <Dialog v-model:visible="newCustomerVisible" modal header="New Menu Item" :style="{ width: '35rem' }">
    <div class="flex align-items-center gap-3 mb-3 mt-5">
      <FloatLabel>
        <InputText v-model="newCustomer.firstname" class="w-full flex-1" />
        <label for="name">Firstname </label>
      </FloatLabel>
      <FloatLabel>
        <InputText v-model="newCustomer.lastname" class="w-full flex-1" />
        <label for="name">Lastname </label>
      </FloatLabel>
    </div>
    <div class="flexr-row align-items-center gap-3 mb-3 mt-4">
      <FloatLabel>
        <InputText v-model="newCustomer.email" rows="2" style="width: 100%;" />
        <label>Email Address</label>
      </FloatLabel>
    </div>
    <div class="flexr-row align-items-center gap-3 mb-3 mt-4">
      <FloatLabel>
        <InputText v-model="newCustomer.phone" rows="2" style="width: 100%;" />
        <label>Phone Number</label>
      </FloatLabel>
    </div>
    <div class="flex justify-content-end gap-2">
      <Button type="button" label="Cancel" severity="secondary" @click="newCustomerVisible = false"></Button>
      <Button type="button" label="Add" @click="addCustomer"></Button>
    </div>
  </Dialog>

</template>
