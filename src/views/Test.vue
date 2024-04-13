<script setup>
import { googleSdkLoaded } from "vue3-google-login";

const login = () => {
  googleSdkLoaded((google) => {
    const tokenClient = google.accounts.oauth2.initTokenClient({
      client_id:
        "215113898368-i535eo8dovjh4pdud4etlol95ss99hq0.apps.googleusercontent.com",
      scope: "email profile openid",
      callback: (response) => {
        if (response.error !== undefined) {
          console.error("Failed to get the token", response);
          return;
        }
        console.log("Access Token:", response.access_token);
        // Optionally, get user information using the access token
        fetchUserInfo(response.access_token);
      },
    });
    tokenClient.requestAccessToken();
  });
};

const fetchUserInfo = (accessToken) => {
  fetch("https://www.googleapis.com/oauth2/v2/userinfo", {
    headers: new Headers({
      Authorization: `Bearer ${accessToken}`,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      console.log("User Info:", data.name);
    })
    .catch((error) => {
      console.error("Error fetching user information:", error);
    });
};
</script>

<template>
  <Button @click="login">Login Using Google</Button>
</template>
