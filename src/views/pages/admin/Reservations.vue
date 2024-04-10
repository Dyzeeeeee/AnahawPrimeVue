<script setup>
import { onMounted, reactive, ref, watch } from "vue";
import { OrderService } from "@/service/OrderService";
import { useToast } from "primevue/usetoast";
const orders = ref();
const expandedRows = ref([]);
const toast = useToast();
const menu = ref();
const items = ref([
  {
    label: 'Options',
    items: [
      {
        label: 'Print',
        icon: 'pi pi-print'
      },
      {
        label: 'Download',
        icon: 'pi pi-download'
      },
      {
        label: 'Archive',
        icon: 'pi pi-box'
      }
    ]
  }
]);
const toggle = (event) => {
  menu.value.toggle(event);
};
onMounted(() => {
  orders.value = OrderService.getOrdersData();
});
const selectedOrder = ref();


const getOrderSeverity = (order) => {
  switch (order.status) {
    case "Paid":
      return "success";

    case "Cancelled":
      return "danger";

    case "Pending":
      return "warning";

    default:
      return null;
  }
};

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
          <Button class="p-button-info font-bold gap-2" icon="pi pi-download" label="Download" disabled>
          </Button>
          <Button class="p-button-info font-bold gap-2" icon="pi pi-print" label="Print" disabled>
          </Button>
        </div>
      </template>
    </Toolbar>
    <div style="height: 65vh" class="border-2 border-dashed surface-border flex flex-wrap    overflow-y-scroll p-2">
      <DataTable :value="orders" dataKey="id" class="w-full" tableStyle="min-width: 60rem"
        v-model:expandedRows="expandedRows" selectionMode="multiple" v-model:selection="selectedOrder" stripedRows>

        <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
        <Column expander style="width: 4rem" />
        <Column field="id" header="id">
        </Column>
        <Column field="customer_id" header="Cashier">
        </Column>
        <Column field="cashier_id" header="Cashier"></Column>
        <Column field="order_date" header="Order Date">

        </Column>
        <Column field="total_order_price" header="Total">
        </Column>
        <Column field="session_id" header="Session">
        </Column>
        <Column field="tendered" header="Tendered">
        </Column>
        <Column field="change" header="Change">
        </Column>
        <Column field="status" header="Status">
          <template #body="slotProps">
            <div class="flex justify-content-center">
              <Tag :value="slotProps.data.status" :severity="getOrderSeverity(slotProps.data)" />
            </div>
          </template>
        </Column>
        <Column>
          <template #body="slotProps">
            <Button icon="pi pi-ellipsis-v" text plain rounded @click="toggle" aria-haspopup="true"
              aria-controls="overlay_menu"></Button>
            <Menu ref="menu" id="overlay_menu" :model="items" :popup="true" />

          </template>
        </Column>
        <template #expansion="slotProps">
          <div class="px-5 mx-5">
            <!-- <h5 class="text-400">Orders {{ slotProps.data.id }} Details</h5> -->
            <DataTable :value="slotProps.data.details" selectionMode="multiple">
              <Column field="id" header="Id" sortable></Column>
              <Column field="menu_item_id" header="menu_item_id" sortable></Column>
              <Column field="quantity" header="quantity" sortable>
              </Column>
              <Column field="subtotal" header="subtotal" sortable></Column>
            </DataTable>
          </div>
        </template>

      </DataTable>
    </div>
  </div>
</template>
