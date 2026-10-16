/**
 * @module services/authService
 * @description API service for user authentication and session management.
 */
import api from './api'

export const authService = {
  /**
   * Login with email and password.
   * @param {{ email: string, password: string }} credentials
   * @returns {Promise<import('axios').AxiosResponse>}
   */
  login(credentials) {
    return api.post('/auth/login', credentials)
  },

  /**
   * Get the current authenticated user profile.
   * @returns {Promise<import('axios').AxiosResponse>}
   */
  getProfile() {
    return api.get('/auth/me')
  },

  /**
   * Logout the current user and revoke their token.
   * @returns {Promise<import('axios').AxiosResponse>}
   */
  logout() {
    return api.post('/auth/logout')
  },
}
