<template>
  <div>
    <v-layout xs12>
      <v-banner :value="{title:namepage,breadcrumbs}"></v-banner>
    </v-layout>
    <v-container class="py-5">
      <v-data-table :headers="headers" :items="$store.state.cart" hide-actions flat>
        <template slot="items" slot-scope="props">
          <tr class="py-1">
            <td>
              <v-layout row justify-center>
                <v-btn flat icon color="red" @click.native.stop="dialogDel = true">
                  <v-icon>clear</v-icon>
                </v-btn>
                <v-dialog v-model="dialogDel" max-width="290">
                  <v-card>
                    <v-toolbar color="green accent-4" dark>
                      <v-toolbar-title>Thông báo</v-toolbar-title>

                    </v-toolbar>
                    <v-card-text>Bạn có chắc chắn xóa sách :<br>
                      <strong>{{ props.item.book.name }}</strong>
                    </v-card-text>
                    <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn color="green accent-4" flat="flat" @click.native="dialogDel = false">HỦY</v-btn>
                      <v-btn color="green accent-4" flat="flat" @click="delCart(props.item)">OK</v-btn>
                    </v-card-actions>
                  </v-card>
                </v-dialog>
              </v-layout>
            </td>
            <td class="py-2">
              <img :src="props.item.book.img" alt="" width="40px" height="60px">
            </td>
            <td>{{ props.item.book.name }}</td>
            <td>{{ formatPrice(props.item.book.price) }} đ</td>
            <td>
              <v-layout row wrap>
                <v-flex xs12 md3>
                  <v-text-field type="number" flat solo :value="props.item.quantity" min="1" @input="upadateQty(props.item.book.id, $event)"></v-text-field>
                </v-flex>
              </v-layout>
            </td>
            <td class="text-xs-right"> {{formatPrice(props.item.book.price*props.item.quantity)}} đ</td>
          </tr>
        </template>
        <template slot="footer">
          <td colspan="100%" class="py-3">
            <v-card-actions class="pr-0">
              <strong>Tổng tiền</strong>
              <v-spacer></v-spacer>
              <strong class="mr-0">{{formatPrice(total)}} đ</strong>
            </v-card-actions>

          </td>
        </template>
      </v-data-table>
      <!-- <v-layout row wrap class="py-4 border-top">
        <v-flex xs6>
          <strong class="title" color="black">Tổng tiền</strong>
        </v-flex>
        <v-flex xs4 text-xs-right>
          <strong color="black">{{formatPrice(total)}} đ</strong>
        </v-flex>
      </v-layout> -->
      <v-layout row wrap class="pt-3 border-top">
        <v-flex xs6>
          <v-btn dark color="green accent-4" to="/"> Tiếp tục mua hàng</v-btn>
        </v-flex>
        <v-flex xs6 text-xs-right>
          <v-btn dark color="green accent-4" to="/check-out">
            thanh toán
          </v-btn>
        </v-flex>
      </v-layout>
    </v-container>
  </div>
</template>

<script>
export default {
  data: () => ({
    dialogDel: false,
    breadcrumbs: [
      {
        name: "Trang Chủ",
        url: "/",
        disabled: false
      },
      {
        name: "Giỏ hàng",
        url: "/card",
        disabled: true
      }
    ],
    namepage: "Giỏ hàng của bạn",
    page: 1,
    headers: [
      {
        sortable: false
      },
      { sortable: false },
      { text: "Tên sách", sortable: false },
      { text: "Giá tiền", sortable: false },
      { text: "Số lượng", sortable: false },
      { text: "Tồng tiền", sortable: false }
    ],
    qty: 0,
    sub: 0
  }),
  methods: {
    upadateQty(id, e) {
      let cart = this.$store.state.cart;
      for (var index in cart) {
        if (cart[index].book.id == id) {
          console.log("vẫn giảm đc");
          cart[index].quantity = e;
          this.$store.dispatch("setCart", cart);
          return;
        }
      }
    },
    delCart(item) {
      let cart = this.$store.state.cart;
      let index = cart.indexOf(item);
      this.dialogDel = false;
      if (index >= 0) {
        cart.splice(index, 1);
        this.$store.dispatch("setCart", cart);
      }
    },
    formatPrice(price) {
      let val = (price / 1).toFixed(0).replace(".", ",");
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
  },
  computed: {
    total() {
      return this.$store.state.cart.reduce((total, p) => {
        return total + p.book.price * p.quantity;
      }, 0);
    }
  }
};
</script>

<style>
.border-top {
  border-top: 1px rgba(0, 0, 0, 0.12) solid;
}
</style>