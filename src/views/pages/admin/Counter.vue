<script setup>
import SessionService from "@/service/SessionService";
import { onMounted, reactive, ref, watch } from "vue";
import { useToast } from "primevue/usetoast";
import { useStore } from "vuex";
import { useRouter } from "vue-router";

const store = useStore();
const router = useRouter();

const toast = useToast();
const viewMode = ref("grid");
const selectedSession = ref();
const cashier_id = store.state.account.user_id;
const newSessionVisible = ref(false);
const noteDetails = ref(false);
const sessions = ref();
const selectedCity = ref({ name: "Active" });
const cities = ref([{ name: "Active" }, { name: "Archived" }, { name: "All" }]);

onMounted(async () => {
  try {
    sessions.value = await SessionService.getAllSessions();
  } catch (error) {
    console.error("Error fetching sessions:", error);
    toast.add({
      severity: "error",
      summary: "Error",
      detail: "Failed to fetch sessions",
    });
  }
});
const newSession = ref({
  opening_cash: "",
  note: "",
  cashier_id: cashier_id,
});

const goToSession = (sessionId) => {
  router.push({ name: "session", params: { id: sessionId } });
};
const changeViewMode = () => {
  viewMode.value = viewMode.value === "grid" ? "list" : "grid";
};
const addSession = async () => {
  try {
    await SessionService.addSession(newSession.value);
    toast.add({
      severity: "success",
      summary: "Success",
      detail: "Customer added successfully",
      life: "3000",
    });
    // Fetch updated menu items
    sessions.value = await SessionService.getAllSessions();
    newSessionVisible.value = false;
    // newCustomerVisible.value = false;
  } catch (error) {
    console.error("Error adding menu item:", error);
    toast.add({
      severity: "error",
      summary: "Error",
      detail: "Select an item to Archive",
      life: "3000",
    });
  }
};
</script>

