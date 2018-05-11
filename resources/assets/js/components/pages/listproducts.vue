<template>
  <div>
    <v-layout xs12>
      <v-banner :value="{title:namepage,breadcrumbs}"></v-banner>
    </v-layout>
    <v-container>
      <v-layout row wrap>
        <v-flex xs12 md3 class="pl-3 pb-3">
          <v-select :items="filter" item-text="text" return-object v-model="filter_value" label="--Chọn--" single-line></v-select>
        </v-flex>
      </v-layout>
      <v-container grid-list-xs>
        <v-layout row wrap>
          <v-flex xs12 md6 lg4 v-for="(item,index) in books" :key="`khoa${index}`">
            <book-item :book="item"></book-item>
          </v-flex>
        </v-layout>
      </v-container>
      <template>
        <div class="text-xs-center mt-5">
          <v-pagination :length="panigation.length" v-model="panigation.page" @input="next" :total-visible="7"></v-pagination>
        </div>
      </template>
    </v-container>
  </div>
</template>

<script>
export default {
  data: () => ({
    filter_value: "",
    breadcrumbs: [
      {
        name: "Trang Chủ",
        url: "/",
        disabled: false
      },
      {
        name: "Danh sách sản phẩm",
        url: "/list-products",
        disabled: true
      }
    ],
    e1: null,
    filter: [
      {
        text: "Lọc theo tên A-Z",
        linkto: "atoz"
      },
      {
        text: "Lọc theo tên Z-A",
        linkto: "atozdesc"
      },
      {
        text: "Lọc Theo Giá tiền",
        linkto: "price"
      },
      { text: "Lọc theo giá tiền giảm giá" }
    ],
    books: {},
    namepage: "Danh sách sản phẩm",
    // page: 1,
    panigation: {
      page: 1,
      visible: 4,
      length: 7
    }
  }),
  watch: {
    // "$route.query.page"(val) {
    //   if (val) {
    //     window.axios
    //       .get("/books/type/" + this.$route.query.type + "?page=" + val)
    //       .then(res => {
    //         this.books = res.data.data;
    //       });
    //   }
    //   console.log("chuyển axios thành công");
    // },
    filter_value(val) {
      console.log(val.linkto);
      window.axios
        .get("/books/type/" + this.$route.query.type + "?sort=" + val.linkto)
        .then(res => {
          this.books = res.data.data;
        });
      console.log("chuyển axios lọc");
    }
  },
  methods: {
    next(page) {
      let type = this.$route.query.type;
      this.$router.push(
        "list-products?type=" + this.$route.query.type + "&&page=" + page
      );
      console.log("đưa lên url thành công");
    },
    fil(filter_value) {
      console.log("đã vào đây");
      this.$router.push(
        "list-products?type=" +
          this.$route.query.type +
          "?&&sort=" +
          filter_value
      );
      console.log("đưa lên url của lọc thành công");
    }
  },
  mounted() {
    let type = this.$route.query.type;
    window.axios.get("/books/type/" + type).then(res => {
      this.books = res.data.data;
      console.log("mang chị thuy", res.data.data);
    });
  }
};
</script>

<style>
.banner {
  min-height: 350px;
  width: 100%;
}
.color-text a {
  color: white !important;
}

.primary {
  background-color: #00c853 !important;
  border-color: #00c853 !important;
}
</style>
