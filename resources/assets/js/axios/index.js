window.axios = require("axios");

window.axios.defaults.headers.post["Content-Type"] = "application/json";
<<<<<<< HEAD
let host = "http://sellingbookstore.test";
=======
let host = "http://selling-books.local";
>>>>>>> 13d11bf51da10f7d9c0f49115855a61437c2fd78
let api = "/api";
window.axios.defaults.baseURL = `${host}${api}`;