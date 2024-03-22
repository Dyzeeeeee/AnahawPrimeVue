<template>
  <div class="grid grid-nogutter surface-section text-800">
    <div class="col-12 md:col-6 p-6 text-center md:text-left flex align-items-center">
      <section>
        <span class="block text-6xl font-bold mb-1">Welcome to</span>
        <div class="text-6xl text-primary font-bold mb-3">Anahaw Island View Resort</div>
        <p class="mt-0 mb-4 text-700 line-height-3">
          Book your own room order the best local foods in town
        </p>
        <Button label="Check Rooms" type="button" class="mr-3 p-button-raised"></Button>
        <Button label="Food Menu" type="button" class="p-button-outlined"></Button>
      </section>
    </div>
    <div class="col-12 md:col-6 overflow-hidden">
      <img
        src="@/assets/images/resort-1.jpg"
        alt="Image"
        class="md:ml-auto block"
        style="clip-path: polygon(8% 0, 100% 0%, 100% 100%, 0 100%); height: 80vh"
      />
    </div>
  </div>
</template>
