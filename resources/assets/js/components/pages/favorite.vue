<template>
  <div>
    <v-layout xs12>
      <v-banner :value="{title:namepage,breadcrumbs}"></v-banner>
    </v-layout>
    <v-container>
      <v-data-table v-if="favoriteBook" :headers="headers" :items="favoriteBook" hide-actions flat>
        <template slot="items" slot-scope="props">
          <tr class="py-1">
            <td class="text-xs-center layout px-0 py-5">
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
                      <v-btn color="green darken-1" @click="delFavorite(props.index)" flat>Xóa</v-btn>
                    </v-card-actions>
                  </v-card>
                </v-dialog>
              </v-layout>
            </td>
            <td class="py-2">
              <img :src="'/storage/images/'+props.item.image" alt="" width="80px" height="120px">
            </td>
            <td>
              <router-link class="subheading text-xs-left red--text text--darken-4" style="text-decoration:none" to="/detail">{{ props.item.name }} {{props.item.id}}
              </router-link>
            </td>
            <td class="text-xs-left">
              <div v-if="props.item.promotion_price">
                <span class="green--text text--accent-4 title "> {{formatPrice(props.item.promotion_price)}}đ</span>
                <span class="grey--text text--darken-1 title ml-3">
                  <del>{{formatPrice(props.item.price)}}đ</del>
                </span>
              </div>
              <div v-else>
                <span class="green--text text--accent-4 title ml-3">
                  {{formatPrice(props.item.price)}}đ
                </span>
              </div>
            </td>
            <td>
              <v-btn class="mx-0 my-3" color="green accent-4 white--text" @click="addCartPageFavorite(props.item)">
                <i class="material-icons add-shopping mr-2 white--text">add_shopping_cart</i>Thêm
              </v-btn>
              <v-snackbar :timeout="timeout" top v-model="snackbarCart" color="green accent-4">
                Thêm vào giỏ hàng thành công
                <v-btn flat icon color="white" @click.native="snackbarCart = false">
                  <v-icon>clear</v-icon>
                </v-btn>
              </v-snackbar>
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
      { sortable: false },
      { sortable: false },
      {
        text: "Tên sách",
        align: "left",
        sortable: false
      },
      { text: "Giá tiền", align: "left", sortable: false },
      { text: "Chọn mua", sortable: false }
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
    dialogDelete: false,
    snackbarCart: false,
    timeout: 3000,
    favoriteBook: []
  }),
  methods: {
    formatPrice(price) {
      let val = (price / 1).toFixed(0).replace(".", ",");
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    },
    delFavorite(index) {
      window.axios
        .get("/removefavorite/" + this.favoriteBook[index].id)
        .then(response => {
          this.favoriteBook.splice(index, 1);
          this.dialogDelete = false;
        })
        .catch(function(error) {
          console.log(error);
        });
      //
    },
    addCartPageFavorite(item) {
      let cart = this.$store.state.cart;
      for (var index in cart) {
        if (cart[index].book.id === item.id) {
          cart[index].quantity = cart[index].quantity + 1;
          this.snackbarCart = true;
          return;
        }
      }
      let itemBook = {
        book: item,
        quantity: 1
      };
      cart.push(itemBook);
      this.$store.dispatch("setCart", cart);
      this.snackbarCart = true;
    }
  },
  mounted() {
    window.axios
      .get("/get-favorite-books")
      .then(response => {
        this.favoriteBook = response.data.data;
        console.log(response.data, "danh sách");
      })
      .catch(function(error) {
        console.log(error);
      });
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
.dialog {
  box-shadow: none;
}
</style> 