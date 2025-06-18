<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice['number'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            font-size: 14px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 32px;
        }
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .company-info, .customer-info {
            width: 48%;
        }
        .company-info h3, .customer-info h3 {
            color: #007bff;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .invoice-details {
            text-align: right;
            margin-bottom: 30px;
        }
        .invoice-details table {
            margin-left: auto;
            border-collapse: collapse;
        }
        .invoice-details td {
            padding: 5px 10px;
            border: 1px solid #ddd;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background-color: #007bff;
            color: white;
            padding: 12px;
            text-align: left;
        }
        .items-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #ddd;
        }
        .items-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .summary {
            width: 300px;
            margin-left: auto;
            border-collapse: collapse;
        }
        .summary td {
            padding: 8px 12px;
            border-bottom: 1px solid #ddd;
        }
        .summary .total-row {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            font-size: 16px;
        }
        .notes {
            margin-top: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>INVOICE</h1>
        <h2>{{ $company['name'] }}</h2>
    </div>

    <!-- Company and Customer Info -->
    <div class="invoice-info">
        <div class="company-info">
            <h3>From:</h3>
            <p><strong>{{ $company['name'] }}</strong><br>
            {{ $company['address'] }}<br>
            {{ $company['city'] }}<br>
            Phone: {{ $company['phone'] }}<br>
            Email: {{ $company['email'] }}</p>
        </div>
        <div class="customer-info">
            <h3>To:</h3>
            <p><strong>{{ $customer['name'] }}</strong><br>
            {{ $customer['address'] }}<br>
            {{ $customer['city'] }}<br>
            Phone: {{ $customer['phone'] }}<br>
            Email: {{ $customer['email'] }}</p>
        </div>
    </div>

    <!-- Invoice Details -->
    <div class="invoice-details">
        <table>
            <tr>
                <td><strong>Invoice #:</strong></td>
                <td>{{ $invoice['number'] }}</td>
            </tr>
            <tr>
                <td><strong>Invoice Date:</strong></td>
                <td>{{ $invoice['date'] }}</td>
            </tr>
            <tr>
                <td><strong>Due Date:</strong></td>
                <td>{{ $invoice['due_date'] }}</td>
            </tr>
            <tr>
                <td><strong>Sale #:</strong></td>
                <td>{{ $sale['number'] }}</td>
            </tr>
            <tr>
                <td><strong>Sale Date:</strong></td>
                <td>{{ $sale['date'] }}</td>
            </tr>
        </table>
    </div>

    <!-- Items -->
    <table class="items-table">
        <thead>
            <tr>
                <th>Item Description</th>
                <th>SKU</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                <td>{{ $item['sku'] }}</td>
                <td class="text-center">{{ $item['quantity'] }}</td>
                <td class="text-right">${{ number_format($item['unit_price'], 2) }}</td>
                <td class="text-right">${{ number_format($item['total'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Summary -->
    <table class="summary">
        <tr>
            <td><strong>Subtotal:</strong></td>
            <td class="text-right">${{ number_format($sale['subtotal'], 2) }}</td>
        </tr>
        @if($sale['discount'] > 0)
        <tr>
            <td><strong>Discount:</strong></td>
            <td class="text-right">-${{ number_format($sale['discount'], 2) }}</td>
        </tr>
        @endif
        @if($sale['tax'] > 0)
        <tr>
            <td><strong>Tax:</strong></td>
            <td class="text-right">${{ number_format($sale['tax'], 2) }}</td>
        </tr>
        @endif
        <tr class="total-row">
            <td><strong>TOTAL:</strong></td>
            <td class="text-right"><strong>${{ number_format($sale['total'], 2) }}</strong></td>
        </tr>
    </table>

    <!-- Notes -->
    @if($sale['notes'])
    <div class="notes">
        <h4>Notes:</h4>
        <p>{{ $sale['notes'] }}</p>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p><strong>Thank you for your business!</strong></p>
        <p>Generated on {{ date('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>