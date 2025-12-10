<script setup>
import InputField from '@/components/atoms/InputField.vue'
import { ref } from 'vue'

const gender = defineModel() // <-- dette er værdi parent får tilbage

const genderMan = ref(false)
const genderWomen = ref(false)
const genderNothing = ref(false)

const updateGender = (selected) => {
  gender.value = selected   // <-- parent modtager værdien korrekt

  genderMan.value = selected === 'Mand'
  genderWomen.value = selected === 'Kvinde'
  genderNothing.value = selected === 'Andet'
}
</script>

<template>
  <div class="inputContainer">
    <p>Køn</p>

    <div class="checkbox-row">
      <InputField
        v-model:checked="genderMan"
        label="Mand"
        @update:checked="updateGender('Mand')"
      />

      <InputField
        v-model:checked="genderWomen"
        label="Kvinde"
        @update:checked="updateGender('Kvinde')"
      />

      <InputField
        v-model:checked="genderNothing"
        label="Intet/andet køn"
        @update:checked="updateGender('Andet')"
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
  display: flex;
  gap: 1.5rem;
  flex-wrap: wrap;
}
</style>
