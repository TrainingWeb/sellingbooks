<template>
    <v-layout xs12>
        <div class="banner" :style="[{'background-image':'url(storage/images/banner.jpg)'}]" style="background-size:cover">
            <v-container fluid fill-height>
                <v-layout justify-center align-center>
                    <v-flex text-xs-center>
                        <h1 class="white--text display-2">{{value.title}}</h1>
                        <v-breadcrumbs divider="/" justify-center>
                            <v-breadcrumbs-item class="color-text" v-for="( breadcrumb,index ) in value.breadcrumbs" :key="'key-$'+index " :disabled="breadcrumb.disabled">
                                <router-link :to="breadcrumb.url" class="url-br">{{ breadcrumb.name }}</router-link>
                            </v-breadcrumbs-item>
                        </v-breadcrumbs>
                    </v-flex>
                </v-layout>
            </v-container>
        </div>
    </v-layout>
</template>

<script>
export default {
  props: ["value"]
};
</script>

<style>
.banner {
  min-height: 350px;
  width: 100%;
}
.url-br {
  text-decoration: none !important;
}
.color-text a {
  color: white !important;
}
</style>
