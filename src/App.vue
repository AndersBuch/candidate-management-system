<script setup>
import HelloWorld from './components/HelloWorld.vue'
import BasicIcon from '@/components/atoms/BasicIcon.vue'
</script>

<template>
  <div>
    <h1>monkjey</h1>
    <HelloWorld />
    <h1>monkjey</h1>
    <RouterView />

    <img src="/icons/Filter.svg" alt="Filter icon" />

    <div class="avatarContainer">
      <BasicIcon name="Filter" />
      <BasicIcon name="Filter" />
    </div>
  </div>
</template>


<style scoped lang="scss">
h1 {
  color: $primaryBlue;
}

</style>
