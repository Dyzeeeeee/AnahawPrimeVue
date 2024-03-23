<script setup>
import { onMounted, reactive, ref, watch } from "vue";
import { ProductService } from "@/service/ProductService";
import { useToast } from "primevue/usetoast";
import { FilterMatchMode } from "primevue/api";
const products = ref();
const expandedRows = ref([]);
const toast = useToast();

onMounted(() => {
  ProductService.getProductsWithOrdersSmall().then((data) => (products.value = data));
});

const onRowExpand = (event) => {
  toast.add({
    severity: "info",
    summary: "Product Expanded",
    detail: event.data.name,
    life: 3000,
  });
};
const onRowCollapse = (event) => {
  toast.add({
    severity: "success",
    summary: "Product Collapsed",
    detail: event.data.name,
    life: 3000,
  });
};
const expandAll = () => {
  expandedRows.value = products.value.reduce((acc, p) => (acc[p.id] = true) && acc, {});
};
const collapseAll = () => {
  expandedRows.value = null;
};
const formatCurrency = (value) => {
  return value.toLocaleString("en-US", { style: "currency", currency: "USD" });
};
const getSeverity = (product) => {
  switch (product.inventoryStatus) {
    case "INSTOCK":
      return "success";

    case "LOWSTOCK":
      return "warning";

    case "OUTOFSTOCK":
      return "danger";

    default:
      return null;
  }
};
const getOrderSeverity = (order) => {
  switch (order.status) {
    case "DELIVERED":
      return "success";

    case "CANCELLED":
      return "danger";

    case "PENDING":
      return "warning";

    case "RETURNED":
      return "info";

    default:
      return null;
  }
};

const selectedFilter = ref({ name: "Active" });
const filters = ref([{ name: "Active" }, { name: "Archived" }, { name: "All" }]);

const filterse = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
  name: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  "country.name": { value: null, matchMode: FilterMatchMode.STARTS_WITH },
  representative: { value: null, matchMode: FilterMatchMode.IN },
  status: { value: null, matchMode: FilterMatchMode.EQUALS },
  verified: { value: null, matchMode: FilterMatchMode.EQUALS },
});
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
          <Button @click="changeViewMode" :icon="viewMode === 'grid' ? 'pi pi-list' : 'pi pi-table'">
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
          <!-- <Button class="p-button-info" icon="pi pi-inbox"></Button> -->
        </div>
      </template>
    </Toolbar>
    <div style="height: 65vh" class="border-2 border-dashed surface-border flex flex-wrap gap-3 overflow-y-scroll p-2">
      <DataTable filterDisplay="row" :globalFilterFields="['name', 'country.name', 'representative.name', 'status']"
        v-model:filters="filterse" v-model:expandedRows="expandedRows" :value="products" dataKey="id"
        @rowExpand="onRowExpand" @rowCollapse="onRowCollapse" class="w-full" tableStyle="min-width: 60rem">
        <template #header>
          <div class="flex flex-wrap justify-content-end gap-2">
            <Button text icon="pi pi-plus" label="Expand All" @click="expandAll" />
            <Button text icon="pi pi-minus" label="Collapse All" @click="collapseAll" />
          </div>
        </template>
        <Column expander style="width: 5rem" />
        <Column field="name" header="Name">
          <template #filter="{ filterModel, filterCallback }">
            <InputText v-model="filterModel.value" type="text" @input="filterCallback()" class="p-column-filter"
              placeholder="Search by name" />
          </template>
        </Column>
        <Column header="Image">
          <template #body="slotProps">
            <img :src="`https://primefaces.org/cdn/primevue/images/product/${slotProps.data.image}`"
              :alt="slotProps.data.image" class="shadow-4" width="64" />
          </template>
        </Column>
        <Column field="price" header="Price">
          <template #body="slotProps">
            {{ formatCurrency(slotProps.data.price) }}
          </template>
        </Column>
        <Column field="category" header="Category"></Column>
        <Column field="rating" header="Reviews">
          <template #body="slotProps">
            <Rating :modelValue="slotProps.data.rating" readonly :cancel="false" />
          </template>
        </Column>
        <Column header="Status">
          <template #body="slotProps">
            <Tag :value="slotProps.data.inventoryStatus" :severity="getSeverity(slotProps.data)" />
          </template>
        </Column>
        <template #expansion="slotProps">
          <div class="p-3">
            <h5>Orders for {{ slotProps.data.name }}</h5>
            <DataTable :value="slotProps.data.orders">
              <Column field="id" header="Id" sortable></Column>
              <Column field="customer" header="Customer" sortable></Column>
              <Column field="date" header="Date" sortable></Column>
              <Column field="amount" header="Amount" sortable>
                <template #body="slotProps">
                  {{ formatCurrency(slotProps.data.amount) }}
                </template>
              </Column>
              <Column field="status" header="Status" sortable>
                <template #body="slotProps">
                  <Tag :value="slotProps.data.status.toLowerCase()" :severity="getOrderSeverity(slotProps.data)" />
                </template>
              </Column>
              <Column headerStyle="width:4rem">
                <template #body>
                  <Button icon="pi pi-search" />
                </template>
              </Column>
            </DataTable>
          </div>
        </template>
      </DataTable>
    </div>
  </div>
</template>
