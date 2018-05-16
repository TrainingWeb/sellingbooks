<template>
  <v-container fill-height fluid>
    <v-layout align-center justify-center>
      <v-card width="300px" height="250px">
        <v-list class="green accent-4 white--text text-xs-center">
          <span class="">QUÊN MẬT KHẨU</span>
        </v-list>
        <v-divider></v-divider>
        <v-container>
          <template>
            <v-form ref="form" v-model="valid" lazy-validation>
              <v-text-field class="mt-3" v-model="email" :rules="emailRules" label="E-mail" @keyup.enter="forgotPassword" required></v-text-field>
              <div class="text-xs-right mt-5">
                <v-btn flat to="/">TRANG CHỦ</v-btn>
                <v-btn :disabled="!valid" @click="forgotPassword">
                  Gửi
                </v-btn>
              </div>
            </v-form>
          </template>
        </v-container>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-dialog v-model="dialogForgotPassword" persistent max-width="290">
            <v-card>
              <v-card-title class="headline ml-1">Thông báo !</v-card-title>
              <v-card-text>Xin hãy kiểm tra E-mail của bạn để thay đổi mật khẩu</v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="green darken-1" flat @click.native="dialogForgotPassword = false" to="/">Đóng</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-card-actions>
      </v-card>
    </v-layout>
  </v-container>
</template>
<script>
import axios from "axios";
export default {
  data: () => ({
    dialogForgotPassword: false,
    valid: true,
    email: "",
    emailRules: [
      v => !!v || "E-mail là bắt buộc",
      v =>
        /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
        "E-mail phải hợp lệ"
    ]
  }),
  methods: {
    forgotPassword() {
      window.axios
        .post("/sendmail", {
          email: this.email
        })
        .then(response => {
          this.dialogForgotPassword = true;
          // window.location = "#/";
          console.log("thanhcong");
          this.email = "";
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    submit() {
      if (this.$refs.form.validate()) {
        axios.post("/api/submit", {
          email: this.email
        });
      }
    },
    clear() {
      this.$refs.form.reset();
    }
  },
  mounted() {}
};
</script>
<style>
</style>
