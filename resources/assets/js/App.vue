<template>
    <v-app id="inspire">
        <v-layout row wrap>
            <v-flex xs12 class="grey lighten-5">
                <v-container class="pa-0">
                    <v-toolbar dense flat class="transparent px-0">
                        <v-toolbar-title class="hidden-sm-and-down">
                            <v-icon small class="green--text text--accent-4">place</v-icon>
                            <span class="caption grey--text  text--darken-1 pr-3">
                                k48/Võ Duy Ninh, Sơn Trà
                            </span>
                            <v-icon small class="green--text text--accent-4">smartphone</v-icon>
                            <span class="caption grey--text  text--darken-1">
                                0123 456 789
                            </span>
                        </v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-toolbar-items>
                            <v-btn flat class="caption grey--text  text--darken-1">Đăng Nhập</v-btn>
                            <v-btn flat class="caption grey--text  text--darken-1">Đăng Ký</v-btn>
                        </v-toolbar-items>
                    </v-toolbar>
                </v-container>
            </v-flex>
            <v-flex xs12 white>
                <v-container class="pa-0">
                    <v-toolbar flat class="transparent px-0">
                        <v-toolbar-title>
                            <a href="\"> <img src="storage/images/logo_green.png" alt=""></a>
                        </v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-toolbar-items>
                            <v-btn flat class="caption grey--text  text--darken-1 p-0" to="/card">
                                <v-badge color="red lighten-1" class="p-0">
                                    <span slot="badge" class="caption">{{$store.state.cart.length}}</span>
                                    <v-icon color="grey">add_shopping_cart</v-icon>
                                </v-badge>
                            </v-btn>
                            <v-btn flat class="caption grey--text  text--darken-1 p-0">
                                <v-badge color="indigo" class="p-0">
                                    <span slot="badge" class="caption">0</span>
                                    <v-icon color="grey">favorite</v-icon>
                                </v-badge>
                            </v-btn>
                        </v-toolbar-items>
                    </v-toolbar>
                </v-container>
            </v-flex>
            <v-flex xs12 green accent-4>
                <v-container class="pa-0">
                    <v-toolbar flat class="green accent-4">
                        <v-toolbar-items>
                            <v-btn flat class="white--text ma-0" to="/">Trang chủ</v-btn>
                            <v-menu open-on-hover offset-y full-width bottom :close-on-content-click="false" content-class="mega-menu">
                                <v-btn slot="activator" flat class="white--text">Thể loại</v-btn>
                                <v-card>
                                    <v-layout row wrap>
                                        <v-flex xs4 v-for="item in 3" :key="item">
                                            <v-list>
                                                <v-list-tile avatar v-for="subtiem in 6" :key="item +'-'+ subtiem">
                                                    <v-list-tile-content>
                                                        <v-list-tile-title>ITEM MENU {{item +'-'+ subtiem}}</v-list-tile-title>
                                                    </v-list-tile-content>
                                                </v-list-tile>
                                            </v-list>
                                        </v-flex>
                                    </v-layout>
                                </v-card>
                            </v-menu>
                            <v-btn flat class="white--text">Tác giả</v-btn>
                            <v-btn flat class="white--text">Giới thiệu</v-btn>
                        </v-toolbar-items>
                        <v-spacer></v-spacer>
                        <v-toolbar-items class="hidden-sm-and-down">
                            <v-text-field label="Tìm kiếm" :append-icon="'search'" dark></v-text-field>
                        </v-toolbar-items>
                    </v-toolbar>
                </v-container>

            </v-flex>
        </v-layout>
        <!--  -->
        <!--  -->

        <v-content class="white">
            <router-view></router-view>
        </v-content>
        <!--  -->
        <!--  -->
        <v-footer height="auto">
            <v-card flat tile class="flex">
                <v-container class="px-0">
                    <v-card-text class="white">
                        <v-layout row wrap>
                            <v-flex xs12 md3 layout column class="">
                                <img class="mb-3" width="180" src="storage/images/logo_green.png">
                                <div class="mb-3" color="grey--text text--darken-2">
                                    <v-icon size="18px" class="mr-1">fas fa-home</v-icon>
                                    179 Võ Duy Ninh,TP ĐN
                                </div>
                                <div class="mb-3">
                                    <v-icon size="18px" class="mr-1  ">fas fa-envelope</v-icon>
                                    bookstore@gmail.com
                                </div>
                                <div class="mb-3">
                                    <v-icon size="18px" class="mr-1">fas fa-phone</v-icon>
                                    + 01 234 567 88
                                </div>
                                <div class="mb-3">
                                    <v-icon size="18px" class="mr-1 ">fas fa-print</v-icon>
                                    + 01 234 567 89
                                </div>
                            </v-flex>
                            <v-flex v-for="(col, i) in rows" :key="i" xs12 md3>
                                <div class="body-2 title-ft my-3" v-text="col.title.toUpperCase()"></div>
                                <div class="my-3 info-ft" v-for="(child, i) in col.children" :key="i" v-text="child"></div>
                            </v-flex>
                            <v-flex xs12 md3 layout column class="">
                                <div class="body-2 my-3">TÀI KHOẢN CỦA TÔI</div>
                                <div class="mb-3  ">Chi tiết tài khoản</div>
                                <div class="mb-3">Lịch sử mua hàng</div>
                                <v-card-title class="">
                                    <v-btn v-for=" icon in icons " :key="icon " icon dark class="mx-1">
                                        <v-icon color="green" size="24px ">{{ icon }}</v-icon>
                                    </v-btn>
                                </v-card-title>
                            </v-flex>
                        </v-layout>
                    </v-card-text>
                </v-container>
                <v-card-actions class="grey lighten-3 justify-center ">
                    &copy;2018 — Demo Vue Js
                </v-card-actions>
            </v-card>
        </v-footer>

    </v-app>
</template>

<script>
export default {
  data: () => ({
    drawer: null,
    icons: [
      "fab fa-facebook",
      "fab fa-twitter",
      "fab fa-google-plus",
      "fab fa-linkedin",
      "fab fa-instagram"
    ],
    rows: [
      {
        title: "DỊCH VỤ",
        children: [
          "Điều khoản và sử dụng",
          "Thông tin giao hàng",
          "Chính sách bảo mật",
          "Dịch vụ khách hàng"
        ]
      },
      {
        title: "HỖ TRỢ",
        children: [
          "Chính sách đổi - trả - hoàn tiền",
          "Chính sách khách sỉ",
          "Phương thức vận chuyển",
          "Phương thức thanh toán"
        ]
      }
    ]
  }),
  props: {
    source: String
  }
};
</script>
<style>
.card__title {
  margin-left: -25px;
}
.mega-menu {
  left: 0 !important;
  min-width: 100% !important;
}
</style>
