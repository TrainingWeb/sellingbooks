<template>
  <div>
    <v-layout>
      <v-carousel hide-controls>
        <v-carousel-item v-for="(item,i) in sliders" :src="item.src" :key="'key'+ i"></v-carousel-item>
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
        <v-flex xs12 md6 lg4 v-for="item in book" :key="`key-${item}`">
          <book-item :book="item"></book-item>
        </v-flex>
        <v-flex xs12 class="pt-2 mr-1">
          <router-link to="/list-products">
            <h5 class="text-xs-right  green--text text--accent-4">XEM THÊM</h5>
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
    <v-container grid-list-xs>
      <v-layout row wrap>
        <v-flex md-9>
          <v-container grid-list-xs>
            <v-layout row wrap>
              <v-flex xs12 md9>
                <v-layout row wrap>
                  <v-flex md6 v-for="(item,index) in bookSale" :key="`4${index}`">
                    <book-item :book="item"></book-item>
                  </v-flex>
                  <v-flex xs12 class="pt-2 mr-1">
                    <router-link to="/list-products">
                      <h5 class="text-xs-right  green--text text--accent-4">XEM THÊM</h5>
                    </router-link>
                  </v-flex>
                </v-layout>
              </v-flex>
              <v-flex xs12 md3>
                <img class="sale-img" src="https://hcplteenscene.files.wordpress.com/2011/03/book-sale.png" alt="">
              </v-flex>
            </v-layout>
          </v-container>
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
        <v-flex xs12 md6 lg4 v-for="(item,index) in book" :key="`Book-${index}`">
          <book-item :book=item></book-item>
        </v-flex>

        <v-flex xs12 class="pt-2 mr-1">
          <router-link to="/list-products">
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
      ],
      book: [
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
        },
        {
          img:
            "http://lehai.com.vn/uploads/news/Thi%E1%BA%BFt%20k%E1%BA%BF%20b%C3%ACa%20s%C3%A1ch/bia-sach-1.jpg",
          name: "Truyện kiều",
          price: "120.000",
          sale: "150.000",
          author: "Nguyễn Du"
        },
        {
          img:
            "https://i.quantrimang.com/photos/image/2016/05/29/sach-hay-2.jpg",
          name: "Chuyện chưa kể",
          price: "120.000",
          sale: "150.000",
          author: "Nguyễn Du"
        },
        {
          img:
            "https://i.pinimg.com/originals/e9/40/fd/e940fd856817c1737338ab47a938f430.jpg",
          name: "Cô bé bán diêm",
          price: "120.000",
          sale: "150.000",
          author: "Nguyễn Du"
        },
        {
          img:
            "https://tintaynguyen.com/wp-content/uploads/2015/11/bia-truyen-thuy-kieu-moi-anh-nha-nam-2-1447230913.jpg",
          name: "Mèo con đi học",
          price: "120.000",
          sale: "150.000",
          author: "Nguyễn Du"
        }
      ],
      bookSale: [
        {
          img:
            "http://lehai.com.vn/uploads/news/Thi%E1%BA%BFt%20k%E1%BA%BF%20b%C3%ACa%20s%C3%A1ch/bia-sach-1.jpg",
          name: "Truyện kiều",
          price: "120.000",
          sale: "150.000",
          author: "Nguyễn Du"
        },
        {
          img:
            "https://i.quantrimang.com/photos/image/2016/05/29/sach-hay-2.jpg",
          name: "Chuyện chưa kể",
          price: "120.000",
          sale: "150.000",
          author: "Nguyễn Du"
        },
        {
          img:
            "https://i.pinimg.com/originals/e9/40/fd/e940fd856817c1737338ab47a938f430.jpg",
          name: "Cô bé bán diêm",
          price: "120.000",
          sale: "150.000",
          author: "Nguyễn Du"
        },
        {
          img:
            "https://tintaynguyen.com/wp-content/uploads/2015/11/bia-truyen-thuy-kieu-moi-anh-nha-nam-2-1447230913.jpg",
          name: "Mèo con đi học",
          price: "120.000",
          sale: "150.000",
          author: "Nguyễn Du"
        }
      ]
    };
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
