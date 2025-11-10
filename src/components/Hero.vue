<script setup>
import { computed } from 'vue'

const props = defineProps({
  type: String
})

// vælg billede og højde ud fra typen
const filePath = computed(() => {
  const base = import.meta.env.BASE_URL
  if (props.type === 'hero2' || props.type === 'hero3')
    return `${base}img/HeroSectionOrtherPages.jpg`
  return `${base}img/HeroSectionPageOne.jpg`
})

const sectionHeight = computed(() => {
  if (props.type === 'hero2') return '650px'
  if (props.type === 'hero3') return '450px'
  return '750px'
})
</script>

<template>
  <section
    class="heroSection"
    :style="{
      backgroundImage: `url(${filePath})`,
      height: sectionHeight
    }"
  ></section>
</template>

<style scoped lang="scss">
.heroSection {
  width: 100%;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  display: flex;
  align-items: center;
}
</style>
