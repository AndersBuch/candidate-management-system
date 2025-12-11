import { ref } from 'vue'
import { defineStore } from 'pinia'

const API_BASE = 'http://localhost:8085/api'

export const useCandidateStatsStore = defineStore('candidateStats', () => {
  const totalCandidates = ref(0)
  const recentCandidates = ref(0)
  const initialized = ref(false)

  async function fetchTotalCandidates() {
    const res = await fetch(`${API_BASE}/candidates/count`)
    const data = await res.json()
    totalCandidates.value = data.count
  }

  async function fetchRecentCandidates(days = 30) {
    const res = await fetch(`${API_BASE}/candidates/recent?days=${days}`)
    const data = await res.json()
    recentCandidates.value = data.count
  }

  async function init() {
    if (initialized.value) return
    await fetchTotalCandidates()
    await fetchRecentCandidates(30)
    initialized.value = true
  }


  return { totalCandidates, recentCandidates, fetchTotalCandidates, fetchRecentCandidates, init }
})
