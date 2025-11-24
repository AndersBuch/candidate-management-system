<script setup>
import DashboardHeader from '@/components/dashboard/DashboardHeader.vue'
import SideMenu from '@/components/dashboard/SideMenu.vue'
import Tableform from '@/components/dashboard/Tableform.vue'
import ExtendedCandidateInfo from '@/components/dashboard/ExtendedCandidateInfo.vue'

import { ref } from 'vue'

const showExtendedInfo = ref(false)
const activeIndex = ref(null)
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
      </div>
    </section>

    <Transition name="slide-right">
      <ExtendedCandidateInfo v-if="showExtendedInfo" :active-index="activeIndex" />
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