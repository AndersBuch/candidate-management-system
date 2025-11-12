<script setup>
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'
import Button from '@/components/Button.vue'
import { ref, computed } from 'vue'

const props = defineProps({
  title: { type: String, default: 'Klik på knappen for at uploade dit CV' },
  hint: { type: String, default: 'Fil typer: doc og pdf maks 2MB' },
  secondaryText: { type: String, default: 'Træk filen her eller brug knappen' },
  successText: { type: String, default: 'Fil klar til upload' },
  errorText: { type: String, default: 'Ugyldig filtype eller størrelse' },
  buttonText: { type: String, default: 'Upload CV' },
  accept: { type: String, default: '.pdf, .doc, .docx, .png, .jpg' },
  maxSizeMB: { type: Number, default: 2 }
})

const emit = defineEmits(['file-selected', 'error', 'file-removed'])

const fileInput = ref(null)
function triggerFileInput() { fileInput.value?.click() }

const dragging = ref(false)
const file = ref(null)
const errorMessage = ref('')
const inputId = 'upload-input-' + Date.now().toString(36) + Math.random().toString(36).slice(2,6)

const acceptList = computed(() =>
  props.accept.split(',').map(s => s.trim().toLowerCase()).filter(Boolean)
)

function validateAndSetFile(f) {
  errorMessage.value = ''
  if (!f) return
  const extOk =
    acceptList.value.length === 0 ||
    acceptList.value.some(a => {
      if (a.startsWith('.')) return f.name.toLowerCase().endsWith(a.toLowerCase())
      return f.type === a
    })

  if (!extOk) {
    errorMessage.value = props.errorText || 'Ugyldig filtype'
    file.value = null
    emit('error', { reason: 'type', file: f })
    return
  }

  const maxBytes = props.maxSizeMB * 1024 * 1024
  if (f.size > maxBytes) {
    errorMessage.value = `Filen er for stor (max ${props.maxSizeMB}MB)`
    file.value = null
    emit('error', { reason: 'size', file: f })
    return
  }

  file.value = f
  errorMessage.value = ''
  emit('file-selected', f)
}

function onInputChange(e) {
  const f = e.target.files && e.target.files[0]
  validateAndSetFile(f)
  e.target.value = ''
}

function onDrop(e) {
  e.preventDefault()
  dragging.value = false
  const f = e.dataTransfer?.files?.[0]
  validateAndSetFile(f)
}

function onDragOver(e) {
  e.preventDefault()
  dragging.value = true
}

function onDragLeave() {
  dragging.value = false
}

function removeFile() {
  const removed = file.value
  file.value = null
  errorMessage.value = ''
  emit('file-removed', removed)
}

const fileName = computed(() => (file.value ? file.value.name : ''))
const stateClass = computed(() => {
  if (errorMessage.value) return 'error'
  if (file.value) return 'success'
  return ''
})
</script>

<template>
  <div class="uploadeBoksRoot">
    <div class="UploadTitle">
      <h3>{{ props.title }}</h3>

      <div class="fileMeta" v-if="fileName">
        <a class="fileLink" href="#" @click.prevent>{{ fileName }}</a>
        <BasicIconAndLogo class="basicIconAndLogo" name="CloseGrey" @click.prevent="removeFile" :iconSize="true" />
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
        <!-- same position: CloudIcon eller Check -->
        <BasicIconAndLogo :name="file && !errorMessage ? 'Check' : 'CloudIcon'" :iconSize="true" />
     
      </div>

      <div class="uploadInner">
        <div class="centerContent">
          <!-- pop hint: always visible -->
          <p class="pop">{{ props.hint }}</p>

          <!-- secondary text (controlled from parent) -->
          <p class="secondaryText" v-if="!fileName">{{ props.secondaryText }}</p>

          <!-- when file approved: show successText -->
          <p class="uploadHint successText" v-else-if="fileName && !errorMessage">{{ props.successText }}</p>

          <!-- when error: show errorMessage -->
          <p class="uploadHint errorText" v-else-if="errorMessage">{{ errorMessage }}</p>

          <div class="actions">
            <input
              ref="fileInput"
              :id="inputId"
              class="fileInput"
              type="file"
              :accept="props.accept"
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
.uploadeBoksRoot { width: 100%; }

.uploadBox {
  border: 2px dashed #3b82f6;
  border-radius: 8px;
  padding: 18px;
  background: rgba(59,130,246,0.04);
  transition: border-color .15s, background .15s;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

/* state classes */
.uploadBox.error {
  border-color: #ef4444;
  background: rgba(239,68,68,0.03);
}
.uploadBox.success {
  border-color: #10b981;
  background: rgba(16,185,129,0.03);
}

.uploadHeader {
  display:flex;
  flex-direction:column;
  align-items:center;
  justify-content:center;
  gap:8px;
  text-align:center;
}

.UploadTitle { //lavet
  display:flex;
  align-items:center;
  justify-content:flex-start;
  margin-bottom:12px;

  h3 {
    @include bigBodyText;
    margin-right: 20px;

  }

}


.fileMeta { //Lavet
  display: flex;
  align-items: center;
  gap: 10px;

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

.actions { margin-top:8px; display:flex; justify-content:center; }
.fileInput { display:none; }

.uploadBox.dragging { background: rgba(14,165,233,0.06); border-color:#0284c7; }
</style>