<script setup>
import FormField from './FormField.vue'
</script>

<template>
  <div class="formSection">
    <h2>Formular</h2>
    <form class="customForm">
      <FormField id="name" label="Navn" placeholder="Indtast dit navn" />
      <FormField id="email" label="Email" placeholder="Indtast email" fieldType="email" />
      <FormField id="phone" label="Telefon" placeholder="Indtast telefon" fieldType="tel" />
      <FormField id="address" label="Adresse" placeholder="Indtast adresse" />
      <FormField id="city" label="By" placeholder="Indtast by" />
      <FormField id="postal" label="Postnummer" placeholder="Indtast postnummer" />
      <FormField id="country" label="Land" placeholder="Indtast land" />
      <FormField id="message" label="Besked" placeholder="Skriv din besked" fieldType="textarea" />
      <button type="submit">Send</button>
    </form>
  </div>
</template>

<style scoped lang="scss">
.customForm {
  display: flex;
  flex-direction: column;
  gap: 1rem;

  button {
    width: 120px;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    background-color: $primaryBlue;
    color: white;
    border: none;
    cursor: pointer;

    &:hover {
      background-color: lighten($primaryBlue, 10%);
    }
  }
}
</style>
