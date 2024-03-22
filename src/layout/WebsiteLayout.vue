<script setup>
import { computed, watch, ref } from "vue";
import Topbar from "@/components/WebsiteLayout/Topbar.vue";
import Helper from "@/components/WebsiteLayout/Helper.vue";
</script>

<template>
  <div class="layout-wrapper">
    <Topbar></Topbar>
    <div class="layout-main-container">
      <div class="layout-main">
        <router-view></router-view>
      </div>
      <Helper></Helper>
    </div>
    <div class="layout-mask"></div>
  </div>
  <Toast />
</template>

<style lang="scss" scoped></style>
