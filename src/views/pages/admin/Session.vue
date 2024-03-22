<script setup>
import { onMounted, reactive, ref, watch } from "vue";
import { useToast } from "primevue/usetoast";
import { useConfirm } from "primevue/useconfirm";
import { ProductService } from "@/service/ProductService";

const confirm = useConfirm();
const toast = useToast();
const showCalc = ref(false);
const viewMode = ref("grid");
const orderListVisible = ref(false);
const customerListVisible = ref(false);
const addNoteVisible = ref(false);
const paymentSidebar = ref(false);
onMounted(() => {
  ProductService.getProducts().then((data) => (products.value = data));
});

const products = ref();
const changeViewMode = () => {
  viewMode.value = viewMode.value === "grid" ? "list" : "grid";
};

const requireConfirmation = (event) => {
  confirm.require({
    target: event.currentTarget,
    group: "headless",
    message: "Create a new order?",
    accept: () => {
      toast.add({
        severity: "success",
        summary: "New order: 00023",
        detail: "Order: 00022 on hold",
        life: 3000,
      });
    },
    reject: () => {
      //   toast.add({
      //     severity: "error",
      //     summary: "Rejected",
      //     detail: "You have rejected",
      //     life: 3000,
      //   });
    },
  });
};
</script>

<template>
  <div class="surface-card p-2 shadow-2 border-round main-view">
    <div class="flex">
      <div
        style="height: 80vh; width: 500px"
        class="flex-auto surface-border flex flex-wrap gap-3 p-2 align-content-start justify-content-around"
      >
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
                <Button
                  @click="changeViewMode"
                  :icon="viewMode === 'grid' ? 'pi pi-list' : 'pi pi-table'"
                >
                </Button>
              </div>
            </template>
            <template #center> </template>
            <template #end>
              <div class="flex gap-2">
                <Button
                  class="p-button-help"
                  icon="pi pi-envelope"
                  @click="addNoteVisible = true"
                >
                </Button>
                <Button
                  class="p-button-help"
                  icon="pi pi-user-plus"
                  @click="customerListVisible = true"
                >
                </Button>
                <Button
                  label="New Order"
                  class="gap-2 font-bold"
                  icon="pi pi-plus"
                  @click="requireConfirmation($event)"
                >
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
          <Button outlined rounded label="All"></Button>
          <Button outlined rounded label="Solo Meals"></Button>
          <Button outlined rounded label="Family Meals"></Button>
          <Button outlined rounded label="Desserts"></Button>
          <Button rounded label="Drinks"></Button>
        </div>
        <template v-if="viewMode === 'grid'">
          <div
            style="height: 59vh; width: 500px"
            class="flex-auto border-2 border-dashed surface-border flex flex-wrap gap-3 p-2 overflow-y-scroll align-content-start justify-content-around"
          >
            <!-- Display sessions in grid view -->
            <Card
              v-for="session in products"
              :key="session.id"
              style="
                width: 30%;
                max-height: 20rem;
                height: auto;
                min-width: 12rem;
                overflow: hidden;
              "
              class="shadow-6"
            >
              <template #header> </template>
              <template #title>
                <div class="justify-content-center flex" style="overflow: visible">
                  <img
                    src="@/assets/images/sisig.jpg"
                    alt="Menu"
                    style="height: 150px; border-radius: 100%"
                  />
                </div>
                <div class="text-center text-xl">{{ session.name }}</div>
              </template>
              <template #subtitle>
                <div class="text-center">
                  {{ session.price }}<br />Available
                </div></template
              >
            </Card>
          </div>
        </template>
      </div>
      <div
        style="height: 80vh; max-width: 450px"
        class="flex-grow-1 border-2 border-dashed surface-border flex flex-wrap gap-3 p-2"
      >
        <div class="text-2xl font-bold">
          <div class="flex gap-3 justify-content-between m-3">
            <div class="align-self-center">Order #2092038</div>
            <Button
              label="Orders"
              class="p-button-info"
              icon="pi pi-folder"
              @click="orderListVisible = true"
            >
            </Button>
          </div>
          <div class="gap-3 flex mt-3 mb-2">
            <Button outlined rounded label="Dine in"></Button>
            <Button rounded label="Take out"></Button>
          </div>
          <div
            class="justify-content-center flex flex-row overflow-y-scroll"
            style="height: 50vh"
          >
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
            <span class="font-italic text-500">P2000.00</span>
          </div>
          <div class="justify-content-end flex gap-2">
            <Button
              outlined
              label="Hold Order"
              icon="pi pi-window-minimize"
              severity="warning"
            ></Button>
            <Button
              @click="paymentSidebar = true"
              label="Proceed to Payment"
              icon="pi pi-wallet"
              severity="success"
            ></Button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <Sidebar v-model:visible="paymentSidebar" position="right" class="" style="width: 65vw">
    <div class="flex h-full">
      <div
        style="height: 90vh; width: 30vw"
        class="flex-grow-1 border-2 border-dashed surface-border flex flex-wrap gap-3 p-2"
      >
        <div class="text-2xl font-bold">
          <div class="gap-3 justify-content-between m-3">
            <div class="text-4xl">Confirmation</div>
            <div class="text-2xl text-400 flex justify-content-between">
              Order #73232 <span class="font-italic">Dine-in</span>
            </div>
          </div>
          <Divider />

          <div
            class="justify-content-center flex flex-row overflow-y-scroll"
            style="height: 60vh"
          >
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

      <div
        style="height: 90vh; width: 30vw"
        class="flex-grow-1 border-2 border-dashed surface-border flex flex-wrap gap-3 p-2"
      >
        <div class="text-2xl font-bold w-full">
          <div class="gap-3 justify-content-between m-3">
            <div class="text-4xl">Payment</div>
            <div class="text-2xl text-400 flex justify-content-between">
              Customer name
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

            <Button
              icon="pi pi-calculator"
              severity="info"
              @click="showCalc = !showCalc"
            ></Button>
          </div>
          <template v-if="showCalc">
            <div
              class="justify-content-between flex flex-row flex-wrap"
              style="height: 35vh"
            >
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
            <div
              class="justify-content-between flex flex-row flex-wrap"
              style="height: 35vh"
            ></div>
          </template>
          <div class="flex justify-content-between items-center text-xl p-3">
            <span>Change:</span>
            <span class="font-italic text-500">P200.00</span>
          </div>
          <div class="gap-3 flex mt-3">
            <Button
              style="width: 50%"
              @click="paymentSidebar = false"
              label="Cancel"
              severity="danger"
              outlined
            ></Button>
            <Button
              style="width: 50%"
              label="Confirm Payment"
              icon="pi pi-wallet"
              severity="success"
            ></Button>
          </div>
        </div>
      </div>
    </div>
  </Sidebar>

  <Dialog
    v-model:visible="orderListVisible"
    modal
    position="top"
    header="Order List"
    :style="{ width: '50rem' }"
  >
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
          <Button label="Go to Order" icon="pi pi-caret-right"></Button>
        </div>
      </template>
    </Toolbar>
  </Dialog>

  <ConfirmPopup group="headless">
    <template #container="{ message, acceptCallback, rejectCallback }">
      <div class="border-round p-3">
        <span>{{ message.message }}</span>
        <div class="flex align-items-center gap-2 mt-3">
          <Button label="Yes" @click="acceptCallback" size="small"></Button>
          <Button
            label="Cancel"
            outlined
            @click="rejectCallback"
            severity="secondary"
            size="small"
            text
          ></Button>
        </div>
      </div>
    </template>
  </ConfirmPopup>

  <Dialog
    v-model:visible="customerListVisible"
    modal
    position="top"
    header="Customer List"
    :style="{ width: '50rem' }"
  >
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
          <Button label="Add New" icon="pi pi-plus"></Button>
        </div>
      </template>
    </Toolbar>
  </Dialog>

  <Dialog
    v-model:visible="addNoteVisible"
    modal
    header="Add Note"
    :style="{ width: '25rem' }"
  >
    <div class="flex justify-content-center mb-3">
      <FloatLabel>
        <Textarea v-model="value" rows="3" cols="30" autoResize class="w-23rem" />
        <label>Note</label>
      </FloatLabel>
    </div>
    <div class="flex justify-content-end gap-2">
      <Button
        type="button"
        label="Cancel"
        severity="secondary"
        @click="addNoteVisible = false"
      ></Button>
      <Button type="button" label="Add" @click="addNoteVisible = false"></Button>
    </div>
  </Dialog>
  <Toast />
</template>
