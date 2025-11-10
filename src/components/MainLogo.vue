<script setup>
import InlineSvg from 'vue-inline-svg'
import { computed } from 'vue'


// Logo ligger i public/icons/Logo.svg
const filePath = computed(() => import.meta.env.BASE_URL + 'icons/Logo.svg')
</script>

<template>
  <InlineSvg
    :src="filePath"
    title="Logo"
    keep-during-loading
  />
</template>

<style scoped lang="scss">
svg {
  display: inline-block;
  width: 150px;
}

</style>
