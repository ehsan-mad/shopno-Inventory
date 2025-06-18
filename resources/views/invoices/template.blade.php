<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; color: #333; }
        .invoice-header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #007bff; padding-bottom: 20px; }
        .invoice-header h1 { color: #007bff; margin: 0; font-size: 32px; }
        .invoice-details { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .company-info, .customer-info { width: 48%; }
        .company-info h3, .customer-info h3 { color: #007bff; margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 5px; }
        .invoice-items { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .invoice-items th, .invoice-items td { border: 1px solid #ddd; padding: 8px; }
        .invoice-items th { background-color: #007bff; color: white; }
        .invoice-total { text-align: right; margin-top: 20px; }
        .invoice-total p { margin: 5px 0; }
        .invoice-total .total { font-weight: bold; font-size: 18px; color: #007bff; }
    </style>
</head>
<body>
    <div class="invoice-header">
        <h1>Invoice</h1>
        <p>Invoice Number: {{ $invoice['number'] }}</p>
        <p>Date: {{ $invoice['date'] }}</p>
        <p>Due Date: {{ $invoice['due_date'] }}</p>
    </div>
    <div class="invoice-details">
        <div class="company-info">
            <h3>Company Details</h3>
            <p>Name: {{ $company['name'] }}</p>
            <p>Address: {{ $company['address'] }}</p>
            <p>City: {{ $company['city'] }}</p>
            <p>Phone: {{ $company['phone'] }}</p>
            <p>Email: {{ $company['email'] }}</p>
        </div>
        <div class="customer-info">
            <h3>Customer Details</h3>
            <p>Name: {{ $customer['name'] }}</p>
            <p>Email: {{ $customer['email'] }}</p>
            <p>Phone: {{ $customer['phone'] }}</p>
            <p>Address: {{ $customer['address'] }}</p>
            <p>City: {{ $customer['city'] }}</p>
        </div>
    </div>
    <table class="invoice-items">
        <thead>
            <tr>
                <th>Item</th>
                <th>SKU</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                <td>{{ $item['sku'] }}</td>
                <td>{{ $item['quantity'] }}</td>
                <td>{{ $item['unit_price'] }}</td>
                <td>{{ $item['total'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="invoice-total">
        <p>Subtotal: {{ $sale['subtotal'] }}</p>
        <p>Discount: {{ $sale['discount'] }}</p>
        <p>Tax: {{ $sale['tax'] }}</p>
        <p class="total">Total: {{ $sale['total'] }}</p>
    </div>
</body>
</html>