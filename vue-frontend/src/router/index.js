/**
 * @module router/index
 * @description Vue Router configuration with lazy-loaded routes and authentication guards.
 */
import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/login',
    name: 'login',
    component: () => import('../views/LoginView.vue'),
    meta: { title: 'Iniciar Sesión', guestOnly: true },
  },
  {
    path: '/',
    name: 'dashboard',
    component: () => import('../views/DashboardView.vue'),
    meta: { title: 'Dashboard', requiresAuth: true },
  },
  {
    path: '/products',
    name: 'products',
    component: () => import('../views/ProductsView.vue'),
    meta: { title: 'Productos', requiresAuth: true },
  },
  {
    path: '/products/create',
    name: 'product-create',
    component: () => import('../views/ProductFormView.vue'),
    meta: { title: 'Nuevo Producto', requiresAuth: true },
  },
  {
    path: '/products/:id/edit',
    name: 'product-edit',
    component: () => import('../views/ProductFormView.vue'),
    meta: { title: 'Editar Producto', requiresAuth: true },
  },
  {
    path: '/categories',
    name: 'categories',
    component: () => import('../views/CategoriesView.vue'),
    meta: { title: 'Categorías', requiresAuth: true },
  },
  {
    path: '/:pathMatch(.*)*',
    redirect: '/',
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

/**
 * Global navigation guard for authentication checks.
 */
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('admin_token')

  if (to.meta.requiresAuth && !token) {
    next({ name: 'login' })
  } else if (to.meta.guestOnly && token) {
    next({ name: 'dashboard' })
  } else {
    next()
  }
})

/** Update document title on navigation */
router.afterEach((to) => {
  document.title = `${to.meta.title || 'App'} — Inventario Pro`
})

export default router
