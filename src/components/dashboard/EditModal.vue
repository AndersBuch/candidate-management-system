<script setup>
import Modal from '@/components/Modal.vue'
import InputField from '@/components/atoms/InputField.vue'
import FormLabel from '@/components/molecules/FormLabel.vue'
import FormField from '@/components/molecules/FormField.vue'
import Button from '@/components/atoms/Button.vue'
import FormDropdown from '@/components/molecules/FormDropdown.vue'
import UploadButton from '@/components/dashboard/UploadButton.vue'
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'
import Toast from '@/components/dashboard/ToastDashboard.vue'

import { ref, reactive, computed, watch } from 'vue'
import { useCandidateStore } from '@/stores/addCandidateStore'

const candidateStore = useCandidateStore()

const emit = defineEmits(['close', 'saved'])

const props = defineProps({
  candidate: {
    type: Object,
    required: true
  }
})

const showModal = ref(false)

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
  gender: '',

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
    status: false,
    gender: false
  }
})

/**
 * ✅ Selected files waiting to be uploaded in this edit session
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

/**
 * ✅ Existing documents fetched from backend for this application
 */
const documents = ref([])

const loadDocuments = async () => {
  const applicationId =
    props.candidate?.applicationId ?? props.candidate?.application_id

  if (!applicationId || !candidateStore.fetchDocuments) {
    documents.value = []
    return
  }

  documents.value = await candidateStore.fetchDocuments(applicationId)
}

// Toasts (optional)
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

watch(
  () => props.candidate,
  async (candidate) => {
    if (!candidate) return

    showModal.value = true
    // Support both snake_case (API) and camelCase (mapped) candidate objects
    const get = (a, b, c) => (a !== undefined ? a : (b !== undefined ? b : c))
    const hasValue = (v) => v !== undefined && v !== null && (typeof v !== 'string' || v.trim() !== '')

    const incomingName = get(candidate.first_name, candidate.firstName, candidate.name)
    if (hasValue(incomingName)) {
      if (typeof incomingName === 'string' && incomingName.includes(' ')) {
        const parts = incomingName.trim().split(' ')
        formData.name = parts[0]
        formData.lastname = parts.slice(1).join(' ')
      } else {
        const fn = get(candidate.first_name, candidate.firstName)
        const ln = get(candidate.last_name, candidate.lastName)
        if (hasValue(fn)) formData.name = fn
        if (hasValue(ln)) formData.lastname = ln
      }
    } else {
      const fn = get(candidate.first_name, candidate.firstName)
      const ln = get(candidate.last_name, candidate.lastName)
      if (hasValue(fn)) formData.name = fn
      if (hasValue(ln)) formData.lastname = ln
    }

    const email = get(candidate.email, null, null)
    if (hasValue(email)) formData.email = email

    const phone = get(candidate.phone, candidate.phone_number, null)
    if (hasValue(phone)) formData.phone = phone

    const address = get(candidate.address, null, null)
    if (hasValue(address)) formData.address = address

    const postal = get(candidate.zip_code, candidate.postal, null)
    if (hasValue(postal)) formData.postal = postal

    const city = get(candidate.city, null, null)
    if (hasValue(city)) formData.city = city

    const age = get(candidate.age, null, null)
    if (hasValue(age)) formData.age = age

    const linkedin = get(candidate.linkedin, candidate.linkedin_url, null)
    if (hasValue(linkedin)) formData.linkedin = linkedin

    const company = get(candidate.current_position, candidate.company, null)
    if (hasValue(company)) formData.company = company

    const message = get(candidate.note, candidate.message, null)
    if (hasValue(message)) formData.message = message

    const status = get(candidate.status, null, null)
    if (hasValue(status)) formData.status = status

    const gender = get(candidate.gender, null, null)
    if (hasValue(gender)) formData.gender = gender

    // Reset pending uploads each time we open a candidate
    files.cv = null
    files.photo = null
    files.ansogning = null
    files.andet = []

    // Load existing docs
    await loadDocuments()
  },
  { immediate: true }
)

// Opdater status når den ændres i formularen
const onStatusChange = async (event) => {
  const newStatus = event.value
  const applicationId = props.candidate?.applicationId ?? props.candidate?.application_id

  if (applicationId) {
    await candidateStore.updateStatus(applicationId, newStatus)
  }
}

const closeModal = () => {
  showModal.value = false
  emit('close')
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

// Funktion der sikrer kun tal og max-længde
const handleNumberInput = (event, maxLength, key) => {
  const value = event.target.value.replace(/\D/g, '') // Fjern ikke-tal
  formData[key] = value.slice(0, maxLength) // Begræns længde
}

// Simpel emailvalidering
const isValidEmail = (email) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)

// Computed fejlbesked til email
const emailErrorMessage = computed(() => {
  if (formData.email.trim() === '') return
  if (!isValidEmail(formData.email)) return 'Indtast en gyldig email'
  return ''
})

/**
 * ✅ Remove a single existing document (DB + physical file)
 */
const removeDocument = async (docId) => {
  try {
    if (!candidateStore.deleteDocument) return
    await candidateStore.deleteDocument(docId)
    await loadDocuments()
    addToast({ title: 'Dokument fjernet', subtitle: 'Filen blev slettet.', variant: 'success' })
  } catch (err) {
    console.error('removeDocument error:', err)
    addToast({ title: 'Fejl', subtitle: 'Kunne ikke slette dokumentet.', variant: 'error' })
  }
}

