import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: () => import('../components/PageOneKandidat.vue')
  },
  {
    path: '/pagetwo',
    name: 'PageTwo',
    component: () => import('../components/PageTwoKandidat.vue')
  },
  {
  path: '/pagethree',
    name: 'PageThree',
    component: () => import('../components/PageThirdKandidat.vue')
  }
]

const router = createRouter({
  history: createWebHistory('/app/'), // base hvis du hoster under /app/
  routes
})

export default router
