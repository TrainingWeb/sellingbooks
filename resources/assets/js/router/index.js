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
import Search from "../components/pages/search.vue";
import Favorite from "../components/pages/favorite.vue";
import Listcategory from "../components/pages/listcategory.vue";
import Listauthor from "../components/pages/listauthor.vue";
import Tags from "../components/pages/tags.vue";
import Forgotpassword from "../components/pages/forgotpassword.vue";
import Resetpassword from "../components/pages/resetpassword.vue";

//
const router = new VueRouter({
  routes: [{
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
    },
    {
      path: "/search",
      name: "search",
      component: Search
    },
    {
      path: "/favorite",
      name: "favorite",
      component: Favorite
    },
    {
      path: "/list-category",
      name: "listcategory",
      component: Listcategory
    },
    {
      path: "/list-author",
      name: "listauthor",
      component: Listauthor
    },
    {
      path: "/tags",
      name: "tags",
      component: Tags
    },
    {
      path: "/forgotpassword",
      name: "forgotpassword",
      component: Forgotpassword
    },
    {
      path: "/resetpassword",
      name: "resetpassword",
      component: Resetpassword
    }
  ]
});
export default router;