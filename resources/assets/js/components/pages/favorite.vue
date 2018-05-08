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
              <img :src="props.item.book.img" alt="" width="80px" height="120px">
            </td>
            <td>
              <router-link class="subheading text-xs-left red--text text--darken-4" style="text-decoration:none" to="/detail">{{ props.item.book.name }}</router-link>
            </td>
            <td>
              <v-layout row wrap>
                <div class="green--text text--accent-4 title "> {{props.item.book.price}}</div>
                <span class="grey--text text--darken-1 title ml-3">
                  <del>{{props.item.book.sale}}</del>
                </span>
              </v-layout>
            </td>
            <td>
              <v-flex xs12 md3 class="mx-0 my-3">
                <v-text-field type="number" flat solo :value="props.item.quantity" @input="upadateQantity(props.item.book.id, $event)"></v-text-field>
              </v-flex>
            </td>
            <td>
              <v-btn class="mx-0 my-3" color="green accent-4 white--text" @click="addCartPageFavorite(props.item)">
                <i class="material-icons add-shopping mr-2 white--text">add_shopping_cart</i>Thêm
              </v-btn>
            </td>
            <td class="text-xs-center layout px-0 py-5">
              <!-- <v-btn icon class="" @click="deleteItem(props.item)">
                <v-icon class="red--text text--darken-4">clear</v-icon>
              </v-btn> -->
              <v-layout row justify-center>
                <v-dialog flat v-model="dialogDelete" persistent max-width="290">
                  <v-btn icon slot="activator">
                    <v-icon class="red--text text--darken-4">clear</v-icon>
                  </v-btn>
                  <v-card flat>
                    <v-card-title class="subheading ml-0 green accent-4 white--text">Thông báo !</v-card-title>
                    <v-card-text class="body-1">Bạn có muốn xóa sản phẩm khỏi trang yêu thích không?</v-card-text>
                    <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn color="green darken-1" flat @click.native="dialogDelete = false">Hủy</v-btn>
                      <v-btn color="green darken-1" @click="deleteItem(props.item)" flat @click.native="dialogDelete = false">Xóa</v-btn>
                    </v-card-actions>
                  </v-card>
                </v-dialog>
              </v-layout>
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

      { text: "Giá tiền", value: "", sortable: false },
      { text: "Số lượng", value: "", sortable: false },
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
    page: 1,
    dialogDelete: false
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
    addCartPageFavorite(val) {
      for (var index in this.$store.state.cart) {
        if (this.$store.state.cart[index].book.id === val.id) {
          alert(
            "Sản phẩm này đã có trong giỏ hàng của bạn vui lòng không chọn thêm"
          );
          return;
        }
      }
      let itemBook = {
        book: val.book,
        quantity: val.quantity
      };
      let cart = this.$store.state.cart;
      cart.push(itemBook);
      this.$store.dispatch("setCart", cart);
    },
    upadateQantity(id, e) {
      let favorite = this.$store.state.favorite;
      for (let index = 0; index < this.$store.state.favorite.length; index++) {
        if (this.$store.state.favorite[index].book.id == id) {
          this.$store.state.favorite[index].quantity = e;
          this.$store.dispatch("setFavorite", favorite);
          break;
        }
      }
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