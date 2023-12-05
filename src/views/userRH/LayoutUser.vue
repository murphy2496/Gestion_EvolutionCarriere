<template>
  <sousHeader />
  <div class="container">
    <router-view />
  </div>
</template>

<script>
import sousHeader from "@/components/sousHeader.vue";
export default{
   name:'LayoutUser',
   components:{
      sousHeader
   }
};
</script>