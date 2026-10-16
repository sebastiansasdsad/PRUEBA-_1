<template>
  <div class="login-wrapper">
    <div class="login-card fade-in-up">
      <!-- Brand header -->
      <div class="login-brand">
        <div class="login-logo-badge">🔐</div>
        <h1 class="login-title">Inventario Pro</h1>
        <p class="login-subtitle">Acceso al Panel de Administración</p>
      </div>

      <!-- Quick Credentials Helper -->
      <div class="quick-credentials-box">
        <div class="quick-header">
          <span class="quick-badge">ADMIN DEMO</span>
          <button type="button" class="quick-fill-btn" @click="fillAdminCredentials">
            ⚡ Autocompletar
          </button>
        </div>
        <div class="quick-data">
          <div><strong>Email:</strong> admin@inventariopro.com</div>
          <div><strong>Password:</strong> password123</div>
        </div>
      </div>

      <!-- Login Form -->
      <form @submit.prevent="handleLogin" novalidate>
        <div v-if="authStore.error" class="login-alert">
          <span>⚠</span>
          <span>{{ authStore.error }}</span>
        </div>

        <div class="form-group">
          <label class="form-label" for="login-email">Correo Electrónico</label>
          <input
            id="login-email"
            v-model="form.email"
            type="email"
            class="form-input"
            :class="{ 'is-invalid': errors.email }"
            placeholder="admin@inventariopro.com"
            autocomplete="email"
            required
          />
          <p v-if="errors.email" class="form-error">{{ errors.email }}</p>
        </div>

        <div class="form-group">
          <label class="form-label" for="login-password">Contraseña</label>
          <div style="position: relative;">
            <input
              id="login-password"
              v-model="form.password"
              :type="showPassword ? 'text' : 'password'"
              class="form-input"
              :class="{ 'is-invalid': errors.password }"
              placeholder="••••••••••••"
              autocomplete="current-password"
              style="padding-right: 2.75rem;"
              required
            />
            <button
              type="button"
              class="password-toggle-btn"
              @click="showPassword = !showPassword"
              :aria-label="showPassword ? 'Ocultar' : 'Mostrar'"
            >
              {{ showPassword ? '👁️' : '🔒' }}
            </button>
          </div>
          <p v-if="errors.password" class="form-error">{{ errors.password }}</p>
        </div>

        <button
          type="submit"
          class="btn btn-primary"
          style="width: 100%; justify-content: center; margin-top: 1rem; padding: 0.875rem;"
          :disabled="authStore.loading"
        >
          <span v-if="authStore.loading" class="spinner spinner-sm"></span>
          {{ authStore.loading ? 'Autenticando…' : 'Iniciar Sesión' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
/**
 * @view LoginView
 * @description Admin login view with credentials helper, validation, and loading indicators.
 */
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const form = ref({
  email: '',
  password: '',
})

const errors = ref({})
const showPassword = ref(false)

function fillAdminCredentials() {
  form.value.email = 'admin@inventariopro.com'
  form.value.password = 'password123'
  errors.value = {}
}

function validate() {
  errors.value = {}
  if (!form.value.email) {
    errors.value.email = 'El correo electrónico es obligatorio.'
  } else if (!/\S+@\S+\.\S+/.test(form.value.email)) {
    errors.value.email = 'Ingrese un correo electrónico válido.'
  }
  if (!form.value.password) {
    errors.value.password = 'La contraseña es obligatoria.'
  }
  return Object.keys(errors.value).length === 0
}

async function handleLogin() {
  if (!validate()) return
  const success = await authStore.login(form.value)
  if (success) {
    router.push('/')
  }
}
</script>

<style scoped>
.login-wrapper {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1.5rem;
  background: radial-gradient(ellipse at top, #1a1a2e 0%, #0f0f1a 100%);
}

.login-card {
  width: 100%;
  max-width: 440px;
  background: var(--color-bg-glass);
  backdrop-filter: blur(24px);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-xl);
  padding: 2.5rem 2rem;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
}

.login-brand {
  text-align: center;
  margin-bottom: 1.75rem;
}

.login-logo-badge {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 56px;
  height: 56px;
  border-radius: 16px;
  background: rgba(108, 99, 255, 0.15);
  border: 1px solid var(--color-border);
  font-size: 1.75rem;
  margin-bottom: 1rem;
}

.login-title {
  font-size: 1.5rem;
  font-weight: 800;
  letter-spacing: -0.02em;
  background: var(--gradient-primary);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.login-subtitle {
  font-size: 0.8125rem;
  color: var(--color-text-muted);
  margin-top: 0.25rem;
}

.quick-credentials-box {
  background: rgba(108, 99, 255, 0.08);
  border: 1px dashed var(--color-border);
  border-radius: var(--radius-md);
  padding: 0.875rem 1rem;
  margin-bottom: 1.5rem;
  font-size: 0.75rem;
}

.quick-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 0.5rem;
}

.quick-badge {
  background: rgba(108, 99, 255, 0.2);
  color: var(--color-accent-primary);
  padding: 0.125rem 0.5rem;
  border-radius: 999px;
  font-weight: 700;
  font-size: 0.6875rem;
}

.quick-fill-btn {
  background: rgba(108, 99, 255, 0.25);
  border: 1px solid var(--color-border-hover);
  color: var(--color-text-primary);
  padding: 0.25rem 0.625rem;
  border-radius: var(--radius-sm);
  font-size: 0.6875rem;
  font-weight: 600;
  cursor: pointer;
  transition: all var(--transition-fast);
}

.quick-fill-btn:hover {
  background: var(--color-accent-primary);
  color: #fff;
}

.quick-data {
  color: var(--color-text-secondary);
  line-height: 1.5;
}

.quick-data strong {
  color: var(--color-text-primary);
}

.login-alert {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1rem;
  border-radius: var(--radius-md);
  background: rgba(233, 69, 96, 0.15);
  border: 1px solid rgba(233, 69, 96, 0.3);
  color: #ff6b81;
  font-size: 0.8125rem;
  margin-bottom: 1.25rem;
}

.password-toggle-btn {
  position: absolute;
  right: 0.75rem;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  cursor: pointer;
  font-size: 1rem;
  opacity: 0.6;
  transition: opacity var(--transition-fast);
}

.password-toggle-btn:hover {
  opacity: 1;
}
</style>
