<script setup>
import TableField from '@/components/dashboard/TableField.vue'
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'
import ToastDashboard from '@/components/dashboard/ToastDashboard.vue'
import { ref } from 'vue'

const rows = [
  { name: 'Hans Hansen Ole', phone: '22283910', email: 'kontaktmail@gmail.com', status: 'Accepted', linkedin: 'https://...' },
  { name: 'Agnes Kofoed', phone: '55724901', email: 'promailhe@hotmail.com', status: 'Pending', linkedin: '' },
  { name: 'Mette Jensen', phone: '40392211', email: 'mette.jensen@gmail.com', status: 'Rejected', linkedin: '' },
  { name: 'Jonas N.', phone: '22335544', email: 'jonas@firmamail.dk', status: 'Contact', linkedin: '' }
]

const activeIndex = ref(null)

function onEdit(row) {
  console.log('📝 edit:', row.name)
}

function onStatusClick(row) {
  console.log('✅ status click:', row.status)
}

function setActiveRow(index) {
  activeIndex.value = index
}


const toasts = ref([])

function showToast() {
  const id = Date.now()

  toasts.value.push({
    id,
    title: 'Gemt!',
    subtitle: 'Din ændring blev gemt.',
    variant: 'success',
    duration: 3000,
    showUndo: true
  })
}

function removeToast(id) {
  toasts.value = toasts.value.filter(t => t.id !== id)
}

function handleUndo(id) {
  console.log("Undo for:", id)
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
      <BasicIconAndLogo name="Shuffle" :iconSize="true" />
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

  <TableField v-for="(r, i) in rows" :key="i" :index="i" :name="r.name" :phone="r.phone" :email="r.email"
    :status="r.status" :linkedin-url="r.linkedin" :is-active="activeIndex === i" @edit="() => onEdit(r)"
    @statusClick="onStatusClick(r)" @rowClick="setActiveRow(i)" />


  <button @click="showToast">Vis toast</button>

  <div class="toastContainer">
    <ToastDashboard 
      v-for="t in toasts"
      :key="t.id"
      v-bind="t"
      @close="removeToast"
      @undo="handleUndo"
    />
  </div>

</template>


<style lang="scss">
.tableHeader {
  display: grid;
  grid-template-columns: 2.8fr 1fr 3fr 1.2fr 0.8fr; // samme som tableRow
  gap: 12px;
  padding: 10px 20px;
}

.headerItem {
  display: flex;
  align-items: center;
  gap: 8px; // afstand mellem p og ikon
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