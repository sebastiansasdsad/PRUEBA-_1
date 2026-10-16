<template>
  <div>
    <!-- Full authenticated layout -->
    <div v-if="route.name !== 'login'" class="app-layout">
      <AppSidebar :isOpen="sidebarOpen" @navigate="sidebarOpen = false" />

      <!-- Mobile header -->
      <button
        class="mobile-menu-btn"
        @click="sidebarOpen = !sidebarOpen"
        aria-label="Toggle menu"
      >
        ☰
      </button>

      <main class="main-content">
        <RouterView />
      </main>
    </div>

    <!-- Login plain layout -->
    <RouterView v-else />

    <ToastNotification />
  </div>
</template>

<script setup>
/**
 * @component App
 * @description Root application component with conditional layout for guest/login views.
 */
import { ref } from 'vue'
import { useRoute } from 'vue-router'
import AppSidebar from './components/AppSidebar.vue'
import ToastNotification from './components/ToastNotification.vue'

const route = useRoute()
const sidebarOpen = ref(false)
</script>

<style>
.mobile-menu-btn {
  display: none;
  position: fixed;
  top: 1rem;
  left: 1rem;
  z-index: 60;
  background: var(--color-bg-glass);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-sm);
  color: var(--color-text-primary);
  font-size: 1.25rem;
  padding: 0.5rem 0.75rem;
  cursor: pointer;
  backdrop-filter: blur(12px);
}

@media (max-width: 768px) {
  .mobile-menu-btn {
    display: block;
  }
}
</style>
