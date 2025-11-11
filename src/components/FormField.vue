<script setup>
import { computed, ref, watch } from 'vue'

const props = defineProps({
  label: { type: String, required: true },
  placeholder: { type: String, default: '' },
  fieldType: { type: String, default: 'text' },
  modelValue: { type: String, default: '' },
  id: { type: String, required: true },
  error: { type: Boolean, default: false },
  touched: { type: Boolean, default: false },
  errorMessage: { type: String, default: '' },
  link: { type: String, default: '' } // <-- Ny prop til link
})

const emit = defineEmits(['update:modelValue', 'blur', 'input'])
const localValue = ref(props.modelValue)

watch(localValue, (val) => emit('update:modelValue', val))
watch(() => props.modelValue, (val) => (localValue.value = val))

const hasValue = computed(() => String(localValue.value).trim().length > 0)
const isError = computed(() => props.error && props.touched)
</script>

<template>
  <div class="formGroup">
    <!-- Hvis der er et link, render <a> i label -->
    <label :for="id" :class="{ errorLabel: isError }">
      <template v-if="link">
        <a :href="link" target="_blank" rel="noopener noreferrer">{{ label }}</a>
      </template>
      <template v-else>{{ label }}</template>
    </label>

    <textarea
      v-if="fieldType === 'textarea'"
      :id="id"
      :placeholder="placeholder"
      v-model="localValue"
      @blur="emit('blur')"
      @input="emit('input', $event)"
      :class="{ errorField: isError, hasValue: hasValue }"
    ></textarea>

    <input
      v-else
      :type="fieldType"
      :id="id"
      :placeholder="placeholder"
      v-model="localValue"
      @blur="emit('blur')"
      @input="emit('input', $event)"
      :class="{ errorField: isError, hasValue: hasValue }"
    />

<p v-if="isError && errorMessage" class="errorMessage">{{ errorMessage }}</p>
  </div>
</template>

<style scoped lang="scss">
.formGroup {
  display: flex;
  flex-direction: column;
  margin-bottom: 1rem;

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
    font-size: 0.9rem;
    margin-top: 0.3rem;
  }

  .errorLabel {
    color: $dangerRed;
  }
}
</style>
