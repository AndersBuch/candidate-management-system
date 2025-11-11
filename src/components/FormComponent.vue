<script setup>
import FormField from './FormField.vue'
import { reactive, computed, watch } from 'vue'

const formData = reactive({
  name: '',
  lastname: '',
  email: '',
  phone: '',
  address: '',
  city: '',
  postal: '',
  message: '',
  linkedin: '',
  touched: {
    name: false,
    lastname: false,
    email: false,
    phone: false,
    address: false,
    city: false,
    postal: false,
    linkedin: false,
    message: false
  }
})

// Funktion der sikrer kun tal og max-længde
const handleNumberInput = (event, maxLength, key) => {
  const value = event.target.value.replace(/\D/g, '') // Fjern ikke-tal
  formData[key] = value.slice(0, maxLength) // Begræns længde
}

// Simpel emailvalidering
const isValidEmail = (email) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)

// Computed fejlbesked til email
const emailErrorMessage = computed(() => {
  if (formData.email.trim() === '') return 'Email må ikke være tom.'
  if (!isValidEmail(formData.email)) return 'Indtast en gyldig emailadresse.'
  return ''
})

// Debug: tjek email, touched og fejltekst i konsollen
watch(() => formData.email, () => {
  formData.touched.email = true
})

// Computed for hele formen - tjek for fejl
const hasErrors = computed(() => !!emailErrorMessage.value)

const submitForm = () => {
  // Marker alle felter som touched, så fejl vises
  Object.keys(formData.touched).forEach(key => formData.touched[key] = true)

  // Tjek fejl inden submit
  if (!hasErrors.value) {
    console.log('Form sendes:', formData)
    // Her kan du kalde API eller email-funktion
  } else {
    console.log('Der er fejl i formularen')
  }
}
</script>

<template>
  <form class="customForm" @submit.prevent="submitForm">
    <div class="formGrid">
      <FormField
        id="name"
        label="Fornavn"
        placeholder="Fornavn"
        v-model="formData.name"
        :touched="formData.touched.name"
        @blur="formData.touched.name = true"
      />

      <FormField
        id="lastname"
        label="Efternavn"
        placeholder="Efternavn"
        v-model="formData.lastname"
        :touched="formData.touched.lastname"
        @blur="formData.touched.lastname = true"
      />

      <FormField
        id="address"
        label="Adresse"
        placeholder="Adresse"
        v-model="formData.address"
        :touched="formData.touched.address"
        @blur="formData.touched.address = true"
      />

      <!-- Postnummer -->
      <FormField
        id="postal"
        label="Postnummer"
        placeholder="Postnummer"
        fieldType="text"
        v-model="formData.postal"
        :touched="formData.touched.postal"
        @input="handleNumberInput($event, 4, 'postal')"
        @blur="formData.touched.postal = true"
      />

      <FormField
        id="city"
        label="By"
        placeholder="Indtast by"
        v-model="formData.city"
        :touched="formData.touched.city"
        @blur="formData.touched.city = true"
      />

      <!-- Telefon -->
      <FormField
        id="phone"
        label="Telefon"
        placeholder="Indtast telefon"
        fieldType="text"
        v-model="formData.phone"
        :touched="formData.touched.phone"
        @input="handleNumberInput($event, 10, 'phone')"
        @blur="formData.touched.phone = true"
      />

      <FormField
        id="linkedin"
        label="LinkedIn"
        placeholder="Indtast din LinkedIn-profil (fx https://www.linkedin.com/in/dit-navn)"
        v-model="formData.linkedin"
        :touched="formData.touched.linkedin"
        @blur="formData.touched.linkedin = true"
      />


      <!-- Email med fejlbesked -->
<FormField
  id="email"
  label="Email"
  placeholder="Indtast din email"
  v-model="formData.email"
  :error="!!emailErrorMessage"          
  :touched="formData.touched.email"
  :error-message="emailErrorMessage"  
  @input="formData.touched.email = true"
  @blur="formData.touched.email = true"
/>


    </div>

    <button type="submit">Send</button>
  </form>
</template>

<style scoped lang="scss">
.customForm {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;

  .formGrid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem 2rem;
  }

  button {
    align-self: flex-start;
    width: 120px;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    background-color: $primaryBlue;
    color: white;
    border: none;
    cursor: pointer;
    margin-top: 1rem;

    &:hover {
      background-color: lighten($primaryBlue, 10%);
    }
  }
}
</style>
