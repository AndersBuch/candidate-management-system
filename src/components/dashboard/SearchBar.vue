<script setup>
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'
import { defineProps, defineEmits } from 'vue'

const props = defineProps({
  modelValue: String,
  placeholder: {
    type: String,
    default: 'Søg...'
  }
})

const emit = defineEmits(['update:modelValue'])

function updateValue(event) {
  emit('update:modelValue', event.target.value)
}
</script>

<template>
  <div class="searchWrapper">
    <BasicIconAndLogo name="Search" :iconSize="true" />
    <input type="text" :placeholder="props.placeholder" :value="props.modelValue" @input="updateValue"
      class="searchInput" />
  </div>
</template>

<style scoped lang="scss">
.searchWrapper {
  display: flex;
  align-items: center;
  background: $lightGrey;
  border-radius: 5px;
  padding: 10px 15px;
  width: 100%;
  max-width: 300px;
  gap: 5px;
  margin-bottom: 20px;
}

.searchInput {
  border: none;
  background: transparent;
  outline: none;
  width: 100%;
  @include bodyText;
  color: $black;

  &::placeholder {
    color: $darkGrey;
  }

}
</style>
