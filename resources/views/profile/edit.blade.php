<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile Builder | Smart Tap</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Manrope', sans-serif;
            color: #f7f9ff;
            background: radial-gradient(circle at 0% 0%, rgba(66, 112, 230, 0.35), transparent 28%), #0c0f17;
        }

        .wrap {
            width: min(1200px, 94%);
            margin: 0 auto;
            padding: 24px 0 36px;
        }

        .head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .head a {
            color: #e6ecff;
            text-decoration: none;
            font-weight: 700;
        }

        h1 {
            margin: 0;
            font-size: clamp(1.6rem, 3.5vw, 2.2rem);
        }

        .grid {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 14px;
        }

        .panel {
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(17, 23, 35, 0.85);
            border-radius: 14px;
            padding: 16px;
        }

        .status,
        .error {
            margin: 0 0 14px;
            border-radius: 10px;
            padding: 9px 11px;
        }

        .status {
            background: rgba(114, 233, 163, 0.12);
            border: 1px solid rgba(114, 233, 163, 0.45);
            color: #d9ffea;
        }

        .error {
            background: rgba(255, 113, 113, 0.12);
            border: 1px solid rgba(255, 113, 113, 0.45);
            color: #ffdede;
        }

        .fields {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .full {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            font-size: 0.86rem;
            color: #cfdaff;
            margin-bottom: 4px;
        }

        input,
        select,
        textarea {
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.04);
            color: #fff;
            padding: 10px;
            font: inherit;
        }

        textarea {
            min-height: 78px;
            resize: vertical;
        }

        button {
            border: 0;
            border-radius: 999px;
            padding: 10px 15px;
            background: #edf2ff;
            color: #101626;
            font-weight: 700;
            cursor: pointer;
        }

        .preview {
            border-radius: 16px;
            padding: 16px;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .preview-head {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .preview-avatar {
            width: 58px;
            height: 58px;
            border-radius: 999px;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.45);
        }

        .preview-avatar.placeholder {
            background: rgba(255, 255, 255, 0.2);
        }

        .preview h2 {
            margin: 0;
            font-size: 1.6rem;
        }

        .preview p {
            margin: 4px 0 0;
            opacity: 0.92;
        }

        .preview-links {
            margin-top: 14px;
            display: grid;
            gap: 8px;
        }

        .preview-link {
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 10px;
            padding: 9px 11px;
            display: flex;
            align-items: center;
            gap: 8px;
            color: inherit;
        }

        .actions {
            display: flex;
            gap: 8px;
            margin-top: 12px;
            flex-wrap: wrap;
        }

        .danger {
            background: rgba(255, 255, 255, 0.11);
            color: #fff;
            padding: 6px 10px;
        }

        .type-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
        }

        .icon-groups {
            display: grid;
            gap: 12px;
            margin-top: 10px;
            max-height: 380px;
            overflow: auto;
            padding-right: 4px;
        }

        .icon-group {
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.02);
        }

        .group-title {
            margin: 0 0 8px;
            font-size: 0.85rem;
            color: #d7e2ff;
            font-weight: 700;
        }

        .icon-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 8px;
        }

        .icon-choice {
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 10px;
            padding: 8px;
            display: flex;
            align-items: center;
            gap: 7px;
            background: rgba(255, 255, 255, 0.03);
            color: #f6f9ff;
            cursor: pointer;
            text-align: left;
        }

        .icon-choice i {
            font-size: 1rem;
        }

        .icon-choice span {
            font-size: 0.82rem;
            line-height: 1.2;
        }

        .icon-choice.is-active {
            border-color: #ff8a3d;
            background: rgba(255, 138, 61, 0.2);
            box-shadow: 0 0 0 2px rgba(255, 138, 61, 0.25) inset;
        }

        .scan-grid {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 14px;
            align-items: center;
            margin-top: 10px;
        }

        .scan-grid img {
            width: 150px;
            height: 150px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            background: #fff;
            padding: 8px;
        }

        .small {
            font-size: 0.84rem;
            color: #c4cee8;
        }

        .avatar-control {
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 10px;
            padding: 10px;
            margin-top: 8px;
        }

        .avatar-control img {
            width: 64px;
            height: 64px;
            object-fit: cover;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            margin-bottom: 8px;
        }

        @media (max-width: 980px) {
            .grid {
                grid-template-columns: 1fr;
            }

            .fields,
            .type-grid,
            .icon-grid,
            .scan-grid {
                grid-template-columns: 1fr;
            }

            .scan-grid img {
                width: 170px;
                height: 170px;
            }
        }
    </style>
