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

const files = reactive({
  cv: null,
  photo: null,
  ansogning: null,
  andet: []
})

const markedForDeletion = ref(new Set())

const hasAnyFiles = computed(() => {
  const isFile = (f) => f instanceof File
  const hasFileInArray = (arr) => Array.isArray(arr) && arr.some(isFile)

  return (
    isFile(files.cv) ||
    isFile(files.photo) ||
    isFile(files.ansogning) ||
    hasFileInArray(files.andet)
  )
})

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
const docsByKind = (kind) => {
  if (!documents.value || !Array.isArray(documents.value)) return []
  return documents.value.filter(d => d.kind === kind)
}

watch(
  () => props.candidate,
  async (candidate) => {
    if (!candidate) return

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

    // Load existing docs BEFORE showing modal
    await loadDocuments()

    // NOW show the modal (docs are loaded)
    showModal.value = true
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

function handleFile(type, fileOrFiles) {
  if (!fileOrFiles) return

  // Hvis UploadButton sender flere filer på en gang (array eller FileList)
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
  const value = event.target.value.replace(/\D/g, '')
  formData[key] = value.slice(0, maxLength)
}

// Simpel emailvalidering
const isValidEmail = (email) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)

// Computed fejlbesked til email
const emailErrorMessage = computed(() => {
  if (formData.email.trim() === '') return
  if (!isValidEmail(formData.email)) return 'Indtast en gyldig email'
  return ''
})

const removeDocument = (docId) => {
  markedForDeletion.value.add(docId)
  loadDocuments().catch(err => {
    console.error('Failed to reload documents:', err)
  })
  addToast({ title: 'Markeret til sletning', subtitle: 'Filen slettes når du gemmer.', variant: 'success' })
}

const submitForm = async () => {
  try {
    const candidateId = props.candidate.id
    const applicationId = props.candidate?.applicationId ?? props.candidate?.application_id

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

    for (const docId of markedForDeletion.value) {
      try {
        if (candidateStore.deleteDocument) {
          await candidateStore.deleteDocument(docId)
        }
      } catch (err) {
        console.error(`Failed to delete document ${docId}:`, err)
        addToast({
          title: 'Fejl',
          subtitle: `Kunne ikke slette dokument ${docId}. Fortsætter med upload...`,
          variant: 'warning'
        })
      }
    }

    let success = false

    if (hasAnyFiles.value && candidateStore.updateCandidateWithFiles) {
      const filesObj = {
        cv: files.cv,
        ansogning: files.ansogning,
        photo: files.photo,
        andet: files.andet && files.andet.length > 0 ? [...files.andet] : []
      }
      success = await candidateStore.updateCandidateWithFiles(candidateId, payload, filesObj)
      await loadDocuments()
    } else {
      success = await candidateStore.updateCandidate(candidateId, payload)
    }

    if (success) {
      markedForDeletion.value.clear()
      emit('saved')
      closeModal()
    } else {
      addToast({ title: 'Fejl', subtitle: 'Opdateringen mislykkedes. Prøv igen.', variant: 'danger' })
    }
  } catch (err) {
    console.error('submitForm error:', err)
    const errorMsg = err?.message || 'En fejl opstod. Prøv igen.'
    addToast({ title: 'Fejl', subtitle: errorMsg, variant: 'danger' })
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

        <FormDropdown v-model="formData.status" :options="['Kontakt', 'Afventer', 'Accepteret', 'Afvist']"
          label="Status" :touched="formData.touched.status" @change="onStatusChange" />

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
          <UploadButton title="CV" button-text="Upload" accept=".pdf,doc,docx" :multiple="false"
            :existing-files="docsByKind('CV')" :marked-for-deletion="markedForDeletion"
            @file-selected="(f) => handleFile('cv', f)" @file-removed="(f) => handleRemoved('cv', f)"
            @remove-existing="removeDocument" />
        </div>

        <div class="uploadItem">
          <UploadButton title="Billede" button-text="Upload" accept=".png,.jpg,.jpeg" :multiple="false"
            :existing-files="docsByKind('Foto')" :marked-for-deletion="markedForDeletion"
            @file-selected="(f) => handleFile('photo', f)" @file-removed="(f) => handleRemoved('photo', f)"
            @remove-existing="removeDocument" />
        </div>

        <div class="uploadItem">
          <UploadButton title="Andre dokumenter" button-text="Upload" :multiple="true"
            :existing-files="docsByKind('Andet')" :marked-for-deletion="markedForDeletion"
            @file-selected="(f) => handleFile('andet', f)" @file-removed="(f) => handleRemoved('andet', f)"
            @remove-existing="removeDocument" />
        </div>

        <div class="uploadItem">
          <UploadButton title="Ansøgning" button-text="Upload" accept=".pdf,doc,docx" :max-size-mb="2" :multiple="false"
            :existing-files="docsByKind('Ansøgning')" :marked-for-deletion="markedForDeletion"
            @file-selected="(f) => handleFile('ansogning', f)" @file-removed="(f) => handleRemoved('ansogning', f)"
            @remove-existing="removeDocument" />
        </div>
      </div>

      <div class="buttonContainer">
        <Button type="smallSecondaryButton" label="Annuller" aria-label="Annuller" @click="closeModal" />
        <Button type="smallDashboard" label="Gem" aria-label="Gem formular til kandidaten" @click="submitForm" />
      </div>
    </Modal>
  </transition>

  <div class="toastWrapper">
    <Toast v-for="t in toasts" :key="t.id" :title="t.title" :subtitle="t.subtitle" :variant="t.variant"
      :duration="t.duration" :showUndo="t.showUndo" @close="removeToast(t.id)" />
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
