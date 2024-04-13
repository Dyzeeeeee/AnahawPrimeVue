<script setup>
import { onMounted, computed, ref, watch } from "vue";
import CustomerService from "@/service/CustomerService";
import { useToast } from "primevue/usetoast";
const customerService = new CustomerService();
const customers = ref();
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
        label: 'Activities',
        icon: 'pi pi-history'
      }
    ]
  }
]);
const toggle = (event) => {
  menu.value.toggle(event);
};
onMounted(() => {
  customers.value = customerService.getCustomersMedium();
});
const selectedMenuItem = ref();


const getCustomerSeverity = (customer) => {
  switch (customer.status) {
    case "new":
      return "success";
    case "renewal":
      return "secondary";
    case "qualified":
      return "info";
    case "unqualified":
      return "danger";
    case "proposal":
      return "warning";

    default:
      return null;
  }
};
const archiveButtonText = computed(() => {
  return selectedFilter.value.name === "Archived" ? "Unarchive" : "Archive";
});

const selectedFilter = ref({ name: "Active" });
const filters = ref([{ name: "Active" }, { name: "Archived" }, { name: "All" }]);

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

          <Button class="p-button-help font-bold gap-2" disabled>
            <Icon icon="bx:archive-in" width="1.5rem" height="1.5rem" />
            {{ archiveButtonText }}
          </Button>
          <Button class="p-button-primary font-bold gap-2" icon="pi pi-plus" label="New Supplier">
          </Button>
        </div>
      </template>
    </Toolbar>
    <template v-if="viewMode === 'list'">
      <div style="height: 65vh"
        class="border-2 border-dashed surface-border flex flex-wrap gap-3 overflow-y-scroll p-2">
        <DataTable :value="customers" dataKey="id" class="w-full" tableStyle="min-width: 60rem" selectionMode="multiple"
          v-model:selection="selectedMenuItem" stripedRows>

          <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
          <!-- <Column expander style="width: 5rem" /> -->
          <Column field="id" header="id">
          </Column>

          <Column field="name" header="name">
          </Column>
          <Column field="company" header="company"></Column>
          <Column field="date" header="date">

          </Column>
          <Column field="status" header="status" sortable>
            <template #body="slotProps">
              <div class="flex justify-content-center">
                <Tag :value="slotProps.data.status" :severity="getCustomerSeverity(slotProps.data)" />
              </div>
            </template>
          </Column>
          <Column field="activity" header="activity">
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
</template>
