<script setup>
import Header from '@/views/Header.vue'
import BasicIcon from '@/components/atoms/BasicIcon.vue'
</script>

<template>
  <Header />
  <main>
  <div>
    <h1>monkjey</h1>
    
    <h1>monkjey</h1>
    <RouterView />

    <img src="/icons/Filter.svg" alt="Filter icon" />

    <div class="avatarContainer">
      <BasicIcon name="LinkinIcon" />
      <BasicIcon name="Filter" />
    </div>
  </div>
  </main>
</template>


<style scoped lang="scss">
h1 {
  color: $primaryBlue;
  @include heading1;
}

</style>
