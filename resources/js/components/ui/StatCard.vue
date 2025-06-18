<script setup lang="ts">
import { cn } from '@/lib/utils'

interface StatCardProps {
  title: string
  value: string | number
  icon?: string
  trend?: number
  loading?: boolean
  color?: 'blue' | 'green' | 'purple' | 'red' | 'yellow'
}

const props = withDefaults(defineProps<StatCardProps>(), {
  loading: false,
  color: 'blue'
})

const colorClasses = {
  blue: 'bg-blue-500 text-blue-100',
  green: 'bg-green-500 text-green-100',
  purple: 'bg-purple-500 text-purple-100',
  red: 'bg-red-500 text-red-100',
  yellow: 'bg-yellow-500 text-yellow-100'
}
</script>

<template>
  <div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex items-center justify-between">
      <div class="flex-1">
        <p class="text-sm font-medium text-gray-600">{{ title }}</p>
        <div class="mt-2">
          <div v-if="loading" class="animate-pulse">
            <div class="h-8 bg-gray-200 rounded w-24"></div>
          </div>
          <p v-else class="text-3xl font-bold text-gray-900">
            {{ typeof value === 'number' ? value.toLocaleString() : value }}
          </p>
        </div>
        <div v-if="trend !== undefined" class="mt-2 flex items-center">
          <span
            :class="[
              trend >= 0 ? 'text-green-600' : 'text-red-600',
              'text-sm font-medium'
            ]"
          >
            {{ trend >= 0 ? '+' : '' }}{{ trend }}%
          </span>
          <span class="text-sm text-gray-500 ml-1">from last month</span>
        </div>
      </div>
      <div
        v-if="icon"
        :class="[
          colorClasses[color],
          'flex items-center justify-center w-12 h-12 rounded-lg'
        ]"
      >
        <span class="text-2xl">{{ icon }}</span>
      </div>
    </div>
  </div>
</template>