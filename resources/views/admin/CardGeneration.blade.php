<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Card Studio</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --bg: #050506;
            --panel: rgba(17,17,19,0.72);
            --text: #f6f7fb;
            --muted: rgba(255,255,255,0.42);
            --accent: #ffffff;
            --line: rgba(255,255,255,0.08);
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Manrope', sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        .wrap { width: min(1240px, 94%); margin: 0 auto; padding: 32px 0 56px; }
        .top { display: flex; justify-content: space-between; align-items: flex-end; gap: 18px; margin-bottom: 28px; }
        .eyebrow { margin: 0 0 8px; color: rgba(255,255,255,0.32); font-family: 'Space Grotesk', sans-serif; font-size: 0.65rem; font-weight: 700; letter-spacing: 0.18em; text-transform: uppercase; }
        .top h1 { margin: 0; font-family: 'Space Grotesk', sans-serif; font-size: clamp(1.8rem, 3vw, 2.7rem); letter-spacing: -0.03em; }
        .top p { margin: 8px 0 0; color: var(--muted); font-size: 0.9rem; }
        .top a { display: inline-flex; align-items: center; gap: 8px; color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.85rem; font-weight: 700; }
        .top a:hover { color: #fff; }
        .alert { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.14); color: #fff; padding: 13px 16px; border-radius: 12px; margin-bottom: 18px; }
        .grid { display: grid; grid-template-columns: 1.05fr 0.95fr; gap: 16px; margin-bottom: 16px; }
        .panel { background: var(--panel); backdrop-filter: blur(18px); border: 1px solid var(--line); border-radius: 18px; padding: 22px; box-shadow: 0 20px 45px rgba(0,0,0,0.24); }
        .panel h2 { margin: 0 0 6px; font-family: 'Space Grotesk', sans-serif; font-size: 1.12rem; }
        .panel-intro { margin: 0 0 18px; color: var(--muted); font-size: 0.82rem; line-height: 1.5; }
        label { display: block; font-size: 0.78rem; font-weight: 700; color: rgba(255,255,255,0.58); margin-bottom: 7px; }
        input, select, textarea, button { width: 100%; border-radius: 11px; border: 1px solid var(--line); background: rgba(255,255,255,0.045); color: var(--text); padding: 11px 12px; font: inherit; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: rgba(255,255,255,0.34); background: rgba(255,255,255,0.07); }
        textarea { min-height: 92px; resize: vertical; }
        button { cursor: pointer; background: #fff; color: #09090b; font-weight: 800; border: 0; }
        button:hover { background: rgba(255,255,255,0.86); }
        .row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .stack { display: grid; gap: 12px; }
        .helper { font-size: 0.82rem; color: var(--muted); margin-top: 6px; }
        .meta { margin-top: 18px; padding: 13px 14px; border: 1px solid var(--line); border-radius: 11px; color: var(--muted); font-size: 0.82rem; }
        .meta strong { display: block; color: rgba(255,255,255,0.38); font-size: 0.65rem; letter-spacing: 0.12em; text-transform: uppercase; margin-bottom: 5px; }
        .preview-card { border-radius: 20px; padding: 24px; min-height: 260px; display: flex; flex-direction: column; justify-content: space-between; border: 1px solid rgba(255,255,255,0.2); background: linear-gradient(135deg, #111827, #1f2937); color: white; box-shadow: inset 0 1px rgba(255,255,255,0.08); }
        .preview-card .title { font-size: 1.05rem; font-weight: 700; margin-bottom: 4px; }
        .preview-card .bio { font-size: 0.95rem; line-height: 1.5; color: rgba(255,255,255,0.9); }
        .preview-tags { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 12px; }
        .preview-tags span { display: inline-flex; padding: 6px 10px; border-radius: 999px; background: rgba(255,255,255,0.14); font-size: 0.78rem; }
        .table-wrap { overflow-x: auto; border: 1px solid var(--line); border-radius: 13px; }
        table { width: 100%; min-width: 760px; border-collapse: collapse; font-size: 0.82rem; }
        th, td { padding: 12px 10px; border-bottom: 1px solid rgba(255,255,255,0.06); text-align: left; vertical-align: middle; }
        tr:last-child td { border-bottom: 0; }
        th { color: rgba(255,255,255,0.34); font-size: 0.62rem; text-transform: uppercase; letter-spacing: 0.12em; }
        td { color: rgba(255,255,255,0.62); }
        .card-number-input { min-width: 170px; }
        .card-label-input { min-width: 150px; }
        .save-button { width: auto; padding: 9px 14px; }
        .muted-cell { color: rgba(255,255,255,0.28); }
        .section-heading { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 16px; }
        .section-heading h2 { margin: 0; }
        .section-heading i { color: rgba(255,255,255,0.35); font-size: 1.05rem; }
        .card-filter { width: auto; min-width: 170px; padding: 9px 32px 9px 11px; font-size: 0.78rem; }
        .card-filter option { color: #000; background: #fff; }
        .card-filter-group { display: flex; align-items: center; gap: 10px; }
        .card-filter-count { color: rgba(255,255,255,0.38); font-size: 0.75rem; white-space: nowrap; }
        @media (max-width: 920px) { .grid, .row { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <main class="wrap">
        <header class="top">
            <div>
                <p class="eyebrow">Administration / Identity</p>
                <h1>Card Studio</h1>
                <p>Generate, assign, and maintain the card IDs connected to your profiles.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}"><i class="bi bi-arrow-left"></i> Back to dashboard</a>
        </header>

        @if (session('success'))
            <div class="alert">{{ session('success') }}</div>
        @endif

        <section class="grid">
            <article class="panel">
                <div class="section-heading"><h2>Generate a card</h2><i class="bi bi-credit-card-2-front"></i></div>
                <p class="panel-intro">Create a unique card ID. You can assign it to a user later.</p>
                <form method="POST" action="{{ route('admin.cards.generate') }}" class="stack">
                    @csrf
                    <div class="stack">
                        <label for="name">Card label</label>
                        <input id="name" name="name" placeholder="Card Label" required>
                    </div>
                    <button type="submit">Generate card</button>
                </form>

                <div class="meta">
                    <strong>Current card ID:</strong>
                    {{ $selectedUser?->card_id ?: 'Not assigned yet' }}
                </div>
            </article>

          </section>

        <section class="panel" style="margin-top:16px;">
            <div class="section-heading">
                <h2>Generated cards</h2>
                <div class="card-filter-group">
                    <span id="card-filter-count" class="card-filter-count" aria-live="polite"></span>
                    <select id="card-filter" class="card-filter" aria-label="Filter generated cards">
                        <option value="all">Show all cards</option>
                        <option value="assigned">Assigned cards</option>
                        <option value="unassigned">Unassigned card IDs</option>
                    </select>
                </div>
            </div>
            <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Card Number</th>
                        <th>Name</th>
                        <th>Assigned To</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cards as $card)
                        @php
                            $assignedUser = \App\Models\User::where('card_id', $card->card_number)->first();
                        @endphp
                        <tr data-card-status="{{ $assignedUser ? 'assigned' : 'unassigned' }}">
                            <td>{{ $loop->iteration }}</td>
                            <td class="font-mono">{{ $card->card_number }}</td>
                            <td>{{ $card->name }}</td>
                            <td class="{{ $assignedUser ? '' : 'muted-cell' }}">{{ $assignedUser ? $assignedUser->name : 'No registration yet' }}</td>
                            <td>{{ $card->created_at?->format('M d, Y h:i A') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="helper muted-cell">No cards found.</td></tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </section>
    </main>

    <script>
        const form = document.getElementById('adminProfileForm');
        if (form) {
            const preview = document.getElementById('profilePreview');
            const previewDisplayName = document.getElementById('previewDisplayName');
            const previewTitle = document.getElementById('previewTitle');
            const previewBio = document.getElementById('previewBio');
            const previewLayout = document.getElementById('previewLayout');
            const previewStyle = document.getElementById('previewStyle');

            const updatePreview = () => {
                const displayName = document.getElementById('display_name').value || 'Display name';
                const title = document.getElementById('title').value || 'Digital Profile';
                const bio = document.getElementById('bio').value || 'Edit your profile from the dashboard.';
                const layout = document.getElementById('layout_style').value || 'classic_card';
                const style = document.getElementById('card_style').value || 'glass';
                const backgroundColor = document.getElementById('background_color').value || '#111827';
                const textColor = document.getElementById('text_color').value || '#f9fafb';
                const accentColor = document.getElementById('accent_color').value || '#60a5fa';

                previewDisplayName.textContent = displayName;
                previewTitle.textContent = title;
                previewBio.textContent = bio;
                previewLayout.textContent = layout.replace('_', ' ');
                previewStyle.textContent = style;
                preview.style.background = backgroundColor;
                preview.style.color = textColor;
                preview.style.borderColor = accentColor;
            };

            form.querySelectorAll('input, textarea, select').forEach((field) => field.addEventListener('input', updatePreview));
            form.querySelectorAll('input, textarea, select').forEach((field) => field.addEventListener('change', updatePreview));
            updatePreview();
        }

        const cardFilter = document.getElementById('card-filter');
        const cardFilterCount = document.getElementById('card-filter-count');
        const cardRows = Array.from(document.querySelectorAll('tbody tr[data-card-status]'));

        function applyCardFilter() {
            const selectedFilter = cardFilter?.value || 'all';
            let visibleCount = 0;

            cardRows.forEach((row) => {
                const isVisible = selectedFilter === 'all' || row.dataset.cardStatus === selectedFilter;
                row.hidden = !isVisible;
                if (isVisible) visibleCount++;
            });

            if (cardFilterCount) {
                cardFilterCount.textContent = `${visibleCount} card${visibleCount === 1 ? '' : 's'}`;
            }
        }

        cardFilter?.addEventListener('change', applyCardFilter);
        applyCardFilter();
    </script>
</body>
</html>