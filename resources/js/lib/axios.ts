import axios from 'axios'

// Create axios instance with default config
const api = axios.create({
  baseURL: '/',
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  }
})

// Request interceptor to add CSRF token and auth token
api.interceptors.request.use(
  (config) => {
    // Add CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    if (csrfToken) {
      config.headers['X-CSRF-TOKEN'] = csrfToken
    }

    // Add auth token from localStorage
    const authToken = localStorage.getItem('auth_token')
    if (authToken) {
      config.headers['Authorization'] = `Bearer ${authToken}`
    }

    return config
  },
  (error) => {
    return Promise.reject(error)
  }
)

// Response interceptor for global error handling
api.interceptors.response.use(
  (response) => response,
  (error) => {
    // Handle 401 unauthorized - redirect to login
    if (error.response?.status === 401) {
      localStorage.removeItem('auth_token')
      sessionStorage.removeItem('auth_token')
      window.location.href = '/login'
    }
    
    return Promise.reject(error)
  }
)

export default api