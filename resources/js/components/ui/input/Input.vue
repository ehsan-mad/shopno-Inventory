<script setup lang="ts">
import { computed } from 'vue'
import { cn } from '@/lib/utils'

interface InputProps {
  modelValue?: string | number
  type?: string
  placeholder?: string
  disabled?: boolean
  readonly?: boolean
  required?: boolean
  class?: string
  step?: string | number
}

const props = withDefaults(defineProps<InputProps>(), {
  type: 'text'
})

const emit = defineEmits<{
  'update:modelValue': [value: string | number]
}>()

const classes = computed(() => cn(
  'flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm ring-offset-white file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-gray-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50',
  props.class
))

const handleInput = (event: Event) => {
  const target = event.target as HTMLInputElement
  emit('update:modelValue', target.value)
}
</script>

<template>
  <input
    :type="type"
    :value="modelValue"
    :placeholder="placeholder"
    :disabled="disabled"
    :readonly="readonly"
    :required="required"
    :step="step"
    :class="classes"
    @input="handleInput"
  />
</template>
