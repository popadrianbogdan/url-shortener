<template>
  <ValidationObserver ref="observer_url_edit_form" tag="div" #default="{ validate }">
    <div class="container-fluid pb-5">
      <div class="row">
        <div class="col-6 offset-3">
          <div class="card text-center">
            <div class="card-header">
              <h4>View Url</h4>
            </div>
            <div class="card-block m-3">
              <div class="form-group">
                <label for="longUrl">Long url</label>
                <ValidationProvider name="longUrl" rules="required" v-slot="{ errors}">
                  <input type="url" class="form-control" name="longUrl" id="longUrl" placeholder="Enter url" v-model="url.longUrl">
                  <p class="text-danger mt-1">{{ errors[0] }}</p>
                </ValidationProvider>
              </div>
              <div class="form-group">
                <label for="shortUrl">Short url</label>
                <ValidationProvider name="shortUrl" rules="required|length:7" v-slot="{ errors}">
                  <input type="url" class="form-control" name="shortUrl" id="shortUrl" placeholder="Enter url" v-model="url.shortUrl">
                  <p class="text-danger mt-1">{{ errors[0] }}</p>
                </ValidationProvider>
              </div>
              <div class="col-10 offset-1 mt-3 mb-3">
                <button class="btn btn-primary" @click="updateUrl()">Update</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </ValidationObserver>
</template>

<script>
import UrlRESTConsumer from "../services/UrlRESTConsumer";
import {EventBus} from "../event-bus";

export default {
  name: "UrlList",
  props: ['url'],
  data() {
    return {
      viewVisible: false,
    };
  },
  methods: {
    async updateUrl() {
      const isValid = await this.$refs.observer_url_edit_form.validate();
      if (!isValid) {
        return;
      }

      UrlRESTConsumer.editUrl(this.url).then(response => {
        EventBus.$emit("urlUpdated", response.data);
      })
    }
  },
}
</script>