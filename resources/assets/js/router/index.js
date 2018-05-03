import Vue from "vue";
import VueRouter from "vue-router";
Vue.use(VueRouter);
//

import Detail from "../components/pages/detail.vue";
import Home from "../components/Home.vue";
import About from "../components/pages/about.vue";
import Checkout from "../components/pages/checkout.vue";

//
const router = new VueRouter({
  routes: [
    {
      path: "/",
      name: "home",
      component: Home
    },
    {
      path: "/detail",
      name: "detail",
      component: Detail
    },
    {
      path: "/about",
      name: "about",
      component: About
    },
    {
      path: "/check-out",
      name: "checkout",
      component: Checkout
    }
  ]
  // mode: "history"
});
export default router;
