<template>
  <div style="height: 15vh;" class="gap-8">
    <div class="flex justify-content-center text-center align-items-center h-full">
      <span class="text-7xl	font-bold">
        Welcome to <span class="text-yellow-500"> Anahaw Island View Resort</span>
      </span>
    </div>
  </div>
  <div class="flex gap-5 pt-2">
    <div class="flex gap-1" style="width: 50vw; height: 68vh; ">
      <div class="flex flex-column w-full h-full" style="">
        <div class="p-1" style=" height: 70%;">
          <img src="@/assets/images/family.jpg" class="" alt="Anahaw logo"
            style="width: 100%; height: 100%; object-fit: cover; clip-path: polygon(11% 0, 91% 0, 100% 0, 100% 100%, 11% 100%, 0 100%, 0 12%);" />
        </div>
        <div class="p-1 h-5" style=" height: 30%;">
          <img src="@/assets/images/Foodsisig.jpg" class="" alt="Anahaw logo"
            style="width: 100%; height: 100%; object-fit: cover; clip-path: polygon(20% 0%, 91% 0, 100% 10%, 100% 100%, 11% 100%, 0 100%, 0 0); border-radius: 0 0 0 5%;" />
        </div>
      </div>
      <div class="flex flex-column w-full h-full">
        <div class="p-1" style=" height: 45%;">
          <img src="@/assets/images/room.jpg" alt="Anahaw logo"
            style="width: 100%; height: 100%; object-fit: cover; clip-path: polygon(20% 0%, 86% 0, 100% 0, 100% 100%, 11% 100%, 0 91%, 0 0); border-radius: 0 5% 0 0%;" />
        </div>
        <div class="p-1" style="height: 45%;">
          <img src="@/assets/images/beach.jpg" alt="Anahaw logo"
            style="width: 100%; height: 100%; object-fit: cover; border-radius: 5%;" />
        </div>
        <div class="flex gap-2 p-1" style="height: 20%;" type="">
          <Button class="w-full h-full " label="Visit Website" icon="pi pi-arrow-right" iconPos="right" size="large"
            style="clip-path: polygon(0 0, 91% 0, 100% 37%, 100% 80%, 100% 100%, 0 100%, 0% 80%, 0% 20%);

