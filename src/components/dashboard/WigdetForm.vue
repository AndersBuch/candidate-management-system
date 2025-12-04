<script setup>
import Wigdet from '@/components/dashboard/Wigdet.vue'
</script>

<template>
    <div class="wigdetFrom">
        <h3>Dine kandidate oprettelser:</h3>
        <div class="wigdetRow">
            <Wigdet icon="UserWhite" title="Kandidater" subtitle="Antallet af alle kandidater" :count="432"
                :forceNeutral="true" />
            <Wigdet icon="UserWhite" title="Kandidater oprettet" subtitle="Antalet fra den seneste uge" :count="2" />
            <Wigdet icon="UserWhite" title="Kandidater slettet" subtitle="Antalet fra den seneste uge" :count="-22" />
        </div>
    </div>
</template>


<style scoped lang="scss">
.wigdetFrom {
    width: 1264px;
    background-color: $sekundareBlue;
    padding: 20px;
    border-radius: 15px;

    display: flex;
    flex-direction: column;
    gap: 20px;

    h3 {
        @include heading3;
        margin: 0;
    }

    .wigdetRow {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
    }
}
</style>