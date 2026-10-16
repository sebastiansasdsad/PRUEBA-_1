/**
 * @module stores/auth
 * @description Pinia store for Authentication state management.
 * Manages admin/client session, Bearer tokens, role checks, and user profile data.
 */
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { authService } from '../services/authService'
import { useToastStore } from './toast'

export const useAuthStore = defineStore('auth', () => {
  const token = ref(localStorage.getItem('admin_token') || null)
  const user = ref(JSON.parse(localStorage.getItem('admin_user') || 'null'))
  const loading = ref(false)
  const error = ref(null)

  const isAuthenticated = computed(() => !!token.value)
  const currentUser = computed(() => user.value)
  const role = computed(() => user.value?.role || 'cliente')
  const isAdmin = computed(() => user.value?.role === 'admin')
  const isClient = computed(() => user.value?.role === 'cliente')

  const toast = useToastStore()

  /**
   * Authenticate user with credentials.
   * @param {{ email: string, password: string }} credentials
   * @returns {Promise<boolean>}
   */
  async function login(credentials) {
    loading.value = true
    error.value = null
    try {
      const { data } = await authService.login(credentials)
      token.value = data.data.token
      user.value = data.data.user

      localStorage.setItem('admin_token', data.data.token)
      localStorage.setItem('admin_user', JSON.stringify(data.data.user))

      const roleLabel = data.data.user.role === 'admin' ? 'Administrador' : 'Cliente'
      toast.success('Bienvenido', `Sesión iniciada como ${data.data.user.name} (${roleLabel})`)
      return true
    } catch (err) {
      error.value = err.response?.data?.message || 'Error al iniciar sesión.'
      return false
    } finally {
      loading.value = false
    }
  }

  /**
   * Fetch current user profile from API.
   */
  async function fetchProfile() {
    if (!token.value) return
    try {
      const { data } = await authService.getProfile()
      user.value = data.data
      localStorage.setItem('admin_user', JSON.stringify(data.data))
    } catch {
      logout(false)
    }
  }

  /**
   * Log out the user and clear local session state.
   * @param {boolean} [callApi=true]
   */
  async function logout(callApi = true) {
    if (callApi && token.value) {
      try {
        await authService.logout()
      } catch {
        // Ignore API logout errors and clear locally anyway
      }
    }

    token.value = null
    user.value = null
    localStorage.removeItem('admin_token')
    localStorage.removeItem('admin_user')

    toast.info('Sesión Finalizada', 'Has cerrado sesión correctamente.')
  }

  return {
    token,
    user,
    loading,
    error,
    isAuthenticated,
    currentUser,
    role,
    isAdmin,
    isClient,
    login,
    fetchProfile,
    logout,
  }
})
