<script setup>
import Button from '@/components/Button.vue'
import Hero from '@/components/Hero.vue'
</script>

<template>
  <div class="heroWrapper">
    <Hero type="hero2" />
    <div class="heroContainer">
      <div class="heroTextContainer">
        <h1>Procesoperatør - Pilotanlæg</h1>
        <Button label="Søg Job" type="default" aria-label="Søg Job" />
      </div>
    </div>
          <p class="heroBottomText">
        KD / 1034 | Storkøbenhavn | Lihme Protein Solutions ApS
      </p>
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

.heroBottomText {
  position: absolute;
  bottom: 20px;
  left: 50%;
  transform: translateX(-50%);
  @include bodyText;
  color: $whiteColor;
}
</style>
