<script setup>
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'

// Props gør komponentet genbrugeligt
const props = defineProps({
    icon: { type: Object, required: true },
    title: { type: String, required: true },
    subtitle: { type: String, required: true },
    count: { type: Number, required: true },
})
</script>

<template>
    <div class="cardWrapper">
        <div class="icon">
            <BasicIconAndLogo name="UserWhite" :large="bigIconSize" />
        </div>

        <h3 class="title">{{ title }}</h3>
        <p class="subtitle">{{ subtitle }}</p>

        <div class="count">{{ count }}</div>
    </div>
</template>


<style scoped lang="scss">
.cardWrapper {
    width: 380px;
    padding: 20px;
    background: $whiteColor;
    border-radius: 15px;
    text-align: center;
    box-shadow: $boxShadow;

    .icon {
        width: 44px;
        height: 44px;
        margin: 0 auto;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: $primaryBlue;
    }

    .title {
        margin: 10px 0 0 0;
        @include bigBodyText;
    }

    .subtitle {
        @include bodyText;
        color: $darkGrey;
    }

    .count {
        margin-top: 5px;
        background: $sekundareBlue;
        display: inline-block;
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 18px;
        @include boldBodyText;
    }

}
</style>
