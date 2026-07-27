<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cart | Smart Tap</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Manrope', sans-serif;
            color: #f6f8ff;
            background: radial-gradient(circle at 100% 0%, rgba(255, 126, 76, 0.25), transparent 35%), #0b0f18;
        }

        .wrap {
            width: min(1000px, 92%);
            margin: 0 auto;
            padding: 26px 0 42px;
        }

        .head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .head a {
            color: #dde5fb;
            text-decoration: none;
            font-weight: 700;
        }

        h1 {
            margin: 0;
            font-size: clamp(1.7rem, 4vw, 2.4rem);
        }

        .status,
        .error {
            margin: 0 0 14px;
            border-radius: 10px;
            padding: 10px 12px;
        }

        .status {
            border: 1px solid rgba(112, 242, 167, 0.5);
            background: rgba(112, 242, 167, 0.13);
            color: #d8ffe8;
        }

        .error {
            border: 1px solid rgba(255, 121, 121, 0.5);
            background: rgba(255, 121, 121, 0.13);
            color: #ffd7d7;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 12px;
            overflow: hidden;
            background: #111827;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.09);
        }

        .right {
            text-align: right;
        }

        .total-row td {
            font-weight: 800;
            font-size: 1.1rem;
            border-bottom: 0;
        }

        .remove-btn,
        .checkout-btn {
            border: 0;
            border-radius: 999px;
            font-weight: 700;
            cursor: pointer;
        }

        .remove-btn {
            padding: 7px 11px;
            background: rgba(255, 255, 255, 0.13);
            color: #fff;
        }

        .checkout-area {
            display: flex;
            justify-content: flex-end;
            margin-top: 14px;
        }

        .checkout-btn {
            padding: 11px 16px;
            background: #edf2ff;
            color: #101624;
        }

        .empty {
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            padding: 14px;
            background: #111827;
        }
    </style>
</head>
<body>
    <main class="wrap">
        <header class="head">
            <h1>Your Guest Cart ({{ $cartCount }})</h1>
            <a href="{{ route('shop.index') }}">Back to Shop</a>
        </header>

        @if (session('status'))
            <p class="status">{{ session('status') }}</p>
        @endif

        @if ($errors->any())
            <p class="error">{{ $errors->first() }}</p>
        @endif

        @if (count($items) === 0)
            <p class="empty">Your cart is empty. Add sample products from the guest shop first.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th class="right">Price</th>
                        <th class="right">Qty</th>
                        <th class="right">Line Total</th>
                        <th class="right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td>
                                {{ $item['name'] }}
                                <div style="font-size:0.8rem;color:#c7d0e8;">Color: {{ $item['color'] }} | Size: {{ $item['size'] }}</div>
                            </td>
                            <td class="right">AUD ₱{{ number_format($item['price'], 2) }}</td>
                            <td class="right">{{ $item['quantity'] }}</td>
                            <td class="right">AUD ₱{{ number_format($item['line_total'], 2) }}</td>
                            <td class="right">
                                <form method="POST" action="{{ route('cart.remove', ['product' => $item['id']]) }}">
                                    @csrf
                                    <input type="hidden" name="color" value="{{ $item['color'] }}">
                                    <input type="hidden" name="size" value="{{ $item['size'] }}">
                                    <button type="submit" class="remove-btn">-1</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="3" class="right">Total</td>
                        <td class="right">AUD ₱{{ number_format($total, 2) }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <div class="checkout-area">
                <form method="POST" action="{{ route('cart.checkout') }}">
                    @csrf
                    <button type="submit" class="checkout-btn">Checkout</button>
                </form>
            </div>
        @endif
    </main>
</body>
</html>
