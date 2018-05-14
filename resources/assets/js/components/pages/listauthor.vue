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
          <v-flex xs12 md6 lg4 v-for="(item,index) in listauthor" :key="`khoaauthor-${index}`">
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

    namepage: "Tác giả",
    page: 1,
    listauthor: []
  }),
  watch: {
    "$route.query.type"(val) {
      this.breadcrumbs[1].name = `${this.$route.query.type}`;
    }
  },
  mounted() {
    this.breadcrumbs[1].name = `${this.$route.query.type}`;

    window.axios
      .get(
        "/authors/" + this.$route.query.type + "?slug=" + this.$route.query.type
      )
      .then(response => {
        this.listauthor = response.data.data.data;
        console.log("đây là tác phẩm", this.listauthor);
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
