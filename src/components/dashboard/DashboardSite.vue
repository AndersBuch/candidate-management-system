<script setup>
import { storeToRefs } from 'pinia'
import { useCompanyStore } from '@/stores/useCompanyStore'

import SideMenu from '@/components/dashboard/SideMenu.vue'
import Tableform from '@/components/dashboard/Tableform.vue' // ← din ven laver denne

const companyStore = useCompanyStore()
const { activeCompany, activePosition } = storeToRefs(companyStore)
</script>

<template>
  <div class="dashboardLayout">
    <SideMenu />

      <section class="dashboardContentWrapper">
        <div class="dashboardContent">
          <div v-if="activeCompany && activePosition" class="dashboardHeader">
            <h1 class="companyTitle">{{ activeCompany.name }}</h1>
            <p class="positionTitle">{{ activePosition.name }}</p>
          </div>
          <Tableform />
        </div>

        
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