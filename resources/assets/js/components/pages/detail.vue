<template>
  <div>
    <v-layout xs12>
      <v-banner :value="{title:namepage,breadcrumbs}"></v-banner>
    </v-layout>
    <v-container>
      <v-layout row wrap>
        <v-flex xs12>
          <v-card flat>
            <v-container fluid style="min-height: 0;" grid-list-lg>
              <v-layout row wrap>
                <v-flex xs12>
                  <v-card color="cyan darken-2" class="white--text">
                    <v-container fluid grid-list-lg>
                      <v-layout row>
                        <v-flex xs4>
                          <v-card-media :src="bookDetail.img" height="450px" contain></v-card-media>
                        </v-flex>
                        <v-flex xs8>
                          <div>
                            <div class="headline grey--text text--darken-3">{{bookDetail.name}}</div>
                            <div class="grey--text accent-4 body-2">
                              <span>Tác giả: {{bookDetail.author.name}}</span>
                            </div>
                            <div v-if="bookDetail.promotion_price">
                              <span class="green--text text--accent-4 title mt-3"> {{formatPrice(bookDetail.price)}} </span>
                              <span class="grey--text text--darken-1 title mt-3 ml-3">
                                <del>{{formatPrice(bookDetail.promotion_price)}}</del>
                              </span>
                            </div>
                            <div v-else>
                              <span class="green--text text--accent-4 title mt-3">
                                {{bookDetail.price}}
                                <span>Tác giả: </span>{{bookDetail.author}}</div>
                            <v-divider class="my-3"></v-divider>
                            <div>
                              <span class=" grey--text text--accent-4body-1">{{bookDetail.detail}}</span>
                              <a>
                                <span class="green--text text--accent-4">Xem thêm</span>
                              </a>
                            </div>
                            <v-divider class="my-3"></v-divider>
                            <div>
                              <v-btn class="mx-0" color="green accent-4 white--text" @click="addCartDetail">
                                <i class="material-icons add-shopping mr-2 white--text">add_shopping_cart</i>Thêm
                              </v-btn>
                              <v-btn color="green accent-4" @click="addFavoriteDetail">
                                <i class="material-icons favorite white--text">favorite</i>
                              </v-btn>
                            </div>
                            <v-layout row wrap class="mt-3">
                              <div class="text-xs-center">
                                <span class="green--text ml-2">Tags:</span>
                                <v-chip color="grey--text text--darken-1" text-color="white" class="px-0" v-for="(tag,index) in tags" :key="`keytag-$`+index">
                                  <router-link :to="`/tags?name=`+tag.slug" class="grey--text text--darken-2" style="text-decoration:none">{{tag.name}} </router-link>
                                </v-chip>
                                <v-chip color="grey--text text--darken-1" text-color="white" class="px-0">
                                  <router-link to="/tags" class="grey--text text--darken-2" style="text-decoration:none">Sách Văn Học </router-link>
                                </v-chip>
                              </div>
                            </v-layout>
                          </div>
                        </v-flex>
                      </v-layout>
                    </v-container>
                  </v-card>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card>
          <v-tabs icons-and-text dark color="white" height="40px">
            <v-tabs-slider color="yellow"></v-tabs-slider>
            <v-tab class="green accent-4" href="#tab-1">
              Chi tiết sản phẩm
            </v-tab>
            <v-tab class="green accent-4" href="#tab-2">
              Nhận xét khách hàng
            </v-tab>
            <v-tabs-items>
              <v-tab-item id="tab-1">
                <v-card-text class="roboto">{{ bookDetail.textDetail }}</v-card-text>
              </v-tab-item>
              <v-tab-item id="tab-2">
                <v-card>
                  <v-list three-line>
                    <template>
                      <v-list-tile v-for="item in comments" avatar :key="item.title">
                        <v-list-tile-avatar>
                          <img :src="item.avatar">
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                          <v-list-tile-title v-html="item.name"></v-list-tile-title>
                          <v-list-tile-sub-title class="subtitleComment" v-html="item.subtitle"></v-list-tile-sub-title>
                        </v-list-tile-content>
                        <v-list-tile-action>
                          <v-list-tile-action-text>{{ item.time }}</v-list-tile-action-text>
                        </v-list-tile-action>
                      </v-list-tile>
                    </template>
                    <v-layout row wrap>
                      <v-text-field v-model="commenttext" name="input-1-3" label="Lời nhận xét của bạn" single-line @keyup.enter="saveComment"></v-text-field>
                      <div>
                        <v-btn color="green accent-4 white--text" @click="saveComment">Gửi</v-btn>
                      </div>
                    </v-layout>
                    <template>
                      <div class="text-xs-center mt-5">
                        <v-pagination :length="3" v-model="page"></v-pagination>
                      </div>
                    </template>
                  </v-list>
                </v-card>
              </v-tab-item>
            </v-tabs-items>
          </v-tabs>
          <v-container grid-list-xs class="my-5">
            <div class="headline grey--text text--darken-3">Những sản phẩm liên quan</div>
            <v-layout row wrap>
              <v-flex xs12 md6 lg4 v-for="(item,index) in book" :key="`Book-${index}`">
                <book-item :book=item></book-item>
              </v-flex>
            </v-layout>
          </v-container>
        </v-flex>
      </v-layout>
    </v-container>
  </div>
