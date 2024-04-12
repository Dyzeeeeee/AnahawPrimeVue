<script setup>
// import { useLayout } from '@/layout/composables/layout';
import { ref, computed, onMounted } from "vue";
import { useStore } from "vuex";
// import facebookLogin from "facebook-login-vuejs";
// import AppConfig from '@/components/AppLayout/AppConfig.vue';
import { PhotoService } from "@/service/PhotoService";
import AccountService from "@/service/AccountService";
import { useToast } from "primevue/usetoast";
import { useRouter } from "vue-router";
const router = useRouter();
const store = useStore();

const toast = useToast();

const email = ref("");
const password = ref("");
const checked = ref(false);

const login = async () => {
  try {
    const response = await AccountService.login({
      email: email.value,
      password: password.value,
    });
    store.dispatch("setUser", response.session);
    router.push("/admin/counter");
  } catch (error) {
    console.error("Error logging in:", error);
    toast.add({
      severity: "error",
      summary: "Login Fail",
      detail: "Account successfully created",
      life: "3000",
    });
  }
};

const role = ref("customer");

const newAccount = ref({
  firstname: "",
  lastname: "",
  email: "",
  phone: "",
  password: "",
  role: role.value,
});
const signUpVisible = ref(false);

const signUpAccount = async () => {
  try {
    await AccountService.createAccount(newAccount.value);
    toast.add({
      severity: "success",
      summary: "Success",
      detail: "Account successfully created",
      life: "3000",
    });
    signUpVisible.value = false;
    email.value = newAccount.value.email;
    password.value = newAccount.value.password;

    newAccount.value = {
      firstname: "",
      lastname: "",
      email: "",
      password: "",
    };
    active.value = 0;
  } catch (error) {
    console.error("Error adding menu item:", error);
    toast.add({
      severity: "error",
      summary: "Error",
      detail: "Did not work huh",
      life: "3000",
    });
  }
};
function validateEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
}

const isNextDisabled = computed(() => {
  switch (active.value) {
    case 0:
      return !newAccount.value.firstname || !newAccount.value.lastname;
    case 1:
      return !validateEmail(newAccount.value.email);
    case 2:
      return !newAccount.value.phone;
    case 3:
      return !newAccount.value.password;
    default:
      return true; // Default case for unknown steps
  }
});

const images = ref();
const responsiveOptions = ref([
  {
    breakpoint: "1300px",
    numVisible: 4,
  },
  {
    breakpoint: "575px",
    numVisible: 1,
  },
]);

const active = ref(0);

onMounted(async () => {
  PhotoService.getImages().then((data) => (images.value = data));
  await loadGoogleApi();
  window.fbAsyncInit = function () {
    FB.init({
      appId: "790378076376898",
      cookie: true,
      xfbml: true,
      version: "v19.0",
    });
    FB.AppEvents.logPageView();
    checkLoginStatus();
  };

  (function (d, s, id) {
    var js,
      fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {
      return;
    }
    js = d.createElement(s);
    js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  })(document, "script", "facebook-jssdk");
});

function checkLoginStatus() {
  FB.getLoginStatus(function (response) {
    statusChangeCallback(response);
  });
}

function statusChangeCallback(response) {
  if (response.status === "connected") {
    // The user is logged in and has authenticated your app
    // You can now use the Graph API, for example:
    FB.api("/me", function (response) {
      console.log("Successful login for: " + response.name);
    });
  } else {
    // The user is not logged into your app or we are unable to tell
    console.log("User not logged in or unable to tell");
  }
}

const loginWithFacebook = () => {
  FB.login(
    function (response) {
      if (response.authResponse) {
        console.log("Welcome! Fetching your information.... ");
        FB.api(
          "/me",
          { fields: "id,name,email,first_name,last_name,gender,locale,timezone" },
          function (response) {
            console.log("Good to see you, " + response.name + ".");
            // Store all user information in a variable
            const userData = {
              user_id: response.id,
              name: response.name,
              first_name: response.first_name,
              last_name: response.last_name,
              email: response.email,
              gender: response.gender,
              locale: response.locale,
              timezone: response.timezone,
            };
            console.log("User data:", userData);

            // Store the user data in Vuex store
            store.commit("setUser", userData);

            // Redirect the user to another page
            router.push("/admin/counter");
            newAccount.value = {
              firstname: response.first_name,
              lastname: response.last_name,
              phone: "None provided",
              email: response.email,
              password: "default",
              role: "customer",
            };
            // Assuming newAccount.value is already defined and filled with the necessary data
            // AccountService.createAccount(newAccount.value);
            signUpAccount();
          }
        );
      } else {
        console.log("User cancelled login or did not fully authorize.");
      }
    },
    { scope: "email" }
  );
};

