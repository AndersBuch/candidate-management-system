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

const error = ref(null)

async function handleLogin() {
    try {
        // Debug: se hvad der bliver sendt til backend
        console.log('Sender login:', JSON.stringify({ email: form.email, password: form.password }));

        const res = await fetch('http://localhost:8085/api/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email: form.email, password: form.password }),
        });

        const data = await res.json();

        // Debug: se hvad backend returnerer
        console.log('Response fra backend:', res, data);

        if (!res.ok) {
            error.value = data.error;
            return;
        }

        localStorage.setItem('token', data.token);
        localStorage.setItem('loggedUser', JSON.stringify(data.user));
        router.push({ name: 'DashboardSite' });
    } catch (err) {
        console.error('Fetch fejl:', err);
        error.value = 'Noget gik galt.';
    }
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
.mainLogo {
    position: absolute;
    top: 10%;
    left: 50%;
    transform: translateX(-50%);
    z-index: 3;
}

.loginPage {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

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

.loginForm {
    display: grid;
    gap: 20px;
}

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
