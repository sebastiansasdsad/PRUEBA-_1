/**
 * @module services/dashboardService
 * @description API service layer for Dashboard stats.
 */
import api from './api'

export const dashboardService = {
  /**
   * Fetch dashboard summary statistics.
   * @returns {Promise<import('axios').AxiosResponse>}
   */
  getStats() {
    return api.get('/dashboard/stats')
  },
}
