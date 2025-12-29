import { ref } from 'vue'
import { defineStore } from 'pinia'



export const useCandidateStatsStore = defineStore('candidateStats', () => {
  const totalCandidates = ref(0)
  const recentCandidates = ref(0)
  const deletedCandidates = ref(0) // ← tilføjet
  const initialized = ref(false)

  async function fetchTotalCandidates() {
    const res = await fetch(`/api/candidates/count`)
    const data = await res.json()
    totalCandidates.value = data.count
  }

  async function fetchRecentCandidates(days = 7) {
    const res = await fetch(`/api/candidates/recent?days=${days}`)
    const data = await res.json()
    recentCandidates.value = data.count
  }

  async function fetchDeletedCandidates(days = 7) {
    const res = await fetch(`/api/candidates/deleted?days=${days}`);
    const data = await res.json();
    deletedCandidates.value = data.count;
  }

  async function init() {
    if (initialized.value) return
    await fetchTotalCandidates()
    await fetchRecentCandidates(7)
    await fetchDeletedCandidates(7)
    initialized.value = true
  }

  return { totalCandidates, recentCandidates, deletedCandidates, fetchTotalCandidates, fetchRecentCandidates, fetchDeletedCandidates, init }
})
