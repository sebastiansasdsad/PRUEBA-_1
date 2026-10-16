/**
 * @module stores/toast
 * @description Global toast notification store.
 * Manages a queue of toast messages displayed to the user.
 */
import { defineStore } from 'pinia'
import { ref } from 'vue'

let nextId = 0

export const useToastStore = defineStore('toast', () => {
  /** @type {import('vue').Ref<Array<{id: number, type: string, title: string, message: string, leaving: boolean}>>} */
  const toasts = ref([])

  /**
   * Add a toast notification.
   * @param {'success'|'error'|'warning'|'info'} type
   * @param {string} title
   * @param {string} [message='']
   * @param {number} [duration=5000] - Auto-dismiss delay in ms
   */
  function addToast(type, title, message = '', duration = 5000) {
    const id = ++nextId
    toasts.value.push({ id, type, title, message, leaving: false })

    if (duration > 0) {
      setTimeout(() => dismiss(id), duration)
    }
  }

  /**
   * Dismiss a toast by ID with leave animation.
   * @param {number} id
   */
  function dismiss(id) {
    const toast = toasts.value.find((t) => t.id === id)
    if (toast) {
      toast.leaving = true
      setTimeout(() => {
        toasts.value = toasts.value.filter((t) => t.id !== id)
      }, 300)
    }
  }

  /** Convenience methods */
  const success = (title, message) => addToast('success', title, message)
  const error   = (title, message) => addToast('error', title, message, 8000)
  const warning = (title, message) => addToast('warning', title, message, 6000)
  const info    = (title, message) => addToast('info', title, message)

  return { toasts, addToast, dismiss, success, error, warning, info }
})
