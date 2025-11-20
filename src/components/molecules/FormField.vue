<script setup>
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'
import { ref, computed, watch } from 'vue'

const props = defineProps({
  label: { type: String, required: true },
  placeholder: { type: String, default: '' },
  fieldType: { type: String, default: 'text' },
  modelValue: { type: String, default: '' },
  id: { type: String, required: true },
  error: { type: Boolean, default: false },
  touched: { type: Boolean, default: false },
  errorMessage: { type: String, default: '' },
  showToggle: { type: Boolean, default: false }
})

const emit = defineEmits(['update:modelValue', 'blur', 'input'])

const localValue = ref(props.modelValue)
const showPassword = ref(false)

watch(() => props.modelValue, (newVal) => (localValue.value = newVal))
watch(localValue, (newVal) => emit('update:modelValue', newVal))

const hasValue = computed(() => String(localValue.value).trim().length > 0)
const isError = computed(() => props.error && props.touched)

function onStatusChange(payload) {
  // payload = { id, value } from dropdown
  emit('statusChange', { rowId: props.rowId, value: payload.value })
}

</script>


<template>
  <div class="formGroup">
    <label :for="id" :class="{ errorLabel: isError }">{{ label }}</label>
    
    <div class="inputWrapper">
      <!-- Hvis der er slot, brug den -->
      <slot v-if="$slots.default"></slot>

      <!-- Ellers default input -->
      <input
        v-else
        :type="showToggle ? (showPassword ? 'text' : 'password') : fieldType"
        :id="id"
        :placeholder="placeholder"
        v-model="localValue"
        @blur="emit('blur')"
        @input="emit('input', $event)"
        :class="{ errorField: isError, hasValue: hasValue }"
      />

      <!-- Eye / EyeOff ikon -->
      <button
        v-if="showToggle"
        type="button"
        class="eyeToggle"
        @click="showPassword = !showPassword"
      >
        <BasicIconAndLogo
          :name="showPassword ? 'Eye' : 'EyeOff'"
          :iconSize="true"
        />
      </button>
    </div>

    <p v-if="isError && errorMessage" class="errorMessage">{{ errorMessage }}</p>
  </div>
</template>


<style scoped lang="scss">

.formGroup {
  display: flex;
  flex-direction: column;
  margin-bottom: 20px;

  label {
    margin-bottom: 0.5rem;
    @include bigBodyText;
    color: $black;

    a {
      color: inherit;
      text-decoration: none;

      &:hover {
        text-decoration: underline;
      }
    }
  }

  input,
  textarea {
    padding: 0.5rem;
    border-radius: 5px;
    @include bodyText;
    border: 1px solid $sekundareBlue;
    background-color: $whiteColor;
    transition: border-color 0.2s, color 0.2s;

    &::placeholder {
      color: $darkGrey;
    }

    &:focus {
      border-color: $darkGrey;
      outline: none;
    }

    &.hasValue {
      border-color: $darkGrey;
      color: $black;
    }
  }

  .errorField {
    border-color: $dangerRed !important;
  }

  .errorMessage {
    color: $dangerRed;
    @include bodyText;
    margin-top: 0.3rem;
  }

  .errorLabel {
    color: $dangerRed;
  }
  
}

 .inputWrapper {
    position: relative;
    display: flex;
    align-items: center;
    overflow: visible;

    input {
      width: 100%;
      padding-right: 2.5rem; // plads til ikonet
    }

    .eyeToggle {
      position: absolute;
      right: 0.6rem;
      background: transparent;
      border: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
    }
  }

</style>
