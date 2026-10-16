/**
 * @module services/productService
 * @description API service layer for Product CRUD operations.
 * Encapsulates all HTTP calls related to products.
 */
import api from './api'

const RESOURCE = '/products'

export const productService = {
  /**
   * Fetch all products with optional query parameters.
   * @param {Object} [params] - Query params (e.g. { category_id, search })
   * @returns {Promise<import('axios').AxiosResponse>}
   */
  getAll(params = {}) {
    return api.get(RESOURCE, { params })
  },

  /**
   * Fetch a single product by ID.
   * @param {number} id
   * @returns {Promise<import('axios').AxiosResponse>}
   */
  getById(id) {
    return api.get(`${RESOURCE}/${id}`)
  },

  /**
   * Create a new product.
   * @param {Object} data - { name, description, price, stock, category_id }
   * @returns {Promise<import('axios').AxiosResponse>}
   */
  create(data) {
    return api.post(RESOURCE, data)
  },

  /**
   * Update an existing product.
   * @param {number} id
   * @param {Object} data - { name, description, price, stock, category_id }
   * @returns {Promise<import('axios').AxiosResponse>}
   */
  update(id, data) {
    return api.put(`${RESOURCE}/${id}`, data)
  },

  /**
   * Delete a product.
   * @param {number} id
   * @returns {Promise<import('axios').AxiosResponse>}
   */
  delete(id) {
    return api.delete(`${RESOURCE}/${id}`)
  },
}
