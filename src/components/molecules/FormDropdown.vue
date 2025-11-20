<script setup>
import { ref, onMounted, onBeforeUnmount, watch } from 'vue'
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'

const props = defineProps({
  modelValue: { type: String, default: 'Kontaktes' },
  options: { type: Array, default: () => ['Kontaktes', 'Afventer', 'Acceptet', 'Afvist'] },
  id: { type: [String, Number], default: null }
})

const emit = defineEmits(['update:modelValue', 'change'])

const open = ref(false)
const selected = ref(props.modelValue || 'Kontaktes') // <-- default

watch(() => props.modelValue, v => {
  selected.value = v || 'Kontaktes'  // opdater også default ved ændringer
})


function toggle() { open.value = !open.value }
function close() { open.value = false }

function select(option) {
  selected.value = option
  emit('update:modelValue', option)
  emit('change', { id: props.id, value: option })
  close()
}

// click outside handler
function onDocClick(e) {
  const el = document.querySelector(`#statusDropdown-${props.id}`)
  if (!el) return
  if (!el.contains(e.target)) close()
}

onMounted(() => document.addEventListener('click', onDocClick))
onBeforeUnmount(() => document.removeEventListener('click', onDocClick))
</script>

<template>
  <div class="formGroup">
    <label :for="`statusDropdown-${id}`">{{ 'Status' }}</label>
    <div class="inputWrapper">
      <div class="statusDropdown" :id="`statusDropdown-${id}`">
        <button type="button" class="fdButton" @click="toggle" :aria-expanded="open">
          <span class="sd__label">{{ selected }}</span>
          <BasicIconAndLogo :name="'ArrowBlue'" :class="{ rotated: open }" />
        </button>

        <ul v-if="open" class="fdList" role="menu">
          <li v-for="opt in options" :key="opt" role="menuitem">
            <button type="button" class="sd__item" @click="select(opt)">
              {{ opt }}
            </button>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<style scoped lang="scss">

.formGroup {
  display: flex;
  flex-direction: column;

  label {
    @include bigBodyText;
    color: $black;
    margin-bottom: 0.5rem;
  }
}

.inputWrapper {
  position: relative;
  align-items: center;
  overflow: visible;

  .fdButton {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.5rem;
    border-radius: 5px;
    @include bodyText;
    border: 1px solid $sekundareBlue;
    background-color: $whiteColor;
    transition: border-color 0.2s, color 0.2s;
    cursor: pointer;
    box-sizing: border-box; 
    margin: 0;

    &:hover {
      border-color: $darkGrey;
      background-color: #f9f9f9;
    }
  }

  /* dropdown list */
  .fdList {
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    width: 100%;
    background-color: $whiteColor;
    border: 1px solid $sekundareBlue;
    border-radius: 5px;
    z-index: 1200;
    @include bodyText;
    padding: 4px 0;
    box-sizing: border-box;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  }

  /* dropdown items */
  .sd__item {
    width: 100%;
    text-align: left;
    padding: 8px 0.5rem;
    background: transparent;
    border: none;
    @include bodyText;
    cursor: pointer;
    transition: background 0.15s;
    box-sizing: border-box;

    &:hover {
      background: rgba(0,0,0,0.04);
      border-radius: 3px;
    }
  }
}


</style>
