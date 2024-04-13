<script setup>
import { ref, computed, onMounted, onBeforeUnmount, watch } from "vue";
import { useLayout } from "@/layout/composables/layout";
import { useRouter } from "vue-router";
import { useStore } from "vuex";
import AccountService from "@/service/AccountService"; // Adjust the path based on your project structure
const isLoggedIn = computed(() => store.state.account.isLoggedIn); // Assuming 'auth' is the name of your auth module in Vuex
const store = useStore();

const accountInfo = ref([]);
watch(
  isLoggedIn,
  (newVal) => {
    if (newVal) {
      const user = store.state.account;
      accountInfo.value = {
        user_id: user.user_id,
        email: user.email,
        phone: user.phone,
        name: user.name,
        isLoggedIn: user.isLoggedIn,
      };
    } else {
      accountInfo.value = {};
    }
  },
  { immediate: true }
);
const logout = async () => {
  try {
    await AccountService.logout();
    store.commit("logout");
    router.push("/");
  } catch (error) {
    console.error("Error logging out:", error);
  }
};
const { layoutConfig, onMenuToggle } = useLayout();

const menuShown = ref(false);

const menuIcon = computed(() => {
  return menuShown.value ? "pi pi-caret-right" : "pi pi-times";
});

// Example toggle function, this should set `menuShown`
const MenuToggle = () => {
  menuShown.value = !menuShown.value;
  onMenuToggle();
};
const outsideClickListener = ref(null);
const topbarMenuActive = ref(false);
const router = useRouter();

onMounted(() => {
  bindOutsideClickListener();
});

onBeforeUnmount(() => {
  unbindOutsideClickListener();
});

const logoUrl = computed(() => {
  return `layout/images/${layoutConfig.darkTheme.value ? "logo-white" : "logo-dark"}.svg`;
});

const onTopBarMenuButton = () => {
  topbarMenuActive.value = !topbarMenuActive.value;
};
const onSettingsClick = () => {
  topbarMenuActive.value = false;
  router.push("/documentation");
};
const topbarMenuClasses = computed(() => {
  return {
    "layout-topbar-menu-mobile-active": topbarMenuActive.value,
  };
});

const bindOutsideClickListener = () => {
  if (!outsideClickListener.value) {
    outsideClickListener.value = (event) => {
      if (isOutsideClicked(event)) {
        topbarMenuActive.value = false;
      }
    };
    document.addEventListener("click", outsideClickListener.value);
  }
};
const unbindOutsideClickListener = () => {
  if (outsideClickListener.value) {
    document.removeEventListener("click", outsideClickListener);
    outsideClickListener.value = null;
  }
};
const isOutsideClicked = (event) => {
  if (!topbarMenuActive.value) return;

  const sidebarEl = document.querySelector(".layout-topbar-menu");
  const topbarEl = document.querySelector(".layout-topbar-menu-button");

  return !(
    sidebarEl.isSameNode(event.target) ||
    sidebarEl.contains(event.target) ||
    topbarEl.isSameNode(event.target) ||
    topbarEl.contains(event.target)
  );
};

const accountMenu = ref();

const toggle = (event) => {
  accountMenu.value.toggle(event);
};
</script>

<template>
  <div class="layout-topbar flex justify-content-center">
    <div class="w-20rem">
      <router-link to="/website/landing" class="layout-topbar-logo">
        <img src="@/assets/images/logo.png" class="w-14rem h-auto" alt="logo" />
      </router-link>
    </div>

    <div>
      <button
        class="p-link layout-menu-button mr-7 layout-topbar-button"
        @click="MenuToggle"
      >
        <i :class="menuIcon"></i>
      </button>
    </div>

    <div class="flex-auto text-center text-2xl font-bold">Page Title</div>

    <div class="layout-topbar-menu" :class="topbarMenuClasses">
      <button @click="onTopBarMenuButton()" class="layout-topbar-button">
        <i class="pi pi-flag"></i>
        <span>Calendar</span>
      </button>
      <button @click="onTopBarMenuButton()" class="layout-topbar-button">
        <i class="pi pi-comment"></i>
        <span>Calendar</span>
      </button>
      <button @click="onTopBarMenuButton()" class="layout-topbar-button">
        <i class="pi pi-bell"></i>
        <span>Calendar</span>
      </button>

      <Avatar
        class="layout-topbar-button"
        style="width: 2.8rem; height: 2.8rem"
        image="https://www.gravatar.com/avatar/05dfd4b41340d09cae045235eb0893c3?d=mp"
        shape="circle"
        @click="toggle"
        aria-controls="overlay_menu"
      />
      <Menu ref="accountMenu" :popup="true" id="overlay_menu" style="width: 25rem">
        <template #start>
          <button
            v-ripple
            class="relative overflow-hidden w-full p-link flex align-items-center p-2 pl-3 text-color hover:surface-200 border-noround"
          >
            <Avatar
              image="https://www.gravatar.com/avatar/05dfd4b41340d09cae045235eb0893c3?d=mp"
              class="mr-2"
              shape="circle"
            />
            <span class="inline-flex flex-column">
              <span class="font-bold">{{ accountInfo.name }}</span>
              <span class="text-sm">Admin</span>
            </span>
          </button>
        </template>

        <template #end>
          <button
            @click="logout"
            v-ripple
            class="relative overflow-hidden w-full p-link flex align-items-center p-2 pl-3 text-color hover:surface-200 border-noround"
          >
            <i class="pi pi-sign-out mr-4"></i>
            <span class="inline-flex flex-column">
              <span class="font-bold">Log out</span>
              <span class="font-bold"> </span>
            </span>
          </button>
        </template>
      </Menu>
      <!-- <button @click="onSettingsClick()" class="p-link layout-topbar-button">
        <i class="pi pi-cog"></i>
        <span>Settings</span>
      </button> -->
    </div>
  </div>
</template>

<style lang="scss" scoped></style>
