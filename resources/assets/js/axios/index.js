window.axios = require("axios");

window.axios.defaults.headers.post["Content-Type"] = "application/json";
<<<<<<< HEAD
let host = "http://sellingbookstore.test";
=======
let host = "http://sellingbooks.local";
>>>>>>> 1520db347d3b4a8ef613539e2270013b11ca6cf9
let api = "/api";
window.axios.defaults.baseURL = `${host}${api}`;
