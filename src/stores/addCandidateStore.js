// src/stores/addCandidateStore.js
import { defineStore } from 'pinia'
import { useCompanyStore } from '@/stores/useCompanyStore'

export const useCandidateStore = defineStore('candidate', () => {
  const companyStore = useCompanyStore()

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

      const url = `/api/candidates`

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
  async function updateStatus(applicationId, newStatus) {
    try {

      const url = `/api/candidates/${applicationId}`

      const res = await fetch(url, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ status: newStatus }),
        credentials: 'include'
      })

      if (!res.ok) {
        console.error('Update status failed:', await res.text())
        throw new Error('Update status failed')
      }

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


  async function deleteCandidate(id) {
  try {
    
    const url = `/api/candidates/${id}`

    const res = await fetch(url, {
      method: 'DELETE',
      credentials: 'include'
    })

    if (!res.ok) {
      console.error('Delete failed:', await res.text())
      throw new Error('Delete failed')
    }

    const jobId = companyStore.activePosition?.id || null
    if (jobId) {
      await companyStore.fetchCandidatesForPosition(jobId)
    }

    return true
  } catch (err) {
    console.error('deleteCandidate error:', err)
    return false
  }
}

async function updateCandidate(id, payload) {
  try {
    
    const url = `/api/candidates/${id}`

    const res = await fetch(url, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
      credentials: 'include'
    })

    if (!res.ok) throw new Error('Update failed')

    const jobId = companyStore.activePosition?.id || null
    if (jobId) {
      await companyStore.fetchCandidatesForPosition(jobId)
    }

    return true
  } catch (err) {
    console.error("updateCandidate error:", err)
    return false
  }
}


  return { addCandidate, updateStatus, deleteCandidate, updateCandidate }
})
