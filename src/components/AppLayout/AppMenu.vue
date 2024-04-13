<script setup>
import { ref } from "vue";

import AppMenuItem from "./AppMenuItem.vue";

const model = ref([
  {
    label: "Home",
    items: [
      { label: "Dashboard", icon: "pi pi-fw pi-chart-bar", to: "/admin/dashboard" },
    ],
  },
  {
    label: "Restaurant",
    items: [
      { label: "Counter", icon: "pi pi-fw pi-calculator", to: "/admin/counter" },
      { label: "Orders", icon: "pi pi-fw pi-bell", to: "/admin/orders" },
      { label: "Menu", icon: "pi pi-fw pi-image", to: "/admin/Menu" },
      { label: "Customers", icon: "pi pi-fw pi-id-card", to: "/admin/customers" },
    ],
  },
  {
    label: "Inventory",
    items: [
      // { label: "Suppliers", icon: "pi pi-fw pi-shopping-cart", to: "/admin/suppliers" },
      { label: "Food Stocks", icon: "pi pi-fw pi-chart-line", to: "/admin/food-stocks" },
      // { label: "Resort Items", icon: "pi pi-fw pi-box", to: "/admin/resort-items" },
    ],
  },
  {
    label: "Resort",
    items: [
      { label: "Rooms", icon: "pi pi-fw pi-home", to: "/admin/rooms" },
      { label: "Guests", icon: "pi pi-fw pi-users", to: "/admin/guests" },
      {
        label: "Reservations",
        icon: "pi pi-fw pi-question-circle",
        to: "/admin/reservations",
      },
    ],
  },
  {
    label: "Others",
    items: [
      { label: "Employee", icon: "pi pi-fw pi-id-card", to: "/admin/employee" },
      { label: "Accounts", icon: "pi pi-fw pi-user-edit", to: "/admin/accounts" },
    ],
  },
]);
</script>

<template>
  <div class="w-10rem mb-5">
    <router-link to="/website/landing">
      <img src="@/assets/images/logo.png" alt="logo" class="w-14rem" />
    </router-link>
  </div>

  <ul class="layout-menu">
    <template v-for="(item, i) in model" :key="item">
      <app-menu-item v-if="!item.separator" :item="item" :index="i"></app-menu-item>
      <li v-if="item.separator" class="menu-separator"></li>
    </template>
  </ul>
</template>

<style>
.layout-menu {
  height: 84vh; /* Adjust the height as needed */
  overflow-y: auto; /* This will add a scrollbar if the content overflows */
}

/* This styles the scrollbar track */
.layout-menu::-webkit-scrollbar-track {
  background-color: green; /* Background of the scrollbar track */
}

/* This styles the scrollbar handle */
.layout-menu::-webkit-scrollbar {
  width: 1px; /* Width of the scrollbar */
  background-color: green; /* Background of the scrollbar itself */
}

.layout-menu::-webkit-scrollbar-thumb {
  background-color: green; /* Color of the scrollbar handle */
  border-radius: 2px; /* Rounded corners on the scrollbar handle */
}

/* Styles for Firefox */
.layout-menu {
  scrollbar-width: thin; /* "auto" or "thin" */
  scrollbar-color: #e6ad20 #f4f4f4; /* Thumb and track color */
}
</style>
