<script setup>
import Modal from '@/components/Modal.vue'
import Button from '@/components/atoms/Button.vue'
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'
import Toast from '@/components/dashboard/ToastDashboard.vue'
import { ref, reactive, computed, watch, onMounted } from 'vue'
import { useCandidateStore } from '@/stores/addCandidateStore'

const props = defineProps({
    candidateId: {
        type: Number,
        required: true
    },
    showTrigger: {
        type: Boolean,
        default: true
    }
})


const store = useCandidateStore()

const showModal = ref(false)

const emit = defineEmits(['close', 'deleted'])

const openModal = () => {
    showModal.value = true
}

const closeModal = () => {
    showModal.value = false
    emit('close')
}

const thashModal = () => {
    openModal()
}

onMounted(() => {
    if (!props.showTrigger) {
        showModal.value = true
    }
})

const toasts = ref([])

function showToast() {
    toasts.value.push({
        id: Date.now(),
        title: 'Kandidat slettet',
        subtitle: 'Kandidaten blev fjernet korrekt',
        variant: 'danger',   // eller 'success'
        duration: 3000
    })
}

function removeToast(id) {
    toasts.value = toasts.value.filter(t => t.id !== id)
}

async function confirmDelete() {
    const ok = await store.deleteCandidate(props.candidateId)

    if (ok) {
        emit('deleted')
        closeModal()
    }
}

</script>

<template>
    <BasicIconAndLogo v-if="props.showTrigger" name="Thash" :iconSize="true" @click.stop="thashModal" class="iconBtn" />
    <transition name="fade">
        <Modal v-if="showModal" @close="closeModal" height="260px" class="deleteCandidateModal">

            <div class="kandidateModal">
                <BasicIconAndLogo name="Thash" :iconSize="true" />
                <p>Du er igang med at slette en kandidate</p>
                <p>Vil du slette denne kandidate?</p>
            </div>
            <div class="buttonModal">
                <Button type="smallSecondaryButton" label="Annuller" aria-label="Annuller" @click="closeModal" />
                <Button type="smallRedButton" label="Ja" aria-label="Sletter Kandidate" @click="confirmDelete" />
            </div>

        </Modal>
    </transition>

    <div class="toastWrapper">
        <Toast v-for="t in toasts" :key="t.id" v-bind="t" @close="removeToast" />
    </div>
</template>

<style lang="scss">
.toastWrapper {
    position: fixed;
    bottom: 20px;
    left: 20px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    z-index: 9999;
}

.deleteCandidateModal .closeIcon {
    display: none !important;
}

.iconBtn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: transform 0.2s ease, opacity 0.2s ease;

    &:hover {
        transform: scale(1.1);
        opacity: 0.9;
    }
}

.kandidateModal {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    gap: 3px;
}

/* Første <p> */
.kandidateModal p:first-of-type {
    @include bigBodyText;
}

/* Andet <p> */
.kandidateModal p:last-of-type {
    @include bodyText;
}

.buttonModal {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 12px;
    margin-top: 20px;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.4s ease-out, transform 0.4s ease-out;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}

.fade-enter-to,
.fade-leave-from {
    opacity: 1;
    transform: translateY(0);
}
</style>
