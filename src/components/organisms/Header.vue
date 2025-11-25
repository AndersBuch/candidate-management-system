<script setup>
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'

import { ref } from 'vue'
</script>

<template>
  <header>
    <div class="headerContainer">
      <RouterLink to="/">
        <BasicIconAndLogo name="MainLogo" :large="true" />
      </RouterLink>
      <nav>
        <a href="#">Om Profil Search</a>
        <a href="#">Arbejdsmetoder</a>
        <a href="#">Tilmeld dig databasen</a>
        <a href="#">Kontakt</a>
        <a href="#">Aktuelle stillinger</a>
      </nav>
    </div>
  </header>
</template>


<style lang="scss">
header {
  width: 100%;
  background-color: $whiteColor;
  height: 80px;

  .headerContainer {
    max-width: 1450px;
    height: 80px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  nav {
    display: flex;
    gap: 16px;
    align-items: center; 

    a {
      display: flex;
      align-items: center;
      justify-content: center;
      color: $black;
      text-decoration: none;
      transition: color 0.2s ease-in-out;
      transition: background-color 0.2s ease-in-out;
      padding: 0 8px;
      @include bodyText;

      &:hover {
        color: rgba($black, 0.7);
      }

      &:last-child {
        background-color: $primaryBlue;
        color: $whiteColor;
        @include boldBodyText;
        padding: 0 20px;
        height: 40px;
        border-radius: 15px;

        &:hover {
          background-color: $hoverBlue;
        }
      }

      &:nth-child(2) {
        color: $primaryBlue;


        &:hover {
          color: $hoverBlue;
        }
      }
    }
  }
}
</style>
