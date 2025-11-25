<script setup>
import { computed } from 'vue'

const props = defineProps({
  type: String
})

// Vælg billede ud fra typen
const filePath = computed(() => {
  const base = import.meta.env.BASE_URL
  if (props.type === 'hero2' || props.type === 'hero3')
    return `${base}img/HeroSectionOrtherPages.jpg`
  if (props.type === 'login')
    return `${base}img/HeroSectionLogIn.jpg`
  return `${base}img/HeroSectionPageOne.jpg`
})

// Vælg højde ud fra typen
const sectionHeight = computed(() => {
  if (props.type === 'hero2') return '650px'
  if (props.type === 'hero3') return '450px'
  if (props.type === 'login') return '100vh' // fuld skærm
  return '750px'
})
</script>

<template>
  <section class="heroSection" :style="{
    backgroundImage: `url(${filePath})`,
    height: sectionHeight
  }">
    <div v-if="props.type === 'login'"></div>

    <slot />
  </section>
</template>

<style scoped lang="scss">
.heroSection {
  width: 100%;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
}
</style>
