<template>
  <div class="facebook-login">
    <button @click="loginWithFacebook">Login with Facebook</button>
  </div>
</template>

<script>
import axios from "axios";

export default {

  methods: {
    loginWithFacebook() {
      // Use Axios to send a request to your server
      axios
        .get("http://capstone.test/AnahawBE/public/facebook/login")
        .then((response) => {
          // Assuming the server responds with the URL and user name
          if (response.data && response.data.url) {
            window.location.href = response.data.url;
            console.log("User name:", response.data.name);
          }
        })
        .catch((error) => {
          console.error("Error during Facebook login:", error);
          alert("Failed to initiate Facebook login.");
        });
    },
  },
};
</script>

<style scoped>
.facebook-login button {
  background-color: #4267b2;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  font-size: 16px;
  cursor: pointer;
}

.facebook-login button:hover {
  background-color: #365899;
}
</style>
