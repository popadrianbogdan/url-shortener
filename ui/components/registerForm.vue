<template>
  <ValidationObserver ref="observer_register_form" tag="div" #default="{ validate }">
    <div class="row mt-5 p-5">
      <div class="col-4 offset-4">
        <div class="card text-center">
          <div class="card-header">
            <h4>Sign Up</h4>
          </div>
          <div class="card-block m-3">
            <div class="col-10 offset-1">
              <div class="col-10 offset-1">
                <div class="form-group">
                  <label for="email">Email address</label>
                  <ValidationProvider name="email" rules="required|email" v-slot="{ errors}">
                    <input v-model="email" type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" placeholder="Enter email">
                    <p class="text-danger mt-1">{{ errors[0] }}</p>
                  </ValidationProvider>
                  <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                </div>
              </div>
              <div class="col-10 offset-1 mt-3">
                <div class="form-group">
                  <label for="password">Password</label>
                  <ValidationProvider name="password" rules="required" v-slot="{ errors }">
                    <input v-model="password" type="password" class="form-control" name="password" id="password" placeholder="Password">
                    <p class="text-danger mt-1">{{ errors[0] }}</p>
                  </ValidationProvider>
                </div>
              </div>
              <div class="col-10 offset-1 mt-3 mb-3">
                <button type="submit" class="btn btn-primary" @click="registerUser()">Sign up</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </ValidationObserver>

</template>

<script>
import AuthorizationService from "../services/AuthorizationService";
import notificationMixin from "../mixins/notificationMixin";
import LocalStorageService from "../services/LocalStorageService";
import { EventBus } from "../event-bus"

export default {
  name: "RegisterForm",
  mixins: [notificationMixin],
  data() {
    return {
      email: null,
      password: null,
    };
  },
  methods: {
    async registerUser() {
      const isValid = await this.$refs.observer_register_form.validate();
      if (!isValid) {
        return;
      }

      AuthorizationService.register(this.email, this.password).then(
        response => {
          if (response.status === 201) {
            this.simpleSuccess('User registered successfully. Logging in...')
            AuthorizationService.login(this.email, this.password).then(
                response => {
                  const token = response.data.session.id;
                  const userEmail = response.data.user.email;
                  LocalStorageService.setAuthToken(token);
                  LocalStorageService.setUserEmail(userEmail);
                  this.$router.push('url')
                }
            )
          }
        }
      )
    }
  }
}
</script>