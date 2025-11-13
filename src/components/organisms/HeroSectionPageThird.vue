<script setup>
import Hero from '@/components/atoms/Hero.vue'
</script>

<template>
  <div class="heroWrapper">
    <Hero type="hero3" />
    <div class="heroContainer">
        <h1>Lihme Protein Solutions ApS</h1>
    </div>
  </div>
</template>

<style scoped lang="scss">
.heroWrapper {
  position: relative; 
}

.heroContainer {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);

  h1 {
    @include heading1;
    margin-bottom: 24px;
    color: $whiteColor;
  }
}

</style>
