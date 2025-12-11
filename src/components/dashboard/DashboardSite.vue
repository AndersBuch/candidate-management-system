<script setup>
import DashboardHeader from '@/components/dashboard/DashboardHeader.vue'
import SideMenu from '@/components/dashboard/SideMenu.vue'
import Tableform from '@/components/dashboard/Tableform.vue'
import ExtendedCandidateInfo from '@/components/dashboard/ExtendedCandidateInfo.vue'
import Fromi from '@/components/atoms/ConfimationForm.vue'

import { ref, computed } from 'vue'
import { useCompanyStore } from '@/stores/useCompanyStore'

const showExtendedInfo = ref(false)
const activeIndex = ref(null)

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

function handleOpenCandidate(index) {
  activeIndex.value = index
  showExtendedInfo.value = index !== null
}
</script>

<template>
  <div class="dashboardLayout">
    <SideMenu />

    <section class="dashboardContentWrapper">
      <div class="dashboardContent">
        <DashboardHeader />
        <Tableform @openCandidate="handleOpenCandidate" />
        <Fromi />
      </div>
    </section>

    <Transition name="slide-right">
      <ExtendedCandidateInfo
        v-if="showExtendedInfo && extendedCandidate"
        :active-index="activeIndex"
        :candidate="extendedCandidate"
      />
    </Transition>
  </div>
</template>

<style scoped lang="scss">
.dashboardLayout {
  display: flex;
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
