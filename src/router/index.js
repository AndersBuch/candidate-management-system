import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: () => import('../components/pages/PageOneKandidat.vue')
  },
  {
    path: '/pagetwo',
    name: 'PageTwo',
    component: () => import('../components/pages/PageTwoKandidat.vue')
  },
  {
  path: '/pagethree',
    name: 'PageThree',
    component: () => import('../components/pages/PageThirdKandidat.vue')
  },
    {
  path: '/login',
    name: 'LoginDashboard',
    component: () => import('../components/dashboard/LogInDashboard.vue')
  }
]

const router = createRouter({
  history: createWebHistory('/app/'), // base hvis du hoster under /app/
  routes
})

export default router
