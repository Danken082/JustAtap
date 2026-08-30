<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Just A Tap</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0a0c11;
            --panel: #0f121a;
            --text: #ffffff;
            --muted: #cad2e8;
            --soft: rgba(255, 255, 255, 0.12);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: radial-gradient(circle at 0% 0%, rgba(43, 77, 170, 0.45), transparent 28%),
                        radial-gradient(circle at 95% 75%, rgba(255, 128, 70, 0.2), transparent 36%),
                        var(--bg);
            color: var(--text);
            font-family: 'Manrope', sans-serif;
        }

        .shell {
            width: min(1500px, 95%);
            margin: 0 auto;
            padding: 18px 0 38px;
        }

        html {
            scroll-behavior: smooth;
        }

        .reveal {
            opacity: 0;
            transform: translateY(36px);
            transition: opacity 0.8s ease, transform 0.8s ease;
            will-change: opacity, transform;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.2s; }
        .reveal-delay-3 { transition-delay: 0.3s; }

        .top-nav {
            background: #0b0d13;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text);
            text-decoration: none;
        }

        .brand-mark {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: grid;
            place-items: center;
            color: #11131a;
            font-weight: 800;
            background: linear-gradient(145deg, #f4f7ff, #96b1ff);
        }

        .brand-name {
            letter-spacing: 0.3em;
            text-transform: uppercase;
            font-size: 0.8rem;
            color: #e5ebff;
        }

        .menu {
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: clamp(12px, 2vw, 26px);
            flex-wrap: wrap;
        }

        .menu a {
            color: #ffffff;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.96rem;
        }

        .menu a:hover {
            color: #d2ddff;
        }

        .nav-tools {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: auto;
        }

        .icon-btn {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            display: grid;
            place-items: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            text-decoration: none;
            transition: border-color 150ms ease;
        }

        .icon-btn:hover {
            border-color: rgba(255, 255, 255, 0.55);
        }

        .market {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            display: grid;
            place-items: center;
            text-decoration: none;
            font-weight: 800;
            color: #fff;
            font-size: 0.75rem;
        }

        .market.shopee {
            background: linear-gradient(150deg, #ff7c4d, #ef4b18);
        }

        .market.lazada {
            background: linear-gradient(150deg, #172997, #0f7cff);
        }

        .auth-link,
        .logout-btn {
            border: 0;
            background: #f2f6ff;
            color: #121724;
            text-decoration: none;
            border-radius: 999px;
            padding: 8px 14px;
            font-weight: 700;
            font-size: 0.82rem;
        }

        .logout-btn {
            cursor: pointer;
        }

        .hero {
    margin-top: 16px;
    min-height: min(72vh, 640px);
    border-radius: 18px;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.08);
    position: relative;
}

.hero-video {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: 0;
}

.hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        180deg,
        rgba(0, 0, 0, 0.1),
        rgba(0, 0, 0, 0.7)
    );
    z-index: 1;
}

