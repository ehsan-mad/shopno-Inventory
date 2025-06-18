<script setup lang="ts">
import { ref, reactive } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import Button from '@/components/ui/button/Button.vue'
import { Eye, EyeOff, Lock, Mail, User } from 'lucide-vue-next'
import api from '@/lib/axios'

const showPassword = ref(false)
const showPasswordConfirmation = ref(false)
const loading = ref(false)

const form = reactive({
  first_name: '', // Add first_name
  last_name: '',  // Add last_name
  email: '',
  password: '',
  password_confirmation: '',
})

const errors = ref<Record<string, string>>({})

const togglePasswordVisibility = () => {
  showPassword.value = !showPassword.value
}

const togglePasswordConfirmationVisibility = () => {
  showPasswordConfirmation.value = !showPasswordConfirmation.value
}

const submit = async () => {
  try {
    loading.value = true
    errors.value = {}

    const response = await api.post('/registration', {
      first_name: form.first_name,
      last_name: form.last_name,
      email: form.email,
      password: form.password,
      password_confirmation: form.password_confirmation
    })

    const data = response.data

    if (response.status === 201 && data.status === 'success') {
      router.visit('/login', {
        data: { message: 'Registration successful! Please login.' }
      })
    }
  } catch (error: any) {
    console.error('Registration error:', error)
    
    if (error.response?.status === 422 && error.response?.data?.errors) {
      errors.value = error.response.data.errors
    } else if (error.response?.data?.message) {
      errors.value = { email: [error.response.data.message] }
    } else {
      errors.value = { email: ['Registration failed. Please try again.'] }
    }
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <Head title="Register" />

  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <!-- Header -->
      <div class="text-center">
        <div class="mx-auto h-12 w-12 bg-blue-600 rounded-xl flex items-center justify-center">
          <User class="h-6 w-6 text-white" />
        </div>
        <h2 class="mt-6 text-3xl font-bold text-gray-900">
          Create your account
        </h2>
        <p class="mt-2 text-sm text-gray-600">
          Join Shopno Inventory today
        </p>
      </div>

      <!-- Register Form -->
      <div class="bg-white rounded-lg shadow-lg p-8">
        <form @submit.prevent="submit" class="space-y-6">
          <!-- First Name & Last Name Row -->
          <div class="grid grid-cols-2 gap-4">
            <!-- First Name -->
            <div>
              <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                First Name
              </label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <User class="h-5 w-5 text-gray-400" />
                </div>
                <input
                  id="first_name"
                  v-model="form.first_name"
                  type="text"
                  placeholder="First name"
                  class="h-10 w-full rounded-md border border-gray-300 bg-white pl-10 pr-3 py-2 text-sm text-gray-900 placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  :class="errors.first_name ? 'border-red-500' : ''"
                  required
                  autofocus
                />
              </div>
              <p v-if="errors.first_name" class="mt-1 text-sm text-red-600">
                {{ Array.isArray(errors.first_name) ? errors.first_name[0] : errors.first_name }}
              </p>
            </div>

            <!-- Last Name -->
            <div>
              <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                Last Name
              </label>
              <input
                id="last_name"
                v-model="form.last_name"
                type="text"
                placeholder="Last name"
                class="h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                :class="errors.last_name ? 'border-red-500' : ''"
                required
              />
              <p v-if="errors.last_name" class="mt-1 text-sm text-red-600">
                {{ Array.isArray(errors.last_name) ? errors.last_name[0] : errors.last_name }}
              </p>
            </div>
          </div>

          <!-- Email -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
              Email Address
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <Mail class="h-5 w-5 text-gray-400" />
              </div>
              <input
                id="email"
                v-model="form.email"
                type="email"
                placeholder="Enter your email"
                class="h-10 w-full rounded-md border border-gray-300 bg-white pl-10 pr-3 py-2 text-sm text-gray-900 placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                :class="errors.email ? 'border-red-500' : ''"
                required
              />
            </div>
            <p v-if="errors.email" class="mt-1 text-sm text-red-600">
              {{ Array.isArray(errors.email) ? errors.email[0] : errors.email }}
            </p>
          </div>

          <!-- Password -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
              Password
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <Lock class="h-5 w-5 text-gray-400" />
              </div>
              <input
                id="password"
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                placeholder="Enter your password"
                class="h-10 w-full rounded-md border border-gray-300 bg-white pl-10 pr-10 py-2 text-sm text-gray-900 placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                :class="errors.password ? 'border-red-500' : ''"
                required
              />
              <button
                type="button"
                @click="togglePasswordVisibility"
                class="absolute inset-y-0 right-0 pr-3 flex items-center"
              >
                <Eye v-if="!showPassword" class="h-5 w-5 text-gray-400 hover:text-gray-600" />
                <EyeOff v-else class="h-5 w-5 text-gray-400 hover:text-gray-600" />
              </button>
            </div>
            <p v-if="errors.password" class="mt-1 text-sm text-red-600">
              {{ Array.isArray(errors.password) ? errors.password[0] : errors.password }}
            </p>
          </div>

          <!-- Confirm Password -->
          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
              Confirm Password
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <Lock class="h-5 w-5 text-gray-400" />
              </div>
              <input
                id="password_confirmation"
                v-model="form.password_confirmation"
                :type="showPasswordConfirmation ? 'text' : 'password'"
                placeholder="Confirm your password"
                class="h-10 w-full rounded-md border border-gray-300 bg-white pl-10 pr-10 py-2 text-sm text-gray-900 placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                :class="errors.password_confirmation ? 'border-red-500' : ''"
                required
              />
              <button
                type="button"
                @click="togglePasswordConfirmationVisibility"
                class="absolute inset-y-0 right-0 pr-3 flex items-center"
              >
                <Eye v-if="!showPasswordConfirmation" class="h-5 w-5 text-gray-400 hover:text-gray-600" />
                <EyeOff v-else class="h-5 w-5 text-gray-400 hover:text-gray-600" />
              </button>
            </div>
            <p v-if="errors.password_confirmation" class="mt-1 text-sm text-red-600">
              {{ Array.isArray(errors.password_confirmation) ? errors.password_confirmation[0] : errors.password_confirmation }}
            </p>
          </div>

          <!-- Submit Button -->
          <Button
            type="submit"
            :loading="loading"
            class="w-full"
          >
            Create Account
          </Button>

          <!-- Login Link -->
          <div class="text-center">
            <span class="text-sm text-gray-600">
              Already have an account?
              <a
                href="/login"
                class="font-medium text-blue-600 hover:text-blue-500"
              >
                Sign in
              </a>
            </span>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
