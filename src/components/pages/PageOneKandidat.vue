<script setup>
import Header from '@/components/organisms/Header.vue'
import HeroSectionPageOne from '@/components/organisms/HeroSectionPageOne.vue'
import SectionTitle from '@/components/atoms/SectionTitle.vue'
import Button from '@/components/atoms/Button.vue'
import JobTable from '@/components/organisms/JobTable.vue'
import Modal from '@/components/Modal.vue'
import InputField from '@/components/atoms/InputField.vue'

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
    modalTitle="Privat politik" 
    @close="showModal = false">
      <div class="privacyPolicy">
        Brænder du for at arbejde med procesudstyr og bidrage til udviklingen af fremtidens 
        fødevaretekno-logi? Har du erfaring med at styre og overvåge produktionsprocesser – og lyst til at være en vigtig del af vores udviklingsteam? Så er du måske den procesoperatør, vi leder efter.  Brænder du for at arbejde med procesudstyr og bidrage til udviklingen af fremtidens 
        fødevaretekno-logi? Har du erfaring med at styre og overvåge produktionsprocesser – og lyst til at være en vigtig del af vores udviklingsteam? Så er du måske den procesoperatør, vi leder efter.Brænder du for at arbejde med procesudstyr og bidrage til udviklingen af fremtidens 
        fødevaretekno-logi? Har du erfaring med at styre og overvåge produktionsprocesser – og lyst til at være en vigtig del af vores udviklingsteam? Så er du måske den procesoperatør, vi leder efter.fødevaretekno-logi?
        Brænder du for at arbejde med procesudstyr og bidrage til udviklingen af fremtidens 
        fødevaretekno-logi? Har du erfaring med at styre og overvåge produktionsprocesser – og lyst til at være en vigtig del af vores udviklingsteam? Så er du måske den procesoperatør, vi leder efter.  Brænder du for at arbejde med procesudstyr og bidrage til udviklingen af fremtidens 
        fødevaretekno-logi? Har du erfaring med at styre og overvåge produktionsprocesser – og lyst til at være en vigtig del af vores udviklingsteam? Så er du måske den procesoperatør, vi leder efter.Brænder du for at arbejde med procesudstyr og bidrage til udviklingen af fremtidens 
        fødevaretekno-logi? Har du erfaring med at styre og overvåge produktionsprocesser – og lyst til at være en vigtig del af vores udviklingsteam? Så er du måske den procesoperatør, vi leder efter.fødevaretekno-logi?
      </div>
      <div class="centerContent">
        <InputField
        v-model:checked="acceptedTerms"
        label="Jeg accepterer betingelserne"
        />
        <Button label="Godkend" type="default" aria-label="Godkend" />
        <Button label="Anuller" type="default" aria-label="Anuller" />
      </div>
    </Modal>
  </main>
</template>


<style scoped lang="scss">

  .privacyPolicy {
    padding: 20px 40px;
    height: 380px;
    width: 100%;
    overflow-y: auto;
    background-color: $lightGrey;
    border-radius: 5px;
    margin-bottom: 20px;
    
  }

  .centerContent {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    gap: 20px;
  }

</style>
