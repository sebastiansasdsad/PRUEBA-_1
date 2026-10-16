<template>
  <div class="loading-overlay" v-if="loading">
    <div class="spinner" :class="{ 'spinner-sm': small }"></div>
    <span v-if="text" class="loading-text">{{ text }}</span>
  </div>
</template>

<script setup>
/**
 * @component LoadingSpinner
 * @description Animated loading indicator with optional text label.
 */
defineProps({
  loading: { type: Boolean, default: true },
  text:    { type: String,  default: 'Cargando…' },
  small:   { type: Boolean, default: false },
})
</script>
