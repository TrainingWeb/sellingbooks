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
        name: "Danh sách sản phẩm",
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
        img: "https://i.quantrimang.com/photos/image/2016/05/29/sach-hay-2.jpg",
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
      },
      {
        img:
          "https://hajimarinokaze.files.wordpress.com/2015/11/screenshot_7.png",
        name: "Mèo con đi học",
        price: "120.000",
        sale: "150.000",
        author: "Nguyễn Du"
      },
      {
        img:
          "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSvdXrTopkVcFYQwFxmDQTMfYHnFWdL0coXXHGKsteMR0eXvgG2tw",
        name: "Mèo con đi học",
        price: "120.000",
        sale: "150.000",
        author: "Nguyễn Du"
      },
      {
        img:
          "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQxAI3ZXxnDDlYjAMJXZlvWPVEaDsLHnGmd06ZFPbR83ug0uUSa",
        name: "Mèo con đi học",
        price: "120.000",
        sale: "150.000",
        author: "Nguyễn Du"
      },
      {
        img:
          "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTBzILC2b-rkURE-BPHmVqnFBPeNDcktpkd71kD0afJGWYKLkI0tg",
        name: "Mèo con đi học",
        price: "120.000",
        sale: "150.000",
        author: "Nguyễn Du"
      },
      {
        img:
          "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTdHmGWsMv-u81C7ZjZsBiWKQAfRmBycRjXSLSMdZpiTNLvAxpHIA",
        name: "Mèo con đi học",
        price: "120.000",
        sale: "150.000",
        author: "Nguyễn Du"
      },
      {
        img:
          "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSLZXijYcdnacO0DTpH_IzMMNmzmJwgcb8DIFzCeStHgQ7dUm8sAA",
        name: "Mèo con đi học",
        price: "120.000",
        sale: "150.000",
        author: "Nguyễn Du"
      },
      {
        img:
          "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSzgAqkP87llex4oOEWrTzvV7bUupOVWNb7JwBphiuoeB761UzT",
        name: "Mèo con đi học",
        price: "120.000",
        sale: "150.000",
        author: "Nguyễn Du"
      },
      {
        img:
          "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT0Xepf1UGSlrefSwiK8jbx8iIPHa_CU13csTFWSAunCIvGoekg",
        name: "Mèo con đi học",
        price: "120.000",
        sale: "150.000",
        author: "Nguyễn Du"
      },
      {
        img:
          "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTrtyIf_Ro-a4IRIIl4rO0NG-4AxpuMz-UIIHMg-iMOZByiDH71qQ",
        name: "Mèo con đi học",
        price: "120.000",
        sale: "150.000",
        author: "Nguyễn Du"
      },
      {
        img:
          "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR51dQmpOYMx-GCBow5lIFF2dzaQknAaZTB1gW7KtYR4By5Y5ElnA",
        name: "Mèo con đi học",
        price: "120.000",
        sale: "150.000",
        author: "Nguyễn Du"
      },
      {
        img:
          "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTs-5e3PeiSCg7t7NLQkHNikmL7zIuS04xe8tK7Fo3bjsZj7w_e",
        name: "Mèo con đi học",
        price: "120.000",
        sale: "150.000",
        author: "Nguyễn Du"
      },
      {
        img:
          "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTxGqfU4M_EyfKALNeUq_67SdVisuPg_C5vC0CCsXoueug-IoaptQ",
        name: "Mèo con đi học",
        price: "120.000",
        sale: "150.000",
        author: "Nguyễn Du"
      }
    ],
    namepage: "Kết quả tìm kiếm: ",

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
