<script setup>
import FormField from '@/components/molecules/FormField.vue'
import FormLabel from '@/components/molecules/FormLabel.vue'
import Button from '@/components/atoms/Button.vue'
import UploadeBoks from '@/components/molecules/UploadeBoks.vue'

import { reactive, computed, watch } from 'vue'

// Placeholder funktioner til UploadeBoks
function handleFile(f) { console.log('valgt fil', f) }
function handleError(e) { console.warn('upload error', e) }

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
  if (!isValidEmail(formData.email)) return 'Indtast en gyldig emailadresse med @.'
  return ''
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

function handleRemoved(f) {
  console.log('Filen fjernet', f)
}
</script>

<template>
  <form class="customForm" @submit.prevent="submitForm">
    <div class="formGrid">
      <FormField id="name" label="Fornavn" placeholder="Fornavn" v-model="formData.name"
        :touched="formData.touched.name" @blur="formData.touched.name = true" />

      <FormField id="lastname" label="Efternavn" placeholder="Efternavn" v-model="formData.lastname"
        :touched="formData.touched.lastname" @blur="formData.touched.lastname = true" />

      <FormField id="address" label="Adresse" placeholder="Adresse" v-model="formData.address"
        :touched="formData.touched.address" @blur="formData.touched.address = true" />

      <!-- Postnummer -->
      <FormField id="postal" label="Postnummer" placeholder="Postnummer" fieldType="text" v-model="formData.postal"
        :touched="formData.touched.postal" @input="handleNumberInput($event, 4, 'postal')"
        @blur="formData.touched.postal = true" />

      <FormField id="city" label="By" placeholder="Indtast by" v-model="formData.city" :touched="formData.touched.city"
        @blur="formData.touched.city = true" />

      <!-- Telefon -->
      <FormField id="phone" label="Telefon" placeholder="Indtast telefon" fieldType="text" v-model="formData.phone"
        :touched="formData.touched.phone" @input="handleNumberInput($event, 10, 'phone')"
        @blur="formData.touched.phone = true" />

      <FormField id="linkedin" label="LinkedIn"
        placeholder="Indtast din LinkedIn-profil (fx https://www.linkedin.com/in/dit-navn)" v-model="formData.linkedin"
        :touched="formData.touched.linkedin" @blur="formData.touched.linkedin = true" />

      <!-- Email med fejlbesked -->
      <FormField id="email" label="Email" placeholder="Indtast din email" v-model="formData.email"
        :error="!!emailErrorMessage" :touched="formData.touched.email" :error-message="emailErrorMessage"
        @input="formData.touched.email = true" @blur="formData.touched.email = true" />

      <FormLabel />

    </div>

    <div class="UploadeBokse">
      <UploadeBoks title="Upload CV" hint="Klik på knappen eller træk filer her"
        secondary-text="Fil typer: doc(x) og pdf maks 2MB" success-text="Filen er upload"
        error-text="Kun pdf/doc(x) op til 2MB" button-text="Upload CV" accept=".pdf,.doc,.docx" :max-size-mb="2"
        :multiple="false" @file-selected="handleFile" @error="handleError" @file-removed="handleRemoved" />

      <UploadeBoks title="Upload profil billede" hint="Klik på knappen eller træk filer her"
        secondary-text="Fil typer: jpg og png maks 2MB" success-text="profil billede klar"
        error-text="Kun .png eller .jpg op til 2MB" button-text="Upload profil billede" accept=".png, .jpg"
        :max-size-mb="2" :multiple="false" @file-selected="handleFile" @error="handleError"
        @file-removed="handleRemoved" />

      <UploadeBoks title="Upload ansøgning" hint="Klik på knappen eller træk filer her"
        secondary-text="Fil typer: doc(x) og pdf maks 2MB" success-text="Filen er upload"
        error-text="Kun pdf/doc(x) op til 2MB" button-text="Upload ansøgning" accept=".pdf,.doc,.docx" :max-size-mb="2"
        :multiple="false" @file-selected="handleFile" @error="handleError" @file-removed="handleRemoved" />

      <UploadeBoks title="Upload andre dokumenter" hint="Klik på knappen eller træk filer her"
        secondary-text="Fil typer: doc(x) og pdf maks 2MB" success-text="Filerne er uploadet"
        error-text="Kun pdf/doc(x) op til 2MB" button-text="Upload dokumenter" accept=".pdf,.doc,.docx" :max-size-mb="2"
        :multiple="true" @file-selected="handleFile" @error="handleError" @file-removed="handleRemoved" />
    </div>

    <div class="buttonContainer">
      <Button type="default" label="Send dit CV" aria-label="Send dit CV" />
    </div>
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
    margin-top: 1rem;
  }
}

.UploadeBokse {
  display: inline-block;
  margin-top: 20px;
  margin-bottom: 20px;
}

.buttonContainer {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 40px;
}
</style>
