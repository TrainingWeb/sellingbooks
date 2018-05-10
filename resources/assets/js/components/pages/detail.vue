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
                      <v-layout row v-if="bookDetail.author">
                        <v-flex xs4>
                          <v-card-media :src="'/storage/images/'+bookDetail.image" height="450px"></v-card-media>
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
                              </span>
                            </div>

                            <v-divider class="my-3"></v-divider>
                            <div>
                              <span class=" grey--text text--accent-4body-1">{{bookDetail.description}}</span>
                              <a>
                                <span class="green--text text--accent-4">Xem thêm</span>
                              </a>
                            </div>
                            <v-divider class="my-3"></v-divider>
                            <div>
                              <v-btn class="mx-0" color="green accent-4" dark @click="addCartDetail">
                                <v-icon class="mr-2">add_shopping_cart</v-icon>
                                Thêm
                              </v-btn>
                              <v-snackbar :timeout="timeout" top v-model="snackbar" color="green accent-4">
                                Thêm vào giỏ hàng thành công
                                <v-btn flat icon color="white" @click.native="snackbar = false">
                                  <v-icon>clear</v-icon>
                                </v-btn>
                              </v-snackbar>
                              <v-btn color="green accent-4" dark @click="addFavoriteDetail">
                                <v-icon class="mr-2">favorite</v-icon>
                                Yêu thích
                              </v-btn>
                            </div>
                            <v-layout row wrap class="mt-3">
                              <div class="text-xs-center">
                                <span class="green--text ml-2">Tags:</span>
                                <v-chip color="grey--text text--darken-1" text-color="white" class="px-0" v-for="(tag,index) in tags" :key="`keytag-$`+index">
                                  <router-link :to="`/tags?name=`+tag.slug" class="grey--text text--darken-2" style="text-decoration:none">{{tag.name}} </router-link>
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
                <v-card-text class="roboto">{{ bookDetail.description }}</v-card-text>
              </v-tab-item>
              <v-tab-item id="tab-2">
                <v-card>
                  <v-list three-line>

                    <template>
                      <h3>NHẬN XÉT ({{comments.length}})</h3>
                      <v-list-tile v-for="(item, index) in comments" avatar :key="`keycm-$`+index">
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
                  </v-list>
                </v-card>
              </v-tab-item>
            </v-tabs-items>
          </v-tabs>
          <v-container grid-list-xs class="my-5">
            <div class="headline grey--text text--darken-3">Những sản phẩm liên quan</div>
            <v-layout row wrap>
              <v-flex xs12 md6 lg4 v-for="(item,index) in books" :key="`khoa${index}`">
                <book-item :book="item"></book-item>
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
  methods: {
    addCartDetail() {
      let cart = this.$store.state.cart;
      for (var index in cart) {
        if (cart[index].book.id === this.bookDetail.id) {
          cart[index].quantity = cart[index].quantity + 1;
          this.snackbar = true;
          return;
        }
      }
      //
      let itemBook = {
        book: this.bookDetail,
        quantity: 1
      };
      cart.push(itemBook);
      this.$store.dispatch("setCart", cart);
      this.snackbar = true;
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