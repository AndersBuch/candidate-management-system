<script setup>
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'
import { onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import { useCompanyStore } from '@/stores/useCompanyStore'

const companyStore = useCompanyStore()
const { companies, activeCompanyId, activePositionId } = storeToRefs(companyStore)

const selectCompany = (id) => {
  companyStore.selectCompany(id)
}

const selectPosition = (companyId, positionId) => {
  companyStore.selectPosition(companyId, positionId)
}

onMounted(() => {
  companyStore.fetchCompanies()
})
</script>

<template>
  <aside class="sideMenu">
    <div class="logoArea">
      <BasicIconAndLogo name="MainLogo" :large="true" />
    </div>

    <div class="menuSectionInner">
      <h2 class="menuTitle">Forside</h2>
      <div class="menuSectionInner">
        <RouterLink to="/homepage" v-slot="{ isActive }">
          <button class="addCompany" :class="{ activeMenu: isActive }">
            <BasicIconAndLogo name="Home" :iconSize="true" />
            Forside
          </button>
        </RouterLink>
      </div>
    </div>
    <div class="divider"></div>

    <!-- Firmaer og stillinger -->
    <section class="menuSection">

      <div class="menuSectionInner">
        <h2 class="menuTitle">Firma</h2>
      </div>

      <div class="divider"></div>

      <ul class="companyList">
        <li v-for="company in companies" :key="company.id" class="companyItem"
          :class="{ activeCompanySection: company.id === activeCompanyId }">
          <div class="menuSectionInner">
            <RouterLink to="/dashboardsite" v-slot="{ isActive }">
              <button class="companyButton" @click="selectCompany(company.id)">
                <BasicIconAndLogo name="Box" :iconSize="true" />
                <span class="companyName">{{ company.name }}</span>
              </button>
            </RouterLink>
          </div>

          <!-- FULL-WIDTH divider -->
          <div v-if="company.id === activeCompanyId" class="activeDivider"></div>

          <!-- Stillinger -->
          <div v-if="company.id === activeCompanyId" class="menuSectionInner">
            <ul class="positionList">
              <li v-for="position in company.positions" :key="position.id" class="positionItem">
                <button class="positionButton" :class="{ isActivePosition: position.id === activePositionId }"
                  @click="selectPosition(company.id, position.id)">
                  <BasicIconAndLogo name="Users" :iconSize="true" />
                  <span class="positionText">{{ position.name }}</span>
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
        <button class="addCompany">
          <BasicIconAndLogo name="Plus" :iconSize="true" />
          Tilføj Firma
        </button>
      </div>
    </section>

    <!-- Tools -->
    <section class="menuSection">
      <div class="menuSectionInner">
        <h2 class="menuTitle">Tools</h2>
      </div>
      <div class="divider"></div>
      <div class="menuSectionInner">
        <button class="logoutButton">
          <BasicIconAndLogo name="User" :iconSize="true" />
          Din Profil
        </button>
        <button class="logoutButton">
          <BasicIconAndLogo name="Logout" :iconSize="true" />
          Log ud
        </button>
      </div>
    </section>
  </aside>
</template>

<style scoped lang="scss">
.addCompany.activeMenu {
  color: $primaryBlue; // tekst bliver blå
  margin-bottom: 20px;
  transition: all 0.3s ease; // <- smooth transition

  :deep(path),
  :deep(circle),
  :deep(rect) {
    stroke: $primaryBlue !important; // ikon bliver blå
    transition: stroke 0.3s ease; // <- smooth for ikonet
  }
}

.sideMenu {
  width: 350px;
  min-width: 350px;
  border-right: 2px solid $lightGrey;
  display: flex;
  flex-direction: column;
  align-items: stretch;
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
  justify-content: center;
  align-items: center;
  flex-direction: column;
  width: 100%;
  margin-bottom: 20px;
}

.menuSectionInner {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  width: 100%;
  max-width: 125px;
  margin: 0 auto;
}

.divider {
  width: 100%;
  height: 2px;
  background-color: $lightGrey;
  margin-bottom: 20px;
}

.activeDivider {
  width: 100%;
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
  display: grid;
  grid-template-columns: 24px 1fr;
  column-gap: 6px;
  align-items: center;
  width: 100%;
  box-sizing: border-box;
  transition: all 0.3s ease; // <- smooth transition til alle knapper
}

.positionButton {
  padding-left: 16px;
}

.companyName,
.positionText {
  min-width: 0;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  transition: color 0.3s ease; // <- smooth farveskift
}

.companyItem.activeCompanySection {

  .companyButton,
  .positionButton,
  .companyName,
  .positionText {
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
    stroke: $primaryBlue;
    fill: transparent;
    transition: stroke 0.3s ease; // <- smooth ikon-transition
  }
}

.companyButton :deep(svg),
.positionButton :deep(svg),
.addCompany :deep(svg),
.logoutButton :deep(svg) {
  width: 24px;
  height: 24px;
  flex-shrink: 0;
  fill: $whiteColor;
  transition: fill 0.3s ease; // <- smooth farveskift for ikon
}

.positionList {
  margin-left: 0;
  width: 100%;

  .positionItem {
    @include bodyText;
    margin: 4px 0;
  }
}

.positionButton.isActivePosition {
  @include boldBodyText;
}
</style>