">
          </Button>
          <!-- <Button class="w-full h-full " label="View Menu">
          </Button> -->
        </div>
      </div>
    </div>
    <div class="gap-1" style="width: 50vw; height: 68vh; ">

      <div class="flex flex-column align-items-center justify-content-center w-full"
        style="height: 25%; width: 100%; background-color: transparent;">
        <!-- <span class="text-2xl mb-3 text-center" style="width: 100%;">
          Enjoy your stay at our resort, complete with sumptuous dining at our restaurant and the serene comfort of our
          hotel rooms.
        </span> -->
        <div class="flex gap-3">
          <Button rounded outlined icon="pi pi-home" label="Room Offers" style="width: 210px;" class="mb-4 h-4rem"
            size=""> </Button>
          <Button rounded icon="pi pi-book" label="Book Now" style="width: 310px;" class="mb-4 h-4rem" size="">
          </Button>
        </div>
      </div>
      <div class="flex align-items-center justify-content-start  w-full gap-2" style="height: 55%; ">
        <div style="width: 40%; height: 100%; background-color: transparent;"
          class=" justify-content-center mr-3 align-items-center flex">
          <Card style="width: 15rem; height: 80%; border-bottom-left-radius: 30%; border-bottom-right-radius: 30%;">
            <template #header>
              <div class="flex justify-content-center">
                <div class="justify-content-center align-items-center flex" style="height:80%; width:80%; ">
                  <img src="@/assets/images/sisig.jpg" alt=""
                    style="object-fit: cover; width: 100%; height: 100%; border-radius: 50%; top: 0; transform: translateY(-30%); aspect-ratio: 1 / 1;">
                </div>
              </div>
              <div class=" text-center justify-content-center flex" style="margin-top: -50px;">
                <span class="text-lg font-bold">Menu Item Name</span>
              </div>
              <div class=" text-center justify-content-center flex" style="">
                <span class="text-md font-italic text-400">Php 100.00</span>
              </div>
            </template>
          </Card>


        </div>
        <div class="justify-content-center" style="width: 50%; ">
          <div style="">
            <Button size="" outlined rounded icon="pi pi-map-marker" label="Check our Menu" style="width: 300px;"
              class="mb-4 h-4rem">
            </Button>
          </div>
          <div class="text-lg mb-3 text-justify" style="width: 100%;">
            Enjoy your stay at our resort, complete with sumptuous dining at our restaurant and the serene comfort of
            our
            hotel rooms.
          </div>
        </div>

      </div>
      <div class="flex w-full justify-content-end align-items-end" style="height: 20%;">
        <div class="text-sm  font-bold justify-content-center text-center" style="width: 100%;">
          <div class="text-yellow-500 font-bold"><i class="pi pi-clock" style="font-size: 3rem"></i>
          </div>
          <br>
          <div class="text-400 font-italic">
            Monday to Sunday <br> 7:00am - 9:30pm
          </div>
        </div>
        <div class="text-sm font-bold justify-content-center text-center" style="width: 100%;">
          <div class="text-yellow-500 font-bold"><i class="pi pi-phone" style="font-size: 3rem"></i>
          </div>
          <br>
          <div class="text-400 font-italic">
            0992-410-3295 <br> anahaw.resort@gmail.com
          </div>
        </div>
        <div class="text-sm font-bold justify-content-center text-center" style="width: 100%;">
          <div class="text-yellow-500 font-bold"><i class="pi pi-map" style="font-size: 3rem"></i>
          </div>
          <br>
          <div class="text-400 font-italic">
            Brgy. Balite, Calapan City, Oriental Mindoro
          </div>
        </div>
      </div>

    </div>
  </div>
  <div style="">

  </div>
  <Button type="button" rounded icon="pi pi-comment" @click="toggleMenu" severity="success" size="large"
    style="position: fixed; bottom: 70px; right: 70px; height: 70px; width: 70px"></Button>


  <Menu v-if="menuVisible" class="flex flex-column gap-3 w-25rem h-26rem"
    style="position: fixed; bottom: 10px; right: 30px; padding: 0">
    <template #start>
      <div class="w-full h-3rem bg-yellow-600 text-center align-content-center relative">
        <span class="inline-flex align-items-center gap-1 px-2 py-2 text-center">
          <span class="font-medium text-xl font-semibold">Chat with<span class="text-bold"> <b> ANAHAW</b></span></span>
        </span>
        <button @click="menuVisible = false"
          style="position: absolute; top: 0; right: 0; background: none; border: none; font-size: 2.5em; color: white; cursor: pointer;">&times;</button>
      </div>
      <div class="h-23rem pb-3 align-content-end" style="overflow-y: auto;">
        <!-- Initial message -->
        <div class="justify-content-start flex pl-2 pb-2 align-items-start">
          <Avatar image="https://www.gravatar.com/avatar/05dfd4b41340d09cae045235eb0893c3?d=mp"
            class="flex align-items-center justify-content-center mr-2" shape="circle" size="small" />
          <Button severity="secondary" rounded label="Good day! How can we help you?"></Button>
        </div>
        <!-- Initial message -->

        <!-- Initial question -->
        <div v-if="activeButtons.has('question1') || !activeButtons.size"
          class="justify-content-end flex pr-2 pb-2 align-items-end">
          <Button :class="{ 'p-button-outlined': !activeButtons.has('question1') }" rounded label="Ask a question ❔"
            @click="toggleButton('question1')"></Button>
        </div>

        <div v-if="activeButtons === 'question2' || !activeButtons"
          class="justify-content-end flex pr-2 pb-2 align-items-end">
          <Button :class="{ 'p-button-outlined': activeButtons !== 'question2' }" rounded label="Give a Feedback ⭐"
            @click="toggleButton('question2')"></Button>
        </div>

        <div v-if="activeButtons === 'question3' || !activeButtons"
          class="justify-content-end flex pr-2 pb-2 align-items-end">
          <Button :class="{ 'p-button-outlined': activeButtons !== 'question3' }" rounded label="Make a Complain 🤬"
            @click="toggleButton('question3')"></Button>
        </div>
        <!-- Initial question -->
        <div v-if="activeButtons === 'question1'" class="justify-content-start flex pl-2 pb-2 align-items-end">
          <Avatar image="https://www.gravatar.com/avatar/05dfd4b41340d09cae045235eb0893c3?d=mp"
            class="flex align-items-center justify-content-center mr-2" shape="circle" />
          <Button severity="secondary" rounded label="Here is our u"></Button>
        </div>
        <div v-if="activeButtons === 'question2'" class="justify-content-start flex pl-2 pb-2 align-items-end">
          <Avatar image="https://www.gravatar.com/avatar/05dfd4b41340d09cae045235eb0893c3?d=mp"
            class="flex align-items-center justify-content-center mr-2" shape="circle" />
          <Button severity="secondary" rounded label="Please rate our service:">
          </Button>
        </div>
        <div v-if="activeButtons === 'question3'" class="justify-content-start flex pl-2 pb-2 align-items-end">
          <Avatar image="https://www.gravatar.com/avatar/05dfd4b41340d09cae045235eb0893c3?d=mp"
            class="flex align-items-center justify-content-center mr-2" shape="circle" />
          <Button severity="secondary" rounded label="What is your inquiry?"></Button>
        </div>
        <div v-if="activeButtons === 'question3'" class="justify-content-end flex pr-2 pb-2 align-items-end">
          <Button :class="{ 'p-button-outlined': activeButtons !== 'question3-1' }" rounded label="Can I see your menu?"
            @click="toggleButton('question3-1')"></Button>
        </div>
        <div v-if="activeButtons === 'question3'" class="justify-content-end flex pr-2 pb-2 align-items-end">
          <Button :class="{ 'p-button-outlined': activeButtons !== 'question3-2' }" rounded
            label="What are your available rooms?" @click="toggleButton('question3-2')"></Button>
        </div>
        <div v-if="activeButtons === 'question3'" class="justify-content-end flex pr-2 pb-2 align-items-end">
          <Button :class="{ 'p-button-outlined': activeButtons !== 'question3-3' }" rounded
            label="Where are you located?" @click="toggleButton('question3-3')"></Button>
        </div>
        <div v-if="activeButtons === 'question3'" class="justify-content-end flex pr-2 pb-2 align-items-end">
          <Button :class="{ 'p-button-outlined': activeButtons !== 'question3-4' }" rounded
            label="How can I contact you?" @click="toggleButton('question3-4')"></Button>
        </div>
        <div v-if="activeButtons === 'question3'" class="justify-content-end flex pr-2 pb-2 align-items-end">
          <Button :class="{ 'p-button-outlined': activeButtons !== 'question3-4' }" rounded label="Are you open now?"
            @click="toggleButton('question3-4')"></Button>
        </div>
        <div v-if="activeButtons === 'question2'" class="justify-content-end flex pr-2 pb-2 align-items-end">
          <Button rounded outlined>
            <Rating v-model="rating" :cancel="false" severity="success" />
          </Button>
        </div>

        <div v-if="rating" class="justify-content-start flex pl-2 pb-2 align-items-end">
          <Avatar image="https://www.gravatar.com/avatar/05dfd4b41340d09cae045235eb0893c3?d=mp"
            class="flex align-items-center justify-content-center mr-2" shape="circle" />
          <Button severity="secondary" rounded label="Thank you for your feedback!">
          </Button>
        </div>
        <!-- <div class="px-2 pt-3">
          <InputGroup>
            <InputText class="w-full" placeholder="Message us here"></InputText>
            <Button icon="pi pi-send" severity="primary" style="width: 100px;"></Button>
          </InputGroup>
        </div> -->
      </div>
    </template>
  </Menu>

