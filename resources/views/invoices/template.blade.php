<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 32px; color: #222; background: #fff; }
        .header { text-align: center; margin-bottom: 32px; }
        .header h1 { font-size: 2rem; margin: 0 0 8px 0; }
        .meta { text-align: center; margin-bottom: 24px; font-size: 1rem; color: #555; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 24px; }
        .info-block { font-size: 1rem; }
        .info-block strong { display: block; margin-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        th, td { padding: 8px 6px; text-align: left; }
        th { border-bottom: 1px solid #eee; font-weight: 600; }
        tr:not(:last-child) td { border-bottom: 1px solid #f3f3f3; }
        .total-row td { font-weight: bold; border-top: 2px solid #222; }
        .right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Invoice</h1>
    </div>
    <div class="meta">
        <span>Invoice #: {{ $invoice['number'] }}</span> |
        <span>Date: {{ $invoice['date'] }}</span>
    </div>
    <div class="info-row">
        <div class="info-block">
            <strong>From</strong>
            {{ $company['name'] }}<br>
        </div>
        <div class="info-block">
            <strong>To</strong>
            {{ $customer['name'] }}<br>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th class="right">Qty</th>
                <th class="right">Unit Price</th>
                <th class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                <td class="right">{{ $item['quantity'] }}</td>
                <td class="right">{{ number_format($item['unit_price'], 2) }}</td>
                <td class="right">{{ number_format($item['total'], 2) }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3" class="right">Total</td>
                <td class="right">{{ number_format($sale['total'] ?? $invoice['total'] ?? 0, 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>