function loadGoogleApi() {
  const script = document.createElement("script");
  script.src = "https://apis.google.com/js/api:client.js";
  script.onload = () => initGoogleSignIn();
  document.head.appendChild(script);
}

// Initialize Google Sign-in
function initGoogleSignIn() {
  gapi.load("auth2", function () {
    gapi.auth2.init({
      client_id:
        "215113898368-i535eo8dovjh4pdud4etlol95ss99hq0.apps.googleusercontent.com", // Replace with your Client ID
    });
  });
}

const loginWithGoogle = () => {
  const auth2 = gapi.auth2.getAuthInstance();
  auth2
    .signIn()
    .then((googleUser) => {
      const profile = googleUser.getBasicProfile();
      console.log("name: ", profile.getName()); 
      // const userData = {
      //   user_id: profile.getId(),
      //   name: profile.getName(),
      //   email: profile.getEmail(),
      //   imageUrl: profile.getImageUrl(),
      // };
      // store.commit("setUser", userData);
      // router.push("/admin/counter");
      // Optionally, save the Google profile data or create an account
    })
    .catch((error) => {
      console.error("Error logging in with Google: ", error);
      toast.add({
        severity: "error",
        summary: "Google Login Fail",
        detail: "Failed to log in with Google",
        life: 3000,
      });
    });
};
</script>

