<script setup>
import Backdrop from './Backdrop.vue';
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'

const props = defineProps({
  modalTitle: { type: String, default: '' },
})

const emit = defineEmits(['close'])

</script>

<template>
  <Backdrop>
    <div class="modal">
      <div class="modal-header">
        <h2>{{ modalTitle }}</h2> <BasicIconAndLogo name="X" :xxSmall="true" @click="emit('close')" class="close-icon" />
      </div>
      <div  class="modal-body">
        <slot></slot>
      </div>
    </div>
  </Backdrop>
</template>

<style scoped lang="scss">

.modal {
  padding: 40px;
  border-radius: 15px;
  background-color: $whiteColor;
  color: $black;
  width: 800px;
  height: 650px;

.modal-header {
  position: relative; // giver os mulighed for at placere ikonet absolut
  text-align: center; // centrerer teksten
  margin-bottom: 1rem;

  h2 {
    @include heading2;
    margin: 0;
  }

  .close-icon {
    position: absolute;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    transition: opacity 0.2s;

    &:hover {
      opacity: 0.7;
    }
  }
}
.modal-body{
  @include bodyText;
}

}

</style>