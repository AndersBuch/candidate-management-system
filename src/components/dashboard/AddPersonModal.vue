<script setup>
import { ref } from 'vue'
import Modal from '@/components/Modal.vue'  // din modal

const isModalOpen = ref(false)

function openModal() {
  isModalOpen.value = true
}

function closeModal() {
  isModalOpen.value = false
}
</script>

<template>
  <div>

    <!-- Modal -->
    <Modal 
      v-if="isModalOpen" 
      modalTitle="Hej! Dette er en test" 
      titleAlign="center" 
      @close="closeModal"
    >
      <p>Her er lidt indhold i modalen.</p>
      <p>Du kan putte hvad du vil her!</p>
    </Modal>
  </div>
</template>

<style lang="scss">
</style>
