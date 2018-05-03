<template>
    <v-card flat>
        <v-container fluid grid-list-lg>
            <v-layout row wrap>
                <v-flex xs5 class="pr-0 py-0">
                    <v-card-media :src="book.img" height="200px" max-width="130px"></v-card-media>
                </v-flex>
                <v-flex xs7 class="grey lighten-5 pl-3">
                    <div>
                        <h3>{{book.name}}</h3>
                        <p class="grey--text text--darken-1 mt-1">Tác giả: {{book.author}}</p>
                        <span class="green--text text--accent-4 title"> {{book.price}}</span>
                        <span class="grey--text text--darken-1 title ml-3">
                            <del>{{book.sale}}</del>
                        </span>
                        <v-divider class="mt-2"></v-divider>
                        <div class="mt-4">
                            <v-icon class="mr-5" color="red">add_shopping_cart</v-icon>
                            <v-icon class="ml-5">favorite</v-icon>
                        </div>
                    </div>
                </v-flex>
            </v-layout>
        </v-container>
    </v-card>
</template>

<script>
export default {
  props: ["book"]
};
</script>

<style>

</style>
