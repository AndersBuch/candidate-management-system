<script setup>

const props = defineProps({
  title: { type: String, default: '' },
  subtitle: { type: String, default: '' },
})
</script>

<template>
  <div class="headerSectionContainer">
    <h2 class="sectionTitle">{{ title }}</h2>
    <p class="sectionSubtitle">{{ subtitle }}</p>
  </div>



</template>

<style scoped lang="scss">
.headerSectionContainer {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  padding-top: 40px;
  padding-bottom: 40px;

    .sectionTitle {
      color: $black;
       @include heading2;
    }
    .sectionSubtitle {
      color: $darkGrey;
      @include bodyText;
    }

}
</style>