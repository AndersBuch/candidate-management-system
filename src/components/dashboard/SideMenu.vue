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
  </div>

  <div class="divider"></div>

  <!-- ⬇️ companyList er flyttet ud af menuSectionInner -->
  <ul class="companyList">
    <li
      v-for="company in companies"
      :key="company.id"
      class="companyItem"
      :class="{ activeCompanySection: company.id === activeCompanyId }"
    >
      <!-- Tekst + ikon i centreret kolonne -->
      <div class="menuSectionInner">
        <button
          class="companyButton"
          @click="selectCompany(company.id)"
        >
          <BasicIconAndLogo name="Box" :iconSize="true" />
          <span class="companyName">{{ company.name }}</span>
        </button>
      </div>

      <!-- FULL-WIDTH divider -->
      <div
        v-if="company.id === activeCompanyId"
        class="activeDivider"
      ></div>

      <!-- Stillinger, også i samme smalle kolonne -->
      <div
        v-if="company.id === activeCompanyId"
        class="menuSectionInner"
      >
        <ul class="positionList">
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
              <span class="positionName">
                <BasicIconAndLogo name="Users" :iconSize="true" />
                {{ position.name }}
              </span>
            </button>
          </li>
        </ul>
      </div>
    </li>
  </ul>
</section>

    <!-- Tilføje firma -->
    <section class="menuSection">
      <div class="menuSectionInner">
          <h2 class="menuTitle">Tilføj Firma</h2>
      </div>
      <div class="divider"></div>
      <div class="menuSectionInner">
        <button class="addCompany"><BasicIconAndLogo name="Plus" :iconSize="true" />Tilføj Firma</button>
      </div>
    </section>

    <!-- Tools -->
    <section class="menuSection">
      <div class="menuSectionInner">
        <h2 class="menuTitle">Tools</h2>
      </div>
        <div class="divider"></div>
      <div class="menuSectionInner">
        <button class="logoutButton"><BasicIconAndLogo name="Logout" :iconSize="true" /> Log ud</button>
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

.activeDivider {
  width: 100%;     // NU er det hele menuSection
  height: 2px;
  background-color: $lightGrey;
  margin: 4px 0 8px 0;
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

    /* ikon + tekst på samme linje */
  display: flex;
  align-items: center;
  gap: 6px;  // afstand mellem ikon og tekst
  width: 100%;
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

.companyItem.activeCompanySection {
  .companyButton :deep(path),
  .companyButton :deep(circle),
  .companyButton :deep(rect),
  .positionButton :deep(path),
  .positionButton :deep(circle),
  .positionButton :deep(rect) {
    stroke: $primaryBlue;      // farven på stregerne
    fill: transparent;         // ingen solid baggrund
  }
}

/* Ikonerne inde i BasicIconAndLogo (pga. scoped skal vi bruge :deep) */
.companyButton :deep(svg),
.positionButton :deep(svg) {
  width: 18px;
  height: 18px;
  flex-shrink: 0;
  fill: $whiteColor;
  
}

/* Stillingsnavn (ikon + tekst) på linje – hvis du vil have ekstra kontrol */
.positionName {
  display: flex;
  align-items: center;
  gap: 6px;
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
