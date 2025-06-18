<script setup lang="ts">
import { ref, reactive, computed, onMounted } from 'vue'
import Modal from '@/components/ui/Modal.vue'
import Button from '@/components/ui/button/Button.vue'
import Input from '@/components/ui/input/Input.vue'
import Select from '@/components/ui/Select.vue'
import { inventoryService, type Customer } from '@/services/inventoryService'

interface CustomerFormProps {
  customer?: Customer | null
}

const props = defineProps<CustomerFormProps>()
const emit = defineEmits<{
  close: []
  saved: []
}>()

const loading = ref(false)
const errors = ref<Record<string, string[]>>({})

const form = reactive({
  name: '',
  email: '',
  phone: '',
  address: '',
  city: '',
  customer_type: 'regular' as 'regular' | 'wholesale' | 'retail' | 'premium',
  status: true as boolean
})

const customerTypeOptions = computed(() => [
  { value: 'regular', label: 'Regular Customer' },
  { value: 'wholesale', label: 'Wholesale Customer' },
  { value: 'retail', label: 'Retail Customer' },
  { value: 'premium', label: 'Premium Customer' }
])

const statusOptions = computed(() => [
  { value: true, label: 'Active' },
  { value: false, label: 'Inactive' }
])

const isEditing = computed(() => !!props.customer)

const initializeForm = () => {
  if (props.customer) {
    Object.assign(form, {
      name: props.customer.name,
      email: props.customer.email,
      phone: props.customer.phone || '',
      address: props.customer.address || '',
      city: props.customer.city || '',
      customer_type: props.customer.customer_type,
      status: props.customer.status
    })
  }
}

const submitForm = async () => {
  try {
    loading.value = true
    errors.value = {}

    const payload = { ...form }
    
    // Add customer ID for editing
    if (isEditing.value && props.customer) {
      (payload as any).id = props.customer.id
    }

    const response = isEditing.value 
      ? await inventoryService.updateCustomer(payload as any)
      : await inventoryService.createCustomer(payload)

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
    :title="isEditing ? 'Edit Customer' : 'Add New Customer'"
    size="lg"
    @close="emit('close')"
  >
    <form @submit.prevent="submitForm" class="space-y-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Customer Name -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Customer Name *
          </label>
          <Input
            v-model="form.name"
            placeholder="Enter customer name"
            :class="getError('name') ? 'border-red-500' : ''"
            required
          />
          <p v-if="getError('name')" class="text-red-500 text-xs mt-1">
            {{ getError('name') }}
          </p>
        </div>

        <!-- Email -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Email Address *
          </label>
          <Input
            v-model="form.email"
            type="email"
            placeholder="Enter email address"
            :class="getError('email') ? 'border-red-500' : ''"
            required
          />
          <p v-if="getError('email')" class="text-red-500 text-xs mt-1">
            {{ getError('email') }}
          </p>
        </div>

        <!-- Phone -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Phone Number
          </label>
          <Input
            v-model="form.phone"
            type="tel"
            placeholder="Enter phone number"
            :class="getError('phone') ? 'border-red-500' : ''"
          />
          <p v-if="getError('phone')" class="text-red-500 text-xs mt-1">
            {{ getError('phone') }}
          </p>
        </div>

        <!-- City -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            City
          </label>
          <Input
            v-model="form.city"
            placeholder="Enter city"
            :class="getError('city') ? 'border-red-500' : ''"
          />
          <p v-if="getError('city')" class="text-red-500 text-xs mt-1">
            {{ getError('city') }}
          </p>
        </div>

        <!-- Customer Type -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Customer Type
          </label>
          <Select
            v-model="form.customer_type"
            :options="customerTypeOptions"
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
      </div>

      <!-- Address -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Address
        </label>
        <textarea
          v-model="form.address"
          rows="3"
          placeholder="Enter customer address"
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
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
        {{ isEditing ? 'Update Customer' : 'Create Customer' }}
      </Button>
    </template>
  </Modal>
</template>