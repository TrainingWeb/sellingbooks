<template>
  <div>
    <v-layout xs12>
      <v-banner :value="{title:namepage,breadcrumbs}"></v-banner>
    </v-layout>
    <v-container v-if="listauthor">
      <v-layout row wrap>
        <v-flex xs12 md3 class="pl-3 pb-3">
          <v-select :items="filter" item-text="text" return-object v-model="filter_value" label="--Chọn--" single-line></v-select>
        </v-flex>
      </v-layout>
      <v-container grid-list-xs>
        <v-layout row wrap>
          <v-flex xs12 md6 lg4 v-for="(item,index) in listauthor.data" :key="`khoaauthor-${index}`">
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
    <v-container v-else class="py-5">
      <h1 class="py-5 text-xs-center">Không có tác phẩm nào thuộc tác giả này</h1>
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
    panigation: {
      page: 1,
      visible: 4,
      length: null
    },

    namepage: "Tác giả",
    listauthor: {},
    author:"",
  }),
  watch: {
    "$route.query.type"(val) {
     
      window.axios
        .get(
          "/authors/" +
            this.$route.query.type +
            "?page=" +
            val +
            (this.$route.query.sort ? "&&sort=" + this.$route.query.sort : "")
        )
        .then(response => {
          
         if(!response.data.Message){
            this.listauthor = response.data.books;
            this.author = response.data.data.name
            this.breadcrumbs[1].name = `${this.author}`
            this.panigation.page = response.data.books.current_page;
            this.panigation.length = response.data.books.last_page;
          }else{
            console.log( response.data.data.name)
            this.listauthor = response.data.books;
           this.author = response.data.data.name
            this.breadcrumbs[1].name = `${this.author}`

          }
        });
    },
    //
    "$route.query.page"(val) {
      if (val) {
        window.axios
          .get(
            "/authors/" +
              this.$route.query.type +
              "?page=" +
              (this.$route.query.page
                ? "&&page=" + this.$route.query.page
                : "") +
              (this.$route.query.sort ? "&&sort=" + this.$route.query.sort : "")
          )
          .then(response => {
            window.scrollTo(0, 0);
            this.listauthor = response.data.books;
            this.panigation.page = response.data.books.current_page;
            this.panigation.length = response.data.books.last_page;
          });
      }
      console.log("chuyển axios thành công");
    },
    //
    "$route.query.sort"(val) {
      if (val) {
        window.axios
          .get(
            "/authors/" +
              this.$route.query.type +
              "?sort=" +
              val +
              (this.$route.query.page ? "&&page=" + this.$route.query.page : "")
          )
          .then(response => {
            this.listauthor = response.data.books;
            this.panigation.page = response.data.books.current_page;
            this.panigation.length = response.data.books.last_page;
          });
      }
    },
    //
    filter_value(val) {
      this.$router.push(
        "/list-author/?type=" + this.$route.query.type + "&&sort=" + val.linkto
      );
    }
  },
  methods: {
    next(page) {
      this.$router.push(
        "/list-author/?type=" +
          this.$route.query.type +
          "&&page=" +
          page +
          (this.$route.query.sort ? "&&sort=" + this.$route.query.sort : "")
      );
      console.log("thành công");
    }
  },
  mounted() {
    window.axios
      .get(
        "/authors/" +
          this.$route.query.type +
          "?" +
          (this.$route.query.sort ? "sort=" + this.$route.query.sort : "") +
          (this.$route.query.page ? "&&page=" + this.$route.query.page : "")
      )
      .then(response => {
          if(!response.data.Message){
            this.listauthor = response.data.books;
            console.log(response.data.books)
            this.author = response.data.data.name
            this.breadcrumbs[1].name = `${this.author}`
            this.panigation.page = response.data.books.current_page;
            this.panigation.length = response.data.books.last_page;
          }else{
            this.listauthor = response.data.books;
            this.author = response.data.data.name
            this.breadcrumbs[1].name = `${this.author}`

          }
      })
      .catch(function(error) {
        console.log(error);
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
