import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

export const useCompanyStore = defineStore('company', () => {

const companies = ref([
  {
    id: 1,
    name: 'Insatech A/S',
    positions: [
      {
        id: '1-1',
        name: 'Maskinmester',
        applicationId: '32&dnAj'
      },
      {
        id: '1-2',
        name: 'Elektriker',
        applicationId: '993KD83'
      },
    ],
  },
  {
    id: 2,
    name: 'Regal A/S',
    positions: [
      {
        id: '2-1',
        name: 'Svejser',
        applicationId: 'AB22DJ3'
      },
      {
        id: '2-2',
        name: 'Projektleder',
        applicationId: '7ss8DJD'
      },
    ],
  },
])

  const activeCompanyId = ref(1)
  const activePositionId = ref('1-1')

  const activeCompany = computed(() =>
    companies.value.find(c => c.id === activeCompanyId.value) || null
  )

  const activePosition = computed(() => {
    const company = activeCompany.value
    if (!company) return null
    return company.positions.find(p => p.id === activePositionId.value) || null
  })

  function selectCompany(companyId) {
    activeCompanyId.value = companyId
    const company = companies.value.find(c => c.id === companyId)
    activePositionId.value = company?.positions[0]?.id || null
  }

  function selectPosition(companyId, positionId) {
    activeCompanyId.value = companyId
    activePositionId.value = positionId
  }

  return {
    companies,
    activeCompanyId,
    activePositionId,
    activeCompany,
    activePosition,
    selectCompany,
    selectPosition,
  }
})
