<script setup>
import HelloWorld from './components/HelloWorld.vue'
</script>

<template>
  <div>
<h1>monkjey</h1>
    <HelloWorld />
    <h1>monkjey</h1>
    <RouterView />
  </div>
</template>


<style scoped lang="scss">
h1 {
  color: $primaryBlue;
}

</style>
