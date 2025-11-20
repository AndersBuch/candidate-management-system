<script setup>
import { ref } from 'vue'
import Modal from '@/components/Modal.vue'

const showModal = ref(false)

const openModal = () => {
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
}
</script>

<template>
  <button @click="openModal">
    Åben denne her
  </button>

  <!-- Brug af modal-komponenten -->
  <Modal
    v-if="showModal"
    modalTitle="Min seje modal"
    titleAlign="center"
    @close="closeModal"
  >
    <!-- Indholdet herinde bliver vist i <slot> i din Modal.vue -->
    <p>
      Her er noget custom indhold i modal'en 🎉

    </p>
    <button @click="closeModal">
      Luk modal
    </button>
  </Modal>
</template>

<style lang="scss">
</style>
