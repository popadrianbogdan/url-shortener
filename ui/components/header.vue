<template>
  <div class="row p-5">
    <div class="col-5 offset-1">
      <router-link :to="{ name: 'home' }" class="text-dark text-decoration-none">
        <h4>Url Shortener</h4>
      </router-link>
    </div>
    <div v-if="!token" class="col-1 offset-3">
      <a href="#" @click="showRegisterForm()">Sign Up</a>
    </div>
    <div v-show="userEmail" class="col-3 offset-1">
      <p>Hello {{ userEmail}}</p>
    </div>
    <div v-show="token" class="col-2">
      <a href="#" @click="logOutUser()">Log Out</a>
    </div>
  </div>
</template>

<script>
import AuthorizationService from "../services/AuthorizationService";
import { EventBus } from "../event-bus";

export default {
  name: "Header",
  props: ['token', 'userEmail'],
  methods: {
    showRegisterForm() {
      EventBus.$emit("registerFormVisible", true);
      EventBus.$emit("logInFormVisible", false);
    },
    logOutUser() {
      AuthorizationService.logout().then(response => {
        this.$router.push('/')
      })
      EventBus.$emit("registerFormVisible", false);
      EventBus.$emit("logInFormVisible", true);
    }
  },
}
</script>