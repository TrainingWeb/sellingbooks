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
                    <v-form ref="form" v-model="valid" lazy-validation>
                      <v-text-field label="Họ và tên" :value="$store.state.user.name" :rules="nameRules" v-model="name"></v-text-field>
                      <v-text-field label="E-mail" :value="$store.state.user.email" disabled></v-text-field>
                      <v-text-field v-model="phone" mask="phone" :rules="emailRules" label="Số điện thoại"></v-text-field>
                      <v-text-field v-model="address" :rules="addressRules" label="Địa chỉ"></v-text-field>
                      <v-btn @click="userOrder" dark color="green accent-4">
                        Lưu
                      </v-btn>
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
                      </td>
                      <td class="text-xs-right">
                        <strong>{{total}}</strong>
                      </td>
                    </template>
                  </v-data-table>
                  <div class="my-3 text-xs-right ">
                    <v-btn @click="oder" dark color="green accent-4">
                      thanh toán
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
                            <v-btn color="green accent-4" flat="flat" @click="dialogDel = false">OK</v-btn>
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
    <v-snackbar :timeout="timeout" top v-model="snackbar" color="green accent-4">
      Lưu thông tin thành công
      <v-btn flat icon color="white" @click.native="snackbar = false">
        <v-icon>clear</v-icon>
      </v-btn>
    </v-snackbar>
    <v-snackbar :timeout="timeout" top v-model="snackbarcheck" color="green accent-4">
      Vui lòng đăng nhập hoặc đăng kí để nhập hàng
      <v-btn flat icon color="white" @click.native="snackbar = false">
        <v-icon>clear</v-icon>
      </v-btn>
    </v-snackbar>
  </div>
</template>
<script>
export default {
  data: () => ({
    dialogDel: false,
    snackbar: false,
    snackbarcheck: false,
    timeout: 3000,
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
    valid: true,
    phone: "",
    name: "",
    address: "",
    addressRules: [v => !!v || "Địa chỉ là bắt buộc"],
    emailRules: [
      v => !!v || " Số điện thoại là bắt buộc",
      v => (v && v.length >= 10) || "Số điện thoại không đúng"
    ],
    nameRules: [v => !!v || " Tên là bắt buộc"],

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
  methods: {
    userOrder() {
      if (this.$store.state.token) {
        if (this.$refs.form.validate()) {
          window.axios
            .post("/check-info", {
              name: this.name,
              phone: this.phone,
              address: this.address
            })
            .then(response => {
              this.snackbar = true;
            })
            .catch(function(error) {
              console.log(error);
            });
        }
      } else {
        this.snackbarcheck = true;
      }
    },
    oder() {
      if (this.$store.state.token) {
        if (this.$refs.form.validate()) {
          let book = this.$store.state.cart;
          let book_id = [];
          let qty = [];
          for (let index in book) {
            book_id.push(book[index].book.id);
            qty.push(book[index].quantity);
          }
          window.axios
            .post("/finish-checkout", {
              id: book_id,
              quantity: qty
            })
            .then(response => {
              this.$store.state.cart = [];
              this.$store.dispatch("setCart", cart);
              this.dialogDel = true;
              window.location = `#/`;
            })
            .catch(function(error) {
              console.log(error);
            });
        }
      } else {
        this.snackbarcheck = true;
      }
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