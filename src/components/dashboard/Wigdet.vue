<script setup>
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'
import Button from '@/components/atoms/Button.vue'

import { computed } from 'vue'

const props = defineProps({
    icon: { type: String, required: true },
    title: { type: String, required: true },
    subtitle: { type: String, required: true },
    count: { type: Number, default: 0 },          
    showButton: { type: Boolean, default: false }, 
    buttonLabel: { type: String, default: 'Annuller' }, 
    buttonClick: { type: Function },             
    positiveColor: { type: String, default: '#34C759' },
    negativeColor: { type: String, default: '#FF383C' },
    neutralColor: { type: String, default: '#D5E9FF' },
    forceNeutral: { type: Boolean, default: false }
})

const displayCount = computed(() => {
    if (props.forceNeutral) {
        return props.count   
    }

    if (props.count > 0) return `+${props.count}`
    if (props.count < 0) return `-${Math.abs(props.count)}`
    return props.count
})

const countColor = computed(() => {
    if (props.forceNeutral) {
        return props.neutralColor
    }

    if (props.count > 0) return props.positiveColor
    if (props.count < 0) return props.negativeColor
    return props.neutralColor
})


const countTextColor = computed(() => {
    if (props.forceNeutral) return '#333' // sort tekst i neutral mode
    if (props.count > 0) return '#fff'   // hvid tekst i grøn
    if (props.count < 0) return '#fff'   // hvid tekst i rød

    return '#333' // sort tekst i neutral
})
</script>

<template>
    <div class="cardWrapper">
        <div class="icon">
            <BasicIconAndLogo :name="icon" :bigIconSize="true" />
        </div>

        <h3 class="title">{{ title }}</h3>
        <p class="subtitle">{{ subtitle }}</p>

        <RouterLink v-if="showButton" to="/dashboardsite">
            <Button type="smallDashboard" :label="buttonLabel" :aria-label="buttonLabel" @click="buttonClick" />
        </RouterLink>

        <div v-else class="count" :style="{ backgroundColor: countColor, color: countTextColor }">
            {{ displayCount }}
        </div>
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
        padding: 6px 14px;
        display: inline-block;
        border-radius: 15px;
        @include boldBodyText;
        transition: background 0.2s;
    }

    Button {
        margin: 5px auto 0 auto; // centerer knappen horisontalt
    }
}
</style>
