<script setup>
import Button from '@/components/atoms/Button.vue'
import Hero from '@/components/atoms/Hero.vue'
</script>

<template>
  <div class="heroWrapper">
    <Hero type="hero1" />
    <div class="heroContainer">
      <div class="heroTextContainer">
        <h1>Rekruttering er vores håndværk – tekniske profiler er vores speciale</h1>
        <Button label="Aktuelle stillinger" type="default" aria-label="Aktuelle stillinger" />
        
      </div>
    </div>
  </div>
</template>

<style scoped lang="scss">
.heroWrapper {
  position: relative; /* gør det muligt at placere tekst ovenpå hero */
}

.heroContainer {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
  color: $whiteColor;
  width: 100%;
  max-width: 1450px;
  margin: 0 auto;

  h1 {
    @include heading1;
    margin-bottom: 24px;
    color: $whiteColor;
  }

  button {
    display: block;
    margin: 0 auto;
  }
}
</style>
