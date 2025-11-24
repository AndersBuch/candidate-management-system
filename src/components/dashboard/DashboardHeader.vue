<script setup>
import SearchBar from '@/components/dashboard/SearchBar.vue'
import Button from '@/components/atoms/Button.vue'
import AddPersonModal from '@/components/dashboard/AddPersonModal.vue'

import { storeToRefs } from 'pinia'
import { useCompanyStore } from '@/stores/useCompanyStore'


const companyStore = useCompanyStore()

const { activeCompany, activePosition } = storeToRefs(companyStore)
</script>

<template>
  <div v-if="activeCompany && activePosition" class="dashboardHeader">

    <SearchBar v-model="search" placeholder="Søg..." />

    <h1 class="companyTitle">{{ activeCompany.name }}</h1>

    <div class="titleRow">
      <p class="positionTitle">{{ activePosition.name }}</p>

  <AddPersonModal />
      
    </div>
    <p class="applicationId">
      ID:<span class="idSpan">{{ activePosition.applicationId }}</span>
    </p>

  </div>
</template>

<style scoped lang="scss">
.dashboardHeader {
  padding: 20px 28px;
  border-bottom: 2px solid $lightGrey;
  margin-bottom: 10px;

  display: flex;
  flex-direction: column;  // ← vigtig, så alt stacker korrekt
  gap: 5px;

  .titleRow {
    display: flex;
    align-items: center;
    justify-content: space-between; // ← placerer knappen til højre for positionTitle
    width: 100%;
    z-index: 999;
  }

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