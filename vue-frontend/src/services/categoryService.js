/**
 * @module services/categoryService
 * @description API service layer for Category CRUD operations.
 * Encapsulates all HTTP calls related to categories.
 */
import api from './api'

const RESOURCE = '/categories'

export const categoryService = {
  /**
   * Fetch all categories.
   * @returns {Promise<import('axios').AxiosResponse>}
   */
  getAll() {
    return api.get(RESOURCE)
  },

  /**
   * Fetch a single category by ID.
   * @param {number} id
   * @returns {Promise<import('axios').AxiosResponse>}
   */
  getById(id) {
    return api.get(`${RESOURCE}/${id}`)
  },

  /**
   * Create a new category.
   * @param {Object} data - { name, description }
   * @returns {Promise<import('axios').AxiosResponse>}
   */
  create(data) {
    return api.post(RESOURCE, data)
  },

  /**
   * Update an existing category.
   * @param {number} id
   * @param {Object} data - { name, description }
   * @returns {Promise<import('axios').AxiosResponse>}
   */
  update(id, data) {
    return api.put(`${RESOURCE}/${id}`, data)
  },

  /**
   * Delete a category.
   * @param {number} id
   * @returns {Promise<import('axios').AxiosResponse>}
   */
  delete(id) {
    return api.delete(`${RESOURCE}/${id}`)
  },
}
