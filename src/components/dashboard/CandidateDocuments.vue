<!-- components/dashboard/CandidateDocuments.vue -->
<script setup>
import { ref } from 'vue'
import Button from '@/components/atoms/Button.vue'

const sections = ref([
  {
    id: 'cv',
    title: 'CV',
    files: [
      {
        id: 'cv-1',
        name: 'MadsmikkeldsenCV.pdf',
        url: '/dummy/MadsmikkeldsenCV.pdf', 
      },
    ],
  },
  {
    id: 'application',
    title: 'Ansøgning',
    files: [
      {
        id: 'application-1',
        name: 'MadsMikkelsenAnsøgning.pdf',
        url: '/dummy/MadsMikkelsenAnsøgning.pdf',
      },
    ],
  },
  {
    id: 'other',
    title: 'Andre dokumenter',
    files: [
      {
        id: 'other-1',
        name: 'TidligereJobs.pdf',
        url: '/dummy/TidligereJobs.pdf',
      },
      {
        id: 'other-2',
        name: 'Anbefanlinger.pdf',
        url: '/dummy/Anbefanlinger.pdf',
      },
      {
        id: 'other-3',
        name: 'Bevis.pdf',
        url: '/dummy/Bevis.pdf',
      },
    ],
  },
])
</script>

<template>
  <div class="documents">
    <div
      v-for="section in sections"
      :key="section.id"
      class="documents__section"
    >
      <h3 class="documents__title">{{ section.title }}</h3>

      <ul class="documents__list">
        <li
          v-for="file in section.files"
          :key="file.id"
          class="documents__item"
        >
          <a
            class="documents__file-link"
            :href="file.url"
            download
          >
            {{ file.name }}
          </a>

          <Button type="smalldashboard" label="Download" aria-label="Download" />
        </li>
      </ul>
    </div>
  </div>
</template>

<style scoped lang="scss">
.documents {
  margin-top: 24px;

}

.documents__section + .documents__section {
  margin-top: 24px;
}

.documents__title {
  @include boldBodyText;
  margin-bottom: 4px;
}

/* Liste med filer */
.documents__list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.documents__item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  margin-top: 4px;
}

/* Filnavn */
.documents__file-link {
  color: $goodGreen;
  text-decoration: underline;
  overflow-x: hidden;
  @include bodyText;
}

</style>
