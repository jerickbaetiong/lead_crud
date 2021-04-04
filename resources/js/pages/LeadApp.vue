<template>
  <div>
      <v-container fluid class="w-100 m-0 p-0 ">
        <router-view></router-view>
      </v-container>
  </div>
</template>

<script>
export default {
    mounted() {
        console.log("Application mounted.");
    },

};
</script>

