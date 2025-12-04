<script setup>
import Wigdet from '@/components/dashboard/Wigdet.vue'
</script>

<template>
    <div class="wigdetFrom">
        <h3>Hurtige genveje:</h3>
        <div class="wigdetRow">
            <Wigdet icon="BoxWhite" title="Insatech A/S" subtitle="Maskinmester" showButton buttonLabel="Gå til siden"
                :buttonClick="handleEdit" />
            <Wigdet icon="BoxWhite" title="Lihme Protein Solutions ApS" subtitle="Maskinmester" showButton buttonLabel="Gå til siden"
                :buttonClick="handleEdit" />
            <Wigdet icon="BoxWhite" title="Regal A/S" subtitle="Maskinmester" showButton buttonLabel="Gå til siden"
                :buttonClick="handleEdit" />
        </div>
    </div>
</template>


<style scoped lang="scss">
.wigdetFrom {
    width: 1264px;
    background-color: $sekundareBlue;
    padding: 20px;
    border-radius: 15px;
    margin-top: 20px;

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