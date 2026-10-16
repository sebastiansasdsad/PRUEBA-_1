<template>
  <div>
    <div class="page-header">
      <div>
        <h1 class="page-title">Categorías</h1>
        <p class="page-subtitle">{{ categoryStore.categoryCount }} categorías registradas</p>
      </div>
      <button v-if="authStore.isAdmin" class="btn btn-primary" @click="openCreate">
        + Nueva Categoría
      </button>
    </div>

    <LoadingSpinner v-if="categoryStore.loading" text="Cargando categorías…" />

    <template v-else>
      <!-- Categories Grid -->
      <div v-if="categoryStore.categories.length" class="stats-grid fade-in-up">
        <div
          v-for="category in categoryStore.categories"
          :key="category.id"
          class="card"
          style="cursor: default;"
        >
          <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 0.75rem;">
            <div>
              <h3 style="font-size: 1.125rem; font-weight: 700;">{{ category.name }}</h3>
              <p style="font-size: 0.8125rem; color: var(--color-text-muted); margin-top: 0.25rem;">
                {{ category.description || 'Sin descripción' }}
              </p>
            </div>
            <span class="badge badge-purple">{{ category.products_count ?? 0 }} productos</span>
          </div>
          <div v-if="authStore.isAdmin" style="display: flex; gap: 0.5rem; margin-top: 1rem; padding-top: 0.75rem; border-top: 1px solid var(--color-border);">
            <button class="btn btn-secondary btn-sm" @click="openEdit(category)">✏️ Editar</button>
            <button class="btn btn-danger btn-sm" @click="confirmDelete(category)">🗑️ Eliminar</button>
          </div>
          <div style="font-size: 0.6875rem; color: var(--color-text-muted); margin-top: 0.75rem;">
            Creada: {{ formatDate(category.created_at) }}
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="card">
        <div class="empty-state">
          <div class="empty-icon">📂</div>
          <div class="empty-title">Sin categorías</div>
          <div class="empty-desc">
            {{ authStore.isAdmin ? 'Crea tu primera categoría para empezar a organizar tus productos.' : 'No hay categorías disponibles en este momento.' }}
          </div>
          <button v-if="authStore.isAdmin" class="btn btn-primary" @click="openCreate">+ Crear Categoría</button>
        </div>
      </div>
    </template>

    <!-- Create/Edit Modal -->
    <ConfirmModal
      v-if="showFormModal"
      :title="editingCategory ? 'Editar Categoría' : 'Nueva Categoría'"
      @close="closeFormModal"
    >
      <form @submit.prevent="handleSubmit" novalidate>
        <div class="form-group">
          <label class="form-label" for="cat-name">Nombre *</label>
          <input
            id="cat-name"
            v-model="form.name"
            type="text"
            class="form-input"
            :class="{ 'is-invalid': categoryStore.errors.name }"
            placeholder="Ej: Ropa"
            autofocus
          />
          <p v-if="categoryStore.errors.name" class="form-error">{{ categoryStore.errors.name[0] }}</p>
        </div>
        <div class="form-group">
          <label class="form-label" for="cat-desc">Descripción</label>
          <textarea
            id="cat-desc"
            v-model="form.description"
            class="form-textarea"
            :class="{ 'is-invalid': categoryStore.errors.description }"
            placeholder="Describe la categoría…"
            rows="3"
          ></textarea>
          <p v-if="categoryStore.errors.description" class="form-error">{{ categoryStore.errors.description[0] }}</p>
        </div>
      </form>
      <template #actions>
        <button class="btn btn-secondary" @click="closeFormModal">Cancelar</button>
        <button class="btn btn-primary" @click="handleSubmit" :disabled="categoryStore.saving">
          <span v-if="categoryStore.saving" class="spinner spinner-sm"></span>
          {{ categoryStore.saving ? 'Guardando…' : (editingCategory ? 'Actualizar' : 'Crear') }}
        </button>
      </template>
    </ConfirmModal>

    <!-- Delete Confirmation Modal -->
    <ConfirmModal
      v-if="showDeleteModal"
      title="Eliminar Categoría"
      @close="showDeleteModal = false"
    >
      <p style="color: var(--color-text-secondary);">
        ¿Estás seguro de que deseas eliminar <strong style="color: var(--color-text-primary);">{{ categoryToDelete?.name }}</strong>?
      </p>
      <p v-if="(categoryToDelete?.products_count ?? 0) > 0" style="color: var(--color-accent-warning); font-size: 0.8125rem; margin-top: 0.75rem;">
        ⚠ Esta categoría tiene {{ categoryToDelete.products_count }} producto(s) asociado(s). Deberás reasignarlos primero.
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
 * @view CategoriesView
 * @description Categories listing page with inline modal for admin and view-only for clients.
 */
import { ref, onMounted, watch } from 'vue'
import { useCategoryStore } from '../stores/categories'
import { useAuthStore } from '../stores/auth'
import { formatDate } from '../utils/formatters'
import LoadingSpinner from '../components/LoadingSpinner.vue'
import ConfirmModal from '../components/ConfirmModal.vue'

const categoryStore = useCategoryStore()
const authStore = useAuthStore()

// ── Form Modal State ──
const showFormModal = ref(false)
const editingCategory = ref(null)
const form = ref({ name: '', description: '' })

function openCreate() {
  editingCategory.value = null
  form.value = { name: '', description: '' }
  categoryStore.clearErrors()
  showFormModal.value = true
}

function openEdit(category) {
  editingCategory.value = category
  form.value = { name: category.name, description: category.description || '' }
  categoryStore.clearErrors()
  showFormModal.value = true
}

function closeFormModal() {
  showFormModal.value = false
  editingCategory.value = null
  categoryStore.clearErrors()
}

async function handleSubmit() {
  let result
  if (editingCategory.value) {
    result = await categoryStore.updateCategory(editingCategory.value.id, form.value)
  } else {
    result = await categoryStore.createCategory(form.value)
  }
  if (result) {
    closeFormModal()
  }
}

// Clear store errors when user types
watch(form, () => {
  if (Object.keys(categoryStore.errors).length) {
    categoryStore.clearErrors()
  }
}, { deep: true })

// ── Delete Modal State ──
const showDeleteModal = ref(false)
const categoryToDelete = ref(null)
const deleting = ref(false)

function confirmDelete(category) {
  categoryToDelete.value = category
  showDeleteModal.value = true
}

async function handleDelete() {
  if (!categoryToDelete.value) return
  deleting.value = true
  await categoryStore.deleteCategory(categoryToDelete.value.id)
  deleting.value = false
  showDeleteModal.value = false
  categoryToDelete.value = null
}

onMounted(() => {
  categoryStore.fetchCategories()
})
</script>
