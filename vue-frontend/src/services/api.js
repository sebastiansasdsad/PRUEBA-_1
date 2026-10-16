/**
 * @module services/api
 * @description Axios HTTP client instance with global interceptors for
 * request/response handling, error management, and toast notifications.
 */
import axios from 'axios'
import { useToastStore } from '../stores/toast'

/** Base URL points to the Laravel API through Vite proxy */
const API_BASE_URL = '/api/v1'

/**
 * Pre-configured Axios instance for all API communications.
 */
const api = axios.create({
  baseURL: API_BASE_URL,
  timeout: 15000,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
})

/**
 * Request interceptor — adds auth tokens or other headers as needed.
 */
api.interceptors.request.use(
  (config) => {
    // Future: inject auth token here
    // const token = localStorage.getItem('token')
    // if (token) config.headers.Authorization = `Bearer ${token}`
    return config
  },
  (error) => {
    return Promise.reject(error)
  }
)

/**
 * Response interceptor — handles common HTTP errors globally
 * and dispatches user-facing toast notifications.
 */
api.interceptors.response.use(
  (response) => response,
  (error) => {
    const toast = useToastStore()

    if (!error.response) {
      // Network error or timeout
      toast.error(
        'Error de Conexión',
        'No se pudo conectar con el servidor. Verifica tu conexión a internet.'
      )
      return Promise.reject(error)
    }

    const { status, data } = error.response

    switch (status) {
      case 422:
        // Validation errors — handled locally by forms, but show a general toast
        toast.warning(
          'Errores de Validación',
          data.message || 'Por favor revisa los campos del formulario.'
        )
        break
      case 404:
        toast.error(
          'No Encontrado',
          data.message || 'El recurso solicitado no existe.'
        )
        break
      case 409:
        toast.error(
          'Conflicto',
          data.message || 'La operación genera un conflicto con el estado actual.'
        )
        break
      case 500:
        toast.error(
          'Error del Servidor',
          data.message || 'Ocurrió un error interno. Intenta de nuevo más tarde.'
        )
        break
      default:
        if (status >= 400 && status < 500) {
          toast.warning(
            'Error en la Solicitud',
            data.message || `Error ${status}: solicitud inválida.`
          )
        } else if (status >= 500) {
          toast.error(
            'Error del Servidor',
            data.message || `Error ${status}: problema en el servidor.`
          )
        }
    }

    return Promise.reject(error)
  }
)

export default api
