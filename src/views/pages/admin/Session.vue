<script setup>
import { onMounted, reactive, ref, watch, computed } from "vue";
import { useToast } from "primevue/usetoast";
import { useConfirm } from "primevue/useconfirm";
import MenuService from "@/service/MenuService";
import CategoryService from "@/service/CategoryService";
import OrderService1 from "@/service/OrderService1";
import CustomerService2 from "@/service/CustomerService2";
import domtoimage from "dom-to-image-more";
import { jsPDF } from "jspdf";

import { useRouter } from "vue-router";
import { useRoute } from "vue-router";
const searchTerm = ref("");


const filteredMenuItems = computed(() => {
  let items = menuItems.value;

  // Filter by category if selected
  if (selectedCategory.value) {
    items = items.filter(item => item.category_id === selectedCategory.value);
  }

  // Filter by search term
  if (searchTerm.value) {
    items = items.filter(item =>
      item.name.toLowerCase().includes(searchTerm.value.toLowerCase())
    );
  }

  return items;
});

const receiptDiv = ref(null);


const downloadReceiptAsPDF = async () => {
  const content = document.getElementById('receiptPDF');

  try {
    const imgData = await domtoimage.toPng(content, {
      quality: 1.0,  // Maximum quality
      style: {
        transform: "scale(1)",  // Increase scale to enhance resolution
        transformOrigin: 'top left'
      }
    });
    const pdf = new jsPDF({
      orientation: 'p',
      unit: 'mm',
      format: [58, 100]
    });

    // Calculate the width and height to maintain aspect ratio
    const imgProps = pdf.getImageProperties(imgData);
    const pdfWidth = pdf.internal.pageSize.getWidth();
    const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

    pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
    pdf.save('test.pdf');
  } catch (error) {
    console.error('Error generating PDF', error);
  }
};

const printReceipt = () => {
  // Create a new window with a specific size
  const printWindow = window.open('', '_blank',);

  // Append only the receipt div to the new window
  printWindow.document.body.innerHTML = `<html><head><title>Receipt</title></head><body>${receiptDiv.value.innerHTML}</body></html>`;

  // Trigger the print process in the new window
  printWindow.print();
  printWindow.close();
}


const menu = ref();
const items = ref([
  {
    label: "Options",
    items: [
      {
        label: "Go to Order",
        icon: "pi pi-caret-right",
      },
      {
        label: "Cancel",
        icon: "pi pi-times",
      },
      {
        label: "Payment",
        icon: "pi pi-wallet",
      },
    ],
  },
]);
const toggle = (event) => {
  menu.value.toggle(event);
};
const paymentSuccessVisible = ref(false);
const route = useRoute();
const sessionId = ref(route.params.id);
const customers = ref();
const selectedCustomer = ref();
const selectedOrder = ref();
const orders = ref([]);
const orderDetails = ref([]);
const orderItems = ref([]);

const order = ref(null);
const selectedCategory = ref(null);
const selectedService = ref("Dine in");
const categories = ref([]);
const confirm = useConfirm();
const toast = useToast();
const showCalc = ref(true);
const viewMode = ref("grid");
const orderListVisible = ref(false);
const customerListVisible = ref(false);
const addNoteVisible = ref(false);
const paymentSidebar = ref(false);
const customerData = ref({
  customer_id: null,
});
const serviceData = ref({
  service: null,
});
const menuItemData = ref({
  menu_item_id: null,
  order_id: order,
  subtotal: 0,
  quantity: 1,
});

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

const customerPaymentData = ref({
  tendered: null,
  status: "",
});
const updateQuantity = async (item, event) => {
  const newQuantity = parseInt(event.target.value);
  if (!isNaN(newQuantity) && newQuantity >= 1) {
    item.quantity = newQuantity;
    item.subtotal =
      item.quantity * menuItems.value.find((menu) => menu.id === item.menu_item_id).price;
    await OrderService1.addMenuItemToOrder(item);
  }
};

const changeComputed = computed(() => {
  if (orderDetails.value && customerPaymentData.value.tendered) {
    return customerPaymentData.value.tendered - orderDetails.value.total_price;
  }
  return null;
});

