<template>
    <div>
        <v-layout xs12>
            <v-banner :value="{title:namepage,breadcrumbs}"></v-banner>
        </v-layout>
        <v-container class="px-0">
            <v-card flat>
                <v-container fluid style="min-height:0;" grid-list-lg class="px-0 ">
                    <v-card flat color="white darken-2" class="white--text px-0">
                        <v-container fluid grid-list-lg>
                            <v-layout row wrap>
                                <v-flex xs12 md6>
                                    <h1 class=" headline grey--text text--darken-3 text-xs-center">Vui lòng xác nhận lại thông tin của bạn</h1>
                                    <v-form v-model="valid" class="mt-5">
                                        <v-text-field label="Họ và tên" class="mb-4" v-model="name" :rules="nameRules" :counter="10" required solo></v-text-field>
                                        <v-text-field label=" Địa chỉ E-mail" class="mb-4" v-model="email" :rules="emailRules" required solo></v-text-field>
                                        <v-text-field label="Số Điện Thoại" class="mb-4" mask="phone" v-model="phone" :rules="phoneRules" required solo></v-text-field>
                                        <v-text-field label="Địa Chỉ" v-model="address" :rules="addressRules" required solo></v-text-field>
                                    </v-form>
                                </v-flex>
                                <v-flex xs12 md6>
                                    <h1 class="headline grey--text text--darken-3 text-xs-center "> Đơn hàng của bạn
                                    </h1>
                                    <v-data-table :headers="headers" :items="$store.state.cart" hide-actions class="elevation-1 mt-5" flat>
                                        <template slot="items" slot-scope="props">
                                            <td>
                                                <strong>{{ props.item.book.name }} x{{props.item.quantity}}</strong>
                                            </td>
                                            <td class="text-xs-right">{{ props.item.book.price * props.item.quantity}} </td>
                                        </template>

                                    </v-data-table>
                                    <template>
                                        <v-layout row wrap>
                                            <v-flex sx12 md10 class="green--text text--accent-4 subheading ">Thanh toán:</v-flex>
                                            <v-flex sx12 md2 class="grey--text text--darken-1 title px-0">
                                                {{total}}
                                            </v-flex>
                                        </v-layout>
                                    </template>
                                    <div class="mt-3 text-xs-center">
                                        <v-btn class="green accent-4" color="white--text"> Đặt hàng</v-btn>
                                    </div>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card>
                </v-container>
            </v-card>
        </v-container>
    </div>
</template>
<script>
export default {
  data: () => ({
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
    nameRules: [
      v => !!v || "Name is required",
      v => v.length <= 10 || "Name must be less than 10 characters"
    ],
    email: "",
    emailRules: [
      v => !!v || "E-mail is required",
      v =>
        /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
        "E-mail must be valid"
    ],
    phone: "",
    phoneRules: [
      v => !!v || "Phone is required",
      v =>
        /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
        "Phone must be valid"
    ],
    address: "",
    addressRules: [
      v => !!v || "Address is required",
      v =>
        /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
        "Address must be valid"
    ],
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