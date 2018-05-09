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
            <!-- Đăng nhập -->
            <v-menu v-model="login" bottom offset-y :max-width="350" :close-on-content-click="false">
              <v-btn flat slot="activator" class="white">Đăng nhập</v-btn>
              <v-card flat>
                <v-list class="green accent-4 white--text text-xs-center">
                  <span class="">ĐĂNG NHẬP</span>
                </v-list>
                <v-divider></v-divider>
                <v-container>
                  <v-form ref="form" v-model="valid" lazy-validation>
                    <v-flex xs12>
                      <v-text-field v-model="emailLogin" :rules="emailRules" label="E-mail" required></v-text-field>
                    </v-flex>
                    <v-flex xs12>
                      <v-text-field v-model="passLogin" required name="input-10-2" label="Mật khẩu" :rules="passRules" :append-icon="e2 ? 'visibility' : 'visibility_off'" :append-icon-cb="() => (e2 = !e2)" :type="e2 ? 'password' : 'text'"></v-text-field>
                    </v-flex>
                    <div>
                      <v-btn flat to="/forgotpassword">Quên mật khẩu</v-btn>
                      <v-btn class="text-xs-center" :disabled="!valid" @click="login = false">
                        Đăng nhập
                      </v-btn>
                    </div>
                  </v-form>
                </v-container>
              </v-card>
            </v-menu>
            <!-- Hết Đăng nhập -->
            <!-- Đăng Ký -->
            <v-menu fluid v-model="register" bottom offset-y :max-width="350" :close-on-content-click="false">
              <v-btn flat slot="activator" class="white">Đăng Ký</v-btn>
              <v-card flat>
                <v-list class="green accent-4 white--text text-xs-center">
                  <span>ĐĂNG KÝ</span>
                </v-list>
                <v-divider></v-divider>
                <v-container>
                  <v-form ref="form" v-model="valid" lazy-validation>
                    <v-flex xs12>
                      <v-text-field v-model="name" :rules="nameRules" label="Tên" required></v-text-field>
                    </v-flex>
                    <v-flex xs12>
                      <v-text-field v-model="emailRegister" :rules="emailRules" label="E-mail" required></v-text-field>
                    </v-flex>
                    <v-flex xs12>
                      <v-text-field v-model="passRegister" required label="Mật khẩu" :rules="passRules" :append-icon="e2 ? 'visibility' : 'visibility_off'" :append-icon-cb="() => (e2 = !e2)" :type="e2 ? 'password' : 'text'"></v-text-field>
                    </v-flex>
                    <div>
                      <v-btn flat>Đóng</v-btn>
                      <v-btn :disabled="!valid" @click="register = false">
                        Đăng ký
                      </v-btn>
                    </div>
                  </v-form>
                </v-container>
              </v-card>
            </v-menu>
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
                  <span slot="badge" class="caption">{{totalCard}}</span>
                  <v-icon color="grey">add_shopping_cart</v-icon>
                </v-badge>
              </v-btn>
              <v-btn flat class="caption grey--text  text--darken-1 p-0" to="/favorite">
                <v-badge color="indigo" class="p-0">
                  <span slot="badge" class="caption">{{$store.state.favorite.length}}</span>
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
                    <v-flex xs4 text-xs-center>
                      <v-list>
                        <v-list-tile avatar v-for="(subtiem,index) in megamenu" :key="`keyCatetgory1-$`+index" exact :to="`/list-category?type=`+subtiem.text">
                          <v-list-tile-content>
                            <v-list-tile-title>
                              {{subtiem.text}}
                            </v-list-tile-title>
                          </v-list-tile-content>
                        </v-list-tile>
                      </v-list>
                    </v-flex>
                    <v-flex xs4>
                      <v-list>
                        <v-list-tile avatar v-for="(subtiem,index) in megamenu2" :key="`keyCatetgory2-$`+index" exact :to="`/list-category?type=`+subtiem.text">
                          <v-list-tile-content>
                            <v-list-tile-title>{{subtiem.text}}</v-list-tile-title>
                          </v-list-tile-content>
                        </v-list-tile>
                      </v-list>
                    </v-flex>
                    <v-flex xs4>
                      <v-list>
                        <v-list-tile avatar v-for="(subtiem,index) in megamenu3" :key="`keyCatetgory3-$`+index" exact :to="`/list-category?type=`+subtiem.text">
                          <v-list-tile-content>
                            <v-list-tile-title>{{subtiem.text}}</v-list-tile-title>
                          </v-list-tile-content>
                        </v-list-tile>
                      </v-list>
                    </v-flex>
                  </v-layout>
                </v-card>
              </v-menu>
              <v-menu open-on-hover offset-y full-width bottom :close-on-content-click="false" content-class="mega-menu">
                <v-btn slot="activator" flat class="white--text">Tác giả</v-btn>
                <v-card>
                  <v-layout row wrap>
                    <v-flex xs4 text-xs-center>
                      <v-list>
                        <v-list-tile avatar v-for="(subtiem,index) in authors" :key="`keyauthor1-$`+index" exact :to="`/list-author?type=`+subtiem.text">
                          <v-list-tile-content>
                            <v-list-tile-title>
                              {{subtiem.text}}
                            </v-list-tile-title>
                          </v-list-tile-content>
                        </v-list-tile>
                      </v-list>
                    </v-flex>
                    <v-flex xs4>
                      <v-list>
                        <v-list-tile avatar v-for="(subtiem,index) in authors2" :key="`keyauthor2-$`+index" exact :to="`/list-author?type=`+subtiem.text">
                          <v-list-tile-content>
                            <v-list-tile-title>{{subtiem.text}}</v-list-tile-title>
                          </v-list-tile-content>
                        </v-list-tile>
                      </v-list>
                    </v-flex>
                    <v-flex xs4>
                      <v-list>
                        <v-list-tile avatar v-for="(subtiem,index) in authors3" :key="`keyauthor3-$`+index" exact :to="`/list-author?type=`+subtiem.text">
                          <v-list-tile-content>
                            <v-list-tile-title>{{subtiem.text}}</v-list-tile-title>
                          </v-list-tile-content>
                        </v-list-tile>
                      </v-list>
                    </v-flex>
                  </v-layout>
                </v-card>
              </v-menu>
              <v-btn flat class="white--text" to="/about">Giới thiệu</v-btn>
            </v-toolbar-items>
            <v-spacer></v-spacer>
            <v-toolbar-items class="hidden-sm-and-down">
              <v-text-field label="Tìm kiếm" :append-icon="'search'" dark @keyup.enter="textSearch" v-model="search" color="white"></v-text-field>
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
        <v-container class="justify-center">
          <v-card-text class="white">
            <v-layout row wrap>
              <v-flex xs12 sm6 lg3>
                <img class="mb-3" width="180" src="storage/images/logo_green.png">

                <div class="mb-3" color="grey--text text--darken-2">
                  <v-icon size="18px" class="mr-1">fas fa-home</v-icon>
                  79 Võ Duy Ninh,TP ĐN
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
              <v-flex v-for="(col, i) in rows" :key="`col-$`+i" xs12 md3>
                <div class="body-2 title-ft my-3" v-text="col.title.toUpperCase()"></div>
                <div class="my-3 info-ft" v-for="(child, i) in col.children" :key="`child-$`+i" v-text="child"></div>
                <v-card-title v-if="col.icons">
                  <v-btn v-for=" (icon,index) in col.icons " :key="`footer-`+index" icon dark class="mx-1">
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
import axios from "axios";
export default {
  data: () => ({
    valid: true,
    name: "",
    nameRules: [v => !!v || "Tên là bắt buộc"],
    passLogin: "",
    passRegister: "",
    passRules: [
      v => !!v || "Mật khẩu là bắt buộc",
      v => v.length >= 8 || "Nhập ít nhất 8 ký tự"
    ],
    emailRegister: "",
    emailLogin: "",
    emailRules: [
      v => !!v || "E-mail là bắt buộc",
      v =>
        /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
        "E-mail phải hợp lệ"
    ],
    password: "",

    drawer: null,
    icons: ["fas fa-home", "fas fa-envelope", "fas fa-phone", "fas fa-print"],
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
      },
      {
        title: "TÀI KHOẢN CỦA TÔI",
        children: ["Chi tiết tài khoản", "Lịch sử mua hàng"],
        icons: [
          "fab fa-facebook",
          "fab fa-twitter",
          "fab fa-google-plus",
          "fab fa-linkedin",
          "fab fa-instagram"
        ]
      }
    ],
    megamenu: [
      {
        id: 1,
        text: "tình Yêu Không có lỗi"
      },
      {
        id: 2,
        text: "ngôn tình "
      },
      {
        id: 3,
        text: "Xuyên không"
      },
      {
        id: 4,
        text: "Truyện cổ tích"
      }
    ],
    megamenu2: [
      {
        id: 5,
        text: "tình Yêu Không có lỗi2"
      },
      {
        id: 6,
        text: "ngôn tình 2"
      },
      {
        id: 7,
        text: "Xuyên không 2"
      },
      {
        id: 8,
        text: "Truyện cổ tích 2"
      }
    ],
    megamenu3: [
      {
        id: 9,
        text: "tình Yêu Không có lỗi 3"
      },
      {
        id: 10,
        text: "ngôn tình 3"
      },
      {
        id: 11,
        text: "Xuyên không 3"
      },
      {
        id: 12,
        text: "Truyện cổ tích 3"
      }
    ],
    authors: [
      {
        id: 1,
        text: "Vũ trọng Phụng"
      },
      {
        id: 2,
        text: "Nguyễn Du"
      },
      {
        id: 3,
        text: "Nguyên Hồng"
      },
      {
        id: 4,
        text: "Tô Hoài"
      }
    ],
    authors2: [
      {
        id: 5,
        text: "Cẩm Vân"
      },
      {
        id: 6,
        text: "Tản Đà"
      },
      {
        id: 7,
        text: "Phan Bội Châu"
      },
      {
        id: 8,
        text: "Hồ Chí Minh"
      }
    ],
    authors3: [
      {
        id: 9,
        text: "Nguyễn Trãi"
      },
      {
        id: 10,
        text: "Nguyễn Ngọc Ký"
      },
      {
        id: 11,
        text: "Ngô Tất Tố"
      },
      {
        id: 12,
        text: "Văn Cao"
      }
    ],
    // popover
    register: false,
    login: false,
    message: false,
    hints: true,
    //    popover
    e2: false,
    e3: false,
    password: "Password",

    search: null
  }),
  props: {
    source: String
  },
  methods: {
    textSearch() {
      console.log("Đã vào rồi ");
      window.location = `#/search?keyword=${this.search}`;
      this.search = "";
    },

    submit() {
      if (this.$refs.form.validate()) {
        axios.post("/api/submit", {
          name: this.name,
          email: this.email
        });
      }
    }
  },
  computed: {
    totalCard() {
      return this.$store.state.cart.reduce((total, p) => {
        return total + p.quantity;
      }, 0);
    }
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