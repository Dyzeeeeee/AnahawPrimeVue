<template>
  <div>
    <div class="text-900 text-6xl mb-4 text-center font-bold">Our Menu</div>
    <div class="text-700 text-xl text-center line-height-3">
      Lorem ipsum dolor sit, amet consectetur adipisicing elit. Velit numquam eligendi
      quos.
    </div>
  </div>
  <div class="card mt-8" style="height: 80vh">
    <TabView class="">
      <TabPanel v-for="(tab, index) in tabs" :key="index">
        <template #header>
          <div
            class="flex align-items-center justify-content-center w-22rem"
            style="min-width: 100%"
          >
            <span class="font-bold">{{ tab.name }}</span>
          </div>
        </template>
        <div class="justify-content-center flex flex-wrap gap-3">
          <Card
            v-for="(item, itemIndex) in tab.items"
            :key="itemIndex"
            style="width: calc(50% - 1rem); overflow: hidden"
            class="shadow-7"
          >
            <template #content>
              <div class="flex">
                <div>
                  <img
                    src="@/assets//images/anahaw-1.jpg"
                    alt="Image"
                    class="w-10rem h-10rem mr-2"
                  />
                </div>
                <div>
                  <span class="flex justify-content-between">
                    <p class="m-0 text-3xl font-semibold">Menu Item Name</p>
                    <p
                      class="m-0 text-l font-italic text-right justify-content-end align-items-end align-items-end"
                    >
                      Menu Item Name
                    </p>
                  </span>
                </div>
              </div>
            </template>
          </Card>
        </div>
      </TabPanel>
    </TabView>
  </div>
</template>

<script setup>
const tabs = [
  {
    id: 1,
    name: "Solo Meals",
    items: [
      { id: 1, name: "MenuItem 1", price: "Price 1", description: "Some description" },
      { id: 2, name: "MenuItem 2", price: "Price 2", description: "Some description" },
    ],
  },
  {
    id: 2,
    name: "Family Meals",
    items: [
      { id: 1, name: "MenuItem 1", price: "Price 1", description: "Some description" },
      { id: 2, name: "MenuItem 2", price: "Price 2", description: "Some description" },
    ],
  },
  {
    id: 3,
    name: "Drinks",
    items: [
      { id: 1, name: "MenuItem 1", price: "Price 1", description: "Some description" },
      { id: 2, name: "MenuItem 2", price: "Price 2", description: "Some description" },
      { id: 1, name: "MenuItem 1", price: "Price 1", description: "Some description" },
      { id: 2, name: "MenuItem 2", price: "Price 2", description: "Some description" },
    ],
  },
  {
    id: 4,
    name: "Desserts",
    items: [
      { id: 1, name: "MenuItem 1", price: "Price 1", description: "Some description" },
      { id: 2, name: "MenuItem 2", price: "Price 2", description: "Some description" },
    ],
  },
];
</script>
