<script setup>
import { computed, ref, watch } from 'vue'

const props = defineProps({
  label: {
    type: String,
    required: true
  },
  type: {
    type: String,
    default: 'default'
  },
  placeholder: {
    type: String,
    default: ''
  },
  fieldType: {
    type: String,
    default: 'text'
  },
  modelValue: {  // <-- skift fra value til modelValue
    type: String,
    default: ''
  },
  id: {
    type: String,
    required: true
  }
})

const emit = defineEmits(['update:modelValue'])

// Lav en lokal kopivariabel
const localValue = ref(props.modelValue)

// Hold localValue og prop synkroniseret
watch(localValue, (val) => {
  emit('update:modelValue', val)
})
watch(() => props.modelValue, (val) => {
  localValue.value = val
})

const fieldClasses = computed(() => ({
  defaultField: props.type === 'default',
  primaryField: props.type === 'primary',
  secondaryField: props.type === 'secondary',
  dangerField: props.type === 'danger'
}))
</script>

<template>
  <div class="formGroup">
    <label :for="id">{{ label }}</label>
    <textarea 
      v-if="fieldType === 'textarea'" 
      :id="id" 
      :placeholder="placeholder" 
      v-model="localValue"
      :class="fieldClasses"
    ></textarea>
    <input 
      v-else 
      :type="fieldType" 
      :id="id" 
      :placeholder="placeholder" 
      v-model="localValue"
      :class="fieldClasses"
    />
  </div>
</template>


<style scoped lang="scss">
.formGroup {
  display: flex;
  flex-direction: column;
  margin-bottom: 1rem;

  label {
    margin-bottom: 0.5rem;
    font-weight: bold;
  }

  input, textarea {
    padding: 0.5rem;
    border-radius: 8px;
    font-size: 1rem;
    border: 1px solid #ccc;
    transition: border-color 0.2s;

    &:focus {
      border-color: $primaryBlue;
      outline: none;
    }
  }

  .defaultField {
    background-color: #fff;
    border-color: #ccc;
  }

  .primaryField {
    background-color: $primaryBlue;
    color: white;
    border: none;

    &:focus {
      border-color: darken($primaryBlue, 10%);
    }
  }

  .secondaryField {
    background-color: #f0f0f0;
    border-color: #aaa;
  }

  .dangerField {
    background-color: #ffe6e6;
    border-color: $dangerRed;
  }
}
</style>
