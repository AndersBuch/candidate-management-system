<script setup>
import Header from '@/components/Header.vue'
import HeroSectionPageOne from '@/components/HeroSectionPageOne.vue'
import SectionTitle from '@/components/SectionTitle.vue'
import Button from '@/components/Button.vue'
import JobTable from './JobTable.vue'
import Modal from './Modal.vue'


import { ref } from 'vue'

const showModal = ref(false)


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
     <button @click="showModal = true">Åbn modal</button>
    <Modal
    v-if="showModal" 
    modalTitle="Hej verden" 
    @close="showModal = false">
      <div class="privacy-policy">
        Brænder du for at arbejde med procesudstyr og bidrage til udviklingen af fremtidens 
        fødevaretekno-logi? Har du erfaring med at styre og overvåge produktionsprocesser – og lyst til at være en vigtig del af vores udviklingsteam? Så er du måske den procesoperatør, vi leder efter.  Brænder du for at arbejde med procesudstyr og bidrage til udviklingen af fremtidens 
        fødevaretekno-logi? Har du erfaring med at styre og overvåge produktionsprocesser – og lyst til at være en vigtig del af vores udviklingsteam? Så er du måske den procesoperatør, vi leder efter.Brænder du for at arbejde med procesudstyr og bidrage til udviklingen af fremtidens 
        fødevaretekno-logi? Har du erfaring med at styre og overvåge produktionsprocesser – og lyst til at være en vigtig del af vores udviklingsteam? Så er du måske den procesoperatør, vi leder efter.fødevaretekno-logi?
        Brænder du for at arbejde med procesudstyr og bidrage til udviklingen af fremtidens 
        fødevaretekno-logi? Har du erfaring med at styre og overvåge produktionsprocesser – og lyst til at være en vigtig del af vores udviklingsteam? Så er du måske den procesoperatør, vi leder efter.  Brænder du for at arbejde med procesudstyr og bidrage til udviklingen af fremtidens 
        fødevaretekno-logi? Har du erfaring med at styre og overvåge produktionsprocesser – og lyst til at være en vigtig del af vores udviklingsteam? Så er du måske den procesoperatør, vi leder efter.Brænder du for at arbejde med procesudstyr og bidrage til udviklingen af fremtidens 
        fødevaretekno-logi? Har du erfaring med at styre og overvåge produktionsprocesser – og lyst til at være en vigtig del af vores udviklingsteam? Så er du måske den procesoperatør, vi leder efter.fødevaretekno-logi?
      </div>
    </Modal>
  </main>
</template>


<style scoped lang="scss">

  .privacy-policy {
    padding: 20px 40px;
    height: 380px;
    width: 100%;
    overflow-y: auto;
    background-color: $lightGrey;
    border-radius: 5px;
  }

</style>
