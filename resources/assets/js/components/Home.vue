<template>
  <div>
    <v-layout>
      <v-carousel>
        <v-carousel-item v-for="(item,i) in sliders" :src="item.src" :key="'slide'+ i"></v-carousel-item>
      </v-carousel>
    </v-layout>
    <v-layout align-center class="py-5">
      <v-flex text-xs-center>
        <h2 class="green--text text--accent-4">
          <v-icon large class="green--text text--accent-4">stars</v-icon> ĐỘC QUYỀN THÁNG NÀY</h2>
        <p class="grey--text text--darken-1 mt-4">Những sản phẩm bán chạy nhất trong tháng này</p>
      </v-flex>
    </v-layout>
    <v-container grid-list-xs>
      <v-layout row wrap>
        <v-flex xs12 sm6 md6 lg4 v-for="(item,index) in featuredbooks" :key="`khoa${index}`">
          <book-item :book="item"></book-item>
        </v-flex>
        <v-flex xs12 class="pt-2 mr-1 text-xs-right">
          <router-link to="/list-products?type=featuredbooks">
            <h5 class="green--text text--accent-4">XEM THÊM</h5>
          </router-link>
        </v-flex>
      </v-layout>
    </v-container>
    <v-layout align-center class="py-5">
      <v-flex text-xs-center>
        <h2 class="green--text text--accent-4">
          <v-icon large class="green--text text--accent-4">stars</v-icon>
          SẢN PHẨM ĐƯỢC GIẢM GIÁ KHỦNG
        </h2>
        <p class="grey--text text--darken-1 mt-4">Những sản phẩm giảm giá nhiều nhất trong tháng này</p>
      </v-flex>
    </v-layout>
    <v-container grid-list-lg>
      <v-layout row wrap>
        <v-flex xs12 md9>
          <v-layout row wrap>
            <v-flex xs12 sm6 md6 v-for="(item,index) in discountbooks" :key="`4${index}`">
              <book-item :book="item"></book-item>
            </v-flex>
            <v-flex xs12 class="pt-2 mr-1">
              <router-link to="/list-products?type=discountbooks">
                <h5 class="text-xs-right  green--text text--accent-4">XEM THÊM</h5>
              </router-link>
            </v-flex>
          </v-layout>
        </v-flex>
        <v-flex xs12 md3>
          <img class="sale-img" src="storage/images/quang-cao.jpg" alt="">
        </v-flex>
      </v-layout>
    </v-container>
    <v-layout align-center class="py-5">
      <v-flex text-xs-center>
        <h2 class="green--text text--accent-4">
          <v-icon large class="green--text text--accent-4">stars</v-icon> SẢN PHẨM MỚI</h2>
        <p class="grey--text text--darken-1 mt-4">Những sản phẩm mới ra mắt trong tháng này</p>
      </v-flex>
    </v-layout>
    <v-container grid-list-xs>
      <v-layout row wrap>
        <v-flex xs12 sm6 md6 lg4 v-for="(item,index) in newbooks" :key="`Book-${index}`">
          <book-item :book=item></book-item>
        </v-flex>
        <v-flex xs12 class="pt-2 mr-1">
          <router-link to="/list-products?type=newbooks">
            <h5 class="text-xs-right  green--text text--accent-4">XEM THÊM</h5>
          </router-link>
        </v-flex>
      </v-layout>
    </v-container>
  </div>
</template>
<script>
export default {
  data() {
    return {
      featuredbooks: {},
      discountbooks: {},
      newbooks: {},
      sliders: [
        {
          src:
            "http://backgrounddep.com/uploads/images/tong-hop-26-hinh-nen-nhung-cuon-sach-dep-den-ngo-ngang-1489317588-22.jpg"
        },
        {
          src:
            "http://static.ybox.vn/2017/4/11/7397e6ae-1e80-11e7-a0c9-2e995a9a3302.jpg"
        },
        {
          src:
            "http://ptth-hstar.thuathienhue.edu.vn/imgs/Thu_muc_he_thong/_Nam_2014/_Thang_09/p1150951fileminimizer-.jpg"
        },
        {
          src: "https://cafeandbooks.files.wordpress.com/2015/07/dscn4528.jpg"
        }
      ]
    };
  },
  methods: {
    loadFeaturedBooks() {
      window.axios.get("/index").then(res => {
        this.featuredbooks = res.data.data.featuredbooks;
        // console.log(res.data);
        this.discountbooks = res.data.data.discountbooks;
        this.newbooks = res.data.data.newbooks;
      });
    }
  },
  mounted() {
    this.loadFeaturedBooks();
  }
};
</script>

<style>
.carousel__controls {
  background-color: transparent !important;
}
.sale-img {
  width: 100%;
  height: 100%;
}
</style>
