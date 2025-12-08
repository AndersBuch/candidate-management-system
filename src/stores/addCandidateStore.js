import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useCandidateStore = defineStore('candidate', () => {
  const candidates = ref([])

async function fetchCandidates(positionName = null) {
  try {
    const base = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8085'
    let url = base + '/api/candidates'

    const params = []
    if (positionName) params.push(`positionId=${encodeURIComponent(positionName)}`)

    if (params.length) url += '?' + params.join('&')

    console.log("Fetching filtered candidates from:", url)

    const res = await fetch(url)
    const bodyText = await res.text()
    candidates.value = JSON.parse(bodyText)
  } catch (err) {
    console.error("fetchCandidates error:", err)
    candidates.value = []
  }
}



  async function addCandidate(payload) {
    try {
      const base = (import.meta.env.VITE_API_BASE_URL) ? import.meta.env.VITE_API_BASE_URL : ''
      const url = base + '/api/candidates'
      const res = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
        credentials: 'include'
      })

      if (!res.ok) {
        const text = await res.text()
        console.error('Add candidate failed:', res.status, text)
        throw new Error('Add candidate failed')
      }

      const newCandidate = await res.json()

      candidates.value.push({
        id: newCandidate.id,
        name: payload.first_name + " " + payload.last_name,
        phone: payload.phone_number,
        email: payload.email,
        status: payload.status,
        linkedin: payload.linkedin_url
      })

      return newCandidate
    } catch (err) {
      console.error('addCandidate error:', err)
      throw err
    }
  }

  return { candidates, fetchCandidates, addCandidate }
})
