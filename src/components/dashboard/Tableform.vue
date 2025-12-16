<script setup>
import { ref, computed } from 'vue'
import TableField from '@/components/dashboard/TableField.vue'
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'
import EditModal from '@/components/dashboard/EditModal.vue'
import Toast from '@/components/dashboard/ToastDashboard.vue'
import { useCompanyStore } from '@/stores/useCompanyStore'

const selectedCandidate = ref(null)
const showEditModal = ref(false)

const props = defineProps({
  activeIndex: {
    type: [Number, null],
    default: null
  }
})

const emit = defineEmits(['openCandidate'])

const companyStore = useCompanyStore()

// Rækker = alle kandidater til den aktive stilling
const rows = computed(() => companyStore.activeCandidates)


function onEdit(row) {
  selectedCandidate.value = row
  showEditModal.value = true
}

function onStatusClick(row) {
  console.log('✅ status click:', row.status)
}

function setActiveRow(index) {
  emit('openCandidate', index)
}


const toasts = ref([])

function showToast() {
  toasts.value.push({
    id: Date.now(),
    title: 'Kandidat opdateret',
    subtitle: 'Ændringerne blev gemt korrekt',
    variant: 'success',
    duration: 3000,
    showUndo: false
  })
}

function removeToast(id) {
  toasts.value = toasts.value.filter(t => t.id !== id)
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

  <div class="tableFormContainer">
    <TableField
      v-for="(r, i) in rows"
      :key="r.id ?? i"
      :id="r.applicationId"
      :index="i"
      :candidate-id="r.id"
      :application-id="r.applicationId"
      :name="r.name"
      :phone="r.phone"
      :email="r.email"
      :status="r.status"
      :linkedin-url="r.linkedin"
      :is-active="props.activeIndex === i"
      @rowClick="setActiveRow"
      @edit="() => onEdit(r)"
    />
  </div>

  <EditModal
    v-if="showEditModal"
    :candidate="selectedCandidate"
    @close="showEditModal = false"
    @saved="showToast"
  />

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

.tableHeader {
  display: grid;
  grid-template-columns: 2.8fr 1fr 3fr 1.2fr 0.8fr;
  gap: 12px;
  padding: 10px 20px;
  flex-shrink: 0;
}

.headerItem {
  display: flex;
  align-items: center;
  gap: 8px;
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

.tableFormContainer {
  flex: 1;
  overflow-y: auto;
  overflow-x: hidden;
  min-height: 0;
  scrollbar-width: none;
  -ms-overflow-style: none;

  &::-webkit-scrollbar {
    display: none;
  }
}
</style>
