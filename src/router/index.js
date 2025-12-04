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

  // 🔒 BESKYTTEDE SIDER
  {
    path: '/dashboardsite',
    name: 'DashboardSite',
    component: () => import('../components/dashboard/DashboardSite.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/homepage',
    name: 'HomePage',
    component: () => import('../components/dashboard/HomePageDashboard.vue'),
    meta: { requiresAuth: true }
  },

  // Login
  {
    path: '/login',
    name: 'LogInDashboard',
    component: () => import('../components/dashboard/LogInDashboard.vue')
  },

  // 404
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: () => import('../components/pages/NotFound.vue')
  }
]

const router = createRouter({
  history: createWebHistory('/app/'),
  routes
})


// 🔐 GLOBAL ROUTE GUARD
router.beforeEach((to, from, next) => {
  const token =
  localStorage.getItem('token') ||
  sessionStorage.getItem('token')


  // 1️⃣ Hvis bruger prøver at gå ind på en beskyttet side uden token → redirect til login
  if (to.meta.requiresAuth && !token) {
    return next({ name: 'LogInDashboard' })
  }

  // 2️⃣ Hvis bruger er logget ind og vil ind på login-siden → redirect til dashboard
  if (to.name === 'LogInDashboard' && token) {
    return next({ name: 'DashboardSite' })
  }

  // 3️⃣ Ellers fortsæt som normalt
  next()
})

export default router
