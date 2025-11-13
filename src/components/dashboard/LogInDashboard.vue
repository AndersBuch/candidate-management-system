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
    const target = { name: 'Dashboard' }
    router.push(target).catch(() => router.push('/dashboard'))
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
                <FormField
                    id="email"
                    label="Email"
                    placeholder="Indtast din email"
                    v-model="form.email"
                    :error="!!error"
                    :touched="true"
                    :error-message="error"
                    @input="error = null"
                />

                <FormField
                    id="password"
                    label="Adgangskode"
                    placeholder="Indtast din adgangskode"
                    fieldType="password"
                    v-model="form.password"
                    :error="!!error"
                    :touched="true"
                    :error-message="error"
                    :showToggle="true"
                />

                <div class="rememberMe">
                    <InputField v-model:checked="rememberme" label="Husk mig" />
                    <p>Glemt adgangskode?</p>
                </div>

                <Button type="default" label="Log ind" aria-label="Log ind" />
            </form>
        </div>
    </div>
</Hero>
</template>

<style scoped lang="scss">
/* Logo ovenpå hero */
.mainLogo {
    position: absolute;
    top: 2rem;    // afstand fra top
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
    width: 360px;
    background: #fff;
    padding: 1.6rem;
    border-radius: 8px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
}

/* Form styling */
.loginForm {
    display: grid;
    gap: 0.6rem;
}

input[type="email"],
input[type="password"],
input[type="text"] {
    width: 100%;
    padding: 0.6rem;
    border: 1px solid #d6d6d6;
    border-radius: 4px;
    box-sizing: border-box;
}

/* Husk mig styling */
.rememberMe {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
}

/* Fejlbesked */
.error {
    color: #b00020;
    font-size: 0.9rem;
}
</style>
