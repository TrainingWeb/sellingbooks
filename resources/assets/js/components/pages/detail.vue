<template>
    <div>
        <v-layout xs12>
            <v-banner :value="{title:namepage,breadcrumbs}"></v-banner>
        </v-layout>
        <v-container>
            <v-layout row wrap>
                <v-flex xs12>
                    <v-card flat>
                        <v-container fluid style="min-height: 0;" grid-list-lg>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-card color="cyan darken-2" class="white--text">
                                        <v-container fluid grid-list-lg>
                                            <v-layout row v-for="(item,index) in bookDetail" :key="`BookDetail-${index}`">
                                                <v-flex xs4>
                                                    <v-card-media :src="bookDetail.img" height="450px" contain></v-card-media>
                                                </v-flex>
                                                <v-flex xs8>
                                                    <div>
                                                        <div class="headline grey--text text--darken-3">{{bookDetail.name}}</div>
                                                        <div class="grey--text accent-4 body-2">
                                                            <span>Tác giả: </span>{{bookDetail.author}}</div>
                                                        <div class="green--text text--accent-4 title mt-3"> {{bookDetail.price}}</div>
                                                        <v-divider class="my-3"></v-divider>
                                                        <div>
                                                            <span class=" grey--text text--accent-4body-1">{{bookDetail.detail}}</span>
                                                            <a>
                                                                <span class="green--text text--accent-4">Xem thêm</span>
                                                            </a>
                                                        </div>
                                                        <v-divider class="my-3"></v-divider>
                                                        <div class="mx-0">
                                                            <v-btn color="green accent-4 white--text" @click="addCart">
                                                                <i class="material-icons add-shopping mr-2 white--text">add_shopping_cart</i>Thêm</v-btn>
                                                            <v-btn color="green accent-4 " @click="favorite">
                                                                <i class="material-icons favorite white--text">favorite</i>
                                                            </v-btn>
                                                        </div>
                                                        <v-layout row wrap class="mt-3">
                                                            <div class="text-xs-center">
                                                                <span class="green--text ml-2">Thể loại:</span>
                                                                <v-chip color="grey--text text--darken-1" text-color="white" class="px-0">Sách Giáo Khoa</v-chip>
                                                                <v-chip color="grey--text text--darken-1" text-color="white">Sách Văn Học</v-chip>
                                                            </div>
                                                        </v-layout>
                                                    </div>
                                                </v-flex>
                                            </v-layout>
                                        </v-container>
                                    </v-card>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card>
                    <v-tabs icons-and-text dark color="white" height="40px">
                        <v-tabs-slider color="yellow"></v-tabs-slider>
                        <v-tab class="green accent-4" href="#tab-1">
                            Chi tiết sản phẩm
                        </v-tab>
                        <v-tab class="green accent-4" href="#tab-2">
                            Nhận xét khách hàng
                        </v-tab>
                        <v-tabs-items>
                            <v-tab-item id="tab-1">
                                <v-card-text class="roboto">{{ textDetail }}</v-card-text>
                            </v-tab-item>
                            <v-tab-item id="tab-2">
                                <v-card>
                                    <v-list three-line>
                                        <template v-for="item in comments">
                                            <v-list-tile avatar :key="item.title">
                                                <v-list-tile-avatar>
                                                    <img :src="item.avatar">
                                                </v-list-tile-avatar>
                                                <v-list-tile-content>
                                                    <v-list-tile-title v-html="item.title"></v-list-tile-title>
                                                    <v-list-tile-sub-title class="subtitleComment" v-html="item.subtitle"></v-list-tile-sub-title>
                                                </v-list-tile-content>
                                                <v-list-tile-action>
                                                    <v-list-tile-action-text>{{ item.time }}</v-list-tile-action-text>
                                                    <!-- <v-icon color="grey lighten-1">star_border</v-icon> -->
                                                </v-list-tile-action>
                                            </v-list-tile>
                                        </template>
                                    </v-list>
                                </v-card>
                            </v-tab-item>
                        </v-tabs-items>
                    </v-tabs>
                    <v-container grid-list-xs class="mt-5">
                        <div class="headline grey--text text--darken-3">Những sản phẩm liên quan</div>
                        <v-layout row wrap>
                            <v-flex xs12 md6 lg4 v-for="(item,index) in book" :key="`khoa${index}`">
                                <book-item :book="item"></book-item>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-flex>
            </v-layout>
        </v-container>
    </div>
