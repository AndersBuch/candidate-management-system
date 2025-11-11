<script setup>
import Header from '@/components/Header.vue'
import HeroSectionPageOne from '@/components/HeroSectionPageOne.vue'
import SectionTitle from '@/components/SectionTitle.vue'
import Button from '@/components/Button.vue'
import JobTable from './JobTable.vue'
</script>

<template>
  <Header />
  <HeroSectionPageOne />
  <main>
    <RouterLink to="/pagetwo">
      <Button label="Gå til Page Two" type="default" />
    </RouterLink>
    <SectionTitle title="Aktuelle stillinger" subtitle="102 åbne stillinger" />
    <JobTable />
  </main>
</template>


<style scoped lang="scss">

</style>
