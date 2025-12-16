<script setup>
import Modal from '@/components/Modal.vue'
import FormLabel from '@/components/molecules/FormLabel.vue'
import FormField from '@/components/molecules/FormField.vue'
import Button from '@/components/atoms/Button.vue'
import FormDropdown from '@/components/molecules/FormDropdown.vue'
import UploadButton from '@/components/dashboard/UploadButton.vue'
import Toast from '@/components/dashboard/ToastDashboard.vue'

import { ref, reactive, computed } from 'vue'
import { useCandidateStore } from '@/stores/addCandidateStore'


const candidateStore = useCandidateStore()

const statusOptions = ['Kontakt', 'Afventer', 'Accepteret', 'Afvist']

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
  gender: '',
  message: '',
  linkedin: '',
  age: '',
  company: '',
  status: 'Kontakt',

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

// Placeholder funktioner til UploadeBoks
function handleFile(f) { console.log('valgt fil', f) }
function handleError(e) { console.warn('upload error', e) }

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

const toasts = ref([])

function showToast() {
  toasts.value.push({
    id: Date.now(),
    title: 'Kandidat tilføjet',
    subtitle: 'Kandidaten blev tilføjet korrekt',
    variant: 'success',
    duration: 3000,
    showUndo: false 
  })
}

function removeToast(id) {
  toasts.value = toasts.value.filter(t => t.id !== id)
}

async function confirmAdd() {
  const payload = {
    first_name: formData.name,
    last_name: formData.lastname,
    email: formData.email,
    phone_number: formData.phone,
    address: formData.address,
    status: formData.status,
    zip_code: formData.postal,
    city: formData.city,
    gender: formData.gender,
    age: formData.age,
    linkedin_url: formData.linkedin,
    current_position: formData.company,
    note: formData.message
  }

  await candidateStore.addCandidate(payload)

  resetForm()   // <--- WOO, NU VIRKER DET 🎉
  closeModal()
  showToast()
}


