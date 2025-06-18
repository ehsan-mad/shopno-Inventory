<script setup lang="ts">
import { computed } from 'vue'
import { cn } from '@/lib/utils'
import { Loader2 } from 'lucide-vue-next'

interface ButtonProps {
  variant?: 'default' | 'destructive' | 'outline' | 'secondary' | 'ghost' | 'link'
  size?: 'default' | 'sm' | 'lg' | 'icon'
  disabled?: boolean
  loading?: boolean
  type?: 'button' | 'submit' | 'reset'
  class?: string
}

const props = withDefaults(defineProps<ButtonProps>(), {
  variant: 'default',
  size: 'default',
  type: 'button'
})

const emit = defineEmits<{
  click: [event: MouseEvent]
}>()

const baseClasses = 'inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-white transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50'

const variantClasses = {
  default: 'bg-blue-600 text-white hover:bg-blue-700',
  destructive: 'bg-red-600 text-white hover:bg-red-700',
  outline: 'border border-gray-300 bg-white hover:bg-gray-50 hover:text-gray-900',
  secondary: 'bg-gray-100 text-gray-900 hover:bg-gray-200',
  ghost: 'hover:bg-gray-100 hover:text-gray-900',
  link: 'text-blue-600 underline-offset-4 hover:underline'
}

const sizeClasses = {
  default: 'h-10 px-4 py-2',
  sm: 'h-9 rounded-md px-3',
  lg: 'h-11 rounded-md px-8',
  icon: 'h-10 w-10'
}

const classes = computed(() => cn(
  baseClasses,
  variantClasses[props.variant],
  sizeClasses[props.size],
  props.class
))

const handleClick = (event: MouseEvent) => {
  if (!props.disabled && !props.loading) {
    emit('click', event)
  }
}
</script>

<template>
  <button
    :type="type"
    :class="classes"
    :disabled="disabled || loading"
    @click="handleClick"
  >
    <Loader2 v-if="loading" class="mr-2 h-4 w-4 animate-spin" />
    <slot />
  </button>
</template>
