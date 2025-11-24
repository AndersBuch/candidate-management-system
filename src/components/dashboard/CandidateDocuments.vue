<script setup>
import Button from '@/components/atoms/Button.vue'

import { ref } from 'vue'

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
      class="documentsSection"
    >
      <h3 class="documentsTitle">{{ section.title }}</h3>

      <ul class="documentsList">
        <li
          v-for="file in section.files"
          :key="file.id"
          class="documentsItem"
        >
          <a
            class="documentsFileLink"
            :href="file.url"
            download
          >
            {{ file.name }}
          </a>

          <Button type="smallDashboard" label="Download" aria-label="Download dokumenter" />
        </li>
      </ul>
    </div>
  </div>
</template>

<style scoped lang="scss">
.documents {
  margin-top: 24px;

}

.documentsSection {
  margin-top: 24px;
}

.documentsTitle {
  @include boldBodyText;
  margin-bottom: 4px;
}

/* Liste med filer */
.documentsList {
  list-style: none;
  padding: 0;
  margin: 0;
}

.documentsItem {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  padding: 10px 0px;
}

/* Filnavn */
.documentsFileLink {
  color: $goodGreen;
  text-decoration: underline;
  overflow-x: hidden;
  @include bodyText;
}

</style>
