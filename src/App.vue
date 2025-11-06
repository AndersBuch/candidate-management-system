<script setup>
import HelloWorld from './components/HelloWorld.vue'
</script>

<template>
  <body>
    

  <div>
<h1>monkjey</h1>
    <HelloWorld />
    <h1>monkjey</h1>
    <RouterView />
  </div>
  </body>
</template>


<style scoped lang="scss">
h1 {
  color: $primaryBlue;
}

</style>
