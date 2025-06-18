<script setup lang="ts">
import { ref, computed, watchEffect } from 'vue'
import { ChevronDown, Check } from 'lucide-vue-next'
import { cn } from '@/lib/utils'

interface Option {
  value: string | number | boolean
  label: string
  disabled?: boolean
}

interface SelectProps {
  options: Option[]
  modelValue?: string | number | boolean | null
  placeholder?: string
  disabled?: boolean
  class?: string
}

const props = withDefaults(defineProps<SelectProps>(), {
  placeholder: 'Select an option'
})

const emit = defineEmits<{
  'update:modelValue': [value: string | number | boolean | null]
}>()

const isOpen = ref(false)
const selectRef = ref<HTMLElement>()

const selectedOption = computed(() => {
  return props.options.find(option => option.value === props.modelValue)
})

const selectOption = (option: Option) => {
  if (option.disabled) return
  emit('update:modelValue', option.value)
  isOpen.value = false
}

const toggleDropdown = () => {
  if (!props.disabled) {
    isOpen.value = !isOpen.value
  }
}

// Close dropdown when clicking outside
const handleOutsideClick = (event: Event) => {
  if (selectRef.value && !selectRef.value.contains(event.target as Node)) {
    isOpen.value = false
  }
}

// Add event listener for outside clicks
watchEffect((onInvalidate) => {
  if (isOpen.value) {
    document.addEventListener('click', handleOutsideClick)
    onInvalidate(() => {
      document.removeEventListener('click', handleOutsideClick)
    })
  }
})
</script>

<template>
  <div class="relative" ref="selectRef">
    <button
      type="button"
      @click="toggleDropdown"
      :class="cn(
        'relative w-full cursor-default rounded-md border border-gray-300 bg-white py-2 pl-3 pr-10 text-left shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 sm:text-sm',
        disabled && 'cursor-not-allowed bg-gray-50',
        props.class
      )"
      :disabled="disabled"
    >
      <span class="block truncate">
        {{ selectedOption?.label || placeholder }}
      </span>
      <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
        <ChevronDown class="h-5 w-5 text-gray-400" />
      </span>
    </button>

    <Transition
      enter-active-class="transition duration-100 ease-out"
      enter-from-class="transform scale-95 opacity-0"
      enter-to-class="transform scale-100 opacity-100"
      leave-active-class="transition duration-75 ease-in"
      leave-from-class="transform scale-100 opacity-100"
      leave-to-class="transform scale-95 opacity-0"
    >
      <div
        v-if="isOpen"
        class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
      >
        <div
          v-for="option in options"
          :key="String(option.value)"
          @click="selectOption(option)"
          :class="cn(
            'relative cursor-default select-none py-2 pl-3 pr-9 hover:bg-blue-50',
            option.disabled && 'cursor-not-allowed opacity-50',
            !option.disabled && 'cursor-pointer'
          )"
        >
          <span
            :class="cn(
              'block truncate',
              option.value === modelValue ? 'font-semibold' : 'font-normal'
            )"
          >
            {{ option.label }}
          </span>

          <span
            v-if="option.value === modelValue"
            class="absolute inset-y-0 right-0 flex items-center pr-4 text-blue-600"
          >
            <Check class="h-5 w-5" />
          </span>
        </div>
      </div>
    </Transition>
  </div>
</template>