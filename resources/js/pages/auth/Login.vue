<script setup lang="ts">
import { ref, reactive } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import Button from '@/components/ui/button/Button.vue'
import { Eye, EyeOff, Lock, Mail } from 'lucide-vue-next'
import api from '@/lib/axios'

const showPassword = ref(false)
const loading = ref(false)

const form = reactive({
  email: '',
  password: '',
  remember: false
})

const errors = ref<Record<string, any>>({})

const togglePasswordVisibility = () => {
  showPassword.value = !showPassword.value
}

const submit = async () => {
  try {
    loading.value = true
    errors.value = {}

    const response = await api.post('/login', {
      email: form.email,
      password: form.password,
      remember: form.remember
    })

    if (response.data.status === 'success') {
      // Store token from your JWT system
      if (response.data.token) {
        localStorage.setItem('auth_token', response.data.token)
      }
      
      // Your controller sets cookie automatically
      // Redirect to dashboard
      router.visit('/dashboard')
    }
  } catch (error: any) {
    if (error.response?.status === 422) {
      // Laravel validation errors
      errors.value = error.response.data.errors || {}
    } else if (error.response?.status === 401) {
      // Invalid credentials
      errors.value = { email: ['Invalid email or password'] }
    } else {
      // Server error
      errors.value = { email: ['Something went wrong. Please try again.'] }
    }
  } finally {
    loading.value = false
    form.password = ''
  }
}
</script>

<template>
  <Head title="Login" />

  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <!-- Header -->
      <div class="text-center">
        <div class="mx-auto h-12 w-12 bg-blue-600 rounded-xl flex items-center justify-center">
          <Lock class="h-6 w-6 text-white" />
        </div>
        <h2 class="mt-6 text-3xl font-bold text-gray-900">
          Sign in to your account
        </h2>
        <p class="mt-2 text-sm text-gray-600">
          Welcome back to Shopno Inventory
        </p>
      </div>

      <!-- Login Form -->
      <div class="bg-white rounded-lg shadow-lg p-8">
        <form @submit.prevent="submit" class="space-y-6">
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
                autofocus
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

          <!-- Remember Me -->
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input
                id="remember"
                v-model="form.remember"
                type="checkbox"
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
              />
              <label for="remember" class="ml-2 block text-sm text-gray-700">
                Remember me
              </label>
            </div>

            <a
              href="/forgot-password"
              class="text-sm text-blue-600 hover:text-blue-500"
            >
              Forgot password?
            </a>
          </div>

          <!-- Submit Button -->
          <Button
            type="submit"
            :loading="loading"
            class="w-full"
          >
            Sign in
          </Button>

          <!-- Register Link -->
          <div class="text-center">
            <span class="text-sm text-gray-600">
              Don't have an account?
              <a
                href="/register"
                class="font-medium text-blue-600 hover:text-blue-500"
              >
                Sign up
              </a>
            </span>
          </div>
        </form>
      </div>

      <!-- Demo Credentials -->
      <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <h3 class="text-sm font-medium text-yellow-800 mb-2">Demo Credentials</h3>
        <div class="text-xs text-yellow-700 space-y-1">
          <p><strong>Email:</strong> admin@example.com</p>
          <p><strong>Password:</strong> password</p>
        </div>
      </div>
    </div>
  </div>
</template>
