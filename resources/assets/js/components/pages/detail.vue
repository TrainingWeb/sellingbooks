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
                <v-card color="cyan darken-2" class="white--text">
                  <v-container fluid grid-list-lg>
                    <v-layout row wrap v-if="bookDetail.author">
                      <v-flex xs12 sm6 md4 class="pr-5">
                        <v-card-media :src="'/storage/images/'+bookDetail.image" height="450px"></v-card-media>
                      </v-flex>
                      <v-flex xs12 sm6 md8>
                        <div>
                          <div class="headline grey--text text--darken-3">{{bookDetail.name}}</div>
                          <div class="grey--text accent-4 body-2 py-2">
                            <span>Tác giả: {{bookDetail.author.name}}</span>
                          </div>
                          <div v-if="bookDetail.promotion_price">
                            <span class="green--text text--accent-4 title py-3"> {{formatPrice(bookDetail.price)}}
                              <span style="text-decoration: underline">đ</span>
                            </span>
                            <span class="grey--text text--darken-1 title mt-3 ml-3">
                              <del>{{formatPrice(bookDetail.promotion_price)}}
                                <span style="text-decoration: underline">đ</span>
                              </del>
                            </span>
                          </div>
                          <div v-else>
                            <span class="green--text text--accent-4 title mt-3">
                              {{formatPrice(bookDetail.price)}}
                              <span style="text-decoration: underline">đ</span>
                            </span>
                          </div>
                          <v-divider class="my-3"></v-divider>
                          <div>
                            <div class="seemore-description grey--text text--accent-4body-1">{{bookDetail.description}}</div>
                            <a>
                              <span class="green--text text--accent-4">Xem thêm</span>
                            </a>
                          </div>
                          <v-divider class="my-3"></v-divider>
                          <div>
                            <v-btn class="mx-0" color="green accent-4 white--text" @click="addCartDetail">
                              <i class="material-icons add-shopping  white--text">add_shopping_cart</i>
                              <v-snackbar :timeout="timeout" top v-model="snackbarCartDetail" color="green accent-4">
                                Thêm vào giỏ hàng thành công
                                <v-btn flat icon color="white" @click.native="snackbarCartDetail = false">
                                  <v-icon>clear</v-icon>
                                </v-btn>
                              </v-snackbar>
                            </v-btn>
                            <v-btn color="green accent-4" @click="addFavoriteDetail">
                              <i class="material-icons favorite white--text">favorite</i>
                              <v-snackbar :timeout="timeout" top v-model="snackbarFavDetail" color="green accent-4">
                                Thêm vào giỏ hàng thành công
                                <v-btn flat icon color="white" @click.native="snackbarFavDetail = false">
                                  <v-icon>clear</v-icon>
                                </v-btn>
                              </v-snackbar>
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
              </v-layout>
            </v-container>
          </v-card>
          <v-tabs icons-and-text dark color="white" height="40px">
            <v-tabs-slider color="green accent-4"></v-tabs-slider>
            <v-tab class="green accent-4" href="#tab-1">
              Chi tiết sản phẩm
            </v-tab>
            <v-tab class="green accent-4" href="#tab-2">
              Nhận xét khách hàng
            </v-tab>
            <v-tabs-items class="ml-2">
              <v-tab-item id="tab-1">
                <v-card-text class="roboto">{{ bookDetail.description }}</v-card-text>
              </v-tab-item>
              <v-tab-item id="tab-2">
                <v-card>
                  <div class="mt-3 ml-3">
                    <a class="grey--text text--darken-3 body-2">
                      <span @click="loadComment(page)" label>Xem những nhận xét cũ hơn ></span>
                    </a>
                  </div>
                  <v-list three-line>
                    <v-list-tile v-for="item in comments.data" avatar :key="item.title">
                      <v-list-tile-avatar>
                        <img src="#">
                      </v-list-tile-avatar>
                      <v-list-tile-content>
                        <v-list-tile-title v-html="item.user.name"></v-list-tile-title>
                        <v-list-tile-sub-title class="subtitleComment" v-html="item.content"></v-list-tile-sub-title>
                      </v-list-tile-content>
                      <v-list-tile-action class="pl-5">
                        <v-list-tile-action-text>{{ item.created_at }}</v-list-tile-action-text>
                      </v-list-tile-action>
                    </v-list-tile>

                    <v-layout row wrap>
                      <v-text-field @keyup.enter="postComment" v-model="commenttext" name="input-1-3" label="Lời nhận xét của bạn" single-line></v-text-field>
                      <div class="ml-0 mr-2">
                        <v-btn @click="postComment" color="green accent-4 white--text">Gửi</v-btn>
                        <v-snackbar :timeout="timeout" top v-model="snackbarComment" color="green accent-4">
                          Vui lòng đăng nhập hoặc đăng ký trước khi nhận xét
                          <v-btn flat icon color="white" @click.native="snackbarComment = false">
                            <v-icon>clear</v-icon>
                          </v-btn>
                        </v-snackbar>
                      </div>
                    </v-layout>

                  </v-list>
                </v-card>
              </v-tab-item>
            </v-tabs-items>
          </v-tabs>
          <div grid-list-xs class="my-5">
            <div class="headline grey--text text--darken-3 ">Những sản phẩm liên quan</div>
            <v-layout row wrap class="my-4 ml-3">
              <v-flex xs12 md6 lg4 v-for="(item,index) in books" :key="`Book-${index}`">
                <book-item :book="item"></book-item>
              </v-flex>
            </v-layout>
          </div>
        </v-flex>
      </v-layout>
    </v-container>
  </div>
