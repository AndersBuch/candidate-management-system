import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useCandidateStore = defineStore('candidate', () => {
  const candidates = ref([])

  async function fetchCandidates() {
    try {
      // brug fuld origin i dev hvis base-path er anderledes:
      const base = (import.meta.env.VITE_API_BASE_URL) ? import.meta.env.VITE_API_BASE_URL : ''
      const url = base + '/api/candidates'
      console.log('Fetching candidates from', url)
      const res = await fetch(url, { credentials: 'include' })

      // debug: hvis ikke OK, log body som tekst
      if (!res.ok) {
        const text = await res.text()
        console.error('Fetch candidates failed:', res.status, res.statusText, text)
        throw new Error('Fetch failed: ' + res.status)
      }

      const contentType = res.headers.get('content-type') || ''
      if (contentType.indexOf('application/json') === -1) {
        const text = await res.text()
        console.error('Expected JSON but got:', text)
        throw new Error('Server did not return JSON')
      }

      const data = await res.json()
      candidates.value = data
      console.log('Candidates loaded:', data.length)
    } catch (err) {
      console.error('fetchCandidates error:', err)
      // valgfrit: sæt candidates.value = [] eller vis toast
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
        status: "Contact",
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
