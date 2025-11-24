<script setup>

import DashboardHeader from '@/components/dashboard/DashboardHeader.vue'
import SideMenu from '@/components/dashboard/SideMenu.vue'
import Tableform from '@/components/dashboard/Tableform.vue'
import ExtendedCandidateInfo from './ExtendedCandidateInfo.vue'

import { ref } from 'vue'
const showExtendedInfo = ref(false)

import SideMenu from '@/components/dashboard/SideMenu.vue'
import SearchBar from "@/components/dashboard/SearchBar.vue"
import Tableform from '@/components/dashboard/Tableform.vue'
import AddPersonModal from '@/components/dashboard/AddPersonModal.vue'
import EditModal from '@/components/dashboard/EditModal.vue'

import { storeToRefs } from 'pinia'
import { ref } from "vue"
import { useCompanyStore } from '@/stores/useCompanyStore'

const search = ref("")
const companyStore = useCompanyStore()

const { activeCompany, activePosition } = storeToRefs(companyStore)

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
          <div v-if="activeCompany && activePosition" class="dashboardHeader">
            <SearchBar v-model="search" placeholder="Søg..." />
            <h1 class="companyTitle">{{ activeCompany.name }}</h1>
            <p class="positionTitle">{{ activePosition.name }}</p>
          </div>
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

.dashboardHeader {
  background: #ffffff;
  padding: 20px 28px;
  border-bottom: 2px solid #e5e7eb;
  margin-bottom: 10px;

  .companyTitle {
    margin: 0;
    font-size: 22px;
    font-weight: 600;
    color: #222;
  }

  .positionTitle {
    margin-top: 6px;
    font-size: 15px;
    color: #3a75ff;
    font-weight: 500;
  }
}

/* Når komponenten forsvinder */
.slide-right-leave-to {
  opacity: 0;
  transform: translateX(40px); /* Til højre */
}
</style>