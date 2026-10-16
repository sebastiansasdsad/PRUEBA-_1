/**
 * @module utils/formatters
 * @description Utility functions for formatting values in the UI.
 */

/**
 * Format a number as currency (MXN).
 * @param {number} value
 * @returns {string}
 */
export function formatCurrency(value) {
  return new Intl.NumberFormat('es-MX', {
    style: 'currency',
    currency: 'MXN',
    minimumFractionDigits: 2,
  }).format(value || 0)
}

/**
 * Format a date string to a locale-aware string.
 * @param {string} dateStr - ISO date string
 * @returns {string}
 */
export function formatDate(dateStr) {
  if (!dateStr) return '—'
  return new Intl.DateTimeFormat('es-MX', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  }).format(new Date(dateStr))
}

/**
 * Truncate a string to a max length with ellipsis.
 * @param {string} str
 * @param {number} [max=80]
 * @returns {string}
 */
export function truncate(str, max = 80) {
  if (!str) return ''
  return str.length > max ? str.slice(0, max) + '…' : str
}
