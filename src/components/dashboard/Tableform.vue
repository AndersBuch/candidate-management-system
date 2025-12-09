<script setup>
import TableField from '@/components/dashboard/TableField.vue'
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'
import ToastDashboard from '@/components/dashboard/ToastDashboard.vue'
import EditModal from '@/components/dashboard/EditModal.vue'

import { ref, onMounted, computed, watch } from 'vue'

// 👉 IMPORT stores
import { useCandidateStore } from '@/stores/addCandidateStore'
import { useCompanyStore } from '@/stores/useCompanyStore'

// 👉 INITIALISER stores (skal ligge FØR watch)
const store = useCandidateStore()
const companyStore = useCompanyStore()

// 👉 Emit
const emit = defineEmits(['openCandidate'])

// 👉 Rækker (computed)
const rows = computed(() => {
  return store.candidates.map(c => ({
    id: c.id,
    name: c.first_name + " " + c.last_name,
    phone: c.phone_number,
    email: c.email,
    status: c.status,
    linkedin: c.linkedin_url
  }))
})

// 👉 Watch company + position
watch(
  () => companyStore.activePosition,
  (newPosition) => {
    const positionName = newPosition?.name || null
    store.fetchCandidates(positionName)
  },
  { immediate: true }
)


// 👉 Lokal state
const activeIndex = ref(null)
const toasts = ref([])
const showExtendedInfo = ref(false)

// 👉 Lifecycle

// 👉 Handlers
function onEdit(row) {
  console.log('📝 edit:', row.name)
}

function onStatusClick(row) {
  console.log('✅ status click:', row.status)
}

function setActiveRow(index) {
  activeIndex.value = activeIndex.value === index ? null : index
  emit('openCandidate', activeIndex.value)
}


function showToast() {
  const id = Date.now()
  toasts.value.push({
    id,
    title: 'Kandidat tilføjet',
    subtitle: 'Mads Mikkels Ole',
    variant: 'success',
    duration: 3000,
    showUndo: true
  })
}

function removeToast(id) {
  toasts.value = toasts.value.filter(t => t.id !== id)
}

function handleUndo(id) {
  console.log("Undo for:", id)
}
</script>


<template>
  <div class="tableHeader">
    <div class="headerItem">
      <p>Navn</p>
      <BasicIconAndLogo name="Shuffle" :iconSize="true" />
    </div>
    <div class="headerItem">
      <p>Tlf</p>
    </div>
    <div class="headerItem">
      <p>Email</p>
      <BasicIconAndLogo name="Shuffle" :iconSize="true" />
    </div>
    <div class="headerItem">
      <p>Status</p>
      <BasicIconAndLogo name="Shuffle" :iconSize="true" />
    </div>
  </div>

<TableField
  v-for="(r, i) in rows"
  :key="r.id"
  :id="r.id"
  :index="i"
  :name="r.name"
  :phone="r.phone"
  :email="r.email"
  :status="r.status"
  :linkedin-url="r.linkedin"
  :is-active="activeIndex === i"
  @rowClick="setActiveRow"
  @statusClick="onStatusClick(r)"
  @edit="onEdit(r)"
/>


</template>

<style lang="scss">
.tableHeader {
  display: grid;
  grid-template-columns: 2.8fr 1fr 3fr 1.2fr 0.8fr;
  gap: 12px;
  padding: 10px 20px;
}

.headerItem {
  display: flex;
  align-items: center;
  gap: 8px; // afstand mellem p og ikon
  cursor: pointer;

  p {
    margin: 0;
    @include bigBodyText;
    transition: color 0.2s ease, opacity 0.2s ease;
  }

  &:hover {
    color: rgba($black, 0.95);
    opacity: 0.8;
  }
}
</style>