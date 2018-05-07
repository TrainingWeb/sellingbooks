import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);
const store = new Vuex.Store({
  state: {
    cart: [
      {
        book: {},
        quantity: 0
      }
    ],
    favorite: [],
    seach: {}
  },
  mutations: {
    SET_CART: (state, cart) => {
      state.cart = cart;
    },
    SET_FAVORITE: (state, favorite) => {
      state.favorite = favorite;
    },
    SET_SEARCH: (state, seach) => {
      state.seach = seach;
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
    },
    setSeach({ commit }, val) {
      commit("SET_SEARCH", val);
      localStorage.favorite = JSON.stringify(val);
    }
  }
});
export default store;
