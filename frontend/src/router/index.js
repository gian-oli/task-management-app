import { createRouter, createWebHashHistory } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

import Login from '@/views/Login.vue'
import Register from '@/views/Register.vue'
import Dashboard from '@/views/Dashboard.vue'
import Admin from '@/views/Admin.vue'
import NotFound from '@/views/errors/NotFound.vue'

const routes = [
  {
    path: '/',
    name: 'Dashboard',
    component: Dashboard,
    meta: { requiresAuth: true }
  },
  {
    path: '/admin',
    name: 'Admin',
    component: Admin,
    meta: { requiresAuth: true, requiresAdmin: true }
  },
  {
    path: '/login',
    name: 'Login',
    component: Login
  },
  {
    path: '/register',
    name: 'Register',
    component: Register
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: NotFound
  }
]

const router = createRouter({
    history: createWebHashHistory(),
    routes
})

router.beforeEach(async (to, from, next) => {
    const auth = useAuthStore()
    if (!auth.user && auth.token) await auth.fetchUser()

    if (to.meta.requiresAuth && !auth.token) {
        next('/login')
    } else if (to.meta.guest && auth.token) {
        next('/')
    } else if (to.meta.isAdmin && auth.user?.role !== 'admin') {
        next('/') // or unauthorized page
    } else {
        next()
    }
})

export default router
