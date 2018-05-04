<template>
    <div>
        <v-layout xs12>
            <v-banner :value="{title:namepage,breadcrumbs}"></v-banner>
        </v-layout>
        <v-container class="py-5">
            <v-data-table :headers="headers" :items="$store.state.cart" hide-actions flat>
                <template slot="items" slot-scope="props">
                    <tr class="py-1">
                        <td class="py-2">
                            <img :src="props.item.img" alt="" width="40px" height="60px">
                        </td>
                        <td>{{ props.item.name }}</td>
                        <td>{{ props.item.price }}</td>
                        <td>
                            <v-layout row wrap>
                                <v-flex xs12 md2>
                                    <v-text-field type="number" solo value="1"></v-text-field>
                                </v-flex>
                            </v-layout>
                        </td>
                        <td>{{ props.item.subtotal }}</td>
                        <td>
                            <v-btn flat icon color="red" @click="delCart(props.item)">
                                <v-icon>clear</v-icon>
                            </v-btn>
                        </td>
                    </tr>
                </template>
            </v-data-table>
            <v-layout row wrap class="py-4 border-top">
                <v-flex xs6>
                    <strong class="title" color="black">Tổng tiền</strong>
                </v-flex>
                <v-flex xs4 text-xs-right>
                    <strong color="black">{{total()}}</strong>
                </v-flex>
            </v-layout>
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
      { text: "Tên sách", sortable: false },
      { text: "Giá tiền", sortable: false },
      { text: "Số lượng", sortable: false },
      { text: "Tồng tiền", sortable: false },
      { sortable: false }
    ],
    qty: 0,
    sub: 0
  }),
  methods: {
    total() {
      console.log(this.$store.state.cart);
      return this.$store.state.cart.reduce((num, item) => item.price + num, 0);
    },
    delCart(item) {
      let cart = this.$store.state.cart;
      let index = cart.indexOf(item);
      if (index >= 0) {
        cart.splice(index, 1);
        this.$store.dispatch("setCart", cart);
      }
    }
  }
};
</script>

<style>
.border-top {
  border-top: 1px rgba(0, 0, 0, 0.12) solid;
}
</style>
