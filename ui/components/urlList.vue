<template>
  <div class="container-fluid pb-5">
    <div class="row">
      <div class="col-5 offset-1 border text-center">
        <span class="text-break">Long Url</span>
      </div>
      <div class="col-2 border text-center">
        <span class="text-break">Short Url</span>
      </div>
      <div class="col-1 border text-center">
        <span class="text-break">Views</span>
      </div>
      <div class="col-2 border text-center">
        <span class="text-break">Actions</span>
      </div>
    </div>
    <div v-for="url in urlsList" :key="url.id" class="row">
      <div class="col-5 offset-1 border text-center">
        <span class="text-break">{{ url.longUrl }}</span>
      </div>
      <div class="col-2 border text-center">
        <span class="text-break">{{ url.shortUrl }}</span>
      </div>
      <div class="col-1 border text-center">
        <span class="text-break">{{ url.views }}</span>
      </div>
      <div class="col-2 border text-center">
        <span class="text-break" @click="goToEdit(url)"><i class="fas fa-edit"></i></span>
        <span class="text-break" @click="deleteUrl(url)"><i class="fas fa-trash-alt"></i></span>
        <span class="text-break" @click="showUrl(url)"><i class="fas fa-eye"></i></span>
      </div>
    </div>
  </div>

</template>

<script>
import UrlRESTConsumer from "../services/UrlRESTConsumer";
import {EventBus} from "../event-bus";

export default {
  name: "UrlList",
  props: ['urlsList'],
  data() {
    return {

    };
  },
  methods: {
    goToEdit(url) {
      EventBus.$emit("goToEdit", url);
    },
    deleteUrl(url) {
      UrlRESTConsumer.deleteUrl(url.id).then(response => {
        EventBus.$emit("urlDeleted", url);
      })
    },
    showUrl(url) {
      UrlRESTConsumer.getUrl(url.id).then(response => {
        EventBus.$emit("urlSelected", response.data);
      })
    },
  },
  created() {

  }
}
</script>