<script setup lang="ts">
import { ref } from 'vue'
import { Upload, X, Image } from 'lucide-vue-next'

interface FileUploadProps {
  modelValue?: File | null
  accept?: string
  maxSize?: number // in MB
  preview?: boolean
}

const props = withDefaults(defineProps<FileUploadProps>(), {
  accept: 'image/*',
  maxSize: 5,
  preview: true
})

const emit = defineEmits<{
  'update:modelValue': [file: File | null]
}>()

const isDragging = ref(false)
const fileInput = ref<HTMLInputElement>()
const previewUrl = ref<string | null>(null)

const handleFileSelect = (files: FileList | null) => {
  if (!files || files.length === 0) return

  const file = files[0]
  
  // Check file size
  if (file.size > props.maxSize * 1024 * 1024) {
    alert(`File size must be less than ${props.maxSize}MB`)
    return
  }

  emit('update:modelValue', file)
  
  if (props.preview && file.type.startsWith('image/')) {
    const reader = new FileReader()
    reader.onload = (e) => {
      previewUrl.value = e.target?.result as string
    }
    reader.readAsDataURL(file)
  }
}

const handleDrop = (e: DragEvent) => {
  isDragging.value = false
  handleFileSelect(e.dataTransfer?.files || null)
}

const handleDragOver = (e: DragEvent) => {
  e.preventDefault()
  isDragging.value = true
}

const handleDragLeave = () => {
  isDragging.value = false
}

const removeFile = () => {
  emit('update:modelValue', null)
  previewUrl.value = null
  if (fileInput.value) {
    fileInput.value.value = ''
  }
}

const openFileDialog = () => {
  fileInput.value?.click()
}
</script>

<template>
  <div class="space-y-4">
    <!-- Drop Zone -->
    <div
      @drop="handleDrop"
      @dragover="handleDragOver"
      @dragleave="handleDragLeave"
      @click="openFileDialog"
      :class="[
        'border-2 border-dashed rounded-lg p-6 text-center cursor-pointer transition-colors',
        isDragging 
          ? 'border-blue-400 bg-blue-50' 
          : 'border-gray-300 hover:border-gray-400'
      ]"
    >
      <input
        ref="fileInput"
        type="file"
        :accept="accept"
        @change="handleFileSelect(($event.target as HTMLInputElement).files)"
        class="hidden"
      />
      
      <Upload class="mx-auto h-12 w-12 text-gray-400 mb-4" />
      <p class="text-sm text-gray-600">
        <span class="font-medium">Click to upload</span> or drag and drop
      </p>
      <p class="text-xs text-gray-500">
        {{ accept.replace('*', '').toUpperCase() }} up to {{ maxSize }}MB
      </p>
    </div>

    <!-- Preview -->
    <div v-if="modelValue || previewUrl" class="relative">
      <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
        <div class="flex-shrink-0">
          <img
            v-if="previewUrl"
            :src="previewUrl"
            alt="Preview"
            class="h-12 w-12 object-cover rounded"
          />
          <div v-else class="h-12 w-12 bg-gray-200 rounded flex items-center justify-center">
            <Image class="h-6 w-6 text-gray-400" />
          </div>
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-sm font-medium text-gray-900 truncate">
            {{ modelValue?.name }}
          </p>
          <p class="text-sm text-gray-500">
            {{ (modelValue!.size / 1024 / 1024).toFixed(2) }} MB
          </p>
        </div>
        <button
          @click.stop="removeFile"
          class="flex-shrink-0 text-gray-400 hover:text-red-500 transition-colors"
        >
          <X class="h-5 w-5" />
        </button>
      </div>
    </div>
  </div>
</template>