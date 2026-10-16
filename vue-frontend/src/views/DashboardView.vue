<template>
  <div>
    <div class="page-header">
      <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Resumen general de tu inventario</p>
      </div>
    </div>

    <!-- Stats Grid -->
    <LoadingSpinner v-if="loading" text="Cargando estadísticas…" />

    <template v-else>
      <div class="stats-grid fade-in-up">
        <div class="stat-card purple">
          <div class="stat-icon purple">📦</div>
          <div class="stat-value">{{ stats.total_products }}</div>
          <div class="stat-label">Productos Totales</div>
        </div>
        <div class="stat-card red">
          <div class="stat-icon red">📂</div>
          <div class="stat-value">{{ stats.total_categories }}</div>
          <div class="stat-label">Categorías</div>
        </div>
        <div class="stat-card green">
          <div class="stat-icon green">📊</div>
          <div class="stat-value">{{ stats.total_stock }}</div>
          <div class="stat-label">Unidades en Stock</div>
        </div>
        <div class="stat-card orange">
          <div class="stat-icon orange">💰</div>
          <div class="stat-value">{{ formatCurrency(stats.total_inventory_value) }}</div>
          <div class="stat-label">Valor del Inventario</div>
        </div>
      </div>

      <!-- Recent Products Table -->
      <div class="card fade-in-up" style="animation-delay: 0.1s;">
        <div class="card-header">
          <h2 class="card-title">Productos Recientes</h2>
          <RouterLink to="/products" class="btn btn-secondary btn-sm">Ver todos →</RouterLink>
        </div>

        <div v-if="stats.recent_products && stats.recent_products.length" class="table-container" style="border:none;box-shadow:none;">
          <table class="data-table">
            <thead>
              <tr>
                <th>Producto</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Creado</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="product in stats.recent_products" :key="product.id">
                <td style="font-weight: 600;">{{ product.name }}</td>
                <td>
                  <span class="badge badge-purple">{{ product.category?.name || '—' }}</span>
                </td>
                <td>{{ formatCurrency(product.price) }}</td>
                <td>
                  <span :class="product.stock < 10 ? 'badge badge-red' : 'badge badge-green'">
                    {{ product.stock }}
                  </span>
                </td>
                <td style="color: var(--color-text-muted); font-size: 0.8125rem;">
                  {{ formatDate(product.created_at) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-else class="empty-state">
          <div class="empty-icon">📦</div>
          <div class="empty-title">Sin productos aún</div>
          <div class="empty-desc">Agrega tu primer producto para empezar a gestionar tu inventario.</div>
          <RouterLink to="/products/create" class="btn btn-primary">+ Nuevo Producto</RouterLink>
        </div>
      </div>

      <!-- Categories Summary -->
      <div class="card fade-in-up" style="margin-top: 1.5rem; animation-delay: 0.2s;">
        <div class="card-header">
          <h2 class="card-title">Categorías</h2>
          <RouterLink to="/categories" class="btn btn-secondary btn-sm">Gestionar →</RouterLink>
        </div>
        <div v-if="stats.categories_with_count && stats.categories_with_count.length">
          <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
            <div
              v-for="cat in stats.categories_with_count"
              :key="cat.id"
              style="background: rgba(108, 99, 255, 0.06); border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: 0.75rem 1.25rem; display: flex; align-items: center; gap: 0.75rem;"
            >
              <span style="font-weight: 600;">{{ cat.name }}</span>
              <span class="badge badge-purple">{{ cat.products_count }} productos</span>
            </div>
          </div>
        </div>
        <div v-else class="empty-state" style="padding: 2rem;">
          <div class="empty-desc">No hay categorías creadas todavía.</div>
        </div>
      </div>
    </template>
  </div>
</template>

<script setup>
/**
 * @view DashboardView
 * @description Main dashboard page showing inventory statistics, recent products, and category summaries.
 */
import { ref, onMounted } from 'vue'
import { dashboardService } from '../services/dashboardService'
import { formatCurrency, formatDate } from '../utils/formatters'
import LoadingSpinner from '../components/LoadingSpinner.vue'

const loading = ref(true)
const stats = ref({
  total_products: 0,
  total_categories: 0,
  total_stock: 0,
  total_inventory_value: 0,
  recent_products: [],
  categories_with_count: [],
})

onMounted(async () => {
  try {
    const { data } = await dashboardService.getStats()
    stats.value = data.data
  } catch {
    // Handled by interceptor
  } finally {
    loading.value = false
  }
})
</script>