const submitForm = async () => {
  try {
    const candidateId = props.candidate.id
    const applicationId = props.candidate?.applicationId ?? props.candidate?.application_id

    // ✅ payload must include application_id (backend needs it for upload paths)
    const payload = {
      application_id: applicationId,
      first_name: formData.name,
      last_name: formData.lastname,
      email: formData.email,
      phone: formData.phone,
      address: formData.address,
      zip_code: formData.postal,
      city: formData.city,
      age: formData.age,
      linkedin: formData.linkedin,
      current_position: formData.company,
      note: formData.message,
      status: formData.status,
      gender: formData.gender
    }

    let success = false

    if (hasAnyFiles.value && candidateStore.updateCandidateWithFiles) {
      success = await candidateStore.updateCandidateWithFiles(candidateId, payload, files)
      // refresh doc list after upload
      await loadDocuments()
    } else {
      success = await candidateStore.updateCandidate(candidateId, payload)
    }

    if (success) {
      emit('saved')
      closeModal()
    }
  } catch (err) {
    console.error('submitForm error:', err)
    addToast({ title: 'Fejl', subtitle: 'Kunne ikke opdatere kandidaten.', variant: 'error' })
  }
}
</script>

<template>
  <transition name="fade">
    <Modal v-if="showModal" modalTitle="Rediger kandidat" titleAlign="left" @close="closeModal" height="900px">
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

        <FormDropdown v-model="formData.status" :options="['Kontakt','Afventer','Accepteret','Afvist']" label="Status"
          :touched="formData.touched.status" @change="onStatusChange" />

        <FormField id="city" label="By" placeholder="Indtast by" v-model="formData.city"
          :touched="formData.touched.city" @blur="formData.touched.city = true" />

        <FormField id="phone" label="Telefon" placeholder="Indtast telefon" fieldType="text" v-model="formData.phone"
          :touched="formData.touched.phone" @input="handleNumberInput($event, 10, 'phone')"
          @blur="formData.touched.phone = true" />

        <FormField id="linkedin" label="LinkedIn"
          placeholder="Indtast din LinkedIn-profil (fx https://www.linkedin.com/in/dit-navn)" v-model="formData.linkedin"
          :touched="formData.touched.linkedin" @blur="formData.touched.linkedin = true" />

        <FormLabel v-model="formData.gender" />

        <FormField id="age" label="Alder" placeholder="Alder" v-model="formData.age" :touched="formData.touched.age"
          @input="handleNumberInput($event, 2, 'age')" @blur="formData.touched.age = true" />

        <FormField id="company" label="Nuværende virksomhed" placeholder="Nuværende virksomhed"
          v-model="formData.company" :touched="formData.touched.company" @blur="formData.touched.company = true" />

        <FormField id="message" label="Noter" placeholder="Noter" v-model="formData.message"
          :touched="formData.touched.message" @blur="formData.touched.message = true" />
      </div>

      <div class="uploadeButtons">
        <UploadButton title="CV" button-text="Upload" accept=".pdf,doc,docx" :max-size-mb="2" :multiple="false"
          @file-selected="(f) => handleFile('cv', f)" @error="handleError" @file-removed="(f) => handleRemoved('cv', f)" />

        <UploadButton title="Billede" button-text="Upload" accept=".png,.jpg,.jpeg" :max-size-mb="2" :multiple="false"
          @file-selected="(f) => handleFile('photo', f)" @error="handleError"
          @file-removed="(f) => handleRemoved('photo', f)" />

        <UploadButton title="Andre dokumenter" button-text="Upload" accept=".pdf,doc,docx,png,jpg,jpeg" :max-size-mb="2"
          :multiple="true" @file-selected="(f) => handleFile('andet', f)" @error="handleError"
          @file-removed="(f) => handleRemoved('andet', f)" />

        <UploadButton title="Ansøgning" button-text="Upload" accept=".pdf,doc,docx" :max-size-mb="2" :multiple="false"
          @file-selected="(f) => handleFile('ansogning', f)" @error="handleError"
          @file-removed="(f) => handleRemoved('ansogning', f)" />
      </div>

      <!-- ✅ Existing documents -->
      <div v-if="documents.length" style="margin-bottom: 20px;">
        <h3 style="margin-bottom: 10px;">Eksisterende dokumenter</h3>

        <div v-for="d in documents" :key="d.id" style="display:flex; gap:12px; align-items:center; margin-bottom:8px;">
          <span style="min-width:110px;"><strong>{{ d.kind }}</strong></span>
          <span style="flex:1;">{{ d.file_name }}</span>

          <a :href="`/api/documents/${d.id}/download`" target="_blank" rel="noopener">Download</a>
          <button type="button" class="iconBtn" @click="removeDocument(d.id)">Fjern</button>
        </div>
      </div>

      <div class="buttonContainer">
        <Button type="smallSecondaryButton" label="Annuller" aria-label="Annuller" @click="closeModal" />
        <Button type="smallDashboard" label="Gem" aria-label="Gem formular til kandidaten" @click="submitForm" />
      </div>
    </Modal>
  </transition>

  <div class="toastWrapper">
    <Toast
      v-for="t in toasts"
      :key="t.id"
      :title="t.title"
      :subtitle="t.subtitle"
      :variant="t.variant"
      :duration="t.duration"
      :showUndo="t.showUndo"
      @close="removeToast(t.id)"
    />
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

.iconBtn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: transform 0.2s ease, opacity 0.2s ease;
  background: transparent;
  border: none;
  padding: 4px 8px;

  &:hover {
    transform: scale(1.05);
    opacity: 0.9;
  }
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
