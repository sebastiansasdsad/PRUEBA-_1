<template>
  <div class="toast-container">
    <TransitionGroup name="toast">
      <div
        v-for="t in toasts"
        :key="t.id"
        class="toast"
        :class="[`toast-${t.type}`, { leaving: t.leaving }]"
      >
        <span class="toast-icon">
          <template v-if="t.type === 'success'">✓</template>
          <template v-else-if="t.type === 'error'">✕</template>
          <template v-else-if="t.type === 'warning'">⚠</template>
          <template v-else>ℹ</template>
        </span>
        <div class="toast-body">
          <div class="toast-title">{{ t.title }}</div>
          <div v-if="t.message" class="toast-message">{{ t.message }}</div>
        </div>
        <button class="toast-close" @click="dismiss(t.id)" aria-label="Cerrar">✕</button>
      </div>
    </TransitionGroup>
  </div>
</template>

<script setup>
/**
 * @component ToastNotification
 * @description Renders the global toast notification stack.
 */
import { storeToRefs } from 'pinia'
import { useToastStore } from '../stores/toast'

const store = useToastStore()
const { toasts } = storeToRefs(store)
const { dismiss } = store
</script>
