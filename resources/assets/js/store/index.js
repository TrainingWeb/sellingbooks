import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);
const store = new Vuex.Store({
  state: {
    cart: [],
    favorite: [],
    selected: [],
    token: null,
    user: {},
    message: null
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
    },
    SET_TOKEN: (state, token) => {
      state.token = token;
    },
    SET_USER: (state, user) => {
      state.user = user;
    },
    SET_MESSAGE: (state, message) => {
      state.message = message;
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
    },
    setToken({ commit }, val) {
      commit("SET_TOKEN", val);
      localStorage.token = val;
      // window.axios.defaults.headers = {
      //   "Authorization": 'Bearer ' + val
      // }
      window.axios.defaults.headers.common["Authorization"] = "Bearer " + val;
    },
    setUser({ commit }, val) {
      commit("SET_USER", val);
      localStorage.user = JSON.stringify(val);
    },
    setMessage({ commit }, val) {
      commit("SET_MESSAGE", val);
      localStorage.message = val;
    }
  }
});
export default store;
