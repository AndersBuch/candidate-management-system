<script setup>
import InputField from '@/components/atoms/InputField.vue'

import { ref } from 'vue'

const genderMan = ref(false)
const genderWomen = ref(false)
const genderNothing = ref(false)
</script>

<template>
  <div class="inputContainer">
    <p>Køn</p>

    <div class="checkbox-row">
      <InputField v-model:checked="genderMan" label="Mand" />
      <InputField v-model:checked="genderWomen" label="Kvinde" />
      <InputField v-model:checked="genderNothing" label="Intet/andet køn" />
    </div>

  </div>
</template>

<style scoped lang="scss">
.inputContainer {
  display: flex;
  flex-direction: column;
  gap: 1rem;

  p {
    @include bigBodyText;
  }
}

.checkbox-row {
  display: flex;
  gap: 1.5rem;
  flex-wrap: wrap;
}
</style>
