<template>
  <div>
    <div class="page-header">
      <div>
        <h1 class="page-title">{{ isEditing ? 'Editar Producto' : 'Nuevo Producto' }}</h1>
        <p class="page-subtitle">
          {{ isEditing ? 'Modifica los datos del producto' : 'Completa los campos para agregar un producto' }}
        </p>
      </div>
      <RouterLink to="/products" class="btn btn-secondary">← Volver</RouterLink>
    </div>

    <LoadingSpinner v-if="initialLoading" text="Cargando datos…" />

    <div v-else class="card fade-in-up" style="max-width: 720px;">
      <form @submit.prevent="handleSubmit" novalidate>
        <!-- Name -->
        <div class="form-group">
          <label class="form-label" for="product-name">Nombre *</label>
          <input
            id="product-name"
            v-model="form.name"
            type="text"
            class="form-input"
            :class="{ 'is-invalid': errors.name }"
            placeholder="Ej: Camiseta Overskull"
          />
          <p v-if="errors.name" class="form-error">{{ errors.name[0] }}</p>
        </div>

        <!-- Description -->
        <div class="form-group">
          <label class="form-label" for="product-desc">Descripción</label>
          <textarea
            id="product-desc"
            v-model="form.description"
            class="form-textarea"
            :class="{ 'is-invalid': errors.description }"
            placeholder="Describe tu producto…"
            rows="3"
          ></textarea>
          <p v-if="errors.description" class="form-error">{{ errors.description[0] }}</p>
        </div>

        <!-- Price & Stock -->
        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="product-price">Precio (MXN) *</label>
            <input
              id="product-price"
              v-model.number="form.price"
              type="number"
              step="0.01"
              min="0"
              class="form-input"
              :class="{ 'is-invalid': errors.price }"
              placeholder="0.00"
            />
            <p v-if="errors.price" class="form-error">{{ errors.price[0] }}</p>
          </div>
          <div class="form-group">
            <label class="form-label" for="product-stock">Stock *</label>
            <input
              id="product-stock"
              v-model.number="form.stock"
              type="number"
              min="0"
              class="form-input"
              :class="{ 'is-invalid': errors.stock }"
              placeholder="0"
            />
            <p v-if="errors.stock" class="form-error">{{ errors.stock[0] }}</p>
          </div>
        </div>

        <!-- Category -->
        <div class="form-group">
          <label class="form-label" for="product-category">Categoría *</label>
          <select
            id="product-category"
            v-model.number="form.category_id"
            class="form-select"
            :class="{ 'is-invalid': errors.category_id }"
          >
            <option value="" disabled>Selecciona una categoría</option>
            <option
              v-for="opt in categoryStore.categoryOptions"
              :key="opt.value"
              :value="opt.value"
            >
              {{ opt.label }}
            </option>
          </select>
          <p v-if="errors.category_id" class="form-error">{{ errors.category_id[0] }}</p>
        </div>

        <!-- Submit -->
        <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
          <button type="submit" class="btn btn-primary" :disabled="productStore.saving">
            <span v-if="productStore.saving" class="spinner spinner-sm"></span>
            {{ productStore.saving ? 'Guardando…' : (isEditing ? 'Actualizar Producto' : 'Crear Producto') }}
          </button>
          <RouterLink to="/products" class="btn btn-secondary">Cancelar</RouterLink>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
/**
 * @view ProductFormView
 * @description Product create/edit form with validation and loading state management.
 * Detects edit mode from route params and pre-fills form data.
 */
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useProductStore } from '../stores/products'
import { useCategoryStore } from '../stores/categories'
import LoadingSpinner from '../components/LoadingSpinner.vue'

const route = useRoute()
const router = useRouter()
const productStore = useProductStore()
const categoryStore = useCategoryStore()

const isEditing = computed(() => !!route.params.id)
const initialLoading = ref(false)

const form = ref({
  name: '',
  description: '',
  price: '',
  stock: '',
  category_id: '',
})

const errors = computed(() => productStore.errors)

/** Load product data when editing */
onMounted(async () => {
  productStore.clearErrors()
  await categoryStore.fetchCategories()

  if (isEditing.value) {
    initialLoading.value = true
    const product = await productStore.fetchProduct(Number(route.params.id))
    if (product) {
      form.value = {
        name: product.name,
        description: product.description || '',
        price: product.price,
        stock: product.stock,
        category_id: product.category_id,
      }
    }
    initialLoading.value = false
  }
})

/** Clear errors when the user modifies a field */
watch(form, () => {
  if (Object.keys(productStore.errors).length) {
    productStore.clearErrors()
  }
}, { deep: true })

async function handleSubmit() {
  let result
  if (isEditing.value) {
    result = await productStore.updateProduct(Number(route.params.id), form.value)
  } else {
    result = await productStore.createProduct(form.value)
  }
  if (result) {
    router.push({ name: 'products' })
  }
}
</script>
