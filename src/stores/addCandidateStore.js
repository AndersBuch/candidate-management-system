// src/stores/addCandidateStore.js
import { defineStore } from 'pinia'
import { useCompanyStore } from '@/stores/useCompanyStore'

export const useCandidateStore = defineStore('candidate', () => {
  const companyStore = useCompanyStore()

  // Opret kandidat på aktivt job (JSON)
  async function addCandidate(payload) {
    try {
      const jobId = companyStore.activePosition?.id || null

      const body = {
        ...payload,
        status: payload.status,
        job_id: jobId
      }

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

      if (!res.ok) {
        console.error('Update failed:', await res.text())
        throw new Error('Update failed')
      }

      const jobId = companyStore.activePosition?.id || null
      if (jobId) {
        await companyStore.fetchCandidatesForPosition(jobId)
      }

      return true
    } catch (err) {
      console.error('updateCandidate error:', err)
      return false
    }
  }

  // ✅ Opret kandidat + filer (FormData)
  async function addCandidateWithFiles(payload, files) {
    const jobId = companyStore.activePosition?.id || null

    const fd = new FormData()

    Object.entries({
      ...payload,
      status: payload.status,
      job_id: jobId
    }).forEach(([k, v]) => {
      if (v !== undefined && v !== null) fd.append(k, v)
    })

    // filer: { cv, ansogning, photo, andet: [] }
    if (files?.cv) fd.append('cv', files.cv)
    if (files?.ansogning) fd.append('ansogning', files.ansogning)
    if (files?.photo) fd.append('photo', files.photo)

    // ✅ vigtig ændring: brug "andet" (ikke andet[])
    if (files?.andet?.length) {
      files.andet.forEach((f) => fd.append('andet', f))
    }

    const res = await fetch('/api/candidates', {
      method: 'POST',
      body: fd,
      credentials: 'include'
    })

    if (!res.ok) {
      console.error('addCandidateWithFiles failed:', await res.text())
      throw new Error('addCandidateWithFiles failed')
    }

    const created = await res.json()

    if (jobId) await companyStore.fetchCandidatesForPosition(jobId)
    return created
  }

  // ✅ Update kandidat + filer (FormData)
  async function updateCandidateWithFiles(candidateId, payload, files) {
    // ✅ vigtig: payload skal indeholde application_id
    if (!payload?.application_id) {
      console.warn(
        'updateCandidateWithFiles: payload.application_id mangler. Dokument-upload kan ikke placeres korrekt.'
      )
    }

    const fd = new FormData()

    // Append payload fields (skip undefined/null)
    Object.entries(payload).forEach(([k, v]) => {
      if (v !== undefined && v !== null) {
        fd.append(k, v)
      }
    })

    // Debug: log what's being appended
    console.log('FormData payload fields:', Object.keys(payload))
    console.log('FormData files before append:', { 
      cv: files?.cv instanceof File ? files.cv.name : files?.cv,
      ansogning: files?.ansogning instanceof File ? files.ansogning.name : files?.ansogning,
      photo: files?.photo instanceof File ? files.photo.name : files?.photo,
      andet: files?.andet?.filter(f => f instanceof File).map(f => f.name) || []
    })

    // Append file fields - ONLY if they are File instances
    if (files?.cv && files.cv instanceof File) {
      fd.append('cv', files.cv)
      console.log('✅ Appended cv:', files.cv.name)
    }
    if (files?.ansogning && files.ansogning instanceof File) {
      fd.append('ansogning', files.ansogning)
      console.log('✅ Appended ansogning:', files.ansogning.name)
    }
    if (files?.photo && files.photo instanceof File) {
      fd.append('photo', files.photo)
      console.log('✅ Appended photo:', files.photo.name)
    }

    // Append andet array - ONLY valid File instances
    if (files?.andet && Array.isArray(files.andet) && files.andet.length > 0) {
      const validFiles = files.andet.filter(f => f instanceof File)
      validFiles.forEach((f) => {
        fd.append('andet', f)
        console.log('✅ Appended andet:', f.name)
      })
      console.log(`Appended ${validFiles.length} andet files`)
    }

    console.log('Final FormData ready for POST to /api/candidates/' + candidateId)

    const res = await fetch(`/api/candidates/${candidateId}`, {
      method: 'PUT',
      body: fd,
      credentials: 'include'
      // NOTE: Do NOT set Content-Type header - browser will set it with boundary for FormData
    })

    if (!res.ok) {
      const errorText = await res.text()
      console.error('updateCandidateWithFiles failed:', errorText)
      console.error('Status:', res.status, res.statusText)
      throw new Error(`updateCandidateWithFiles failed: ${errorText}`)
    }

    const jobId = companyStore.activePosition?.id || null
    if (jobId) await companyStore.fetchCandidatesForPosition(jobId)

    return true
  }

  // docs
  async function fetchDocuments(applicationId) {
    const res = await fetch(`/api/applications/${applicationId}/documents`, {
      method: 'GET',
      credentials: 'include'
    })
    if (!res.ok) throw new Error(await res.text())
    return await res.json()
  }

  async function deleteDocument(documentId) {
    const res = await fetch(`/api/documents/${documentId}`, {
      method: 'DELETE',
      credentials: 'include'
    })
    if (!res.ok) throw new Error(await res.text())
    return true
  }

  return {
    addCandidate,
    addCandidateWithFiles,
    updateStatus,
    deleteCandidate,
    updateCandidate,
    updateCandidateWithFiles,
    fetchDocuments,
    deleteDocument
  }
})
