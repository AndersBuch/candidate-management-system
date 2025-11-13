<script setup>
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'
import FormField from '@/components/molecules/FormField.vue'
import InputField from '@/components/atoms/InputField.vue'
import Button from '@/components/atoms/Button.vue'
import Hero from '@/components/atoms/Hero.vue'

import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'

const rememberme = ref(false)

const router = useRouter()
const form = reactive({
    email: '',
    password: '',
})
const showPassword = ref(false)
const error = ref(null)

// Dummy brugere
const users = [
    { email: 'admin@example.com', password: 'password', name: 'Admin' },
    { email: 'user@example.com', password: 'secret', name: 'User' },
]

function handleLogin() {
    error.value = null
    if (!form.email || !form.password) {
        error.value = 'Indtast email og adgangskode.'
        return
    }

    const found = users.find(
        (u) => u.email.toLowerCase() === form.email.toLowerCase() && u.password === form.password
    )

    if (!found) {
        error.value = 'Ugyldige loginoplysninger.'
        return
    }

    // Gem simpel session (til demo)
    localStorage.setItem('loggedUser', JSON.stringify({ email: found.email, name: found.name }))

    // Naviger til dashboard
    router.push({ name: 'DashboardSite' })
}
</script>

<template>
    <Hero type="login">
        <!-- Logo ovenpå hero -->
        <div class="mainLogo">
            <BasicIconAndLogo name="MainLogo" :large="true" />
        </div>

        <!-- Login-card centralt -->
        <div class="loginPage">
            <div class="card">
                <h2>Velkommen tilbage!</h2>
                <p>Venligst indsæt dine oplysninger</p>

                <form @submit.prevent="handleLogin" class="loginForm" novalidate>
                    <FormField id="email" label="Email" placeholder="Indtast din email" v-model="form.email"
                        :error="!!error" :touched="true" :error-message="error" @input="error = null" />

                    <FormField id="password" label="Adgangskode" placeholder="Indtast din adgangskode"
                        fieldType="password" v-model="form.password" :error="!!error" :touched="true"
                        :error-message="error" :showToggle="true" />

                    <div class="rememberMe">
                        <InputField v-model:checked="rememberme" label="Husk mig" />
                        <p>Glemt adgangskode?</p>
                    </div>

                    <div class="buttonWrapper">
                        <Button type="default" label="Log ind" aria-label="Log ind" @click="handleLogin" />
                    </div>
                </form>
            </div>
        </div>
    </Hero>
</template>

<style scoped lang="scss">
/* Logo ovenpå hero */
.mainLogo {
    position: absolute;
    top: 10%;
    left: 50%;
    transform: translateX(-50%);
    z-index: 3;
}

/* Login-card centrering */
.loginPage {
    position: relative;
    z-index: 2; // ligger over overlay
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

/* Card styling */
.card {
    width: 600px;
    height: auto;
    background: $whiteColor;
    padding: 50px;
    border-radius: 15px;


    h2 {
        @include heading3;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    p {
        @include bodyText;
        text-align: center;
        color: $darkGrey;
    }
}

/* Form styling */
.loginForm {
    display: grid;
    gap: 20px;
}

/* Husk mig styling */
.rememberMe {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;

    p {
        @include bodyText;
        color: $primaryBlue;
        cursor: pointer;
        text-decoration: underline;
    }
}

.buttonWrapper {
    display: flex;
    justify-content: center;
}
</style>
