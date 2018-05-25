<template>
  <v-card flat>
    <v-container fluid grid-list-lg py-0 pb-4>
      <v-layout row wrap offset-sm3 class="hover-card">
        <v-flex xs12 sm5 md5 class=" py-0 px-0">
          <router-link :to="`/detail?type=`+book.slug" class="link-book">
            <v-card-media :src="'/storage/images/'+book.image" height="200px"></v-card-media>
          </router-link>
        </v-flex>
        <v-flex xs12 sm7 md7 class="grey lighten-5 pl-3">
          <div>
            <router-link :to="`/detail?type=`+book.slug" class="link-book">
              <v-tooltip bottom>
                <h3 class="block-with-text" slot="activator">{{book.name}} </h3>
                <span>{{book.name}}</span>
              </v-tooltip>
            </router-link>
            <p class="grey--text text--darken-1 mt-1">Tác giả: {{book.author.name}}</p>
            <v-card-actions v-if="book.promotion_price">
              <div class="green--text text--accent-4 title"> {{formatPrice(book.promotion_price)}}
                <span style="text-decoration: underline">đ</span>
              </div>
              <v-spacer></v-spacer>
              <div class="grey--text text--darken-1 ">
                <del>{{formatPrice(book.price)}}
                  <span style="text-decoration: underline">đ</span>
                </del>
              </div>
            </v-card-actions>
            <v-card-actions v-else>
              <div class="grey--text text--darken-1 ">
                <div class="green--text text--accent-4 title">{{formatPrice(book.price)}}
                  <span style="text-decoration: underline">đ</span>
                </div>
              </div>
            </v-card-actions>
            <v-divider class="mt-2"></v-divider>
            <v-card-actions>
              <v-btn flat icon color="red" @click="addCart">
                <v-icon>add_shopping_cart</v-icon>
              </v-btn>
              <v-snackbar :timeout="timeout" top v-model="snackbar" color="green accent-4">
                Thêm vào giỏ hàng thành công
                <v-btn flat icon color="white" @click.native="snackbar = false">
                  <v-icon>clear</v-icon>
                </v-btn>
              </v-snackbar>
              <v-spacer></v-spacer>
              <v-btn flat icon color="grey" @click="addItemfavorite()">
                <v-icon :color="isLove()?'red':'grey lighten-1'">favorite</v-icon>
              </v-btn>
              <v-snackbar :timeout="timeout" top v-model="snackbarFavorite" color="green accent-4">
                <span v-if="!$store.state.token">Vui lòng đăng nhập hoặc đăng ký tài khoản</span>
                <span v-else-if="love === false">Bỏ yêu thích thành công</span>
                <span v-if="love === true">Thêm vào yêu thích thành công</span>
                <v-btn flat icon color="white" @click.native="snackbarFavorite = false">
                  <v-icon>clear</v-icon>
                </v-btn>
              </v-snackbar>
            </v-card-actions>
          </div>
        </v-flex>
      </v-layout>
    </v-container>
  </v-card>
</template>
<script>
export default {
  props: ["book"],
  data() {
    return {
      snackbar: false,
      snackbarFavorite: false,
      timeout: 3000,
      love: false
    };
  },
  watch: {},
  methods: {
    isLove() {
      if (this.$store.state.favorite && this.$store.state.favorite.length > 0)
        return this.$store.state.favorite.find(item => {
          return item.id === this.book.id;
        });
      return false;
    },
    addCart() {
      let cart = this.$store.state.cart;
      for (var index in cart) {
        if (cart[index].book.id === this.book.id) {
          cart[index].quantity = cart[index].quantity + 1;
          this.snackbar = true;
          return;
        }
      }
      //
      let itemBook = {
        book: this.book,
        quantity: 1
      };
      cart.push(itemBook);
      this.$store.dispatch("setCart", cart);
      this.snackbar = true;
    },
    addItemfavorite() {
      if (this.$store.state.token) {
        let favorite = this.$store.state.favorite;
        for (var index in favorite) {
          if (favorite[index].id === this.book.id) {
            window.axios
              .get("/removefavorite/" + favorite[index].id)
              .then(response => {
                favorite.splice(index, 1);
                this.love = false;
                this.snackbarFavorite = true;
                this.$store.dispatch("setFavorite", favorite);
              })
              .catch(function(error) {
                console.log(error);
              });
            return;
          }
        }
        //
        window.axios
          .post("/add-favorite/" + this.book.id, {
            id_book: this.book.id
          })
          .then(response => {
            favorite.push(this.book);
            this.$store.dispatch("setFavorite", favorite);
            this.love = true;
            this.snackbarFavorite = true;
          })
          .catch(function(error) {
            console.log(error);
          });
      } else {
        this.snackbarFavorite = true;
      }
    },
    formatPrice(price) {
      let val = (price / 1).toFixed(0).replace(".", ",");
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
  },
  mounted() {}
};
</script>

<style>
.link-book {
  text-decoration: none;
  color: black;
}
.hover-card:hover {
  box-shadow: 0 5px 5px -3px rgba(0, 0, 0, 0.2),
    0 8px 10px 1px rgba(0, 0, 0, 0.14), 0 3px 14px 2px rgba(0, 0, 0, 0.12);
  transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
  transition-property: box-shadow;
}
.hover-card {
  border: solid 1px #f5f5f5 !important;
}
.block-with-text {
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}
</style>