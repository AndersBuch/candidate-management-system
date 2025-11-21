<script setup>
import { onMounted, onUnmounted } from 'vue'
import Backdrop from '@/components/atoms/Backdrop.vue';
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'

const props = defineProps({
  modalTitle: { type: String, default: '' },
  titleAlign: { type: String, default: 'left' },
  height: { type: String, default: 'auto' }
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
    <div class="modal" :style="{ height: props.height, maxHeight: '90vh' }">

      <div class="modalHeader" :style="{ textAlign: props.titleAlign }">
        <h2>{{ modalTitle }}</h2>
        <BasicIconAndLogo name="X" :xxSmall="true" @click="emit('close')" class="closeIcon" />
      </div>
      <div class="modalBody">
        <slot></slot>
      </div>
    </div>
  </Backdrop>
</template>

<style scoped lang="scss">
.modal {
  display: flex;
  flex-direction: column;
  padding: 40px 80px 40px;
  border-radius: 15px;
  background-color: $whiteColor;
  color: $black;
  box-shadow: $modalDropShadow;

  .modalHeader {
    position: relative; 
    text-align: center; 
    margin-bottom: 30px;

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

  .modalBody {
    @include bodyText;
    flex: 1;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: transparent transparent;

    &::-webkit-scrollbar {
      width: 0px;
      background: transparent;
    }
  }

}
</style>