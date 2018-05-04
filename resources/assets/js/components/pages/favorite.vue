<template>
  <div>
    <v-layout xs12>
      <v-banner :value="{title:namepage,breadcrumbs}"></v-banner>
    </v-layout>
    <v-container>
      <v-layout row wrap>
        <v-flex xs12 md6 lg4 v-for="(item,index) in $store.state.favorite" :key="`khoa${index}`">
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
        name: "Sản phẩm yêu thích",
        url: "/favorite",
        disabled: true
      }
    ],
    e1: null,
    namepage: "Sản phẩm yêu thích",
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
