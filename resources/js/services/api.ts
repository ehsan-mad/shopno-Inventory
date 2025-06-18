import axios from 'axios'

const api = axios.create({
  baseURL: '/', // Your routes are directly under root, not /api
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-Requested-With': 'XMLHttpRequest'
  }
})

// Function to get user ID
const getUserId = (): string | null => {
  // Method 1: Get user_id from meta tag
  const userMeta = document.querySelector('meta[name="user-id"]')?.getAttribute('content')
  if (userMeta) {
    return userMeta
  }
  
  // Method 2: Try to get from any global Laravel object
  if (typeof window !== 'undefined' && (window as any).Laravel?.user_id) {
    return (window as any).Laravel.user_id
  }
  
  return null
}

// Add request interceptor to include user_id header
api.interceptors.request.use((config) => {
  const userId = getUserId()
  if (userId) {
    config.headers['user_id'] = userId
  }
  
  return config
})

// Response interceptor for error handling
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      window.location.href = '/login'
    }
    return Promise.reject(error)
  }
)

export default api