const resetForm = () => {
  Object.assign(formData, {
    name: '',
    lastname: '',
    email: '',
    phone: '',
    address: '',
    city: '',
    postal: '',
    gender: '',
    message: '',
    linkedin: '',
    age: '',
    company: '',
    status: 'Kontakt',

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
}


</script>

<template>
  <Button type="dashboardPrimary" label="Tilføj kandidat" aria-label="Tilføj kandidat" :showIcon="true"
    iconName="AddPerson" @click="openModal" />
 <transition name="fade">
  <Modal v-if="showModal" modalTitle="Tilføj kandidat" titleAlign="left" @close="closeModal" height="900px">
    <!-- Indholdet herinde bliver vist i <slot> i din Modal.vue -->

    <div class="formGrid">
      <FormField id="name" label="Fornavn" placeholder="Fornavn" v-model="formData.name"
        :touched="formData.touched.name" @blur="formData.touched.name = true" />

      <FormField id="lastname" label="Efternavn" placeholder="Efternavn" v-model="formData.lastname"
        :touched="formData.touched.lastname" @blur="formData.touched.lastname = true" />

      <!-- Email med fejlbesked -->
      <FormField id="email" label="Email" placeholder="Indtast din email" v-model="formData.email"
        :error="!!emailErrorMessage" :touched="formData.touched.email" :error-message="emailErrorMessage"
        @input="formData.touched.email = true" @blur="formData.touched.email = true" />

      <FormField id="address" label="Adresse" placeholder="Adresse" v-model="formData.address"
        :touched="formData.touched.address" @blur="formData.touched.address = true" />

      <!-- Postnummer -->
      <FormField id="postal" label="Postnummer" placeholder="Postnummer" fieldType="text" v-model="formData.postal"
        :touched="formData.touched.postal" @input="handleNumberInput($event, 4, 'postal')"
        @blur="formData.touched.postal = true" />

      <FormDropdown v-model="formData.status" :options="statusOptions" label="Status"
        :touched="formData.touched.status" />

      <FormField id="city" label="By" placeholder="Indtast by" v-model="formData.city" :touched="formData.touched.city"
        @blur="formData.touched.city = true" />

      <!-- Telefon -->
      <FormField id="phone" label="Telefon" placeholder="Indtast telefon" fieldType="text" v-model="formData.phone"
        :touched="formData.touched.phone" @input="handleNumberInput($event, 10, 'phone')"
        @blur="formData.touched.phone = true" />

      <FormField id="linkedin" label="LinkedIn"
        placeholder="Indtast din LinkedIn-profil (fx https://www.linkedin.com/in/dit-navn)" v-model="formData.linkedin"
        :touched="formData.touched.linkedin" @blur="formData.touched.linkedin = true" />

      <FormLabel v-model="formData.gender" />

      <FormField id="age" label="Alder" placeholder="Alder" v-model="formData.age" :touched="formData.touched.age"
        @input="handleNumberInput($event, 2, 'age')" @blur="formData.touched.age = true" />

      <FormField id="company" label="Nuværenede Firma" placeholder="Nuværenede Firma" v-model="formData.company"
        :touched="formData.touched.company" @blur="formData.touched.company = true" />

      <FormField id="message" label="Note" placeholder="Maks 150 tegn" fieldType="textarea" v-model="formData.message"
        :touched="formData.touched.message" @blur="formData.touched.message = true" class="noteField" />
    </div>

    <div class="uploadeButtons">
      <UploadButton title="CV" button-text="Upload" accept=".pdf,.doc,.docx" :max-size-mb="2" :multiple="false"
        @file-selected="handleFile" @error="handleError" @file-removed="handleRemoved" />

      <UploadButton title="Billede" button-text="Upload" accept=".png,.jpg" :max-size-mb="2" :multiple="false"
        @file-selected="handleFile" @error="handleError" @file-removed="handleRemoved" />

      <UploadButton title="Andre dokumenter" button-text="Upload" accept=".pdf,.doc,.docx,.png,.jpg" :max-size-mb="2"
        :multiple="true" @file-selected="handleFile" @error="handleError" @file-removed="handleRemoved" />

      <UploadButton title="Ansøgning" button-text="Upload" accept=".pdf,.doc,.docx" :max-size-mb="2" :multiple="false"
        @file-selected="handleFile" @error="handleError" @file-removed="handleRemoved" />
    </div>

    <div class="buttonContainer">
      <Button type="smallSecondaryButton" label="Annuller" aria-label="Annuller" @click="closeModal" />
      <Button type="smallDashboard" label="Gem" aria-label="Gem formular til kandidaten" @click="confirmAdd" />
    </div>

  </Modal>
  </transition>

  <div class="toastWrapper">
    <Toast v-for="t in toasts" :key="t.id" :title="t.title" :subtitle="t.subtitle" :variant="t.variant"
      :duration="t.duration" :showUndo="t.showUndo"  @close="removeToast(t.id)" @undo="handleUndo" />
  </div>
</template>

<style lang="scss">
.toastWrapper {
  position: fixed;
  bottom: 20px;
  left: 20px;
  display: flex;
  flex-direction: column;
  gap: 10px;
  z-index: 9999;
}

.uploadeButtons {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 2rem 2rem;
  margin-bottom: 50px;

  .uploadItem {
    display: flex;
    flex-direction: column;

    h3 {
      @include bigBodyText;
      color: $black;
      margin-bottom: 0.5rem;
    }
  }

  .uploadItem:nth-child(3),
  .uploadItem:nth-child(4) {
    margin-top: 9rem;
  }
}

.formGrid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.5rem 2rem;
  margin-bottom: 20px;
}

.buttonContainer {
  display: flex;
  justify-content: flex-end;
  gap: 20px;
  bottom: 0;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.4s ease-out, transform 0.4s ease-out;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
.fade-enter-to, .fade-leave-from {
  opacity: 1;
  transform: translateY(0);
}
</style>
