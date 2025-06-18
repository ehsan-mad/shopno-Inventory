<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import DataTable from '@/components/ui/DataTable.vue'
import Button from '@/components/ui/button/Button.vue'
import CustomerForm from '@/components/forms/CustomerForm.vue'
import DeleteConfirmation from '@/components/ui/DeleteConfirmation.vue'
import { inventoryService, type Customer } from '@/services/inventoryService'
import { 
  Plus, 
  Edit, 
  Trash2, 
  Mail,
  Phone,
  MapPin,
  Download,
  Users
} from 'lucide-vue-next'

const customers = ref<Customer[]>([])
const loading = ref(true)
const showCustomerForm = ref(false)
const editingCustomer = ref<Customer | null>(null)
const showDeleteConfirm = ref(false)
const deletingCustomer = ref<Customer | null>(null)
const error = ref<string | null>(null)

const columns = [
  { key: 'name', label: 'Customer Name', sortable: true },
  { key: 'email', label: 'Email', sortable: true },
  { key: 'phone', label: 'Phone', sortable: false },
  { key: 'city', label: 'City', sortable: true },
  { key: 'customer_type', label: 'Type', sortable: true },
  { key: 'status', label: 'Status', sortable: true },
  { key: 'created_at', label: 'Created', sortable: true },
  { key: 'actions', label: 'Actions', sortable: false }
]

const customerTypeColors = {
  regular: 'bg-gray-100 text-gray-800',
  wholesale: 'bg-blue-100 text-blue-800',
  retail: 'bg-green-100 text-green-800',
  premium: 'bg-purple-100 text-purple-800'
}

const loadCustomers = async () => {
  try {
    loading.value = true
    const response = await inventoryService.getCustomers()
    if (response.status === 'success') {
      customers.value = response.data
    }
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to load customers'
  } finally {
    loading.value = false
  }
}

const openCustomerForm = (customer?: Customer) => {
  editingCustomer.value = customer || null
  showCustomerForm.value = true
}

const closeCustomerForm = () => {
  showCustomerForm.value = false
  editingCustomer.value = null
}

const handleCustomerSaved = () => {
  closeCustomerForm()
  loadCustomers()
}

const confirmDelete = (customer: Customer) => {
  deletingCustomer.value = customer
  showDeleteConfirm.value = true
}

const handleDelete = async () => {
  if (!deletingCustomer.value) return

  try {
    const response = await inventoryService.deleteCustomer(deletingCustomer.value.id)
    if (response.status === 'success') {
      loadCustomers()
    }
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to delete customer'
  } finally {
    showDeleteConfirm.value = false
    deletingCustomer.value = null
  }
}

const exportCustomers = () => {
  // Implementation for export functionality
  console.log('Export customers')
}

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString()
}

onMounted(() => {
  loadCustomers()
})
</script>

