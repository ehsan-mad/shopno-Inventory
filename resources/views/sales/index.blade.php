@extends('layouts.app')

@section('title', 'Sales')

@section('content')
    <!-- Page Title and Add Button -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Sales
            </h2>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <button type="button" onclick="openCreateModal()" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-plus mr-2"></i>
                Add Sale
            </button>
        </div>
    </div>

    <!-- Sales Table -->
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
                                            Sale #
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Customer
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Actions</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="salesTableBody">
                                    <!-- Sales will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination Controls -->
    <div class="flex items-center justify-between mt-4" id="salesPagination" style="display:none;">
        <div>
            Showing <span id="salesPageStart">0</span> to <span id="salesPageEnd">0</span> of <span id="salesTotal">0</span> sales
        </div>
        <div>
            <button onclick="previousSalesPage()" class="px-3 py-1 bg-gray-200 rounded mr-2">Previous</button>
            <button onclick="nextSalesPage()" class="px-3 py-1 bg-gray-200 rounded">Next</button>
        </div>
    </div>

    <!-- Create/Edit Sale Modal -->
    <div id="saleModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="saleForm" class="p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modalTitle">Add Sale</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="customer_id" class="block text-sm font-medium text-gray-700">Customer</label>
                            <select name="customer_id" id="customer_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value="">Loading customers...</option>
                            </select>
                        </div>
                        <div>
                            <label for="sale_date" class="block text-sm font-medium text-gray-700">Date</label>
                            <input type="date" name="sale_date" id="sale_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>
                        <!-- Sale Items Section -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sale Items</label>
                            <div class="flex items-center space-x-2 mb-2 font-semibold text-gray-700 text-sm bg-gray-50 p-2 rounded">
                                <div class="w-1/2">Product</div>
                                <div class="w-1/6">Quantity</div>
                                <div class="w-1/6">Price</div>
                                <div class="w-1/6 text-center">Remove</div>
                            </div>
                            <div id="saleItemsContainer"></div>
                            <button type="button" onclick="addSaleItemRow()" class="mt-2 px-3 py-1 bg-indigo-100 text-indigo-700 rounded hover:bg-indigo-200 text-sm">+ Add Item</button>
                        </div>
                        <div>
                            <label for="discount" class="block text-sm font-medium text-gray-700">Discount</label>
                            <input type="number" name="discount" id="discount" min="0" step="0.01" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="tax" class="block text-sm font-medium text-gray-700">Tax</label>
                            <input type="number" name="tax" id="tax" min="0" step="0.01" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea name="notes" id="notes" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-2">
                        <button type="button" onclick="closeSaleModal()" class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">Cancel</button>
                        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">Save</button>
                    </div>
                    <div id="saleFormMessage" class="mt-3 text-sm"></div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Sale Modal -->
    <div id="viewModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Sale Details</h3>
                    <div id="viewSaleContent">Loading...</div>
                    <div class="mt-6 flex justify-end">
                        <button type="button" onclick="closeViewModal()" class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                <div class="p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Delete Sale</h3>
                    <p>Are you sure you want to delete this sale? This action cannot be undone.</p>
                    <div class="mt-6 flex justify-end space-x-2">
                        <button type="button" onclick="closeDeleteModal()" class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">Cancel</button>
                        <button type="button" onclick="confirmDeleteSale()" class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm">Delete</button>
                    </div>
                    <div id="deleteSaleMessage" class="mt-3 text-sm"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let salesCurrentPage = 1;
        let salesLastPage = 1;
        let salesPerPage = 10;
        let salesTotal = 0;
        let productOptions = [];
        let editingSaleId = null;
        let deletingSaleId = null;

        document.addEventListener('DOMContentLoaded', function() {
            loadSales();
        });

        function loadSales(page = 1) {
            const tableBody = document.getElementById('salesTableBody');
            const pagination = document.getElementById('salesPagination');
            tableBody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Loading...</td></tr>';
            pagination.style.display = 'none';

            fetch(`/salesList?page=${page}`, {
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
                    const sales = data.data || [];
                    salesCurrentPage = data.pagination.current_page;
                    salesLastPage = data.pagination.last_page;
                    salesPerPage = data.pagination.per_page;
                    salesTotal = data.pagination.total_items;

                    tableBody.innerHTML = '';
                    if (sales.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No sales found</td></tr>';
                        return;
                    }

                    sales.forEach(sale => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="px-6 py-4 whitespace-nowrap">${sale.sale_number || sale.id}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${sale.customer ? sale.customer.name : 'N/A'}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${sale.sale_date ? new Date(sale.sale_date).toLocaleDateString() : ''}</td>
                            <td class="px-6 py-4 whitespace-nowrap">$${sale.total}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${sale.status === 'completed' ? 'bg-green-100 text-green-800' : sale.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'}">
                                    ${sale.status ? sale.status.charAt(0).toUpperCase() + sale.status.slice(1) : 'N/A'}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button onclick="viewSale(${sale.id})" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="editSale(${sale.id})" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteSale(${sale.id})" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });

                    // Pagination info
                    document.getElementById('salesPageStart').textContent = ((salesCurrentPage - 1) * salesPerPage) + 1;
                    document.getElementById('salesPageEnd').textContent = Math.min(salesCurrentPage * salesPerPage, salesTotal);
                    document.getElementById('salesTotal').textContent = salesTotal;
                    pagination.style.display = salesTotal > 0 ? '' : 'none';
                } else {
                    tableBody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-red-500">Error loading sales</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                tableBody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-red-500">Error loading sales</td></tr>';
            });
        }

        function previousSalesPage() {
            if (salesCurrentPage > 1) {
                loadSales(salesCurrentPage - 1);
            }
        }

        function nextSalesPage() {
            if (salesCurrentPage < salesLastPage) {
                loadSales(salesCurrentPage + 1);
            }
        }

        // Fetch products for sale item dropdowns
        function loadProductsForSale() {
            fetch('/productList?per_page=100', {
                headers: {
                    'user_id': '{{ $user->id }}',
                    'email': '{{ $user->email }}',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    productOptions = (data.products && data.products.data) ? data.products.data : [];
                } else {
                    productOptions = [];
                }
                renderSaleItemsRows();
            })
            .catch(() => {
                productOptions = [];
                renderSaleItemsRows();
            });
        }

        // Sale items dynamic rows
        let saleItems = [];
        function addSaleItemRow() {
            saleItems.push({ product_id: '', quantity: 1, price: '' });
            renderSaleItemsRows();
        }
        function removeSaleItemRow(index) {
            saleItems.splice(index, 1);
            renderSaleItemsRows();
        }
        function updateSaleItemField(index, field, value) {
            saleItems[index][field] = value;
            // If the product is changed, auto-fill the price from productOptions
            if (field === 'product_id') {
                const product = productOptions.find(p => p.id == value);
                if (product) {
                    saleItems[index].price = product.price;
                } else {
                    saleItems[index].price = '';
                }
                renderSaleItemsRows(); // re-render to update the price input
            }
        }
        function renderSaleItemsRows() {
            const container = document.getElementById('saleItemsContainer');
            container.innerHTML = '';
            if (saleItems.length === 0) {
                container.innerHTML = '<div class="text-gray-400 text-sm">No items. Add at least one product.</div>';
            }
            saleItems.forEach((item, idx) => {
                const row = document.createElement('div');
                row.className = 'flex items-center space-x-2 mb-2';
                // Product select
                let productSelect = `<select class="border rounded px-2 py-1" onchange="updateSaleItemField(${idx}, 'product_id', this.value)">`;
                productSelect += '<option value="">Select product</option>';
                productOptions.forEach(p => {
                    productSelect += `<option value="${p.id}" ${item.product_id == p.id ? 'selected' : ''}>${p.name}</option>`;
                });
                productSelect += '</select>';
                // Quantity input
                let qtyInput = `<input type="number" min="1" class="border rounded px-2 py-1 w-20" value="${item.quantity}" onchange="updateSaleItemField(${idx}, 'quantity', this.value)">`;
                // Price input
                let priceInput = `<input type="number" min="0" step="0.01" class="border rounded px-2 py-1 w-24" value="${item.price}" onchange="updateSaleItemField(${idx}, 'price', this.value)">`;
                // Total (quantity * price)
                let total = (parseFloat(item.quantity) || 0) * (parseFloat(item.price) || 0);
                let totalField = `<input type='text' class='border rounded px-2 py-1 w-24 bg-gray-100' value='${total.toFixed(2)}' readonly title='Total = Quantity x Price'>`;
                // Remove button
                let removeBtn = `<button type="button" onclick="removeSaleItemRow(${idx})" class="text-red-500 hover:text-red-700 ml-2">&times;</button>`;
                row.innerHTML = productSelect + qtyInput + priceInput + totalField + removeBtn;
                container.appendChild(row);
            });
        }

        // Modal functions
        function openCreateModal() {
            document.getElementById('modalTitle').textContent = 'Add Sale';
            document.getElementById('saleForm').reset();
            document.getElementById('saleFormMessage').textContent = '';
            loadCustomers();
            loadProductsForSale();
            saleItems = [];
            addSaleItemRow();
            document.getElementById('saleModal').classList.remove('hidden');
        }
        function closeSaleModal() {
            document.getElementById('saleModal').classList.add('hidden');
            editingSaleId = null;
        }
        // Load customers for dropdown
        function loadCustomers() {
            const select = document.getElementById('customer_id');
            select.innerHTML = '<option value="">Loading customers...</option>';
            fetch('/customerList', {
                headers: {
                    'user_id': '{{ $user->id }}',
                    'email': '{{ $user->email }}',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const customers = data.data || [];
                    select.innerHTML = '<option value="">Select a customer</option>';
                    customers.forEach(customer => {
                        select.innerHTML += `<option value="${customer.id}">${customer.name}</option>`;
                    });
                } else {
                    select.innerHTML = '<option value="">Error loading customers</option>';
                }
            })
            .catch(() => {
                select.innerHTML = '<option value="">Error loading customers</option>';
            });
        }
        // Form submission
        document.getElementById('saleForm').addEventListener('submit', function(e) {
            e.preventDefault();
            document.getElementById('saleFormMessage').textContent = '';
            // Validate sale items
            if (!saleItems.length || saleItems.some(item => !item.product_id || !item.quantity || !item.price)) {
                document.getElementById('saleFormMessage').textContent = 'Please add at least one valid sale item.';
                document.getElementById('saleFormMessage').className = 'mt-3 text-red-600 text-sm';
                return;
            }
            // Build payload
            const payload = {
                customer_id: document.getElementById('customer_id').value,
                sale_date: document.getElementById('sale_date').value,
                status: document.getElementById('status').value,
                items: saleItems
            };
            // Optional fields
            const discount = document.getElementById('discount').value;
            if (discount) payload.discount = parseFloat(discount);
            const tax = document.getElementById('tax').value;
            if (tax) payload.tax = parseFloat(tax);
            const notes = document.getElementById('notes').value;
            if (notes) payload.notes = notes;
            let url = '/saleCreate';
            let method = 'POST';
            if (editingSaleId) {
                url = '/saleUpdate';
                method = 'POST';
                payload.id = editingSaleId;
            }
            fetch(url, {
                method: method,
                headers: {
                    'user_id': '{{ $user->id }}',
                    'email': '{{ $user->email }}',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload),
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('saleFormMessage').textContent = editingSaleId ? 'Sale updated successfully!' : 'Sale created successfully!';
                    document.getElementById('saleFormMessage').className = 'mt-3 text-green-600 text-sm';
                    setTimeout(() => {
                        closeSaleModal();
                        loadSales(salesCurrentPage);
                        editingSaleId = null;
                    }, 1000);
                } else {
                    document.getElementById('saleFormMessage').textContent = data.message || 'Error saving sale.';
                    document.getElementById('saleFormMessage').className = 'mt-3 text-red-600 text-sm';
                }
            })
            .catch(() => {
                document.getElementById('saleFormMessage').textContent = 'Error saving sale.';
                document.getElementById('saleFormMessage').className = 'mt-3 text-red-600 text-sm';
            });
        });

        // Preview Sale
        function viewSale(id) {
            document.getElementById('viewSaleContent').innerHTML = 'Loading...';
            document.getElementById('viewModal').classList.remove('hidden');
            fetch(`/saleShow?id=${id}`, {
                headers: {
                    'user_id': '{{ $user->id }}',
                    'email': '{{ $user->email }}',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const sale = data.sale;
                    let html = `<div><strong>Sale #:</strong> ${sale.sale_number || sale.id}</div>`;
                    html += `<div><strong>Customer:</strong> ${sale.customer ? sale.customer.name : 'N/A'}</div>`;
                    html += `<div><strong>Date:</strong> ${sale.sale_date ? new Date(sale.sale_date).toLocaleDateString() : ''}</div>`;
                    html += `<div><strong>Status:</strong> ${sale.status}</div>`;
                    html += `<div><strong>Discount:</strong> $${sale.discount || 0}</div>`;
                    html += `<div><strong>Tax:</strong> $${sale.tax || 0}</div>`;
                    html += `<div><strong>Notes:</strong> ${sale.notes || ''}</div>`;
                    html += `<div class='mt-2'><strong>Items:</strong><ul class='list-disc pl-5'>`;
                    (sale.sale_items || sale.saleItems || []).forEach(item => {
                        html += `<li>${item.product ? item.product.name : 'Product'} - Qty: ${item.quantity}, Price: $${item.price}, Total: $${item.total}</li>`;
                    });
                    html += `</ul></div>`;
                    html += `<div class='mt-2'><strong>Total:</strong> $${sale.total}</div>`;
                    document.getElementById('viewSaleContent').innerHTML = html;
                } else {
                    document.getElementById('viewSaleContent').innerHTML = 'Error loading sale details.';
                }
            })
            .catch(() => {
                document.getElementById('viewSaleContent').innerHTML = 'Error loading sale details.';
            });
        }
        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
        }

        // Edit Sale
        function editSale(id) {
            editingSaleId = id;
            document.getElementById('modalTitle').textContent = 'Edit Sale';
            document.getElementById('saleForm').reset();
            document.getElementById('saleFormMessage').textContent = '';
            loadCustomers();
            loadProductsForSale();
            // Fetch sale details
            fetch(`/saleShow?id=${id}`, {
                headers: {
                    'user_id': '{{ $user->id }}',
                    'email': '{{ $user->email }}',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const sale = data.sale;
                    document.getElementById('customer_id').value = sale.customer_id;
                    document.getElementById('sale_date').value = sale.sale_date;
                    document.getElementById('status').value = sale.status;
                    document.getElementById('discount').value = sale.discount || '';
                    document.getElementById('tax').value = sale.tax || '';
                    document.getElementById('notes').value = sale.notes || '';
                    saleItems = (sale.sale_items || sale.saleItems || []).map(item => ({
                        product_id: item.product_id,
                        quantity: item.quantity,
                        price: item.price
                    }));
                    renderSaleItemsRows();
                    document.getElementById('saleModal').classList.remove('hidden');
                } else {
                    alert('Error loading sale details');
                }
            })
            .catch(() => {
                alert('Error loading sale details');
            });
        }

        // Delete Sale
        function deleteSale(id) {
            deletingSaleId = id;
            document.getElementById('deleteSaleMessage').textContent = '';
            document.getElementById('deleteModal').classList.remove('hidden');
        }
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            deletingSaleId = null;
        }
        function confirmDeleteSale() {
            if (!deletingSaleId) return;
            document.getElementById('deleteSaleMessage').textContent = 'Deleting...';
            fetch('/saleDelete', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: deletingSaleId }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('deleteSaleMessage').textContent = 'Sale deleted!';
                    setTimeout(() => {
                        closeDeleteModal();
                        loadSales(salesCurrentPage);
                    }, 800);
                } else {
                    document.getElementById('deleteSaleMessage').textContent = data.message || 'Error deleting sale.';
                }
            })
            .catch(() => {
                document.getElementById('deleteSaleMessage').textContent = 'Error deleting sale.';
            });
        }
    </script>
@endsection 