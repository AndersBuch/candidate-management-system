<script setup>
import FormField from '@/components/molecules/FormField.vue'
import FormLabel from '@/components/molecules/FormLabel.vue'
import Button from '@/components/atoms/Button.vue'
import UploadeBoks from '@/components/molecules/UploadeBoks.vue'

import { reactive, computed, ref, } from 'vue'
import { useCandidateStore } from '@/stores/addCandidateStore'
import { useCompanyStore } from '@/stores/useCompanyStore'

const candidateStore = useCandidateStore()
const companyStore = useCompanyStore()


// Upload state (sendes som multipart/form-data via candidateStore)
const files = reactive({
  cv: null,
  photo: null,
  ansogning: null,
  andet: []
})

const submitError = ref('')
const submitSuccess = ref(false)

// Form state (ingen message)
const formData = reactive({
  name: '',
  lastname: '',
  email: '',
  phone: '',
  address: '',
  city: '',
  postal: '',
  linkedin: '',
  gender: '',
  touched: {
    name: false,
    lastname: false,
    email: false,
    phone: false,
    address: false,
    city: false,
    postal: false,
    linkedin: false
  }
})

// Kun tal + max længde
const handleNumberInput = (event, maxLength, key) => {
  const value = String(event?.target?.value ?? '').replace(/\D/g, '')
  formData[key] = value.slice(0, maxLength)
}

// Simpel emailvalidering
const isValidEmail = (email) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)

const emailErrorMessage = computed(() => {
  if (!formData.touched.email) return ''
  if (formData.email.trim() === '') return 'Email må ikke være tom.'
  if (!isValidEmail(formData.email)) return 'Indtast en gyldig emailadresse med @.'
  return ''
})

const hasErrors = computed(() => !!emailErrorMessage.value)

// Fil handler til UploadeBoks
function handleFile(kind, fileOrFiles) {
  if (!fileOrFiles) return

  const arr = Array.isArray(fileOrFiles) ? fileOrFiles : [fileOrFiles]

  if (kind === 'andet') {
    arr.forEach(f => {
      if (f) files.andet.push(f)
    })
    return
  }

  files[kind] = arr[0] ?? null
}

function handleRemoved(kind, removedInfo = null) {
  if (kind === 'andet') {
    // removedInfo kan være index eller fil-objekt afhængigt af UploadeBoks
    if (typeof removedInfo === 'number') {
      files.andet.splice(removedInfo, 1)
    } else if (removedInfo?.name) {
      const idx = files.andet.findIndex(f => f?.name === removedInfo.name)
      if (idx >= 0) files.andet.splice(idx, 1)
    } else {
      files.andet = []
    }
    return
  }

  files[kind] = null
}

function handleError(e) {
  console.warn('upload error', e)
}

const submitForm = async () => {
  submitError.value = ''
  submitSuccess.value = false

  // Marker alle felter som touched, så fejl vises
  Object.keys(formData.touched).forEach((key) => {
    formData.touched[key] = true
  })

  // Tjek fejl inden submit
  if (hasErrors.value) return

  // Sørg for at der er valgt et job (sættes i privacy modal flowet)
  if (!companyStore.activePosition?.id) {
    submitError.value = 'Ingen stilling valgt. Gå tilbage og tryk "Søg job" på en stilling.'
    return
  }

  // Payload (matcher AddPersonModal keys, bare færre felter + uden note/message)
  const payload = {
    first_name: formData.name,
    last_name: formData.lastname,
    email: formData.email,
    phone_number: formData.phone,
    address: formData.address,
    zip_code: formData.postal,
    city: formData.city,
    linkedin_url: formData.linkedin,
    gender: formData.gender,
    status: 'Afventer'
  }

  try {
    await candidateStore.addCandidateWithFiles(payload, files)
    submitSuccess.value = true
  } catch (e) {
    console.error(e)
    submitError.value = 'Kunne ikke indsende ansøgning. Prøv igen.'
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

      <FormField
        id="email"
        label="Email"
        placeholder="Indtast din email"
        v-model="formData.email"
        :touched="formData.touched.email"
        :errorMessage="emailErrorMessage"
        @input="formData.touched.email = true"
        @blur="formData.touched.email = true"
      />

      <!-- Køn -->
      <FormLabel v-model="formData.gender" />
    </div>

    <div class="UploadeBokse">
      <UploadeBoks
        title="Upload CV"
        hint="Klik på knappen eller træk filer her"
        secondary-text="Fil typer: doc(x) og pdf maks 2MB"
        success-text="Filen er upload"
        error-text="Kun pdf/doc(x) op til 2MB"
        button-text="Upload CV"
        accept=".pdf,.doc,.docx"
        :max-size-mb="2"
        :multiple="false"
        @file-selected="(f) => handleFile('cv', f)"
        @error="handleError"
        @file-removed="(info) => handleRemoved('cv', info)"
      />

      <UploadeBoks
        title="Upload profil billede"
        hint="Klik på knappen eller træk filer her"
        secondary-text="Fil typer: jpg og png maks 2MB"
        success-text="profil billede klar"
        error-text="Kun .png eller .jpg op til 2MB"
        button-text="Upload profil billede"
        accept=".png,.jpg,.jpeg"
        :max-size-mb="2"
        :multiple="false"
        @file-selected="(f) => handleFile('photo', f)"
        @error="handleError"
        @file-removed="(info) => handleRemoved('photo', info)"
      />

      <UploadeBoks
        title="Upload ansøgning"
        hint="Klik på knappen eller træk filer her"
        secondary-text="Fil typer: doc(x) og pdf maks 2MB"
        success-text="Filen er upload"
        error-text="Kun pdf/doc(x) op til 2MB"
        button-text="Upload ansøgning"
        accept=".pdf,.doc,.docx"
        :max-size-mb="2"
        :multiple="false"
        @file-selected="(f) => handleFile('ansogning', f)"
        @error="handleError"
        @file-removed="(info) => handleRemoved('ansogning', info)"
      />

      <UploadeBoks
        title="Upload andre dokumenter"
        hint="Klik på knappen eller træk filer her"
        secondary-text="Fil typer: doc(x) og pdf maks 2MB"
        success-text="Filerne er uploadet"
        error-text="Kun pdf/doc(x) op til 2MB"
        button-text="Upload dokumenter"
        accept=".pdf,.doc,.docx"
        :max-size-mb="2"
        :multiple="true"
        @file-selected="(f) => handleFile('andet', f)"
        @error="handleError"
        @file-removed="(info) => handleRemoved('andet', info)"
      />
    </div>

    <div class="buttonContainer">
      
        <Button htmlType="submit" type="default" label="Send dit CV" aria-label="Send dit CV" />
      
    </div>

    <p v-if="submitError" style="text-align:center; margin-bottom: 20px;">
      {{ submitError }}
    </p>
    <p v-if="submitSuccess" style="text-align:center; margin-bottom: 20px;">
      Tak! Din ansøgning er sendt.
    </p>
  </form>
</template>

<style scoped lang="scss">

  .submitBtnWrapper {
  all: unset;
  display: inline-block;
  cursor: pointer;
}
.customForm {
  display: flex;
  flex-direction: column;
}

.formGrid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
}

@media (max-width: 900px) {
  .formGrid {
    grid-template-columns: 1fr;
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
