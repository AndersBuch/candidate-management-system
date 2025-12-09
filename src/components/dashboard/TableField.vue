<script setup>
import Button from '@/components/atoms/Button.vue'
import StatusDropdown from '@/components/dashboard/StatusDropdown.vue'
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'
import EditModal from '@/components/dashboard/EditModal.vue'

import { ref, watch, computed } from 'vue'

import { useCandidateStore } from '@/stores/addCandidateStore'


const props = defineProps({
  id: Number,
  index: Number,
  name: String,
  phone: String,
  email: String,
  status: String,
  linkedinUrl: String,
  isActive: Boolean
})

const emit = defineEmits(["statusClick", "toggle", "rowClick"])
const store = useCandidateStore()

const localStatus = ref(props.status)

watch(localStatus, (newVal) => {
  emit('statusClick', newVal)
})

watch(
  () => props.status,
  (newVal) => {
    localStatus.value = newVal
  }
)

async function onStatusClick(newStatus) {
  if (!props.id) return
  const success = await store.updateStatus(props.id, newStatus)
  if (success) localStatus.value = newStatus
  else alert("Kunne ikke opdatere status på serveren")
}

const rowClass = computed(() => (props.index % 2 === 0 ? 'rowEven' : 'rowOdd'))

function handleClick(event) {
  if (event.target.closest('.colStatus')) return
  emit('rowClick', props.index)
}

function openLinkedin() {
  if (props.linkedinUrl) window.open(props.linkedinUrl, '_blank')
}

function onEdit() { emit('edit') }

function getStatusLabel(status) {
  switch (status?.toLowerCase()) {
    case 'accepted': return 'Accepteret'
    case 'pending': return 'Afventer'
    case 'contact': return 'Kontakt'
    case 'rejected': return 'Afvist'
    default: return status || 'Ukendt'
  }
}

const normalizedStatus = computed({
  get() {
    switch ((localStatus.value || "").toLowerCase()) {
      case "accepteret": return "Accepted"
      case "afventer": return "Pending"
      case "kontakt": return "Contact"
      case "afvist": return "Rejected"
      default: return localStatus.value
    }
  },
  set(value) {
    localStatus.value = value
  }
})


</script>

<template>
  <div class="tableRow" :class="[rowClass, { activeRow: isActive }]" @click="handleClick">
    <div class="col colName">
      <div class="avatar">
        <BasicIconAndLogo :name="isActive ? 'UserWhite' : 'User'" :iconSize="true" />
      </div>
      <div class="nameText">{{ name }}</div>
    </div>

    <div class="col colPhone">{{ phone }}</div>
    <div class="col colEmail">{{ email }}</div>

    <div class="colStatus">
      <StatusDropdown v-model="normalizedStatus" :is-open="isActive" @toggle="emit('rowClick', index)" @click.stop
        @update:modelValue="onStatusClick" />

    </div>

    <div class="col colActions">
      <BasicIconAndLogo :name="isActive ? 'LinkinIconWhite' : 'LinkinIcon'" :iconSize="true" class="iconBtn linkedin"
        role="button" tabindex="0" aria-label="LinkedIn" @click.stop="openLinkedin" />

      <div class="notActions">
        <EditModal />
      </div>
    </div>
  </div>
</template>

<style scoped lang="scss">
.tableRow {
  display: grid;
  grid-template-columns: 2.8fr 1fr 3fr 1.2fr 0.8fr;
  align-items: center;
  gap: 12px;
  padding: 18px 20px;
  border-radius: 5px;
  cursor: pointer;
  position: relative;
  color: $black;
}

.colStatus {
  position: relative;
  display: flex;
  justify-content: flex-start;
  align-items: center;
}

.rowEven {
  background: $sekundareBlue;
  transition: 0.2s ease;

  &:hover {
    background-color: $hoverLightBlue;
  }

}

.rowOdd {
  background: $whiteColor;
  transition: 0.2s ease;

  &:hover {
    background-color: $lightGrey;
  }
}

.activeRow {
  background-color: $primaryBlue !important;
  color: $whiteColor;
  transition: all 0.2s ease-in-out;
}

.col {
  display: flex;
  align-items: center;
}

.colName {
  gap: 21px;
  @include bodyText;
}

.avatar {
  display: flex;
}

.colPhone,
.colEmail {
  @include bodyText;
}

.colActions {
  justify-content: flex-end;
  gap: 12px;
}

.notActions {
  justify-content: flex-end;
  gap: 12px;
  z-index: 999;
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
</style>
