<script setup>

import DashboardHeader from '@/components/dashboard/DashboardHeader.vue'
import SideMenu from '@/components/dashboard/SideMenu.vue'
import Tableform from '@/components/dashboard/Tableform.vue'
import ExtendedCandidateInfo from './ExtendedCandidateInfo.vue'


</script>

<template>
  <div class="dashboardLayout">
    <SideMenu />

      <section class="dashboardContentWrapper">
        <div class="dashboardContent">
          <DashboardHeader />
          <Tableform />
        </div>

        
      </section>

    <ExtendedCandidateInfo />
  </div>
</template>

<style scoped lang="scss">
.dashboardLayout {
  display: flex;


}


.dashboardContentWrapper {
  overflow-y: auto;
  flex: 1;
  max-height: 1080px;
}
</style>