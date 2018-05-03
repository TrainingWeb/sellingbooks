import Vue from "vue";
import VueRouter from "vue-router";
Vue.use(VueRouter);
//
import Home from "../components/Home.vue";
import Detail from "../components/pages/detail.vue";
import About from "../components/pages/about.vue";
import Checkout from "../components/pages/checkout.vue";
import Listproducts from "../components/pages/listproducts.vue";
import Card from "../components/pages/card.vue";
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
    },
    {
      path: "/list-products",
      name: "listproducts",
      component: Listproducts
    },
    {
      path: "/card",
      name: "card",
      component: Card
    }
  ]
});
export default router;
