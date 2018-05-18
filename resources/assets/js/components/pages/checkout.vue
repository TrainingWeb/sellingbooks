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
                      <v-text-field label="Họ và tên" :value="$store.state.user.name" :rules="nameRules" disabled></v-text-field>
                      <v-text-field label="E-mail" :value="$store.state.user.email" disabled></v-text-field>
                      <template v-if="$store.state.user.phone">
                        <v-text-field :value="$store.state.user.phone" :rules="emailRules" label="Số điện thoại" disabled></v-text-field>
                        <v-text-field :rules="addressRules" :value="$store.state.user.address" label="Địa chỉ" disabled></v-text-field>
                      </template>
                      <template v-else>
                        <v-text-field v-model="phone" mask="phone" :rules="emailRules" label="Số điện thoại"></v-text-field>
                        <v-text-field v-model="address" :rules="addressRules" label="Địa chỉ"></v-text-field>
                      </template>
                    </v-form>
                  </v-container>
                </v-card>
              </v-flex>
              <v-flex xs6>
                <v-card>
                  <h1 class="headline grey--text text--darken-3 text-xs-center pt-3 "> Đơn hàng của bạn
                  </h1>
                  <v-data-table :headers="headers" :items="$store.state.cart" hide-actions class="">
                    <template slot="items" slot-scope="props">
                      <td class="py-4">
                        <strong>{{ props.item.book.name }} x{{props.item.quantity}}</strong>
                      </td>
                      <td class="py-4">
                        <strong>{{props.item.quantity}}</strong>
                      </td>
                      <td class="text-xs-right">{{ props.item.book.price * props.item.quantity}} </td>
                    </template>
                    <template slot="footer">
                      <td>
                        <strong>Thanh toán</strong>
                      </td>
                      <td></td>
                      <td class="text-xs-right">
                        <strong>{{total}}</strong>
                      </td>
                    </template>
                  </v-data-table>
                  <div class="my-3 text-xs-right ">
                    <v-btn class="my-4" slot="activator" @click="openDialog" dark color="green accent-4">
                      thanh toán
                    </v-btn>
                    <v-layout row justify-center>
                      <v-dialog v-model="dialog" persistent max-width="300">
                        <v-card>
                          <v-toolbar color="green accent-4" dark>
                            <v-toolbar-title>Thông báo</v-toolbar-title>
                          </v-toolbar>
                          <v-card-text>
                            Bạn có muốn đặt hàng
                          </v-card-text>
                          <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="green accent-4" flat @click="dialog = false">Hủy</v-btn>
                            <v-btn color="green accent-4" flat @click="userOrder">OK</v-btn>
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
      Đặt hàng thành công
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
    dialog: false,
    snackbar: false,
    snackbarcheck: false,
    dialogEdit: false,
    timeout: 3000,
    headers: [
      {
        text: "Sản phẩm",
        align: "left",
        sortable: false,
        value: "name"
      },
      {
        text: "Số lượng",
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
    namepage: "Kiểm tra đơn hàng",
    editedItem: {}
  }),
  methods: {
    openDialog() {
      if (this.$refs.form.validate()) {
        this.dialog = true;
      }
    },

    userOrder() {
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
              this.dialog = true;
              this.$store.state.cart = [];
              this.$store.dispatch("setCart", this.$store.state.cart);
            })
            .catch(function(error) {
              console.log(error);
            });
          if (this.$store.state.user.phone) {
            window.axios

              .post("/check-info", {
                name: this.$store.state.user.name,
                phone: this.$store.state.user.phone,
                address: this.$store.state.user.address
              })
              .then(response => {})
              .catch(function(error) {
                console.log(error);
              });
            window.location = "#/";
          } else {
            window.axios
              .post("/check-info", {
                name: this.$store.state.user.name,
                phone: this.phone,
                address: this.address
              })
              .then(response => {})
              .catch(function(error) {
                console.log(error);
              });
            window.location = "#/";
          }
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