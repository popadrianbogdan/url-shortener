<template>
  <div class="container-fluid pb-5">
    <div class="row">
      <div class="col-6 offset-3">
        <div class="card text-center">
          <div class="card-header">
            <h4>View Url</h4>
          </div>
          <div class="card-block m-3">
            <div class="col-10 offset-1">
                <h6>Long Url:</h6>
            </div>
            <div class="col-10 offset-1">
                <h4>{{ url.longUrl }}</h4>
            </div>
            <div class="col-10 offset-1">
              <h6>Short Url:</h6>
            </div>
            <div class="col-10 offset-1">
              <h4>{{ url.shortUrl }}</h4>
            </div>
            <div class="col-10 offset-1">
              <h6>Creation Date:</h6>
            </div>
            <div class="col-10 offset-1">
              <h4>{{ url.createdAt }}</h4>
            </div>
            <div class="col-10 offset-1">
              <h6>Created By:</h6>
            </div>
            <div class="col-10 offset-1">
              <h4>{{ url.user.email }}</h4>
            </div>
            <div class="col-10 offset-1">
              <h6>Viewed:</h6>
            </div>
            <div class="col-10 offset-1">
              <h4>{{ url.views }} times</h4>
            </div>
            <div class="col-10 offset-1 mt-3 mb-3">
              <button class="btn btn-secondary" @click="visitUrl(url)">Visit Url</button>
              <button class="btn btn-primary" @click="backToList()">Back to list</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {EventBus} from "../event-bus";

export default {
  name: "UrlView",
  props: ['url'],
  data() {
    return {
      viewVisible: false,
    };
  },
  methods: {
    backToList() {
      EventBus.$emit("goToList");
    },
    visitUrl(url) {
      window.open('http://url-shortener.local/' + url.shortUrl, '_blank');
      url.views = url.views + 1;
      EventBus.$emit('urlVisited', url)
    }
  },
}
</script>