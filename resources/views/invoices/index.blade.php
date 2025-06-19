
@extends('layouts.app')

@section('title', 'Invoices - Shopno Inventory')

@section('content')
<div class="max-w-xl mx-auto mt-12 bg-white p-8 rounded shadow">
    <h1 class="text-2xl font-bold mb-6 text-center">Download Invoices by Customer</h1>
    <form id="downloadForm" class="space-y-4">
        <div>
            <label for="customerSelect" class="block text-sm font-medium text-gray-700 mb-2">Select Customer</label>
            <select id="customerSelect" name="customer_id" class="w-full border rounded px-3 py-2" required>
                <option value="">-- Choose Customer --</option>
            </select>
        </div>
        <div class="text-center">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">Download Latest Invoice</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', async function() {
    // Fetch customers for dropdown
    const customerSelect = document.getElementById('customerSelect');
    try {
        const res = await fetch('/customerList', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        const data = await res.json();
        if (data.status === 'success' && Array.isArray(data.data)) {
            data.data.forEach(customer => {
                const opt = document.createElement('option');
                opt.value = customer.id;
                opt.textContent = customer.name;
                customerSelect.appendChild(opt);
            });
        }
    } catch (e) {
        alert('Failed to load customers.');
    }

    // Download latest invoice for selected customer
    document.getElementById('downloadForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const customerId = customerSelect.value;
        if (!customerId) {
            alert('Please select a customer.');
            return;
        }
        // Fetch the latest invoice for this customer
        try {
            const res = await fetch(`/api/latest-invoice?customer_id=${customerId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            const data = await res.json();
            if (data.status === 'success' && data.invoice && data.invoice.sale_id) {
                window.open(`/downloadInvoice?sale_id=${data.invoice.sale_id}`, '_blank');
            } else {
                alert('No invoice found for this customer.');
            }
        } catch (err) {
            alert('Failed to fetch latest invoice.');
        }
    });
});
</script>
@endsection
