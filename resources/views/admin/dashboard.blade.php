<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard | Just A Tap</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Manrope', 'sans-serif'],
                        display: ['Space Grotesk', 'sans-serif'],
                    },
                    colors: {
                        surface: '#0a0a0b',
                        card: '#111113',
                        border: 'rgba(255,255,255,0.06)',
                        'border-light': 'rgba(255,255,255,0.10)',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1)',
                        'glow-pulse': 'glowPulse 3s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        glowPulse: {
                            '0%, 100%': { opacity: '0.5' },
                            '50%': { opacity: '1' },
                        },
                    },
                },
            },
        };
    </script>

    <style>
        /* ── Reset & base ── */
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            background: #050506;
            font-family: 'Manrope', sans-serif;
            color: #f0f0f0;
            position: relative;
            -webkit-font-smoothing: antialiased;
        }

        /* Ambient grid */
        /* body::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.015) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.015) 1px, transparent 1px);
            background-size: 52px 52px;
            -webkit-mask-image: radial-gradient(1300px 800px at 50% 0%, #000 0%, transparent 75%);
            mask-image: radial-gradient(1300px 800px at 50% 0%, #000 0%, transparent 75%);
        } */

        body::after {
            content: '';
            position: fixed;
            top: -30%;
            left: 50%;
            transform: translateX(-50%);
            width: 1000px;
            height: 1000px;
            background: radial-gradient(circle at center, rgba(180, 180, 200, 0.04) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
            border-radius: 50%;
        }

        ::selection {
            background: rgba(255, 255, 255, 0.25);
            color: #000;
        }

        /* ── Glass utilities ── */
        .glass {
            background: rgba(17, 17, 19, 0.65);
            backdrop-filter: blur(20px) saturate(1.3);
            -webkit-backdrop-filter: blur(20px) saturate(1.3);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .glass-light {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(12px) saturate(1.1);
            -webkit-backdrop-filter: blur(12px) saturate(1.1);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .glow-ring {
            box-shadow: 0 0 80px -30px rgba(160, 160, 180, 0.10);
        }
        .accent-border {
            position: relative;
        }
        .accent-border::before {
            content: '';
            position: absolute;
            inset: -1px;
            border-radius: inherit;
            padding: 1px;
            background: linear-gradient(145deg, rgba(255,255,255,0.10), rgba(255,255,255,0.01), rgba(255,255,255,0.06));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }

        /* ── Mobile menu ── */
        #mobileMenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.3s ease, visibility 0.3s ease;
            opacity: 0;
            visibility: hidden;
        }
        #mobileMenu.open {
            max-height: 520px;
            opacity: 1;
            visibility: visible;
        }

        /* ── Inputs ── */
        .input-dark {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 10px;
            padding: 0.6rem 1rem 0.6rem 2.2rem;
            color: #f0f0f0;
            font-size: 0.85rem;
            width: 100%;
            transition: border-color 0.25s ease, box-shadow 0.25s ease;
        }
        .input-dark:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.03);
        }
        .input-dark::placeholder {
            color: rgba(255, 255, 255, 0.2);
        }

        /* ── Buttons ── */
        .btn-primary {
            background: #ffffff;
            color: #0a0a0b;
            font-weight: 700;
            padding: 0.6rem 1.4rem;
            border-radius: 999px;
            border: none;
            transition: background 0.2s ease, transform 0.1s ease;
            cursor: pointer;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .btn-primary:hover {
            background: #e8e8e8;
        }
        .btn-primary:active {
            transform: scale(0.96);
        }

        .btn-outline {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.10);
            color: rgba(255, 255, 255, 0.55);
            font-weight: 600;
            padding: 0.3rem 0.8rem;
            border-radius: 999px;
            transition: all 0.2s ease;
            cursor: pointer;
            font-size: 0.7rem;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }
        .btn-outline:hover {
            border-color: rgba(255, 255, 255, 0.25);
            color: #fff;
            background: rgba(255, 255, 255, 0.03);
        }

        .btn-accent {
            background: #ffffff;
            border: none;
            color: #0a0a0b;
            font-weight: 700;
            padding: 0.6rem 1.4rem;
            border-radius: 999px;
            transition: background 0.2s ease, transform 0.1s ease;
            cursor: pointer;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .btn-accent:hover {
            background: #e8e8e8;
        }
        .btn-accent:active {
            transform: scale(0.96);
        }
        .btn-accent:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            transform: none;
        }

        .btn-danger {
            background: transparent;
            border: 1px solid rgba(255, 100, 100, 0.2);
            color: rgba(255, 150, 150, 0.7);
            font-weight: 600;
            padding: 0.2rem 0.6rem;
            border-radius: 999px;
            transition: all 0.2s ease;
            cursor: pointer;
            font-size: 0.65rem;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }
        .btn-danger:hover {
            background: rgba(255, 70, 70, 0.10);
            border-color: rgba(255, 70, 70, 0.4);
            color: #ffb3b3;
        }

        /* ── Pills ── */
        .pill {
            display: inline-block;
            padding: 0.1rem 0.6rem;
            border-radius: 999px;
            font-size: 0.6rem;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.04);
            color: rgba(255, 255, 255, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.06);
            margin: 0.1rem 0.15rem;
            white-space: nowrap;
        }

        /* ── Tables ── */
        .table-wrap {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.08) transparent;
        }
        .table-wrap::-webkit-scrollbar {
            height: 4px;
        }
        .table-wrap::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.12);
            border-radius: 999px;
        }

        .table-wrap table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }

        .table-wrap th {
            font-size: 0.6rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: rgba(255, 255, 255, 0.3);
            font-weight: 700;
            padding: 0.6rem 0.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            text-align: left;
            white-space: nowrap;
        }

        .table-wrap td {
            padding: 0.6rem 0.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            vertical-align: middle;
        }

        .table-wrap tbody tr {
            transition: background 0.15s ease;
        }
        .table-wrap tbody tr:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        /* ── Checkbox ── */
        .checkbox-dark {
            width: 16px;
            height: 16px;
            accent-color: #ffffff;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 4px;
            cursor: pointer;
            flex-shrink: 0;
        }

        /* ── Profile card ── */
        .profile-card {
            background: rgba(255, 255, 255, 0.015);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 0.8rem 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: border-color 0.2s ease;
            flex-wrap: wrap;
        }
        .profile-card:hover {
            border-color: rgba(255, 255, 255, 0.10);
        }
        .profile-card .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.5);
            flex-shrink: 0;
            border: 1px solid rgba(255, 255, 255, 0.06);
        }
        .profile-card .info {
            flex: 1;
            min-width: 120px;
        }
        .profile-card .info .name {
            font-weight: 600;
            color: #fff;
            font-size: 0.9rem;
        }
        .profile-card .info .owner {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.3);
        }
        .profile-card .meta {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .profile-card .meta .links-count {
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.75rem;
        }

        /* ── Product card ── */
        .product-card {
            background: rgba(255, 255, 255, 0.015);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 14px;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            flex-direction: column;
        }
        .product-card:hover {
            transform: translateY(-5px);
            border-color: rgba(255, 255, 255, 0.12);
            box-shadow: 0 20px 50px -15px rgba(0,0,0,0.5);
        }
        .product-card .product-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
            display: block;
            background: #0f172a;
        }
        .product-card .product-body {
            padding: 0.9rem 1rem 1rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .product-card .product-name {
            font-weight: 700;
            font-size: 0.95rem;
            color: #fff;
            margin-bottom: 0.1rem;
        }
        .product-card .product-category {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.3);
            margin-bottom: 0.3rem;
        }
        .product-card .product-price {
            font-size: 1rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 0.4rem;
        }
        .product-card .product-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.25rem;
            margin-bottom: 0.7rem;
        }
        .product-card .product-tags .tag {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 999px;
            padding: 0.05rem 0.5rem;
            font-size: 0.55rem;
            color: rgba(255, 255, 255, 0.4);
            font-weight: 600;
            letter-spacing: 0.02em;
        }
        .product-card .product-actions {
            margin-top: auto;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-top: 0.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.04);
        }
        .product-card .product-actions .edit-link {
            color: rgba(255, 255, 255, 0.3);
            font-size: 0.7rem;
            font-weight: 600;
            transition: color 0.2s ease;
            text-decoration: none;
        }
        .product-card .product-actions .edit-link:hover {
            color: #fff;
        }

        /* ── QR select ── */
        .qr-select {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 6px;
            color: #f0f0f0;
            padding: 0.15rem 0.4rem;
            font-size: 0.65rem;
            cursor: pointer;
            transition: border-color 0.2s ease;
        }
        .qr-select:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.2);
        }
        .qr-select option {
            background: #111113;
        }

        /* ── Responsive ── */
        @media (max-width: 640px) {
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .main-grid { grid-template-columns: 1fr; }
            .product-grid { grid-template-columns: 1fr 1fr; }
            .profile-card { flex-direction: column; align-items: stretch; }
            .profile-card .meta { justify-content: flex-start; }
        }

        @media (min-width: 641px) and (max-width: 1024px) {
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .main-grid { grid-template-columns: 1fr; }
            .product-grid { grid-template-columns: 1fr 1fr 1fr; }
        }

        @media (min-width: 1025px) {
            .stats-grid { grid-template-columns: repeat(4, 1fr); }
            .main-grid { grid-template-columns: 1.3fr 0.7fr; }
            .product-grid { grid-template-columns: repeat(3, 1fr); }
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.10);
            border-radius: 999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.18);
        }
    </style>
