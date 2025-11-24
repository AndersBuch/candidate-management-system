<script setup>
import { defineEmits } from 'vue'

const emit = defineEmits(['clickOutside'])

const handleClick = () => {
  emit('clickOutside')  // emitter når man klikker på overlay
}
</script>

<template>
  <div class="backdrop" @click="handleClick">
    <div class="backdropContent" @click.stop>
      <slot></slot>
    </div>
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
  z-index: 999999; 
}

.backdropContent {
  position: relative;
  z-index: 1001;
}
</style>
