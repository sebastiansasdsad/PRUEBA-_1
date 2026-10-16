<template>
  <Teleport to="body">
    <div class="modal-overlay" @click.self="$emit('close')">
      <div class="modal" role="dialog" aria-modal="true">
        <div class="modal-header">
          <h3 class="modal-title">{{ title }}</h3>
          <button class="btn-icon btn-secondary" @click="$emit('close')" aria-label="Cerrar">✕</button>
        </div>
        <div class="modal-body">
          <slot />
        </div>
        <div v-if="$slots.actions" class="modal-actions">
          <slot name="actions" />
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
/**
 * @component ConfirmModal
 * @description A reusable modal dialog with teleport, overlay dismiss, and slot-based content.
 */
defineProps({
  title: { type: String, required: true },
})

defineEmits(['close'])
</script>