const addMenuItemToOrder = async (menuId) => {
  try {
    console.log("hello", selectedCategory.value)
    if (order.value !== null) {
      const existingItem = orderItems.value.find((item) => item.menu_item_id === menuId);
      if (existingItem) {
        existingItem.quantity++;
        existingItem.subtotal =
          existingItem.quantity *
          menuItems.value.find((menu) => menu.id === menuId).price;
        await OrderService1.addMenuItemToOrder(existingItem);
      } else {
        menuItemData.value.menu_item_id = menuId;
        menuItemData.value.subtotal =
          menuItems.value.find((menu) => menu.id === menuId).price *
          menuItemData.value.quantity;
        await OrderService1.addMenuItemToOrder(menuItemData.value);
      }
      orderItems.value = await OrderService1.getOrderItems(order.value);
      orderDetails.value = await OrderService1.getOrderDetails(order.value);
      orders.value = await OrderService1.getAllSessionOrders(sessionId.value);
    } else {
      await createNewOrder();
      menuItemData.value.menu_item_id = menuId;
      menuItemData.value.subtotal =
        menuItems.value.find((menu) => menu.id === menuId).price *
        menuItemData.value.quantity;
      if (order.value !== null && menuItemData.value.menu_item_id !== null) {
        console.log("order", order.value);
        console.log("menu", menuItemData.value.menu_item_id);
        await OrderService1.addMenuItemToOrder(menuItemData.value);
        orderItems.value = await OrderService1.getOrderItems(order.value);
        orderDetails.value = await OrderService1.getOrderDetails(order.value);
        orders.value = await OrderService1.getAllSessionOrders(sessionId.value);
      }
    }
  } catch (error) {
    console.error("Error adding customer to order:", error);
    toast.add({
      severity: "error",
      summary: "Error",
      detail: "Please select a customer",
      life: 3000,
    });
  }
};

const increaseQuantity = async (item) => {
  item.quantity++;
  const price = menuItems.value.find((menu) => menu.id === item.menu_item_id).price;
  item.subtotal = (item.quantity * price).toFixed(2); // Convert to decimal with 2 decimal places

  try {
    await OrderService1.addMenuItemToOrder(item);
    orderDetails.value = await OrderService1.getOrderDetails(order.value);
  } catch (error) {
    console.error("Error updating order item:", error);
    toast.add({
      severity: "error",
      summary: "Error",
      detail: "Failed to update order item",
    });
  }
};

const decreaseQuantity = async (item) => {
  if (item.quantity > 1) {
    item.quantity--;
    const price = menuItems.value.find((menu) => menu.id === item.menu_item_id).price;
    item.subtotal = (item.quantity * price).toFixed(2); // Convert to decimal with 2 decimal places

    try {
      await OrderService1.addMenuItemToOrder(item);
      orderDetails.value = await OrderService1.getOrderDetails(order.value);
      orders.value = await OrderService1.getAllSessionOrders(sessionId.value);
    } catch (error) {
      console.error("Error updating order item:", error);
      toast.add({
        severity: "error",
        summary: "Error",
        detail: "Failed to update order item",
      });
    }
  }
};

const removeItem = async (item) => {
  if (item.quantity) {
    item.quantity = 0;
    item.subtotal =
      item.quantity * menuItems.value.find((menu) => menu.id === item.menu_item_id).price;
    try {
      await OrderService1.addMenuItemToOrder(item);
      orderItems.value = await OrderService1.getOrderItems(order.value);
      orderDetails.value = await OrderService1.getOrderDetails(order.value);
      orders.value = await OrderService1.getAllSessionOrders(sessionId.value);
    } catch (error) {
      console.error("Error updating order item:", error);
      toast.add({
        severity: "error",
        summary: "Error",
        detail: "Failed to update order item",
      });
    }
  }
};
const goToOrder2 = async (orderId) => {
  selectedOrder.value = orders.value.find((order) => order.id === orderId);
  order.value = orderId;
  orderListVisible.value = false;
  menuItems.value = await MenuService.getAllMenuItems();
  categories.value = await CategoryService.getAllCategories();
  customers.value = await CustomerService2.getAllCustomers();
  orderDetails.value = await OrderService1.getOrderDetails(order.value);
  orderItems.value = await OrderService1.getOrderItems(order.value);

  // orders.value = await OrderService1.getAllOrders();
  orders.value = await OrderService1.getAllSessionOrders(sessionId.value);
};

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

const goToOrder = async (orderId) => {
  order.value = orderId;
  orderListVisible.value = false;
  menuItems.value = await MenuService.getAllMenuItems();
  categories.value = await CategoryService.getAllCategories();
  customers.value = await CustomerService2.getAllCustomers();
  orderDetails.value = await OrderService1.getOrderDetails(order.value);
  orderItems.value = await OrderService1.getOrderItems(order.value);

  // orders.value = await OrderService1.getAllOrders();
  orders.value = await OrderService1.getAllSessionOrders(sessionId.value);
};
watch(selectedCustomer, (newValue, oldValue) => {
  console.log(newValue.id);
});

// const addMenuItemToOrder = (menuId) => {
//   console.log(`Menu with ID ${menuId} added to order`);
// };

