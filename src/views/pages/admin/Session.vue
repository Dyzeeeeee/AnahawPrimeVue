<script setup>
import { onMounted, reactive, ref, watch } from "vue";
import { useToast } from "primevue/usetoast";
import { useConfirm } from "primevue/useconfirm";
import MenuService from "@/service/MenuService";
import CategoryService from "@/service/CategoryService";
import OrderService1 from "@/service/OrderService1";
import CustomerService2 from "@/service/CustomerService2";

import { useRouter } from 'vue-router';
import { useRoute } from 'vue-router';

const route = useRoute();
const sessionId = ref(route.params.id);
const customers = ref();
const selectedCustomer = ref();
const selectedOrder = ref();
const orders = ref([]);
const orderDetails = ref([]);
const orderItems = ref([]);

const order = ref(null);
const selectedCategory = ref('All');
const selectedService = ref('Dine in');
const categories = ref([]);
const confirm = useConfirm();
const toast = useToast();
const showCalc = ref(false);
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
  quantity: 1
});
const updateQuantity = async (item, event) => {
  const newQuantity = parseInt(event.target.value);
  if (!isNaN(newQuantity) && newQuantity >= 1) {
    item.quantity = newQuantity;
    item.subtotal = item.quantity * menuItems.value.find(menu => menu.id === item.menu_item_id).price;
    await OrderService1.addMenuItemToOrder(item);
  }
};
const addMenuItemToOrder = async (menuId) => {
  try {
    if (order.value !== null) {
      const existingItem = orderItems.value.find(item => item.menu_item_id === menuId);
      if (existingItem) {
        existingItem.quantity++;
        existingItem.subtotal = existingItem.quantity * menuItems.value.find(menu => menu.id === menuId).price;
        await OrderService1.addMenuItemToOrder(existingItem);
      } else {
        menuItemData.value.menu_item_id = menuId;
        menuItemData.value.subtotal = menuItems.value.find(menu => menu.id === menuId).price * menuItemData.value.quantity;
        await OrderService1.addMenuItemToOrder(menuItemData.value);
      }
      orderItems.value = await OrderService1.getOrderItems(order.value);
      orderDetails.value = await OrderService1.getOrderDetails(order.value);
      orders.value = await OrderService1.getAllSessionOrders(sessionId.value);
    }
    else {
      await createNewOrder();
      menuItemData.value.menu_item_id = menuId;
      menuItemData.value.subtotal = menuItems.value.find(menu => menu.id === menuId).price * menuItemData.value.quantity;
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
    console.error('Error adding customer to order:', error);
    toast.add({ severity: 'error', summary: 'Error', detail: 'Please select a customer', life: 3000 });
  }
};


const increaseQuantity = async (item) => {
  item.quantity++;
  item.subtotal = item.quantity * menuItems.value.find(menu => menu.id === item.menu_item_id).price;
  try {
    await OrderService1.addMenuItemToOrder(item);
    orderDetails.value = await OrderService1.getOrderDetails(order.value);

  } catch (error) {
    console.error('Error updating order item:', error);
    toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to update order item' });
  }
};

const decreaseQuantity = async (item) => {
  if (item.quantity > 1) {
    item.quantity--;
    item.subtotal = item.quantity * menuItems.value.find(menu => menu.id === item.menu_item_id).price;
    try {
      await OrderService1.addMenuItemToOrder(item);
      orderDetails.value = await OrderService1.getOrderDetails(order.value);
      orders.value = await OrderService1.getAllSessionOrders(sessionId.value);

    } catch (error) {
      console.error('Error updating order item:', error);
      toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to update order item' });
    }
  }
};
const removeItem = async (item) => {
  if (item.quantity) {
    item.quantity = 0;
    item.subtotal = item.quantity * menuItems.value.find(menu => menu.id === item.menu_item_id).price;
    try {
      await OrderService1.addMenuItemToOrder(item);
      orderItems.value = await OrderService1.getOrderItems(order.value);
      orderDetails.value = await OrderService1.getOrderDetails(order.value);
      orders.value = await OrderService1.getAllSessionOrders(sessionId.value);


    } catch (error) {
      console.error('Error updating order item:', error);
      toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to update order item' });
    }
  }
};

onMounted(async () => {
  try {
    menuItems.value = await MenuService.getAllMenuItems();
    categories.value = await CategoryService.getAllCategories();
    customers.value = await CustomerService2.getAllCustomers();
    orders.value = await OrderService1.getAllSessionOrders(sessionId.value);

  } catch (error) {
    console.error('Error fetching data:', error);
    toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to fetch menu items' });
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
  session_id: sessionId
});


const addCustomerToOrder = async () => {
  try {
    customerData.value.customer_id = selectedCustomer.value.id;
    const response = await OrderService1.addCustomerToOrder(order.value, customerData.value);
    console.log('Customer added to order:', response);
    toast.add({ severity: 'success', summary: 'Success', detail: 'Customer added to order successfully', life: 3000 });
    customerListVisible.value = false
    orderDetails.value = await OrderService1.getOrderDetails(order.value);
    orders.value = await OrderService1.getAllSessionOrders(sessionId.value);
    orderItems.value = await OrderService1.getOrderItems(order.value);

    // selectedCustomer.value = customerData.value;
  } catch (error) {
    console.error('Error adding customer to order:', error);
    toast.add({ severity: 'error', summary: 'Error', detail: 'Please select a customer', life: 3000 });
  }
};

const changeService = async (serviceType) => {
  try {
    serviceData.value.service = serviceType;
    selectedService.value = serviceType;
    const response = await OrderService1.changeService(order.value, serviceData.value);
    toast.add({ severity: 'success', summary: 'Success', detail: 'Service changed successfully', life: 3000 });
    customerListVisible.value = false
    orderDetails.value = await OrderService1.getOrderDetails(order.value);
    orders.value = await OrderService1.getAllSessionOrders(sessionId.value);
    orderItems.value = await OrderService1.getOrderItems(order.value);

    // selectedCustomer.value = customerData.value;
  } catch (error) {
    console.error('Error changing service:', error);
    toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to change service', life: 3000 });
  }
};

const createNewOrder = async () => {
  try {
    const response = await OrderService1.createNewOrder(newOrder.value);
    console.log('New order created:', response);
    toast.add({ severity: 'success', summary: 'Success', detail: 'New order created successfully', life: 3000 });
    order.value = response.order_id;
    console.log(order.value);
    orderDetails.value = await OrderService1.getOrderDetails(order.value);
    orderItems.value = await OrderService1.getOrderItems(order.value);
    orders.value = await OrderService1.getAllSessionOrders(sessionId.value);

  } catch (error) {
    console.error('Error creating new order:', error);
    toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to create new order', life: 3000 });
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
        console.error('Error creating new order:', error);
      }
    },
    reject: () => {

    },
  });
};

