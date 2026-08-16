<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Card Studio</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #07111f;
            --panel: #111b2b;
            --text: #f6f7fb;
            --muted: #90a2c7;
            --accent: #ff8c42;
            --line: rgba(255,255,255,0.12);
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Manrope', sans-serif;
            background: linear-gradient(135deg, #0d1526 0%, #121c30 100%);
            color: var(--text);
        }

        .wrap { width: min(1280px, 96%); margin: 0 auto; padding: 24px 0 40px; }
        .top { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 18px; }
        .top h1 { margin: 0; font-size: clamp(1.4rem, 2.5vw, 2rem); }
        .top a { color: #dbe7ff; text-decoration: none; font-weight: 700; }
        .alert { background: rgba(255,140,66,0.22); border: 1px solid rgba(255,140,66,0.35); color: #ffd7b7; padding: 12px 14px; border-radius: 10px; margin-bottom: 16px; }
        .grid { display: grid; grid-template-columns: 1.05fr 0.95fr; gap: 16px; margin-bottom: 16px; }
        .panel { background: var(--panel); border: 1px solid var(--line); border-radius: 16px; padding: 16px; box-shadow: 0 20px 45px rgba(0,0,0,0.2); }
        .panel h2 { margin: 0 0 12px; font-size: 1.02rem; }
        label { display: block; font-size: 0.9rem; color: var(--muted); margin-bottom: 6px; }
        input, select, textarea, button { width: 100%; border-radius: 10px; border: 1px solid var(--line); background: #0d1626; color: var(--text); padding: 10px 11px; font: inherit; }
        textarea { min-height: 92px; resize: vertical; }
        button { cursor: pointer; background: linear-gradient(135deg, #ff8c42, #ff6b3d); color: white; font-weight: 700; border: 0; }
        .row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .stack { display: grid; gap: 12px; }
        .helper { font-size: 0.85rem; color: var(--muted); margin-top: 6px; }
        .meta { margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--line); color: var(--muted); }
        .preview-card { border-radius: 24px; padding: 22px; min-height: 260px; display: flex; flex-direction: column; justify-content: space-between; border: 1px solid rgba(255,255,255,0.2); background: linear-gradient(135deg, #111827, #1f2937); color: white; }
        .preview-card .title { font-size: 1.05rem; font-weight: 700; margin-bottom: 4px; }
        .preview-card .bio { font-size: 0.95rem; line-height: 1.5; color: rgba(255,255,255,0.9); }
        .preview-tags { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 12px; }
        .preview-tags span { display: inline-flex; padding: 6px 10px; border-radius: 999px; background: rgba(255,255,255,0.14); font-size: 0.78rem; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; font-size: 0.9rem; }
        th, td { padding: 8px 6px; border-bottom: 1px solid rgba(255,255,255,0.08); text-align: left; }
        th { color: var(--muted); font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.04em; }
        @media (max-width: 920px) { .grid, .row { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <main class="wrap">
        <header class="top">
            <h1>Admin Card Studio</h1>
            <a href="{{ route('admin.dashboard') }}">Back to dashboard</a>
        </header>

        @if (session('success'))
            <div class="alert">{{ session('success') }}</div>
        @endif

        <section class="grid">
            <article class="panel">
                <h2>Generate and assign a card ID</h2>
                <form method="POST" action="{{ route('admin.cards.generate') }}" class="stack">
                    @csrf
                    <div class="stack">
                        <label for="user_id">Select user</label>
                        <select id="user_id" name="user_id">
                            @foreach ($users as $userOption)
                                <option value="{{ $userOption->id }}" {{ $selectedUser && $selectedUser->id === $userOption->id ? 'selected' : '' }}>
                                    {{ $userOption->name }} ({{ $userOption->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="stack">
                        <label for="name">Card label</label>
                        <input id="name" name="name" placeholder="VIP Card" required>
                    </div>
                    <button type="submit">Generate & assign</button>
                </form>

                <div class="meta">
                    <strong>Current card ID:</strong>
                    {{ $selectedUser?->card_id ?: 'Not assigned yet' }}
                </div>
            </article>

            <article class="panel">
                <h2>Edit profile preview</h2>
                @if ($selectedUser)
                    <form id="adminProfileForm" method="POST" action="{{ route('admin.users.profile.update', $selectedUser) }}" class="stack">
                        @csrf
                        <div class="row">
                            <div class="stack">
                                <label for="display_name">Display name</label>
                                <input id="display_name" name="display_name" value="{{ old('display_name', $profile?->display_name ?? $selectedUser->name) }}" required>
                            </div>
                            <div class="stack">
                                <label for="display_name_font_size">Font size</label>
                                <input id="display_name_font_size" name="display_name_font_size" value="{{ old('display_name_font_size', $profile?->display_name_font_size ?? '24') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="stack">
                                <label for="layout_style">Layout style</label>
                                <select id="layout_style" name="layout_style">
                                    <option value="classic_card" {{ old('layout_style', $profile?->layout_style ?? 'classic_card') === 'classic_card' ? 'selected' : '' }}>Classic card</option>
                                    <option value="wave_split" {{ old('layout_style', $profile?->layout_style ?? 'classic_card') === 'wave_split' ? 'selected' : '' }}>Wave split</option>
                                    <option value="soft_fade" {{ old('layout_style', $profile?->layout_style ?? 'classic_card') === 'soft_fade' ? 'selected' : '' }}>Soft fade</option>
                                    <option value="hihello_card" {{ old('layout_style', $profile?->layout_style ?? 'classic_card') === 'hihello_card' ? 'selected' : '' }}>HiHello style</option>
                                </select>
                            </div>
                            <div class="stack">
                                <label for="card_style">Card style</label>
                                <select id="card_style" name="card_style">
                                    <option value="glass" {{ old('card_style', $profile?->card_style ?? 'glass') === 'glass' ? 'selected' : '' }}>Glass</option>
                                    <option value="clean" {{ old('card_style', $profile?->card_style ?? 'glass') === 'clean' ? 'selected' : '' }}>Clean</option>
                                    <option value="bold" {{ old('card_style', $profile?->card_style ?? 'glass') === 'bold' ? 'selected' : '' }}>Bold</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="stack">
                                <label for="background_pattern">Background pattern</label>
                                <select id="background_pattern" name="background_pattern">
                                    <option value="gradient" {{ old('background_pattern', $profile?->background_pattern ?? 'gradient') === 'gradient' ? 'selected' : '' }}>Gradient</option>
                                    <option value="dots" {{ old('background_pattern', $profile?->background_pattern ?? 'gradient') === 'dots' ? 'selected' : '' }}>Dots</option>
                                    <option value="solid" {{ old('background_pattern', $profile?->background_pattern ?? 'gradient') === 'solid' ? 'selected' : '' }}>Solid</option>
                                </select>
                            </div>
                            <div class="stack">
                                <label for="title">Title</label>
                                <input id="title" name="title" value="{{ old('title', $profile?->title ?? 'Digital Profile') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="stack">
                                <label for="background_color">Background color</label>
                                <input id="background_color" name="background_color" type="color" value="{{ old('background_color', $profile?->background_color ?? '#111827') }}">
                            </div>
                            <div class="stack">
                                <label for="text_color">Text color</label>
                                <input id="text_color" name="text_color" type="color" value="{{ old('text_color', $profile?->text_color ?? '#f9fafb') }}">
                            </div>
                            <div class="stack">
                                <label for="accent_color">Accent color</label>
                                <input id="accent_color" name="accent_color" type="color" value="{{ old('accent_color', $profile?->accent_color ?? '#60a5fa') }}">
                            </div>
                        </div>
                        <div class="stack">
                            <label for="bio">Bio</label>
                            <textarea id="bio" name="bio">{{ old('bio', $profile?->bio ?? 'Edit your profile from the dashboard.') }}</textarea>
                        </div>
                        <button type="submit">Save profile</button>
                    </form>
                @else
                    <p class="helper">Select a user to start editing the live preview.</p>
                @endif
            </article>
        </section>

        <section class="panel">
            <h2>Live profile preview</h2>
            <div class="preview-card" id="profilePreview" style="background: {{ $profile?->background_color ?? '#111827' }}; color: {{ $profile?->text_color ?? '#f9fafb' }}; border-color: {{ $profile?->accent_color ?? '#60a5fa' }};">
                <div>
                    <div class="title" id="previewDisplayName">{{ $profile?->display_name ?? ($selectedUser?->name ?? 'No registration yet') }}</div>
                    <div id="previewTitle">{{ $profile?->title ?? 'Digital Profile' }}</div>
                    <div class="bio" id="previewBio" style="margin-top: 10px;">{{ $profile?->bio ?? 'Edit your profile from the dashboard.' }}</div>
                </div>
                <div class="preview-tags">
                    <span id="previewLayout">{{ ucfirst(str_replace('_', ' ', $profile?->layout_style ?? 'classic card')) }}</span>
                    <span id="previewStyle">{{ ucfirst($profile?->card_style ?? 'glass') }}</span>
                </div>
            </div>
        </section>

        <section class="panel" style="margin-top:16px;">
            <h2>Generated cards</h2>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Card Number</th>
                        <th>Name</th>
                        <th>Assigned To</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cards as $card)
                        @php
                            $assignedUser = \App\Models\User::where('card_id', $card->card_number)->first();
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.cards.update', $card) }}" style="display: flex; gap: 8px; align-items: center;">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="card_number" value="{{ old('card_number', $card->card_number) }}" required style="min-width: 150px;">
                            </td>
                            <td>
                                    <input type="text" name="name" value="{{ old('name', $card->name) }}" required style="min-width: 140px;">
                            </td>
                            <td>{{ $assignedUser ? $assignedUser->name : 'No registration yet' }}</td>
                            <td>{{ $card->created_at?->format('M d, Y h:i A') }}</td>
                            <td>
                                    <button type="submit" style="width: auto; padding: 8px 12px;">Save</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="helper">No cards found.</td></tr>
                    @endforelse
                </tbody>
            </table>
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
    </script>
</body>
</html>