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

  // reset form
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

  // reset files
  files.cv = null
  files.photo = null
  files.ansogning = null
  files.andet = []
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

/**
 * ✅ Files state (skal matches med backend/pinia keys)
 * cv -> $_FILES['cv']
 * photo -> $_FILES['photo']
 * ansogning -> $_FILES['ansogning']
 * andet[] -> $_FILES['andet'] (flere)
 */
const files = reactive({
  cv: null,
  photo: null,
  ansogning: null,
  andet: []
})

const hasAnyFiles = computed(() => {
  return !!files.cv || !!files.photo || !!files.ansogning || (files.andet && files.andet.length > 0)
})

// Funktion der sikrer kun tal og max-længde
const handleNumberInput = (event, maxLength, key) => {
  const value = event.target.value.replace(/\D/g, '') // Fjern ikke-tal
  formData[key] = value.slice(0, maxLength) // Begræns længde
}

/**
 * ✅ Upload handlers (typed)
 */
function handleFile(type, fileOrFiles) {
  if (!fileOrFiles) return

  // Hvis UploadButton sender flere filer på én gang (array eller FileList)
  const list = Array.isArray(fileOrFiles)
    ? fileOrFiles
    : (fileOrFiles instanceof FileList ? Array.from(fileOrFiles) : [fileOrFiles])

  if (type === 'andet') {
    // filtrer alt der ikke er File
    list.forEach(f => {
      if (f instanceof File) files.andet.push(f)
    })
  } else {
    // single: tag første File
    const first = list.find(f => f instanceof File)
    if (first) files[type] = first
  }
}

function handleRemoved(type, fileOrFiles) {
  const list = Array.isArray(fileOrFiles)
    ? fileOrFiles
    : (fileOrFiles instanceof FileList ? Array.from(fileOrFiles) : [fileOrFiles])

  if (type === 'andet') {
    files.andet = files.andet.filter(existing => !list.includes(existing))
  } else {
    files[type] = null
  }
}

function handleError(e) {
  console.warn('upload error', e)
}

// Simpel emailvalidering
const isValidEmail = (email) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)

// Computed fejlbesked til email
const emailErrorMessage = computed(() => {
  if (formData.email.trim() === '') return
  if (!isValidEmail(formData.email)) return 'Indtast en gyldig email'
  return ''
})

// Toasts
const toasts = ref([])
const addToast = (toast) => {
  toasts.value.push({
    id: Date.now() + Math.random(),
    title: toast.title,
    subtitle: toast.subtitle,
    variant: toast.variant || 'success',
    duration: toast.duration || 3000,
    showUndo: toast.showUndo || false
  })
}
const removeToast = (id) => {
  toasts.value = toasts.value.filter((t) => t.id !== id)
}

function handleUndo() {
  console.log('Undo klikket – ikke implementeret endnu')
}

async function confirmAdd() {
  // payload (snake_case matcher backend)
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

  try {
    // ✅ Hvis der er filer -> FormData action, ellers JSON action
    if (hasAnyFiles.value && candidateStore.addCandidateWithFiles) {
      await candidateStore.addCandidateWithFiles(payload, files)
    } else {
      await candidateStore.addCandidate(payload)
    }

    addToast({
      title: 'Kandidat oprettet',
      subtitle: hasAnyFiles.value ? 'Kandidat og dokumenter blev gemt.' : 'Kandidat blev gemt.',
      variant: 'success'
    })

    closeModal()
  } catch (err) {
    console.error('confirmAdd error:', err)
    addToast({
      title: 'Fejl',
      subtitle: 'Kunne ikke gemme kandidaten. Tjek konsollen/server logs.',
      variant: 'error'
    })
  }
}
</script>

