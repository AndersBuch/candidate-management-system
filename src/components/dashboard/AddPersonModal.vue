<script setup>
import Modal from '@/components/Modal.vue'
import InputField from '@/components/atoms/InputField.vue'
import FormLabel from '@/components/molecules/FormLabel.vue'
import FormField from '@/components/molecules/FormField.vue'
import Button from '@/components/atoms/Button.vue'
import FormDropdown from '@/components/molecules/FormDropdown.vue'

import { ref, reactive, computed, watch } from 'vue'

const showModal = ref(false)

const openModal = () => {
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
}

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
  age: '',
  company: '',
  status: '',

  touched: {
    name: false,
    lastname: false,
    email: false,
    phone: false,
    address: false,
    city: false,
    postal: false,
    linkedin: false,
    message: false,
    status: false
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
</script>

<template>
  <button @click="openModal">
    Åben denne her
  </button>

  <!-- Brug af modal-komponenten -->
  <Modal
    v-if="showModal"
    modalTitle="Min seje modal"
    titleAlign="left"
    @close="closeModal"
    height="900px"
  >
    <!-- Indholdet herinde bliver vist i <slot> i din Modal.vue -->

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

      <FormDropdown
        v-model="formData.status"
        :options="statusOptions"
        label="Status"
        :touched="formData.touched.status"
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


<FormLabel/>

            <FormField
        id="age"
        label="Alder"
        placeholder="Alder"
        v-model="formData.address"
        :touched="formData.touched.address"
        @blur="formData.touched.address = true"
      />

      <FormField
        id="company"
        label="Nuværenede Firma"
        placeholder="Nuværenede Firma"
        v-model="formData.address"
        :touched="formData.touched.address"
        @blur="formData.touched.address = true"
      />

      <FormField
        id="message"
        label="Note"
        placeholder="Maks 150 tegn"
        v-model="formData.address"
        :touched="formData.touched.address"
        @blur="formData.touched.address = true"
      />
</div>

    <div class="buttonContainer">
      <Button type="smallSecondaryButton" label="Annuller" aria-label="Annuller" />
      <Button type="smallDashboard" label="Gem" aria-label="Gem formular til kandidaten" />
    </div>

    <button @click="closeModal">
      Luk modal
    </button>
  </Modal>
</template>

<style lang="scss">

.statusDropdown {
  width: 100%;
  padding: 0.5rem;
  border-radius: 5px;
  border: 1px solid $sekundareBlue;
  background-color: $whiteColor;
  appearance: none; /* fjerner default browser pil */
  background-image: url("data:image/svg+xml,%3Csvg fill='black' height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3Cpath d='M0 0h24v24H0z' fill='none'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 0.5rem center;
  background-size: 1rem;
}

  .formGrid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem 2rem;
  }

.buttonContainer {
  display: flex;
  justify-content: flex-end; /* Skubber knapper til højre */
  gap: 20px; 
  bottom: 0; /* altid nederst */
}


</style>
