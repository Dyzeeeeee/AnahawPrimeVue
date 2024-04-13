<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import { useLayout } from "@/layout/composables/layout";
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import AccountService from "@/service/AccountService"; // Adjust the path based on your project structure

const store = useStore();
const router = useRouter();
const isLoggedIn = computed(() => store.state.account.isLoggedIn); // Assuming 'auth' is the name of your auth module in Vuex

const logout = async () => {
  try {
    await AccountService.logout();
    store.commit('logout');
    router.push('/');
  } catch (error) {
    console.error('Error logging out:', error);
  }
};
const login = () => {
  router.push('/auth/login');

};
</script>

<template>
  <div class="wlayout-topbar">
    <router-link to="/" class="wlayout-topbar-logo">
      <img src="@/assets/images/logo.png" alt="logo" />
    </router-link>

    <div class="wlayout-topbar-menu">
      <div class="wlayout-topbar-links gap-5">
        <router-link to="/">
          <span>HOME</span>
        </router-link>
        <router-link to="/">
          <span>ABOUT</span>
        </router-link>
        <router-link to="/">
          <span>ROOMS</span>
        </router-link>
        <router-link to="/">
          <span>MENU</span>
        </router-link>
        <router-link to="/">
          <span>CONTACT</span>
        </router-link>
        <Button v-if="isLoggedIn" @click="logout" class="mr-3 p-button-raised">LOG OUT</Button>
        <Button v-else @click="login" class="mr-3 p-button-raised">
          <span>LOG IN</span>
        </Button>
      </div>
    </div>
  </div>
</template>

<style lang="scss" scoped></style>
