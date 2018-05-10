<template>
  <v-card flat>
    <v-container fluid grid-list-lg py-0 pb-4>
      <v-layout row wrap offset-sm3 class="hover-card">
        <v-flex xs12 sm5 md5 class=" py-0 px-0">
          <router-link :to="`/detail?type=`+book.slug" class="link-book">
            <v-card-media :src="'/storage/images/'+book.image" height="180px"></v-card-media>
          </router-link>
        </v-flex>
        <v-flex xs12 sm7 md7 class="grey lighten-5 pl-3">
          <div>
            <router-link :to="`/detail?type=`+book.slug" class="link-book">
              <v-tooltip bottom>
                <h3 class="block-with-text" slot="activator">{{book.name}} </h3>
                <span>{{book.name}} </span>
              </v-tooltip>
            </router-link>
            <!-- <router-link :to="`/detail?type=`+book.slug" class="link-book">
              <h3>{{book.name}}</h3>
            </router-link> -->
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
                <v-icon v-if="!love" color="grey lighten-1">favorite</v-icon>
                <v-icon v-else color="red">favorite</v-icon>
              </v-btn>
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
      timeout: 3000,
      love: false
    };
  },
  watch: {},
  methods: {
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
      let favorite = this.$store.state.favorite;
      for (var index in favorite) {
        if (favorite[index].book.id === this.book.id) {
          if (i > -1) {
            console.log("không đỏ");
            favorite.splice(favorite[index], 1);
            love: false, this.$store.dispatch("setFavorite", favorite);
            return;
          }
        }
      }
      console.log("Đỏ");

      let itemBook = {
        book: this.book,
        quantity: 1,
        love: true
      };
      favorite.push(itemBook);
      this.$store.dispatch("setFavorite", favorite);
    },
    formatPrice(price) {
      let val = (price / 1).toFixed(0).replace(".", ",");
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
  }
};
</script>

<style>
.link-book {
  text-decoration: none;
  color: black;
}
.hover-card:hover {
  /* border: solid 1px #00c853 !important; */
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
