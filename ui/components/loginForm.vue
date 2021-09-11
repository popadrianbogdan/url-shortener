<template>
  <ValidationObserver ref="observer_login_form" tag="div" #default="{ validate }">
    <div class="row mt-5 p-5">
      <div class="col-4 offset-4">
        <div class="card text-center">
          <div class="card-header">
            <h4>Login</h4>
          </div>
          <div class="card-block m-3">
              <div class="col-10 offset-1">
                <div class="form-group">
                  <label for="email">Email address</label>
                  <ValidationProvider name="email" rules="required|email" v-slot="{ errors}">
                    <input v-model="email" type="email" class="form-control" name="email" id="email" placeholder="Enter email" required>
                    <p class="text-danger mt-1">{{ errors[0] }}</p>
                  </ValidationProvider>
                </div>
              </div>
              <div class="col-10 offset-1">
                <div class="form-group">
                  <label for="password">Password</label>
                  <ValidationProvider name="password" rules="required" v-slot="{ errors }">
                    <input v-model="password" type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                    <p class="text-danger mt-1">{{ errors[0] }}</p>
                  </ValidationProvider>
                </div>
              </div>
              <div class="col-10 offset-1 mt-3 mb-3">
                <button type="submit" class="btn btn-primary" @click="loginUser()">Log in</button>
              </div>
          </div>
        </div>
      </div>
    </div>
  </ValidationObserver>
</template>

<script>
import AuthorizationService from "../services/AuthorizationService";
import LocalStorageService from "../services/LocalStorageService";
import { EventBus } from "../event-bus";
import notificationMixin from "../mixins/notificationMixin";

export default {
  name: "LoginForm",
  mixins: [notificationMixin],
  data() {
    return {
      email: null,
      password: null,
    };
  },
  methods: {
    async loginUser() {
      const isValid = await this.$refs.observer_login_form.validate();
      if (!isValid) {
        return;
      }

      AuthorizationService.login(this.email, this.password).then(
          response => {
            const token = response.data.session.id;
            const userEmail = response.data.user.email;
            LocalStorageService.setAuthToken(token);
            LocalStorageService.setUserEmail(userEmail);
            this.$router.push('url')
          }
      ).catch(
          error => {
            this.simpleError(error.response.data.error.message)
          }
      )
    }
  }
}
</script>