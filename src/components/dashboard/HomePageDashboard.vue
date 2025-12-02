<script setup>
import DashboardHeader from '@/components/dashboard/DashboardHeader.vue'
import SideMenu from '@/components/dashboard/SideMenu.vue'
import Wigdet from '@/components/dashboard/Wigdet.vue'


import { ref } from 'vue'


</script>

<template>
    <div class="dashboardLayout">
        <SideMenu />

        <section class="dashboardContentWrapper">
            <div class="dashboardContent">
                <DashboardHeader />
                <h1>Velkommen tilbage</h1>
                <h2>Claus Hansen</h2>
                <div class="divider"></div>
            </div>
                      <Wigdet
    :icon="User"
    title="Kandiddsadater"
    subtitle="Antalet af alle kandidater"
    :count="43"
  />
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

.dashboardContent {
    text-align: center;

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
</style>