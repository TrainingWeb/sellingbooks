<template>
  <div>
    <v-layout xs12>
      <v-banner :value="{title:namepage,breadcrumbs}"></v-banner>
    </v-layout>
    <v-container v-if="search">
      <v-layout row wrap>
        <v-flex xs12 md3 class="pl-3 pb-3">
          <v-select :items="filter" item-text="text" return-object v-model="filter_search" label="--Chọn--" single-line></v-select>
        </v-flex>
      </v-layout>
      <v-container grid-list-xs>
        <v-layout row wrap>
          <v-flex xs12 md6 lg4 v-for="(item,index) in search" :key="`khoa${index}`">
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
      <h1 class="py-5">Không có kết quả tìm kiếm phù hợp</h1>
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
    search: {},
    filter_search: "",
    namepage: "",
    panigation: {
      page: 1,
      visible: 4,
      length: 5
    }
  }),
  watch: {
    "$route.query.name"(val) {
      this.breadcrumbs[1].name = `${this.$route.query.name}`;
      this.namepage = `Kết quả tìm kiếm: ${this.$route.query.name}`;
      window.axios
        .get(
          "/search?name=" +
            this.$route.query.name +
            "&page=" +
            (this.$route.query.page ? "&&page=" + this.$route.query.page : "") +
            (this.$route.query.sort ? "&&sort=" + this.$route.query.sort : "")
        )
        .then(res => {
          this.search = res.data.data;
          this.panigation.page = res.data.current_page;
          this.panigation.length = res.data.last_page;
        });
    },
    "$route.query.page"(val) {
      if (val) {
        window.axios
          .get(
            "/search?name=" +
              this.$route.query.name +
              "&page=" +
              val +
              (this.$route.query.sort ? "&&sort=" + this.$route.query.sort : "")
          )
          .then(res => {
            this.search = res.data.data;
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
            "/search?name=" +
              this.$route.query.name +
              "&sort=" +
              val +
              (this.$route.query.page ? "&&page=" + this.$route.query.page : "")
          )
          .then(res => {
            this.search = res.data.data;
            this.panigation.page = res.data.current_page;
            this.panigation.length = res.data.last_page;
          });
      }
      console.log("chuyển axios thành công");
    },
    filter_search(val) {
      this.$router.push(
        "/search?name=" + this.$route.query.name + "&&sort=" + val.linkto
      );
    }
  },
  methods: {
    next(page) {
      this.$router.push(
        "/search?name=" +
          this.$route.query.name +
          "&&page=" +
          page +
          (this.$route.query.sort ? "&&sort=" + this.$route.query.sort : "")
      );
      console.log("đưa lên url thành công");
    }
  },
  mounted() {
    this.namepage = `Kết quả tìm kiếm: ${this.$route.query.name}`;
    this.breadcrumbs[1].name = `Kết quả tìm kiếm: ${this.$route.query.name}`;

    window.axios
      .get("/search?name=" + this.$route.query.name)
      .then(response => {
        this.search = response.data.data;
        this.panigation.page = response.data.current_page;
        this.panigation.length = response.data.last_page;
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