const products = ref();
const menuItems = ref();
const changeViewMode = () => {
  viewMode.value = viewMode.value === "grid" ? "list" : "grid";
};

const newOrder = ref({
  session_id: sessionId,
});

const addCustomerToOrder = async () => {
  try {
    customerData.value.customer_id = selectedCustomer.value.id;
    const response = await OrderService1.addCustomerToOrder(
      order.value,
      customerData.value
    );
    console.log("Customer added to order:", response);
    toast.add({
      severity: "success",
      summary: "Success",
      detail: "Customer added to order successfully",
      life: 3000,
    });
    customerListVisible.value = false;
    orderDetails.value = await OrderService1.getOrderDetails(order.value);
    orders.value = await OrderService1.getAllSessionOrders(sessionId.value);
    orderItems.value = await OrderService1.getOrderItems(order.value);

    // selectedCustomer.value = customerData.value;
  } catch (error) {
    console.error("Error adding customer to order:", error);
    toast.add({
      severity: "error",
      summary: "Error",
      detail: "Please select a customer",
      life: 3000,
    });
  }
};

const changeService = async (serviceType) => {
  try {
    serviceData.value.service = serviceType;
    selectedService.value = serviceType;
    const response = await OrderService1.changeService(order.value, serviceData.value);
    toast.add({
      severity: "success",
      summary: "Success",
      detail: "Service changed successfully",
      life: 3000,
    });
    customerListVisible.value = false;
    orderDetails.value = await OrderService1.getOrderDetails(order.value);
    orders.value = await OrderService1.getAllSessionOrders(sessionId.value);
    orderItems.value = await OrderService1.getOrderItems(order.value);

    // selectedCustomer.value = customerData.value;
  } catch (error) {
    console.error("Error changing service:", error);
    toast.add({
      severity: "error",
      summary: "Error",
      detail: "Failed to change service",
      life: 3000,
    });
  }
};

const addCustomerPayment = async () => {
  try {
    customerPaymentData.value.status = "completed";
    const response = await OrderService1.addCustomerPayment(
      order.value,
      customerPaymentData.value
    );
    toast.add({
      severity: "success",
      summary: "Success",
      detail: "Service changed successfully",
      life: 3000,
    });
    customerListVisible.value = false;
    orderDetails.value = await OrderService1.getOrderDetails(order.value);
    orders.value = await OrderService1.getAllSessionOrders(sessionId.value);
    orderItems.value = await OrderService1.getOrderItems(order.value);
    paymentSidebar.value = false;
    paymentSuccessVisible.value = true;
    // selectedCustomer.value = customerData.value;
  } catch (error) {
    console.error("Error changing service:", error);
    toast.add({
      severity: "error",
      summary: "Error",
      detail: "Failed to change service",
      life: 3000,
    });
  }
};

const createNewOrder = async () => {
  try {
    const response = await OrderService1.createNewOrder(newOrder.value);
    console.log("New order created:", response);
    toast.add({
      severity: "success",
      summary: "Success",
      detail: "New order created successfully",
      life: 3000,
    });
    order.value = response.order_id;
    console.log(order.value);
    orderDetails.value = await OrderService1.getOrderDetails(order.value);
    orderItems.value = await OrderService1.getOrderItems(order.value);
    orders.value = await OrderService1.getAllSessionOrders(sessionId.value);
    paymentSuccessVisible.value = false;
  } catch (error) {
    console.error("Error creating new order:", error);
    toast.add({
      severity: "error",
      summary: "Error",
      detail: "Failed to create new order",
      life: 3000,
    });
  }
};

const requireConfirmation = (event) => {
  confirm.require({
    target: event.currentTarget,
    group: "headless",
    message: "Create a new order?",
    accept: async () => {
      try {
        createNewOrder();

        // Reset the form or any other necessary actions
        // Reset other necessary data
      } catch (error) {
        console.error("Error creating new order:", error);
      }
    },
    reject: () => { },
  });
};

const requireConfirmation2 = (event) => {
  confirm.require({
    target: event.currentTarget,
    group: "headless",
    message: "Hold current order?",
    accept: async () => {
      try {
        toast.add({
          severity: "warn",
          summary: "Success",
          detail: "Order put on hold",
          life: 3000,
        });
        // Reset the form or any other necessary actions
        order.value = null;
        // Reset other necessary data
      } catch (error) {
        console.error("Error creating new order:", error);
      }
    },
    reject: () => { },
  });
};
const requireConfirmation3 = (event) => {
  confirm.require({
    target: event.currentTarget,
    group: "headless",
    message: "Confirm payment information?",
    accept: async () => {
      try {
        toast.add({
          severity: "warn",
          summary: "Success",
          detail: "Order Paid",
          life: 3000,
        });
        addCustomerPayment(event);
      } catch (error) {
        console.error("Error creating new order:", error);
      }
    },
    reject: () => { },
  });
};
</script>

