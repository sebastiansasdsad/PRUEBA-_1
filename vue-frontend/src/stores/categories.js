/**
 * @module stores/categories
 * @description Pinia store for Category state management.
 * Handles loading, success, and error states for all category operations.
 */
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { categoryService } from '../services/categoryService'
import { useToastStore } from './toast'

export const useCategoryStore = defineStore('categories', () => {
  // ── State ──
  /** @type {import('vue').Ref<Array>} */
  const categories = ref([])
  const loading = ref(false)
  const saving = ref(false)
  const errors = ref({})

  // ── Getters ──
  const categoryCount = computed(() => categories.value.length)

  const getCategoryById = computed(() => {
    return (id) => categories.value.find((c) => c.id === Number(id))
  })

  /** Categories formatted for <select> options */
  const categoryOptions = computed(() =>
    categories.value.map((c) => ({ value: c.id, label: c.name }))
  )

  // ── Actions ──
  const toast = useToastStore()

  /**
   * Fetch all categories from the API.
   */
  async function fetchCategories() {
    loading.value = true
    errors.value = {}
    try {
      const { data } = await categoryService.getAll()
      categories.value = data.data
    } catch (err) {
      // Error already handled by Axios interceptor
    } finally {
      loading.value = false
    }
  }

  /**
   * Create a new category.
   * @param {Object} payload - { name, description }
   * @returns {Promise<Object|null>} Created category or null on failure
   */
  async function createCategory(payload) {
    saving.value = true
    errors.value = {}
    try {
      const { data } = await categoryService.create(payload)
      categories.value.push(data.data)
      toast.success('Categoría Creada', `"${data.data.name}" se agregó correctamente.`)
      return data.data
    } catch (err) {
      if (err.response?.status === 422) {
        errors.value = err.response.data.errors || {}
      }
      return null
    } finally {
      saving.value = false
    }
  }

  /**
   * Update an existing category.
   * @param {number} id
   * @param {Object} payload - { name, description }
   * @returns {Promise<Object|null>}
   */
  async function updateCategory(id, payload) {
    saving.value = true
    errors.value = {}
    try {
      const { data } = await categoryService.update(id, payload)
      const index = categories.value.findIndex((c) => c.id === id)
      if (index !== -1) categories.value[index] = data.data
      toast.success('Categoría Actualizada', `"${data.data.name}" se actualizó correctamente.`)
      return data.data
    } catch (err) {
      if (err.response?.status === 422) {
        errors.value = err.response.data.errors || {}
      }
      return null
    } finally {
      saving.value = false
    }
  }

  /**
   * Delete a category by ID.
   * @param {number} id
   * @returns {Promise<boolean>}
   */
  async function deleteCategory(id) {
    try {
      await categoryService.delete(id)
      categories.value = categories.value.filter((c) => c.id !== id)
      toast.success('Categoría Eliminada', 'La categoría se eliminó correctamente.')
      return true
    } catch (err) {
      return false
    }
  }

  /**
   * Clear validation errors.
   */
  function clearErrors() {
    errors.value = {}
  }

  return {
    // State
    categories,
    loading,
    saving,
    errors,
    // Getters
    categoryCount,
    getCategoryById,
    categoryOptions,
    // Actions
    fetchCategories,
    createCategory,
    updateCategory,
    deleteCategory,
    clearErrors,
  }
})
