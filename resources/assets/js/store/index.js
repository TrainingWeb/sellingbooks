import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);
const store = new Vuex.Store({
  state: {
    count: 0,
    vueX: [],
    cart: [
      {
        book: {},
        quantity: 0
      }
    ]
  },
  mutations: {
    SET_CART: (state, cart) => {
      // Vue.set(state, "cart", cart);
      state.cart = cart;
    },
    increment(state, count) {
      state.count = count;
    },
    SET_VUEX(state, vueX) {
      state.vueX = vueX;
      localStorage.vueX = JSON.stringify(vueX);
    }
  },
  actions: {
    setCart({ commit }, val) {
      commit("SET_CART", val);
      localStorage.cart = JSON.stringify(val);
    }
  }
});
export default store;