</head>
<body>
    @php
        $publicCardUrl = route('profile.public', ['cardId' => $user->card_id]);
        $selectedType = old('type', 'website');
        $selectedTypeMeta = $linkTypes[$selectedType] ?? reset($linkTypes);
        $groupedLinkTypes = collect($linkTypes)->groupBy('category', true);
    @endphp

    <main class="wrap">
        <header class="head">
            <h1>Profile Builder</h1>
            <div>
                <a href="{{ route('home') }}">Home</a>
                <span> | </span>
                <a href="{{ $publicCardUrl }}" target="_blank">View Public Card</a>
            </div>
        </header>

        @if (session('status'))
            <p class="status">{{ session('status') }}</p>
        @endif

        @if ($errors->any())
            <p class="error">{{ $errors->first() }}</p>
        @endif

        <section class="grid">
            <article class="panel">
                <h2>Design & Info</h2>
                <p class="small">Customize your digital profile card and virtual contact details.</p>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="fields">
                        <div>
                            <label for="display_name">Display Name</label>
                            <input id="display_name" type="text" name="display_name" value="{{ old('display_name', $profile->display_name ?? $user->name) }}" required>
                        </div>
                        <div>
                            <label for="title">Role / Title</label>
                            <input id="title" type="text" name="title" value="{{ old('title', $profile->title) }}">
                        </div>
                        <div class="full">
                            <label for="bio">Bio</label>
                            <textarea id="bio" name="bio">{{ old('bio', $profile->bio) }}</textarea>
                        </div>
                        <div class="full">
                            <label for="avatar_image">Profile Picture Upload (optional)</label>
                            <input id="avatar_image" type="file" name="avatar_image" accept="image/png,image/jpeg,image/webp,image/gif">
                            <p class="small">Accepted: JPG, PNG, WEBP, GIF. Maximum size: 4MB.</p>

                            @if ($profile->avatar_url)
                                <div class="avatar-control">
                                    <img src="{{ $profile->avatar_url }}" alt="Current profile picture">
                                    <label style="display:flex;align-items:center;gap:8px;margin:0;">
                                        <input type="checkbox" name="remove_avatar" value="1" style="width:auto;">
                                        Remove current profile picture
                                    </label>
                                </div>
                            @endif
                        </div>
                        <div class="full">
                            <label for="avatar_url">Avatar URL (optional)</label>
                            <p class="small">You can use upload above or paste an image URL here.</p>
                            <input id="avatar_url" type="url" name="avatar_url" value="{{ old('avatar_url', $profile->avatar_url) }}">
                        </div>
                        <div>
                            <label for="background_color">Background Color</label>
                            <input id="background_color" type="color" name="background_color" value="{{ old('background_color', $profile->background_color) }}">
                        </div>
                        <div>
                            <label for="text_color">Text Color</label>
                            <input id="text_color" type="color" name="text_color" value="{{ old('text_color', $profile->text_color) }}">
                        </div>
                        <div>
                            <label for="accent_color">Accent Color</label>
                            <input id="accent_color" type="color" name="accent_color" value="{{ old('accent_color', $profile->accent_color) }}">
                        </div>
                        <div>
                            <label for="card_style">Card Style</label>
                            <select id="card_style" name="card_style">
                                <option value="glass" @selected(old('card_style', $profile->card_style) === 'glass')>Glass</option>
                                <option value="clean" @selected(old('card_style', $profile->card_style) === 'clean')>Clean</option>
                                <option value="bold" @selected(old('card_style', $profile->card_style) === 'bold')>Bold</option>
                            </select>
                        </div>
                        <div class="full">
                            <label for="background_pattern">Background Pattern</label>
                            <select id="background_pattern" name="background_pattern">
                                <option value="gradient" @selected(old('background_pattern', $profile->background_pattern) === 'gradient')>Gradient</option>
                                <option value="dots" @selected(old('background_pattern', $profile->background_pattern) === 'dots')>Dots</option>
                                <option value="solid" @selected(old('background_pattern', $profile->background_pattern) === 'solid')>Solid</option>
                            </select>
                        </div>
                    </div>
                    <div class="actions">
                        <button type="submit">Save Profile</button>
                    </div>
                </form>
            </article>

            <article class="panel">
                <h2>Add Icon Links</h2>
                <p class="small">Choose an icon first, then add your account link, number, or contact info for that icon.</p>

                <form method="POST" action="{{ route('profile.links.add') }}">
                    @csrf
                    <input id="type" name="type" type="hidden" value="{{ $selectedType }}">

                    <div class="icon-groups" aria-label="Choose icon type">
                        @foreach ($groupedLinkTypes as $category => $types)
                            <section class="icon-group">
                                <p class="group-title">{{ $category }}</p>
                                <div class="icon-grid">
                                    @foreach ($types as $type => $meta)
                                        <button
                                            type="button"
                                            class="icon-choice @if ($selectedType === $type) is-active @endif"
                                            data-link-choice
                                            data-type="{{ $type }}"
                                            data-label="{{ $meta['label'] }}"
                                            data-placeholder="{{ $meta['placeholder'] }}"
                                        >
                                            <i class="bi {{ $meta['icon'] }}"></i>
                                            <span>{{ $meta['label'] }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </section>
                        @endforeach
                    </div>

                    <div class="type-grid">
                        <div>
                            <label for="label">Label</label>
                            <input id="label" name="label" type="text" value="{{ old('label') }}" placeholder="{{ $selectedTypeMeta['label'] ?? 'Link Label' }}" required>
                        </div>
                        <div>
                            <label for="value">URL / Contact</label>
                            <input id="value" name="value" type="text" value="{{ old('value') }}" placeholder="{{ $selectedTypeMeta['placeholder'] ?? 'your-link-or-contact' }}" required>
                        </div>
                    </div>
                    <div class="actions">
                        <button type="submit">Add Link</button>
                    </div>
                </form>

                <div class="actions" style="margin-top:14px;"></div>

                @foreach ($profile->links as $link)
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:8px;border:1px solid rgba(255,255,255,0.12);border-radius:10px;padding:8px 10px;">
                        <span><i class="bi {{ $linkTypes[$link->type]['icon'] ?? 'bi-link-45deg' }}"></i> {{ $link->label }}</span>
                        <form method="POST" action="{{ route('profile.links.remove', ['link' => $link->id]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="danger">Remove</button>
                        </form>
                    </div>
                @endforeach
            </article>
        </section>

        <section class="panel" style="margin-top:14px;">
            <h2>Live Preview</h2>
            <div class="preview"
                style="
                    background: {{ $profile->background_pattern === 'solid' ? $profile->background_color : 'linear-gradient(140deg, '.$profile->background_color.', '.$profile->accent_color.')' }};
                    color: {{ $profile->text_color }};
                    box-shadow: {{ $profile->card_style === 'bold' ? '0 25px 60px rgba(0,0,0,0.42)' : '0 12px 30px rgba(0,0,0,0.24)' }};
                    border-radius: {{ $profile->card_style === 'clean' ? '8px' : '16px' }};
                ">
                <div class="preview-head">
                    <img
                        id="preview_avatar"
                        class="preview-avatar @if (!($profile->avatar_url)) placeholder @endif"
                        src="{{ $profile->avatar_url ?? 'data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=' }}"
                        alt="Profile preview avatar"
                    >
                    <div>
                        <h2 id="preview_display_name">{{ $profile->display_name ?? $user->name }}</h2>
                        <p id="preview_title">{{ $profile->title }}</p>
                    </div>
                </div>
                <p id="preview_bio">{{ $profile->bio }}</p>
                <p style="font-size:0.86rem;opacity:0.9;">Card URL: {{ $publicCardUrl }}</p>

                <div class="preview-links">
                    @forelse ($profile->links as $link)
                        <a class="preview-link" href="{{ $link->value }}" target="_blank" rel="noopener" style="border-color: {{ $profile->accent_color }}55;">
                            <i class="bi {{ $linkTypes[$link->type]['icon'] ?? 'bi-link-45deg' }}"></i>
                            <span>{{ $link->label }}</span>
                        </a>
                    @empty
                        <p>Add your first contact link above.</p>
                    @endforelse
                </div>
            </div>

            <div class="actions" style="margin-top: 14px; align-items: stretch;">
                <div style="width:100%;border:1px solid rgba(255,255,255,0.12);border-radius:12px;padding:12px;">
                    <h3 style="margin:0;">Scan Or Tap Access</h3>
                    <p class="small" style="margin-top:6px;">This is the profile link encoded in your card. Users can scan the QR code or tap your NFC card to open your live profile instantly.</p>
                    <div class="scan-grid">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ urlencode($publicCardUrl) }}" alt="QR code for public profile">
                        <div>
                            <label for="public_card_url">Public Card URL</label>
                            <input id="public_card_url" type="text" value="{{ $publicCardUrl }}" readonly>
                            <div class="actions">
                                <button id="copy_card_url" type="button">Copy Card Link</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        const linkTypeInput = document.getElementById('type');
        const labelInput = document.getElementById('label');
        const valueInput = document.getElementById('value');
        const iconChoices = document.querySelectorAll('[data-link-choice]');
        const copyCardUrlButton = document.getElementById('copy_card_url');
        const publicCardUrlInput = document.getElementById('public_card_url');
        const displayNameInput = document.getElementById('display_name');
        const titleInput = document.getElementById('title');
        const bioInput = document.getElementById('bio');
        const avatarUrlInput = document.getElementById('avatar_url');
        const avatarImageInput = document.getElementById('avatar_image');
        const backgroundColorInput = document.getElementById('background_color');
        const textColorInput = document.getElementById('text_color');
        const accentColorInput = document.getElementById('accent_color');
        const cardStyleInput = document.getElementById('card_style');
        const backgroundPatternInput = document.getElementById('background_pattern');

        const previewCard = document.querySelector('.preview');
        const previewName = document.getElementById('preview_display_name');
        const previewTitle = document.getElementById('preview_title');
        const previewBio = document.getElementById('preview_bio');
        const previewAvatar = document.getElementById('preview_avatar');
        const previewLinks = document.querySelectorAll('.preview-link');

        const emptyAvatar = 'data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=';

        function setActiveIcon(choiceButton) {
            iconChoices.forEach((button) => button.classList.remove('is-active'));
            choiceButton.classList.add('is-active');

            const selectedType = choiceButton.dataset.type || 'website';
            const selectedLabel = choiceButton.dataset.label || 'Link';
            const selectedPlaceholder = choiceButton.dataset.placeholder || 'your-link-or-contact';

            linkTypeInput.value = selectedType;
            valueInput.placeholder = selectedPlaceholder;

            if (!labelInput.value.trim()) {
                labelInput.value = selectedLabel;
            }
        }

        iconChoices.forEach((button) => {
            button.addEventListener('click', () => setActiveIcon(button));
        });

        copyCardUrlButton?.addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(publicCardUrlInput.value);
                copyCardUrlButton.textContent = 'Copied';
            } catch (error) {
                publicCardUrlInput.select();
                document.execCommand('copy');
                copyCardUrlButton.textContent = 'Copied';
            }

            setTimeout(() => {
                copyCardUrlButton.textContent = 'Copy Card Link';
            }, 1200);
        });

        function updatePreviewBackground() {
            if (!previewCard) {
                return;
            }

            if (backgroundPatternInput.value === 'solid') {
                previewCard.style.background = backgroundColorInput.value;
            } else {
                previewCard.style.background = `linear-gradient(140deg, ${backgroundColorInput.value}, ${accentColorInput.value})`;
            }
        }

        function updatePreviewStyling() {
            if (!previewCard) {
                return;
            }

            previewCard.style.color = textColorInput.value;

            if (cardStyleInput.value === 'bold') {
                previewCard.style.boxShadow = '0 25px 60px rgba(0,0,0,0.42)';
            } else {
                previewCard.style.boxShadow = '0 12px 30px rgba(0,0,0,0.24)';
            }

            if (cardStyleInput.value === 'clean') {
                previewCard.style.borderRadius = '8px';
            } else {
                previewCard.style.borderRadius = '16px';
            }

            previewLinks.forEach((link) => {
                link.style.borderColor = `${accentColorInput.value}55`;
            });

            updatePreviewBackground();
        }

        displayNameInput?.addEventListener('input', () => {
            previewName.textContent = displayNameInput.value || '{{ $user->name }}';
        });

        titleInput?.addEventListener('input', () => {
            previewTitle.textContent = titleInput.value;
        });

        bioInput?.addEventListener('input', () => {
            previewBio.textContent = bioInput.value;
        });

        avatarUrlInput?.addEventListener('input', () => {
            const nextUrl = avatarUrlInput.value.trim();

            if (nextUrl === '') {
                previewAvatar.src = emptyAvatar;
                previewAvatar.classList.add('placeholder');
                return;
            }

            previewAvatar.src = nextUrl;
            previewAvatar.classList.remove('placeholder');
        });

        avatarImageInput?.addEventListener('change', () => {
            const file = avatarImageInput.files?.[0];

            if (!file) {
                return;
            }

            const reader = new FileReader();

            reader.onload = (event) => {
                const result = event.target?.result;

                if (typeof result === 'string') {
                    previewAvatar.src = result;
                    previewAvatar.classList.remove('placeholder');
                }
            };

            reader.readAsDataURL(file);
        });

        [backgroundColorInput, textColorInput, accentColorInput, cardStyleInput, backgroundPatternInput].forEach((input) => {
            input?.addEventListener('input', updatePreviewStyling);
            input?.addEventListener('change', updatePreviewStyling);
        });
    </script>
</body>
</html>
