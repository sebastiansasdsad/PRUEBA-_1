<template>
  <div>
    <div class="page-header">
      <div>
        <h1 class="page-title">Productos</h1>
        <p class="page-subtitle">{{ productStore.productCount }} productos registrados</p>
      </div>
      <RouterLink to="/products/create" class="btn btn-primary">+ Nuevo Producto</RouterLink>
    </div>

    <LoadingSpinner v-if="productStore.loading" text="Cargando productos…" />

    <template v-else>
      <!-- Search & Filter Bar -->
      <div class="card" style="margin-bottom: 1.5rem; padding: 1rem 1.25rem;">
        <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: center;">
          <input
            v-model="searchQuery"
            type="text"
            class="form-input"
            placeholder="Buscar por nombre…"
            style="max-width: 320px;"
          />
          <select v-model="filterCategory" class="form-select" style="max-width: 220px;">
            <option value="">Todas las categorías</option>
            <option v-for="opt in categoryStore.categoryOptions" :key="opt.value" :value="opt.value">
              {{ opt.label }}
            </option>
          </select>
        </div>
      </div>

      <!-- Products Table -->
      <div v-if="filteredProducts.length" class="table-container fade-in-up">
        <table class="data-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Categoría</th>
              <th>Precio</th>
              <th>Stock</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="product in filteredProducts" :key="product.id">
              <td style="color: var(--color-text-muted);">#{{ product.id }}</td>
              <td>
                <div style="font-weight: 600;">{{ product.name }}</div>
                <div style="font-size: 0.75rem; color: var(--color-text-muted); margin-top: 0.125rem;">
                  {{ truncate(product.description, 60) }}
                </div>
              </td>
              <td>
                <span class="badge badge-purple">{{ product.category?.name || '—' }}</span>
              </td>
              <td style="font-weight: 600;">{{ formatCurrency(product.price) }}</td>
              <td>
                <span :class="product.stock < 10 ? 'badge badge-red' : 'badge badge-green'">
                  {{ product.stock }} uds
                </span>
              </td>
              <td>
                <div class="table-actions">
                  <RouterLink
                    :to="`/products/${product.id}/edit`"
                    class="btn btn-secondary btn-sm"
                    title="Editar"
                  >
                    ✏️
                  </RouterLink>
                  <button
                    class="btn btn-danger btn-sm"
                    @click="confirmDelete(product)"
                    title="Eliminar"
                  >
                    🗑️
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Empty State -->
      <div v-else class="card">
        <div class="empty-state">
          <div class="empty-icon">📦</div>
          <div class="empty-title">No se encontraron productos</div>
          <div class="empty-desc">
            {{ searchQuery || filterCategory ? 'Intenta ajustar los filtros de búsqueda.' : 'Agrega tu primer producto para empezar.' }}
          </div>
          <RouterLink v-if="!searchQuery && !filterCategory" to="/products/create" class="btn btn-primary">
            + Crear Producto
          </RouterLink>
        </div>
      </div>
    </template>

    <!-- Delete Confirmation Modal -->
    <ConfirmModal
      v-if="showDeleteModal"
      title="Eliminar Producto"
      @close="showDeleteModal = false"
    >
      <p style="color: var(--color-text-secondary);">
        ¿Estás seguro de que deseas eliminar <strong style="color: var(--color-text-primary);">{{ productToDelete?.name }}</strong>?
        Esta acción no se puede deshacer.
      </p>
      <template #actions>
        <button class="btn btn-secondary" @click="showDeleteModal = false">Cancelar</button>
        <button class="btn btn-danger" @click="handleDelete" :disabled="deleting">
          <span v-if="deleting" class="spinner spinner-sm"></span>
          {{ deleting ? 'Eliminando…' : 'Eliminar' }}
        </button>
      </template>
    </ConfirmModal>
  </div>
</template>

<script setup>
/**
 * @view ProductsView
 * @description Product listing page with search, filter, and delete functionality.
 */
import { ref, computed, onMounted } from 'vue'
import { useProductStore } from '../stores/products'
import { useCategoryStore } from '../stores/categories'
import { formatCurrency, truncate } from '../utils/formatters'
import LoadingSpinner from '../components/LoadingSpinner.vue'
import ConfirmModal from '../components/ConfirmModal.vue'

const productStore = useProductStore()
const categoryStore = useCategoryStore()

const searchQuery = ref('')
const filterCategory = ref('')
const showDeleteModal = ref(false)
const productToDelete = ref(null)
const deleting = ref(false)

/** Products filtered by search text and category */
const filteredProducts = computed(() => {
  let items = productStore.products
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase()
    items = items.filter((p) => p.name.toLowerCase().includes(q))
  }
  if (filterCategory.value) {
    items = items.filter((p) => p.category_id === Number(filterCategory.value))
  }
  return items
})

function confirmDelete(product) {
  productToDelete.value = product
  showDeleteModal.value = true
}

async function handleDelete() {
  if (!productToDelete.value) return
  deleting.value = true
  await productStore.deleteProduct(productToDelete.value.id)
  deleting.value = false
  showDeleteModal.value = false
  productToDelete.value = null
}

onMounted(() => {
  productStore.fetchProducts()
  categoryStore.fetchCategories()
})
</script>
