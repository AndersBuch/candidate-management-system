<script setup>
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'
import DefinitionRow from '@/components/atoms/DefinitionRow.vue'
import CandidateDocuments from '@/components/dashboard/CandidateDocuments.vue'
import EditModal from '@/components/dashboard/EditModal.vue'
import DeleteModal from '@/components/dashboard/DeleteModal.vue'

import { ref } from 'vue'

const props = defineProps({
  activeIndex: { type: [Number, String, null], default: null },
  candidate: {
    type: Object,
    default: () => ({
      firstName: 'Mads',
      lastName: 'Mikkelsen Hansen',
      age: '',
      gender: 'Mand',
      phone: '11223344',
      status: 'Afventer',
      email: 'Madharenmail@gmail.com',
      address: 'Mullervej 2',
      postal: '5230',
      city: 'Odense',
      company: '',
      note: 'Brænder du for at arbejde med procesudstyr og bidrage til udviklingen af fremtidens fødevaretekno-logi? Har du erfaring med at styre og overvåge produktionsprocesser?',
      profilePicture: '/img/TestProfilePicture.jpg',
      linkedin: ''
    })
  }
})

const showEditModal = ref(false)
const showDeleteModal = ref(false)

const openEditModal = () => showEditModal.value = true
const closeEditModal = () => showEditModal.value = false

const openDeleteModal = () => showDeleteModal.value = true
const closeDeleteModal = () => showDeleteModal.value = false
</script>

<template>
  <aside v-if="props.activeIndex !== null" class="exstendedCandidateContainer">

    <section class="flexContainer flexContainerCenter">
      <BasicIconAndLogo name="User" :iconSize="true" />
       <h2 class="adminName">Claus Bjerring - Admin</h2>
    </section>

    <div class="divider"></div>

    <section class="flexContainer flexContainerCenter">
      <div class="iconContainer">
        <EditModal @click.native="openEditModal"/>
        <DeleteModal @click.native="openDeleteModal"/>
      </div>
   <img class="profilePicture" src="/img/TestProfilePicture.jpg" alt="Candidate profile picture">      <h3 class="candidateName">{{ candidate.firstName }} {{ candidate.lastName }}</h3>
      <BasicIconAndLogo name="LinkinIcon" :iconSize="true" v-if="candidate.linkedin"/>
    </section>

    <div class="divider"></div>

    <section class="flexContainer flexContainerLeft ">
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
      <div class="noteHeader">
        <span class="noteLabel">Note</span>
        <BasicIconAndLogo name="Edit" :iconSize="true" @click="openEditModal" />
      </div>
      <p class="noteText">{{ candidate.note }}</p>
    </section>

    <section class="flexContainer flexContainerLeft note">
      <CandidateDocuments />
    </section>

    <!-- Modals -->
    <EditModal v-if="showEditModal" :candidate="candidate" @close="closeEditModal"/>
    <DeleteModal v-if="showDeleteModal" :candidateId="candidate.id" @close="closeDeleteModal"/>
  </aside>
</template>


<style scoped lang="scss">
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