</template>


<script setup>
import { ref } from 'vue';
import { useToast } from 'primevue/usetoast';
import { useRouter } from 'vue-router';


const toast = useToast();
const rating = ref(null);

const router = useRouter();
const menuVisible = ref(true);  // This controls the visibility of the Menu

const toggleMenu = () => {
  menuVisible.value = !menuVisible.value;  // This toggles the visibility
}

const activeButtons = ref(new Set());

const toggleButton = (button) => {
  if (activeButtons.value.has(button)) {
    activeButtons.value.delete(button);
  } else {
    activeButtons.value.add(button);
  }
  console.log("active button is:", activeButtons.value)
}





const items = ref([
  {
    label: 'Add',
    icon: 'pi pi-pencil',
    command: () => {
      toast.add({ severity: 'info', summary: 'Add', detail: 'Data Added' });
    }
  },
  {
    label: 'Update',
    icon: 'pi pi-refresh',
    command: () => {
      toast.add({ severity: 'success', summary: 'Update', detail: 'Data Updated' });
    }
  },
  {
    label: 'Delete',
    icon: 'pi pi-trash',
    command: () => {
      toast.add({ severity: 'error', summary: 'Delete', detail: 'Data Deleted' });
    }
  },
  {
    label: 'Upload',
    icon: 'pi pi-upload',
    command: () => {
      router.push('/fileupload');
    }
  },
  {
    label: 'Vue Website',
    icon: 'pi pi-external-link',
    command: () => {
      window.location.href = 'https://vuejs.org/'
    }
  }
])
</script>