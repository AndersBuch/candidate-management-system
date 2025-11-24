<script setup>
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'
import DefinitionRow from '@/components/atoms/DefinitionRow.vue'
import CandidateDocuments from '@/components/dashboard/CandidateDocuments.vue'


</script>

<template>

<aside class="exstendedCandidateContainer">

<section class="flexContainer flexContainer--center">
  <BasicIconAndLogo name="User" :iconSize="true" />
  <h2 class="adminName">Claus Bjerring - Admin</h2>
</section>

<div class="divider"></div>

<section class="flexContainer flexContainer--center">
  <div class="iconContainer">
    <BasicIconAndLogo name="Edit" :iconSize="true" />
    <BasicIconAndLogo name="Thash" :iconSize="true" />
  </div>
  <img class="profilePicture" src="/img/TestProfilePicture.jpg" alt="Candidate profile picture">
  <h3 class="candidateName">Mads Mikkelsen Hansen</h3>
  <BasicIconAndLogo name="LinkinIcon" :iconSize="true" />
</section>

<div class="divider"></div>

<section class="flexContainer flexContainer--left ">
  <dl class="info-grid">
  <DefinitionRow label="Alder" value="" />
  <DefinitionRow label="Køn" value="Mand" />
  <DefinitionRow label="Telefon" value="11223344" />
  <DefinitionRow label="Status" value="Afventer" />
  <DefinitionRow label="Email" value="Madharenmail@gmail.com" full  />
  <DefinitionRow label="Adresse" value="Mullervej 2"  />
  <DefinitionRow label="Postnummer" value="5230" />
  <DefinitionRow label="By" value="Odense" />
  <DefinitionRow label="Nuværende firma" value=""  />
</dl>
</section>

<section class="flexContainer  flexContainer--left note">
  <div class="note-header">
    <span class="note-label">Note</span>
    <BasicIconAndLogo name="Edit" :iconSize="true" />
  </div>

  <p class="note-text">
    Brænder du for at arbejde med procesudstyr og bidrage til udviklingen af
    fremtidens fødevaretekno-logi? Har du erfaring med at styre og overvåge
    produktionsprocesser?
  </p>
</section>

<section class="flexContainer flexContainer--left  note">
  <CandidateDocuments />
</section>

</aside>


</template>

<style scoped lang="scss">

.exstendedCandidateContainer {
  height: 100vh;
  max-width: 500px;
  overflow-y: auto;
  margin: 0 auto; 
}

.exstendedCandidateContainer::-webkit-scrollbar {
  display: none;
}

.exstendedCandidateContainer {
  scrollbar-width: none;
  -ms-overflow-style: none;

}

.flexContainer--center {
  align-items: center;
}

.flexContainer--left {
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

.info-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;  /* 2 kolonner */
  column-gap: 40px;
  row-gap: 24px;
}

.flexContainer.note {
  margin-top: 24px;
}

.flexContainer.note:last-child {
  padding-bottom: 20px;
}

.note-header {
  display: flex;
  align-items: center;
  gap: 4px;
}


.note-label {
  @include boldBodyText;
}

.note-text {
  
  @include bodyText;

}

</style>