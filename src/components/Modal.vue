<script setup>
import { watch } from 'vue'
import Backdrop from '@/components/atoms/Backdrop.vue';
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'

// Props
const props = defineProps({
  modelValue: { type: Boolean, default: false },
  modalTitle: { type: String, default: '' },
  titleAlign: { type: String, default: 'left' }
})

const emit = defineEmits(['update:modelValue', 'close'])

// Lås scroll
const lockScroll = () => {
  const body = document.body
  const scrollBarWidth = window.innerWidth - document.documentElement.clientWidth
  body.classList.add('no-scroll')
  if (scrollBarWidth > 0) body.style.paddingRight = `${scrollBarWidth}px`
}

// Frigiv scroll
const unlockScroll = () => {
  const body = document.body
  body.classList.remove('no-scroll')
  body.style.paddingRight = ''
}

// Watch v-model
watch(() => props.modelValue, (val) => {
  if (val) lockScroll()
  else unlockScroll()
})

// Luk modal
function close() {
  emit('update:modelValue', false)
  emit('close')
}
</script>

<template>
  <Backdrop v-if="props.modelValue">
    <div class="modal">
      <div class="modalHeader" :style="{ textAlign: props.titleAlign }">
        <h2>{{ props.modalTitle }}</h2>
        <BasicIconAndLogo name="X" :xxSmall="true" @click="close" class="closeIcon" />
      </div>
      <div class="modalBody">
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