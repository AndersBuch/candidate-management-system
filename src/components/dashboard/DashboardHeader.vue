<script setup>
  import { storeToRefs } from 'pinia'
  import { useCompanyStore } from '@/stores/useCompanyStore'
  const companyStore = useCompanyStore()
  const { activeCompany, activePosition } = storeToRefs(companyStore)
</script>

<template>
    <div v-if="activeCompany && activePosition" class="dashboardHeader">
      <h1 class="companyTitle">{{ activeCompany.name }}</h1>
      <p class="positionTitle">{{ activePosition.name }}</p>
      <p class="applicationId">ID:<span class="idSpan">{{ activePosition.applicationId }}</span></p>
    </div>
</template>

<style scoped lang="scss">
.dashboardHeader {
  padding: 20px 28px;
  border-bottom: 2px solid $lightGrey;
  margin-bottom: 10px;

  .companyTitle {
    @include heading3;
  }

  .positionTitle {
    color: $primaryBlue;
    @include bigBodyText;
  }

  .applicationId {
    @include boldBodyText;
    color: $black;
    
  }
  .idSpan {
    padding-left: 4px;
    color: $darkGrey;
  }
}
</style>