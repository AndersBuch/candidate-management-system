<script setup>
const props = defineProps({
  title: { type: String, default: '' },
  subtitle: { type: String, default: '' },
})
</script>

<template>
  <div class="header-section-container">
    <h2 class="section-title">{{ title }}</h2>
    <p class="section-subtitle">{{ subtitle }}</p>
  </div>
</template>

<style scoped lang="scss">
.header-section-container {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  padding-top: 40px;
  padding-bottom: 40px;

    .section-title {
      color: $black;
       @include heading2;
    }
    .section-subtitle {
      color: $darkGrey;
      @include bodyText;
    }

}
</style>