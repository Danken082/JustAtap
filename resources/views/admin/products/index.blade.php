<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Products | Just A Tap</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0b1017;
            --panel: #131b28;
            --line: rgba(255, 255, 255, 0.12);
            --text: #f3f7ff;
            --muted: #bfcde8;
            --accent: #ff8447;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Manrope', sans-serif;
            color: var(--text);
            background: radial-gradient(circle at 0% 0%, rgba(74, 104, 213, 0.33), transparent 35%), var(--bg);
        }

        .wrap {
            width: min(1200px, 95%);
            margin: 0 auto;
            padding: 22px 0 40px;
        }

        .top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
        }

        .top a {
            color: #dde5f8;
            text-decoration: none;
            font-weight: 700;
        }

        h1 { margin: 0; font-size: clamp(1.5rem, 3vw, 2.1rem); }

        .status {
            margin: 0 0 14px;
            border-radius: 10px;
            border: 1px solid rgba(112, 242, 167, 0.5);
            background: rgba(112, 242, 167, 0.13);
            color: #d8ffe8;
            padding: 10px 12px;
        }

        .add-btn {
            display: inline-flex;
            border: 0;
            border-radius: 999px;
            padding: 10px 16px;
            background: var(--accent);
            color: #1b1206;
            font-weight: 800;
            text-decoration: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid var(--line);
            border-radius: 12px;
            overflow: hidden;
            background: var(--panel);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid var(--line);
            vertical-align: middle;
        }

        img.thumb {
            width: 56px;
            height: 56px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid var(--line);
        }

        .muted { color: var(--muted); }

        .pill {
            display: inline-flex;
            border: 1px solid rgba(125, 196, 182, 0.45);
            color: #d7fff5;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 3px 8px;
            margin: 2px;
        }

        .actions a, .actions button {
            border: 0;
            border-radius: 999px;
            padding: 7px 12px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.85rem;
            margin-right: 6px;
        }

        .edit-btn {
            background: #edf2ff;
            color: #101624;
        }

        .delete-btn {
            background: rgba(255, 121, 121, 0.18);
            border: 1px solid rgba(255, 121, 121, 0.5);
            color: #ffd7d7;
        }
    </style>
</head>
<body>
    <main class="wrap">
        <header class="top">
            <h1>Manage Products</h1>
            <div>
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <span style="color:#5b6577;">|</span>
                <a href="{{ route('shop.index') }}">Shop</a>
            </div>
        </header>

        @if (session('status'))
            <p class="status">{{ session('status') }}</p>
        @endif

        <p style="margin-bottom:14px;">
            <a class="add-btn" href="{{ route('admin.products.create') }}">+ Add Product</a>
        </p>

        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Colors</th>
                    <th>Sizes</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>
                            @if ($product->mainImageUrl())
                                <img class="thumb" src="{{ $product->mainImageUrl() }}" alt="{{ $product->name }}">
                            @else
                                <span class="muted">No image</span>
                            @endif
                        </td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category ?: '—' }}</td>
                        <td>PHP {{ number_format($product->price, 2) }}</td>
                        <td>
                            @forelse ($product->colors as $color)
                                <span class="pill">{{ $color->name }}</span>
                            @empty
                                <span class="muted">—</span>
                            @endforelse
                        </td>
                        <td>
                            @forelse ($product->sizes as $size)
                                <span class="pill">{{ $size->name }}</span>
                            @empty
                                <span class="muted">—</span>
                            @endforelse
                        </td>
                        <td>{{ $product->is_active ? 'Active' : 'Hidden' }}</td>
                        <td class="actions">
                            <a class="edit-btn" href="{{ route('admin.products.edit', $product) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" style="display:inline;" onsubmit="return confirm('Delete this product? This cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button class="delete-btn" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="muted">No products yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </main>
</body>
</html>
