import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { useCompanyStore } from '@/stores/useCompanyStore'

export const useSearchStore = defineStore('candidateSearch', () => {
  const searchTerm = ref('')

  const companyStore = useCompanyStore()

  // 👇 filtrer kandidater baseret på navn
  const filteredCandidates = computed(() => {
    if (!searchTerm.value.trim()) {
      return companyStore.activeCandidates
    }

    const term = searchTerm.value.toLowerCase()

    return companyStore.activeCandidates.filter(c =>
      c.name.toLowerCase().includes(term)
    )
  })

  function setSearch(value) {
    searchTerm.value = value
  }

  function clearSearch() {
    searchTerm.value = ''
  }

  return {
    searchTerm,
    filteredCandidates,
    setSearch,
    clearSearch
  }
})