.hero-content {
    position: relative;
    z-index: 2;
    color: #fff;
    padding: 4rem;
}

        .hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, transparent 50%, rgba(0, 0, 0, 0.78));
            pointer-events: none;
        }

        .hero-copy {
            position: absolute;
            left: clamp(22px, 6vw, 90px);
            bottom: clamp(34px, 8vw, 76px);
            max-width: 620px;
            z-index: 1;
        }

        h1 {
            margin: 0;
            line-height: 1.03;
            font-size: clamp(2rem, 5vw, 4rem);
            letter-spacing: -0.02em;
        }

        .cta {
            position: absolute;
            right: clamp(24px, 6vw, 90px);
            bottom: clamp(36px, 8vw, 84px);
            z-index: 1;
            text-decoration: none;
            color: #0e1420;
            background: #f2f5ff;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            padding: 12px 22px;
        }

        .auth-badge {
            margin-top: 14px;
            color: #dbe4ff;
            font-size: 0.95rem;
        }

        .flash {
            margin-top: 16px;
            border-radius: 12px;
            border: 1px solid rgba(102, 235, 152, 0.45);
            background: rgba(102, 235, 152, 0.12);
            color: #d9ffe7;
            padding: 11px 14px;
        }

        .products {
            margin-top: 46px;
            padding-bottom: 8px;
        }

        .products h2 {
            margin: 0 0 18px;
            text-align: center;
            font-size: clamp(1.7rem, 3vw, 2.2rem);
            letter-spacing: -0.02em;
        }

        .product-grid {
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .product-card {
            min-height: 330px;
            border-radius: 10px;
            position: relative;
            overflow: hidden;
            background-size: cover;
            background-position: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
            text-decoration: none;
            color: #fff;
        }

        .product-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, transparent 58%, rgba(0, 0, 0, 0.75));
        }

        .product-copy {
            position: absolute;
            left: 18px;
            right: 18px;
            bottom: 14px;
            z-index: 1;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 10px;
        }

        .product-copy h3 {
            margin: 0;
            font-size: 1.8rem;
            line-height: 1;
        }

        .product-copy p {
            margin: 3px 0 0;
            color: #d8def0;
            font-size: 0.78rem;
        }

        .product-left {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .add-form {
            margin: 0;
        }

        .add-btn {
            border: 0;
            border-radius: 999px;
            background: #edf2ff;
            color: #101624;
            font-weight: 700;
            font-size: 0.78rem;
            padding: 7px 11px;
            cursor: pointer;
        }

        .shop-link {
            color: #edf2ff;
            font-size: 0.78rem;
            font-weight: 700;
            text-decoration: none;
        }

        .product-arrow {
            font-size: 1.25rem;
            line-height: 1;
            color: #f2f5ff;
            text-decoration: none;
        }

        @media (max-width: 1040px) {
            .top-nav {
                flex-wrap: wrap;
            }

            .menu {
                order: 3;
                width: 100%;
                margin: 4px 0 0;
                justify-content: flex-start;
            }

            .nav-tools {
                margin-left: 0;
            }
        }

        @media (max-width: 430px) {
            .shell {
                width: min(1500px, 93%);
            }

            .hero {
                min-height: 70vh;
            }

            .cta {
                left: clamp(22px, 6vw, 90px);
                right: auto;
                bottom: 24px;
            }

            .product-grid {
                grid-template-columns: 1fr;
            }

            .product-card {
                min-height: 300px;
            }
        }
    </style>
</head>
<body>
    @php($guestCartCount = array_sum(session('guest_cart', [])))

    <div class="shell">
        <nav class="top-nav reveal reveal-delay-1">
            <a href="{{ route('home') }}" class="brand" aria-label="Smart Tap home">
                <span class="brand-mark">JAT</span>
                <span class="brand-name">Just A Tap</span>
            </a>

            <div class="menu" aria-label="Main menu">
                <a href="#">Home</a>
                <a href="{{ route('shop.index') }}">Cards</a>
                <a href="#">Tags</a>
                <a href="#">Custom Cards &amp; Tags</a>
                <a href="#">Accessories</a>
                <a href="#">News</a>
                <a href="#">Contact</a>
            </div>

            <div class="nav-tools">
                <a class="market shopee" href="https://shopee.com" target="_blank" rel="noopener" title="Shop on Shopee" aria-label="Shopee">S</a>
                <a class="market lazada" href="https://lazada.com" target="_blank" rel="noopener" title="Shop on Lazada" aria-label="Lazada">L</a>

                <a class="icon-btn" href="#" aria-label="Search">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8"></circle>
                        <path d="M20 20L17.2 17.2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
                    </svg>
                </a>
                <a class="icon-btn" href="#" aria-label="Account">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.8"></circle>
                        <path d="M4 20C5.4 16.7 8.2 15 12 15C15.8 15 18.6 16.7 20 20" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
                    </svg>
                </a>
                <a class="icon-btn" href="{{ route('cart.index') }}" aria-label="Cart">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M3 5H6L8.3 15H18.2L20.5 8H7.2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                        <circle cx="10" cy="19" r="1.4" fill="currentColor"></circle>
                        <circle cx="17" cy="19" r="1.4" fill="currentColor"></circle>
                    </svg>
                </a>

                @guest
                    <a href="{{ route('cart.index') }}" class="auth-link">Cart {{ $guestCartCount }}</a>
                @endguest

                @auth
                    <a href="{{ route('profile.edit') }}" class="auth-link">Profile Builder</a>
                    @if (auth()->user()->isCorporate())
                        <a href="{{ route('corporate.cards.index') }}" class="auth-link">Corporate Cards</a>
                    @endif
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="auth-link">Admin</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="auth-link">Login</a>
                @endauth
            </div>
        </nav>
<!-- 
        @if (session('status'))
            <p class="flash">{{ session('status') }}</p>
        @endif -->

        <section class="hero reveal reveal-delay-2">
            <video class="hero-video" autoplay muted loop playsinline>
        <source src="https://smarttap.au/cdn/shop/videos/c/vp/6610f314738b4b1c8dcbc6e42f42bb79/6610f314738b4b1c8dcbc6e42f42bb79.HD-720p-4.5Mbps-42023443.mp4?v=0" type="video/mp4">
    </video>
            <div class="hero-copy">
                <h1>Smart Tap, Digital Business Cards &amp; NFC Networking Solutions Australia</h1>

                @auth
                    <p class="auth-badge">Logged in as {{ auth()->user()->name }} ({{ auth()->user()->email }})</p>
                @endauth
            </div>

            <a class="cta" href="#">Explore Now <span aria-hidden="true">&rarr;</span></a>
        </section>

        <section class="products reveal reveal-delay-3" aria-label="Our products">
            <h2>Our Products</h2>

            <div class="product-grid">
                @forelse ($homeProducts as $index => $product)
                    <article class="product-card reveal reveal-delay-{{ ($index % 3) + 1 }}" style="background-image: url('{{ $product['image'] }}');">
                        <div class="product-copy">
                            <div class="product-left">
                                <h3>{{ $product['name'] }}</h3>
                                <p>{{ \Illuminate\Support\Str::limit($product['description'], 60) }}</p>

                                @guest
                                    <form method="POST" action="{{ route('cart.add', ['product' => $product['id']]) }}" class="add-form">
                                        @csrf
                                        <input type="hidden" name="color" value="{{ $product['colors'][0] }}">
                                        <input type="hidden" name="size" value="{{ $product['sizes'][0] }}">
                                        <button type="submit" class="add-btn">Add to Cart</button>
                                    </form>
                                @else
                                    <a href="{{ route('shop.index') }}" class="shop-link">Guest shop</a>
                                @endguest
                            </div>
                            <a class="product-arrow" href="{{ route('shop.index') }}" aria-label="Go to guest shop">&rarr;</a>
                        </div>
                    </article>
                @empty
                    <p class="muted">No products available yet.</p>
                @endforelse
            </div>
        </section>
    </div>

    <script>
        const revealItems = document.querySelectorAll('.reveal');

        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.15,
            rootMargin: '0px 0px -30px 0px'
        });

        revealItems.forEach((item) => revealObserver.observe(item));
    </script>
</body>
</html>
