<script setup>
import { ref } from "vue";

import AppMenuItem from "./AppMenuItem.vue";

const model = ref([
  {
    label: "Home",
    items: [{ label: "Dashboard", icon: "pi pi-fw pi-home", to: "/admin/dashboard" }],
  },
  {
    label: "Restaurant",
    items: [
      { label: "Counter", icon: "pi pi-fw pi-id-card", to: "/admin/counter" },
      { label: "Orders", icon: "pi pi-fw pi-id-card", to: "/admin/orders" },
      { label: "Menu", icon: "pi pi-fw pi-id-card", to: "/admin/Menu" },
      { label: "Customers", icon: "pi pi-fw pi-id-card", to: "/admin/customers" },
    ],
  },
  {
    label: "Inventory",
    items: [
      { label: "Suppliers", icon: "pi pi-fw pi-id-card", to: "/admin/suppliers" },
      { label: "Food Stocks", icon: "pi pi-fw pi-id-card", to: "/admin/food-stocks" },
      { label: "Resort Items", icon: "pi pi-fw pi-id-card", to: "/admin/resort-items" },
    ],
  },
  {
    label: "Resort",
    items: [
      { label: "Rooms", icon: "pi pi-fw pi-id-card", to: "/admin/rooms" },
      { label: "Guests", icon: "pi pi-fw pi-id-card", to: "/admin/guests" },
      { label: "Reservations", icon: "pi pi-fw pi-id-card", to: "/admin/reservations" },
    ],
  },
  {
    label: "Others",
    items: [
      { label: "Employee", icon: "pi pi-fw pi-id-card", to: "/admin/employee" },
      { label: "Accounts", icon: "pi pi-fw pi-id-card", to: "/admin/accounts" },
    ],
  },
]);
</script>

<template>
  <ul class="layout-menu">
    <template v-for="(item, i) in model" :key="item">
      <app-menu-item v-if="!item.separator" :item="item" :index="i"></app-menu-item>
      <li v-if="item.separator" class="menu-separator"></li>
    </template>
  </ul>
</template>

<style lang="scss" scoped></style>
