<script setup>
import Button from '@/components/atoms/Button.vue'
import { ref, onMounted, onBeforeUnmount, computed } from 'vue'

const emit = defineEmits(['update:modelValue'])

const props = defineProps({
  modelValue: {
    type: String,
    required: true
  }
})

const isOpen = ref(false)

const options = [
  { label: 'Accepteret', value: 'Accepteret' },
  { label: 'Afventer', value: 'Afventer' },
  { label: 'Kontakt', value: 'Kontakt' },
  { label: 'Afvist', value: 'Afvist' }
]

function getLabelColor(value) {
  switch (value) {
    case 'Accepteret': return '#34C759'
    case 'Afventer': return '#FF8D28'
    case 'Kontakt': return '#CB30E0'
    case 'Afvist': return '#FF383C'
    default: return '#333'
  }
}

const buttonType = computed(() => {
  switch ((props.modelValue || '').toLowerCase()) {
    case 'accepteret': return 'accepted'
    case 'afventer': return 'pending'
    case 'kontakt': return 'contact'
    case 'afvist': return 'rejected'
    default: return 'default'
  }
})

function selectOption(opt) {
  emit('update:modelValue', opt.value)
  isOpen.value = false
}

const dropdownRef = ref(null)

function handleClickOutside(event) {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    isOpen.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>

<template>
  <div class="dropdownWrapper" ref="dropdownRef">
    <Button :label="modelValue" :type="buttonType" aria-label="Status knap" @click="isOpen = !isOpen" />

    <transition name="slide">
      <div v-if="isOpen" class="dropdownMenu">
        <p>Status</p>
        <div class="divider"></div>

        <button v-for="o in options" :key="o.label" class="dropdownItem" :style="{ color: getLabelColor(o.value) }"
          @click="selectOption(o)">
          {{ o.label }}
        </button>
      </div>
    </transition>
    
  </div>
</template>

<style scoped lang="scss">
.slide-enter-from,
.slide-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

.slide-enter-to,
.slide-leave-from {
  opacity: 1;
  transform: translateY(0);
}

.slide-enter-active,
.slide-leave-active {
  transition: all 0.22s ease;
}

.dropdownWrapper {
  position: relative;
  display: inline-block;
}

.dropdownMenu {
  position: absolute;
  top: 45px;
  right: -40px;
  width: 169px;
  background: $whiteColor;
  text-align: center;
  border-radius: 15px;
  padding: 10px 0;
  box-shadow: $boxShadow;
  z-index: 99999;

  p {
    @include bigBodyText;
    padding: 6px 20px;
  }
}

.divider {
  border-bottom: 2px solid $lightGrey;
  margin-bottom: 5px;
}

.dropdownItem {
  width: 100%;
  padding: 12px 14px;
  text-align: left;
  background: none;
  border: none;
  cursor: pointer;
  text-align: center;
  transition: 0.2s ease;
  @include boldBodyText;

  &:hover {
    background: $lightGrey;
  }
}
</style>
