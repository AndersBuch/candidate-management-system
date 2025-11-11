<script setup>
import InputField from './InputField.vue'
import { ref } from 'vue'

const acceptedTerms = ref(false)
const newsletter = ref(true)
</script>

<template>
  <div class="inputContainer">
    <p>Køn</p>
    <div class="checkbox-row">
      <!-- Første genbrug -->
      <InputField
        v-model:checked="acceptedTerms"
        label="Jeg accepterer betingelserne"
      />

      <!-- Anden genbrug -->
      <InputField
        v-model:checked="newsletter"
        label="Tilmeld nyhedsbrev"
      />

      <!-- Tredje genbrug -->
      <InputField
        v-model:checked="newsletter"
        label="Noget tredje"
      />
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
  display: flex;       /* gør dem i række */
  gap: 1.5rem;         /* afstand mellem checkboxene */
  flex-wrap: wrap;     /* flyt til næste linje hvis der ikke er plads */
}
</style>