<template>
  <Button type="dashboardPrimary" label="Tilføj kandidat" aria-label="Tilføj kandidat" :showIcon="true"
    iconName="AddPerson" @click="openModal" />

  <transition name="fade">
    <Modal v-if="showModal" modalTitle="Tilføj kandidat" titleAlign="left" @close="closeModal" height="900px">
      <div class="formGrid">
        <FormField id="name" label="Fornavn" placeholder="Fornavn" v-model="formData.name"
          :touched="formData.touched.name" @blur="formData.touched.name = true" />

        <FormField id="lastname" label="Efternavn" placeholder="Efternavn" v-model="formData.lastname"
          :touched="formData.touched.lastname" @blur="formData.touched.lastname = true" />

        <FormField id="email" label="Email" placeholder="Indtast din email" v-model="formData.email"
          :error="!!emailErrorMessage" :touched="formData.touched.email" :error-message="emailErrorMessage"
          @input="formData.touched.email = true" @blur="formData.touched.email = true" />

        <FormField id="address" label="Adresse" placeholder="Adresse" v-model="formData.address"
          :touched="formData.touched.address" @blur="formData.touched.address = true" />

        <FormField id="postal" label="Postnummer" placeholder="Postnummer" fieldType="text" v-model="formData.postal"
          :touched="formData.touched.postal" @input="handleNumberInput($event, 4, 'postal')"
          @blur="formData.touched.postal = true" />

        <FormDropdown v-model="formData.status" :options="statusOptions" label="Status"
          :touched="formData.touched.status" />

        <FormField id="city" label="By" placeholder="Indtast by" v-model="formData.city"
          :touched="formData.touched.city" @blur="formData.touched.city = true" />

        <FormField id="phone" label="Telefon" placeholder="Indtast telefon" fieldType="text" v-model="formData.phone"
          :touched="formData.touched.phone" @input="handleNumberInput($event, 10, 'phone')"
          @blur="formData.touched.phone = true" />

        <FormField id="linkedin" label="LinkedIn"
          placeholder="Indtast din LinkedIn-profil (fx https://www.linkedin.com/in/dit-navn)"
          v-model="formData.linkedin" :touched="formData.touched.linkedin" @blur="formData.touched.linkedin = true" />

        <FormLabel v-model="formData.gender" />

        <FormField id="age" label="Alder" placeholder="Alder" v-model="formData.age" :touched="formData.touched.age"
          @input="handleNumberInput($event, 2, 'age')" @blur="formData.touched.age = true" />

        <FormField id="company" label="Nuværende virksomhed" placeholder="Nuværende virksomhed"
          v-model="formData.company" :touched="formData.touched.company" @blur="formData.touched.company = true" />

        <FormField id="message" label="Noter" placeholder="Noter" v-model="formData.message"
          :touched="formData.touched.message" @blur="formData.touched.message = true" />
      </div>

      <div class="uploadeButtons">
        <div class="uploadItem">
          <UploadButton title="CV" button-text="Upload" accept=".pdf,.doc,.docx" :max-size-mb="2" :multiple="false"
            @file-selected="(f) => handleFile('cv', f)" @error="handleError"
            @file-removed="(f) => handleRemoved('cv', f)" />
        </div>

        <div class="uploadItem">
          <UploadButton title="Billede" button-text="Upload" accept=".png,.jpg,.jpeg" :max-size-mb="2" :multiple="false"
            @file-selected="(f) => handleFile('photo', f)" @error="handleError"
            @file-removed="(f) => handleRemoved('photo', f)" />
        </div>

        <div class="uploadItem">
          <UploadButton title="Andre dokumenter" button-text="Upload" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg"
            :max-size-mb="2" :multiple="true" @file-selected="(f) => handleFile('andet', f)" @error="handleError"
            @file-removed="(f) => handleRemoved('andet', f)" />
        </div>

        <div class="uploadItem">
          <UploadButton title="Ansøgning" button-text="Upload" accept=".pdf,doc,docx" :max-size-mb="2" :multiple="false"
            @file-selected="(f) => handleFile('ansogning', f)" @error="handleError"
            @file-removed="(f) => handleRemoved('ansogning', f)" />
        </div>
      </div>

      <div class="buttonContainer">
        <Button type="smallSecondaryButton" label="Annuller" aria-label="Annuller" @click="closeModal" />
        <Button type="smallDashboard" label="Gem" aria-label="Gem formular til kandidaten" @click="confirmAdd" />
      </div>
    </Modal>
  </transition>

  <div class="toastWrapper">
    <Toast v-for="t in toasts" :key="t.id" :title="t.title" :subtitle="t.subtitle" :variant="t.variant"
      :duration="t.duration" :showUndo="t.showUndo" @close="removeToast(t.id)" @undo="handleUndo" />
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

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.4s ease-out, transform 0.4s ease-out;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

.fade-enter-to,
.fade-leave-from {
  opacity: 1;
  transform: translateY(0);
}
</style>
