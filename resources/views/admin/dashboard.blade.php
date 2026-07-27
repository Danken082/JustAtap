<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard | Just A Tap</title>
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
            width: min(1300px, 95%);
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

        h1 {
            margin: 0;
            font-size: clamp(1.5rem, 3vw, 2.1rem);
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 10px;
            margin-bottom: 14px;
        }

        .stat {
            border: 1px solid var(--line);
            background: var(--panel);
            border-radius: 12px;
            padding: 12px;
        }

        .stat .label {
            font-size: 0.76rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .stat .value {
            margin-top: 6px;
            font-size: 1.5rem;
            font-weight: 800;
        }

        .grid {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 12px;
        }

        .panel {
            border: 1px solid var(--line);
            background: var(--panel);
            border-radius: 12px;
            padding: 14px;
            overflow: auto;
        }

        h2 {
            margin: 0 0 10px;
            font-size: 1.08rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.88rem;
        }

        th,
        td {
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            color: #d4e0fb;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .muted {
            color: var(--muted);
            font-size: 0.82rem;
        }

        .pill {
            display: inline-flex;
            border: 1px solid rgba(255, 132, 71, 0.5);
            border-radius: 999px;
            padding: 3px 8px;
            font-size: 0.76rem;
            color: #ffd8c8;
        }

        .products {
            margin-top: 12px;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
        }

        .product {
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            overflow: hidden;
            background: #111827;
        }

        .product img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            display: block;
        }

        .product .meta {
            padding: 9px;
        }

        .product h3 {
            margin: 0;
            font-size: 0.93rem;
        }

        .product p {
            margin: 6px 0 0;
            font-size: 0.79rem;
            color: var(--muted);
        }

        @media (max-width: 980px) {
            .stats,
            .grid,
            .products {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <main class="wrap">
        <header class="top">
            <h1>Admin Dashboard</h1>
            <div>
                <a href="{{ route('home') }}">Home</a>
                <span>|</span>
                <a href="{{ route('shop.index') }}">Shop</a>
            </div>
        </header>

        <section class="stats" aria-label="Admin summary">
            <article class="stat">
                <p class="label">Total Users</p>
                <p class="value">{{ $users->count() }}</p>
            </article>
            <article class="stat">
                <p class="label">Profiles</p>
                <p class="value">{{ $profiles->count() }}</p>
            </article>
            <article class="stat">
                <p class="label">Profile Links</p>
                <p class="value">{{ $latestLinks->count() }}</p>
            </article>
            <article class="stat">
                <p class="label">Catalog Products</p>
                <p class="value">{{ count($products) }}</p>
            </article>
        </section>

        <section class="grid">
            <article class="panel">
                <h2>Users</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Card ID</th>
                            <th>Profile</th>
                            <th>Registered</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->card_id }}</td>
                                <td>
                                    @if ($user->profile)
                                        <a href="{{ route('profile.public', ['cardId' => $user->card_id]) }}" target="_blank" rel="noopener">Public Card</a>
                                    @else
                                        <span class="muted">No profile yet</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at?->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="muted">No users available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </article>

            <article class="panel">
                <h2>Profiles</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Display Name</th>
                            <th>Owner</th>
                            <th>Style</th>
                            <th>Links</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($profiles as $profile)
                            <tr>
                                <td>{{ $profile->display_name ?: 'Unnamed' }}</td>
                                <td>{{ $profile->user?->email ?? 'n/a' }}</td>
                                <td>
                                    <span class="pill">{{ $profile->card_style }}</span>
                                    <span class="pill">{{ $profile->background_pattern }}</span>
                                </td>
                                <td>{{ $profile->links_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="muted">No profiles available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </article>
        </section>

        <section class="panel" style="margin-top:12px;">
            <h2>Latest Profile Links</h2>
            <table>
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Label</th>
                        <th>Value</th>
                        <th>Owner</th>
                        <th>Added</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($latestLinks as $link)
                        <tr>
                            <td>{{ $link->type }}</td>
                            <td>{{ $link->label }}</td>
                            <td style="max-width:320px;word-break:break-all;">{{ $link->value }}</td>
                            <td>{{ $link->profile?->user?->email ?? 'n/a' }}</td>
                            <td>{{ $link->created_at?->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="muted">No profile links yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        <section class="panel" style="margin-top:12px;">
            <h2>Product Catalog</h2>
            <div class="products">
                @foreach ($products as $product)
                    <article class="product">
                        <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}">
                        <div class="meta">
                            <h3>{{ $product['name'] }}</h3>
                            <p>{{ $product['category'] }} | PHP {{ number_format($product['price'], 2) }}</p>
                            <p>{{ implode(', ', $product['colors']) }}</p>
                            <p>{{ implode(', ', $product['sizes']) }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    </main>
</body>
</html>
