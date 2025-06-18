<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales - Shopno Inventory</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="bg-gray-800 text-white w-64 py-6 flex flex-col">
            <div class="px-6 mb-8">
                <h1 class="text-2xl font-bold">Shopno Inventory</h1>
            </div>
            <nav class="flex-1">
                <a href="/dashboard" class="block px-6 py-2 hover:bg-gray-700">
                    <i class="fas fa-home mr-2"></i> Dashboard
                </a>
                <a href="/products" class="block px-6 py-2 hover:bg-gray-700">
                    <i class="fas fa-box mr-2"></i> Products
                </a>
                <a href="/categories" class="block px-6 py-2 hover:bg-gray-700">
                    <i class="fas fa-tags mr-2"></i> Categories
                </a>
                <a href="/customers" class="block px-6 py-2 hover:bg-gray-700">
                    <i class="fas fa-users mr-2"></i> Customers
                </a>
                <a href="/sales" class="block px-6 py-2 bg-gray-700">
                    <i class="fas fa-shopping-cart mr-2"></i> Sales
                </a>
                <a href="/invoices" class="block px-6 py-2 hover:bg-gray-700">
                    <i class="fas fa-file-invoice mr-2"></i> Invoices
                </a>
            </nav>
            <div class="px-6 py-4 border-t border-gray-700">
                <form action="/logout" method="POST" class="flex items-center">
                    @csrf
                    <button type="submit" class="flex items-center text-gray-300 hover:text-white">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Top Navigation -->
            <nav class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <h2 class="text-xl font-semibold">Sales</h2>
                        </div>
                        <div class="flex items-center">
                            <span class="text-gray-700 mr-4">{{ $user->first_name }} {{ $user->last_name }}</span>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Content -->
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <!-- Filters -->
                <div class="bg-white p-4 rounded-lg shadow mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date From</label>
                            <input type="date" id="dateFrom" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date To</label>
                            <input type="date" id="dateTo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select id="statusFilter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All</option>
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button onclick="applyFilters()" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sales Table -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-4 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-medium">Sales List</h3>
                            <button onclick="openModal()" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                                <i class="fas fa-plus mr-2"></i> New Sale
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sale #</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="salesTableBody" class="bg-white divide-y divide-gray-200">
                                <!-- Sales will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-700">
                                Showing <span id="paginationStart">0</span> to <span id="paginationEnd">0</span> of <span id="paginationTotal">0</span> entries
                            </div>
                            <div class="flex space-x-2">
                                <button onclick="changePage('prev')" class="px-3 py-1 border rounded-md hover:bg-gray-100">Previous</button>
                                <button onclick="changePage('next')" class="px-3 py-1 border rounded-md hover:bg-gray-100">Next</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sale Modal -->
    <div id="saleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-4/5 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium" id="modalTitle">New Sale</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="saleForm" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Customer</label>
                        <select id="customer_id" name="customer_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Customer</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Sale Date</label>
                        <input type="date" id="sale_date" name="sale_date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4">
                    <h4 class="text-md font-medium mb-4">Sale Items</h4>
                    <div id="saleItems" class="space-y-4">
                        <div class="sale-item grid grid-cols-12 gap-4">
                            <div class="col-span-4">
                                <label class="block text-sm font-medium text-gray-700">Product</label>
                                <select name="items[0][product_id]" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Select Product</option>
                                </select>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                <input type="number" name="items[0][quantity]" required min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Price</label>
                                <input type="number" name="items[0][price]" required min="0" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Total</label>
                                <input type="text" readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50">
                            </div>
                            <div class="col-span-2 flex items-end">
                                <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="addItem()" class="mt-4 text-indigo-600 hover:text-indigo-800">
                        <i class="fas fa-plus mr-2"></i> Add Item
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-t border-gray-200 pt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Subtotal</label>
                        <input type="text" id="subtotal" readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Discount</label>
                        <input type="number" id="discount" name="discount" min="0" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tax</label>
                        <input type="number" id="tax" name="tax" min="0" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Total Amount</label>
                            <input type="text" id="total" readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4">
                    <label class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>

                <div class="flex justify-end space-x-3 mt-4">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Save Sale
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Sale</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">Are you sure you want to delete this sale? This action cannot be undone.</p>
                </div>
                <div class="flex justify-center space-x-3 mt-4">
                    <button onclick="closeDeleteModal()" class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button onclick="confirmDelete()" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Sale Modal -->
    <div id="viewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-4/5 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium" id="viewModalTitle">Sale Details</h3>
                <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="viewModalContent" class="p-6">
                <!-- Sale details will be dynamically inserted here -->
            </div>
        </div>
    </div>

    <script>
        let currentPage = 1;
        let selectedSaleId = null;
        let isLoading = false;

        // Load sales on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadSales();
            loadCustomers();
            loadProducts();
        });

        // Load sales
        function loadSales(page = 1) {
            const tableBody = document.getElementById('salesTableBody');
            tableBody.innerHTML = '<tr><td colspan="6" class="text-center py-4">Loading...</td></tr>';

            const url = new URL('/salesList', window.location.origin);
            url.searchParams.append('page', page);

            fetch(url, {
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
                    tableBody.innerHTML = data.data.map(sale => `
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-700">${sale.id ?? 'N/A'}</td>
                            <td>${sale.customer ? sale.customer.name : 'N/A'}</td>
                            <td>${sale.sale_date || 'N/A'}</td>
                            <td>${sale.total || 'N/A'}</td>
                            <td>${sale.status || 'N/A'}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">
                                <button onclick="viewSale(${sale.id})" class="text-blue-600 hover:text-blue-900">View</button>
                                <button onclick="editSale(${sale.id})" class="text-yellow-600 hover:text-yellow-900">Edit</button>
                                <button onclick="deleteSale(${sale.id})" class="text-red-600 hover:text-red-900">Delete</button>
                                <button onclick="downloadInvoice(${sale.id})" class="text-green-600 hover:text-green-900">Download Invoice</button>
                                <button onclick="previewInvoice(${sale.id})" class="text-blue-600 hover:text-blue-900">Preview Invoice</button>
                            </td>
                        </tr>
                    `).join('');

                    // Update pagination
                    const pagination = document.getElementById('pagination');
                    if (pagination) {
                        pagination.innerHTML = `
                            <button onclick="loadSales(${data.pagination.current_page - 1})" ${data.pagination.current_page === 1 ? 'disabled' : ''} class="px-3 py-1 bg-gray-200 rounded">Previous</button>
                            <span class="mx-2">Page ${data.pagination.current_page} of ${data.pagination.last_page}</span>
                            <button onclick="loadSales(${data.pagination.current_page + 1})" ${data.pagination.current_page === data.pagination.last_page ? 'disabled' : ''} class="px-3 py-1 bg-gray-200 rounded">Next</button>
                        `;
                    }
                } else {
                    tableBody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-red-500">Error loading sales</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                tableBody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-red-500">Error loading sales</td></tr>';
            });
        }

        // Load customers for dropdown
        function loadCustomers() {
            fetch('/customerList', {
                headers: {
                    'user_id': '{{ $user->id }}',
                    'email': '{{ $user->email }}',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const customerSelect = document.getElementById('customer_id');
                    data.data.forEach(customer => {
                        const option = document.createElement('option');
                        option.value = customer.id;
                        option.textContent = customer.name;
                        customerSelect.appendChild(option);
                    });
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Load products for dropdown
        function loadProducts() {
            fetch('/productList', {
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
                    const productSelects = document.querySelectorAll('select[name^="items"][name$="[product_id]"]');
                    const products = data.products.data || [];
                    
                    productSelects.forEach(select => {
                        // Clear existing options except the first one
                        while (select.options.length > 1) {
                            select.remove(1);
                        }
                        
                        products.forEach(product => {
                            const option = document.createElement('option');
                            option.value = product.id;
                            option.textContent = `${product.name} (Stock: ${product.stock_quantity})`;
                            option.dataset.price = product.selling_price;
                            select.appendChild(option);
                        });
                    });

                    // Add event listener to update price when product is selected
                    productSelects.forEach(select => {
                        select.addEventListener('change', function() {
                            const selectedOption = this.options[this.selectedIndex];
                            const priceInput = this.closest('.sale-item').querySelector('input[name$="[price]"]');
                            if (selectedOption && selectedOption.dataset.price) {
                                priceInput.value = selectedOption.dataset.price;
                                calculateItemTotal(priceInput);
                            }
                        });
                    });
                } else {
                    console.error('Error loading products:', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        // Calculate item total
        function calculateItemTotal(input) {
            const itemDiv = input.closest('.sale-item');
            const quantity = itemDiv.querySelector('input[name$="[quantity]"]').value;
            const price = itemDiv.querySelector('input[name$="[price]"]').value;
            const total = quantity * price;
            itemDiv.querySelector('input[readonly]').value = total.toFixed(2);
            calculateTotals();
        }

        // Calculate all totals
        function calculateTotals() {
            let subtotal = 0;
            document.querySelectorAll('.sale-item input[readonly]').forEach(input => {
                subtotal += parseFloat(input.value || 0);
            });

            const discount = parseFloat(document.getElementById('discount').value || 0);
            const tax = parseFloat(document.getElementById('tax').value || 0);
            const total = subtotal - discount + tax;

            document.getElementById('subtotal').value = subtotal.toFixed(2);
            document.getElementById('total').value = total.toFixed(2);
        }

        // Add new item row
        function addItem() {
            const itemsContainer = document.getElementById('saleItems');
            const itemCount = itemsContainer.children.length;
            const newItem = document.createElement('div');
            newItem.className = 'sale-item grid grid-cols-12 gap-4';
            newItem.innerHTML = `
                <div class="col-span-4">
                    <label class="block text-sm font-medium text-gray-700">Product</label>
                    <select name="items[${itemCount}][product_id]" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Select Product</option>
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Quantity</label>
                    <input type="number" name="items[${itemCount}][quantity]" required min="1" onchange="calculateItemTotal(this)" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Price</label>
                    <input type="number" name="items[${itemCount}][price]" required min="0" step="0.01" onchange="calculateItemTotal(this)" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Total</label>
                    <input type="text" readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50">
                </div>
                <div class="col-span-2 flex items-end">
                    <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;

            // Add products to the new select
            const productSelect = newItem.querySelector('select');
            document.querySelector('select[name="items[0][product_id]"]').querySelectorAll('option').forEach(option => {
                productSelect.appendChild(option.cloneNode(true));
            });

            itemsContainer.appendChild(newItem);
        }

        // Remove item row
        function removeItem(button) {
            const itemsContainer = document.getElementById('saleItems');
            if (itemsContainer.children.length > 1) {
                button.closest('.sale-item').remove();
                calculateTotals();
            }
        }

        // Form submission
        document.getElementById('saleForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const isEdit = selectedSaleId !== null;
            const url = isEdit ? '/saleUpdate' : '/saleCreate';
            
            if (isEdit) {
                formData.append('id', selectedSaleId);
            }

            fetch(url, {
                method: 'POST',
                headers: {
                    'user_id': '{{ $user->id }}',
                    'email': '{{ $user->email }}',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                credentials: 'same-origin',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    closeModal();
                    loadSales();
                } else {
                    alert(data.message || 'An error occurred');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving the sale');
            });
        });

        // View sale details
        function viewSale(id) {
            selectedSaleId = id;
            fetch(`/saleShow?id=${id}`, {
                headers: {
                    'user_id': '{{ $user->id }}',
                    'email': '{{ $user->email }}',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const sale = data.sale;
                    const viewModal = document.getElementById('viewModal');
                    const viewModalContent = document.getElementById('viewModalContent');
                    viewModalContent.innerHTML = `
                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6 rounded-lg shadow-lg text-white">
                            <h3 class="text-lg font-medium mb-4 flex items-center">
                                <i class="fas fa-receipt mr-2"></i> Sale Details
                            </h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="flex items-center"><i class="fas fa-id-card mr-2"></i> <strong>Sale ID:</strong> ${sale.id}</p>
                                    <p class="flex items-center"><i class="fas fa-hashtag mr-2"></i> <strong>Sale Number:</strong> ${sale.sale_number || 'N/A'}</p>
                                    <p class="flex items-center"><i class="fas fa-user mr-2"></i> <strong>Customer:</strong> ${sale.customer ? sale.customer.name : 'N/A'}</p>
                                    <p class="flex items-center"><i class="fas fa-calendar mr-2"></i> <strong>Sale Date:</strong> ${sale.sale_date || 'N/A'}</p>
                                </div>
                                <div>
                                    <p class="flex items-center"><i class="fas fa-dollar-sign mr-2"></i> <strong>Total:</strong> ${sale.total || 'N/A'}</p>
                                    <p class="flex items-center"><i class="fas fa-info-circle mr-2"></i> <strong>Status:</strong> ${sale.status || 'N/A'}</p>
                                    <p class="flex items-center"><i class="fas fa-clock mr-2"></i> <strong>Created At:</strong> ${sale.created_at || 'N/A'}</p>
                                </div>
                            </div>
                            <h4 class="text-md font-medium mt-4 mb-2 flex items-center">
                                <i class="fas fa-shopping-cart mr-2"></i> Sale Items
                            </h4>
                            <div class="mt-2">
                                ${sale.sale_items ? sale.sale_items.map(item => `
                                    <div class="border-t border-white pt-2">
                                        <p class="flex items-center"><i class="fas fa-box mr-2"></i> <strong>Product:</strong> ${item.product ? item.product.name : 'N/A'}</p>
                                        <p class="flex items-center"><i class="fas fa-sort-numeric-up mr-2"></i> <strong>Quantity:</strong> ${item.quantity || 'N/A'}</p>
                                        <p class="flex items-center"><i class="fas fa-tag mr-2"></i> <strong>Price:</strong> ${item.price || 'N/A'}</p>
                                        <p class="flex items-center"><i class="fas fa-calculator mr-2"></i> <strong>Total:</strong> ${item.total || 'N/A'}</p>
                                    </div>
                                `).join('') : 'No items found'}
                            </div>
                        </div>
                    `;
                    viewModal.classList.remove('hidden');
                } else {
                    alert(data.message || 'Error loading sale details');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading sale details');
            });
        }

        // Edit sale
        function editSale(id) {
            selectedSaleId = id;
            fetch(`/saleShow?id=${id}`, {
                headers: {
                    'user_id': '{{ $user->id }}',
                    'email': '{{ $user->email }}',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
                console.log('Received data:', data);
                console.log('Sale details:', data.sale);
                console.log('Sale items:', data.sale.sale_items);
                if (data.status === 'success') {
                    const sale = data.sale;
                    console.log('Sale details:', sale);
                    document.getElementById('modalTitle').textContent = 'Edit Sale';
                    document.getElementById('customer_id').value = sale.customer_id;
                    document.getElementById('sale_date').value = sale.sale_date.split(' ')[0];
                    document.getElementById('discount').value = sale.discount;
                    document.getElementById('tax').value = sale.tax;
                    document.getElementById('status').value = sale.status;
                    document.getElementById('notes').value = sale.notes;

                    // Clear existing items
                    const itemsContainer = document.getElementById('saleItems');
                    itemsContainer.innerHTML = '';

                    // Add sale items
                    if (sale.sale_items && sale.sale_items.length > 0) {
                        sale.sale_items.forEach((item, index) => {
                            if (index > 0) addItem();
                            const itemDiv = itemsContainer.children[index];
                            if (itemDiv) {
                                const productSelect = itemDiv.querySelector('select[name$="[product_id]"]');
                                const quantityInput = itemDiv.querySelector('input[name$="[quantity]"]');
                                const priceInput = itemDiv.querySelector('input[name$="[price]"]');
                                if (productSelect) productSelect.value = item.product_id;
                                if (quantityInput) quantityInput.value = item.quantity;
                                if (priceInput) priceInput.value = item.price;
                                calculateItemTotal(quantityInput);
                            }
                        });
                    }

                    document.getElementById('subtotal').value = sale.total;
                    document.getElementById('total').value = sale.total;

                    document.getElementById('saleModal').classList.remove('hidden');
                } else {
                    alert(data.message || 'Error loading sale details');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading sale details');
            });
        }

        // Delete sale
        function deleteSale(id) {
            selectedSaleId = id;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function confirmDelete() {
            fetch('/saleDelete', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'user_id': '{{ $user->id }}',
                    'email': '{{ $user->email }}',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                credentials: 'same-origin',
                body: JSON.stringify({ id: selectedSaleId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    closeDeleteModal();
                    loadSales();
                } else {
                    alert(data.message || 'An error occurred');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the sale');
            });
        }

        // Modal functions
        function openModal() {
            selectedSaleId = null;
            document.getElementById('modalTitle').textContent = 'New Sale';
            document.getElementById('saleForm').reset();
            document.getElementById('saleItems').innerHTML = `
                <div class="sale-item grid grid-cols-12 gap-4">
                    <div class="col-span-4">
                        <label class="block text-sm font-medium text-gray-700">Product</label>
                        <select name="items[0][product_id]" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Product</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Quantity</label>
                        <input type="number" name="items[0][quantity]" required min="1" onchange="calculateItemTotal(this)" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Price</label>
                        <input type="number" name="items[0][price]" required min="0" step="0.01" onchange="calculateItemTotal(this)" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Total</label>
                        <input type="text" readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50">
                    </div>
                    <div class="col-span-2 flex items-end">
                        <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            loadProducts();
            document.getElementById('saleModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('saleModal').classList.add('hidden');
            selectedSaleId = null;
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            selectedSaleId = null;
        }

        // Filter functions
        function applyFilters() {
            currentPage = 1;
            loadSales();
        }

        function changePage(direction) {
            if (direction === 'prev' && currentPage > 1) {
                currentPage--;
            } else if (direction === 'next') {
                currentPage++;
            }
            loadSales();
        }

        function updatePagination(pagination) {
            document.getElementById('paginationStart').textContent = ((pagination.current_page - 1) * pagination.per_page) + 1;
            document.getElementById('paginationEnd').textContent = Math.min(pagination.current_page * pagination.per_page, pagination.total_items);
            document.getElementById('paginationTotal').textContent = pagination.total_items;
        }

        function getStatusClass(status) {
            switch (status) {
                case 'completed':
                    return 'bg-green-100 text-green-800';
                case 'pending':
                    return 'bg-yellow-100 text-yellow-800';
                case 'cancelled':
                    return 'bg-red-100 text-red-800';
                default:
                    return 'bg-gray-100 text-gray-800';
            }
        }

        // View Sale Modal functions
        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
            selectedSaleId = null;
        }

        function downloadInvoice(saleId) {
            fetch(`/downloadInvoice?sale_id=${saleId}`, {
                headers: {
                    'user_id': '{{ $user->id }}',
                    'email': '{{ $user->email }}',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                credentials: 'same-origin'
            }).then(response => response.blob())
              .then(blob => {
                  const url = window.URL.createObjectURL(blob);
                  const a = document.createElement('a');
                  a.href = url;
                  a.download = `invoice_${saleId}.pdf`;
                  document.body.appendChild(a);
                  a.click();
                  window.URL.revokeObjectURL(url);
              });
        }

        function previewInvoice(saleId) {
            fetch(`/previewInvoice?sale_id=${saleId}`, {
                headers: {
                    'user_id': '{{ $user->id }}',
                    'email': '{{ $user->email }}',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                credentials: 'same-origin'
            }).then(response => response.text())
              .then(html => {
                  const win = window.open('', '_blank');
                  win.document.write(html);
                  win.document.close();
              });
        }
    </script>
</body>
</html> 