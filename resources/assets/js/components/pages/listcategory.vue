<template>
  <div>
    <v-layout xs12>
      <v-banner :value="{title:namepage,breadcrumbs}"></v-banner>
    </v-layout>
    <v-container>
      <v-layout row wrap>
        <v-flex xs12 md3 class="pl-3 pb-3">
          <v-select :items="filter" v-model="e1" label="--Chọn--" single-line></v-select>
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
          <v-pagination :length="3" v-model="page"></v-pagination>
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
    e1: null,
    filter: [
      { text: "Lọc theo tên A-Z" },
      { text: "Lọc Theo Giá tiền" },
      { text: "Lọc theo giá tiền giảm giá" }
    ],
    listCatagory: {},
    namepage: "Danh sách sản phẩm",
    page: 1
  }),
  methods: {
    loadauthor() {
      window.axios
        .get(
          "/categories/" +
            this.$route.query.type +
            "?slug=" +
            this.$route.query.type
        )
        .then(response => {
          this.listCatagory = response.data.data;
          console.log("đây là tác phẩm tác giả", response.data.data);
        })
        .catch(function(error) {
          console.log(error);
        });
    }
  },
  watch: {
    "$route.query.type"(val) {
      this.breadcrumbs[1].name = `${this.$route.query.type}`;
      this.loadauthor();
    }
  },
  mounted() {
    this.breadcrumbs[1].name = `${this.$route.query.type}`;
    this.loadauthor();
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
