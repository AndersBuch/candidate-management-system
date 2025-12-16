<script setup>
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'
import DefinitionRow from '@/components/atoms/DefinitionRow.vue'
import CandidateDocuments from '@/components/dashboard/CandidateDocuments.vue'
import EditModal from '@/components/dashboard/EditModal.vue'
import DeleteModal from '@/components/dashboard/DeleteModal.vue'
import { ref, defineExpose } from 'vue'


const props = defineProps({
  candidate: {
    type: Object,
    required: true
  },
  activeIndex: {
    type: [Number, null],
    default: null
  }
})

const rootRef = ref(null)

defineExpose({
  rootRef
})

const emit = defineEmits(['candidateDeleted', 'deleteRequested', 'saved'])

const openEditModal = () => {
  showEditModal.value = true
}

const closeEditModal = () => {
  showEditModal.value = false
}

const showEditModal = ref(false)
const showDeleteModal = ref(false)

const openDeleteModal = () => {
  showDeleteModal.value = true
}

const handleCandidateDeleted = () => {
  emit('candidateDeleted')
  showDeleteModal.value = false
}

const handleSaved = () => {
  console.log('✅ ExtendedCandidateInfo handleSaved triggered, emitting saved')
  showEditModal.value = false
  emit('saved')
}


</script>

<template>

<aside class="exstendedCandidateContainer" ref="rootRef" @click.stop @pointerdown.stop>


    <section class="flexContainer flexContainerCenter">
      <BasicIconAndLogo name="User" :iconSize="true" />
      <h2 class="adminName">Claus Bjerring - Admin</h2>
    </section>

    <div class="divider"></div>

    <section class="flexContainer flexContainerCenter">
<div class="iconContainer">
  <!-- EDIT IKON -->
  <BasicIconAndLogo
    name="Edit"
    :iconSize="true"
    @click="openEditModal"
  />

  <!-- DELETE IKON -->
  <BasicIconAndLogo
    name="Thash"
    :iconSize="true"
    @click="openDeleteModal"
  />
</div>
      <img
        class="profilePicture"
        src="/img/TestProfilePicture.jpg"
        alt="Candidate profile picture"
      />
      <h3 class="candidateName">
        {{ candidate.firstName }} {{ candidate.lastName }}
      </h3>
      <BasicIconAndLogo
        name="LinkinIcon"
        :iconSize="true"
        v-if="candidate.linkedin"
      />

      <EditModal
  v-if="showEditModal"
  :candidate="candidate"
  @close="showEditModal = false"
  @saved="handleSaved"
/>

<DeleteModal
  v-if="showDeleteModal"
  :candidateId="candidate.id"
  :showTrigger="false"
  @close="showDeleteModal = false"
  @confirm="() => { emit('deleteRequested', candidate.id); showDeleteModal = false }"
/>

    </section>

    <div class="divider"></div>

    <section class="flexContainer flexContainerLeft">
      <dl class="infoGrid">
        <DefinitionRow label="Alder" :value="candidate.age" />
        <DefinitionRow label="Køn" :value="candidate.gender" />
        <DefinitionRow label="Telefon" :value="candidate.phone" />
        <DefinitionRow label="Status" :value="candidate.status" />
        <DefinitionRow label="Email" :value="candidate.email" full />
        <DefinitionRow label="Adresse" :value="candidate.address" />
        <DefinitionRow label="Postnummer" :value="candidate.postal" />
        <DefinitionRow label="By" :value="candidate.city" />
        <DefinitionRow label="Nuværende firma" :value="candidate.company" />
      </dl>
    </section>

    <section class="flexContainer flexContainerLeft note">
      <header class="noteHeader">
        <BasicIconAndLogo name="Note" :iconSize="true" />
        <span class="noteLabel">Note</span>
      </header>
      <p class="noteText">{{ candidate.note }}</p>
    </section>

    <section class="flexContainer flexContainerLeft note">
      <CandidateDocuments />
    </section>

  </aside>
</template>

<style scoped lang="scss">

  .toastWrapper {
    position: fixed;
    bottom: 20px;
    left: 20px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    z-index: 9999;
}

.exstendedCandidateContainer {
  height: 100vh;
  max-width: 500px;
  overflow-y: auto;
  margin: 0 auto;
  color: $black;
}

.exstendedCandidateContainer::-webkit-scrollbar {
  display: none;
}

.exstendedCandidateContainer {
  scrollbar-width: none;
  -ms-overflow-style: none;

}

.flexContainerCenter {
  align-items: center;
}

.flexContainerLeft {
  align-items: flex-start;
}

.flexContainer {
  display: flex;
  flex-direction: column;
  gap: 10px;
  padding: 0 80px;
  width: 100%;
}

.flexContainer:first-child {
  padding-top: 20px;
}

.adminName {
  @include boldBodyText;
}

.iconContainer {
  display: flex;
  gap: 10px;
  cursor: pointer;
}

.divider {
  height: 2px;
  width: 100%;
  background-color: $lightGrey;
  margin: 20px 0px;
}

.profilePicture {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  object-fit: cover;
  object-position: center;
  display: block;
}

.candidateName {
  @include boldBodyText;
}

.infoGrid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  /* 2 kolonner */
  column-gap: 40px;
  row-gap: 24px;
}

.flexContainer.note {
  margin-top: 24px;
}

.flexContainer.note:last-child {
  padding-bottom: 20px;
}

.noteHeader {
  display: flex;
  align-items: center;
  gap: 4px;
}

.noteLabel {
  @include boldBodyText;
}

.noteText {
  @include bodyText;
  color: $black
}
</style>
