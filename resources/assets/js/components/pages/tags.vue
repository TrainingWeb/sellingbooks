<template>
  <div>
    <v-layout xs12>
      <v-banner :value="{title:namepage,breadcrumbs}"></v-banner>
    </v-layout>
    <v-container>
      <v-layout row wrap>
        <v-flex xs12 md3 class="pl-3 pb-3">
          <v-select :items="filter" item-text="text" return-object v-model="filter_tags" label="--Chọn--" single-line></v-select>
        </v-flex>
      </v-layout>
      <v-container grid-list-xs>
        <v-layout row wrap>
          <v-flex xs12 md6 lg4 v-for="(item,index) in tags" :key="`khoa${index}`">
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
        name: "Tags",
        url: "/list-products",
        disabled: true
      }
    ],
    namepage: "Tags",
    e1: null,
    tags: {},
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
    panigation: {
      page: 1,
      visible: 4,
      length: 7
    },
    filter_tags: ""
  }),
  methods: {
    next(page) {
      this.$router.push(
        "/tags?name=" +
          this.$route.query.name +
          "&&page=" +
          page +
          (this.$route.query.sort ? "sort=" + this.$route.query.sort : "")
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
        console.log(this.$route);
        window.axios
          .get(
            "/tags/" +
              this.$route.query.name +
              "?page=" +
              val +
              (this.$route.query.sort ? "&&sort=" + this.$route.query.sort : "")
          )
          .then(res => {
            this.tags = res.data.data;
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
            "/tags/" +
              this.$route.query.name +
              "?sort=" +
              val +
              (this.$route.query.page ? "&&page=" + this.$route.query.page : "")
          )
          .then(res => {
            this.tags = res.data.data;
            this.panigation.page = res.data.current_page;
            this.panigation.length = res.data.last_page;
          });
      }
      console.log("chuyển axios thành công");
    },
    filter_tags(val) {
      this.$router.push(
        "/tags?name=" + this.$route.query.name + "&&sort=" + val.linkto
      );
    }
  },
  mounted() {
    console.log(this.$route);
    window.axios
      .get(
        "/tags/" +
          this.$route.query.name +
          (this.$route.query.sort ? "sort=" + this.$route.query.sort : "") +
          (this.$route.query.page ? "&&page=" + this.$route.query.page : "")
      )
      .then(response => {
        this.tags = response.data.data;
        console.log("đây là tác phẩm của tags", response.data.data);
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

