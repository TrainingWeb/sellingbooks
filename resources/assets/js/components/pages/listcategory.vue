<template>
  <div>
    <v-layout xs12>
      <v-banner :value="{title:namepage,breadcrumbs}"></v-banner>
    </v-layout>
    <v-container>
      <v-layout row wrap>
        <v-flex xs12 md3 class="pl-3 pb-3">
          <v-select :items="filter" item-text="text" return-object v-model="filter_listCatagory" label="--Chọn--" single-line></v-select>
        </v-flex>
      </v-layout>
      <v-container grid-list-xs>
        <v-layout row wrap>
          <v-flex xs12 md6 lg4 v-for="(item,index) in listCatagory" :key="`khoa${index}`">
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
    namepage: "Danh sách sản phẩm",
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
      length: 5
    },
    filter_listCatagory: ""
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
      this.breadcrumbs[1].name = `${this.$route.query.type}`;
    },
    "$route.query.page"(val) {
      if (val) {
        window.axios
          .get(
            "/categories/" +
              this.$route.query.type +
              "?page=" +
              val +
              (this.$route.query.sort ? "&&sort=" + this.$route.query.sort : "")
          )
          .then(res => {
            this.listCatagory = res.data.books.data;
            this.panigation.page = res.data.books.current_page;
            this.panigation.length = res.data.books.last_page;
          });
      }
      console.log("chuyển axios thành công");
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
            this.listCatagory = res.data.books.data;
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
      .then(response => {
        this.listCatagory = response.data.books.data;
        // console.log(this.listCatagory);
        this.panigation.page = response.data.books.current_page;
        this.panigation.length = response.data.books.last_page;
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