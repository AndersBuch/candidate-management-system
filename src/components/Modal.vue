<script setup>
import Backdrop from '@/components/atoms/Backdrop.vue';
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'

const props = defineProps({
  modalTitle: { type: String, default: '' },
})

const emit = defineEmits(['close'])

</script>

<template>
  <Backdrop>
    <div class="modal">
      <div class="modalHeader">
        <h2>{{ modalTitle }}</h2> <BasicIconAndLogo name="X" :xxSmall="true" @click="emit('close')" class="closeIcon" />
      </div>
      <div  class="modalBody">
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
  box-shadow: $modalDropShadow;
  width: 650px;

.modalHeader {
  position: relative; // giver os mulighed for at placere ikonet absolut
  text-align: center; // centrerer teksten
  margin-bottom: 1rem;

  h2 {
    @include heading2;
    margin: 0;
  }

  .closeIcon {
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
.modalBody{
  @include bodyText;
}

}

</style>