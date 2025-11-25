<script setup>
import Button from '@/components/atoms/Button.vue'
import { useRouter } from 'vue-router'
const router = useRouter()

function goHome() {
    router.push('/')
}
</script>

<template>
    <div class="notFoundPage">
        <h1>404</h1>
        <h2>Siden blev ikke fundet</h2>
        <p>Ups! Den side, du leder efter, findes ikke.</p>
        <Button label="Gå til forsiden" type="default" @click="goHome" aria-label="Gå til forsiden" />
    </div>
</template>

<style scoped lang="scss">
.notFoundPage {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 70vh;
    text-align: center;
    gap: 16px;

    h1 {
        @include heading1;
        color: $primaryBlue;
    }

    h2 {
        @include heading2;
    }

    p {
        @include bodyText;
    }
}
</style>
