// src/stores/addCandidateStore.js
import { defineStore } from 'pinia'
import { useCompanyStore } from '@/stores/useCompanyStore'

export const useCandidateStore = defineStore('candidate', () => {
  const companyStore = useCompanyStore()

  function getApiBase() {
    return import.meta.env.VITE_API_BASE_URL || 'http://localhost:8085'
  }

  // Opret kandidat på aktivt job
  async function addCandidate(payload) {
    try {
      const jobId = companyStore.activePosition?.id || null

      const body = {
        ...payload,
        status: payload.status, // 'Kontakt' / 'Afventer' / 'Accepteret' / 'Afvist'
        job_id: jobId
      }

      console.log('ACTIVE POSITION:', companyStore.activePosition)
      console.log('JOB ID SENT:', jobId)
      console.log('FULL BODY:', body)

      const base = getApiBase()
      const url = `${base}/api/candidates`

      const res = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(body),
        credentials: 'include'
      })

      if (!res.ok) {
        const text = await res.text()
        console.error('Add candidate failed:', res.status, text)
        throw new Error('Add candidate failed')
      }

      const newCandidate = await res.json()

      if (jobId) {
        await companyStore.fetchCandidatesForPosition(jobId)
      }

      return newCandidate
    } catch (err) {
      console.error('addCandidate error:', err)
      throw err
    }
  }

  // Opdater status på kandidat (application)
  async function updateStatus(id, newStatus) {
    try {
      const base = getApiBase()
      const url = `${base}/api/candidates/${id}/status`

      const res = await fetch(url, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ status: newStatus }), // også dansk
        credentials: 'include'
      })

      if (!res.ok) throw new Error('Status update failed')

      const jobId = companyStore.activePosition?.id || null
      if (jobId) {
        await companyStore.fetchCandidatesForPosition(jobId)
      }

      return true
    } catch (err) {
      console.error('updateStatus error:', err)
      return false
    }
  }

  return { addCandidate, updateStatus }
})
