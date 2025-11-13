<script setup>
import BasicIconAndLogo from '@/components/atoms/BasicIconAndLogo.vue'
import FormField from '@/components/molecules/FormField.vue'
import Button from '@/components/atoms/Button.vue'

import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'



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

    // Naviger til en anden side (tilpas route navn eller path efter dit projekt)
    // Her forsøger vi at navigere til en route med name 'Dashboard', fallback til '/dashboard'
    const target = { name: 'Dashboard' }
    router.push(target).catch(() => router.push('/dashboard'))
}
</script>

<template>

    <div class="mainLogo">
        <BasicIconAndLogo name="MainLogo" :large="true" />
    </div>
    
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

  <div class="password-row">
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


  </div>

  <div v-if="error" class="error">{{ error }}</div>

    <Button
    type="default"
    label="Log ind"
    aria-label="Log ind"
  />

</form>


            <div class="hint">
                <strong>Dummy-data (til test):</strong>
                <ul>
                    <li>admin@example.com / password</li>
                    <li>user@example.com / secret</li>
                </ul>
                <p class="small">Efter vellykket login navigeres der til dashboard-siden.</p>
            </div>
        </div>
    </div>
</template>


<style scoped lang="scss">
.loginPage {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem;
}
.card {
    width: 360px;
    background: #fff;
    padding: 1.6rem;
    border-radius: 8px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
}
h2 {
    margin: 0 0 1rem;
    font-size: 1.25rem;
}
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
.password-row {
    gap: 0.5rem;
    align-items: center;
}
.password-row .toggle {
    padding: 0.45rem 0.6rem;
    font-size: 0.9rem;
    border: 1px solid #d6d6d6;
    background: #f5f5f5;
    border-radius: 4px;
    cursor: pointer;
}
.primary {
    margin-top: 0.6rem;
    width: 100%;
    padding: 0.7rem;
    background: #0366d6;
    color: #fff;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}
.error {
    color: #b00020;
    font-size: 0.9rem;
}
.hint {
    margin-top: 1rem;
    font-size: 0.9rem;
    color: #555;
}
.hint ul {
    margin: 0.4rem 0 0;
    padding-left: 1.1rem;
}
.small {
    font-size: 0.8rem;
    color: #777;
    margin-top: 0.4rem;
}
</style>