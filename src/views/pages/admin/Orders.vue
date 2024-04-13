<script setup>
import { onMounted, reactive, ref, watch } from "vue";
import { OrderService } from "@/service/OrderService";
import OrderService1 from "@/service/OrderService1";

import { useToast } from "primevue/usetoast";

const getSeverity = (status) => {
  switch (status) {
    case "pending":
      return "warning";

    case "completed":
      return "success";

    case "cancelled":
      return "danger";
  }
};

const orders = ref([]);
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

onMounted(async () => {
  try {
    orders.value = await OrderService1.getAllOrders();
    console.log(orders.value)
  } catch (error) {
    console.error("Error fetching data:", error);
    toast.add({
      severity: "error",
      summary: "Error",
      detail: "Failed to fetch orders",
    });
  }
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
        <Column field="id" header="ID">
        </Column>
        <Column field="customer_name" header="Customer">
        </Column>
        <Column field="cashier_name" header="Cashier">
        </Column>
        <!-- <Column field="cashier_id" header="Cashier"></Column> -->
        <Column field="order_date" header="Order Date">

        </Column>
        <Column field="total_price" header="Total">
        </Column>
        <Column field="session_id" header="Session">
        </Column>
        <Column field="tendered" header="Tendered">
          <template #body="slotProps">
            {{ slotProps.data.tendered !== null ? slotProps.data.tendered : 'None yet' }}
          </template>
        </Column>
        <Column field="change1" header="Change">
          <template #body="slotProps">
            {{ slotProps.data.change1 !== null ? Math.abs(slotProps.data.change1).toFixed(2) : 'None yet' }}
          </template>
        </Column>



        <Column field="status" header="Status" style="width: 2rem">
          <template #body="slotProps">
            <Tag :value="slotProps.data.status" :severity="getSeverity(slotProps.data.status)" />
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
              <Column field="menu_item_name" header="menu_item_id" sortable></Column>
              <Column field="quantity" header="Quantity" sortable>
              </Column>
              <Column field="subtotal" header="Subtotal" sortable></Column>
            </DataTable>
          </div>
        </template>

      </DataTable>
    </div>
  </div>
</template>
