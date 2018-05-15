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
import store from "./store";

// Import global components
import BookItem from "./components/extends/BookItem";
Vue.component("BookItem", BookItem);
//
import VBanner from "./components/extends/Banner";
Vue.component("VBanner", VBanner);

require("./axios");
//

// Save card

if (localStorage.cart) store.commit("SET_CART", JSON.parse(localStorage.cart));
//
if (localStorage.favorite)
  store.commit("SET_FAVORITE", JSON.parse(localStorage.favorite));
//
if (localStorage.selected)
  store.commit("SET_SELECTED", JSON.parse(localStorage.selected));
//
if (localStorage.token && localStorage.token != "undefined")
  store.dispatch("setToken", localStorage.token);

if (localStorage.user && localStorage.user != "undefined")
  store.dispatch("setUser", JSON.parse(localStorage.user));

if (localStorage.message && localStorage.message != "undefined")
  store.dispatch("setMessage", localStorage.message);

Vue.use(Vuetify);

const app = new Vue({
  el: "#app",
  router,
  store,
  render: h => h(App)
});
