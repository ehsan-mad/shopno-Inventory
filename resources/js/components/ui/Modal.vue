<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { X } from 'lucide-vue-next'

interface ModalProps {
  title?: string
  size?: 'sm' | 'md' | 'lg' | 'xl' | '2xl'
  closable?: boolean
}

const props = withDefaults(defineProps<ModalProps>(), {
  size: 'md',
  closable: true
})

const emit = defineEmits<{
  close: []
}>()

const modalRef = ref<HTMLElement>()

const sizeClasses = {
  sm: 'max-w-md',
  md: 'max-w-lg',
  lg: 'max-w-2xl',
  xl: 'max-w-4xl',
  '2xl': 'max-w-6xl'
}

const handleEscape = (e: KeyboardEvent) => {
  if (e.key === 'Escape' && props.closable) {
    emit('close')
  }
}

const handleOutsideClick = (e: MouseEvent) => {
  if (modalRef.value && !modalRef.value.contains(e.target as Node) && props.closable) {
    emit('close')
  }
}

onMounted(() => {
  document.addEventListener('keydown', handleEscape)
  document.addEventListener('click', handleOutsideClick)
  document.body.style.overflow = 'hidden'
})

onUnmounted(() => {
  document.removeEventListener('keydown', handleEscape)
  document.removeEventListener('click', handleOutsideClick)
  document.body.style.overflow = 'auto'
})
</script>

<template>
  <div class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-4">
      <!-- Backdrop -->
      <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
      
      <!-- Modal -->
      <div
        ref="modalRef"
        :class="[
          'relative bg-white rounded-lg shadow-xl transition-all w-full',
          sizeClasses[size]
        ]"
      >
        <!-- Header -->
        <div v-if="title || $slots.header || closable" class="flex items-center justify-between p-6 border-b border-gray-200">
          <div>
            <slot name="header">
              <h3 class="text-lg font-semibold text-gray-900">{{ title }}</h3>
            </slot>
          </div>
          <button
            v-if="closable"
            @click="emit('close')"
            class="text-gray-400 hover:text-gray-600 transition-colors"
          >
            <X class="w-5 h-5" />
          </button>
        </div>

        <!-- Body -->
        <div class="p-6">
          <slot />
        </div>

        <!-- Footer -->
        <div v-if="$slots.footer" class="flex justify-end space-x-3 p-6 border-t border-gray-200">
          <slot name="footer" />
        </div>
      </div>
    </div>
  </div>
</template>