<template>
  <div class="surface-card p-4 shadow-2 border-round main-view">
    <Toolbar style="border: none">
      <template #start>
        <div class="gap-2 flex">
          <div class="text-4xl font-bold align-self-center"></div>

          <Button icon="pi pi-filter-fill"> </Button>
          <IconField iconPosition="left">
            <InputIcon>
              <i class="pi pi-search" />
            </InputIcon>
            <InputText placeholder="Search" class="w-15rem" />
          </IconField>
          <Button
            @click="changeViewMode"
            :icon="viewMode === 'grid' ? 'pi pi-list' : 'pi pi-table'"
          >
          </Button>
          <Dropdown
            v-model="selectedCity"
            :options="cities"
            optionLabel="name"
            class="w-10rem"
          />
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
          <Button label="New Session" @click="newSessionVisible = true" icon="pi pi-plus">
          </Button>
        </div>
      </template>
    </Toolbar>
    <div
      style="height: 65vh"
      class="border-2 border-dashed surface-border flex flex-wrap gap-3 overflow-y-scroll p-2"
    >
      <template v-if="viewMode === 'grid'">
        <!-- Display sessions in grid view -->
        <Card
          v-for="session in sessions"
          :key="session.id"
          style="width: 23%; max-height: 15rem; min-width: 17rem; overflow: hidden"
          class="shadow-6 p-0 m-0"
        >
          <template #title>
            <div class="flex justify-content-between p-0 m-0">
              <div>
                <Badge value=" " size="" severity="success"></Badge>
                Session
                {{ session.id }}
              </div>
              <Checkbox v-model="selectedSession" :value="session.id" />
            </div>
          </template>
          <template #subtitle>
            <div class="p-0 m-0">
              <span class="font-medium text-600 font-bold"
                >{{ session.firstname }} {{ session.lastname }}</span
              >
              <div class="surface-100 p-1 my-2">
                <span class="text-900 font-medium text-sm">{{ session.start_time }}</span>
              </div>

              <div
                class="overflow-hidden font-medium text-secondary text-400 font-italic mb-2"
                style="
                  height: 40px;
                  line-height: 20px;
                  display: -webkit-box;
                  -webkit-line-clamp: 2;
                  -webkit-box-orient: vertical;
                  cursor: pointer;
                "
                @click="noteDetails = true"
              >
                {{ session.note ? session.note : "No notes" }}
              </div>
            </div>
            <Dialog
              v-model:visible="noteDetails"
              modal
              header="Note"
              :style="{ width: '25rem' }"
            >
              <span class=""> {{ session.note ? session.note : "No notes" }} </span>
              <div class="flex justify-content-end gap-2">
                <Button class="" icon="pi pi-pencil" />
              </div>
            </Dialog>
            <div class="flex gap-3 p-0 m-0">
              <Button label="End" severity="danger" outlined class="w-full" />
              <Button label="Continue" class="w-full" @click="goToSession(session.id)" />
            </div>
          </template>
        </Card>
      </template>
      <template v-else>
        <DataView :value="sessions" select="multiplefvh">
          <template #list="slotProps">
            <div class="grid grid-nogutter w-full">
              <div v-for="(item, index) in slotProps.items" :key="index" class="col-12">
                <div
                  class="flex flex-column sm:flex-row sm:align-items-center p-4 gap-3  w-full"
                  :class="{ 'border-top-1 surface-border': index !== 0 }"
                >
                  <div
                    class="flex flex-column md:flex-row justify-content-between md:align-items-center flex-1 gap-4 w-full"
                  >
                    <div
                      class="flex flex-row md:flex-column justify-content-between align-items-start gap-2"
                    >
                      <div>
                        <div class="text-2xl font-bold text-900">
                          <Badge value=" " severity="success"></Badge>
                          Session {{ item.id }}
                        </div>
                        <span class="font-medium text-secondary"
                          >Cashier: {{ item.cashier_id }}</span
                        >
                      </div>
                      <div class="surface-100 p-1">
                        <span class="text-900 font-medium text-sm">{{
                          item.start_time
                        }}</span>
                      </div>
                    </div>
                    <div class="flex flex-column md:align-items-end gap-5">
                      <span class="text-xl font-semibold text-900">
                        <Checkbox
                          v-model="selectedSession"
                          :value="item.id"
                          class="checkbox-large"
                        />
                      </span>
                      <div class="flex flex-row-reverse md:flex-row gap-2">
                        <Button outlined label="Close" severity="danger"></Button>
                        <Button
                          icon="pi pi-shopping-cart"
                          label="Continue"
                          class="flex-auto md:flex-initial white-space-nowrap"
                        ></Button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </template>
        </DataView>
      </template>
    </div>
  </div>

  <Dialog
    v-model:visible="newSessionVisible"
    modal
    header="New Session"
    :style="{ width: '28rem' }"
    :pt="{
      root: 'border-none',
      mask: {
        style: 'backdrop-filter: blur(4px)',
      },
    }"
  >
    <div class="flex align-items-center gap-3 mb-3 mt-5">
      <InputGroup>
        <InputGroupAddon>
          <i class="pi pi-money-bill"></i>
        </InputGroupAddon>
        <FloatLabel>
          <InputText v-model="newSession.opening_cash" />
          <label>Opening Cash</label>
        </FloatLabel>
      </InputGroup>
    </div>
    <div class="flex align-items-center gap-3 mb-3 mt-4">
      <InputGroup>
        <InputGroupAddon>
          <i class="pi pi-envelope"></i>
        </InputGroupAddon>
        <FloatLabel>
          <!-- <InputText v-model="newSession.note" :lines="2" /> -->
          <Textarea v-model="newSession.note" rows="2" cols="30" autoResize />

          <label>Note</label>
        </FloatLabel>
      </InputGroup>
    </div>
    <div class="flex justify-content-end gap-2">
      <Button
        type="button"
        label="Cancel"
        severity="secondary"
        @click="newSessionVisible = false"
      ></Button>
      <Button type="button" label="Open" @click="addSession"></Button>
    </div>
  </Dialog>

  
</template>