</template>
<script>
export default {
  data() {
    return {
      commenttext: "",
      bookDetail: {},
      tags: {},
      comments: {},
      books: {},
      snackbar: false,
      timeout: 3000,
      breadcrumbs: [
        {
          name: "Trang Chủ",
          url: "/",
          disabled: false
        },
        {
          name: "Chi tiết sản phẩm",
          url: "/list-products",
          disabled: true
        }
      ],
      namepage: "Chi tiết sản phẩm",
      currentComment: null,
      page: 1
    };
  },
  mounted() {
    window.axios.get("/book/{slug}");
  },
  methods: {
    addCartDetail() {
      for (var index in this.$store.state.cart) {
        if (this.$store.state.cart[index].book.id === this.book.id) {
          alert(
            "Sản phẩm này đã có trong giỏ hàng của bạn vui lòng không chọn thêm"
          );
          return;
        }
      }
      let itemBook = {
        book: this.bookDetail,
        quantity: 1
      };
      let cart = this.$store.state.cart;
      cart.push(itemBook);
      this.$store.dispatch("setCart", cart);
    },
    addFavoriteDetail() {
      for (var index in this.$store.state.favorite) {
        if (this.$store.state.favorite[index].id == this.book.id) {
          alert("Sản phẩm này đã được bạn yêu thích");
          return;
        }
      }
      let itemBook = {
        book: this.bookDetail,
        quantity: 1
      };
      let favorite = this.$store.state.favorite;
      favorite.push(itemBook);
      this.$store.dispatch("setFavorite", favorite);
    },
    formatPrice(price) {
      let val = (price / 1).toFixed(0).replace(".", ",");
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
  },
  mounted() {
    window.axios
      .get(
        "/books/" + this.$route.query.type + "?slug=" + this.$route.query.type
      )
      .then(response => {
        this.bookDetail = response.data.data.book;
        this.books = response.data.data.samebooks;
        this.tags = response.data.data.book.tags;
        this.comments = response.data.data.book.comments;
        console.log("đây là tác phẩm cảu detail", response.data);
      })
      .catch(function(error) {
        console.log(error);
      });
  }
};
</script>
<style>
.cyan.darken-2,
.cyan.darken-2--after:after {
  background-color: #fff !important;
}
.cyan {
  background-color: #fff !important;
  border-color: #fff !important;
}
.card {
  box-shadow: none;
}
.application.theme--light {
  background: #fff;
}
.application.theme--light .text--primary {
  color: #757575 !important;
}
.primary {
  background-color: #00c853 !important;
  border-color: #00c853 !important;
}
</style>