<script setup>
import Button from '@/components/Button.vue'
</script>

<template>
  <section class="heroSection">
    <div class="heroContainer">
      <div class="heroTextContainer">
        <h1>Rekruttering er vores håndværk – tekniske profiler er vores speciale</h1>
        <Button
          label="Aktuelle stillinger"
          type="default"
          aria-label="Aktuelle stillinger"
        />
      </div>
    </div>
  </section>
</template>

<style scoped lang="scss">
.heroSection {
  width: 100%;
  height: 750px;
  background-image: url('@/assets/img/HeroSectionPageOne.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;

  display: flex;
  align-items: center; /* centrerer vertikalt */
}

/* Dette matcher din headerContainer */
.heroContainer {
  max-width: 1450px;
  width: 100%;
  margin: 0 auto; /* centrerer indholdet som resten af sitet */
  display: flex;
  justify-content: center; /* centrer tekst i midten */
  align-items: center;
  text-align: center;
}

.heroTextContainer {

  h1 {
    @include heading1;
    margin-bottom: 24px;
    color: $whiteColor;
  }

  button {
    display: block;
    margin: 0 auto; /* centrer knappen under teksten */
  }
}
</style>
