<script setup>
import { ref, onMounted } from "vue";
import { usePrimeVue } from "primevue/config";
import { useLayout } from "@/layout/composables/layout";
import { useStore } from "vuex";

const { layoutConfig } = useLayout();
const $primevue = usePrimeVue();
const visible = ref(false);
const store = useStore();
const toDoList = ref(false);
const active = ref(0);

const onHelperClick = () => {
  visible.value = !visible.value;
};

const redirectToGoogleMaps = () => {
  const googleMapsUrl =
    "https://www.google.com/maps/dir//Anahaw+Island+View+Resort,+Brgy,+Calapan,+5200+Oriental+Mindoro/@13.4161617,121.1574395,16z/data=!4m19!1m10!3m9!1s0x33bceece975a1a9d:0xe9752d2738d90944!2sAnahaw+Island+View+Resort!5m2!4m1!1i2!8m2!3d13.4163798!4d121.1611153!16s%2Fg%2F1tgc71nr!4m7!1m0!1m5!1m1!1s0x33bceece975a1a9d:0xe9752d2738d90944!2m2!1d121.1611153!2d13.4163798?entry=ttu";
  window.open(googleMapsUrl, "_blank");
};

const onChangeTheme = (theme, mode) => {
  $primevue.changeTheme(layoutConfig.theme.value, theme, "theme-css", () => {
    layoutConfig.theme.value = theme;
    layoutConfig.darkTheme.value = mode;
  });
  localStorage.setItem("theme", theme); // Save theme to localStorage
  localStorage.setItem("darkMode", mode); // Save dark mode state to localStorage
};

const onDarkModeChange = (value) => {
  const newThemeName = value
    ? layoutConfig.theme.value.replace("light", "dark")
    : layoutConfig.theme.value.replace("dark", "light");
  layoutConfig.darkTheme.value = value;
  onChangeTheme(newThemeName, value);
  store.commit("setDarkMode", value); // Store darkMode state in Vuex
  localStorage.setItem("darkMode", value); // Save dark mode state to localStorage
};

const checked = ref(true); // Initialize checked as true initially

onMounted(() => {
  // Set checked value to the current dark mode state from Vuex when component is mounted
  checked.value = store.state.darkMode;

  // Set the theme based on the stored theme in localStorage
  const storedTheme = localStorage.getItem("theme");
  if (storedTheme) {
    onChangeTheme(storedTheme, store.state.darkMode);
    console.log("Theme set to:", storedTheme);
  }

  // Set the dark mode state based on the stored state in localStorage
  const storedDarkMode = localStorage.getItem("darkMode");
  if (storedDarkMode !== null) {
    layoutConfig.darkTheme.value = storedDarkMode === "true";
    console.log("Dark mode set to:", storedDarkMode);
  }
});
</script>

<template>
  <Button @click="onHelperClick()" class="layout-config-button p-link">
    <Icon icon="icon-park-solid:more-four" height="2rem" />
  </Button>

  <Sidebar v-model:visible="visible" position="right" class="layout-config-sidebar w-26rem" pt:closeButton="ml-auto">
    <section class="py-4 flex align-items-center justify-content-between border-bottom-1 surface-border">
      <Button label="Get Directions to Resort" type="button" class="w-full mt-2" icon="pi pi-map"
        @click="redirectToGoogleMaps" severity="info"></Button>
    </section>
    <section class="py-4 flex align-items-center justify-content-end border-bottom-1 surface-border">
      <ToggleButton v-model="checked" onLabel="Dark " offLabel="Light" offIcon="pi pi-sun" onIcon="pi pi-moon"
        class="w-full " aria-label="Do you confirm" @update:modelValue="onDarkModeChange" />
    </section>

    <section class="py-4 flex align-items-center justify-content-between border-bottom-1 surface-border">
      <Button label="To Do List" type="button" class="w-full mt-2" icon="pi pi-list" @click="toDoList = true" severity="help"></Button>
    </section>
  </Sidebar>

  <Dialog v-model:visible="toDoList" modal header="To Do List" :style="{ width: '35rem' }">
    <div class="flex mb-2 gap-2 justify-content-end">
      <Button @click="active = 0" rounded label="1" class="w-2rem h-2rem p-0" :outlined="active !== 0" />
      <Button @click="active = 1" rounded label="2" class="w-2rem h-2rem p-0" :outlined="active !== 1" />
      <Button @click="active = 2" rounded label="3" class="w-2rem h-2rem p-0" :outlined="active !== 2" />
    </div>

    <TabView v-model:activeIndex="active">
      <TabPanel header="Menu">
      <ul>
        <li>Search</li>
        <li>Edit</li>
        <li>Delete</li>
      </ul>
      </TabPanel>
      <TabPanel header="Counter">
      </TabPanel>
      <TabPanel header="Customers">
      </TabPanel>
    </TabView>
  </Dialog>

</template>
