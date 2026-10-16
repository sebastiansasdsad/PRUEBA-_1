<template>
  <div class="login-wrapper">
    <div class="login-card fade-in-up">
      <!-- Brand header -->
      <div class="login-brand">
        <div class="login-logo-badge">🔐</div>
        <h1 class="login-title">Inventario Pro</h1>
        <p class="login-subtitle">Ingresa a tu cuenta</p>
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
            placeholder="usuario@dominio.com"
            autocomplete="email"
            required
            autofocus
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
              :aria-label="showPassword ? 'Ocultar contraseña' : 'Ver contraseña'"
            >
              {{ showPassword ? '👁️' : '🔒' }}
            </button>
          </div>
          <p v-if="errors.password" class="form-error">{{ errors.password }}</p>
        </div>

        <button
          type="submit"
          class="btn btn-primary"
          style="width: 100%; justify-content: center; margin-top: 1.25rem; padding: 0.875rem;"
          :disabled="authStore.loading"
        >
          <span v-if="authStore.loading" class="spinner spinner-sm"></span>
          {{ authStore.loading ? 'Verificando…' : 'Iniciar Sesión' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
/**
 * @view LoginView
 * @description Secure login view without exposing credentials on screen.
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
  padding: 2.75rem 2.25rem;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
}

.login-brand {
  text-align: center;
  margin-bottom: 2rem;
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
  font-size: 1.625rem;
  font-weight: 800;
  letter-spacing: -0.02em;
  background: var(--gradient-primary);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.login-subtitle {
  font-size: 0.875rem;
  color: var(--color-text-muted);
  margin-top: 0.25rem;
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
