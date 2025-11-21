<script setup>
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'
import DefinitionRow from '@/components/atoms/DefinitionRow.vue'

</script>

<template>

<aside class="exstendedCandidateContainer">

<section class="flexContainer">
  <BasicIconAndLogo name="User" :iconSize="true" />
  <h2 class="adminName">Claus Bjerring - Admin</h2>
</section>

<div class="divider"></div>

<section class="flexContainer">
  <div class="iconContainer">
    <BasicIconAndLogo name="Edit" :iconSize="true" />
    <BasicIconAndLogo name="Thash" :iconSize="true" />
  </div>
  <img class="profilePicture" src="/img/TestProfilePicture.jpg" alt="Candidate profile picture">
  <h3 class="candidateName">Mads Mikkelsen Hansen</h3>
  <BasicIconAndLogo name="LinkinIcon" :iconSize="true" />
</section>

<div class="divider"></div>

<section class="flexContainer">
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


</aside>


</template>

<style scoped lang="scss">



.flexContainer {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  gap: 10px;
  padding: 0 80px;
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

</style>