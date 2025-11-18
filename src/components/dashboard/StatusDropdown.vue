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

    <!-- DROPDOWN MENU -->
    <div v-if="isOpen" class="dropdownMenu">
      <p class="title">Skift status</p>
      <div class="divider"></div>

      <button
        v-for="o in options"
        :key="o.label"
        class="dropdownItem"
        @click="selectOption(o)"
      >
        {{ o.label }}
      </button>
    </div>
  </div>
</template>



<style scoped lang="scss">
.dropdownWrapper {
  position: relative;
  display: inline-block;
}

.dropdownMenu {
  position: absolute;
  top: 45px;
  right: 0;
  width: 180px;
  background: white;
  border-radius: 12px;
  padding: 10px 0;
  box-shadow: 0 4px 15px rgba(0,0,0,0.12);
  z-index: 999;
}

.title {
  font-weight: 600;
  padding: 6px 14px;
}

.divider {
  border-bottom: 1px solid #ddd;
  margin-bottom: 5px;
}

.dropdownItem {
  width: 100%;
  padding: 8px 14px;
  text-align: left;
  background: none;
  border: none;
  cursor: pointer;

  &:hover {
    background: #f3f3f3;
  }
}
</style>
