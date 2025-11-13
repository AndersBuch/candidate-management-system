<script setup>
import { onMounted, onUnmounted } from 'vue'
import Backdrop from '@/components/atoms/Backdrop.vue';
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'

const props = defineProps({
  modalTitle: { type: String, default: '' },
  titleAlign: { type: String, default: 'left' } // "center" | "left"
})

const emit = defineEmits(['close'])

const lockScroll = () => {
  if (typeof document === 'undefined') return

  const body = document.body
  const scrollBarWidth = window.innerWidth - document.documentElement.clientWidth

  // lås scroll
  body.classList.add('no-scroll')
  // kompensér for den forsvundne scrollbar så siden ikke “hopper”
  if (scrollBarWidth > 0) {
    body.style.paddingRight = `${scrollBarWidth}px`
  }
}

const unlockScroll = () => {
  if (typeof document === 'undefined') return

  const body = document.body
  body.classList.remove('no-scroll')
  body.style.paddingRight = ''
}

onMounted(() => {
  lockScroll()
})

onUnmounted(() => {
  unlockScroll()
})

</script>

<template>
  <Backdrop>
    <div class="modal">
      <div class="modalHeader" :style="{ textAlign: props.titleAlign }">
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