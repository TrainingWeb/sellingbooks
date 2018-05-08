<template>
  <div>
    <v-layout xs12>
      <v-banner :value="{title:namepage,breadcrumbs}"></v-banner>
    </v-layout>
    <v-container class="px-0">
      <v-flex xs12>
        <v-card flat color=" darken-2" class="white--text px-1">
          <v-container>
            <v-layout row>
              <v-flex xs6 class="mx-3">
                <v-card>
                  <h1 class=" headline grey--text text--darken-3 text-xs-center pt-3">Vui lòng xác nhận lại thông tin của bạn</h1>
                  <v-container>
                    <v-form v-model="valid" class="mt-5">
                      <v-text-field label="Họ và tên" v-model="name" :rules="nameRules" :counter="10" required></v-text-field>
                      <v-text-field label=" Địa chỉ E-mail" v-model="email" :rules="emailRules" required></v-text-field>
                      <v-text-field label="Số Điện Thoại" mask="phone" v-model="phone" :rules="phoneRules" required></v-text-field>
                      <v-text-field label="Địa Chỉ" v-model="address" :rules="addressRules" required></v-text-field>
                    </v-form>
                  </v-container>
                </v-card>
              </v-flex>
              <v-flex xs6>
                <v-card>
                  <h1 class="headline grey--text text--darken-3 text-xs-center pt-3 "> Đơn hàng của bạn
                  </h1>
                  <v-data-table :headers="headers" :items="$store.state.cart" hide-actions class="mt-5">
                    <template slot="items" slot-scope="props">
                      <td class="py-4">
                        <strong>{{ props.item.book.name }} x{{props.item.quantity}}</strong>
                      </td>
                      <td class="text-xs-right">{{ props.item.book.price * props.item.quantity}} </td>
                    </template>
                    <template slot="footer">
                      <td>
                        <strong>Thanh toán</strong>
                        <!--  -->

                        <!--  -->
                      </td>
                      <td class="text-xs-right">
                        <strong>{{total}}</strong>
                      </td>
                    </template>
                  </v-data-table>
                  <div class="mt-3 text-xs-right ">
                    <v-btn dark color="green accent-4" @click.native.stop="dialogDel = true">
                      Đặt hàng
                    </v-btn>
                    <v-layout row justify-center>

                      <v-dialog v-model="dialogDel" max-width="290">
                        <v-card>
                          <v-toolbar color="green accent-4" dark>
                            <v-toolbar-title>Thông báo</v-toolbar-title>
                          </v-toolbar>
                          <v-card-text>Bạn đã đặt hàng thành công<br>

                          </v-card-text>
                          <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="green accent-4" flat="flat" @click.native="dialogDel = false" to="/">OK</v-btn>

                          </v-card-actions>
                        </v-card>
                      </v-dialog>
                    </v-layout>
                  </div>
                </v-card>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card>
      </v-flex>
    </v-container>
  </div>
</template>
<script>
export default {
  data: () => ({
    dialogDel: false,
    headers: [
      {
        text: "Sản phẩm",
        align: "left",
        sortable: false,
        value: "name"
      },
      {
        text: "Giá tiền",
        align: "right",
        value: "total",
        sortable: false
      }
    ],

    valid: false,
    name: "",
    nameRules: [v => !!v || "Tên là bắt buộc"],
    email: "",
    emailRules: [
      v => !!v || "E-mail là bắt buộc",
      v =>
        /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
        "E-mail không hợp lệ"
    ],
    phone: "",
    phoneRules: [v => !!v || "Số điện thoại là bắt buộc"],
    address: "",
    addressRules: [v => !!v || "Địa chỉ là bắt buộc"],
    breadcrumbs: [
      {
        name: "Trang Chủ",
        url: "/",
        disabled: false
      },
      {
        name: "Kiểm tra đơn hàng",
        url: "/about",
        disabled: true
      }
    ],
    namepage: "Kiểm tra đơn hàng"
  }),
  computed: {
    total() {
      return this.$store.state.cart.reduce((total, p) => {
        return total + p.book.price * p.quantity;
      }, 0);
    }
  }
};
</script>