<script setup>
import Button from '@/components/atoms/Button.vue'
import { computed, onMounted, ref, watch } from 'vue'
import { useCandidateStore } from '@/stores/addCandidateStore'

const props = defineProps({
  applicationId: {
    type: [Number, String],
    required: true,
  },
})

const candidateStore = useCandidateStore()

const documents = ref([])
const loading = ref(false)
const error = ref(null)

async function loadDocuments() {
  if (!props.applicationId) return
  loading.value = true
  error.value = null
  try {
    documents.value = await candidateStore.fetchDocuments(props.applicationId)
  } catch (e) {
    error.value = e?.message || String(e)
    documents.value = []
  } finally {
    loading.value = false
  }
}

onMounted(loadDocuments)
watch(() => props.applicationId, loadDocuments)

// Byg URLs (hvis du senere vælger at backend returnerer view_url/download_url, bruger vi dem)
function viewUrl(doc) {
  return doc.view_url || `/api/documents/${doc.id}/view`
}
function downloadUrl(doc) {
  return doc.download_url || `/api/documents/${doc.id}/download`
}

function fileName(doc) {
  return doc.file_name || doc.original_name || doc.name || doc.filename || 'Dokument'
}

/**
 * Gruppér docs i sektioner.
 * Her gætter vi på feltet "type"/"kind"/"category".
 * Hvis din backend bruger et andet felt-navn, så sig hvad det hedder, så retter jeg mapperen.
 */
function normalizeType(doc) {
  const raw = (doc.kind || '').toString().toLowerCase()

  // normalisér danske bogstaver
  const k = raw
    .replace(/ø/g, 'o')
    .replace(/å/g, 'a')
    .replace(/æ/g, 'ae')

  if (k === 'cv') return 'cv'
  if (k === 'ansogning' || k === 'ansoegning' || k === 'application')
    return 'application'
  if (k === 'photo' || k === 'foto' || k === 'billede')
    return 'photo'
  if (k === 'andet' || k === 'other')
    return 'other'

  return 'other'
}

const sections = computed(() => {
  const base = [
    { id: 'cv', title: 'CV', files: [] },
    { id: 'application', title: 'Ansøgning', files: [] },
    { id: 'photo', title: 'Billede', files: [] },
    { id: 'other', title: 'Andre dokumenter', files: [] },
  ]

  for (const doc of documents.value || []) {
    const type = normalizeType(doc)
    const section = base.find((s) => s.id === type) || base[2]

    section.files.push({
      id: doc.id,
      name: fileName(doc),
      viewUrl: viewUrl(doc),
      downloadUrl: downloadUrl(doc),
    })
  }

  return base
})

// Download via <a> click så Button kan bruges uden at wrappe i <a>
function downloadFile(file) {
  const a = document.createElement('a')
  a.href = file.downloadUrl
  a.download = '' // browser bruger Content-Disposition filename fra server hvis sat
  document.body.appendChild(a)
  a.click()
  a.remove()
}
</script>

<template>
  <div class="documents">
    <!-- status -->
    <div v-if="loading">Henter dokumenter...</div>
    <div v-else-if="error">Fejl: {{ error }}</div>

    <div v-else>
      <div v-for="section in sections" :key="section.id" class="documentsSection">
        <h3 class="documentsTitle">{{ section.title }}</h3>

        <ul class="documentsList">
          <li v-for="file in section.files" :key="file.id" class="documentsItem">
            <!-- Klik navn -> åbner PDF (inline) i ny fane -->
            <a
              class="documentsFileLink"
              :href="file.viewUrl"
              target="_blank"
              rel="noopener"
            >
              {{ file.name }}
            </a>

            <!-- Download-knap -->
            <Button
              type="smallDashboard"
              label="Download"
              aria-label="Download dokumenter"
              @click="downloadFile(file)"
            />
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<style scoped lang="scss">
.documents {
  margin-top: 24px;
}

.documentsSection {
  margin-top: 24px;
}

.documentsTitle {
  @include boldBodyText;
}

/* Liste med filer */
.documentsList {
  list-style: none;
  padding: 0;
  margin: 0;
}

.documentsItem {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  gap: 16px;
  padding: 10px 0px;
}

/* Filnavn */
.documentsFileLink a,
.documentsFileLink a:visited,
.documentsFileLink a:hover,
.documentsFileLink a:active {
  color: $goodGreen;
  text-decoration: none;
  @include bodyText;

  flex: 1 1 auto;
  min-width: 0;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.documentsItem > :last-child {
  flex: 0 0 auto;
  margin-left: auto;
}
</style>
