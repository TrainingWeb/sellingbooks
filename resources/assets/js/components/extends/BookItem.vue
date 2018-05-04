<template>
    <v-card flat>
        <v-container fluid grid-list-lg>
            <v-layout row wrap>
                <v-flex xs5 class="pr-0 py-0">
                    <router-link to="/detail" class="link-book">
                        <v-card-media :src="book.img" height="200px" max-width="130px"></v-card-media>
                    </router-link>
                </v-flex>
                <v-flex xs7 class="grey lighten-5 pl-3">
                    <div>
                        <router-link to="/detail" class="link-book">
                            <h3>{{book.name}}</h3>
                        </router-link>
                        <p class="grey--text text--darken-1 mt-1">Tác giả: {{book.author}}</p>
                        <span class="green--text text--accent-4 title"> {{book.price}}</span>
                        <span class="grey--text text--darken-1 title ml-3">
                            <del>{{book.sale}}</del>
                        </span>
                        <v-divider class="mt-2"></v-divider>
                        <v-card-actions>
                            <v-btn flat icon color="red" @click="addCart">
                                <v-icon>add_shopping_cart</v-icon>
                            </v-btn>
                            <v-spacer></v-spacer>
                            <v-btn flat icon color="grey" @click="favorite">
                                <v-icon>favorite</v-icon>
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
      for (let index = 0; index < this.$store.state.cart.length; index++) {
        if (this.$store.state.cart[index].id == this.book.id) {
          alert(
            "sản phẩm này đã có trong giỏ hàng của bạn vui lòng không chọn thêm"
          );
        }
      }
      let cart = this.$store.state.cart;
      cart.push(this.book);
      this.$store.dispatch("setCart", cart);
    },
    favorite() {
      for (let index = 0; index < this.$store.state.favorite.length; index++) {
        if (this.$store.state.favorite[index].id == this.book.id) {
          alert("Sản phẩm này đã được bạn yêu thích");
        }
      }
      let favorite = this.$store.state.favorite;
      favorite.push(this.book);
      this.$store.dispatch("setFavorite", favorite);
    }
  }
};
</script>

<style>
.link-book {
  text-decoration: none;
  color: black;
}
</style>