const requireConfirmation2 = (event) => {
  confirm.require({
    target: event.currentTarget,
    group: "headless",
    message: "Hold current order?",
    accept: async () => {
      try {
        toast.add({ severity: 'warn', summary: 'Success', detail: 'Order put on hold', life: 3000 });
        // Reset the form or any other necessary actions
        order.value = null;
        // Reset other necessary data
      } catch (error) {
        console.error('Error creating new order:', error);
      }
    },
    reject: () => {

    },
  });
};
</script>

<template>
  <div class="surface-card p-2 shadow-2 border-round main-view">
    <div class="flex">
      <div style="height: 80vh; width: 500px"
        class="flex-auto border-2 border-dashed  surface-border flex flex-wrap gap-3 p-2 align-content-start justify-content-around mr-2">
        <div class="col-12 flex p-0">
          <Toolbar class="w-full" style="border: none">
            <template #start>
              <div class="gap-2 flex">
                <Button icon="pi pi-filter-fill"> </Button>
                <IconField iconPosition="left">
                  <InputIcon>
                    <i class="pi pi-search" />
                  </InputIcon>
                  <InputText placeholder="Search" />
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
          <Button @click="selectedCategory = 'All'" :outlined="selectedCategory !== 'All'" rounded
            class="font-bold">All</Button>
          <div v-for="category in categories" :key="category.id">
            <Button @click="selectedCategory = category.name" :outlined="selectedCategory !== category.name" rounded
              class="font-bold">{{ category.name }}</Button>
          </div>
        </div>
        <template v-if="viewMode === 'grid'">
          <div style="height: 60vh; width: 500px"
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
                <div class="text-center">
                  {{ menu.price }}<br />Available
                </div>
              </template>
            </Card>
          </div>
        </template>
      </div>
      <div style="height: 80vh; max-width: 450px; width: 450px"
        class="flex-grow-1 border-2 border-dashed surface-border flex flex-wrap gap-3 p-2">
        <div class="text-2xl font-bold" style="width: 450px">
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
              <div class="text-lg text-400 align-self-center">{{ orderDetails.customer_name }}</div>
            </div>
            <div class="justify-content-center flex flex-row overflow-y-scroll" style="height: 50vh">
              <DataTable :value="orderItems" tableStyle="min-width: 387px">
                <Column field="menu_item_name" header="Item"></Column>
                <Column field="quantity" bodyStyle="text-align: center;">
                  <template #header>
                    <div class="text-center ">Quantity</div>
                  </template>
                  <template #body="rowData">
                    <InputGroup>
                      <Button icon="pi pi-minus" severity="danger" outlined @click="decreaseQuantity(rowData.data)" />
                      <InputText style="width: 50px;" :value="rowData.data.quantity"
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
            <div class="flex justify-content-between items-center text-xl p-3">
              <span>Total price:</span>
              <span class="font-italic text-500">{{ orderDetails.total_price }}</span>
            </div>
            <div class="justify-content-end flex gap-2">
              <Button outlined label="Hold Order" icon="pi pi-window-minimize" severity="warning"
                @click="requireConfirmation2($event)"></Button>
              <Button @click="paymentSidebar = true" label="Proceed to Payment" icon="pi pi-wallet"
                severity="success"></Button>
            </div>
          </template>
        </div>
      </div>
    </div>
  </div>

  <Sidebar v-model:visible="paymentSidebar" position="right" class="" style="width: 65vw" :pt="{
    root: 'border-none',
    mask: {
      style: 'backdrop-filter: blur(10px)'
    }
  }">
    <div class="flex h-full">
      <div style="height: 90vh; width: 30vw"
        class="flex-grow-1 border-2 border-dashed surface-border flex flex-wrap gap-3 p-2">
        <div class="text-2xl font-bold">
          <div class="gap-3 justify-content-between m-3">
            <div class="text-4xl">Confirmation</div>
            <div class="text-2xl text-400 flex justify-content-between">
              Order #73232 <span class="font-italic">Note</span>
            </div>
          </div>
          <Divider />

          <div class="justify-content-center flex flex-row overflow-y-scroll" style="height: 60vh">
            <DataTable :value="products" tableStyle="min-width: 387px">
              <Column field="name" header="Item"></Column>
              <Column Column field="quantity" header="Quantity"></Column>
              <Column field="price" header="Subtotal"></Column>
              <Column>
                <template #body>
                  <div class="flex gap-2">
                    <Button rounded icon="pi pi-minus" severity="info" outlined text />
                    <Button rounded icon="pi pi-times" severity="danger" text />
                  </div>
                </template>
              </Column>
            </DataTable>
          </div>
          <div class="flex justify-content-between items-center text-xl p-3">
            <span>Total price:</span>
            <span class="font-italic text-400">P2000.00</span>
          </div>
          <div class="flex justify-content-between items-center text-xl p-3">
            <span>Tax price:</span>
            <span class="font-italic text-400">P57.00</span>
          </div>
        </div>
      </div>

      <Divider layout="vertical" />

      <div style="height: 90vh; width: 30vw"
        class="flex-grow-1 border-2 border-dashed surface-border flex flex-wrap gap-3 p-2">
        <div class="text-2xl font-bold w-full">
          <div class="gap-3 justify-content-between m-3">
            <div class="text-4xl">Payment</div>
            <div class="text-2xl text-400 flex justify-content-between">
              Customer name<span class="font-italic">Dine-in</span>
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
                <InputNumber />
                <label>Amount Tendered</label>
              </FloatLabel>
            </InputGroup>

            <Button icon="pi pi-calculator" severity="info" @click="showCalc = !showCalc"></Button>
          </div>
          <template v-if="showCalc">
            <div class="justify-content-between flex flex-row flex-wrap" style="height: 35vh">
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
            <div class="justify-content-between flex flex-row flex-wrap" style="height: 35vh"></div>
          </template>
          <div class="flex justify-content-between items-center text-xl p-3">
            <span>Change:</span>
            <span class="font-italic text-500">P200.00</span>
          </div>
          <div class="gap-3 flex mt-3">
            <Button style="width: 50%" @click="paymentSidebar = false" label="Cancel" severity="danger"
              outlined></Button>
            <Button style="width: 50%" label="Confirm Payment" icon="pi pi-wallet" severity="success"></Button>
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

    <div class="border-2 border-dashed surface-border flex flex-wrap    overflow-y-scroll p-2">
      <DataTable style="max-height: 60vh;" :value="orders" dataKey="id" class="w-full" tableStyle="min-width: 60rem"
        v-model:expandedRows="expandedRows" selectionMode="single" v-model:selection="selectedOrder" stripedRows>

        <!-- <Column expander style="width: 4rem" /> -->
        <Column field="customer_name" header="Customer">
        </Column>
        <Column field="total_price" header="Total Price">
        </Column>
        <Column field="status" header="Status">
        </Column>
        <Column field="service" header="Service">
        </Column>
        <Column field="total_price" header="Total">
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

        <Column field="name" header="Name">
        </Column>
        <Column field="email" header="Email Address"></Column>
        <Column field="phone" header="Phone Number">
        </Column>


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
  <Toast />
</template>
