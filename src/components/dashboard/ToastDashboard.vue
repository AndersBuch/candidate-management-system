<script setup>
import { ref, computed, onMounted, onBeforeUnmount, watch } from 'vue'

const props = defineProps({
  id: { type: [String, Number], default: null },
  title: { type: String, required: true },
  subtitle: { type: String, default: '' },
  variant: { type: String, default: 'success' }, // 'success' | 'danger'
  duration: { type: Number, default: 3000 }, // ms
  showUndo: { type: Boolean, default: true },
  undoLabel: { type: String, default: 'Fortryd' }
})

const emit = defineEmits(['close', 'undo'])

const running = ref(true)
let timer = null

function startTimer() {
  clearTimer()
  timer = setTimeout(() => close(), props.duration)
  running.value = true
}

function clearTimer() {
  if (timer) { clearTimeout(timer); timer = null }
  running.value = false
}

function pause() { clearTimer() }
function resume() { startTimer() }

function close() {
  clearTimer()
  emit('close', props.id)
}

function doUndo() {
  emit('undo', props.id)
  close()
}

onMounted(() => startTimer())
onBeforeUnmount(() => clearTimer())

// restart if duration changes
watch(() => props.duration, () => startTimer())

const variantClass = computed(() => (props.variant === 'danger' ? 'toast--danger' : 'toast--success'))
const progressStyle = computed(() => ({
  animationDuration: `${props.duration}ms`,
  animationPlayState: running.value ? 'running' : 'paused'
}))
</script>

<template>
  <div
    class="toast"
    :class="variantClass"
    role="status"
    @mouseenter="pause"
    @mouseleave="resume"
    aria-live="polite"
  >
    <div class="toast__inner">
      <div class="toast__icon" aria-hidden>
        <template v-if="props.variant === 'danger'">
          <!-- trash / danger icon -->
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M3 6h18" stroke="#EF4444" stroke-width="2" stroke-linecap="round"/><path d="M8 6V4h8v2" stroke="#EF4444" stroke-width="2" stroke-linecap="round"/><rect x="6" y="6" width="12" height="14" rx="2" stroke="#EF4444" stroke-width="2"/></svg>
        </template>
        <template v-else>
          <!-- check / success icon -->
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M20 6L9 17l-5-5" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </template>
      </div>

      <div class="toast__text">
        <div class="toast__title">{{ props.title }}</div>
        <div class="toast__subtitle" v-if="props.subtitle">{{ props.subtitle }}</div>
      </div>

      <div class="toast__actions">
        <button v-if="props.showUndo" class="toast__undo" @click="doUndo">{{ props.undoLabel }}</button>
        <button class="toast__close" @click="close" aria-label="Luk">✕</button>
      </div>
    </div>

    <div class="toast__progress-wrap" aria-hidden>
      <div class="toast__progress" :style="progressStyle"></div>
    </div>
  </div>
</template>

<style scoped lang="scss">
.toast {
  width: 100%;
  max-width: 760px;
  border-radius: 10px;
  padding: 14px;
  box-sizing: border-box;
  box-shadow: 0 6px 18px rgba(2,6,23,0.06);
  overflow: hidden;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.toast__inner {
  display:flex;
  align-items:center;
  gap:12px;
}

.toast__icon { flex: 0 0 30px; display:flex; align-items:center; justify-content:center; }
.toast__text { flex:1; }
.toast__title { font-weight:800; color:#0f172a; font-size:1rem; }
.toast__subtitle { color: rgba(15,23,42,0.6); font-size:0.9rem; margin-top:2px; }

.toast__actions { display:flex; gap:8px; align-items:center; }
.toast__undo {
  background: #0ea5e9;
  color: #fff;
  border: none;
  padding: 8px 12px;
  border-radius: 999px;
  font-weight:700;
  cursor:pointer;
}
.toast__close {
  background: transparent;
  border: none;
  font-size: 14px;
  padding: 6px;
  cursor:pointer;
}

/* variants */
.toast--success {
  background: rgba(59,130,246,0.06);
  border: 1px solid rgba(6,95,212,0.06);
}
.toast--danger {
  background: rgba(254,226,226,0.9);
  border: 1px solid rgba(239,68,68,0.15);
}

/* progress bar */
.toast__progress-wrap {
  width: 100%;
  height: 6px;
  background: rgba(0,0,0,0.04);
  border-radius: 6px;
  overflow: hidden;
}
.toast__progress {
  height: 100%;
  width: 0%;
  background: linear-gradient(90deg, #10b981, #059669);
  transform-origin: left center;
  animation-name: toastProgress;
  animation-timing-function: linear;
  animation-fill-mode: forwards;
}

@keyframes toastProgress {
  from { width: 0%; }
  to   { width: 100%; }
}
</style>