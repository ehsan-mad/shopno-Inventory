@extends('layouts.app')

@section('title', 'Products')

@section('content')
    <!-- Page Title and Add Button -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Products
            </h2>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <button type="button" onclick="openCreateModal()" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-plus mr-2"></i>
                Add Product
            </button>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Name
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Category
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Price
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Stock
                                        </th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Actions</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="productsTableBody">
                                    <!-- Products will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-between mt-4">
        <div>
            Showing <span id="currentPageStart">0</span> to <span id="currentPageEnd">0</span> of <span id="totalItems">0</span> products
        </div>
        <div>
            <button onclick="previousPage()" class="px-3 py-1 bg-gray-200 rounded mr-2">Previous</button>
            <button onclick="nextPage()" class="px-3 py-1 bg-gray-200 rounded">Next</button>
        </div>
    </div>

    <!-- Create/Edit Product Modal -->
    <div id="productModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="productForm" class="p-6">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalTitle">
                        Add Product
                    </h3>
                    <div class="mt-2">
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" name="name" id="name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                            </div>
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                            </div>
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                                <select name="category_id" id="category_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                    <!-- Categories will be loaded here -->
                                </select>
                            </div>
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700">Purchase Price</label>
                                <input type="number" name="price" id="price" step="0.01" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                            </div>
                            <div>
                                <label for="selling_price" class="block text-sm font-medium text-gray-700">Selling Price</label>
                                <input type="number" name="selling_price" id="selling_price" step="0.01" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                            </div>
                            <div>
                                <label for="stock_quantity" class="block text-sm font-medium text-gray-700">Stock Quantity</label>
                                <input type="number" name="stock_quantity" id="stock_quantity" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                            </div>
                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700">Product Image</label>
                                <input type="file" name="image" id="image" accept="image/*" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm">
                        Save
                    </button>
                    <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Delete Product
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete this product? This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="confirmDelete()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Delete
                    </button>
                    <button type="button" onclick="closeDeleteModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentPage = 1;
        let totalPages = 1;
        let selectedProductId = null;
        let isLoading = false;

        // Load products on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadProducts();
            loadCategories();
        });

        // Load products with pagination
        function loadProducts(page = 1) {
            isLoading = true;
            const tableBody = document.getElementById('productsTableBody');
            tableBody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Loading...</td></tr>';

            fetch(`/productList?page=${page}`, {
                headers: {
                    'user_id': '{{ $user->id }}',
                    'email': '{{ $user->email }}',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                isLoading = false;
                if (data.status === 'success') {
                    const products = data.products.data || [];
                    tableBody.innerHTML = '';

                    if (products.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No products found</td></tr>';
                        return;
                    }

                    products.forEach(product => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        ${product.image ?
                                            `<img class="h-10 w-10 rounded-full" src="/storage/${product.image}" alt="${product.name}">` :
                                            `<div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                <i class="fas fa-box text-gray-400"></i>
                                            </div>`
                                        }
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">${product.name}</div>
                                        <div class="text-sm text-gray-500">${product.description || ''}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">${product.category ? product.category.name : 'N/A'}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">$${product.price}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">${product.stock_quantity}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${product.status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                    ${product.status ? 'Active' : 'Inactive'}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button onclick="editProduct(${product.id})" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteProduct(${product.id})" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });

                    // Update pagination
                    currentPage = data.pagination.current_page;
                    totalPages = data.pagination.total_pages;
                    document.getElementById('currentPageStart').textContent = (currentPage - 1) * data.pagination.per_page + 1;
                    document.getElementById('currentPageEnd').textContent = Math.min(currentPage * data.pagination.per_page, data.pagination.total_items);
                    document.getElementById('totalItems').textContent = data.pagination.total_items;
                } else {
                    tableBody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-red-500">Error loading products</td></tr>';
                }
            })
            .catch(error => {
                isLoading = false;
                console.error('Error:', error);
                tableBody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-red-500">Error loading products</td></tr>';
            });
        }

        // Load categories for dropdown
        function loadCategories() {
            const select = document.getElementById('category_id');
            select.innerHTML = '<option value="">Loading categories...</option>';

            fetch('/categoryList', {
                headers: {
                    'user_id': '{{ $user->id }}',
                    'email': '{{ $user->email }}',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    const categories = data.category || [];
                    select.innerHTML = '<option value="">Select a category</option>';
                    categories.forEach(category => {
                        select.innerHTML += `<option value="${category.id}">${category.name}</option>`;
                    });
                } else {
                    select.innerHTML = '<option value="">Error loading categories</option>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                select.innerHTML = '<option value="">Error loading categories</option>';
            });
        }

        // Modal functions
        function openCreateModal() {
            document.getElementById('modalTitle').textContent = 'Add Product';
            document.getElementById('productForm').reset();
            document.getElementById('productModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('productModal').classList.add('hidden');
        }

        function openDeleteModal() {
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Form submission
        document.getElementById('productForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const isEdit = selectedProductId !== null;
            const url = isEdit ? '/productUpdate' : '/productCreate';

            if (isEdit) {
                formData.append('id', selectedProductId);
            }

            // Clear previous error messages
            const errorMessages = document.querySelectorAll('.error-message');
            errorMessages.forEach(el => el.remove());

            fetch(url, {
                method: 'POST',
                headers: {
                    'user_id': '{{ $user->id }}',
                    'email': '{{ $user->email }}',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData,
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(JSON.stringify(data));
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    closeModal();
                    loadProducts(currentPage);
                } else {
                    alert(data.message || 'An error occurred');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                try {
                    const errorData = JSON.parse(error.message);
                    if (errorData.errors) {
                        // Display validation errors
                        Object.keys(errorData.errors).forEach(field => {
                            const input = document.getElementById(field);
                            if (input) {
                                const errorDiv = document.createElement('div');
                                errorDiv.className = 'error-message text-red-500 text-sm mt-1';
                                errorDiv.textContent = errorData.errors[field][0];
                                input.parentNode.appendChild(errorDiv);
                            }
                        });
                    } else {
                        alert(errorData.message || 'An error occurred while saving the product');
                    }
                } catch (e) {
                    alert('An error occurred while saving the product');
                }
            });
        });

        // Edit product
        function editProduct(id) {
            selectedProductId = id;
            fetch(`/productShow?id=${id}`, {
                headers: {
                    'user_id': '{{ $user->id }}',
                    'email': '{{ $user->email }}',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(JSON.stringify(data));
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    const product = data.product;
                    document.getElementById('modalTitle').textContent = 'Edit Product';
                    document.getElementById('name').value = product.name;
                    document.getElementById('description').value = product.description || '';
                    document.getElementById('category_id').value = product.category_id;
                    document.getElementById('price').value = product.price;
                    document.getElementById('selling_price').value = product.selling_price;
                    document.getElementById('stock_quantity').value = product.stock_quantity;
                    document.getElementById('status').value = product.status ? '1' : '0';
                    document.getElementById('productModal').classList.remove('hidden');
                } else {
                    alert(data.message || 'Error loading product details');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                try {
                    const errorData = JSON.parse(error.message);
                    alert(errorData.message || 'Error loading product details');
                } catch (e) {
                    alert('Error loading product details');
                }
            });
        }

        // Delete product
        function deleteProduct(id) {
            selectedProductId = id;
            openDeleteModal();
        }

        function confirmDelete() {
            fetch('/productDelete', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: selectedProductId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    closeDeleteModal();
                    loadProducts(currentPage);
                } else {
                    alert(data.message || 'An error occurred');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the product');
            });
        }

        // Pagination
        function previousPage() {
            if (currentPage > 1 && !isLoading) {
                loadProducts(currentPage - 1);
            }
        }

        function nextPage() {
            if (currentPage < totalPages && !isLoading) {
                loadProducts(currentPage + 1);
            }
        }
    </script>
@endsection
