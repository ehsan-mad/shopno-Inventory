<script setup lang="ts">
import { ref, reactive, computed, onMounted } from 'vue'
import Modal from '@/components/ui/Modal.vue'
import Button from '@/components/ui/button/Button.vue'
import Input from '@/components/ui/input/Input.vue'
import Select from '@/components/ui/Select.vue'
import FileUpload from '@/components/ui/FileUpload.vue'
import { inventoryService, type Category } from '@/services/inventoryService'

interface CategoryFormProps {
  category?: Category | null
}

const props = defineProps<CategoryFormProps>()
const emit = defineEmits<{
  close: []
  saved: []
}>()

const loading = ref(false)
const errors = ref<Record<string, string[]>>({})

const form = reactive({
  name: '',
  description: '',
  status: true as boolean, // Explicitly type as boolean
  image: null as File | null
})

const statusOptions = computed(() => [
  { value: true, label: 'Active' },
  { value: false, label: 'Inactive' }
])

const isEditing = computed(() => !!props.category)

const initializeForm = () => {
  if (props.category) {
    Object.assign(form, {
      name: props.category.name,
      description: props.category.description || '',
      status: props.category.status,
      image: null
    })
  }
}

const submitForm = async () => {
  try {
    loading.value = true
    errors.value = {}

    const formData = new FormData()
    
    // Add form fields
    Object.entries(form).forEach(([key, value]) => {
      if (key === 'image' && value instanceof File) {
        formData.append(key, value)
      } else if (value !== null && value !== undefined) {
        formData.append(key, value.toString())
      }
    })

    // Add category ID for editing
    if (isEditing.value && props.category) {
      formData.append('id', props.category.id.toString())
    }

    const response = isEditing.value 
      ? await inventoryService.updateCategory(formData)
      : await inventoryService.createCategory(formData)

    if (response.status === 'success') {
      emit('saved')
    }
  } catch (error: any) {
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors
    } else {
      console.error('Form submission error:', error)
    }
  } finally {
    loading.value = false
  }
}

const getError = (field: string): string => {
  return errors.value[field]?.[0] || ''
}

onMounted(() => {
  initializeForm()
})
</script>

<template>
  <Modal 
    :title="isEditing ? 'Edit Category' : 'Add New Category'"
    size="md"
    @close="emit('close')"
  >
    <form @submit.prevent="submitForm" class="space-y-6">
      <!-- Category Name -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Category Name *
        </label>
        <Input
          v-model="form.name"
          placeholder="Enter category name"
          :class="getError('name') ? 'border-red-500' : ''"
          required
        />
        <p v-if="getError('name')" class="text-red-500 text-xs mt-1">
          {{ getError('name') }}
        </p>
      </div>

      <!-- Description -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Description
        </label>
        <textarea
          v-model="form.description"
          rows="3"
          placeholder="Enter category description"
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
        />
      </div>

      <!-- Status -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Status
        </label>
        <Select
          v-model="form.status"
          :options="statusOptions"
        />
      </div>

      <!-- Category Image -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Category Image
        </label>
        <FileUpload
          v-model="form.image"
          accept="image/*"
          :max-size="5"
        />
      </div>
    </form>

    <template #footer>
      <Button
        variant="outline"
        @click="emit('close')"
        :disabled="loading"
      >
        Cancel
      </Button>
      <Button
        @click="submitForm"
        :loading="loading"
      >
        {{ isEditing ? 'Update Category' : 'Create Category' }}
      </Button>
    </template>
  </Modal>
</template>