<script setup>
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'

import { defineProps, defineModel } from 'vue'

defineProps({
  placeholder: {
    type: String,
    default: "Søg..."
  }
})

const model = defineModel() // v-model support
</script>

<template>
  <div class="searchWrapper">
    <BasicIconAndLogo name="Search" :iconSize="true" />
    <input
      type="text"
      :placeholder="placeholder"
      v-model="model"
      class="searchInput"
    />
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
