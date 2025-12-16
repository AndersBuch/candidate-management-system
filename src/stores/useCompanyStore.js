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
  const deletedCandidates = ref({})

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

  function hideCandidate(candidateId) {
    const posId = activePositionId.value
    if (!posId) return
    const list = candidatesByPositionId.value[posId] || []
    const idx = list.findIndex(c => c.id === candidateId)
    if (idx === -1) return
    deletedCandidates.value[candidateId] = list[idx]
    candidatesByPositionId.value = {
      ...candidatesByPositionId.value,
      [posId]: list.filter(c => c.id !== candidateId)
    }
  }

  function restoreCandidate(candidateId) {
    const posId = activePositionId.value
    if (!posId) return
    const cand = deletedCandidates.value[candidateId]
    if (!cand) return
    const list = candidatesByPositionId.value[posId] || []
    candidatesByPositionId.value = {
      ...candidatesByPositionId.value,
      [posId]: [cand, ...list]
    }
    delete deletedCandidates.value[candidateId]
  }

  async function deleteCandidate(candidateId) {
    try {
      const base = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8085'
      const url = `${base}/api/candidates/${candidateId}`

      const res = await fetch(url, {
        method: 'DELETE',
        credentials: 'include'
      })

      if (!res.ok) {
        console.error('Delete failed:', await res.text())
        throw new Error('Delete failed')
      }

      const posId = activePositionId.value
      if (posId) {
        const list = candidatesByPositionId.value[posId] || []
        candidatesByPositionId.value = {
          ...candidatesByPositionId.value,
          [posId]: list.filter(c => c.id !== candidateId)
        }
      }

      delete deletedCandidates.value[candidateId]
      return true
    } catch (err) {
      console.error('deleteCandidate error:', err)
      return false
    }
  }

  return {
    companies,
    activeCompanyId,
    activePositionId,
    candidatesByPositionId,
    deletedCandidates,
    activeCompany,
    activePosition,
    activeCandidates,
    selectCompany,
    selectPosition,
    fetchCompanies,
    fetchCandidatesForPosition,
    addCompany
    ,hideCandidate, restoreCandidate, deleteCandidate
  }
})
