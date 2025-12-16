<script setup>
import DashboardHeader from '@/components/dashboard/DashboardHeader.vue'
import SideMenu from '@/components/dashboard/SideMenu.vue'
import Tableform from '@/components/dashboard/Tableform.vue'
import ExtendedCandidateInfo from '@/components/dashboard/ExtendedCandidateInfo.vue'
import Fromi from '@/components/atoms/ConfimationForm.vue'

import Toast from '@/components/dashboard/ToastDashboard.vue'

import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import { useCompanyStore } from '@/stores/useCompanyStore'

const showExtendedInfo = ref(false)
const activeIndex = ref(null)
const toasts = ref([])

// kandidater fra companyStore
const companyStore = useCompanyStore()
const candidates = computed(() => companyStore.activeCandidates)

// Den rå kandidat ud fra index'et
const selectedCandidateRow = computed(() => {
  if (activeIndex.value === null) return null
  return candidates.value[activeIndex.value] ?? null
})

// Map til ExtendedCandidateInfo's forventede struktur
const extendedCandidate = computed(() => {
  const row = selectedCandidateRow.value
  if (!row) return null

  const firstName = row.first_name ?? (row.name?.split(' ')[0] ?? '')
  const lastName =
    row.last_name ??
    (row.name ? row.name.split(' ').slice(1).join(' ') : '')

  return {
    id: row.id,
    applicationId: row.applicationId,
    firstName,
    lastName,
    age: row.age ?? '',
    gender: row.gender ?? '',
    phone: row.phone ?? row.phone_number,
    status: row.status,
    email: row.email,
    address: row.address ?? '',
    postal: row.zip_code ?? '',
    city: row.city ?? '',
    company: row.current_position ?? '',
    note: row.note ?? '',
    profilePicture: '/img/TestProfilePicture.jpg',
    linkedin: row.linkedin ?? row.linkedin_url ?? ''
  }
})

const dashboardRef = ref(null)
const extendedRef = ref(null)

function openCandidate(index) {
  activeIndex.value =
    activeIndex.value === index ? null : index
}

function handleClickOutside(event) {
  const clickedDashboard =
    dashboardRef.value?.contains(event.target)

  const clickedExtended =
    extendedRef.value?.rootRef?.value?.contains(event.target)

  if (!clickedDashboard && !clickedExtended) {
    activeIndex.value = null
  }
}

const pendingDeleteId = ref(null)
const deleteTimer = ref(null)

function requestDelete(candidateId) {
  pendingDeleteId.value = candidateId

  // 🔥 Fjern kandidat fra UI med det samme
  companyStore.hideCandidate(candidateId)

  activeIndex.value = null

  toasts.value.push({
    id: Date.now(),
    title: 'Kandidat slettet',
    subtitle: 'Du kan fortryde handlingen',
    variant: 'danger',
    duration: 3000,
    showUndo: true
  })

  deleteTimer.value = setTimeout(async () => {
    await companyStore.deleteCandidate(candidateId)
    pendingDeleteId.value = null
  }, 3000)
}

function undoDelete() {
  if (!pendingDeleteId.value) return

  clearTimeout(deleteTimer.value)
  deleteTimer.value = null

  companyStore.restoreCandidate(pendingDeleteId.value)

  pendingDeleteId.value = null
}


function handleCandidateDeleted() {
  activeIndex.value = null
  toasts.value.push({
    id: Date.now(),
    title: 'Kandidat slettet',
    subtitle: 'Kandidaten blev fjernet korrekt',
    variant: 'danger',
    duration: 3000
  })
}

function removeToast(id) {
  toasts.value = toasts.value.filter(t => t.id !== id)
}


onMounted(() => {
  document.addEventListener('pointerdown', handleClickOutside)
})

onBeforeUnmount(() => {
  document.removeEventListener('pointerdown', handleClickOutside)
})

</script>

<template>
  <div class="dashboardLayout">
    <SideMenu />

    <section ref="dashboardRef" class="dashboardContentWrapper">
      <DashboardHeader />
      <Tableform
  :active-index="activeIndex"
  @openCandidate="openCandidate"
/>
    </section>

<Transition name="slide-right">
  <ExtendedCandidateInfo
    v-if="extendedCandidate"
    ref="extendedRef"
    :candidate="extendedCandidate"
    @candidateDeleted="handleCandidateDeleted"
    @deleteRequested="requestDelete"
  />
</Transition>

<div class="toastWrapper">
  <Toast v-for="t in toasts" :key="t.id" v-bind="t" @close="removeToast" @undo="undoDelete" />
</div>

  </div>
</template>


<style scoped lang="scss">
.dashboardLayout {
  display: flex;
}

.dashboardContentWrapper {
  flex: 1;
}

.toastWrapper {
  position: fixed;
  bottom: 20px;
  left: 20px;
  display: flex;
  flex-direction: column;
  gap: 10px;
  z-index: 9999;
}

.dashboardContentWrapper {
  flex: 1;
}

.slide-right-enter-active,
.slide-right-leave-active {
  transition: all 0.2s ease;
}

.slide-right-enter-from {
  opacity: 0;
  transform: translateX(40px);
}

.slide-right-leave-to {
  opacity: 0;
  transform: translateX(40px);
}
</style>
