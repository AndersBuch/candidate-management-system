<script setup>
import Button from '@/components/atoms/Button.vue'
import { ref, onMounted, onBeforeUnmount } from 'vue'

const emit = defineEmits(["update:modelValue"])

const props = defineProps({
    modelValue: {
        type: String,
        required: true
    },
})

const isOpen = ref(false)

const options = [
  { label: "Accepteret", value: "Accepted" },
  { label: "Afventer", value: "Pending" },
  { label: "Kontakt", value: "Contact" },
  { label: "Afvist", value: "Rejected" }
]

function getLabelColor(value) {
  switch (value) {
    case 'Accepted': return '#34C759'; 
    case 'Pending':  return '#FF8D28'; 
    case 'Contact':  return '#CB30E0'; 
    case 'Rejected': return '#FF383C'; 
    default: return '#333';
  }
}


function selectOption(opt) {
    emit("update:modelValue", opt.value)
    isOpen.value = false
}

// REF til dropdown-wrapper
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
  <!-- STATUS BUTTON -->
  <Button 
    :label="modelValue"
    :type="modelValue.toLowerCase()"
    aria-label="Status knap"
    @click="isOpen = !isOpen"
  />

  <!-- DROPDOWN MENU MED TRANSITION -->
  <transition name="slide">
    <div v-if="isOpen" class="dropdownMenu">
      <p>Status</p>
      <div class="divider"></div>

<button
  v-for="o in options"
  :key="o.label"
  class="dropdownItem"
  :style="{ color: getLabelColor(o.value) }"
  @click="selectOption(o)"
>
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
  box-shadow: 0 4px 15px rgba(0,0,0,0.12);
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
