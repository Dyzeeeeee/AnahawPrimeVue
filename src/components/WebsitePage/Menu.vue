<template>
  <div class="text-900 font-bold text-6xl mb-4 text-center">Our Menu</div>
  <div class="text-700 text-xl mb-6 text-center line-height-3">
    Lorem ipsum dolor sit, amet consectetur adipisicing elit. Velit numquam eligendi
    quos.
  </div>

  <div style="height: 58vh; "
    class="flex-auto surface-border flex flex-wrap gap-3 p-2 overflow-y-scroll align-content-start justify-content-around">
    <!-- Display sessions in grid view -->
    <Card v-for="menu in menuItems" :key="menu.id" style="
                cursor: pointer;
                width: 30%;
                max-height: 20rem;
                height: auto;
                min-width: 12rem;
                overflow: hidden;
              " class="shadow-6" @click="addMenuItemToOrder(menu.id)">
      <template #header> </template>
      <template #title>
        <div class="justify-content-center flex" style="overflow: visible">
          <img src="@/assets/images/sisig.jpg" alt="Menu" style="height: 150px; border-radius: 100%" />
        </div>
        <div class="text-center text-xl">{{ menu.name }}</div>
      </template>
      <template #subtitle>
        <div class="text-center">{{ menu.price }}<br />Available</div>
      </template>
    </Card>
  </div>
</template>

<script setup>
import { onMounted, reactive, ref, watch, computed } from "vue";
import MenuService from "@/service/MenuService";

onMounted(async () => {
  try {
    menuItems.value = await MenuService.getAllMenuItems();
    categories.value = await CategoryService.getAllCategories();
    customers.value = await CustomerService2.getAllCustomers();
    orders.value = await OrderService1.getAllSessionOrders(sessionId.value);
  } catch (error) {
    console.error("Error fetching data:", error);
    toast.add({
      severity: "error",
      summary: "Error",
      detail: "Failed to fetch menu items",
    });
  }
});
const menuItems = ref();

</script>
