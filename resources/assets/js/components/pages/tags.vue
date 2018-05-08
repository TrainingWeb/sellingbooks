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
      <v-layout row wrap>
        <v-flex xs12 md6 lg4 v-for="(item,index) in books" :key="`khoa${index}`">
          <book-item :book="item"></book-item>
        </v-flex>
      </v-layout>
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

    books: [
      {
        img:
          "http://vietart.co/blog/wp-content/uploads/2014/01/9_thiet_ke_bia_sach_dep_20.jpg",
        name: "Cô gái mở đường",
        price: "120.000",
        sale: "150.000",
        author: "Nguyễn Du"
      },
      {
        img:
          "https://thegioidohoa.com/wp-content/uploads/2017/08/tong-hop-20-mau-bia-sach-doc-dao-nhat-nam-2017-7.jpg",
        name: "Dế mèn phiêu lưu kí",
        price: "120.000",
        sale: "150.000",
        author: "Nguyễn Du"
      }
    ],
    namepage: "Tags",

    page: 1
  })
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

