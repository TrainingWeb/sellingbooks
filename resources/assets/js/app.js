/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Vue from "vue";
import Vuetify from "vuetify";
import "vuetify/dist/vuetify.min.css";
import router from "./router";
import App from "./App.vue";

// Import global components
import BookItem from "./components/extends/BookItem";
Vue.component("BookItem", BookItem);
//
import VBanner from "./components/extends/Banner";
Vue.component("VBanner", VBanner);
//
Vue.use(Vuetify);

const app = new Vue({
  el: "#app",
  router,
  //   store,
  render: h => h(App)
});
