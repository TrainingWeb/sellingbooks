<template>
  <v-container fill-height fluid>
    <v-layout align-center justify-center>
      <v-card width="300px" height="350px">
        <v-list class="green accent-4 white--text text-xs-center">
          <span class="">ĐẶT LẠI MẬT KHẨU</span>
        </v-list>
        <v-divider></v-divider>
        <v-container>
          <template>
            <v-form ref="form" v-model="valid" lazy-validation>
              <v-flex xs12>
                <v-text-field v-model="email" :rules="emailRules" label="E-mail" required></v-text-field>
              </v-flex>
              <v-flex xs12>
                <v-text-field v-model="password" required label="Mật khẩu" :rules="passRules" :append-icon="e2 ? 'visibility' : 'visibility_off'" :append-icon-cb="() => (e2 = !e2)" :type="e2 ? 'password' : 'text'"></v-text-field>
              </v-flex>
              <v-flex xs12>
                <v-text-field v-model="resetpassword" required label="Nhập lại mật khẩu" :rules="passRules" :append-icon="e2 ? 'visibility' : 'visibility_off'" :append-icon-cb="() => (e2 = !e2)" :type="e2 ? 'password' : 'text'"></v-text-field>
              </v-flex>
              <v-btn @click="clear">Đóng</v-btn>
              <v-btn :disabled="!valid" @click="submit">Gửi</v-btn>
            </v-form>
          </template>
        </v-container>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-dialog v-model="dialogResetPassword" persistent max-width="290">
            <v-card>
              <v-card-title class="headline ml-1">Thông báo !</v-card-title>
              <v-card-text>Bạn đã thay đổi mật khẩu thành công</v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="green darken-1" flat @click.native="dialogResetPassword = false" to="/">Đóng</v-btn>
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
    dialogResetPassword: false,
    valid: true,

    emailRules: [
      v => !!v || "E-mail là bắt buộc",
      v =>
        /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
        "E-mail phải hợp lệ"
    ],
    passRules: [
      v => !!v || "Mật khẩu là bắt buộc",
      v => v.length >= 8 || "Nhập ít nhất 8 ký tự"
    ],
    email: "",
    password: "",
    resetpassword: "",
    e2: false
  }),
  methods: {
    // forgotPassword() {
    //   this.dialogForgotPassword = true;
    // },
    submit() {
      if (this.$refs.form.validate()) {
        axios.post("/api/submit", {
          email: this.email,
          password: this.password,
          resetpassword: this.resetpassword
        });
      }
      window.axios
        .get("reset/password")
        .then(response => {
          this.dialogResetPassword = true;
          console.log("Reset Password");
        })
        .catch(function(error) {
          console.log(error);
        });
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