</template>
<script>
export default {
  data() {
    return {
      bookDetail: [
        {
          img:
            "http://vietart.co/blog/wp-content/uploads/2014/01/9_thiet_ke_bia_sach_dep_20.jpg",
          name: "Cô gái mở đường",
          price: 12000,
          detail:
            "Ngày cùng sư phụ thành thân, ta hạnh phúc biết bao,mong chờ nhường nào, rằng hai chúng ta sẽ sống những ngày vô lo vô nghĩ, mãi mãi bên nhau đến khi bạc đầu”[...]",
          sale: 1500,
          author: "Nguyễn Du"
        }
      ],
      textDetail:
        "Một cô gái thôn quê tỉnh dậy trong cơ thể một cậu trai thành phố và ngược lại, cậu trai ấy cũng tỉnh dậy trong cuộc sống đời thường của cô gái. Hai con người khác nhau, sống ở hai địa điểm khác nhau ở nước Nhật – một cổ xưa, một hiện đại. Vậy bí mật nào đã đưa họ tới với nhau?Your Name là một cuốn sách chứa đựng nhiều cung bậc cảm xúc. Đoạn mở đầu với sự hài hước, đoạn giữa tiếp nối bằng hồi hộp – bí ẩn và đoạn cuối thì giống như một cú bùng nổ cực đại, hệt như ngôi sao chổi đâm sầm vào Trái đất và làm nổ tung mọi giác quan của người xem. Mọi diễn biến truyện diễn ra dồn dập, với tiết tấu nhanh chậm đan xen nhịp nhàng. Khiến cho người đọc không thể rời mắt, từ háo hức ở giây đầu tiên cho tới sự nuối tiếc ở những giây cuối cùng.Ngày cùng sư phụ thành thân, ta hạnh phúc biết bao, mong chờ nhường nào, rằng hai chúng ta sẽ sống những ngày vô lo vô nghĩ, mãi mãi bên nhau đến khi bạc đầu” chúng ta  Thế nhưng, sư phụ chàng cái gì cũng tốt, điều không tốt duy nhất chính là không yêu ta. Cho đến tận ngày ta vì khó sinh mà bước chân vào cửa tử, chàng vẫn lãnh đạm tựa băng tuyết nghìn năm, thậm chí còn chẳng ghé mắt mà nhìn ta lần cuối. Ta chẳng thể bấu víu vào đâu, đành ôm nỗi vấn vương trần thế cùng mối hận thác xuống cửu tuyền.Duy trì đáng kể các giải pháp nhấp chuột và vữa mà không có giải pháp chức năng.Hoàn toàn hợp tác hóa các mối quan hệ thuế tài nguyên thông qua các thị trường thích hợp hàng đầu. Chuyên nghiệp trau dồi dịch vụ khách hàng một đối một với những ý tưởng mạnh mẽ.",
      comments: [
        {
          avatar: "./img/author.jpg",
          title: "Võ Đăng Ánh",
          subtitle:
            "<span class='text--primary'>Tôi rất hài lòng về cách phục vụ khách hàng của nhân viên ở đây</span>",
          time: "02:56 PM"
        },
        {
          avatar: "./img/author.jpg",
          title: "Tô Thị Tuyết Nga",
          subtitle:
            "<span class='text--primary'>Sẽ ghé shop nhiều lần sau nữa</span>",
          time: "03:15 PM"
        },
        {
          avatar: "./img/user.jpg",
          title: "Nguyễn Thị Thu Thủy",
          subtitle: "<span class='text--primary'>View của shop rất đẹp</span>",
          time: "08:32 PM"
        }

        // { divider: true, inset: true }
      ],
      book: [
        {
          img:
            "http://vietart.co/blog/wp-content/uploads/2014/01/9_thiet_ke_bia_sach_dep_20.jpg",
          name: "Cô gái mở đường",
          price: "120.000",
          sale: "",
          author: "Nguyễn Du"
        },
        {
          img:
            "https://thegioidohoa.com/wp-content/uploads/2017/08/tong-hop-20-mau-bia-sach-doc-dao-nhat-nam-2017-7.jpg",
          name: "Dế mèn phiêu lưu kí",
          price: "120.000",
          sale: "",
          author: "Nguyễn Du"
        },
        {
          img:
            "http://lehai.com.vn/uploads/news/Thi%E1%BA%BFt%20k%E1%BA%BF%20b%C3%ACa%20s%C3%A1ch/bia-sach-1.jpg",
          name: "Truyện kiều",
          price: "120.000",
          sale: "",
          author: "Nguyễn Du"
        }
      ],

      breadcrumbs: [
        {
          name: "Trang Chủ",
          url: "/",
          disabled: false
        },
        {
          name: "Chi tiết sản phẩm",
          url: "/list-products",
          disabled: true
        }
      ],
      namepage: "Chi tiết sản phẩm"
    };
  },
  methods: {
    addCard() {}
  }
};
</script>
<style>
.cyan.darken-2,
.cyan.darken-2--after:after {
  background-color: #fff !important;
}
.cyan {
  background-color: #fff !important;
  border-color: #fff !important;
}
.card {
  box-shadow: none;
}
.application.theme--light {
  background: #fff;
}
.application.theme--light .text--primary {
  color: #757575 !important;
}
</style>

