<template>
    <v-app id="inspire">
        <v-layout row wrap>
            <v-flex xs12 class="grey lighten-5">
                <v-container class="pa-0">
                    <v-toolbar dense flat class="transparent px-0">
                        <v-toolbar-title class="hidden-sm-and-down">
                            <v-icon small class="green--text text--accent-4">place</v-icon>
                            <span class="caption grey--text  text--darken-1 pr-3">
                                k48/Võ Duy Ninh, Sơn Trà
                            </span>
                            <v-icon small class="green--text text--accent-4">smartphone</v-icon>
                            <span class="caption grey--text  text--darken-1">
                                0123 456 789
                            </span>
                        </v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-toolbar-items>
                            <v-btn flat class="caption grey--text  text--darken-1">Đăng Nhập</v-btn>
                            <v-btn flat class="caption grey--text  text--darken-1">Đăng Ký</v-btn>
                        </v-toolbar-items>
                    </v-toolbar>
                </v-container>
            </v-flex>
            <v-flex xs12 white>
                <v-container class="pa-0">
                    <v-toolbar flat class="transparent px-0">
                        <v-toolbar-title>LoGo</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-toolbar-items>
                            <v-btn flat class="caption grey--text  text--darken-1 p-0">
                                <v-badge color="red lighten-1" class="p-0">
                                    <span slot="badge" class="caption">6</span>
                                    <v-icon color="grey">add_shopping_cart</v-icon>
                                </v-badge>
                            </v-btn>
                            <v-btn flat class="caption grey--text  text--darken-1 p-0">
                                <v-badge color="indigo" class="p-0">
                                    <span slot="badge" class="caption">0</span>
                                    <v-icon color="grey">favorite</v-icon>
                                </v-badge>
                            </v-btn>
                        </v-toolbar-items>
                    </v-toolbar>
                </v-container>
            </v-flex>
            <v-flex xs12 green accent-4>
                <v-container class="pa-0">
                    <v-toolbar flat class="green accent-4">
                        <v-toolbar-title>
                            <v-btn flat class="white--text ma-0">Trang chủ</v-btn>
                            <v-btn flat class="white--text">Thể loại</v-btn>
                            <v-btn flat class="white--text">Tác giả</v-btn>
                        </v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-toolbar-items class="hidden-sm-and-down">
                            <v-text-field label="Tìm kiếm" :append-icon="'search'" dark></v-text-field>
                        </v-toolbar-items>
                    </v-toolbar>
                </v-container>

            </v-flex>
        </v-layout>
        <!--  -->
        <!--  -->

        <v-content class="white">
            <router-view></router-view>
        </v-content>
    </v-app>
</template>

<script>
export default {
  data: () => ({
    drawer: null
  }),
  props: {
    source: String
  }
};
</script>
<style>
.toolbar-w {
  max-width: 900px;
}
</style>
