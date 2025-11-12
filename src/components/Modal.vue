<script setup>
import Backdrop from './Backdrop.vue';
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'

const props = defineProps({
  modalTitle: { type: String, default: '' },
})
</script>

<template>
  <Backdrop>
    <div class="modal">
      <div class="modal-header">
        <h1>{{ modalTitle }}</h1> <BasicIconAndLogo name="X" :xxSmall="true" />
      </div>
    </div>
  </Backdrop>
</template>

<style scoped lang="scss">

.modal {
  padding: 20px;
  border-radius: 15px;
  background-color: $whiteColor;
  color: $black;
}

</style>