<script setup lang="ts">
import { ref, reactive, computed, onMounted } from 'vue'
import Modal from '@/components/ui/Modal.vue'
import Button from '@/components/ui/button/Button.vue'
import Input from '@/components/ui/input/Input.vue'
import Select from '@/components/ui/Select.vue'
import FileUpload from '@/components/ui/FileUpload.vue'
import { inventoryService, type Product, type Category } from '@/services/inventoryService'

interface ProductFormProps {
  product?: Product | null
  categories: Category[]
}

const props = defineProps<ProductFormProps>()
const emit = defineEmits<{
  close: []
  saved: []
}>()

const loading = ref(false)
const errors = ref<Record<string, string[]>>({})

const form = reactive({
  name: '',
  sku: '',
  description: '',
  category_id: null as number | null,
  price: 0,
  selling_price: 0,
  stock_quantity: 0,
  min_stock_level: 0,
  status: true as boolean, // Explicitly type as boolean
  image: null as File | null
})

const categoryOptions = computed(() => {
  return props.categories.map(category => ({
    value: category.id,
    label: category.name
  }))
})

const statusOptions = computed(() => [
  { value: true, label: 'Active' },
  { value: false, label: 'Inactive' }
])

const isEditing = computed(() => !!props.product)

const initializeForm = () => {
  if (props.product) {
    Object.assign(form, {
      name: props.product.name,
      sku: props.product.sku,
      description: props.product.description,
      category_id: props.product.category_id,
      price: props.product.price,
      selling_price: props.product.selling_price,
      stock_quantity: props.product.stock_quantity,
      min_stock_level: props.product.min_stock_level,
      status: props.product.status,
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

    // Add product ID for editing
    if (isEditing.value && props.product) {
      formData.append('id', props.product.id.toString())
    }

    const response = isEditing.value 
      ? await inventoryService.updateProduct(formData)
      : await inventoryService.createProduct(formData)

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
    :title="isEditing ? 'Edit Product' : 'Add New Product'"
    size="lg"
    @close="emit('close')"
  >
    <form @submit.prevent="submitForm" class="space-y-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Product Name -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Product Name *
          </label>
          <Input
            v-model="form.name"
            placeholder="Enter product name"
            :class="getError('name') ? 'border-red-500' : ''"
            required
          />
          <p v-if="getError('name')" class="text-red-500 text-xs mt-1">
            {{ getError('name') }}
          </p>
        </div>

        <!-- SKU -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            SKU *
          </label>
          <Input
            v-model="form.sku"
            placeholder="Enter SKU"
            :class="getError('sku') ? 'border-red-500' : ''"
            required
          />
          <p v-if="getError('sku')" class="text-red-500 text-xs mt-1">
            {{ getError('sku') }}
          </p>
        </div>

        <!-- Category -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Category *
          </label>
          <Select
            v-model="form.category_id"
            :options="categoryOptions"
            placeholder="Select category"
            :class="getError('category_id') ? 'border-red-500' : ''"
          />
          <p v-if="getError('category_id')" class="text-red-500 text-xs mt-1">
            {{ getError('category_id') }}
          </p>
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

        <!-- Purchase Price -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Purchase Price *
          </label>
          <Input
            v-model.number="form.price"
            type="number"
            step="0.01"
            placeholder="0.00"
            :class="getError('price') ? 'border-red-500' : ''"
            required
          />
          <p v-if="getError('price')" class="text-red-500 text-xs mt-1">
            {{ getError('price') }}
          </p>
        </div>

        <!-- Selling Price -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Selling Price *
          </label>
          <Input
            v-model.number="form.selling_price"
            type="number"
            step="0.01"
            placeholder="0.00"
            :class="getError('selling_price') ? 'border-red-500' : ''"
            required
          />
          <p v-if="getError('selling_price')" class="text-red-500 text-xs mt-1">
            {{ getError('selling_price') }}
          </p>
        </div>

        <!-- Stock Quantity -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Stock Quantity *
          </label>
          <Input
            v-model.number="form.stock_quantity"
            type="number"
            placeholder="0"
            :class="getError('stock_quantity') ? 'border-red-500' : ''"
            required
          />
          <p v-if="getError('stock_quantity')" class="text-red-500 text-xs mt-1">
            {{ getError('stock_quantity') }}
          </p>
        </div>

        <!-- Minimum Stock Level -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Minimum Stock Level
          </label>
          <Input
            v-model.number="form.min_stock_level"
            type="number"
            placeholder="0"
          />
        </div>
      </div>

      <!-- Description -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Description
        </label>
        <textarea
          v-model="form.description"
          rows="3"
          placeholder="Enter product description"
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        />
      </div>

      <!-- Product Image -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Product Image
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
        {{ isEditing ? 'Update Product' : 'Create Product' }}
      </Button>
    </template>
  </Modal>
</template>