<script setup>
import InputField from '@/components/atoms/InputField.vue'
import { ref } from 'vue'

const genderMan = ref(false)
const genderWomen = ref(false)
const genderNothing = ref(false)

// const newsletter = ref(true)
</script>

<template>
  <div class="inputContainer">
    <p>Køn</p>
    <div class="checkbox-row">
      <!-- Første genbrug -->
      <InputField
        v-model:checked="genderMan"
        label="Mand"
      />

      <!-- Anden genbrug -->
      <InputField
        v-model:checked="genderWomen"
        label="Kvinde"
      />

      <!-- Tredje genbrug -->
      <InputField
        v-model:checked="genderNothing"
        label="Intet/andet køn"
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
