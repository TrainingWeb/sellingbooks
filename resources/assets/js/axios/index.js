window.axios = require("axios");

window.axios.defaults.headers.post["Content-Type"] = "application/json";
let host = "http://sellingbooks.local";
let api = "/api";
window.axios.defaults.baseURL = `${host}${api}`;