<template>
  <div class="surface-card p-2 shadow-2 border-round main-view">
    <div class="flex">
      <div style="height: 86vh; width: 500px"
        class="flex-auto border-2 border-dashed surface-border flex flex-wrap gap-3 p-2 align-content-start justify-content-around mr-2">
        <div class="col-12 flex p-0">
          <Toolbar class="w-full" style="border: none">
            <template #start>
              <div class="gap-2 flex">
                <Button icon="pi pi-filter-fill"> </Button>
                <IconField iconPosition="left">
                  <InputIcon>
                    <i class="pi pi-search" />
                  </InputIcon>
                  <InputText v-model="searchTerm" placeholder="Search menu items..." />
                </IconField>
                <Button @click="changeViewMode" :icon="viewMode === 'grid' ? 'pi pi-list' : 'pi pi-table'">
                </Button>
              </div>
            </template>
            <template #center> </template>
            <template #end>
              <div class="flex gap-2">
                <Button class="p-button-info" icon="pi pi-envelope" @click="addNoteVisible = true"
                  :disabled="order === null">
                </Button>
                <Button class="p-button-info" icon="pi pi-user-plus" @click="customerListVisible = true"
                  :disabled="order === null">
                </Button>
                <Button label="New Order" class="gap-2 font-bold" icon="pi pi-plus"
                  @click="requireConfirmation($event)">
                </Button>
                <!-- <Button
                  label="Payment"
                  class="p-button-success gap-2 font-bold"
                  icon="pi pi-wallet"
                >
                </Button> -->
              </div>
            </template>
          </Toolbar>
        </div>
        <div class="col-12 gap-3 flex justify-content-center p-0">

          <div class="categories-container pt-2 pb-3">
            <Button @click="selectedCategory = null" :outlined="selectedCategory !== null" rounded
              class="font-bold category-button">All</Button>

            <div v-for="category in categories" :key="category.id" class="category-button">
              <Button @click="selectedCategory = category.id" :outlined="selectedCategory !== category.id" rounded
                class="font-bold">
                {{ category.name }}
              </Button>
            </div>
          </div>
        </div>
        <template v-if="viewMode === 'grid'">
          <div style="height: 65vh; width: 500px"
            class="flex-auto surface-border flex flex-wrap gap-3 p-2 overflow-y-scroll align-content-start justify-content-around">
            <!-- Display sessions in grid view -->
            <Card v-for="menu in filteredMenuItems" :key="menu.id" style="
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
                  <img :src="menu.image" alt="Menu" style="height: 150px; max-width: 150px;  border-radius: 100%" />
                </div>
                <div class="text-center text-xl">{{ menu.name }}</div>
              </template>
              <template #subtitle>
                <div class="text-center">{{ menu.price }}<br />Available</div>
              </template>
            </Card>
          </div>
        </template>
      </div>
      <div style="height: 86vh; max-width:550px; width: 550px"
        class="flex-grow-1 border-2 border-dashed surface-border flex flex-wrap gap-3 p-2">
        <div class="text-2xl font-bold" style="width: 100%">
          <div class="flex gap-3 justify-content-between m-3">
            <div class="align-self-center">Order #{{ order }}</div>
            <Button label="Orders" class="p-button-info" icon="pi pi-folder" @click="orderListVisible = true">
            </Button>
          </div>
          <template v-if="order !== null">
            <div class="gap-3 flex mt-3 mb-2 justify-content-between px-3">
              <div class="gap-2 flex">
                <Button @click="changeService('Dine in')" :outlined="selectedService !== 'Dine in'" rounded
                  class="font-bold">Dine in</Button>
                <Button @click="changeService('Take out')" :outlined="selectedService !== 'Take out'" rounded
                  class="font-bold">Take out</Button>
              </div>
              <div class="text-lg text-400 align-self-center">
                {{ orderDetails.customer_name }}
              </div>
            </div>
            <div class="justify-content-center flex flex-row overflow-y-scroll" style="height: 60vh">
              <DataTable :value="orderItems" tableStyle="min-width: 387px">
                <Column field="menu_item_name" header="Item"></Column>
                <Column field="quantity" bodyStyle="text-align: center;">
                  <template #header>
                    <div class="text-center">Quantity</div>
                  </template>
                  <template #body="rowData">
                    <InputGroup>
                      <Button icon="pi pi-minus" severity="danger" outlined @click="decreaseQuantity(rowData.data)" />
                      <InputText style="width: 50px" :value="rowData.data.quantity"
                        @input="updateQuantity(rowData.data, $event)" />
                      <Button icon="pi pi-plus" severity="success" outlined @click="increaseQuantity(rowData.data)" />
                    </InputGroup>
                  </template>
                </Column>
                <Column field="subtotal" header="Subtotal"></Column>
                <Column>
                  <template #body="rowData">
                    <div class="flex gap-2">
                      <Button rounded icon="pi pi-times" severity="danger" @click="removeItem(rowData.data)" text />
                    </div>
                  </template>
                </Column>
              </DataTable>
            </div>
            <div class="flex justify-content-between items-center text-xl p-3 mb-2">
              <span>Total price:</span>
              <span class="font-italic text-1000">{{ orderDetails.total_price }}</span>
            </div>
            <div class="justify-content-end flex gap-2">
              <Button outlined label="Hold Order" icon="pi pi-window-minimize" severity="warning"
                @click="requireConfirmation2($event)"></Button>
              <Button @click="paymentSidebar = true" label="Proceed to Payment" :disabled="orderDetails.total_price <= 0 || orderDetails.total_price == null
                    " icon="pi pi-wallet" severity="success" class="mr-3"></Button>
            </div>
          </template>
        </div>
      </div>
    </div>
  </div>

  <Sidebar v-model:visible="paymentSidebar" position="right" class="" style="width: 65vw" :pt="{
                    root: 'border-none',
                    mask: {
                      style: 'backdrop-filter: blur(10px)',
                    },
                  }">
    <div class="flex h-full">
      <div style="height: 90vh; width: 30vw"
        class="flex-grow-1 border-2 border-dashed surface-border flex flex-wrap gap-3 p-2">
        <div class="text-2xl font-bold w-full">
          <div class="gap-3 justify-content-between m-3">
            <div class="text-4xl">Confirmation</div>
            <div class="text-2xl text-400 flex justify-content-between">
              Order #{{ order }}<span class="font-italic">Note</span>
            </div>
          </div>
          <Divider />

          <div class="justify-content-center flex flex-row overflow-y-scroll " style="height: 70vh; width: 100%;">
            <DataTable :value="orderItems" tableStyle="" class="w-full">
              <Column field="menu_item_name" header="Item" class="w-full"></Column>
              <Column field="quantity" bodyStyle="text-align: start;" header="Quantity" class="w-full">
              </Column>
              <Column field="subtotal" header="Subtotal" class="w-full"></Column>
            </DataTable>
          </div>
          <div class="flex justify-content-between items-center text-xl p-3">
            <span>Total:</span>
            <span class="font-italic text-1000 mr-8">{{ orderDetails.total_price }}</span>
          </div>
          <!-- <div class="flex justify-content-between items-center text-xl p-3">
            <span>Tax price:</span>
            <span class="font-italic text-400">P57.00</span>
          </div> -->
        </div>
      </div>

      <Divider layout="vertical" />

      <div style="height: 92vh; width: 30vw"
        class="flex-grow-1 border-2 border-dashed surface-border flex flex-wrap gap-3 p-2">
        <div class="text-2xl font-bold w-full">
          <div class="gap-3 justify-content-between m-3">
            <div class="text-4xl">Payment</div>
            <div class="text-2xl text-400 flex justify-content-between">
              {{ orderDetails.customer_name }}
              <span class="font-italic">
                {{ orderDetails.service }}
              </span>
              <!-- <span class="font-italic text-xl align-self-center"> <span class="text-900">Due:</span>   P2000</span> -->
            </div>
          </div>
          <Divider />
          <div class="gap-3 justify-content-between m-3">
            <div class="text-xl">Payment Method</div>
          </div>
          <div class="gap-3 flex mt-3">
            <Button iconPos="top" label="Cash" icon="pi pi-money-bill"></Button>
            <Button outlined iconPos="top" label="Bank" icon="pi pi-bitcoin"></Button>
            <Button outlined iconPos="top" label="Gcash" icon="pi pi-wallet"></Button>
            <Button outlined iconPos="top" label="Paypal" icon="pi pi-paypal"></Button>
          </div>
          <div class="gap-2 flex mt-5 text-lg mx-2">
            <InputGroup>
              <InputGroupAddon>P</InputGroupAddon>
              <FloatLabel>
                <InputNumber v-model="customerPaymentData.tendered" />
                <label>Amount Tendered</label>
              </FloatLabel>
            </InputGroup>

            <Button icon="pi pi-calculator" severity="info" @click="showCalc = !showCalc"></Button>
          </div>
          <template v-if="showCalc">
            <div class="justify-content-between flex flex-row flex-wrap" style="height: 50vh">
              <div class="gap-2 m-2 flex-1">
                <Button label="Exact" class="col-12"></Button>
                <div class="gap-1 flex mt-1 mr-2">
                  <Button label="1" class="col-3" outlined></Button>
                  <Button label="2" class="col-3" outlined></Button>
                  <Button label="3" class="col-3" outlined></Button>
                  <Button label="+10" class="col-3" outlined></Button>
                </div>
                <div class="gap-1 flex mt-1 mr-2">
                  <Button label="4" class="col-3" outlined></Button>
                  <Button label="5" class="col-3" outlined></Button>
                  <Button label="6" class="col-3" outlined></Button>
                  <Button label="+20" class="col-3" outlined></Button>
                </div>
                <div class="gap-1 flex mt-1 mr-2">
                  <Button label="7" class="col-3" outlined></Button>
                  <Button label="8" class="col-3" outlined></Button>
                  <Button label="9" class="col-3" outlined></Button>
                  <Button label="+50" class="col-3" outlined></Button>
                </div>
                <div class="gap-1 flex mt-1 mr-2">
                  <Button label="C" class="col-3" outlined></Button>
                  <Button label="0" class="col-3" outlined></Button>
                  <Button label="." class="col-3" outlined></Button>
                  <Button icon="pi pi-delete-left" class="col-3" outlined></Button>
                </div>
              </div>
            </div>
          </template>

          <template v-else>
            <div class="justify-content-between flex flex-row flex-wrap" style="height: 50vh"></div>
          </template>
          <div class="flex justify-content-between items-center text-xl p-3">
            <span>{{ changeComputed < 0 ? "Amount Due:" : "Change:" }}</span>
                <span class="font-italic text-1000">{{
                    changeComputed !== null ? changeComputed.toFixed(2) : ""
                  }}</span>
          </div>

          <div class="gap-3 flex mt-2">
            <Button style="width: 50%" @click="paymentSidebar = false" label="Return" severity="warning"
              outlined></Button>
            <Button style="width: 50%" label="Confirm Payment" icon="pi pi-wallet" severity="success"
              @click="requireConfirmation3($event)" :disabled="changeComputed < 0 || changeComputed == null"></Button>
          </div>
        </div>
      </div>
    </div>
  </Sidebar>

  <Dialog v-model:visible="orderListVisible" modal position="top" header="Order List" :style="{ width: '70rem' }">
    <Toolbar style="border: none">
      <template #start> </template>

      <template #center> </template>

      <template #end>
        <div class="gap-2 flex">
          <Button icon="pi pi-filter-fill"> </Button>

          <IconField iconPosition="left">
            <InputIcon>
              <i class="pi pi-search" />
            </InputIcon>
            <InputText placeholder="Search" />
          </IconField>
          <Button label="Go to Order" icon="pi pi-caret-right" @click="goToOrder(selectedOrder.id)"></Button>
        </div>
      </template>
    </Toolbar>

    <div class="border-2 border-dashed surface-border flex flex-wrap overflow-y-scroll p-2">
      <DataTable @row-dblclick="goToOrder2($event.data.id)" style="max-height: 60vh" :value="orders" dataKey="id"
        class="w-full" tableStyle="min-width: 60rem" v-model:expandedRows="expandedRows" selectionMode="single"
        v-model:selection="selectedOrder" stripedRows>
        <Column expander style="width: 4rem" />

        <!-- <Column expander style="width: 4rem" /> -->
        <Column field="id" header="#"> </Column>
        <Column field="customer_name" header="Customer"> </Column>
        <Column field="total_price" header="Total Price"> </Column>
        <Column field="service" header="Service"> </Column>
        <Column field="total_price" header="Total"> </Column>
        <template #expansion="slotProps">
          <div class="px-5 mx-5">
            <DataTable :value="slotProps.data.details" selectionMode="multiple">
              <Column field="menu_item_name" header="Item" sortable></Column>
              <Column field="quantity" header="Quantity" sortable> </Column>
              <Column field="subtotal" header="Subtotal" sortable></Column>
            </DataTable>
          </div>
        </template>
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
      </DataTable>
    </div>
  </Dialog>

  <ConfirmPopup group="headless">
    <template #container="{ message, acceptCallback, rejectCallback }">
      <div class="border-round p-3">
        <span>{{ message.message }}</span>
        <div class="flex align-items-center gap-2 mt-3">
          <Button label="Yes" @click="acceptCallback" size="small"></Button>
          <Button label="Cancel" outlined @click="rejectCallback" severity="secondary" size="small" text></Button>
        </div>
      </div>
    </template>
  </ConfirmPopup>

  <Dialog v-model:visible="customerListVisible" modal position="top" header="Customer List" :style="{ width: '70rem' }">
    <Toolbar style="border: none">
      <template #start> </template>

      <template #center> </template>

      <template #end>
        <div class="gap-2 flex">
          <Button icon="pi pi-filter-fill"> </Button>

          <IconField iconPosition="left">
            <InputIcon>
              <i class="pi pi-search" />
            </InputIcon>
            <InputText placeholder="Search" />
          </IconField>
          <Button label="New" icon="pi pi-plus" severity="success"></Button>
          <Button icon="pi pi-caret-right" label="Add Customer to Order"
            @click="addCustomerToOrder(order, id)"></Button>
        </div>
      </template>
    </Toolbar>

    <div class="border-2 border-dashed surface-border flex flex-wrap gap-3 overflow-y-scroll p-2">
      <DataTable :value="customers" dataKey="id" class="w-full" tableStyle="min-width: 60rem" selectionMode="single"
        v-model:selection="selectedCustomer" stripedRows>
        <Column field="name" header="Name"> </Column>
        <Column field="email" header="Email Address"></Column>
        <Column field="phone" header="Phone Number"> </Column>
      </DataTable>
    </div>
  </Dialog>

  <Dialog v-model:visible="addNoteVisible" modal header="Add Note" :style="{ width: '25rem' }">
    <div class="flex justify-content-center mb-3">
      <FloatLabel>
        <Textarea v-model="value" rows="3" cols="30" autoResize class="w-23rem" />
        <label>Note</label>
      </FloatLabel>
    </div>
    <div class="flex justify-content-end gap-2">
      <Button type="button" label="Cancel" severity="secondary" @click="addNoteVisible = false"></Button>
      <Button type="button" label="Add" @click="addNoteVisible = false"></Button>
    </div>
  </Dialog>

  <Dialog v-model:visible="paymentSuccessVisible" modal :closable="false" :style="{ width: '30rem' }" :pt="{
                    root: 'border-none',
                    mask: {
                      style: 'backdrop-filter: blur(4px)',
                    },
                  }">
    <template #header>
      <div class="flex align-items-center surface-overlay border-round justify-content-center">
        <div class="border-circle bg-primary inline-flex justify-content-center align-items-center h-3rem w-3rem">
          <i class="pi pi-check text-2xl"></i>
        </div>
      </div>
      <span class="font-bold text-2xl block justify-content-center text-center">
        Payment Successful
      </span>
    </template>

    <span class="p-text-secondary block mb-5">What do you want to do with the receipt?</span>
    <div class="flex align-items-center gap-3 mb-2">
      <InputGroup>
        <InputText id="email" v-model="orderDetails.customer_email" class="flex-auto" autocomplete="off" />
        <Button icon="pi pi-send" label="Email" iconPos="right" />
      </InputGroup>
    </div>
    <div class="flex align-items-center gap-3 mb-2">
      <Button icon="pi pi-download" label="Download" iconPos="right" class="w-full" severity="info"
        @click="downloadReceiptAsPDF" />
      <Button icon="pi pi-print" label="Print" iconPos="right" class="w-full" severity="help" @click="printReceipt" />

    </div>
    <template #footer>
      <Button label="New Order" class="w-full" @click="createNewOrder" />
    </template>
  </Dialog>


  <div
    style="background-color:white; margin: 8px; padding: 1rem;  color: black; position: absolute; left: -10000px; top: -10000px;"
    id="receipt" ref="receiptDiv">
    <div style="font-weight: bold; padding-top: 1rem;  font-size: .75rem; text-align: center;">
      Anahaw Island <br> ☀️View Resort
    </div>
    <div style=" text-align: center;  font-size: .50rem;margin-bottom: 1rem;">
      Anahaw Address, Calapan, Oriental Mindoro <br>
      (+63)900-000-0000 <br>
      AnahawEmail@email.com <br>
    </div>
    <div style=" text-align: center;  font-size: .50rem;">
      Order # {{ order }}
    </div>
    <hr style="margin: 0;">

    <!-- Loop ReceiptItems from here -->
    <div v-for="(item, index) in orderItems" :key="index">
      <div style=" display: flex; justify-content: space-between;">
        <div style="flex-basis: 50%; font-size: .50rem; text-align: left; ">
          <span style="font-weight: bold;">
            {{ item.menu_item_name }}
          </span>
          <div style="font-style: italic;  font-size: .50rem;">
            {{ item.quantity }} x {{ item.menu_item_price }}
          </div>
        </div>
        <div
          style="flex-basis: 50%; text-align: right; font-size: .75rem; display: flex; align-items: center; justify-content: flex-end;">
          Php {{ item.subtotal }}
        </div>
      </div>
    </div>
    <hr> <!-- Loop ReceiptItems to here -->
    <div style=" display: flex; justify-content: space-between;">
      <div style="flex-basis: 50%; font-size: .85rem;font-weight: bold; text-align: right;">TOTAL:
      </div>
      <div style="flex-basis: 100%; text-align: right; font-size: .75rem; font-weight: bold;">Php {{
                    orderDetails.total_price }} </div>
    </div>
    <div style="font-weight: 100%; display: flex; justify-content: space-between;">
      <div style="flex-basis: 100%; font-size: .50rem; font-weight: bold; text-align: right;">TENDERED:
      </div>
      <div style="flex-basis: 100%; text-align: right; font-size: .50rem;">Php {{ orderDetails.tendered }}
      </div>
    </div>
    <div style=" display: flex; justify-content: space-between;">
      <div style="flex-basis: 100%; font-size: .50rem; text-align: right; font-weight: bold;">CHANGE:
      </div>
      <div style="flex-basis: 100%; text-align: right; font-size: .50rem;">
        Php {{ Math.abs(orderDetails.change1).toFixed(2) }}
      </div>
    </div>
    <hr>
    <div style="padding-left: 0rem;  font-size: .50rem;text-align: start;">
      Served by: {{ orderDetails.cashier_name }}
    </div>
    <div style="padding-left: 0rem;  font-size: .50rem;text-align: start;">
      Customer: {{ orderDetails.customer_name }}
    </div>
    <div style="padding-left: 0rem;  font-size: .50rem;text-align: start;">
      {{ orderDetails.order_date }}
    </div>
  </div>

  <div
    style="background-color:white; margin: 8px; padding: 50px; padding-left: 200px;padding-right: 200px; color: black; position: absolute; left: -10000px; top: -10000px;"
    id="receiptPDF">
    <div style="font-weight: bold; padding-top: 1rem; padding-left: 1rem; font-size: 2rem; text-align: center;">
      Anahaw Island <br> ☀️View Resort
    </div>
    <div style="padding-left: 1rem; text-align: center;  font-size: 2rem;margin-bottom: 1rem;">
      Anahaw Address, Calapan, Oriental Mindoro <br>
      (+63)900-000-0000 <br>
      AnahawEmail@email.com <br>
    </div>
    <hr style="margin: 0;">
    <div style="padding-left: 1rem;  font-size: 2rem;text-align: center;">
      Served by: {{ orderDetails.cashier_name }}
    </div>
    <!-- Loop ReceiptItems from here -->
    <div v-for="(item, index) in orderItems" :key="index">
      <div style=" display: flex; justify-content: space-between;">
        <div style="flex-basis: 50%; font-size: 2rem; text-align: left; ">
          <span style="font-weight: bold;">
            {{ item.menu_item_name }}
          </span>
          <div style="font-style: italic;  font-size: 2rem;">
            {{ item.quantity }} x {{ item.menu_item_price }}
          </div>
        </div>
        <div
          style="flex-basis: 50%; text-align: right; font-size: 2rem; display: flex; align-items: center; justify-content: flex-end;">
          Php {{ item.subtotal }}
        </div>
      </div>
    </div>
    <hr> <!-- Loop ReceiptItems to here -->
    <div style=" display: flex; justify-content: space-between;">
      <div style="flex-basis: 50%; font-size: 2rem;font-weight: bold; text-align: right;">TOTAL:
      </div>
      <div style="flex-basis: 100%; text-align: right; font-size: 2rem; font-weight: bold;">Php {{
                    orderDetails.total_price }} </div>
    </div>
    <div style="font-weight: 100%; display: flex; justify-content: space-between;">
      <div style="flex-basis: 100%; font-size: 2rem; font-weight: bold; text-align: right;">TENDERED:
      </div>
      <div style="flex-basis: 100%; text-align: right; font-size: 2rem;">Php {{ orderDetails.tendered }}
      </div>
    </div>
    <div style=" display: flex; justify-content: space-between;">
      <div style="flex-basis: 100%; font-size: 2rem; text-align: right; font-weight: bold;">CHANGE:
      </div>
      <div style="flex-basis: 100%; text-align: right; font-size: 2rem;">
        Php {{ Math.abs(orderDetails.change1).toFixed(2) }}
      </div>
    </div>
  </div>


</template>
<style scoped>
.categories-container {
  display: flex;
  overflow-x: auto;
  /* Enables horizontal scrolling */
  white-space: nowrap;
  /* Prevents items from wrapping to the next line */
}

.category-button {
  flex: none;
  /* Prevents the button from stretching to fill the space */
  margin-right: 8px;
  /* Adds spacing between buttons */
}
</style>
