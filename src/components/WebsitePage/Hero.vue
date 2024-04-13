<script setup>
import { onMounted, ref } from "vue";
import { PhotoService } from "@/service/PhotoService";

onMounted(() => {
  PhotoService.getImages().then((data) => (images.value = data));
});
const images = ref();
</script>

<template>
  <div class="grid grid-nogutter surface-section text-800">
    <div class="col-12 md:col-6 p-6 text-center md:text-left flex align-items-center">
      <section>
        <span class="block text-6xl font-bold mb-1">Welcome to ☀</span>
        <div class="text-6xl text-primary font-bold mb-3">Anahaw Island View Resort</div>
        <p class="mt-0 mb-4 text-700 line-height-3">
          Book your own room order the best local foods in town
        </p>
        <Button label="Check Rooms" type="button" class="mr-3 p-button-raised"></Button>
        <Button label="Food Menu" type="button" class="p-button-outlined"></Button>
      </section>
    </div>
    <div class="col-12 md:col-6 overflow-hidden">
      <Galleria
        :value="images"
        :responsiveOptions="responsiveOptions"
        :numVisible="5"
        :circular="true"
        containerStyle=""
        :showThumbnails="false"
        :autoPlay="true"
      >
        <template #item="slotProps">
          <div style="">
            <img
              class="md:ml-auto block"
              :src="slotProps.item.itemImageSrc"
              :alt="slotProps.item.alt"
              style="
                width: 100%;
                display: block;
                clip-path: polygon(8% 0, 100% 0%, 100% 100%, 0 100%);
                height: 80vh;
              "
            />
            <div
              class="px-8 pb-4"
              style="position: absolute; bottom: 0; left: 0; right: 0; color: white"
            >
              <h2 style="background-color: black">{{ slotProps.item.title }}</h2>
              <p style="background-color: grey">{{ slotProps.item.alt }}</p>
            </div>
          </div>
        </template>

        <template #thumbnail="slotProps">
          <img
            :src="slotProps.item.thumbnailImageSrc"
            :alt="slotProps.item.alt"
            style="display: block"
          />
        </template>
      </Galleria>
      <!-- <img
        src="@/assets/images/resort-1.jpg"
        alt="Image"
        class="md:ml-auto block"
        style="clip-path: polygon(8% 0, 100% 0%, 100% 100%, 0 100%); height: 80vh"
      /> -->
    </div>
  </div>
</template>