<template>
  <Head title="Customers" />

  <AppLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Customers</h1>
          <p class="text-gray-600">Manage your customer database</p>
        </div>
        <div class="flex space-x-3">
          <Button
            variant="outline"
            @click="exportCustomers"
            class="flex items-center"
          >
            <Download class="w-4 h-4 mr-2" />
            Export
          </Button>
          <Button
            @click="openCustomerForm()"
            class="flex items-center"
          >
            <Plus class="w-4 h-4 mr-2" />
            Add Customer
          </Button>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <Users class="h-8 w-8 text-blue-600" />
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Total Customers</p>
              <p class="text-2xl font-bold text-gray-900">{{ customers.length }}</p>
            </div>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
              <span class="text-green-600 font-semibold text-sm">R</span>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Regular</p>
              <p class="text-2xl font-bold text-gray-900">
                {{ customers.filter(c => c.customer_type === 'regular').length }}
              </p>
            </div>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
              <span class="text-blue-600 font-semibold text-sm">W</span>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Wholesale</p>
              <p class="text-2xl font-bold text-gray-900">
                {{ customers.filter(c => c.customer_type === 'wholesale').length }}
              </p>
            </div>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
              <span class="text-purple-600 font-semibold text-sm">P</span>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Premium</p>
              <p class="text-2xl font-bold text-gray-900">
                {{ customers.filter(c => c.customer_type === 'premium').length }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Error Alert -->
      <div v-if="error" class="bg-red-50 border border-red-200 rounded-lg p-4">
        <p class="text-red-800">{{ error }}</p>
      </div>

      <!-- Customers Table -->
      <DataTable
        :columns="columns"
        :data="customers"
        :loading="loading"
        searchable
        @search="loadCustomers"
      >
        <template #default="{ data }">
          <tr
            v-for="customer in data"
            :key="customer.id"
            class="hover:bg-gray-50"
          >
            <td class="px-6 py-4">
              <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                  <span class="text-blue-600 font-semibold text-sm">
                    {{ customer.name.charAt(0).toUpperCase() }}
                  </span>
                </div>
                <div class="ml-4">
                  <p class="font-medium text-gray-900">{{ customer.name }}</p>
                </div>
              </div>
            </td>
            <td class="px-6 py-4">
              <div class="flex items-center">
                <Mail class="w-4 h-4 text-gray-400 mr-2" />
                <a 
                  :href="`mailto:${customer.email}`" 
                  class="text-blue-600 hover:text-blue-800"
                >
                  {{ customer.email }}
                </a>
              </div>
            </td>
            <td class="px-6 py-4">
              <div class="flex items-center" v-if="customer.phone">
                <Phone class="w-4 h-4 text-gray-400 mr-2" />
                <span class="text-gray-900">{{ customer.phone }}</span>
              </div>
              <span v-else class="text-gray-400">-</span>
            </td>
            <td class="px-6 py-4">
              <div class="flex items-center" v-if="customer.city">
                <MapPin class="w-4 h-4 text-gray-400 mr-2" />
                <span class="text-gray-900">{{ customer.city }}</span>
              </div>
              <span v-else class="text-gray-400">-</span>
            </td>
            <td class="px-6 py-4">
              <span
                :class="[
                  'inline-flex px-2 py-1 text-xs font-medium rounded-full capitalize',
                  customerTypeColors[customer.customer_type]
                ]"
              >
                {{ customer.customer_type }}
              </span>
            </td>
            <td class="px-6 py-4">
              <span
                :class="[
                  'inline-flex px-2 py-1 text-xs font-medium rounded-full',
                  customer.status
                    ? 'bg-green-100 text-green-800'
                    : 'bg-red-100 text-red-800'
                ]"
              >
                {{ customer.status ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td class="px-6 py-4 text-sm text-gray-500">
              {{ formatDate(customer.created_at) }}
            </td>
            <td class="px-6 py-4">
              <div class="flex items-center space-x-2">
                <Button
                  variant="ghost"
                  size="sm"
                  @click="openCustomerForm(customer)"
                >
                  <Edit class="w-4 h-4" />
                </Button>
                <Button
                  variant="ghost"
                  size="sm"
                  @click="confirmDelete(customer)"
                  class="text-red-600 hover:text-red-700"
                >
                  <Trash2 class="w-4 h-4" />
                </Button>
              </div>
            </td>
          </tr>
        </template>
      </DataTable>
    </div>

    <!-- Customer Form Modal -->
    <CustomerForm
      v-if="showCustomerForm"
      :customer="editingCustomer"
      @close="closeCustomerForm"
      @saved="handleCustomerSaved"
    />

    <!-- Delete Confirmation Modal -->
    <DeleteConfirmation
      v-if="showDeleteConfirm"
      :title="'Delete Customer'"
      :message="`Are you sure you want to delete '${deletingCustomer?.name}'? This action cannot be undone.`"
      @confirm="handleDelete"
      @cancel="showDeleteConfirm = false"
    />
  </AppLayout>
</template>