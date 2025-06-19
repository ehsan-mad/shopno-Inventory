{{-- filepath: c:\dev\laravel\shopno-Inventory\resources\views\invoices\index.blade.php --}}
@extends('layouts.app')

@section('title', 'Invoices - Shopno Inventory')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Invoices</h1>
            <p class="text-gray-600">Manage and view all invoices</p>
        </div>
        <div class="flex space-x-3">
            <button
                onclick="exportInvoices()"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export All
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Invoices</p>
                    <p class="text-2xl font-bold text-gray-900" id="total-invoices">0</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Amount</p>
                    <p class="text-2xl font-bold text-gray-900" id="total-amount">$0.00</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">This Month</p>
                    <p class="text-2xl font-bold text-gray-900" id="month-invoices">0</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Avg. Invoice</p>
                    <p class="text-2xl font-bold text-gray-900" id="avg-invoice">$0.00</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input
                    type="text"
                    id="search-input"
                    placeholder="Search invoices..."
                    class="input-field"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                <input
                    type="date"
                    id="date-from"
                    class="input-field"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                <input
                    type="date"
                    id="date-to"
                    class="input-field"
                >
            </div>
            <div class="flex items-end">
                <button
                    onclick="filterInvoices()"
                    class="btn-primary w-full"
                >
                    Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Invoices Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Recent Invoices</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Invoice #
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Customer
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Amount
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody id="invoices-table-body" class="bg-white divide-y divide-gray-200">
                    <!-- Invoice rows will be populated by JavaScript -->
                </tbody>
            </table>
        </div>

        <!-- Loading State -->
        <div id="loading-state" class="p-8 text-center">
            <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-gray-500 bg-white hover:bg-gray-50 transition ease-in-out duration-150 cursor-not-allowed">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Loading invoices...
            </div>
        </div>

        <!-- Empty State -->
        <div id="empty-state" class="p-8 text-center hidden">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No invoices</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by creating your first sale.</p>
        </div>
    </div>
</div>

<!-- Invoice Preview Modal -->
<div id="invoice-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeInvoiceModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Invoice Preview</h3>
                    <button onclick="closeInvoiceModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="invoice-content">
                    <!-- Invoice content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Global variables
let invoicesData = [];
let currentPage = 1;
const perPage = 10;

// Load invoices on page load
document.addEventListener('DOMContentLoaded', function() {
    loadInvoices();
});

