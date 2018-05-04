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
    ],
    favorite: []
  },
  mutations: {
    SET_CART: (state, cart) => {
      // Vue.set(state, "cart", cart);
      state.cart = cart;
    },
    SET_FAVORITE: (state, favorite) => {
      state.favorite = favorite;
    },
    increment(state, count) {
      state.count = count;
    }
  },
  actions: {
    setCart({ commit }, val) {
      commit("SET_CART", val);
      localStorage.cart = JSON.stringify(val);
    },
    setFavorite({ commit }, val) {
      commit("SET_FAVORITE", val);
      localStorage.favorite = JSON.stringify(val);
    }
  }
});
export default store;
