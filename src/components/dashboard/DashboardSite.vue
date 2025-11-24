<script setup>

import DashboardHeader from '@/components/dashboard/DashboardHeader.vue'
import SideMenu from '@/components/dashboard/SideMenu.vue'
import Tableform from '@/components/dashboard/Tableform.vue'
import ExtendedCandidateInfo from './ExtendedCandidateInfo.vue'
import SearchBar from "@/components/dashboard/SearchBar.vue"
import AddPersonModal from '@/components/dashboard/AddPersonModal.vue'
import EditModal from '@/components/dashboard/EditModal.vue'

import { ref } from 'vue'

const showExtendedInfo = ref(false)

const search = ref("")

// State til modal
const isModalOpen = ref(false)

function openModal() {
  isModalOpen.value = true
}

</script>

<template>
  <div class="dashboardLayout">
    <SideMenu />

      <section class="dashboardContentWrapper">
        <div class="dashboardContent">
          <DashboardHeader />
          <button @click="showExtendedInfo = !showExtendedInfo">
            {{ showExtendedInfo ? 'Skjul udvidet info' : 'Vis udvidet info' }}
          </button>
          <Tableform />
        </div>


        <!-- Modal -->
        <AddPersonModal />
        <EditModal />


      </section>

      <Transition name="slide-right">
        <ExtendedCandidateInfo v-if="showExtendedInfo" />
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

/* Når komponenten kommer ind */
.slide-right-enter-from {
  opacity: 0;
  transform: translateX(40px); /* Fra højre */

}


/* Når komponenten forsvinder */
.slide-right-leave-to {
  opacity: 0;
  transform: translateX(40px); /* Til højre */
}
</style>