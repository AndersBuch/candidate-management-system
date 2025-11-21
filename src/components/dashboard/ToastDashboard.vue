<script setup>
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'
import Button from '@/components/atoms/Button.vue'
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

const progressColor = computed(() => {
    return props.variant === 'danger'
        ? 'linear-gradient(90deg, #FF383C)'
        : 'linear-gradient(90deg, #34C759)'
})

const variantClass = computed(() => (props.variant === 'danger' ? 'toastDanger' : 'toastSuccess'))
const progressStyle = computed(() => ({
    background: props.variant === 'danger'
        ? 'linear-gradient(90deg, #FF383C)'
        : 'linear-gradient(90deg, #34C759)'
}))

</script>

<template>
    <div class="toast" :class="variantClass" :style="{'--duration': props.duration + 'ms'}" role="status" @mouseenter="pause" @mouseleave="resume" aria-live="polite">
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

            <Button type="smallDashboard" label="Fortryd" @click="close" />
        </div>

        <div class="toastProgressWrap" aria-hidden>
            <div class="toastProgress" :class="{ paused: !running }" :style="{ background: progressColor }">
            </div>
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
    margin-bottom: -5px
}

.toastSubtitle {
    @include bodyText;
    color: $darkGrey;
}

/* variants */
.toastSuccess {
    background-color: $sekundareBlue;
}

.toastDanger {
    background-color: rgba($dangerRed, 0.2);
}

/* progress bar */
.toastProgressWrap {
    width: 100%;
    height: 6px;
    background: $lightGrey;
    border-radius: 6px;
    overflow: hidden;
}

.toastProgress {
  height: 100%;
  width: 100%;
  transform-origin: left center;
  transform: scaleX(0); // STARTPOINT
  animation: toastProgress var(--duration) linear forwards;
}

@keyframes toastProgress {
  from {
    transform: scaleX(0);
  }
  to {
    transform: scaleX(1);
  }
}


.toastProgress.paused {
    animation-play-state: paused;
}

</style>