<template>
  <div>
    <v-layout xs12>
      <v-banner :value="{title:namepage,breadcrumbs}"></v-banner>
    </v-layout>
    <v-container>
      <v-data-table :headers="headers" :items="$store.state.favorite" hide-actions flat>
        <template slot="items" slot-scope="props">
          <tr class="py-1">
            <td class="py-2">
              <img :src="props.item.img" alt="" width="100px" height="150px">
            </td>
            <td class="title text-xs-left" color="red">{{ props.item.name }}</td>
            <td>
              <v-layout row wrap>
                <div class="green--text text--accent-4 title mt-3"> {{props.item.price}}</div>
                <span class="grey--text text--darken-1 title mt-3 ml-3">
                  <del>{{props.item.sale}}</del>
                </span>
              </v-layout>
              <v-btn class="mx-0 my-3" color="green accent-4 white--text" @click="addCartPageFavorite">
                <i class="material-icons add-shopping mr-2 white--text">add_shopping_cart</i>Thêm
              </v-btn>
            </td>
            <td class="justify-center layout px-0">
              <v-btn icon class="my-5" @click="deleteItem(props.item)">
                <v-icon color="pink">clear</v-icon>
              </v-btn>
            </td>
          </tr>
        </template>
      </v-data-table>

    </v-container>
  </div>
</template>

<script>
export default {
  data: () => ({
    headers: [
      { text: "", value: "img", sortable: false },
      {
        text: "Tên sách",
        align: "left",
        sortable: false,
        value: "name"
      },

      { text: "Chọn mua", value: "", sortable: false },
      { text: "", value: "", sortable: false }
    ],
    breadcrumbs: [
      {
        name: "Trang Chủ",
        url: "/",
        disabled: false
      },
      {
        name: "Sản phẩm yêu thích",
        url: "/favorite",
        disabled: true
      }
    ],
    e1: null,
    namepage: "Sản phẩm yêu thích",
    page: 1
  }),
  methods: {
    deleteItem(item) {
      let favorite = this.$store.state.favorite;
      let index = favorite.indexOf(item);
      if (index >= 0) {
        favorite.splice(index, 1);
        this.$store.dispatch("setFavorite", favorite);
      }
    },
    addCartPageFavorite() {
      for (var index in this.$store.state.cart) {
        if (this.$store.state.cart[index].book.id === this.book.id) {
          alert(
            "sản phẩm này đã có trong giỏ hàng của bạn vui lòng không chọn thêm"
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
    }
  }
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
