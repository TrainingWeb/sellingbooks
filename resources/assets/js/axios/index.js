window.axios = require("axios");

window.axios.defaults.headers.post["Content-Type"] = "application/json";
let host = "http://sellingbookstore.test:8080";
let api = "/api";
window.axios.defaults.baseURL = `${host}${api}`;