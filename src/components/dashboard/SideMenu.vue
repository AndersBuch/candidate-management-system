<script setup>
  import { storeToRefs } from 'pinia'
  import { useCompanyStore } from '@/stores/useCompanyStore'
  import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'

  const companyStore = useCompanyStore()
  const { companies, activeCompanyId, activePositionId } = storeToRefs(companyStore)

  const selectCompany = (id) => {
    companyStore.selectCompany(id)
  }

  const selectPosition = (companyId, positionId) => {
    companyStore.selectPosition(companyId, positionId)
  }
</script>

<template>
  <aside class="sideMenu">
    <!-- Logo / top -->
    <div class="logoArea">
      <BasicIconAndLogo name="MainLogo" :large="true" />
    </div>

    <!-- Firmaer og stillinger -->
    <section class="menuSection">
      <div class="menuSectionInner">
        <h2 class="menuTitle">Firma</h2>

        <ul class="companyList">
          <li
            v-for="company in companies"
            :key="company.id"
            class="companyItem"
            :class="{ activeCompanySection: company.id === activeCompanyId }"
          >
            <!-- Firma-knap -->
            <button
              class="companyButton"
              @click="selectCompany(company.id)"
            >
              <span class="companyName">{{ company.name }}</span>
            </button>

            <!-- Stillinger -->
            <ul
              v-if="company.id === activeCompanyId"
              class="positionList"
            >
              <li
                v-for="position in company.positions"
                :key="position.id"
                class="positionItem"
              >
                <button
                  class="positionButton"
                  :class="{ isActivePosition: position.id === activePositionId }"
                  @click="selectPosition(company.id, position.id)"
                >
                  <span class="positionName">{{ position.name }}</span>
                </button>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </section>

    <!-- Tilføje firma -->
    <section class="menuSection">
      <div class="menuSectionInner">
        <h2 class="menuTitle">Tilføje Firma</h2>
        <div class="divider"></div>
        <button class="addCompany">+ Tilføje Firma</button>
      </div>
    </section>

    <!-- Tools -->
    <section class="menuSection">
      <div class="menuSectionInner">
        <h2 class="menuTitle">Tools</h2>
        <div class="divider"></div>
        <button class="logoutButton">⏎ Log ud</button>
      </div>
    </section>
  </aside>
</template>

<style scoped lang="scss">
.sideMenu {
  width: 350px;
  min-width: 350px;
  border-right: 2px solid $lightGrey;
  display: flex;
  flex-direction: column;
  align-items: stretch; // alt fylder 100%
  height: 100vh;
}

.logoArea {
  padding: 40px 0;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
}

.menuSection {
  display: flex;
  justify-content: center; // centrer indre kolonne vandret
  align-items: center;
  flex-direction: column;
  width: 100%;
  margin-bottom: 20px;
}

/* Indre kolonne som ligger i midten, men er venstrejusteret */
.menuSectionInner {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  width: 100%;
  max-width: 125px;  // god bredde til menuindhold
  margin: 0 auto;
}

.divider {
  width: 100%;
  height: 2px;
  background-color: $lightGrey;
  margin-bottom: 10px;
}

.menuTitle {
  @include bigBodyText;
  color: $black;
}

.companyList,
.positionList {
  list-style: none;
  padding: 0;
  margin: 0;
}

.companyItem {
  margin-bottom: 6px;
}

/* Basis-stil for knapper */
.companyButton,
.positionButton,
.addCompany,
.logoutButton {
  border: none;
  padding: 6px 0;
  background: none;
  cursor: pointer;
  text-align: left;
  @include bodyText;
  color: $black;
}

/* Hele sektionen for aktivt firma → blå tekst */
.companyItem.activeCompanySection {
  .companyButton,
  .positionButton,
  .companyName,
  .positionName {
    color: $primaryBlue;
  }
}

.positionList {
  margin-left: 16px; // indrykning af stillinger

  .positionItem {
    @include bodyText;
    margin: 4px 0;
  }
}

/* Aktiv stilling → kun bold (farven arves fra firma-sektionen) */
.positionButton.isActivePosition {
  @include boldBodyText;
}
</style>
