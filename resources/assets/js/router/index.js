import Vue from "vue";
import VueRouter from "vue-router";
Vue.use(VueRouter);
//
import Home from "../components/Home.vue";
import Detail from "../components/pages/detail.vue";
import Footer from "../components/pages/footer.vue";
import Listproducts from "../components/pages/listproducts.vue";
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
      path: "/footer",
      name: "footer",
      component: Footer
    },
    {
      path: "/list-products",
      name: "listproducts",
      component: Listproducts
    }
  ]
});
export default router;
