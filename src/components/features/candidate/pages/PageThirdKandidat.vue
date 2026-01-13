<script setup>
import Header from '@/components/organisms/Header.vue'
import HeroSectionPageThird from '@/components/organisms/HeroSectionPageThird.vue'
import Form from '@/components/organisms/FormComponent.vue'
</script>

<template>
  <Header />
  <HeroSectionPageThird />
  <main>

    <div class="formSection">
      <h2>Formular</h2>
      <Form />
    </div>

  </main>
</template>

<style scoped lang="scss">
.formSection {

  h2 {
    text-align: center;
    @include heading2;
    margin-bottom: 40px;
    margin-top: 65px;
  }

}
</style>
