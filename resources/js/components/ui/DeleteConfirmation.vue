<script setup lang="ts">
import Modal from './Modal.vue'
import Button from './button/Button.vue'
import { AlertTriangle } from 'lucide-vue-next'

interface DeleteConfirmationProps {
  title?: string
  message?: string
  confirmText?: string
  cancelText?: string
  loading?: boolean
}

const props = withDefaults(defineProps<DeleteConfirmationProps>(), {
  title: 'Confirm Delete',
  message: 'Are you sure you want to delete this item? This action cannot be undone.',
  confirmText: 'Delete',
  cancelText: 'Cancel',
  loading: false
})

const emit = defineEmits<{
  confirm: []
  cancel: []
}>()
</script>

<template>
  <Modal :title="title" size="sm" @close="emit('cancel')">
    <div class="text-center">
      <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
        <AlertTriangle class="h-6 w-6 text-red-600" />
      </div>
      
      <p class="text-gray-600 mb-6">{{ message }}</p>
    </div>

    <template #footer>
      <Button
        variant="outline"
        @click="emit('cancel')"
        :disabled="loading"
      >
        {{ cancelText }}
      </Button>
      <Button
        variant="destructive"
        @click="emit('confirm')"
        :loading="loading"
      >
        {{ confirmText }}
      </Button>
    </template>
  </Modal>
</template>