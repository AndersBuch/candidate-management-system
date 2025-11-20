<script setup>
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

  </div>
</template>

<style scoped lang="scss">
.dashboardLayout {
  display: flex;

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

.dashboardContentWrapper {
  overflow-y: auto;
  flex: 1;
  max-height: 1080px;
}
</style>