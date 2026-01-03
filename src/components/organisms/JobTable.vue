<script setup>
import { computed, onMounted } from 'vue'
import { useCompanyStore } from '@/stores/useCompanyStore'
import Button from '@/components/atoms/Button.vue'
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const companyStore = useCompanyStore()

onMounted(() => {
  companyStore.fetchCompanies()
})

const rows = computed(() => {
  return (companyStore.companies || []).flatMap(company =>
    (company.positions || []).map(position => ({
      company,
      position
    }))
  )
})

function goToPageTwo() {
  router.push({ name: 'PageTwo' })
}

const emit = defineEmits(['open-privacy-modal'])

function onApplyClick(row, e) {
  e?.stopPropagation()
  emit('open-privacy-modal', { companyId: row.company.id, positionId: row.position.id })
}
</script>

<template>
  <div class="jobTableContainer">
    <table class="jobs">
      <colgroup>
        <col style="width:32%"> <!-- Firma -->
        <col style="width:26%"> <!-- Stilling -->
        <col style="width:22%"> <!-- Branche -->
        <col style="width:14%"> <!-- Geografi -->
        <col class="colCta"> <!-- CTA -->
      </colgroup>

      <thead>
        <tr>
          <th>Firma</th>
          <th>Stilling</th>
          <th>Branche</th>
          <th>Geografi</th>
          <th></th>
        </tr>
      </thead>

    <tbody>
      <tr
        v-for="row in rows"
        :key="`${row.company.id}-${row.position.id}`"
        @click="goToPageTwo"
        style="cursor: pointer"
      >
        <td class="cellCompany">
          <BasicIconAndLogo name="LPSLogo" :exstraSmall="true" />
          {{ row.company.name }}
        </td>

        <td>{{ row.position.name }}</td>
        <td>{{ row.position.branch }}</td>
        <td>{{ row.position.geography }}</td>

        <td class="cellCta">
          <Button
            label="Søg job"
            type="small"
            aria-label="Søg job"
            @click="(e) => onApplyClick(row, e)"
          />
        </td>
      </tr>
    </tbody>
    </table>
  </div>
</template>

<style scoped lang="scss">
.jobTableContainer {
  display: flex;
  justify-content: center;
  width: 100%;
  margin-bottom: 40px;
}

.jobs {
  width: 1450px;
  border-radius: 15px;
  border-collapse: separate;
  border-spacing: 0;
  overflow: hidden;
}

.jobs th,
.jobs td {
  padding: 2rem 3rem;
  text-align: left;
  vertical-align: middle;
  color: $black;
}

.jobs thead th {
  background: $tableHeader;
  @include boldBodyText;
  font-size: 20px;
}

.jobs tbody td {
  @include bodyText;
}

.jobs .cellCompany {
  display: flex;
  align-items: center;
  gap: 12px;
}

.jobs .colCta,
.jobs .cellCta,
.jobs th:last-child,
.jobs td:last-child {
  width: 1%;
  white-space: nowrap;
  text-align: right;
  padding-right: 1rem;
}

.jobs tbody tr:hover td {
  background: $hoverLightBlue !important;
  transition: 0.2s;
}

.jobs tbody tr:nth-child(even) td {
  background: $lightGrey;
}
</style>