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
                                    <v-carousel style="height:350px">
                                        <v-carousel-item v-for="(item,i) in slideDetail" :src="item.src" :key="i"></v-carousel-item>
                                    </v-carousel>
                                </v-flex>
                                <v-flex xs12 md6 class="pl-5">
                                    <div>
                                        <div class="headline grey--text text--darken-3 mb-4">GIỚI THIỆU</div>
                                        <div class="grey--text text--accent-4 body-2">Với mong muốn cung cấp kiến thức cho mọi người từ nguồn tri thức của nhân loại, góp phần gìn giữ văn hoá đọc, Công ty cổ phẩn Sách và Thiết bị giáo dục Trí Tuệ đã và đang xây dựng hình ảnh của mình trở thành thương hiệu có uy tín trên thị trường. Công ty cổ phẩn Sách và Thiết bị giáo dục Trí Tuệ đã và đang xây dựng hình ảnh của mình trở thành thương hiệu có uy tín trên thị trường.Ngoài hoạt động chính là tiệm sách lớn, nhà sách Khai Trí còn sưu tầm nhiều sách báo ngoại ngữ cũng như các bản thảo bằng tiếng Việt. </div>

                                    </div>
                                    <div class="grey lighten-4 grey--text text--accent-4 mt-5 py-2 px-2">
                                        Nhiệm vụ của chúng tôi là cung cấp các loại sách chất lượng cao nhất với giá cả phải chăng, với giao hàng nhanh và dịch vụ khách hàng tuyệt vời. Nhiệm vụ của chúng tôi là cung cấp các loại sách chất lượng cao nhất với giá cả phải chăng, với giao hàng nhanh và dịch vụ khách hàng tuyệt vời.
                                    </div>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card>
                </v-container>
            </v-card>
        </v-container>
        <div>
            <v-container fluid style="min-height: 0;" grid-list-lg px-0>
                <v-layout row wrap>
                    <v-flex>
                        <v-card flat color="grey lighten-4" class="white--text">
                            <v-container grid-list-lg>
                                <v-layout row wrap>
                                    <v-flex xs12 md5 mt-2>
                                        <div>
                                            <div class="title grey--text text--darken-3">TÁC GIẢ NỖI TIẾNG</div>
                                            <div class="grey--text text--darken-2 py-3">Với mong muốn cung cấp kiến thức cho mọi người từ nguồn tri thức của nhân loại, góp phần gìn giữ văn hoá đọc, Công ty cổ phẩn Sách và Thiết bị giáo dục Trí Tuệ đã và đang xây dựng hình ảnh của mình trở thành thương hiệu có uy tín trên thị trường. Thiết bị giáo dục Trí Tuệ đã và đang xây dựng hình ảnh của mình trở thành thương hiệu có uy tín trên thị trường,xây dựng hình ảnh của mình trở thành thương hiệu có uy tín trên thị trường</div>
                                            <v-btn class="mx-0 green accent-4 white--text"> Tham gia với chúng tôi</v-btn>
                                        </div>
                                    </v-flex>
                                    <v-flex xs12 md7>
                                        <v-layout row wrap>
                                            <v-flex xs12 md4>
                                                <v-card>
                                                    <v-card-media src="storage/images/team-1.jpg" height="220px">
                                                    </v-card-media>
                                                    <v-card-title class="py-2 subheading" style="margin-left:0px">
                                                        <v-spacer></v-spacer>
                                                        Jone Doe
                                                        <v-spacer></v-spacer>
                                                    </v-card-title>
                                                </v-card>
                                            </v-flex>
                                            <v-flex xs12 md4>
                                                <v-card>
                                                    <v-card-media src="storage/images/team-3.jpg" height="220px">
                                                    </v-card-media>
                                                    <v-card-title class="py-2 subheading" style="margin-left:0px">
                                                        <v-spacer></v-spacer>
                                                        Jone Doe
                                                        <v-spacer></v-spacer>
                                                    </v-card-title>
                                                </v-card>
                                            </v-flex>
                                            <v-flex xs12 md4>
                                                <v-card>
                                                    <v-card-media src="storage/images/team-4.jpg" height="220px">
                                                    </v-card-media>
                                                    <v-card-title class="py-2 subheading" style="margin-left:0px">
                                                        <v-spacer></v-spacer>
                                                        Jone Doe
                                                        <v-spacer></v-spacer>
                                                    </v-card-title>
                                                </v-card>
                                            </v-flex>

                                        </v-layout>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-container>
        </div>
    </div>
</template>
<script>
export default {
  data() {
    return {
      slideDetail: [
        {
          src: "storage/images/about-1.jpg"
        },
        {
          src: "storage/images/about-2.jpg"
        },
        {
          src: "storage/images/about-3.jpg"
        }
      ],
      breadcrumbs: [
        {
          name: "Trang Chủ",
          url: "/",
          disabled: false
        },
        {
          name: "Giới Thiệu",
          url: "/about",
          disabled: true
        }
      ],
      namepage: "Giới Thiệu"
    };
  }
};
</script>
<style>
.carousel__controls {
  background-color: transparent !important;
}
</style>
