<script setup>
import SearchBar from '@/components/dashboard/SearchBar.vue'
import AddPersonModal from '@/components/dashboard/AddPersonModal.vue'

import { storeToRefs } from 'pinia'
import { watch } from 'vue'
import { useCompanyStore } from '@/stores/useCompanyStore'
import { useSearchStore } from '@/stores/useSearchStore'

const companyStore = useCompanyStore()
const searchStore = useSearchStore()

const { searchTerm } = storeToRefs(searchStore)
const { activeCompany, activePosition } = storeToRefs(companyStore)

watch(
  () => companyStore.activePosition?.id,
  () => {
    searchStore.clearSearch()
  }
)
</script>

<template>
  <div v-if="activeCompany && activePosition" class="dashboardHeader">

    <SearchBar v-model="searchTerm" placeholder="Søg kandidat..." />

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
  flex-direction: column;
  gap: 5px;

  .titleRow {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
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