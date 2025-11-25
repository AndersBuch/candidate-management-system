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
  <div :class="['definitionRow', { 'definitionRowFull': full }]">
    <dt class="definitionRowLabel">{{ label }}</dt>
    <dd class="definitionRowValue">{{ displayValue }}</dd>
  </div>
</template>

<style scoped lang="scss">
.definitionRow {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.definitionRowLabel {
  @include boldBodyText;
}

.definitionRowValue {
  @include bodyText;
}

.definitionRowFull {
  grid-column: 1 / -1;
}
</style>