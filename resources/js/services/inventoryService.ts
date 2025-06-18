import api from './api'

export interface Product {
  id: number
  name: string
  sku: string
  description: string
  category_id: number
  category?: Category
  price: number
  selling_price: number
  stock_quantity: number
  min_stock_level: number
  image?: string
  status: boolean
  created_at: string
  updated_at: string
}

export interface Category {
  id: number
  name: string
  description: string
  image?: string
  status: boolean
  products_count: number
  created_at: string
  updated_at: string
}

export interface Customer {
  id: number
  name: string
  email: string
  phone: string
  address: string
  city: string
  customer_type: 'regular' | 'wholesale' | 'retail' | 'premium'
  status: boolean
  created_at: string
  updated_at: string
}

export interface Sale {
  id: number
  sale_number: string
  customer_id: number
  customer?: Customer
  sale_date: string
  subtotal: number
  discount: number
  tax: number
  total: number
  status: 'pending' | 'completed' | 'cancelled'
  notes: string
  sale_items?: SaleItem[]
  created_at: string
  updated_at: string
}

export interface SaleItem {
  id: number
  sale_id: number
  product_id: number
  product?: Product
  quantity: number
  price: number
  total: number
}

export const inventoryService = {
  // Dashboard - Based on your DashboardController
  async getDashboardData() {
    const response = await api.get('/dashboard')
    return response.data
  },

  async getStockAlerts() {
    const response = await api.get('/stockAlerts')
    return response.data
  },

  // Products - Based on your ProductController routes
  async getProducts(params?: any) {
    const response = await api.get('/productList', { params })
    return response.data
  },

  async getProduct(id: number) {
    const response = await api.get('/productShow', { params: { id } })
    return response.data
  },

  async createProduct(data: FormData | Partial<Product>) {
    const response = await api.post('/productCreate', data, {
      headers: {
        'Content-Type': data instanceof FormData ? 'multipart/form-data' : 'application/json'
      }
    })
    return response.data
  },

  async updateProduct(data: FormData | (Partial<Product> & { id: number })) {
    const response = await api.post('/productUpdate', data, {
      headers: {
        'Content-Type': data instanceof FormData ? 'multipart/form-data' : 'application/json'
      }
    })
    return response.data
  },

  async deleteProduct(id: number) {
    const response = await api.delete('/productDelete', { params: { id } })
    return response.data
  },

  // Categories - Based on your CategoryController routes
  async getCategories(params?: any) {
    const response = await api.get('/categoryList', { params })
    return response.data
  },

  async getCategory(id: number) {
    const response = await api.get('/categoryShow', { params: { id } })
    return response.data
  },

  async createCategory(data: FormData | Partial<Category>) {
    const response = await api.post('/categoryCreate', data, {
      headers: {
        'Content-Type': data instanceof FormData ? 'multipart/form-data' : 'application/json'
      }
    })
    return response.data
  },

  async updateCategory(data: FormData | (Partial<Category> & { id: number })) {
    const response = await api.post('/categoryUpdate', data, {
      headers: {
        'Content-Type': data instanceof FormData ? 'multipart/form-data' : 'application/json'
      }
    })
    return response.data
  },

  async deleteCategory(id: number) {
    const response = await api.delete('/categoryDelete', { params: { id } })
    return response.data
  },

  // Customers - Based on your CustomerController routes
  async getCustomers(params?: any) {
    const response = await api.get('/customerList', { params })
    return response.data
  },

  async getCustomer(id: number) {
    const response = await api.get('/customerShow', { params: { id } })
    return response.data
  },

  async createCustomer(data: Partial<Customer>) {
    const response = await api.post('/customerCreate', data)
    return response.data
  },

  async updateCustomer(data: Partial<Customer> & { id: number }) {
    const response = await api.post('/customerUpdate', data)
    return response.data
  },

  async deleteCustomer(id: number) {
    const response = await api.delete('/customerDelete', { params: { id } })
    return response.data
  },

  // Sales - Based on your SaleController routes
  async getSales(params?: any) {
    const response = await api.get('/saleList', { params })
    return response.data
  },

  async getSale(id: number) {
    const response = await api.get('/saleShow', { params: { id } })
    return response.data
  },

  async createSale(data: any) {
    const response = await api.post('/saleCreate', data)
    return response.data
  },

  async updateSale(data: any) {
    const response = await api.post('/saleUpdate', data)
    return response.data
  },

  async deleteSale(id: number) {
    const response = await api.delete('/saleDelete', { params: { id } })
    return response.data
  },

  async getSalesSummary(params?: any) {
    const response = await api.get('/saleSummary', { params })
    return response.data
  },

  // Invoices - Based on your InvoiceController routes
  async downloadInvoice(saleId: number) {
    const response = await api.get('/downloadInvoice', { 
      params: { sale_id: saleId },
      responseType: 'blob'
    })
    return response.data
  },

  async previewInvoice(saleId: number) {
    window.open(`/previewInvoice?sale_id=${saleId}`, '_blank')
  }
}