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
    path: '/apply/:companyId/:positionId',
    name: 'ApplyJob',
    component: () => import('../components/pages/PageThirdKandidat.vue')
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


router.beforeEach(async (to, from, next) => {
  const wantsLogin = to.name === 'LogInDashboard'
  const requiresAuth = !!to.meta.requiresAuth

  // Kun tjek login-status hvis enten:
  // - siden kræver auth
  // - eller brugeren går til login-siden (for at redirecte dem væk)
  if (!requiresAuth && !wantsLogin) return next()

  try {
    const res = await fetch('/api/me', { credentials: 'include' })

    const isLoggedIn = res.ok

    if (requiresAuth && !isLoggedIn) return next({ name: 'LogInDashboard' })
    if (wantsLogin && isLoggedIn) return next({ name: 'DashboardSite' })

    return next()
  } catch {
    if (requiresAuth) return next({ name: 'LogInDashboard' })
    return next()
  }
})

export default router