<template>
  <Toast />
  <div
    class="surface-ground flex align-items-center justify-content-evenly min-h-screen min-w-screen overflow-hidden"
  >
    <div class="flex flex-column align-items-center justify-content-end">
      <!-- <div class="w-full surface-card p-4" style="border-radius: 53px; border-top-right-radius: 53px;"> -->
      <Galleria
        :value="images"
        :responsiveOptions="responsiveOptions"
        :numVisible="5"
        :circular="true"
        containerStyle="max-width: 640px;  border-radius: 53px; "
        :showThumbnails="false"
        :autoPlay="true"
      >
        <template #item="slotProps">
          <div style="position: relative">
            <img
              :src="slotProps.item.itemImageSrc"
              :alt="slotProps.item.alt"
              style="width: 100%; display: block; border-radius: 53px; max-height: 500px"
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

      <!-- </div> -->
    </div>

    <div class="flex flex-column align-items-center">
      <div
        class="w-full surface-card pt-5 pb-5 px-5 sm:px-8"
        style="border-top-left-radius: 53px; border-top-right-radius: 53px"
      >
        <div class="pb-4 justify-content-center flex">
          <img
            src="@/assets/images/logo.png"
            alt="Anahaw logo"
            class=""
            style="height: 70px"
          />
        </div>
        <div>
          <InputText
            id="email1"
            type="text"
            placeholder="Email or phone"
            class="w-full mb-4"
            style="padding: 1rem"
            v-model="email"
          />
          <Password
            :feedback="false"
            id="password1"
            v-model="password"
            placeholder="Password"
            :toggleMask="true"
            class="w-full mb-3"
            inputClass="w-full"
            :inputStyle="{ padding: '1rem' }"
          >
          </Password>

          <div class="flex align-items-center justify-content-between mb-5 gap-5">
            <div class="flex align-items-center">
              <Checkbox v-model="checked" id="rememberme1" binary class="mr-2"></Checkbox>
              <label for="rememberme1">Remember me</label>
            </div>
            <a
              class="font-medium no-underline ml-2 text-right cursor-pointer"
              style="color: var(--primary-color)"
              >Forgot password?</a
            >
          </div>
          <Button label="Log in" class="w-full p-3 text-xl" @click="login"></Button>
        </div>
        <Divider align="center" type="dotted">
          <b>OR</b>
        </Divider>
        <div class="justify-content-center flex mb-3">
          <Button
            label="Log in with Facebook"
            outlined
            class="w-full p-3 text-xl"
            icon="pi pi-facebook"
            severity="info"
            iconPos="left"
            @click="loginWithFacebook"
          />
        </div>
        <div class="justify-content-center flex">
          <Button
            label="Log in with Google"
            outlined
            class="w-full p-3 text-xl"
            icon="pi pi-google"
            iconPos="left"
            severity="danger"
            @click="loginWithGoogle"
          />
        </div>
      </div>
      <div
        class="w-full surface-card py-4 px-4 sm:px-8 mt-3"
        style="border-bottom-left-radius: 53px; border-bottom-right-radius: 53px"
      >
        <div class="justify-content-center align-items-center flex text-lg">
          Don't have an account yet?
          <Button link class="text-primary p-0 pl-2" @click="signUpVisible = true">
            Sign up</Button
          >
        </div>
      </div>
    </div>
  </div>

  <Dialog
    v-model:visible="signUpVisible"
    modal
    header="Create your account"
    :style="{ width: '40rem' }"
    :pt="{
      root: 'border-none',
      mask: {
        style: 'backdrop-filter: blur(10px)',
      },
    }"
  >
    <Stepper v-model:activeStep="active">
      <StepperPanel>
        <template #header="{ index, clickCallback }">
          <button
            class="bg-transparent border-none inline-flex flex-column gap-2"
            @click="clickCallback"
          >
            <span
              :class="[
                'border-round border-2 w-3rem h-3rem inline-flex align-items-center justify-content-center',
                {
                  'bg-primary border-primary': index <= active,
                  'surface-border': index > active,
                },
              ]"
            >
              <i class="pi pi-user" />
            </span>
          </button>
        </template>
        <template #content="{ nextCallback }">
          <div
            class="flex flex-column gap-2 mx-auto"
            style="min-height: 16rem; max-width: 100%"
          >
            <div class="text-center mt-0 text-xl font-semibold mb-3">
              What is your name?
            </div>
            <div class="flex gap-2">
              <div class="field p-fluid flex-1">
                <InputText
                  id="input"
                  v-model="newAccount.firstname"
                  type="text"
                  placeholder="First Name"
                />
              </div>
              <div class="field p-fluid flex-1">
                <InputText
                  id="input"
                  v-model="newAccount.lastname"
                  type="text"
                  placeholder="Last Name"
                />
              </div>
            </div>
          </div>
          <div class="flex pt-4 justify-content-end">
            <Button
              label="Next"
              icon="pi pi-arrow-right"
              iconPos="right"
              :disabled="isNextDisabled"
              @click="nextCallback"
            />
          </div>
        </template>
      </StepperPanel>
      <StepperPanel>
        <template #header="{ index, clickCallback }">
          <button
            class="bg-transparent border-none inline-flex flex-column gap-2"
            @click="clickCallback"
          >
            <span
              :class="[
                'border-round border-2 w-3rem h-3rem inline-flex align-items-center justify-content-center',
                {
                  'bg-primary border-primary': index <= active,
                  'surface-border': index > active,
                },
              ]"
            >
              <i class="pi pi-envelope" />
            </span>
          </button>
        </template>
        <template #content="{ prevCallback, nextCallback }">
          <div
            class="flex flex-column gap-2 mx-auto"
            style="min-height: 16rem; max-width: 100%"
          >
            <div class="text-center mt-0 text-xl font-semibold mb-3">
              Enter your email address
            </div>
            <div class="flex gap-2">
              <div class="field p-fluid flex-1">
                <InputText
                  id="input"
                  v-model="newAccount.firstname"
                  type="text"
                  placeholder="First Name"
                  disabled
                />
              </div>
              <div class="field p-fluid flex-1">
                <InputText
                  id="input"
                  v-model="newAccount.lastname"
                  type="text"
                  placeholder="Last Name"
                  disabled
                />
              </div>
            </div>
            <div class="flex gap-2">
              <div class="field p-fluid flex-1">
                <InputText
                  id="input"
                  v-model="newAccount.email"
                  type="text"
                  placeholder="Email"
                />
              </div>
            </div>
          </div>
          <div class="flex pt-4 justify-content-between">
            <Button
              label="Back"
              severity="secondary"
              icon="pi pi-arrow-left"
              @click="prevCallback"
            />
            <Button
              label="Next"
              icon="pi pi-arrow-right"
              iconPos="right"
              :disabled="isNextDisabled"
              @click="nextCallback"
            />
          </div>
        </template>
      </StepperPanel>
      <StepperPanel>
        <template #header="{ index, clickCallback }">
          <button
            class="bg-transparent border-none inline-flex flex-column gap-2"
            @click="clickCallback"
          >
            <span
              :class="[
                'border-round border-2 w-3rem h-3rem inline-flex align-items-center justify-content-center',
                {
                  'bg-primary border-primary': index <= active,
                  'surface-border': index > active,
                },
              ]"
            >
              <i class="pi pi-phone" />
            </span>
          </button>
        </template>
        <template #content="{ prevCallback, nextCallback }">
          <div
            class="flex flex-column gap-2 mx-auto"
            style="min-height: 16rem; max-width: 100%"
          >
            <div class="text-center mt-0 text-xl font-semibold mb-3">
              Enter your phone number
            </div>
            <div class="flex gap-2">
              <div class="field p-fluid flex-1">
                <InputText
                  id="input"
                  v-model="newAccount.firstname"
                  type="text"
                  placeholder="First Name"
                  disabled
                />
              </div>
              <div class="field p-fluid flex-1">
                <InputText
                  id="input"
                  v-model="newAccount.lastname"
                  type="text"
                  placeholder="Last Name"
                  disabled
                />
              </div>
            </div>
            <div class="flex gap-2">
              <div class="field p-fluid flex-1">
                <InputText
                  id="input"
                  v-model="newAccount.email"
                  type="text"
                  placeholder="Email"
                  disabled
                />
              </div>
            </div>
            <div class="flex gap-2">
              <div class="field p-fluid flex-1">
                <InputText
                  id="input"
                  v-model="newAccount.phone"
                  type="text"
                  placeholder="Phone number"
                />
              </div>
            </div>
          </div>
          <div class="flex pt-4 justify-content-between">
            <Button
              label="Back"
              severity="secondary"
              icon="pi pi-arrow-left"
              @click="prevCallback"
            />
            <Button
              label="Next"
              icon="pi pi-arrow-right"
              iconPos="right"
              :disabled="isNextDisabled"
              @click="nextCallback"
            />
          </div>
        </template>
      </StepperPanel>
      <StepperPanel>
        <template #header="{ index, clickCallback }">
          <button
            class="bg-transparent border-none inline-flex flex-column gap-2"
            @click="clickCallback"
          >
            <span
              :class="[
                'border-round border-2 w-3rem h-3rem inline-flex align-items-center justify-content-center',
                {
                  'bg-primary border-primary': index <= active,
                  'surface-border': index > active,
                },
              ]"
            >
              <i class="pi pi-lock" />
            </span>
          </button>
        </template>
        <template #content="{ prevCallback, nextCallback }">
          <div
            class="flex flex-column gap-2 mx-auto"
            style="min-height: 16rem; max-width: 100%"
          >
            <div class="text-center mt-0 text-xl font-semibold mb-3">
              Protect your account
            </div>
            <div class="flex gap-2">
              <div class="field p-fluid flex-1">
                <InputText
                  id="input"
                  v-model="newAccount.firstname"
                  type="text"
                  placeholder="First Name"
                  disabled
                />
              </div>
              <div class="field p-fluid flex-1">
                <InputText
                  id="input"
                  v-model="newAccount.lastname"
                  type="text"
                  placeholder="Last Name"
                  disabled
                />
              </div>
            </div>
            <div class="flex gap-2">
              <div class="field p-fluid flex-1">
                <InputText
                  id="input"
                  v-model="newAccount.email"
                  type="text"
                  placeholder="Email"
                  disabled
                />
              </div>
            </div>
            <div class="flex gap-2">
              <div class="field p-fluid flex-1">
                <InputText
                  id="input"
                  v-model="newAccount.phone"
                  type="text"
                  placeholder="Phone number"
                  disabled
                />
              </div>
            </div>
            <div class="flex gap-2">
              <div class="field p-fluid flex-1">
                <Password
                  id="password1"
                  v-model="newAccount.password"
                  placeholder="Password"
                  :toggleMask="true"
                  class="w-full"
                  inputClass="w-full"
                >
                </Password>
              </div>
            </div>
            <div class="justify-content-center flex text-sm p-fluid">
              <span
                >By clicking Sign Up, you agree to our
                <router-link to="/privacy-policy">Terms and Privacy Policy</router-link>.
                You may receive SMS Notifications from us and can opt out any time.</span
              >
            </div>
          </div>
          <div class="flex pt-4 justify-content-between">
            <Button
              label="Back"
              severity="secondary"
              icon="pi pi-arrow-left"
              @click="prevCallback"
            />
            <Button
              label="Sign Up"
              icon="pi pi-check"
              iconPos="right"
              @click="signUpAccount"
              :disabled="isNextDisabled"
            />
          </div>
        </template>
      </StepperPanel>
    </Stepper>
  </Dialog>
</template>

<style scoped>
.pi-eye {
  transform: scale(1.6);
  margin-right: 1rem;
}

.pi-eye-slash {
  transform: scale(1.6);
  margin-right: 1rem;
}
</style>
