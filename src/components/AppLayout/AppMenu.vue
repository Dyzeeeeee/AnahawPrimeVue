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
      { label: "Menu", icon: "pi pi-fw pi-id-card", to: "/uikit/formlayout" },
      { label: "Customers", icon: "pi pi-fw pi-id-card", to: "/uikit/formlayout" },
    ],
  },
  {
    label: "Inventory",
    items: [
      { label: "Suppliers", icon: "pi pi-fw pi-id-card", to: "/uikit/formlayout" },
      { label: "Food Stocks", icon: "pi pi-fw pi-id-card", to: "/uikit/formlayout" },
      { label: "Resort Items", icon: "pi pi-fw pi-id-card", to: "/uikit/formlayout" },
    ],
  },
  {
    label: "Resort",
    items: [
      { label: "Rooms", icon: "pi pi-fw pi-id-card", to: "/uikit/formlayout" },
      { label: "Guests", icon: "pi pi-fw pi-id-card", to: "/uikit/formlayout" },
      { label: "Reservations", icon: "pi pi-fw pi-id-card", to: "/uikit/formlayout" },
    ],
  },
  {
    label: "Others",
    items: [
      { label: "Employee", icon: "pi pi-fw pi-id-card", to: "/uikit/formlayout" },
      { label: "Accounts", icon: "pi pi-fw pi-id-card", to: "/uikit/formlayout" },
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
