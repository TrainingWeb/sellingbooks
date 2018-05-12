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
          <v-flex xs12 md6 lg4 v-for="(item,index) in tags" :key="`khoa${index}`">
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
        name: "Tags",
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

    tags: {},
    namepage: "Tags",
    page: 1
  }),
  mounted() {
    window.axios
      .get(
        "/tags/" + this.$route.query.name + "?slug=" + this.$route.query.name
      )
      .then(response => {
        this.tags = response.data.data;
        console.log("đây là tác phẩm của tags", response.data.data);
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

