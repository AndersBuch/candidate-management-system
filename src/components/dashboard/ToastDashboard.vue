<script setup>
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'
import { ref, computed, onMounted, onBeforeUnmount, watch } from 'vue'

const props = defineProps({
    id: { type: [String, Number], default: null },
    title: { type: String, required: true },
    subtitle: { type: String, default: '' },
    variant: { type: String, default: 'success' }, // 'success' | 'danger'
    duration: { type: Number, default: 90000 }, // ms
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
    <div class="toast" :class="variantClass" role="status" @mouseenter="pause" @mouseleave="resume" aria-live="polite">
        <div class="toastInner">
            <div class="toastIcon" aria-hidden>
                <template v-if="props.variant === 'danger'">
                    <!-- trash / danger icon -->
                <BasicIconAndLogo name="Thash" :iconSize="true" />
                </template>
                <template v-else>
                <BasicIconAndLogo name="CheckCircle" :iconSize="true" />
                </template>
            </div>

            <div class="toastText">
                <div class="toastTitle">{{ props.title }}</div>
                <div class="toastSubtitle" v-if="props.subtitle">{{ props.subtitle }}</div>
            </div>

            <div class="toast__actions">
                <button v-if="props.showUndo" class="toast__undo" @click="doUndo">{{ props.undoLabel }}</button>
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
    max-width: 500px;
    border-radius: 15px;
    padding: 14px;
    box-sizing: border-box;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.toastInner {
    display: flex;
    align-items: center;
    gap: 12px;
}

.toastIcon {
    flex: 0 0 30px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.toastText {
    flex: 1;
}

.toastTitle {
    @include bigBodyText;
    color: $black; 
}

.toastSubtitle {
    @include bodyText;
    color: $darkGrey; 
}

.toast__actions {
    display: flex;
    gap: 8px;
    align-items: center;
}

.toast__undo {
    background: #0ea5e9;
    color: #fff;
    border: none;
    padding: 8px 12px;
    border-radius: 999px;
    font-weight: 700;
    cursor: pointer;
}

/* variants */
.toast--success {
    background-color: $sekundareBlue;
}

.toast--danger {
  background-color: rgba($dangerRed, 0.2);
}

/* progress bar */
.toast__progress-wrap {
    width: 100%;
    height: 6px;
    background: rgba(0, 0, 0, 0.04);
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
    from {
        width: 0%;
    }

    to {
        width: 100%;
    }
}
</style>