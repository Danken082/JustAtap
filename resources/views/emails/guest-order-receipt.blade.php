<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Receipt</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1a2333; line-height: 1.6;">
    <h2>Thanks for your order, {{ $customerName }}!</h2>
    <p>Your receipt for the order placed with JustAtap is below.</p>

    <table cellpadding="8" cellspacing="0" border="1" style="border-collapse: collapse; width: 100%; max-width: 720px;">
        <thead style="background: #eef3ff;">
            <tr>
                <th align="left">Product</th>
                <th align="right">Price</th>
                <th align="right">Qty</th>
                <th align="right">Line Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $item['name'] }}<br><small>{{ $item['color'] }} / {{ $item['size'] }}</small></td>
                    <td align="right">₱ {{ number_format($item['price'], 2) }}</td>
                    <td align="right">{{ $item['quantity'] }}</td>
                    <td align="right">₱ {{ number_format($item['line_total'], 2) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3" align="right"><strong>Total</strong></td>
                <td align="right"><strong>₱ {{ number_format($total, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <p><strong>Order by:</strong> {{ $customerName }} ({{ $customerEmail }})</p>
    <p>We will contact you soon with the order details.</p>
</body>
</html>
