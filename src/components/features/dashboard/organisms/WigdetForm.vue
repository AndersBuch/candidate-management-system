<script setup>
import { onMounted } from 'vue'
import { useCandidateStatsStore } from '@/stores/useCandidateStatsStore.js'
import Wigdet from '@/components/dashboard/Wigdet.vue'

const candidateStatsStore = useCandidateStatsStore()

onMounted(() => {
  candidateStatsStore.init()
})

const handleEdit = () => {
  console.log('Klikket')
}
</script>

<template>
  <div class="wigdetFrom">
    <h3>Dine kandidate oprettelser:</h3>
    <div class="wigdetRow">
      <Wigdet icon="UserWhite" title="Kandidater" subtitle="Antallet af alle kandidater"
        :count="candidateStatsStore.totalCandidates" :forceNeutral="true" />
      <Wigdet icon="UserWhite" title="Kandidater oprettet" subtitle="Antallet fra den seneste måned"
        :count="candidateStatsStore.recentCandidates" />
      <Wigdet icon="UserWhite" title="Kandidater slettet" subtitle="Antallet fra den seneste uge" />
    </div>
  </div>
</template>

<style scoped lang="scss">
.wigdetFrom {
  width: 1264px;
  background-color: $sekundareBlue;
  padding: 20px;
  border-radius: 15px;

  display: flex;
  flex-direction: column;
  gap: 20px;

  h3 {
    @include heading3;
    margin: 0;
  }

  .wigdetRow {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
  }
}
</style>