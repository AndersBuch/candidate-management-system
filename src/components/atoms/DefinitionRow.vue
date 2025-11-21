<script setup>
import { computed } from 'vue'

const props = defineProps({
  label: {
    type: String,
    required: true,
  },
  value: {
    type: [String, Number, Boolean, null],
    default: null,
  },
  // If true - full width
  full: {
    type: Boolean,
    default: false,
  },
})

const displayValue = computed(() => {
  if (props.value === null || props.value === undefined || props.value === '') {
    return '—'
  }
  return props.value
})
</script>

<template>
  
  <div :class="['definition-row', { 'definition-row--full': full }]">
    <dt class="definition-row__label">{{ label }}</dt>
    <dd class="definition-row__value">{{ displayValue }}</dd>
  </div>
</template>

<style scoped lang="scss">
.definition-row {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.definition-row__label {
  @include boldBodyText;
}

.definition-row__value {
  @include bodyText;
}


.definition-row--full {
  grid-column: 1 / -1;
}
</style>