</template>
<script>
export default {
  data() {
    return {
      snackbarComment: false,
      commenttext: "",
      bookDetail: {},
      tags: {},
      comments: [],
      books: {},
      snackbar: false,
      snackbarCartDetail: false,
      snackbarFavDetail: false,
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
      for (var index in this.$store.state.cart) {
        if (this.$store.state.cart[index].book.id === this.book.id) {
          this.snackbarCartDetail = true;
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
      this.snackbarCartDetail = true;
    },
    addFavoriteDetail() {
      for (var index in this.$store.state.favorite) {
        if (this.$store.state.favorite[index].id == this.book.id) {
          this.snackbarFavDetail = true;
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
      this.snackbarFavDetail = true;
    },
    formatPrice(price) {
      let val = (price / 1).toFixed(0).replace(".", ",");
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    },
    postComment() {
      if (this.$store.state.token) {
        window.axios
          .post("/add-comment/" + this.$route.query.type, {
            content: this.commenttext
          })
          .then(response => {
            console.log("add comment", response.data);
            let data = response.data.data;
            data.user = this.$store.state.user;
            this.comments.data.push(data);
            this.commenttext = "";
          });
      } else {
        this.snackbarComment = true;
        this.commenttext = "";
      }
    },
    loadComment() {
      let type = this.$route.query.type;
      if (this.comments.current_page <= this.comments.last_page) {
        let page = this.comments.current_page + 1;
        console.log(this.comments);
        let url = `/getmorecomments/${type}?page=${page}`;
        window.axios.get(url).then(response => {
          let data = response.data;
          let dataold = this.comments.data;
          this.comments = response.data;
          this.comments.data.reverse();
          for (let i = 0; i < this.comments.data.length; i++) {
            for (let j = 0; j < dataold.length; j++) {
              if (this.comments.data[i].id === dataold[j].id) {
                this.comments.data.splice(i, 1);
                i--;
              }
            }
          }
          this.comments.data = [...this.comments.data, ...dataold];
        });
      }
    }
  },
  watch: {
    "$route.query.type"(val) {
      console.log("load thành công");
      window.axios
        .get("/books/" + this.$route.query.type)
        .then(response => {
          window.scrollTo(0, 0);
          this.bookDetail = response.data.book;
          this.books = response.data.samebooks;
          this.tags = response.data.book.tags;
          this.comments = response.data.comments;
          this.comments.data.reverse();
          this.currentComment = response.data.comments.current_page;
          console.log(this.currentComment);
        })
        .catch(function(error) {
          console.log(error);
        });
    }
  },
  mounted() {
    window.axios
      .get("/books/" + this.$route.query.type)
      .then(response => {
        // console.log("DDaay laf detail", response.data);
        this.bookDetail = response.data.book;
        this.books = response.data.samebooks;
        this.tags = response.data.book.tags;
        this.comments = response.data.comments;
        this.comments.data.reverse();
        this.currentComment = response.data.comments.current_page;
        console.log(this.currentComment);
        // console.log(this.comments);
        // console.log("đây là tác phẩm của detail", response.data);
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
.seemore-description {
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}
</style>