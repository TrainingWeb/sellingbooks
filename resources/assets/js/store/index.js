import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);
const store = new Vuex.Store({
  state: {
    cart: [],
    favorite: [],
    selected: []
  },
  mutations: {
    SET_CART: (state, cart) => {
      state.cart = cart;
    },
    SET_FAVORITE: (state, favorite) => {
      state.favorite = favorite;
    },
    SET_SELECTED: (state, selected) => {
      state.selected = selected;
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
    setSelected({ commit }, val) {
      commit("SET_SELECTED", val);
      localStorage.selected = JSON.stringify(val);
    }
  }
});
export default store;
