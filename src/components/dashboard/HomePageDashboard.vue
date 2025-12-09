<script setup>
import DashboardHeader from '@/components/dashboard/DashboardHeader.vue'
import SearchBar from '@/components/dashboard/SearchBar.vue'
import SideMenu from '@/components/dashboard/SideMenu.vue'
import Wigdet from '@/components/dashboard/Wigdet.vue'
import WigdetForm from '@/components/dashboard/WigdetForm.vue'
import WigdetShortcut from '@/components/dashboard/WigdetShortcut.vue'


import { ref } from 'vue'


</script>

<template>
    <div class="dashboardLayout">
        <SideMenu />

        <section class="dashboardContentWrapper">
            <div class="dashboardHeaders">
                <SearchBar v-model="searchTerm" placeholder="Søg kandidat..." />
                <h1>Velkommen tilbage</h1>
                <h2>Claus Hansen</h2>
                <div class="divider"></div>
            </div>
            <div class="homepageContainer">
                <WigdetForm />
                <WigdetShortcut />
            </div>
        </section>

    </div>
</template>

<style scoped lang="scss">
.dashboardLayout {
    display: flex;
}

.dashboardContentWrapper {
    flex: 1;
}

.dashboardHeaders {
    text-align: center;
    padding: 20px 28px;

    h1 {
        @include heading1;
    }

    h2 {
        @include heading2;
        color: $primaryBlue;
        margin-bottom: 20px;
    }

    .divider {
        width: 90%;
        height: 2px;
        background-color: $lightGrey;
        margin-bottom: 10px;
    }
}

.homepageContainer {
    padding: 40px;
}
</style>