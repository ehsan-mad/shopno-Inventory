<script setup lang="ts" generic="T">
interface Column {
  key: string
  label: string
  sortable?: boolean
  class?: string
}

interface DataTableProps {
  columns: Column[]
  data: T[]
  loading?: boolean
  searchable?: boolean
  pagination?: {
    current_page: number
    last_page: number
    per_page: number
    total: number
  }
}

const props = withDefaults(defineProps<DataTableProps>(), {
  loading: false,
  searchable: false
})

const emit = defineEmits<{
  'sort': [column: string, direction: 'asc' | 'desc']
  'search': [query: string]
  'page-change': [page: number]
}>()

const searchQuery = ref('')
const sortColumn = ref('')
const sortDirection = ref<'asc' | 'desc'>('asc')

const handleSort = (column: Column) => {
  if (!column.sortable) return
  
  if (sortColumn.value === column.key) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortColumn.value = column.key
    sortDirection.value = 'asc'
  }
  
  emit('sort', column.key, sortDirection.value)
}

const handleSearch = (query: string) => {
  searchQuery.value = query
  emit('search', query)
}
</script>

<template>
  <div class="bg-white rounded-lg shadow-md">
    <!-- Search Bar -->
    <div v-if="searchable" class="p-4 border-b border-gray-200">
      <div class="relative">
        <input
          :value="searchQuery"
          @input="handleSearch(($event.target as HTMLInputElement).value)"
          type="text"
          placeholder="Search..."
          class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        />
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th
              v-for="column in columns"
              :key="column.key"
              :class="[
                'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider',
                column.sortable ? 'cursor-pointer hover:bg-gray-100' : '',
                column.class
              ]"
              @click="handleSort(column)"
            >
              <div class="flex items-center space-x-1">
                <span>{{ column.label }}</span>
                <div v-if="column.sortable" class="flex flex-col">
                  <svg
                    :class="[
                      'h-3 w-3',
                      sortColumn === column.key && sortDirection === 'asc' 
                        ? 'text-gray-900' 
                        : 'text-gray-400'
                    ]"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" />
                  </svg>
                  <svg
                    :class="[
                      'h-3 w-3 -mt-1',
                      sortColumn === column.key && sortDirection === 'desc' 
                        ? 'text-gray-900' 
                        : 'text-gray-400'
                    ]"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                  </svg>
                </div>
              </div>
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <!-- Loading State -->
          <tr v-if="loading">
            <td :colspan="columns.length" class="px-6 py-4 text-center">
              <div class="flex items-center justify-center">
                <svg class="animate-spin h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                </svg>
                <span class="ml-2">Loading...</span>
              </div>
            </td>
          </tr>

          <!-- Data Rows -->
          <slot v-else :data="data" />

          <!-- Empty State -->
          <tr v-if="!loading && data.length === 0">
            <td :colspan="columns.length" class="px-6 py-4 text-center text-gray-500">
              No data available
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div v-if="pagination && pagination.last_page > 1" class="px-6 py-3 border-t border-gray-200">
      <div class="flex items-center justify-between">
        <div class="text-sm text-gray-700">
          Showing {{ ((pagination.current_page - 1) * pagination.per_page) + 1 }} to 
          {{ Math.min(pagination.current_page * pagination.per_page, pagination.total) }} of 
          {{ pagination.total }} results
        </div>
        <div class="flex items-center space-x-2">
          <button
            :disabled="pagination.current_page === 1"
            @click="emit('page-change', pagination.current_page - 1)"
            class="px-3 py-1 border border-gray-300 rounded-md text-sm disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
          >
            Previous
          </button>
          <button
            :disabled="pagination.current_page === pagination.last_page"
            @click="emit('page-change', pagination.current_page + 1)"
            class="px-3 py-1 border border-gray-300 rounded-md text-sm disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
          >
            Next
          </button>
        </div>
      </div>
    </div>
  </div>
</template>