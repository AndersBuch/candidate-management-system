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
      const res = await fetch('/api/companies', {
        credentials: 'include'
      })

      if (!res.ok) throw new Error('Kunne ikke hente firmaer')
      const data = await res.json()
      companies.value = data

      if (data.length) {
        activeCompanyId.value = data[0].id
        const firstPositionId = data[0].positions[0]?.id ?? null
        activePositionId.value = firstPositionId
        if (firstPositionId) fetchCandidatesForPosition(firstPositionId)
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
      const res = await fetch(`/api/jobs/${positionId}/candidates`, {
        credentials: 'include'
      })

      if (!res.ok) throw new Error('Kunne ikke hente kandidater')
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
    const res = await fetch('/api/companies', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
      credentials: 'include'
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
      const url = `/api/candidates/${candidateId}`

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
    , hideCandidate, restoreCandidate, deleteCandidate
  }
})
