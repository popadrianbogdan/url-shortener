<template>
  <div class="container-fluid bg-light main-content">
    <app-header />
    <register-form v-if="registerFormVisible" />
    <login-form v-if="loginFormVisible" />
  </div>
</template>

<script>
import appHeader from "./header";
import registerForm from "./registerForm";
import loginForm from "./loginForm";
import { EventBus } from "../event-bus";
import LocalStorageService from "../services/LocalStorageService";

export default {
  name: "Main",
  components: {
    registerForm,
    loginForm,
    appHeader
  },
  data() {
    return {
      token: null,
      userEmail: null,
      registerFormVisible: false,
      loginFormVisible: true,
    };
  },
  created() {
    let token = LocalStorageService.getAuthToken();
    let userEmail = LocalStorageService.getUserEmail();

    if(token) {
      this.token = token;
    }

    if(userEmail) {
      this.userEmail = userEmail;
    }

    if(this.token && this.userEmail) {
      this.$router.push('url')
    }

    EventBus.$on("registerFormVisible", data => {
      this.registerFormVisible = data;
    });
    EventBus.$on("logInFormVisible", data => {
      this.loginFormVisible = data;
    });
  }
}
</script>