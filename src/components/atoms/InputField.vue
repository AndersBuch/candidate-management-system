<script setup>
import { computed } from 'vue'

const props = defineProps({
  label: { type: String, default: 'Tekst' },
  checked: { type: Boolean, default: false },
  id: { type: String, default: null }
})

const emit = defineEmits(['update:checked'])

const uid = Math.random().toString(36).slice(2, 9)
const id = props.id || `inputboks-${uid}`

const isChecked = computed({
  get: () => props.checked,
  set: (val) => emit('update:checked', val)
})

function onCheck(e) {
  isChecked.value = e.target.checked
}
</script>


<template>
  <div class="inputBoks">
    <label :for="id" class="checkboxLabel">
      <!-- Custom styled checkbox -->
      <input type="checkbox" :id="id" :checked="isChecked" @change="onCheck($event)" :disabled="disabled" />
      <span class="labelText" :class="{ checked: isChecked }">
        <slot>{{ label }}</slot>
      </span>
      <!-- Optional: custom checkmark -->
      <span class="customCheckbox"></span>
    </label>
  </div>
</template>

<style scoped lang="scss">
.inputBoks {
  display: flex;
  gap: 0.5rem;
  align-items: center;
}

.checkboxLabel {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
  user-select: none;
  position: relative;
}

/* Skjul den originale checkbox */
.checkboxLabel input[type="checkbox"] {
  appearance: none;
  width: 24px;
  height: 24px;
  border: 2px solid $sekundareBlue;
  border-radius: 5px;
  position: relative;
  cursor: pointer;
  outline: none;
  transition: all 0.2s;
}

.checkboxLabel input[type="checkbox"]:checked {
  background-color: $primaryBlue;
  border-color: $primaryBlue;
}

.labelText {
  @include bodyText;
  color: $darkGrey;
  transition: color 0.2s;
}

.labelText.checked {
  color: $black;
}

.customCheckbox {
  position: absolute;
  width: 24px;
  height: 24px;
  pointer-events: none;
  top: 0;
  left: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}

.checkboxLabel input[type="checkbox"]:checked+.labelText+.customCheckbox::after {
  content: '✔';
  color: $whiteColor;
  font-size: 16px;
}
</style>
