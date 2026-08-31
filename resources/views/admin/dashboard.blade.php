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

        .alert {
            margin-bottom: 14px;
            padding: 10px 12px;
            border: 1px solid rgba(255, 132, 71, 0.5);
            border-radius: 8px;
            color: #ffe1d2;
            background: rgba(255, 132, 71, 0.16);
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            min-width: 190px;
        }

        .actions form { margin: 0; }
        .action {
            display: inline-block;
            padding: 5px 8px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 6px;
            color: #ffe1d2;
            background: transparent;
            font: inherit;
            font-size: 0.78rem;
            font-weight: 700;
            cursor: pointer;
        }
        .action.primary {
            background: var(--accent);
            border-color: var(--accent);
            color: #1c1010;
        }
        .action.danger { color: #ffb3b3; border-color: rgba(255, 120, 120, 0.45); }
        .action:disabled {
            opacity: 0.55;
            cursor: not-allowed;
        }

        .bulk-tools {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 12px;
        }

        .bulk-tools .left {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--muted);
            font-size: 0.8rem;
        }

        .bulk-tools input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--accent);
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

        .user-search {
            display: flex;
            gap: 8px;
            margin-bottom: 12px;
        }

        .user-search input {
            min-width: 0;
            flex: 1;
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 9px 10px;
            background: #0e1623;
            color: var(--text);
            font: inherit;
        }

        .user-search button,
        .user-search a {
            border: 0;
            border-radius: 8px;
            padding: 9px 12px;
            background: var(--accent);
            color: #1c1010;
            font: inherit;
            font-weight: 800;
            text-decoration: none;
            cursor: pointer;
        }

        .user-search a {
            background: transparent;
            border: 1px solid var(--line);
            color: var(--muted);
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
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
                <a href="{{ route('admin.cards.index') }}">Card Studio</a>
                <span>|</span>
                <a href="{{ route('admin.products.index') }}">Products</a>
                <span>|</span>
                <a href="{{ route('home') }}">Home</a>
                <span>|</span>
                <a href="{{ route('shop.index') }}">Shop</a>
            </div>
        </header>

        @if (session('success'))
            <div class="alert">{{ session('success') }}</div>
        @endif

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
                <form class="user-search" method="GET" action="{{ route('admin.dashboard') }}">
                    <label class="sr-only" for="user_search">Search users</label>
                    <input id="user_search" name="user_search" type="search" value="{{ $userSearch }}" placeholder="Search by name, email, or card ID">
                    <button type="submit">Search</button>
                    @if ($userSearch !== '')
                        <a href="{{ route('admin.dashboard') }}">Clear</a>
                    @endif
                </form>

                <form method="POST" action="{{ route('admin.users.qr.download') }}" id="bulk-qr-form">
                    @csrf
                    <div class="bulk-tools">
                        <div class="left">
                            <input id="select-all-users" type="checkbox" aria-label="Select all users">
                            <label for="select-all-users">Select all</label>
                        </div>
                        <button class="action primary" id="download-selected-qr" type="submit" disabled>Download QR</button>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th style="width:32px;">
                                    <input id="select-all-table-users" type="checkbox" aria-label="Select all users in table" class="table-select-toggle">
                                </th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Card ID</th>
                                <th>Profile</th>
                                <th>Actions</th>
                                <th>Registered</th>
                            </tr>
                        </thead>
                        <tbody id="users-table-body">
                            @forelse ($users as $user)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="user-qr-checkbox" aria-label="Select {{ $user->name }}">
                                    </td>
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
                                    <td>
                                        <div class="actions">
                                            <a class="action" href="{{ route('admin.users.profile.edit', $user) }}">Edit builder</a>
                                            <form method="POST" action="{{ route('admin.users.profile-builder.toggle', $user) }}">
                                                @csrf
                                                <button class="action" type="submit">{{ $user->profile?->profile_builder_active === false ? 'Activate' : 'Deactivate' }}</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.users.duplicate', $user) }}">
                                                @csrf
                                                <button class="action" type="submit">Duplicate</button>
                                            </form>
                                            @if (auth()->id() !== $user->id)
                                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user and their profile? This cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="action danger" type="submit">Delete</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $user->created_at?->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="muted">No users available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </form>
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
                            <th>QR</th>
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
                                <td>
                                    @if ($profile->user)
                                        <select class="profile-qr-select" data-open-url="{{ route('profile.public', ['cardId' => $profile->user->card_id]) }}" data-qr-download-url="{{ route('admin.users.profile.qr.download', $profile->user) }}">
                                            <option value="">Choose action</option>
                                            <option value="download">Download QR</option>
                                            <option value="open">Open profile</option>
                                        </select>
                                    @else
                                        <span class="muted">n/a</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="muted">No profiles available.</td>
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
            <div style="display:flex; align-items:center; justify-content:space-between;">
                <h2>Product Catalog</h2>
                <a href="{{ route('admin.products.create') }}" style="color:#ffd6b5; font-weight:700; text-decoration:none;">+ Add Product</a>
            </div>
            <div class="products">
                @forelse ($products as $product)
                    <article class="product">
                        @if ($product['image'])
                            <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}">
                        @endif
                        <div class="meta">
                            <h3>{{ $product['name'] }}</h3>
                            <p>{{ $product['category'] }} | PHP {{ number_format($product['price'], 2) }}</p>
                            <p>{{ implode(', ', $product['colors']) }}</p>
                            <p>{{ implode(', ', $product['sizes']) }}</p>
                            <p><a href="{{ route('admin.products.edit', $product['id']) }}" style="color:#ffd6b5;">Edit</a></p>
                        </div>
                    </article>
                @empty
                    <p class="muted">No products yet. <a href="{{ route('admin.products.create') }}" style="color:#ffd6b5;">Add your first product</a>.</p>
                @endforelse
            </div>
        </section>
    </main>

    <script>
        const userSearchInput = document.getElementById('user_search');
        const userTableRows = Array.from(document.querySelectorAll('#users-table-body tr'));
        const clearSearchLink = document.querySelector('.user-search a');
        const bulkQrForm = document.getElementById('bulk-qr-form');
        const selectAllUsers = document.getElementById('select-all-users');
        const tableSelectToggle = document.getElementById('select-all-table-users');
        const downloadSelectedQr = document.getElementById('download-selected-qr');
        const userQrCheckboxes = Array.from(document.querySelectorAll('.user-qr-checkbox'));

        function applyUserSearchFilter() {
            const query = (userSearchInput?.value || '').trim().toLowerCase();

            userTableRows.forEach((row) => {
                const rowText = (row.textContent || '').toLowerCase();
                row.style.display = (!query || rowText.includes(query)) ? '' : 'none';
            });
        }

        if (userSearchInput) {
            userSearchInput.addEventListener('input', applyUserSearchFilter);
        }

        if (clearSearchLink) {
            clearSearchLink.addEventListener('click', function () {
                if (userSearchInput) {
                    userSearchInput.value = '';
                }
                applyUserSearchFilter();
            });
        }

        function syncBulkQrState() {
            const checkedBoxes = userQrCheckboxes.filter((checkbox) => checkbox.checked);
            const allChecked = userQrCheckboxes.length > 0 && checkedBoxes.length === userQrCheckboxes.length;

            if (selectAllUsers) {
                selectAllUsers.checked = allChecked;
            }

            if (tableSelectToggle) {
                tableSelectToggle.checked = allChecked;
            }

            if (downloadSelectedQr) {
                downloadSelectedQr.disabled = checkedBoxes.length === 0;
            }
        }

        if (selectAllUsers) {
            selectAllUsers.addEventListener('change', function () {
                userQrCheckboxes.forEach((checkbox) => {
                    checkbox.checked = this.checked;
                });
                syncBulkQrState();
            });
        }

        if (tableSelectToggle) {
            tableSelectToggle.addEventListener('change', function () {
                userQrCheckboxes.forEach((checkbox) => {
                    checkbox.checked = this.checked;
                });
                syncBulkQrState();
            });
        }

        userQrCheckboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', syncBulkQrState);
        });

        if (bulkQrForm) {
            bulkQrForm.addEventListener('submit', function (event) {
                const checkedBoxes = userQrCheckboxes.filter((checkbox) => checkbox.checked);
                if (checkedBoxes.length === 0) {
                    event.preventDefault();
                    return false;
                }
            });
        }

        syncBulkQrState();
    </script>
</body>
</html>
