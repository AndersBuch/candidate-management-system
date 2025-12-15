<script setup>
import { ref, computed } from 'vue'
import TableField from '@/components/dashboard/TableField.vue'
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'
import EditModal from '@/components/dashboard/EditModal.vue'
import { useCompanyStore } from '@/stores/useCompanyStore'

const selectedCandidate = ref(null)
const showEditModal = ref(false)


const emit = defineEmits(['openCandidate'])

const companyStore = useCompanyStore()

// Rækker = alle kandidater til den aktive stilling
const rows = computed(() => companyStore.activeCandidates)

const activeIndex = ref(null)

function onEdit(row) {
  selectedCandidate.value = row
  showEditModal.value = true
}

function onStatusClick(row) {
  console.log('✅ status click:', row.status)
}

// sæt lokal activeIndex OG emit til parent så DashboardSite kan åbne panelet
function setActiveRow(index) {
  activeIndex.value = activeIndex.value === index ? null : index
  emit('openCandidate', activeIndex.value)
}
</script>

<template>
  <div class="tableHeader">
    <div class="headerItem">
      <p>Navn</p>
      <BasicIconAndLogo name="Shuffle" :iconSize="true" />
    </div>
    <div class="headerItem">
      <p>Tlf</p>
    </div>
    <div class="headerItem">
      <p>Email</p>
      <BasicIconAndLogo name="Shuffle" :iconSize="true" />
    </div>
    <div class="headerItem">
      <p>Status</p>
      <BasicIconAndLogo name="Shuffle" :iconSize="true" />
    </div>
  </div>

<TableField
  v-for="(r, i) in rows"
  :key="r.id ?? i"
  :id="r.applicationId"
  :index="i"
  :candidate-id="r.id"
  :application-id="r.applicationId"
  :name="r.name"
  :phone="r.phone"
  :email="r.email"
  :status="r.status"
  :linkedin-url="r.linkedin"
  :is-active="activeIndex === i"
  @rowClick="setActiveRow"
  @statusClick="() => onStatusClick(r)"
  @edit="() => onEdit(r)"
/>

<EditModal
  v-if="showEditModal && selectedCandidate"
  :candidate="selectedCandidate"
  @close="showEditModal = false"
/>

</template>

<style lang="scss">
.tableHeader {
  display: grid;
  grid-template-columns: 2.8fr 1fr 3fr 1.2fr 0.8fr;
  gap: 12px;
  padding: 10px 20px;
}

.headerItem {
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;

  p {
    margin: 0;
    @include bigBodyText;
    transition: color 0.2s ease, opacity 0.2s ease;
  }

  &:hover {
    color: rgba($black, 0.95);
    opacity: 0.8;
  }
}
</style>