</head>
<body class="font-sans antialiased">

    <!-- ============================================================ -->
    <!--  STICKY HEADER                                               -->
    <!-- ============================================================ -->
    <header class="sticky top-0 z-50 w-full border-b border-white/[0.04] bg-[#080809]/80 backdrop-blur-2xl saturate-150">
        <div class="mx-auto w-[94%] max-w-[1300px] px-3 py-3 md:py-4">

            <div class="flex items-center justify-between gap-3">

                <!-- Brand -->
               <div class="flex items-center gap-3 shrink-0">
    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' fill='%23333'/%3E%3C/svg%3E" 
         alt="Smart Tap Logo" 
         class="h-12 w-12 rounded-xl object-cover border border-white/5">
</div>

                <!-- Desktop Nav -->
                <nav class="hidden md:flex items-center gap-0.5 text-sm font-medium" aria-label="Main navigation">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 rounded-full border border-transparent px-4 py-2 text-white/35 transition hover:text-white hover:bg-white/5">Home</a>
                    <a href="{{ route('admin.cards.index') }}" class="inline-flex items-center gap-2 rounded-full border border-transparent px-4 py-2 text-white/35 transition hover:text-white hover:bg-white/5">Card Studio</a>
                    <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 rounded-full border border-transparent px-4 py-2 text-white/35 transition hover:text-white hover:bg-white/5">Products</a>
                    <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-2 rounded-full border border-transparent px-4 py-2 text-white/35 transition hover:text-white hover:bg-white/5">Shop</a>
                </nav>

                <!-- Right: mobile toggle -->
                <div class="flex items-center gap-2 shrink-0">
                    <button id="menuToggle" class="md:hidden inline-flex h-9 w-9 items-center justify-center rounded-full border border-white/10 text-white/40 transition hover:border-white/30 hover:bg-white/5 hover:text-white" aria-label="Toggle menu" aria-expanded="false">
                        <i id="menuIcon" class="bi bi-list text-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile dropdown -->
            <div id="mobileMenu" class="md:hidden mt-3 border-t border-white/5 pt-3">
                <nav class="flex flex-col gap-1 text-sm font-medium" aria-label="Mobile navigation">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 rounded-xl border border-transparent px-4 py-3 text-white/40 transition hover:text-white hover:bg-white/5">
                        Home
                    </a>
                    <a href="{{ route('admin.cards.index') }}" class="flex items-center gap-3 rounded-xl border border-transparent px-4 py-3 text-white/40 transition hover:text-white hover:bg-white/5">
                        Card Studio
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 rounded-xl border border-transparent px-4 py-3 text-white/40 transition hover:text-white hover:bg-white/5">
                        Products
                    </a>
                    <a href="{{ route('shop.index') }}" class="flex items-center gap-3 rounded-xl border border-transparent px-4 py-3 text-white/40 transition hover:text-white hover:bg-white/5">
                        Shop
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <!-- ============================================================ -->
    <!--  MAIN CONTENT                                                -->
    <!-- ============================================================ -->
    <main class="relative z-10 mx-auto w-[94%] max-w-[1300px] py-6 pb-12 md:py-8">

        <!-- Alert -->
        @if (session('success'))
            <div class="mb-6 flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-5 py-3.5 text-sm text-white/80 backdrop-blur-sm animate-fade-in">
                <i class="bi bi-check-circle-fill text-white/60 text-base"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Stats -->
        <section class="stats-grid grid gap-4 mb-7" aria-label="Admin summary">
            <div class="stat-card glass rounded-2xl p-4 border border-white/5">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/5 text-white/40">
                        <i class="bi bi-people text-base"></i>
                    </div>
                    <div>
                        <p class="text-[0.55rem] uppercase tracking-[0.15em] text-white/20 font-semibold">Total Users</p>
                        <p class="text-xl font-bold text-white leading-none">{{ $users->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="stat-card glass rounded-2xl p-4 border border-white/5">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/5 text-white/40">
                        <i class="bi bi-person-badge text-base"></i>
                    </div>
                    <div>
                        <p class="text-[0.55rem] uppercase tracking-[0.15em] text-white/20 font-semibold">Profiles</p>
                        <p class="text-xl font-bold text-white leading-none">{{ $profiles->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="stat-card glass rounded-2xl p-4 border border-white/5">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/5 text-white/40">
                        <i class="bi bi-link-45deg text-base"></i>
                    </div>
                    <div>
                        <p class="text-[0.55rem] uppercase tracking-[0.15em] text-white/20 font-semibold">Profile Links</p>
                        <p class="text-xl font-bold text-white leading-none">{{ $latestLinks->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="stat-card glass rounded-2xl p-4 border border-white/5">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/5 text-white/40">
                        <i class="bi bi-box-seam text-base"></i>
                    </div>
                    <div>
                        <p class="text-[0.55rem] uppercase tracking-[0.15em] text-white/20 font-semibold">Catalog</p>
                        <p class="text-xl font-bold text-white leading-none">{{ count($products) }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Grid: Users (table) + Profiles (cards) -->
        <section class="main-grid grid gap-6 mb-7">

            <!-- Users Panel (table with search & bulk) -->
            <div class="glass glow-ring rounded-2xl border border-white/5 p-5 shadow-2xl shadow-black/30 accent-border">
                <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-people text-white/30 text-base"></i>
                        <h2 class="font-display text-base font-bold text-white">Users</h2>
                    </div>
                    <!-- Bulk QR download -->
                    <form method="POST" action="{{ route('admin.users.qr.download') }}" id="bulk-qr-form" class="flex items-center gap-2">
                        @csrf
                        <button class="btn-accent text-sm" id="download-selected-qr" type="submit" disabled>
                            <i class="bi bi-download"></i> Download QR
                        </button>
                    </form>
                </div>

                <!-- Search -->
                <form class="flex flex-wrap items-center gap-2 mb-4" method="GET" action="{{ route('admin.dashboard') }}">
                    <label class="sr-only" for="user_search">Search users</label>
                    <div class="relative flex-1 min-w-[160px]">
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-white/15 text-sm"></i>
                        <input id="user_search" name="user_search" type="search" value="{{ $userSearch }}" placeholder="Search by name, email, or card ID" class="input-dark">
                    </div>
                    <button type="submit" class="btn-primary text-sm">Search</button>
                    @if ($userSearch !== '')
                        <a href="{{ route('admin.dashboard') }}" class="btn-outline text-sm">Clear</a>
                    @endif
                </form>

                <!-- Users table -->
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th style="width:32px;">
                                    <input id="select-all-users" type="checkbox" class="checkbox-dark">
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
                                        <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="checkbox-dark user-qr-checkbox">
                                    </td>
                                    <td class="font-medium text-white/90">{{ $user->name }}</td>
                                    <td class="text-white/50 text-sm">{{ $user->email }}</td>
                                    <td><span class="text-white/30 font-mono text-xs">{{ $user->card_id }}</span></td>
                                    <td>
                                        @if ($user->profile)
                                            <a href="{{ route('profile.public', ['cardId' => $user->card_id]) }}" target="_blank" rel="noopener" class="text-white/60 hover:text-white text-sm transition">Public Card</a>
                                        @else
                                            <span class="text-white/20 text-sm">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="flex flex-wrap gap-1">
                                            <a class="btn-outline text-xs px-2 py-0.5" href="{{ route('admin.users.profile.edit', $user) }}"><i class="bi bi-pencil"></i></a>
                                            <form method="POST" action="{{ route('admin.users.profile-builder.toggle', $user) }}" class="inline">
                                                @csrf
                                                <button class="btn-outline text-xs px-2 py-0.5" type="submit">{{ $user->profile?->profile_builder_active === false ? 'Activate' : 'Deactivate' }}</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.users.duplicate', $user) }}" class="inline">
                                                @csrf
                                                <button class="btn-outline text-xs px-2 py-0.5" type="submit"><i class="bi bi-copy"></i></button>
                                            </form>
                                            @if (auth()->id() !== $user->id)
                                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user and their profile? This cannot be undone.');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn-danger text-xs px-2 py-0.5" type="submit"><i class="bi bi-trash3"></i></button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-white/20 text-xs">{{ $user->created_at?->format('Y-m-d') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-white/30 py-4 text-center text-sm">No users available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Profiles Panel (cards) -->
            <div class="glass glow-ring rounded-2xl border border-white/5 p-5 shadow-2xl shadow-black/30 accent-border">
                <div class="flex items-center gap-2 mb-4">
                    <i class="bi bi-person-badge text-white/30 text-base"></i>
                    <h2 class="font-display text-base font-bold text-white">Profiles</h2>
                </div>
                <div class="space-y-2">
                    @forelse ($profiles as $profile)
                        <div class="profile-card">
                            <div class="avatar">{{ strtoupper(substr($profile->display_name ?: 'U', 0, 2)) }}</div>
                            <div class="info">
                                <div class="name">{{ $profile->display_name ?: 'Unnamed' }}</div>
                                <div class="owner">{{ $profile->user?->email ?? 'n/a' }}</div>
                            </div>
                            <div class="meta">
                                <span class="pill">{{ $profile->card_style }}</span>
                                <span class="pill">{{ $profile->background_pattern }}</span>
                                <span class="links-count"><i class="bi bi-link-45deg"></i> {{ $profile->links_count }}</span>
                            </div>
                            <div>
                                @if ($profile->user)
                                    <select class="qr-select profile-qr-select" data-open-url="{{ route('profile.public', ['cardId' => $profile->user->card_id]) }}" data-qr-download-url="{{ route('admin.users.profile.qr.download', $profile->user) }}">
                                        <option value="">Action</option>
                                        <option value="download">Download QR</option>
                                        <option value="open">Open</option>
                                    </select>
                                @else
                                    <span class="text-white/20 text-sm">—</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-white/30 py-4 text-center text-sm">No profiles available.</div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Latest Profile Links (full-width table) -->
        <section class="glass glow-ring rounded-2xl border border-white/5 p-5 shadow-2xl shadow-black/30 accent-border mb-7">
            <div class="flex items-center gap-2 mb-3">
                <i class="bi bi-link-45deg text-white/30 text-base"></i>
                <h2 class="font-display text-base font-bold text-white">Latest Profile Links</h2>
            </div>
            <div class="table-wrap">
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
                                <td><span class="text-white/50 text-sm">{{ $link->type }}</span></td>
                                <td class="font-medium text-white/90">{{ $link->label }}</td>
                                <td class="text-white/40 text-sm break-all max-w-[200px]">{{ $link->value }}</td>
                                <td class="text-white/50 text-sm">{{ $link->profile?->user?->email ?? 'n/a' }}</td>
                                <td class="text-white/20 text-xs">{{ $link->created_at?->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-white/30 py-4 text-center text-sm">No profile links yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Product Catalog (card grid) -->
        <section class="glass glow-ring rounded-2xl border border-white/5 p-5 shadow-2xl shadow-black/30 accent-border">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                <div class="flex items-center gap-2">
                    <i class="bi bi-box-seam text-white/30 text-base"></i>
                    <h2 class="font-display text-base font-bold text-white">Product Catalog</h2>
                </div>
                <a href="{{ route('admin.products.create') }}" class="btn-accent text-sm">
                    Add Product
                </a>
            </div>

            @if (count($products) > 0)
                <div class="product-grid grid gap-5">
                    @foreach ($products as $product)
                        <div class="product-card">
                            @if ($product['image'])
                                <img class="product-image" src="{{ $product['image'] }}" alt="{{ $product['name'] }}" loading="lazy">
                            @else
                                <div class="product-image" style="background:rgba(255,255,255,0.03);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.1);font-size:0.8rem;">No image</div>
                            @endif
                            <div class="product-body">
                                <div class="product-name">{{ $product['name'] }}</div>
                                <div class="product-category">{{ $product['category'] }}</div>
                                <div class="product-price">PHP {{ number_format($product['price'], 2) }}</div>
                                <div class="product-tags">
                                    @foreach ($product['colors'] as $color)
                                        <span class="tag">{{ $color }}</span>
                                    @endforeach
                                    @foreach ($product['sizes'] as $size)
                                        <span class="tag">{{ $size }}</span>
                                    @endforeach
                                </div>
                                <div class="product-actions">
                                    <a href="{{ route('admin.products.edit', $product['id']) }}" class="edit-link"><i class="bi bi-pencil"></i> Edit</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-white/30 text-center py-8 text-sm">No products yet. <a href="{{ route('admin.products.create') }}" class="text-white/60 hover:text-white transition">Add your first product</a>.</p>
            @endif
        </section>
    </main>

    <!-- ============================================================ -->
    <!--  JAVASCRIPT  (preserved – all backend logic intact)         -->
    <!-- ============================================================ -->
    <script>
        // ─── Search filter ──────────────────────────────────────────
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

        // ─── Bulk QR selection state ────────────────────────────────
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

        // ─── Profile QR select actions ──────────────────────────────
        document.querySelectorAll('.profile-qr-select').forEach((select) => {
            select.addEventListener('change', function () {
                const value = this.value;
                if (!value) return;

                const openUrl = this.dataset.openUrl;
                const downloadUrl = this.dataset.qrDownloadUrl;

                if (value === 'open' && openUrl) {
                    window.open(openUrl, '_blank');
                } else if (value === 'download' && downloadUrl) {
                    window.location.href = downloadUrl;
                }
                this.value = '';
            });
        });

        // ─── Mobile menu toggle ──────────────────────────────────────
        const menuToggle = document.getElementById('menuToggle');
        const mobileMenu = document.getElementById('mobileMenu');
        const menuIcon = document.getElementById('menuIcon');

        if (menuToggle && mobileMenu) {
            menuToggle.addEventListener('click', function () {
                const isOpen = mobileMenu.classList.toggle('open');
                this.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                if (menuIcon) {
                    menuIcon.className = isOpen ? 'bi bi-x-lg text-lg' : 'bi bi-list text-lg';
                }
            });
        }
    </script>
</body>
</html>