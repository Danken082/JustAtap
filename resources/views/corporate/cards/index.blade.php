<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corporate Cards</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Manrope:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --black: #050506; --black-soft: #0a0b0d; --panel: #0e0f12; --line: rgba(255,255,255,.1); --line-strong: rgba(255,255,255,.25); --white: #fff; --gray-100: #eef0f3; --gray-300: #b9bcc4; --gray-500: #7a7d87; --accent: #ff8447; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; color: var(--white); background: var(--black); font-family: 'Manrope', sans-serif; -webkit-font-smoothing: antialiased; }
        body::before { content: ''; position: fixed; inset: 0; z-index: 0; pointer-events: none; background-image: linear-gradient(rgba(255,255,255,.035) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.035) 1px, transparent 1px); background-size: 48px 48px; -webkit-mask-image: radial-gradient(1200px 700px at 50% 0%, #000 0%, transparent 78%); mask-image: radial-gradient(1200px 700px at 50% 0%, #000 0%, transparent 78%); }
        main { position: relative; z-index: 1; width: min(1240px, 92%); margin: 0 auto; padding: 28px 0 64px; }
        header { display: flex; align-items: flex-start; justify-content: space-between; gap: 24px; padding-bottom: 22px; border-bottom: 1px solid var(--line); margin-bottom: 22px; }
        .eyebrow, .section-label { margin: 0 0 8px; color: var(--gray-500); font-family: 'JetBrains Mono', monospace; font-size: .7rem; letter-spacing: .16em; text-transform: uppercase; }
        h1, h2 { margin: 0; font-family: 'Space Grotesk', sans-serif; } h1 { font-size: clamp(1.8rem, 4vw, 2.8rem); letter-spacing: -.03em; } h2 { font-size: 1.15rem; } .subtitle, .muted { color: var(--gray-300); } .subtitle { max-width: 600px; margin: 9px 0 0; line-height: 1.6; font-size: .9rem; } a { color: var(--gray-100); }
        .home-link { padding: 9px 12px; border: 1px solid var(--line-strong); border-radius: 8px; color: var(--gray-100); font-size: .82rem; font-weight: 700; text-decoration: none; white-space: nowrap; }
        .home-link:hover, .card-link:hover { border-color: var(--white); color: var(--white); }
        .grid { display: grid; grid-template-columns: minmax(280px, .75fr) minmax(0, 1.25fr); gap: 14px; }
        .panel { min-width: 0; padding: 20px; border: 1px solid var(--line); border-radius: 12px; background: rgba(14,15,18,.92); box-shadow: 0 18px 50px rgba(0,0,0,.18); } .panel-header { display: flex; align-items: baseline; justify-content: space-between; gap: 12px; margin-bottom: 16px; } .panel-header .muted { font-size: .75rem; }
        .field { margin-top: 14px; } label { display: block; margin-bottom: 7px; color: var(--gray-100); font-size: .78rem; font-weight: 700; }
        input, select, textarea, button { box-sizing: border-box; font: inherit; } input, select, textarea { width: 100%; padding: 10px 11px; border: 1px solid var(--line-strong); border-radius: 8px; outline: none; background: var(--black-soft); color: var(--white); font-size: .85rem; } input:focus, select:focus, textarea:focus { border-color: var(--white); } textarea { min-height: 100px; resize: vertical; }
        button { margin-top: 16px; padding: 10px 14px; border: 1px solid var(--accent); border-radius: 8px; background: var(--accent); color: #1c1010; cursor: pointer; font-size: .8rem; font-weight: 800; } button:hover { filter: brightness(1.08); } .button-danger { border-color: rgba(255,120,120,.45); background: transparent; color: #ffb3b3; }
        .notice, .error { margin: 0 0 14px; padding: 11px 13px; border-radius: 8px; font-size: .82rem; } .notice { border: 1px solid rgba(255,132,71,.5); background: rgba(255,132,71,.14); color: #ffe1d2; } .error { border: 1px solid rgba(255,120,120,.45); background: rgba(145,30,45,.18); color: #ffb3b3; }
        table { width: 100%; border-collapse: collapse; margin-top: 18px; font-size: .8rem; } th, td { padding: 10px 7px; border-bottom: 1px solid var(--line); text-align: left; vertical-align: top; } th { color: var(--gray-500); font-size: .68rem; letter-spacing: .08em; text-transform: uppercase; } td { color: var(--gray-100); } .card-link { color: var(--white); font-family: 'JetBrains Mono', monospace; font-size: .72rem; text-decoration: none; }
        .full { grid-column: 1 / -1; } .reorder { margin-top: 22px; padding-top: 18px; border-top: 1px solid var(--line); } .reorder select { min-height: 140px; } .reorder .muted { margin: 8px 0 0; font-size: .76rem; line-height: 1.5; }
        .profile-link { display: inline-block; margin: 0 0 16px; color: var(--gray-100); font-size: .82rem; font-weight: 700; } .profile-actions { display: flex; flex-wrap: wrap; gap: 8px; margin: 0 0 20px; } .profile-actions form { margin: 0; } .profile-actions button { margin-top: 0; }
        @media (max-width: 760px) { main { width: min(100% - 28px, 600px); padding-top: 20px; } header { align-items: stretch; flex-direction: column; gap: 16px; } .home-link { align-self: flex-start; } .grid { grid-template-columns: 1fr; } .panel { padding: 16px; overflow-x: auto; } table { min-width: 520px; } .full { grid-column: auto; } }
    </style>
</head>
<body>
    <main>
        <header>
            <div><p class="eyebrow">Just A Tap / Corporate</p><h1>Card operations</h1><p class="subtitle">Order employee cards and manage the profiles attached to your company.</p></div>
            <a class="home-link" href="{{ route('home') }}">Back to home</a>
        </header>
        @if (session('success')) <p class="notice">{{ session('success') }}</p> @endif
        @if ($errors->any()) <p class="error">{{ $errors->first() }}</p> @endif
        <div class="grid">
            <section class="panel">
                <div class="panel-header"><h2>Order cards</h2><span class="muted">Up to 500</span></div>
                <form method="POST" action="{{ route('corporate.cards.order') }}">@csrf
                    <div class="field"><label for="name">Order label</label><input id="name" name="name" value="{{ old('name') }}" placeholder="Sales team cards"></div>
                    <div class="field"><label for="quantity">Quantity</label><input id="quantity" type="number" name="quantity" min="1" max="500" value="{{ old('quantity', 10) }}" required></div>
                    <button type="submit">Order cards</button>
                </form>
            </section>
            <section class="panel">
                <div class="panel-header"><h2>Ordered cards</h2><span class="muted">{{ $cards->count() }} total</span></div>
                <form method="GET" action="{{ route('corporate.cards.index') }}">
                    <div class="field"><label for="card_id">Find by card ID</label><input id="card_id" name="card_id" value="{{ request('card_id') }}" placeholder="CARD-XXXXXXXXXXXX"></div><button type="submit">Open card</button>
                </form>
                <table><thead><tr><th>Card ID</th><th>Label</th><th>Employee</th></tr></thead><tbody>
                @forelse ($cards as $card)
                    @php($cardEmployee = \App\Models\User::where('card_id', $card->card_number)->first())
                    <tr><td><a class="card-link" href="{{ route('corporate.cards.index', ['card_id' => $card->card_number]) }}">{{ $card->card_number }}</a></td><td>{{ $card->name }}</td><td>{{ $cardEmployee?->name ?? 'Awaiting registration' }}</td></tr>
                @empty <tr><td colspan="3" class="muted">No corporate cards ordered yet.</td></tr> @endforelse
                </tbody></table>

                @if ($cards->isNotEmpty())
                    <form class="reorder" method="POST" action="{{ route('corporate.cards.reorder') }}">
                        @csrf
                        <label for="card_ids">Reorder cards</label>
                        <select id="card_ids" name="card_ids[]" multiple size="{{ min($cards->count(), 10) }}" style="min-height: 140px;">
                            @foreach ($cards as $card)
                                <option value="{{ $card->id }}" @selected(request('card_id') === $card->card_number)>{{ $card->card_number }} - {{ $card->name }}</option>
                            @endforeach
                        </select>
                        <p class="muted">Select your cards in the order you want them displayed, then submit.</p>
                        <button type="submit">Save card order</button>
                    </form>
                @endif
            </section>
            @if ($selectedCard)
                <section class="panel full">
                    <h2>{{ $selectedCard->card_number }}</h2>
                    @if ($employee)
                        <p><a href="{{ route('profile.public', ['cardId' => $selectedCard->card_number]) }}" target="_blank" rel="noopener">View employee live profile</a></p>
                        <div class="profile-actions">
                            <form method="POST" action="{{ route('corporate.cards.employees.deactivate', $employee) }}">@csrf
                                <button type="submit">Deactivate account</button>
                            </form>
                            <form method="POST" action="{{ route('corporate.cards.employees.delete', $employee) }}" onsubmit="return confirm('Delete this employee account and their card assignment?');">@csrf @method('DELETE')
                                <button class="button-danger" type="submit">Delete account</button>
                            </form>
                        </div>
                        <form method="POST" action="{{ route('corporate.cards.profile.update', $selectedCard->card_number) }}">@csrf
                            <label for="display_name">Display name</label><input id="display_name" name="display_name" value="{{ old('display_name', $profile->display_name) }}" required>
                            <label for="title">Title</label><input id="title" name="title" value="{{ old('title', $profile->title) }}">
                            <label for="bio">Bio</label><textarea id="bio" name="bio">{{ old('bio', $profile->bio) }}</textarea>
                            <label for="layout_style">Layout</label><select id="layout_style" name="layout_style"><option value="classic_card" @selected(old('layout_style', $profile->layout_style) === 'classic_card')>Classic card</option><option value="wave_split" @selected(old('layout_style', $profile->layout_style) === 'wave_split')>Wave split</option><option value="soft_fade" @selected(old('layout_style', $profile->layout_style) === 'soft_fade')>Soft fade</option><option value="hihello_card" @selected(old('layout_style', $profile->layout_style) === 'hihello_card')>HiHello style</option></select>
                            <input type="hidden" name="display_name_font_size" value="{{ old('display_name_font_size', $profile->display_name_font_size) }}"><input type="hidden" name="card_style" value="{{ old('card_style', $profile->card_style) }}"><input type="hidden" name="background_pattern" value="{{ old('background_pattern', $profile->background_pattern) }}"><input type="hidden" name="background_color" value="{{ old('background_color', $profile->background_color) }}"><input type="hidden" name="text_color" value="{{ old('text_color', $profile->text_color) }}">
                            <button type="submit">Save live profile</button>
                        </form>
                    @else <p class="muted">This card has not been registered by an employee yet.</p> @endif
                </section>
            @endif
        </div>
    </main>
</body>
</html>