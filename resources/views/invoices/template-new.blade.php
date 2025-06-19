<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice['number'] }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-white">
    <div class="max-w-4xl mx-auto p-8">
        <!-- Header Actions (No Print) -->
        <div class="no-print mb-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">Invoice Preview</h1>
            <div class="space-x-3">
                <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print
                </button>
                <a href="/downloadInvoice?sale_id={{ $sale['number'] }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                    <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download PDF
                </a>
            </div>
        </div>

        <!-- Invoice Content -->
        <div class="bg-white border border-gray-200 rounded-lg p-8">
            <!-- Header -->
            <div class="flex justify-between items-start mb-8">
                <!-- Company Info -->
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Shopno</h2>
                    <div class="text-gray-600 space-y-1">
                        <p>Bangladesh</p>
                        <p>Dhaka</p>
                        <p>3551343213</p>
                        <p>{{ $company['email'] ?? 'info@shopno.com' }}</p>
                    </div>
                </div>

                <!-- Invoice Info -->
                <div class="text-right">
                    <h1 class="text-4xl font-bold text-blue-600 mb-4">INVOICE</h1>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div class="text-gray-600">Invoice #:</div>
                            <div class="font-semibold">{{ $invoice['number'] }}</div>
                            
                            <div class="text-gray-600">Date:</div>
                            <div class="font-semibold">{{ $invoice['date'] }}</div>
                            
                            <div class="text-gray-600">Due Date:</div>
                            <div class="font-semibold">{{ $invoice['due_date'] }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Bill To:</h3>
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-900">{{ $customer['name'] }}</h4>
                    <div class="text-gray-600 mt-2 space-y-1">
                        <p>{{ $customer['email'] }}</p>
                        @if($customer['phone'] && $customer['phone'] !== 'N/A')
                            <p>{{ $customer['phone'] }}</p>
                        @endif
                        @if($customer['address'] && $customer['address'] !== 'N/A')
                            <p>{{ $customer['address'] }}</p>
                        @endif
                        @if($customer['city'] && $customer['city'] !== 'N/A')
                            <p>{{ $customer['city'] }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="mb-8">
                <div class="overflow-hidden rounded-lg border border-gray-200">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($items as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ $item['name'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item['sku'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                    {{ $item['quantity'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                                    ${{ number_format($item['unit_price'], 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                                    ${{ number_format($item['total'], 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Totals -->
            <div class="flex justify-end mb-8">
                <div class="w-72">
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="font-medium">${{ number_format($sale['subtotal'], 2) }}</span>
                            </div>
                            
                            @if($sale['discount'] > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Discount:</span>
                                <span class="font-medium text-red-600">-${{ number_format($sale['discount'], 2) }}</span>
                            </div>
                            @endif
                            
                            @if($sale['tax'] > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tax:</span>
                                <span class="font-medium">${{ number_format($sale['tax'], 2) }}</span>
                            </div>
                            @endif
                            
                            <div class="border-t border-gray-200 pt-3">
                                <div class="flex justify-between">
                                    <span class="text-lg font-semibold text-gray-900">Total:</span>
                                    <span class="text-xl font-bold text-blue-600">${{ number_format($sale['total'], 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($sale['notes'])
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Notes:</h3>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-gray-700">{{ $sale['notes'] }}</p>
                </div>
            </div>
            @endif

            <!-- Footer -->
            <div class="text-center text-sm text-gray-500 border-t border-gray-200 pt-6">
                <p>Thank you for your business!</p>
                <p class="mt-1">For any questions regarding this invoice, please contact us at {{ $company['email'] ?? 'info@shopno.com' }}</p>
            </div>
        </div>
    </div>
</body>
</html>
