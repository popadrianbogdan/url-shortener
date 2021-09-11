<template>
  <div class="container-fluid bg-light main-content">
    <app-header :token="token" :user-email="userEmail" />
    <div v-if="listVisible" class="row mt-5 p-5">
      <div class="col-8 offset-2">
        <div class="card text-center">
          <div class="card-header">
            <h4> Welcome </h4>
          </div>
          <ValidationObserver ref="observer_url_create_form" tag="div" #default="{ validate }">
            <div class="card-block m-3">
              <div class="col-10 offset-1">
                <div class="form-group">
                  <label for="longUrl">Enter URL to shorten</label>
                  <ValidationProvider name="longUrl" rules="required" v-slot="{ errors}">
                    <input type="url" class="form-control" name="longUrl" id="longUrl" placeholder="Enter url" v-model="longUrl">
                    <p class="text-danger mt-1">{{ errors[0] }}</p>
                  </ValidationProvider>
                  <input type="hidden" name="user_email" id="user_email" v-model="userEmail">
                  <input type="hidden" name="views" id="views" v-model="views">
                </div>
              </div>
              <div class="col-10 offset-1 mt-3 mb-3">
                <button type="submit" class="btn btn-primary" @click="saveUrl()">Make short Url</button>
              </div>
            </div>
          </ValidationObserver>
        </div>
      </div>
    </div>
    <urls-list v-if="listVisible" :urlsList="urlsList" />
    <url-view v-if="viewVisible" :url="selectedUrl" />
    <url-edit v-if="editVisible" :url="selectedUrl" />
  </div>
</template>

<script>
import LocalStorageService from "../services/LocalStorageService";
import UrlRESTConsumer from "../services/UrlRESTConsumer";
import appHeader from "./header";
import urlsList from "./urlList";
import urlView from "./urlView";
import urlEdit from "./urlEdit";
import {EventBus} from "../event-bus";

export default {
  name: "UrlPage",
  components: {
    appHeader,
    urlsList,
    urlView,
    urlEdit,
  },
  data() {
    return {
      token: null,
      userEmail: null,
      views: 0,
      longUrl: null,
      urlsList: [],
      selectedUrl: null,
      listVisible: true,
      viewVisible: false,
      editVisible: false,
    };
  },
  methods: {
    async saveUrl() {
      const isValid = await this.$refs.observer_url_create_form.validate();
      if (!isValid) {
        return;
      }

      UrlRESTConsumer.createUrl({
        longUrl: this.longUrl,
        userEmail:  this.userEmail,
      }).then(response => {
        this.urlsList.push(response.data)
      })
    }
  },
  created() {
    this.token = LocalStorageService.getAuthToken();
    this.userEmail = LocalStorageService.getUserEmail();
    EventBus.$on("urlSelected", data => {
      this.selectedUrl = data;
      this.listVisible = false;
      this.viewVisible = true;
      this.editVisible = false;
    });
    EventBus.$on("goToList", data => {
      this.listVisible = true;
      this.viewVisible = false;
      this.editVisible = false;
    });
    EventBus.$on("goToEdit", data => {
      this.selectedUrl = data;
      this.listVisible = false;
      this.viewVisible = false;
      this.editVisible = true;
    });
    EventBus.$on("urlUpdated", data => {
      this.urlsList[this.urlsList.findIndex(url => url.id = data.id)] = data;
      this.listVisible = true;
      this.viewVisible = false;
      this.editVisible = false;
    });
    EventBus.$on("urlVisited", data => {
      this.urlsList[this.urlsList.findIndex(url => url.id = data.id)] = data;
    });
    EventBus.$on("urlDeleted", data => {
      this.urlsList.splice(this.urlsList.findIndex(url => url.id = data), 1)
    });
    UrlRESTConsumer.getUrls().then(response => {
      this.urlsList = response.data
    })
  }
}
</script>