/**
 * @module stores/products
 * @description Pinia store for Product state management.
 * Handles loading, success, and error states for all product operations.
 */
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { productService } from '../services/productService'
import { useToastStore } from './toast'

export const useProductStore = defineStore('products', () => {
  // ── State ──
  /** @type {import('vue').Ref<Array>} */
  const products = ref([])
  const currentProduct = ref(null)
  const loading = ref(false)
  const saving = ref(false)
  const errors = ref({})

  // ── Getters ──
  const productCount = computed(() => products.value.length)

  const totalStock = computed(() =>
    products.value.reduce((sum, p) => sum + (p.stock || 0), 0)
  )

  const totalValue = computed(() =>
    products.value.reduce((sum, p) => sum + (p.price || 0) * (p.stock || 0), 0)
  )

  const getProductById = computed(() => {
    return (id) => products.value.find((p) => p.id === Number(id))
  })

  // ── Actions ──
  const toast = useToastStore()

  /**
   * Fetch all products from the API.
   * @param {Object} [params] - Optional query parameters
   */
  async function fetchProducts(params = {}) {
    loading.value = true
    errors.value = {}
    try {
      const { data } = await productService.getAll(params)
      products.value = data.data
    } catch (err) {
      // Error already handled by Axios interceptor
    } finally {
      loading.value = false
    }
  }

  /**
   * Fetch a single product by ID.
   * @param {number} id
   * @returns {Promise<Object|null>}
   */
  async function fetchProduct(id) {
    loading.value = true
    errors.value = {}
    try {
      const { data } = await productService.getById(id)
      currentProduct.value = data.data
      return data.data
    } catch (err) {
      return null
    } finally {
      loading.value = false
    }
  }

  /**
   * Create a new product.
   * @param {Object} payload - { name, description, price, stock, category_id }
   * @returns {Promise<Object|null>} Created product or null on failure
   */
  async function createProduct(payload) {
    saving.value = true
    errors.value = {}
    try {
      const { data } = await productService.create(payload)
      products.value.push(data.data)
      toast.success('Producto Creado', `"${data.data.name}" se agregó correctamente.`)
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
   * Update an existing product.
   * @param {number} id
   * @param {Object} payload
   * @returns {Promise<Object|null>}
   */
  async function updateProduct(id, payload) {
    saving.value = true
    errors.value = {}
    try {
      const { data } = await productService.update(id, payload)
      const index = products.value.findIndex((p) => p.id === id)
      if (index !== -1) products.value[index] = data.data
      toast.success('Producto Actualizado', `"${data.data.name}" se actualizó correctamente.`)
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
   * Delete a product by ID.
   * @param {number} id
   * @returns {Promise<boolean>}
   */
  async function deleteProduct(id) {
    try {
      await productService.delete(id)
      products.value = products.value.filter((p) => p.id !== id)
      toast.success('Producto Eliminado', 'El producto se eliminó correctamente.')
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
    products,
    currentProduct,
    loading,
    saving,
    errors,
    // Getters
    productCount,
    totalStock,
    totalValue,
    getProductById,
    // Actions
    fetchProducts,
    fetchProduct,
    createProduct,
    updateProduct,
    deleteProduct,
    clearErrors,
  }
})
