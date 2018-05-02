<template>
    <v-footer height="auto">
        <v-card flat tile class="flex">
            <v-container>
                <v-card-text class="white">
                    <v-layout>
                        <v-flex xs3 layout column class="">
                            <img class="mb-2" src="storage/images/logo_green.png">
                            <div class="mb-3 info-ft ">
                                <v-icon size="18px" class="mr-1">fas fa-home</v-icon>
                                179 Võ Duy Ninh,TP ĐN
                            </div>
                            <div class="mb-3">
                                <v-icon size="18px" class="mr-1  ">fas fa-envelope</v-icon>
                                bookstore@gmail.com
                            </div>
                            <div class="mb-3">
                                <v-icon size="18px" class="mr-1">fas fa-phone</v-icon>
                                + 01 234 567 88
                            </div>
                            <div class="mb-3">
                                <v-icon size="18px" class="mr-1 ">fas fa-print</v-icon>
                                + 01 234 567 89
                            </div>
                        </v-flex>
                        <v-flex v-for="(col, i) in rows" :key="i" xs3>
                            <div class="body-2 title-ft my-3" v-text="col.title.toUpperCase()"></div>
                            <div class="my-3 info-ft" v-for="(child, i) in col.children" :key="i" v-text="child"></div>
                        </v-flex>
                        <v-flex xs3 layout column class="">
                            <div class="body-2 my-3">TÀI KHOẢN CỦA TÔI</div>
                            <div class="mb-3 info-ft">Chi tiết tài khoản</div>
                            <div class="mb-3">Lịch sử mua hàng</div>
                            <v-card-title class="my-0">
                                <v-btn v-for=" icon in icons " :key="icon " icon dark class="mx-1 icon ">
                                    <v-icon size="24px ">{{ icon }}</v-icon>
                                </v-btn>
                            </v-card-title>
                        </v-flex>
                    </v-layout>
                </v-card-text>
            </v-container>
            <v-card-actions class="grey lighten-4 justify-center ">
                &copy;2018 — Demo Vue Js
            </v-card-actions>
        </v-card>
    </v-footer>
</template>
<script>
export default {
  data: () => ({
    icons: [
      "fab fa-facebook",
      "fab fa-twitter",
      "fab fa-google-plus",
      "fab fa-linkedin",
      "fab fa-instagram"
    ],
    rows: [
      {
        title: "DỊCH VỤ",
        children: [
          "Điều khoản và sử dụng",
          "Thông tin giao hàng",
          "Chính sách bảo mật",
          "Dịch vụ khách hàng"
        ]
      },
      {
        title: "HỖ TRỢ",
        children: [
          "Chính sách đổi - trả - hoàn tiền",
          "Chính sách khách sỉ",
          "Phương thức vận chuyển",
          "Phương thức thanh toán"
        ]
      }
      // {
      //   title: "Company Name",
      //   children: ["Chi tiết tài khoản", "Lịch sử mua hàng"]
      // }
    ]
  })
};
</script>
<style>
.title-ft {
  color: #4c4848;
}
.info-ft {
  color: #666464;
}
.icon {
  color: #27ae61;
}
.card__title {
  padding: 0px;
  margin-left: -10px;
}
.application .theme--dark.btn,
.theme--dark .btn {
  color: #27ae61;
}
.application.theme--light {
  background: #fff;
}
img {
  width: 63%;
}
</style>

