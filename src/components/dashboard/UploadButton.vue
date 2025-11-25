<script setup>
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'
import Button from '@/components/atoms/Button.vue'

import { ref, computed } from 'vue'

const props = defineProps({
  title: { type: String, default: 'Klik på knappen for at uploade dit CV' },
  hint: { type: String, default: 'Fil typer: doc og pdf maks 2MB' },
  secondaryText: { type: String, default: 'Træk filerne her eller brug knappen' },
  successText: { type: String, default: 'Filer klar til upload' },
  errorText: { type: String, default: 'Ugyldig filtype eller størrelse' },
  buttonText: { type: String, default: 'Upload filer' },
  accept: { type: String, default: '.pdf, .doc, .docx, .png, .jpg' },
  maxSizeMB: { type: Number, default: 2 },
  multiple: { type: Boolean, default: false }
})

const emit = defineEmits(['file-selected', 'error', 'file-removed'])

const fileInput = ref(null)
const dragging = ref(false)
const files = ref([])
const errorMessage = ref('')
const inputId = 'upload-input-' + Date.now().toString(36)

function triggerFileInput() {
  fileInput.value?.click()
}

const acceptList = computed(() =>
  props.accept.split(',').map(s => s.trim().toLowerCase()).filter(Boolean)
)

function validateAndAddFiles(fileList) {
  errorMessage.value = ''
  const maxBytes = props.maxSizeMB * 1024 * 1024

  // Hvis multiple = false → tillad kun en fil
  if (!props.multiple && fileList.length > 1) {
    errorMessage.value = 'Du kan kun uploade én fil her.'
    emit('error', { reason: 'single-only', count: fileList.length })
    return
  }

  // Maks 6 filer samlet
  if (files.value.length + fileList.length > 6) {
    errorMessage.value = 'Du kan maks uploade 6 filer.'
    emit('error', { reason: 'max-files', count: files.value.length + fileList.length })
    return
  }

  for (const f of fileList) {
    const extOk = acceptList.value.some(a => {
      if (a.startsWith('.')) return f.name.toLowerCase().endsWith(a)
      return f.type === a
    })

    if (!extOk) {
      errorMessage.value = props.errorText
      emit('error', { reason: 'type', file: f })
      continue
    }

    if (f.size > maxBytes) {
      errorMessage.value = `Filen er for stor (max ${props.maxSizeMB}MB)`
      emit('error', { reason: 'size', file: f })
      continue
    }

    // Hvis kun en fil må vælges, overskriv i stedet for at tilføje
    if (!props.multiple) {
      files.value = [f]
    } else {
      files.value.push(f)
    }
  }

  emit('file-selected', props.multiple ? files.value : files.value[0])
}

function onInputChange(e) {
  const fileList = e.target.files
  if (fileList.length) validateAndAddFiles(fileList)
  e.target.value = ''
}

function onDrop(e) {
  e.preventDefault()
  dragging.value = false
  const fileList = e.dataTransfer?.files
  if (fileList?.length) validateAndAddFiles(fileList)
}

function onDragOver(e) {
  e.preventDefault()
  dragging.value = true
}

function onDragLeave() {
  dragging.value = false
}

function removeFile(index) {
  const removed = files.value.splice(index, 1)[0]
  emit('file-removed', removed)
}

const fileNames = computed(() => files.value.map(f => f.name))
const stateClass = computed(() => {
  if (errorMessage.value) return 'error'
  if (files.value.length) return 'success'
  return ''
})
</script>

<template>
  <div class="uploadeButtonRoot">

    <!-- Titel -->
    <div class="UploadTitle">
      <h3>{{ props.title }}</h3>
      <p v-if="errorMessage" class="uploadError">{{ errorMessage }}</p>
    </div>

    <!-- Upload-knap -->
    <div class="uploadButton" :class="[stateClass, { dragging }]" @drop="onDrop" @dragover="onDragOver"
      @dragleave="onDragLeave">
      <div class="actions">
        <input ref="fileInput" :id="inputId" class="fileInput" type="file" :accept="props.accept"
          :multiple="props.multiple" @change="onInputChange" />
        <Button type="dashboardPrimary" @click="triggerFileInput" :label="props.buttonText"
          :aria-label="props.buttonText" />
      </div>
    </div>

    <!-- ✔ FILE META LIGGER NU HER -->
    <div class="fileMeta" v-if="files.length">
      <div class="fileRow" v-for="(name, index) in fileNames" :key="index">
        <a class="fileLink" href="#" @click.prevent>{{ name }}</a>
        <BasicIconAndLogo class="basicIconAndLogo" name="CloseGrey" @click.prevent="removeFile(index)"
          :iconSize="true" />
      </div>
    </div>
  </div>

</template>

<style scoped lang="scss">
.uploadeButtonRoot {
  margin-bottom: 20px;
  width: 100%;
}

.UploadTitle {
  //lavet
  display: flex;
  align-items: center;
  justify-content: flex-start;
  margin-bottom: 10px;

  h3 {
    @include bigBodyText;
    margin-right: 20px;

  }
}

.actions {
  margin-bottom: 10px;
}

.fileRow {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
  min-width: 0; // VIGTIGSTE LINJE!
  max-width: 330px;
  overflow: hidden;
}

.fileMeta {
  display: flex;
  flex-direction: column; // ⬅ hver fil på en ny linje
  gap: 8px;

  .fileLink {
    color: $goodGreen;
    text-decoration: underline;
    @include bodyText;
    cursor: default;
    min-width: 0;
    flex: 1 1 auto;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: block;

    &:hover {
      color: rgba($goodGreen, 0.8);
    }
  }

  .basicIconAndLogo {
    cursor: pointer;
    display: inline-flex;
    transition: opacity .15s ease;
    flex: 0 0 24px;
  }

  .basicIconAndLogo:hover {
    opacity: .8;
  }
}

.fileInput {
  display: none;
}

.uploadError {
  color: $dangerRed; // rød farve til fejl
  @include bodyText; // lidt mindre end h3
  margin-top: 4px; // afstand mellem titel og fejl
}

.uploadButton.dragging {
  background: rgba($primaryBlue, 0.1);
  border-radius: 5px;
}

.uploadButton.dragging {
  border-color: rgba($primaryBlue, 8%);
  background: rgba($primaryBlue, 0.2);
}

/* når boksen er i error-tilstand men brugeren trækker */
.uploadButton.error.dragging {
  border-color: $dangerRed;
  background: rgba($dangerRed, 0.2);
}

/* når boksen er i success-tilstand men brugeren trækker */
.uploadButton.success.dragging {
  border-color: $goodGreen;
  background: rgba($goodGreen, 0.2);
}
</style>