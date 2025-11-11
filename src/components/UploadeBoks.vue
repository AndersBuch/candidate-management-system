<script setup>
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'
import Button from '@/components/Button.vue'
import { ref, computed } from 'vue'

const fileInput = ref(null)

function triggerFileInput() {
  fileInput.value?.click()
}

const props = defineProps({
  title: { type: String, default: 'Klik på knappen for at uploade dit CV' },
  hint: { type: String, default: 'Fil typer: doc og pdf maks 2MB' },
  buttonText: { type: String, default: 'Upload CV' },
  accept: { type: String, default: '.pdf, .doc, .docx' },
  maxSizeMB: { type: Number, default: 2 }
})

const emit = defineEmits(['file-selected', 'error', 'file-removed'])

const dragging = ref(false)
const file = ref(null)
const errorMessage = ref('')
const inputId = 'upload-input-' + Date.now().toString(36) + Math.random().toString(36).slice(2,6)

const acceptList = computed(() => props.accept.split(',').map(s => s.trim().toLowerCase()).filter(Boolean))

function validateAndSetFile(f) {
  errorMessage.value = ''
  if (!f) return
  // check ext by name if accept contains dot-suffixes, else check mime
  const extOk = acceptList.value.length === 0 || acceptList.value.some(a => {
    if (a.startsWith('.')) {
      return f.name.toLowerCase().endsWith(a)
    }
    return f.type === a
  })
  if (!extOk) {
    errorMessage.value = 'Ugyldig filtype'
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

const fileName = computed(() => file.value ? file.value.name : '')
const stateClass = computed(() => {
  if (errorMessage.value) return 'error'
  if (file.value) return 'success'
  return ''
})
</script>

<template>
    <div class="UploadTitle">
        <h3>{{ props.title }}</h3>

        <div class="fileMeta" v-if="fileName">
        <a class="fileLink" @click.prevent>{{ fileName }}</a>
        <BasicIconAndLogo name="CloseGrey" @click.prevent="removeFile" :iconSize="true" />
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
      <BasicIconAndLogo name="CloudIcon" :iconSize="true" />
      <p class="uploadHint" v-if="hint">{{ hint }}</p>
    </div>

    <div class="uploadInner">
      <div class="iconWrap">
        <template v-if="file && !errorMessage">
          <!-- check icon -->
        <BasicIconAndLogo name="Check" :iconSize="true" />
        </template>
        <template v-else>
        </template>
      </div>

      <div class="centerContent">
        <p class="uploadHint" v-if="!fileName">{{ props.hint }}</p>
        <p class="uploadHint successText" v-if="fileName && !errorMessage">Fil klar til upload</p>
        <div class="actions">
        <input
            ref="fileInput"
            class="fileInput"
            type="file"
            :accept="props.accept"
            @change="onInputChange"
        />
        <Button @click="triggerFileInput":label="props.buttonText" :aria-label="props.buttonText" />

        </div>
      </div>
    </div>

    <p class="errorMessage" v-if="errorMessage">{{ errorMessage }}</p>
  </div>
</template>

<style scoped lang="scss">
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
  display: flex;
  flex-direction: column; /* 🟢 Placer ikon og tekst under hinanden */
  align-items: center;    /* 🟢 Centrer vandret */
  justify-content: center;/* 🟢 Centrer lodret */
  gap: 8px;               /* 🟢 Lidt luft imellem ikon og tekst */
  text-align: center;     /* 🟢 Sørg for, at teksten står midt */
}

.uploadTitle {
  margin:0;
  font-size:1.05rem;
  color:#0f172a;
}

.fileMeta {
  display:flex;
  align-items:center;
  gap:8px;
}

.fileLink {
  color:#059669;
  text-decoration: underline;
  cursor: pointer;
  font-weight:600;
  font-size:.95rem;
}

.removeBtn {
  background:transparent;
  border:none;
  color:#374151;
  font-size:1rem;
  cursor:pointer;
  padding:4px;
  border-radius:4px;
}
.removeBtn:hover { background: rgba(15,23,42,0.04); }

.uploadInner {
  display: flex;
  flex-direction: column; /* 🟢 Alt inde i uploadInner skal ligge under hinanden */
  align-items: center;    /* 🟢 Centrer alt vandret */
  justify-content: center;/* 🟢 Centrer alt lodret */
  gap: 12px;
  padding: 8px 0;
}

.iconWrap { display:flex; align-items:center; justify-content:center; width:44px; }
.icon { display:block; }
.icon.check { stroke:#059669; }
.icon.cloud { stroke:#0ea5e9; }

.centerContent {
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center; /* 🟢 Gør, at teksten og knappen også centrerer */
  justify-content: center;
}

.uploadHint { margin:0; color:#2563eb; font-weight:600; }
.successText { color:#059669; }

.actions { margin-top:8px; display:flex; justify-content:center; }
.fileInput { display:none; }

.uploadButton {
  background:#0ea5e9;
  color:white;
  padding:10px 18px;
  border-radius:999px;
  cursor:pointer;
  user-select:none;
  font-weight:600;
  border:none;
}

/* error text */
.errorMessage {
  color:#ef4444;
  margin:0;
  font-size:0.95rem;
  text-align:center;
}

/* dragging visual */
.uploadBox.dragging { background: rgba(14,165,233,0.06); border-color:#0284c7; }
</style>