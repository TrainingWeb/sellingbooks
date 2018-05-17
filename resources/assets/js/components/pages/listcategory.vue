<template>
  <div>
    <v-layout xs12>
      <v-banner :value="{title:namepage,breadcrumbs}"></v-banner>
    </v-layout>
    <v-container v-if="listCatagory">
      <v-layout row wrap>
        <v-flex xs12 md3 class="pl-3 pb-3">
          <v-select :items="filter" item-text="text" return-object v-model="filter_listCatagory" label="--Chọn--" single-line></v-select>
        </v-flex>
      </v-layout>
      <v-container grid-list-xs>
        <v-layout row wrap>
          <v-flex xs12 md6 lg4 v-for="(item,index) in listCatagory.data" :key="`khoa${index}`">
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
    <v-container v-else>
      <h1 class="py-5 text-xs-center">Không có tác phẩm nào thuộc danh mục này</h1>
    </v-container>
  </div>
</template>

<script>
export default {
  data: () => ({
    breadcrumbs: [
      {
        name: "Trang Chủ",
        url: "/",
        disabled: false
      },
      {
        name: "",
        url: "/list-products",
        disabled: true
      }
    ],
    namepage: "Danh mục sản phẩm",
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
    listCatagory: {},

    panigation: {
      page: 1,
      visible: 4,
      length: null
    },
    filter_listCatagory: "",
    category: ""
  }),
  methods: {
    next(page) {
      this.$router.push(
        "/list-category/?type=" +
          this.$route.query.type +
          "&&page=" +
          page +
          (this.$route.query.sort ? "&&sort=" + this.$route.query.sort : "")
      );
      console.log("đưa lên url thành công");
    }
  },
  watch: {
    "$route.query.type"(val) {
      window.axios
        .get(
          "/categories/" +
            this.$route.query.type +
            "?page=" +
            val +
            (this.$route.query.sort ? "&&sort=" + this.$route.query.sort : "")
        )
        .then(res => {
          if (!res.data.Message) {
            this.listCatagory = res.data.books;
            this.category = res.data.data.name;
            this.breadcrumbs[1].name = `${this.category}`;
            this.panigation.page = res.data.books.current_page;
            this.panigation.length = res.data.books.last_page;
          } else {
            console.log(res.data.data.name);
            this.listCatagory = res.data.books;
            this.category = res.data.data.name;
            this.breadcrumbs[1].name = `${this.category}`;
          }
        });
    },
    "$route.query.page"(val) {
      if (val) {
        window.axios
          .get(
            "/categories/" +
              this.$route.query.type +
              "?page=" +
              (this.$route.query.page
                ? "&&page=" + this.$route.query.page
                : "") +
              (this.$route.query.sort ? "&&sort=" + this.$route.query.sort : "")
          )
          .then(res => {
            window.scrollTo(0, 0);
            this.listCatagory = res.data.books;
            this.panigation.page = res.data.books.current_page;
            this.panigation.length = res.data.books.last_page;
          });
      }
    },
    "$route.query.sort"(val) {
      if (val) {
        window.axios
          .get(
            "/categories/" +
              this.$route.query.type +
              "?sort=" +
              val +
              (this.$route.query.page ? "&&page=" + this.$route.query.page : "")
          )
          .then(res => {
            this.listCatagory = res.data.books;
            this.panigation.page = res.data.books.current_page;
            this.panigation.length = res.data.books.last_page;
          });
      }
      console.log("chuyển axios thành công");
    },
    filter_listCatagory(val) {
      this.$router.push(
        "/list-category/?type=" +
          this.$route.query.type +
          "&&sort=" +
          val.linkto
      );
    }
  },
  mounted() {
    this.breadcrumbs[1].name = `${this.$route.query.type}`;
    window.axios
      .get(
        "/categories/" +
          this.$route.query.type +
          "?" +
          (this.$route.query.sort ? "sort=" + this.$route.query.sort : "") +
          (this.$route.query.page ? "&&page=" + this.$route.query.page : "")
      )
      .then(res => {
        if (!res.data.Message) {
          this.listCatagory = res.data.books;
          console.log(response.data.Message);
          this.category = res.data.data.name;
          this.breadcrumbs[1].name = `${this.category}`;
          this.panigation.page = res.data.books.current_page;
          this.panigation.length = res.data.books.last_page;
        } else {
          this.listCatagory = res.data.books;
          this.category = res.data.data.name;
          this.breadcrumbs[1].name = `${this.category}`;
        }
      })
      .catch(function(error) {
        console.log(error);
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