// Load invoices from API
async function loadInvoices() {
    try {
        showLoading(true);
        
        // Use hardcoded user ID for now (replace with actual auth later)
        const userId = '1'; // This matches the user_id in the sales data
        
        const response = await fetch('/getInvoices', {
            headers: {
                'user_id': userId,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (!response.ok) {
            throw new Error(`Failed to load invoices: ${response.status}`);
        }

        const data = await response.json();
        
        if (data.status === 'success') {
            invoicesData = data.data || [];
            updateStats();
            renderInvoicesTable();
        } else {
            showError('Failed to load invoices: ' + (data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error loading invoices:', error);
        showError('Error loading invoices: ' + error.message);
    } finally {
        showLoading(false);
    }
}

// Update statistics
function updateStats() {
    const totalInvoices = invoicesData.length;
    const totalAmount = invoicesData.reduce((sum, invoice) => sum + parseFloat(invoice.total_amount || 0), 0);
    const avgInvoice = totalInvoices > 0 ? totalAmount / totalInvoices : 0;
    
    const currentMonth = new Date().getMonth();
    const currentYear = new Date().getFullYear();
    const monthInvoices = invoicesData.filter(invoice => {
        const invoiceDate = new Date(invoice.sale_date);
        return invoiceDate.getMonth() === currentMonth && invoiceDate.getFullYear() === currentYear;
    }).length;

    document.getElementById('total-invoices').textContent = totalInvoices;
    document.getElementById('total-amount').textContent = `$${totalAmount.toFixed(2)}`;
    document.getElementById('month-invoices').textContent = monthInvoices;
    document.getElementById('avg-invoice').textContent = `$${avgInvoice.toFixed(2)}`;
}

// Render invoices table
function renderInvoicesTable() {
    const tbody = document.getElementById('invoices-table-body');
    const emptyState = document.getElementById('empty-state');
    
    if (invoicesData.length === 0) {
        tbody.innerHTML = '';
        emptyState.classList.remove('hidden');
        return;
    }
    
    emptyState.classList.add('hidden');
      const rows = invoicesData.map(invoice => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="font-medium text-blue-600">${invoice.invoice_number}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="font-medium text-gray-900">${invoice.customer_name || 'N/A'}</div>
                <div class="text-sm text-gray-500">${invoice.customer_email || ''}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${new Date(invoice.sale_date).toLocaleDateString()}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">$${parseFloat(invoice.total_amount || 0).toFixed(2)}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                    ${invoice.status || 'Completed'}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex justify-end space-x-2">
                    <button
                        onclick="previewInvoice(${invoice.id})"
                        class="text-blue-600 hover:text-blue-900"
                        title="Preview"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                    <button
                        onclick="downloadInvoice(${invoice.id})"
                        class="text-green-600 hover:text-green-900"
                        title="Download"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
    
    tbody.innerHTML = rows;
}

// Preview invoice
async function previewInvoice(saleId) {
    try {
        // Use hardcoded user ID for now (replace with actual auth later)
        const userId = '1'; // This matches the user_id in the sales data
        
        const response = await fetch(`/previewInvoice?sale_id=${saleId}`, {
            headers: {
                'user_id': userId,
                'Accept': 'text/html',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (!response.ok) {
            const errorText = await response.text();
            console.error('Preview error response:', errorText);
            throw new Error(`Failed to load invoice preview: ${response.status} ${response.statusText}`);
        }

        const html = await response.text();
        
        // Inject the sale ID into the template
        const modifiedHtml = html.replace(
            '</head>',
            `<script>window.currentSaleId = ${saleId};</script></head>`
        );
        
        document.getElementById('invoice-content').innerHTML = modifiedHtml;
        document.getElementById('invoice-modal').classList.remove('hidden');
    } catch (error) {
        console.error('Error previewing invoice:', error);
        showError('Error loading invoice preview: ' + error.message);
    }
}

// Download invoice
async function downloadInvoice(saleId) {
    try {
        const userId = '1'; // This matches the user_id in the sales data
        
        const response = await fetch(`/downloadInvoice?sale_id=${saleId}`, {
            method: 'GET',
            headers: {
                'user_id': userId,
                'Accept': 'application/pdf',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (!response.ok) {
            throw new Error(`Failed to download invoice: ${response.status}`);
        }

        // Get filename from response headers or create default
        const contentDisposition = response.headers.get('Content-Disposition');
        let filename = `invoice_${saleId}_${new Date().toISOString().split('T')[0]}.pdf`;
        
        if (contentDisposition) {
            const filenameMatch = contentDisposition.match(/filename="(.+)"/);
            if (filenameMatch) {
                filename = filenameMatch[1];
            }
        }

        // Create blob and download
        const blob = await response.blob();
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        window.URL.revokeObjectURL(url);
        
    } catch (error) {
        console.error('Error downloading invoice:', error);
        showError('Error downloading invoice: ' + error.message);
    }
}

// Close invoice modal
function closeInvoiceModal() {
    document.getElementById('invoice-modal').classList.add('hidden');
}

// Filter invoices
function filterInvoices() {
    const searchTerm = document.getElementById('search-input').value.toLowerCase();
    const dateFrom = document.getElementById('date-from').value;
    const dateTo = document.getElementById('date-to').value;
    
    // Implementation for filtering
    console.log('Filtering invoices...', { searchTerm, dateFrom, dateTo });
    
    // For now, just reload the table
    renderInvoicesTable();
}

// Export invoices
function exportInvoices() {
    console.log('Exporting invoices...');
    // Implementation for export functionality
}

// Show loading state
function showLoading(show) {
    const loadingState = document.getElementById('loading-state');
    if (show) {
        loadingState.classList.remove('hidden');
    } else {
        loadingState.classList.add('hidden');
    }
}
}

// Show error message
function showError(message) {
    // Simple error display - you can enhance this
    console.error(message);
    alert(message);
}
</script>
@endpush
