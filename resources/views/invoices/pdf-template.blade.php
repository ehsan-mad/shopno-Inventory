<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice['number'] }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background: white;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 20px;
        }
        
        .company-info h2 {
            font-size: 32px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 10px;
        }
        
        .company-details {
            color: #6b7280;
            font-size: 14px;
        }
        
        .company-details p {
            margin-bottom: 4px;
        }
        
        .invoice-title {
            text-align: right;
        }
        
        .invoice-title h1 {
            font-size: 36px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 20px;
        }
        
        .invoice-meta {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        
        .invoice-meta table {
            width: 100%;
        }
        
        .invoice-meta td {
            padding: 4px 8px;
            font-size: 14px;
        }
        
        .invoice-meta .label {
            color: #6b7280;
            font-weight: normal;
        }
        
        .invoice-meta .value {
            font-weight: 600;
            text-align: right;
        }
        
        .customer-section {
            margin-bottom: 40px;
        }
        
        .customer-section h3 {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 15px;
        }
        
        .customer-info {
            background: #eff6ff;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #dbeafe;
        }
        
        .customer-info h4 {
            font-weight: 600;
            color: #111827;
            margin-bottom: 10px;
        }
        
        .customer-details {
            color: #6b7280;
            font-size: 14px;
        }
        
        .customer-details p {
            margin-bottom: 4px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .items-table thead {
            background: #f9fafb;
        }
        
        .items-table th {
            padding: 15px 20px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .items-table th.text-center {
            text-align: center;
        }
        
        .items-table th.text-right {
            text-align: right;
        }
        
        .items-table td {
            padding: 15px 20px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 14px;
        }
        
        .items-table tr:last-child td {
            border-bottom: none;
        }
        
        .items-table .item-name {
            font-weight: 600;
            color: #111827;
        }
        
        .items-table .sku {
            color: #6b7280;
        }
        
        .items-table .text-center {
            text-align: center;
        }
        
        .items-table .text-right {
            text-align: right;
        }
        
        .items-table .amount {
            font-weight: 600;
            color: #111827;
        }
        
        .totals-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 40px;
        }
        
        .totals-box {
            width: 300px;
            background: #f9fafb;
            padding: 30px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        
        .totals-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 14px;
        }
        
        .totals-row .label {
            color: #6b7280;
        }
        
        .totals-row .amount {
            font-weight: 600;
        }
        
        .totals-row.discount .amount {
            color: #dc2626;
        }
        
        .total-final {
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
            margin-top: 15px;
        }
        
        .total-final .label {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
        }
        
        .total-final .amount {
            font-size: 20px;
            font-weight: bold;
            color: #2563eb;
        }
        
        .notes-section {
            margin-bottom: 40px;
        }
        
        .notes-section h3 {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 15px;
        }
        
        .notes-box {
            background: #fefce8;
            border: 1px solid #fde047;
            border-radius: 8px;
            padding: 20px;
        }
        
        .notes-box p {
            color: #374151;
            font-size: 14px;
        }
        
        .footer {
            text-align: center;
            font-size: 14px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 30px;
        }
        
        .footer p {
            margin-bottom: 8px;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Invoice Header -->
        <div class="invoice-header">
            <!-- Company Info -->
            <div class="company-info">
                <h2>Shopno</h2>
                <div class="company-details">
                    <p>Bangladesh</p>
                    <p>Dhaka</p>
                    <p>3551343213</p>
                    <p>{{ $company['email'] ?? 'info@shopno.com' }}</p>
                </div>
            </div>

            <!-- Invoice Info -->
            <div class="invoice-title">
                <h1>INVOICE</h1>
                <div class="invoice-meta">
                    <table>
                        <tr>
                            <td class="label">Invoice #:</td>
                            <td class="value">{{ $invoice['number'] }}</td>
                        </tr>
                        <tr>
                            <td class="label">Date:</td>
                            <td class="value">{{ $invoice['date'] }}</td>
                        </tr>
                        <tr>
                            <td class="label">Due Date:</td>
                            <td class="value">{{ $invoice['due_date'] }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="customer-section">
            <h3>Bill To:</h3>
            <div class="customer-info">
                <h4>{{ $customer['name'] }}</h4>
                <div class="customer-details">
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
        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>SKU</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>
                        <div class="item-name">{{ $item['name'] }}</div>
                    </td>
                    <td>
                        <div class="sku">{{ $item['sku'] }}</div>
                    </td>
                    <td class="text-center">
                        {{ $item['quantity'] }}
                    </td>
                    <td class="text-right">
                        ${{ number_format($item['unit_price'], 2) }}
                    </td>
                    <td class="text-right amount">
                        ${{ number_format($item['total'], 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-section">
            <div class="totals-box">
                <div class="totals-row">
                    <span class="label">Subtotal:</span>
                    <span class="amount">${{ number_format($sale['subtotal'], 2) }}</span>
                </div>
                
                @if($sale['discount'] > 0)
                <div class="totals-row discount">
                    <span class="label">Discount:</span>
                    <span class="amount">-${{ number_format($sale['discount'], 2) }}</span>
                </div>
                @endif
                
                @if($sale['tax'] > 0)
                <div class="totals-row">
                    <span class="label">Tax:</span>
                    <span class="amount">${{ number_format($sale['tax'], 2) }}</span>
                </div>
                @endif
                
                <div class="totals-row total-final">
                    <span class="label">Total:</span>
                    <span class="amount">${{ number_format($sale['total'], 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Notes -->
        @if($sale['notes'])
        <div class="notes-section">
            <h3>Notes:</h3>
            <div class="notes-box">
                <p>{{ $sale['notes'] }}</p>
            </div>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for your business!</p>
            <p>For any questions regarding this invoice, please contact us at {{ $company['email'] ?? 'info@shopno.com' }}</p>
        </div>
    </div>
</body>
</html>
