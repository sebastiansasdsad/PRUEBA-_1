<template>
  <aside class="sidebar" :class="{ open: isOpen }">
    <div class="sidebar-brand">
      <h1>Inventario Pro</h1>
      <p>{{ authStore.isAdmin ? 'Panel de Administración' : 'Portal de Clientes' }}</p>
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

    <!-- User Profile & Logout -->
    <div class="sidebar-user-section">
      <div class="user-profile-card">
        <div class="user-avatar" :class="{ 'client-avatar': !authStore.isAdmin }">
          {{ userInitials }}
        </div>
        <div class="user-info">
          <div style="display: flex; align-items: center; gap: 0.375rem;">
            <span class="user-name">{{ authStore.currentUser?.name || 'Usuario' }}</span>
            <span class="badge" :class="authStore.isAdmin ? 'badge-purple' : 'badge-green'" style="font-size: 0.625rem; padding: 0.125rem 0.375rem;">
              {{ authStore.isAdmin ? 'ADMIN' : 'CLIENTE' }}
            </span>
          </div>
          <div class="user-email">{{ authStore.currentUser?.email || '' }}</div>
        </div>
      </div>

      <button
        type="button"
        class="logout-btn"
        @click="handleLogout"
        title="Cerrar Sesión"
      >
        <svg style="width: 16px; height: 16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
          <polyline points="16 17 21 12 16 7"/>
          <line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
        Cerrar Sesión
      </button>
    </div>
  </aside>
</template>

<script setup>
/**
 * @component AppSidebar
 * @description Main sidebar navigation displaying role-specific info and logout.
 */
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

defineProps({
  isOpen: { type: Boolean, default: false },
})
defineEmits(['navigate'])

const router = useRouter()
const authStore = useAuthStore()

const userInitials = computed(() => {
  const name = authStore.currentUser?.name || 'U'
  return name
    .split(' ')
    .map((w) => w[0])
    .slice(0, 2)
    .join('')
    .toUpperCase()
})

async function handleLogout() {
  await authStore.logout()
  router.push('/login')
}
</script>

<style scoped>
.sidebar-user-section {
  padding: 1rem;
  border-top: 1px solid var(--color-border);
  margin-top: auto;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.user-profile-card {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.5rem 0.625rem;
  border-radius: var(--radius-md);
  background: rgba(108, 99, 255, 0.06);
  border: 1px solid var(--color-border);
}

.user-avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: var(--gradient-primary);
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 0.8125rem;
  color: #fff;
  flex-shrink: 0;
}

.user-avatar.client-avatar {
  background: linear-gradient(135deg, #00d2a0 0%, #00a8ff 100%);
}

.user-info {
  flex: 1;
  min-width: 0;
}

.user-name {
  font-size: 0.8125rem;
  font-weight: 600;
  color: var(--color-text-primary);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.user-email {
  font-size: 0.6875rem;
  color: var(--color-text-muted);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.logout-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  width: 100%;
  padding: 0.5rem;
  border-radius: var(--radius-sm);
  background: rgba(233, 69, 96, 0.1);
  border: 1px solid rgba(233, 69, 96, 0.25);
  color: #ff6b81;
  font-size: 0.75rem;
  font-weight: 600;
  cursor: pointer;
  transition: all var(--transition-fast);
}

.logout-btn:hover {
  background: rgba(233, 69, 96, 0.2);
  border-color: rgba(233, 69, 96, 0.5);
  color: #fff;
}
</style>
