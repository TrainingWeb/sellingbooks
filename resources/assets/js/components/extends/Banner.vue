<template>

  <v-parallax src="storage/images/banner.jpg" height="250" class="bgBnaner">
    <v-layout column align-center justify-center>
      <h1 class="white--text display-2">{{value.title}}</h1>
      <v-breadcrumbs divider="/" justify-center>
        <v-breadcrumbs-item class="color-text" v-for="( breadcrumb,index ) in value.breadcrumbs" :key="'key-$'+index " :disabled="breadcrumb.disabled">
          <router-link :to="breadcrumb.url" class="url-br">{{ breadcrumb.name }}</router-link>
        </v-breadcrumbs-item>
      </v-breadcrumbs>
    </v-layout>
  </v-parallax>

</template>

<script>
export default {
  props: ["value"]
};
</script>

<style>
.bgBnaner {
  width: 100%;
}
.url-br {
  text-decoration: none !important;
}
.color-text a {
  color: white !important;
}
</style>
