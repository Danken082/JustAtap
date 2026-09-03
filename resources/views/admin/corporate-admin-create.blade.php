<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Corporate Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Manrope:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --black: #050506; --black-soft: #0a0b0d; --panel: #0e0f12; --line: rgba(255,255,255,.1); --line-strong: rgba(255,255,255,.25); --white: #fff; --gray-100: #eef0f3; --gray-300: #b9bcc4; --gray-500: #7a7d87; --accent: #ff8447; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; color: var(--white); background: var(--black); font-family: 'Manrope', sans-serif; -webkit-font-smoothing: antialiased; }
        body::before { content: ''; position: fixed; inset: 0; z-index: 0; pointer-events: none; background-image: linear-gradient(rgba(255,255,255,.035) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.035) 1px, transparent 1px); background-size: 48px 48px; -webkit-mask-image: radial-gradient(1200px 700px at 50% 0%, #000 0%, transparent 78%); mask-image: radial-gradient(1200px 700px at 50% 0%, #000 0%, transparent 78%); }
        main { position: relative; z-index: 1; width: min(720px, calc(100% - 32px)); margin: 0 auto; padding: 38px 0 64px; }
        .panel { padding: 28px 24px 24px; border: 1px solid var(--line); border-radius: 12px; background: rgba(14,15,18,.94); box-shadow: 0 22px 60px rgba(0,0,0,.25); }
        .eyebrow { margin: 0 0 8px; color: var(--gray-500); font-family: 'JetBrains Mono', monospace; font-size: .7rem; letter-spacing: .16em; text-transform: uppercase; }
        h1 { margin: 0; color: var(--white); font-family: 'Space Grotesk', sans-serif; font-size: clamp(1.8rem, 5vw, 2.5rem); letter-spacing: -.03em; }
        .intro { margin: 10px 0 26px; color: var(--gray-300); font-size: .9rem; line-height: 1.6; }
        .muted { color: var(--gray-300); }
        .field { margin-bottom: 17px; }
        label { display: block; margin-bottom: 7px; color: var(--gray-100); font-size: .78rem; font-weight: 700; }
        input, textarea { width: 100%; padding: 11px 12px; border: 1px solid var(--line-strong); border-radius: 8px; outline: none; background: var(--black-soft); color: var(--white); font: inherit; font-size: .86rem; }
        input:focus, textarea:focus { border-color: var(--white); }
        textarea { min-height: 110px; resize: vertical; }
        button { padding: 11px 16px; border: 1px solid var(--accent); border-radius: 8px; background: var(--accent); color: #1c1010; font-size: .8rem; font-weight: 800; cursor: pointer; }
        button:hover { filter: brightness(1.08); }
        .notice, .error { margin-bottom: 18px; padding: 12px 13px; border-radius: 8px; font-size: .82rem; }
        .notice { border: 1px solid rgba(255,132,71,.5); background: rgba(255,132,71,.14); color: #ffe1d2; }
        .error { border: 1px solid rgba(255,120,120,.45); background: rgba(145,30,45,.18); color: #ffb3b3; }
        .back-link { display: inline-flex; margin-bottom: 18px; color: var(--gray-100); font-size: .82rem; font-weight: 700; text-decoration: none; }
        .back-link:hover { color: var(--white); }
        @media (max-width: 560px) { main { width: min(100% - 24px, 720px); padding-top: 22px; } .panel { padding: 22px 16px 18px; } }
    </style>
</head>
<body>
    <main>
        <a class="back-link" href="{{ route('admin.dashboard') }}">&larr; Back to dashboard</a>
        <section class="panel">
            <p class="eyebrow">Just A Tap / Admin</p>
            <h1>Register Corporate Admin</h1>
            <p class="intro">Create a corporate admin account and assign the ID cards they should manage. The default password will be set from the value below.</p>

            @if (session('success'))
                <div class="notice">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('admin.corporate-admins.store') }}">
                @csrf

                <div class="field">
                    <label for="name">Full name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required>
                </div>

                <div class="field">
                    <label for="company_name">Company name</label>
                    <input id="company_name" name="company_name" type="text" value="{{ old('company_name') }}" required>
                </div>

                <div class="field">
                    <label for="email">Corporate admin email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                </div>

                <div class="field">
                    <label for="default_password">Default password</label>
                    <input id="default_password" name="default_password" type="text" value="{{ old('default_password') }}" placeholder="Welcome123!" required>
                </div>

                <div class="field">
                    <label for="card_count">Number of card IDs to generate</label>
                    <input id="card_count" name="card_count" type="number" min="0" max="1000" value="{{ old('card_count', 0) }}" required>
                    <p class="muted" style="margin-top: 6px; font-size: 13px;">Enter how many unique card IDs should be generated for this corporate admin. New orders will automatically generate additional IDs as needed.</p>
                </div>

                <button type="submit">Register corporate admin</button>
            </form>
        </section>
    </main>
</body>
</html>
