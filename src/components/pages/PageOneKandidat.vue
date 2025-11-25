<script setup>
import Header from '@/components/organisms/Header.vue'
import HeroSectionPageOne from '@/components/organisms/HeroSectionPageOne.vue'
import SectionTitle from '@/components/atoms/SectionTitle.vue'
import Button from '@/components/atoms/Button.vue'
import JobTable from '@/components/organisms/JobTable.vue'
import Modal from '@/components/Modal.vue'
import InputField from '@/components/atoms/InputField.vue'

import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'

const showModal = ref(false)

const acceptedTerms = ref(false)

const router = useRouter()

function openPrivacyModal() {
  showModal.value = true
}

function closeModal() {
  showModal.value = false
  acceptedTerms.value = false
}

const acceptButtonType = computed(() =>
  acceptedTerms.value ? 'default' : 'notActive'
)

const acceptButtonDisabled = computed(() => !acceptedTerms.value)

function onAccept() {
  if (!acceptedTerms.value) return
  showModal.value = false
  router.push('/pagethree')
}

const jobTableRef = ref(null)

const scrollToJobs = () => {
  if (jobTableRef.value?.$el) {
    jobTableRef.value.$el.scrollIntoView({ behavior: 'smooth' })
  }
}
</script>

<template>
  <Header />
  <HeroSectionPageOne @scroll-to-jobs="scrollToJobs" />
  <main>

    <SectionTitle title="Aktuelle stillinger" subtitle="102 åbne stillinger" />
    <JobTable @open-privacy-modal="openPrivacyModal" ref="jobTableRef" />
    <Modal v-if="showModal" modalTitle="Privat politik" titleAlign="center" @close="showModal = false">
      <div class="centerContent">
        <div class="privacyPolicy">
          <div class="scrollInner">
            <p>
              Brænder du for at arbejde med procesudstyr og bidrage til udviklingen af fremtidens
              fødevaretekno-logi? Har du erfaring med at styre og overvåge produktionsprocesser – og lyst til at være en
              vigtig del af vores udviklingsteam? Så er du måske den procesoperatør, vi leder efter. Brænder du for at
              arbejde med procesudstyr og bidrage til udviklingen af fremtidens
              fødevaretekno-logi? Har du erfaring med at styre og overvåge produktionsprocesser – og lyst til at være en
              vigtig del af vores udviklingsteam? Så er du måske den procesoperatør, vi leder efter.Brænder du for at
              arbejde med procesudstyr og bidrage til udviklingen af fremtidens
              fødevaretekno-logi? Har du erfaring med at styre og overvåge produktionsprocesser – og lyst til at være en
              vigtig del af vores udviklingsteam? Så er du måske den procesoperatør, vi leder efter.fødevaretekno-logi?
              Brænder du for at arbejde med procesudstyr og bidrage til udviklingen af fremtidens
              fødevaretekno-logi? Har du erfaring med at styre og overvåge produktionsprocesser – og lyst til at være en
              vigtig del af vores udviklingsteam? Så er du måske den procesoperatør, vi leder efter. Brænder du for at
              arbejde med procesudstyr og bidrage til udviklingen af fremtidens
              fødevaretekno-logi? Har du erfaring med at styre og overvåge produktionsprocesser – og lyst til at være en
              vigtig del af vores udviklingsteam? Så er du måske den procesoperatør, vi leder efter.Brænder du for at
              arbejde med procesudstyr og bidrage til udviklingen af fremtidens
              fødevaretekno-logi? Har du erfaring med at styre og overvåge produktionsprocesser – og lyst til at være en
              vigtig del af vores udviklingsteam? Så er du måske den procesoperatør, vi leder efter.fødevaretekno-logi?
            </p>
          </div>
        </div>
        <InputField v-model:checked="acceptedTerms" label="Jeg accepterer betingelserne" />
        <Button label="Godkend" :type="acceptButtonType" :disabled="acceptButtonDisabled" aria-label="Godkend"
          @click="onAccept" />
        <Button label="Anuller" type="secondary" aria-label="Anuller" @click="closeModal" />
      </div>
    </Modal>
  </main>
</template>


<style scoped lang="scss">
.privacyPolicy {
  padding: 20px 40px;
  height: 380px;
  width: 695px;
  background-color: $lightGrey;
  border-radius: 5px;
  box-shadow: $privacyPolicyDropShadow;
  margin-bottom: 10px;

  .scrollInner {
    height: 100%;
    overflow-y: auto;
  }
}

.centerContent {
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  gap: 10px;
}
</style>
