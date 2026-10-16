/**
 * @module router/index
 * @description Vue Router configuration with lazy-loaded routes.
 */
import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/',
    name: 'dashboard',
    component: () => import('../views/DashboardView.vue'),
    meta: { title: 'Dashboard' },
  },
  {
    path: '/products',
    name: 'products',
    component: () => import('../views/ProductsView.vue'),
    meta: { title: 'Productos' },
  },
  {
    path: '/products/create',
    name: 'product-create',
    component: () => import('../views/ProductFormView.vue'),
    meta: { title: 'Nuevo Producto' },
  },
  {
    path: '/products/:id/edit',
    name: 'product-edit',
    component: () => import('../views/ProductFormView.vue'),
    meta: { title: 'Editar Producto' },
  },
  {
    path: '/categories',
    name: 'categories',
    component: () => import('../views/CategoriesView.vue'),
    meta: { title: 'Categorías' },
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

/** Update document title on navigation */
router.afterEach((to) => {
  document.title = `${to.meta.title || 'App'} — Inventario Pro`
})

export default router
