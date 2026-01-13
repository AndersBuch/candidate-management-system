<script setup>
import Modal from '@/components/Modal.vue'
import Button from '@/components/atoms/Button.vue'
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'
import { ref, watch } from 'vue'

const showModal = ref(false)

const openModal = () => {
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
}

// Automatisk lukning med 3 sekunders delay
watch(showModal, (newVal) => {
  if (newVal) {
    setTimeout(() => {
      showModal.value = false
    }, 3000)
  }
})

const thashModal = () => {
  openModal()
}
</script>

<template>
  <BasicIconAndLogo name="Thash" :iconSize="true" @click.stop="thashModal" class="iconBtn" />

  <transition name="fade">
    <Modal v-if="showModal" @close="closeModal" height="250px" class="deleteCandidateModal">
      <div class="kandidateModal">
        <BasicIconAndLogo name="CheckCircle" :exstraSmall="true" />
        <p>Tak for din ansøgning</p>
        <p>Vi vil vende tilbage til dig hurtigst muligt</p>
      </div>
    </Modal>
  </transition>
</template>

<style lang="scss">
.deleteCandidateModal .closeIcon {
  display: none !important;
}

.iconBtn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: transform 0.2s ease, opacity 0.2s ease;

  &:hover {
    transform: scale(1.1);
    opacity: 0.9;
  }
}

.kandidateModal {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  gap: 5px;
}

.kandidateModal p:first-of-type {
  @include bigBodyText;
}

.kandidateModal p:last-of-type {
  @include bodyText;
}

/* Fade transition */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease-out, transform 0.5s ease-out;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

.fade-enter-to,
.fade-leave-from {
  opacity: 1;
  transform: translateY(0);
}
</style>
