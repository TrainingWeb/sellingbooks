<template>
  <v-container fill-height fluid>
    <v-layout align-center justify-center>
      <v-card width="300px" height="350px">
        <v-list class="green accent-4 white--text text-xs-center">
          <span class="">ĐẶT LẠI MẬT KHẨU</span>
        </v-list>
        <v-divider></v-divider>
        <v-container>
            <v-form ref="form" v-model="valid" lazy-validation @submit.prevent="submit">
              <v-flex xs12>
                <v-text-field :rules="emailRules" :value="$route.query.email" disabled label="E-mail" required></v-text-field>
              </v-flex>
              <v-flex xs12>
                <v-text-field v-model="password" required label="Mật khẩu" :rules="passRules" :append-icon="e2 ? 'visibility' : 'visibility_off'" :append-icon-cb="() => (e2 = !e2)" :type="e2 ? 'password' : 'text'"></v-text-field>
              </v-flex>
              <v-flex xs12>
                <v-text-field v-model="confirm_password" required label="Nhập lại mật khẩu" :rules="comparePasswords" :append-icon="e2 ? 'visibility' : 'visibility_off'" :append-icon-cb="() => (e2 = !e2)" :type="e2 ? 'password' : 'text'"></v-text-field>
              </v-flex>
              <v-card-actions class="ml-5">
                <v-btn @click="clear">Đóng</v-btn>
                <v-btn class="ml-4 green accent-4 white--text" @click="submit">Gửi</v-btn>
              </v-card-actions>
            </v-form>
        </v-container>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-dialog v-model="dialogResetPassword" persistent max-width="290">
            <v-card>
              <v-card-title class="headline ml-1 ">Thông báo !</v-card-title>
               <v-card-text v-if="status==false">Mã của bạn đã hết hạn hoặc không đúng</v-card-text>
              <v-card-text v-else>Bạn đã thay đổi mật khẩu thành công
              </v-card-text>
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
      v => v.length >= 6 || "Nhập ít nhất 6 ký tự"
    ],
    email: "",
    password: "",
    confirm_password: "",
    e2: false,
    status:true,
  }),
  computed: {
    comparePasswords() {
      return this.password !== this.confirm_password
        ? [`Mật khẩu chưa khớp`]
        : [];
    }
  },
  methods: {
    // forgotPassword() {
    //   this.dialogForgotPassword = true;
    // },
    submit() {
      if (this.$refs.form.validate()) {
        window.axios
          .post("/reset/password/" + this.$route.query.token, {
            email: this.$route.query.email,
            password: this.password,
            confirm_password: this.confirm_password
          })
          .then(response => {
            console.log(
              response.data.status)
              this.status=response.data.status
            this.dialogResetPassword = true;
            this.data = response.data;
            this.$store.dispatch("setToken", this.data.api_token);
            this.$store.dispatch("setUser", this.data.user);
          })
          .catch(function(error) {
            console.log(error);
          });
      }
    },
    clear() {
      this.$refs.form.reset();
    }
  }
};
</script>
<style>
</style>
