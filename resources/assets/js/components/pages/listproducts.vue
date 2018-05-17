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
    panigation: {
      page: 1,
      visible: 4,
      length: 7
    }
  }),
  watch: {
    "$route.query.page"(val) {
      if (val) {
        window.axios
          .get(
            "/books/type/" +
              this.$route.query.type +
              "?page=" +
              val +
              (this.$route.query.sort ? "&&sort=" + this.$route.query.sort : "")
          )
          .then(res => {
            window.scrollTo(0, 0);
            this.books = res.data.data;
            this.panigation.page = res.data.current_page;
            this.panigation.length = res.data.last_page;
          });
      }
      console.log("chuyển axios thành công");
    },
    "$route.query.sort"(val) {
      if (val) {
        window.axios
          .get(
            "/books/type/" +
              this.$route.query.type +
              "?sort=" +
              val +
              (this.$route.query.page ? "&&page=" + this.$route.query.page : "")
          )
          .then(res => {
            this.books = res.data.data;
            this.panigation.page = res.data.current_page;
            this.panigation.length = res.data.last_page;
          });
      }
      console.log("chuyển axios thành công");
    },
    filter_value(val) {
      this.$router.push(
        "list-products?type=" + this.$route.query.type + "&&sort=" + val.linkto
      );
    }
  },
  methods: {
    next(page) {
      let type = this.$route.query.type;
      this.$router.push(
        "list-products?type=" +
          this.$route.query.type +
          "&&page=" +
          page +
          (this.$route.query.sort ? "&&sort=" + this.$route.query.sort : "")
      );
      console.log("đưa lên url thành công");
    }
  },
  mounted() {
    let type = this.$route.query.type;
    window.axios
      .get(
        "/books/type/" +
          type +
          "?" +
          (this.$route.query.sort ? "sort=" + this.$route.query.sort : "") +
          (this.$route.query.page ? "&&page=" + this.$route.query.page : "")
      )
      .then(res => {
        this.books = res.data.data;
        this.panigation.page = res.data.current_page;
        this.panigation.length = res.data.last_page;
      });
  }
};
</script>

<style>
.primary {
  background-color: #00c853 !important;
  border-color: #00c853 !important;
}
</style>
