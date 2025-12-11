// src/stores/useCompanyStore.js
import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

export const useCompanyStore = defineStore('company', () => {
  // Liste over firmaer med stillinger (fra /api/companies)
  const companies = ref([])

  // Aktive valg
  const activeCompanyId = ref(null)
  const activePositionId = ref(null)

  // Kandidater per stilling/job-id
  // struktur: { [jobId]: [ { ...kandidatdata... } ] }
  const candidatesByPositionId = ref({})

  // === COMPUTED ===

  const activeCompany = computed(() =>
    companies.value.find(c => c.id === activeCompanyId.value) || null
  )

  const activePosition = computed(() => {
    const company = activeCompany.value
    if (!company) return null
    return company.positions.find(p => p.id === activePositionId.value) || null
  })

  // alle kandidater til den aktuelle stilling
  const activeCandidates = computed(() => {
    if (!activePositionId.value) return []
    return candidatesByPositionId.value[activePositionId.value] || []
  })

  // === ACTIONS ===

  function selectCompany(companyId) {
    activeCompanyId.value = companyId
    const company = companies.value.find(c => c.id === companyId)
    const firstPositionId = company?.positions[0]?.id || null
    activePositionId.value = firstPositionId

    if (firstPositionId) {
      fetchCandidatesForPosition(firstPositionId)
    }
  }

  function selectPosition(companyId, positionId) {
    activeCompanyId.value = companyId
    activePositionId.value = positionId

    if (positionId) {
      fetchCandidatesForPosition(positionId)
    }
  }

  async function fetchCompanies() {
    try {
      const token = localStorage.getItem('token') || sessionStorage.getItem('token')
      const headers = token ? { 'Authorization': `Bearer ${token}` } : {}

      // du brugte tidligere hardcoded http://localhost:8085
      const res = await fetch('http://localhost:8085/api/companies', { headers })

      if (!res.ok) {
        throw new Error('Kunne ikke hente firmaer')
      }

      const data = await res.json()
      console.log('companies from API', data)
      companies.value = data

      if (data.length) {
        activeCompanyId.value = data[0].id
        const firstPositionId = data[0].positions[0]?.id ?? null
        activePositionId.value = firstPositionId

        if (firstPositionId) {
          fetchCandidatesForPosition(firstPositionId)
        }
      } else {
        activeCompanyId.value = null
        activePositionId.value = null
      }
    } catch (err) {
      console.error('Fejl ved hentning af firmaer:', err)
    }
  }

  async function fetchCandidatesForPosition(positionId) {
    if (!positionId) return

    try {
      const res = await fetch(`http://localhost:8085/api/jobs/${positionId}/candidates`)

      if (!res.ok) {
        throw new Error('Kunne ikke hente kandidater')
      }

      const data = await res.json()

      candidatesByPositionId.value = {
        ...candidatesByPositionId.value,
        [positionId]: data
      }
    } catch (err) {
      console.error('Fejl ved hentning af kandidater:', err)
    }
  }

  async function addCompany(payload) {
    const token = localStorage.getItem('token') || sessionStorage.getItem('token')
    const headers = Object.assign(
      { 'Content-Type': 'application/json' },
      token ? { 'Authorization': `Bearer ${token}` } : {}
    )

    const res = await fetch('http://localhost:8085/api/companies', {
      method: 'POST',
      headers,
      body: JSON.stringify(payload)
    })

    if (!res.ok) throw new Error('Kunne ikke tilføje firma')

    await fetchCompanies()
  }

  return {
    companies,
    activeCompanyId,
    activePositionId,
    candidatesByPositionId,
    activeCompany,
    activePosition,
    activeCandidates,
    selectCompany,
    selectPosition,
    fetchCompanies,
    fetchCandidatesForPosition,
    addCompany
  }
})
