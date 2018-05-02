import Vue from "vue";
import VueRouter from "vue-router";
Vue.use(VueRouter);
//

import Home from "../components/ExampleComponent.vue";

//
const router = new VueRouter({
  routes: [
    {
      path: "/",
      name: "home",
      component: Home
    }
  ],
  mode: "history"
});
export default router;
