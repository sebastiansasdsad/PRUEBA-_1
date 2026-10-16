<template>
  <aside class="sidebar" :class="{ open: isOpen }">
    <div class="sidebar-brand">
      <h1>Inventario Pro</h1>
      <p>Gestión de productos</p>
    </div>

    <nav class="sidebar-nav">
      <RouterLink to="/" class="nav-link" @click="$emit('navigate')">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="3" width="7" height="7" rx="1"/>
          <rect x="14" y="3" width="7" height="7" rx="1"/>
          <rect x="3" y="14" width="7" height="7" rx="1"/>
          <rect x="14" y="14" width="7" height="7" rx="1"/>
        </svg>
        Dashboard
      </RouterLink>

      <RouterLink to="/products" class="nav-link" @click="$emit('navigate')">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
          <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
          <line x1="12" y1="22.08" x2="12" y2="12"/>
        </svg>
        Productos
      </RouterLink>

      <RouterLink to="/categories" class="nav-link" @click="$emit('navigate')">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
        </svg>
        Categorías
      </RouterLink>
    </nav>

    <div style="padding: 0.75rem 1rem; border-top: 1px solid var(--color-border); margin-top: auto;">
      <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; color: var(--color-text-muted);">
        <span style="width:8px;height:8px;border-radius:50%;background:var(--color-accent-success);"></span>
        API Conectada
      </div>
    </div>
  </aside>
</template>

<script setup>
/**
 * @component AppSidebar
 * @description Main sidebar navigation with brand, links, and status indicator.
 */
defineProps({
  isOpen: { type: Boolean, default: false },
})
defineEmits(['navigate'])
</script>
