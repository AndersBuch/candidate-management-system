import { ref, computed } from 'vue'
import { defineStore } from 'pinia'


export const useCompanyStore = defineStore('company', () => {
  const companies = ref([])
  const activeCompanyId = ref(null)
  const activePositionId = ref(null)
  

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

  async function fetchCompanies() {
    try {
      // Brug lokal PHP backend direkte under udvikling for at undgå Vite-proxy til remote
      const token = localStorage.getItem('token') || sessionStorage.getItem('token')
      const headers = token ? { 'Authorization': `Bearer ${token}` } : {}
      const res = await fetch('http://localhost:8085/api/companies', { headers })
      if (!res.ok) {
        throw new Error('Kunne ikke hente firmaer')
      }
      const data = await res.json()
      // DEBUG: vis API-respons i browserkonsollen så du kan se hvad backend returnerer
      console.log('companies from API', data)
      companies.value = data

      if (data.length) {
        activeCompanyId.value = data[0].id
        activePositionId.value = data[0].positions[0]?.id ?? null
      } else {
        activeCompanyId.value = null
        activePositionId.value = null
      }
    } catch (err) {
      console.error('Fejl ved hentning af firmaer:', err)
    }
  }

  async function addCompany(payload) {
  const token = localStorage.getItem('token') || sessionStorage.getItem('token')
  const res = await fetch('http://localhost:8085/api/companies', {
    method: 'POST',
    headers: Object.assign({ 'Content-Type': 'application/json' }, token ? { 'Authorization': `Bearer ${token}` } : {}),
    body: JSON.stringify(payload)
  });

  if (!res.ok) throw new Error('Kunne ikke tilføje firma');

  // Opdater lokale companies
  await fetchCompanies();
}


return {
  companies,
  activeCompanyId,
  activePositionId,
  activeCompany,
  activePosition,
  selectCompany,
  selectPosition,
  fetchCompanies,
  addCompany // <--- tilføjet her
}

})