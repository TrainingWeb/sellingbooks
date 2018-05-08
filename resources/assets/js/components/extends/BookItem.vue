<template>
  <v-card flat>
    <v-container fluid grid-list-lg py-0 pb-4>
      <v-layout row wrap offset-sm3 class="hover-card">
        <v-flex xs12 sm5 md5 class=" py-0 px-0">
          <router-link to="/detail" class="link-book">
            <v-card-media :src="book.img" height="180px"></v-card-media>
          </router-link>
        </v-flex>
        <v-flex xs12 sm7 md7 class="grey lighten-5 pl-3">
          <div>
            <router-link to="/detail" class="link-book">
              <h3>{{book.name}}</h3>
            </router-link>
            <p class="grey--text text--darken-1 mt-1">Tác giả: {{book.author}}</p>
            <v-card-actions>
              <div class="green--text text--accent-4 title"> {{formatPrice(book.price)}}
                <span style="text-decoration: underline">đ</span>
              </div>
              <v-spacer></v-spacer>
              <div class="grey--text text--darken-1 ">
                <del>{{formatPrice(book.sale)}}
                  <span style="text-decoration: underline">đ</span>
                </del>
              </div>
            </v-card-actions>
            <v-divider class="mt-2"></v-divider>
            <v-card-actions>
              <v-btn flat icon color="red" @click="addCart">
                <v-icon>add_shopping_cart</v-icon>
              </v-btn>
              <v-spacer></v-spacer>
              <v-btn flat icon color="grey" @click="addItemfavorite(book.id)">
                <v-icon v-if="$store.state.selected.indexOf(book.id) < 0" color="grey lighten-1">favorite</v-icon>
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
    return {};
  },
  watch: {},
  methods: {
    addCart() {
      for (var index in this.$store.state.cart) {
        if (this.$store.state.cart[index].book.id === this.book.id) {
          this.$store.state.cart[index].quantity =
            this.$store.state.cart[index].quantity + 1;
          return;
        }
      }
      //
      let itemBook = {
        book: this.book,
        quantity: 1
      };
      let cart = this.$store.state.cart;
      cart.push(itemBook);
      this.$store.dispatch("setCart", cart);
      alert("Chúc mừng bạn thêm giỏ hàng thành công");
    },
    addItemfavorite(id) {
      const i = this.$store.state.selected.indexOf(id);
      let favorite = this.$store.state.favorite;
      for (var index in this.$store.state.favorite) {
        if (this.$store.state.favorite[index].book.id === this.book.id) {
          if (i > -1) {
            console.log("không đỏ");
            favorite.splice(this.$store.state.favorite[index], 1);
            this.$store.dispatch("setFavorite", favorite);
            this.$store.state.selected.splice(i, 1);
            this.$store.dispatch("setSelected", selected);
            return;
          }
        }
      }
      console.log("Đỏ");

      let itemBook = {
        book: this.book,
        quantity: 1
      };
      favorite.push(itemBook);
      this.$store.dispatch("setFavorite", favorite);
      this.$store.state.selected.push(id);
      this.$store.dispatch("setSelected", selected);
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
  border: solid 1px #00c853 !important;
  box-shadow: 0 5px 5px -3px rgba(0, 0, 0, 0.2),
    0 8px 10px 1px rgba(0, 0, 0, 0.14), 0 3px 14px 2px rgba(0, 0, 0, 0.12);
  transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
  transition-property: box-shadow;
}
.hover-card {
  border: solid 1px #f5f5f5 !important;
}
</style>
