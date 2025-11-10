<script setup>
import { ref } from 'vue'
import SectionTitle from '../components/SectionTitle.vue';

</script>

<template>
  <header>
    <div class="header-container">
      <h1>Mit logo</h1>
      <nav>
        <a href="#">Forside</a>
        <a href="#">Om</a>
        <a href="#">Kontakt</a>
      </nav>
    </div>
  </header>
  <SectionTitle title="Aktuelle Stillinger" subtitle="102 åbne stillinger"/>
</template>
<style lang="scss">
header {
  width: 100%;
  background-color: pink; /* baggrund helt ud */
  color: white;
  height: 100px;
}

/* "containeren" inde i headeren */
.header-container {
  max-width: 1450px;
  width: 800%;
  height: 100px;
  margin: 0 auto; /* centrerer */
  display: flex;
  align-items: center;
  justify-content: space-between;
}
</style>
