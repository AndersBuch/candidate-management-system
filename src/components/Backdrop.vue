<script setup>

</script>

<template>

  <div class="backdrop">
    <slot></slot>
  </div>

</template>

<style scoped lang="scss">

.backdrop {
  position: fixed;
  inset: 0;
  background-color: $backdropColor;
  display: flex;
  align-items: center;
  justify-content: center;
}

</style>