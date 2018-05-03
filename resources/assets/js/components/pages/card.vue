<template>
    <div>
        <v-layout xs12>
            <v-banner :value="{title:namepage,breadcrumbs}"></v-banner>
        </v-layout>
        <v-container class="py-5">
            <v-data-table :headers="headers" :items="books" hide-actions flat>
                <template slot="items" slot-scope="props">
                    <tr class="py-1">
                        <td class="py-2">
                            <img :src="props.item.img" alt="" width="40px" height="60px">
                        </td>
                        <td>{{ props.item.name }}</td>
                        <td>{{ props.item.total }}</td>
                        <td>
                            <v-layout row wrap>
                                <v-flex xs12 md2>
                                    <v-text-field name="input-3" value="1" solo></v-text-field>
                                </v-flex>
                            </v-layout>
                        </td>
                        <td>{{ props.item.subtotal }}</td>
                        <td>
                            <v-btn flat icon color="red">
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
                    <strong color="black">400.000</strong>
                </v-flex>
            </v-layout>
            <v-layout row wrap class="pt-3 border-top">
                <v-flex xs6>
                    <v-btn dark color="green accent-4"> Tiếp tục mua hàng</v-btn>
                </v-flex>
                <v-flex xs6 text-xs-right>
                    <v-btn dark color="green accent-4">
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
    books: [
      {
        img:
          "http://static.ybox.vn/2017/4/11/7397e6ae-1e80-11e7-a0c9-2e995a9a3302.jpg",
        name: "Cô gái mở đường",
        total: "100.000",
        qty: 2,
        subtotal: "200.000"
      },
      {
        img:
          "http://static.ybox.vn/2017/4/11/7397e6ae-1e80-11e7-a0c9-2e995a9a3302.jpg",
        name: "Cô gái mở đường",
        total: "100.000",
        qty: 2,
        subtotal: "200.000"
      }
    ]
  })
};
</script>

<style>
.border-top {
  border-top: 1px rgba(0, 0, 0, 0.12) solid;
}
</style>
