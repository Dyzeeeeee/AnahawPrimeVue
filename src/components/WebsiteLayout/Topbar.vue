<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import { useLayout } from "@/layout/composables/layout";
import { useRouter } from "vue-router";
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
        <Button label="Log in" class="mr-3 p-button-raised"></Button>
      </div>
    </div>
  </div>
</template>

<style lang="scss" scoped></style>
