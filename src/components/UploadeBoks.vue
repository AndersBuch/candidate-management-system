<script setup>
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'
import Button from '@/components/Button.vue'
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
  multiple: { type: Boolean, default: false } // 👈 tilføjet her
})

const emit = defineEmits(['file-selected', 'error', 'file-removed'])

const fileInput = ref(null)
const dragging = ref(false)
const files = ref([]) // 👈 vi bruger array i stedet for én fil
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

  // 🔒 Hvis multiple = false → tillad kun én fil
  if (!props.multiple && fileList.length > 1) {
    errorMessage.value = 'Du kan kun uploade én fil her.'
    emit('error', { reason: 'single-only', count: fileList.length })
    return
  }

  // 🔒 Maks 6 filer samlet
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

    // ✅ Hvis kun én fil må vælges, overskriv i stedet for at tilføje
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
  <div class="uploadeBoksRoot">
    <div class="UploadTitle">
      <h3>{{ props.title }}</h3>

      <div class="fileMeta" v-if="files.length">
        <template v-for="(name, index) in fileNames" :key="index">
          <a class="fileLink" href="#" @click.prevent>{{ name }}</a>
          <BasicIconAndLogo
            class="basicIconAndLogo"
            name="CloseGrey"
            @click.prevent="removeFile(index)"
            :iconSize="true"
          />
        </template>
      </div>
    </div>

    <div
      class="uploadBox"
      :class="[stateClass, { dragging }]"
      @drop="onDrop"
      @dragover="onDragOver"
      @dragleave="onDragLeave"
    >
      <div class="uploadHeader">
        <BasicIconAndLogo :name="files.length && !errorMessage ? 'Check' : 'CloudIcon'" :iconSize="true" />
      </div>

      <div class="uploadInner">
        <div class="centerContent">
          <p class="pop">{{ props.hint }}</p>

          <p class="uploadHint errorText" v-if="errorMessage">{{ errorMessage }}</p>
          <p class="uploadHint successText" v-else-if="files.length">{{ props.successText }}</p>
          <p class="secondaryText" v-else>{{ props.secondaryText }}</p>

          <div class="actions">
            <input
              ref="fileInput"
              :id="inputId"
              class="fileInput"
              type="file"
              :accept="props.accept"
              :multiple="props.multiple" 
              @change="onInputChange"
            />
            <Button @click="triggerFileInput" :label="props.buttonText" :aria-label="props.buttonText" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped lang="scss">
.uploadBox {
  border: 2px dashed $primaryBlue;
  border-radius: 5px;
  padding: 18px;
  background: $sekundareBlue;
  transition: border-color .15s, background .15s;
  flex-direction: column;
  gap: 12px;
  margin-bottom: 20px;
}

.uploadBox.error {
  border-color: $dangerRed;
  background-color: rgba($dangerRed, 0.1);

}
.uploadBox.success {
  border-color: $goodGreen;
  background: rgba($goodGreen, 0.1);
}

.uploadHeader {
  display:flex;
  flex-direction:column;
  align-items:center;
  justify-content:center;
  text-align:center;
}

.UploadTitle { //lavet
  display:flex;
  align-items:center;
  justify-content:flex-start;
  margin-bottom: 20px;

  h3 {
    @include bigBodyText;
    margin-right: 20px;

  }

}

.fileMeta { 
  display: flex;
  align-items: center;
  flex-wrap: wrap; 
  gap: 10px;
  max-width: 100%; 

  .fileLink {
    color: $goodGreen;
    text-decoration: underline;
    @include bodyText;
    cursor: default;

    &:hover {
      color: rgba($goodGreen, 0.8);
    }
  }

  .basicIconAndLogo {
    cursor: pointer;
    display: inline-flex; 
    transition: opacity .15s ease;
  }

  .basicIconAndLogo:hover {
    opacity: .8;
  }
}


/* center content */
.uploadInner {
  display:flex;
  flex-direction:column;
  align-items:center;
  justify-content:center;
  padding:8px 0;
}

.centerContent {  //Lavet
  text-align:center; 
  display:flex; 
  flex-direction:column; 
  align-items:center; }

.pop { 
  margin:0; 
  color:$primaryBlue; 
  @include bigBodyText;
} 

.secondaryText { 
  margin:0; 
  color:$darkGrey;
  @include bodyText; 
} 

.successText { 
  color:$goodGreen;
  @include bodyText; 
}

.errorText { 
  color:$dangerRed;
  @include bodyText; 
}

.actions { 
  margin-top:8px; 
  display:flex; 
  justify-content:center; 
}

.fileInput { 
  display:none; 
}

.uploadBox.dragging { 
  background: rgba($primaryBlue, 0.1); 
  }

  .uploadBox.dragging {
  border-color: darken($primaryBlue, 8%);
  background: rgba($primaryBlue, 0.08);
}

/* når boksen er i error-tilstand men brugeren trækker */
.uploadBox.error.dragging {
  border-color: $dangerRed; 
  background: rgba($dangerRed, 0.2); 
}

/* når boksen er i success-tilstand men brugeren trækker */
.uploadBox.success.dragging {
  border-color: $goodGreen;
  background: rgba($goodGreen, 0.2); 
}


</style>