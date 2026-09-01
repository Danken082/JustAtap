<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corporate Cards</title>
    <style>
        body { margin: 0; color: #172033; background: #f1f5f3; font-family: Georgia, serif; }
        main { max-width: 1120px; margin: 0 auto; padding: 32px 20px 56px; }
        header, .grid { display: grid; gap: 20px; } header { grid-template-columns: 1fr auto; align-items: center; margin-bottom: 24px; }
        h1, h2 { margin: 0 0 10px; } h1 { font-size: 32px; } h2 { font-size: 20px; } a { color: #005f5b; }
        .grid { grid-template-columns: minmax(280px, .8fr) minmax(0, 1.2fr); } .panel { background: #fff; border: 1px solid #cdd9d5; border-radius: 8px; padding: 20px; }
        label { display: block; font-weight: bold; margin: 12px 0 5px; } input, select, textarea, button { box-sizing: border-box; font: inherit; } input, select, textarea { width: 100%; border: 1px solid #9fb3ad; border-radius: 4px; padding: 9px; } textarea { min-height: 90px; resize: vertical; }
        button { margin-top: 16px; padding: 10px 14px; border: 0; border-radius: 4px; background: #005f5b; color: #fff; cursor: pointer; } .notice { padding: 11px; background: #dff2e8; color: #154a32; border-radius: 4px; } .error { color: #a11c2e; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; } th, td { padding: 9px 5px; text-align: left; border-bottom: 1px solid #dce5e2; } .muted { color: #60716d; } .full { grid-column: 1 / -1; }
        @media (max-width: 700px) { header, .grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <main>
        <header>
            <div><h1>Corporate Cards</h1><p class="muted">Order employee cards and manage the profiles attached to your order.</p></div>
            <a href="{{ route('home') }}">Home</a>
        </header>
        @if (session('success')) <p class="notice">{{ session('success') }}</p> @endif
        @if ($errors->any()) <p class="error">{{ $errors->first() }}</p> @endif
        <div class="grid">
            <section class="panel">
                <h2>Order Cards</h2>
                <form method="POST" action="{{ route('corporate.cards.order') }}">@csrf
                    <label for="name">Order label</label><input id="name" name="name" value="{{ old('name') }}" placeholder="Sales team cards">
                    <label for="quantity">Quantity</label><input id="quantity" type="number" name="quantity" min="1" max="500" value="{{ old('quantity', 10) }}" required>
                    <button type="submit">Order cards</button>
                </form>
            </section>
            <section class="panel">
                <h2>Your Ordered Cards</h2>
                <form method="GET" action="{{ route('corporate.cards.index') }}">
                    <label for="card_id">Find by card ID</label><input id="card_id" name="card_id" value="{{ request('card_id') }}" placeholder="ID-2026-000001"><button type="submit">Open card</button>
                </form>
                <table><thead><tr><th>Card ID</th><th>Label</th><th>Employee</th></tr></thead><tbody>
                @forelse ($cards as $card)
                    @php($cardEmployee = \App\Models\User::where('card_id', $card->card_number)->first())
                    <tr><td><a href="{{ route('corporate.cards.index', ['card_id' => $card->card_number]) }}">{{ $card->card_number }}</a></td><td>{{ $card->name }}</td><td>{{ $cardEmployee?->name ?? 'Awaiting registration' }}</td></tr>
                @empty <tr><td colspan="3" class="muted">No corporate cards ordered yet.</td></tr> @endforelse
                </tbody></table>

                @if ($cards->isNotEmpty())
                    <form method="POST" action="{{ route('corporate.cards.reorder') }}" style="margin-top: 18px;">
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
                        <div style="display:flex; gap:10px; margin: 12px 0 18px; flex-wrap:wrap;">
                            <form method="POST" action="{{ route('corporate.cards.employees.deactivate', $employee) }}">@csrf
                                <button type="submit">Deactivate account</button>
                            </form>
                            <form method="POST" action="{{ route('corporate.cards.employees.delete', $employee) }}" onsubmit="return confirm('Delete this employee account and their card assignment?');">@csrf @method('DELETE')
                                <button type="submit" style="background:#8b1e2d;">Delete account</button>
                            </form>
                        </div>
                        <form method="POST" action="{{ route('corporate.cards.profile.update', $selectedCard->card_number) }}">@csrf
                            <label for="display_name">Display name</label><input id="display_name" name="display_name" value="{{ old('display_name', $profile->display_name) }}" required>
                            <label for="title">Title</label><input id="title" name="title" value="{{ old('title', $profile->title) }}">
                            <label for="bio">Bio</label><textarea id="bio" name="bio">{{ old('bio', $profile->bio) }}</textarea>
                            <label for="layout_style">Layout</label><select id="layout_style" name="layout_style"><option value="classic_card" @selected(old('layout_style', $profile->layout_style) === 'classic_card')>Classic card</option><option value="wave_split" @selected(old('layout_style', $profile->layout_style) === 'wave_split')>Wave split</option><option value="soft_fade" @selected(old('layout_style', $profile->layout_style) === 'soft_fade')>Soft fade</option><option value="hihello_card" @selected(old('layout_style', $profile->layout_style) === 'hihello_card')>HiHello style</option></select>
                            <input type="hidden" name="display_name_font_size" value="{{ old('display_name_font_size', $profile->display_name_font_size) }}"><input type="hidden" name="card_style" value="{{ old('card_style', $profile->card_style) }}"><input type="hidden" name="background_pattern" value="{{ old('background_pattern', $profile->background_pattern) }}"><input type="hidden" name="background_color" value="{{ old('background_color', $profile->background_color) }}"><input type="hidden" name="text_color" value="{{ old('text_color', $profile->text_color) }}"><input type="hidden" name="accent_color" value="{{ old('accent_color', $profile->accent_color) }}">
                            <button type="submit">Save live profile</button>
                        </form>
                    @else <p class="muted">This card has not been registered by an employee yet.</p> @endif
                </section>
            @endif
        </div>
    </main>
</body>
</html>