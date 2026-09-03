<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $profile->display_name ?? $user->name }} | Smart Tap Card</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @php
        $displayName = $profile->display_name ?? $user->name;
        $displayNameSize = max(18, min(40, (int) ($profile->display_name_font_size ?? 30)));
        $badgeImages = is_array($profile->badge_images) ? array_slice($profile->badge_images, 0, 10) : [];
        $isAvatarVideo = is_string($profile->avatar_url) && preg_match('/\.(mp4|webm|mov|m4v|avi|quicktime)(\?.*)?$/i', $profile->avatar_url);
        $avatarOffsetX = (int) ($profile->avatar_offset_x ?? 0);
        $avatarOffsetY = (int) ($profile->avatar_offset_y ?? 0);
        $layoutStyle = in_array($profile->layout_style, ['classic_card', 'wave_split', 'soft_fade', 'hihello_card'], true)
            ? $profile->layout_style
            : 'classic_card';
        $backgroundPattern = in_array($profile->background_pattern, ['gradient', 'dots', 'solid'], true)
            ? $profile->background_pattern
            : 'gradient';
    @endphp
    <style>
        :root {
            --surface: #ffffff;
            --soft-surface: #f4f5f7;
            --line: #e5e7eb;
            --ink: #2f3238;
            --muted: #6b7280;
            --profile-background: {{ $profile->background_color }};
            --profile-text: {{ $profile->text_color }};
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            padding: 12px;
            display: grid;
            place-items: center;
            font-family: 'Manrope', sans-serif;
            background: #e8ebef;
            color: var(--ink);
        }

        .card-shell {
            width: min(360px, 100%);
            background: var(--surface);
            border: 1px solid #d9dee7;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 22px 44px rgba(11, 15, 24, 0.14);
        }

        .save-contact-wrap {
            padding: 0 12px 12px;
        }

        .save-contact-button {
            width: 100%;
            background: #181d22;
            color: #fff;
            border: 0;
            border-radius: 10px;
            padding: 14px 16px;
            font-size: 1.02rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 12px 26px rgba(15, 23, 42, 0.2);
        }

        .save-contact-button i {
            font-size: 1.05rem;
        }

        .save-contact-link {
            margin-top: 10px;
            display: block;
            text-align: center;
            color: #2d5f8b;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.87rem;
        }

        .contact-modal {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.28);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 100;
            padding: 20px;
        }

        .contact-modal.is-open {
            display: flex;
        }

        .contact-modal-card {
            width: min(560px, 100%);
            background: #f3f5f7;
            border-radius: 18px;
            box-shadow: 0 22px 60px rgba(15, 23, 42, 0.18);
            padding: 20px 20px 18px;
            border: 1px solid rgba(17, 24, 39, 0.05);
        }

        .contact-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 18px;
        }

        .contact-modal-title {
            margin: 0;
            font-size: clamp(1.1rem, 2vw, 2rem);
            color: #1f2937;
            font-weight: 700;
        }

        .contact-modal-close {
            border: 0;
            background: transparent;
            color: #4b5563;
            font-size: 1.4rem;
            cursor: pointer;
            line-height: 1;
        }

        .contact-form {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .field label {
            font-size: 0.92rem;
            color: #4b5563;
            font-weight: 600;
        }

        .field input {
            border: 0;
            border-bottom: 1px solid rgba(17, 24, 39, 0.15);
            background: transparent;
            padding: 10px 0;
            font-size: 1.02rem;
            color: #111827;
            outline: none;
        }

        .field input:focus {
            border-bottom-color: #60a5fa;
        }

        .field .input-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid rgba(17, 24, 39, 0.15);
            padding-right: 6px;
        }

        .field .input-wrap i {
            color: #6b7280;
            font-size: 1.1rem;
            width: 18px;
            text-align: center;
        }

        .field .input-wrap input {
            flex: 1;
            border-bottom: 0;
            padding: 10px 0;
        }

        .continue-button {
            border: 0;
            border-radius: 10px;
            background: #8ec8f7;
            color: #0f172a;
            padding: 14px 18px;
            font-size: 1.15rem;
            font-weight: 800;
            cursor: pointer;
            margin-top: 8px;
        }

        .privacy-note {
            text-align: center;
            color: #4b5563;
            font-size: 0.8rem;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .cover {
            position: relative;
            height: 280px;
            background-color: {{ $profile->background_color }};
            background-image:
                @if ($backgroundPattern === 'dots')
                    radial-gradient(circle, {{ $profile->text_color }} 1px, transparent 1.5px),
                @elseif ($backgroundPattern === 'gradient')
                    linear-gradient(140deg, rgba(255, 255, 255, 0.2), rgba(0, 0, 0, 0.25)),
                @endif
                linear-gradient(180deg, rgba(0, 0, 0, 0.02), rgba(0, 0, 0, 0.18)),
                @if ($profile->avatar_url)
                    @if ($isAvatarVideo)
                        linear-gradient(140deg, {{ $profile->background_color }}, {{ $profile->background_color }});
                    @else
                        url('{{ $profile->avatar_url }}') calc(50% + {{ $avatarOffsetX }}px) calc(50% + {{ $avatarOffsetY }}px) / cover no-repeat;
                    @endif
                @else
                    linear-gradient(140deg, {{ $profile->background_color }}, {{ $profile->background_color }});
                @endif
            @if ($backgroundPattern === 'dots')
                background-size: 14px 14px, cover, cover;
            @else
                background-size: cover;
            @endif
            background-position: center;
        }

        .cover video {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            background: #0f172a;
        }

        .cover::after {
            content: '';
            position: absolute;
            left: -8%;
            right: -8%;
            bottom: -44px;
            height: 92px;
            background: var(--surface);
            border-radius: 50% 50% 0 0;
        }

        .card-shell.layout-wave_split .cover::after {
            left: -20%;
            right: -20%;
            bottom: -48px;
            height: 96px;
            border-radius: 55% 45% 0 0;
            border-top: 8px solid #8f949d;
        }

        .card-shell.layout-soft_fade .cover::after {
            left: 0;
            right: 0;
            bottom: 0;
            height: 126px;
            border-radius: 0;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.98) 56%, #fff 100%);
        }

        .card-shell.layout-hihello_card .cover {
            height: 200px;
        }

        .card-shell.layout-hihello_card .cover::after {
            left: -10%;
            right: -10%;
            bottom: -20px;
            height: 58px;
            background: #eef1f4;
            border-radius: 24px 24px 0 0;
        }

        .card-shell.layout-hihello_card .identity {
            width: calc(100% - 22px);
            margin-top: -18px;
            border-radius: 14px;
            padding-top: 10px;
        }

        .identity {
            position: relative;
            z-index: 1;
            width: calc(100% - 42px);
            margin: -58px auto 0;
            border-radius: 12px;
            background: var(--surface);
            border: 1px solid var(--line);
            box-shadow: 0 12px 26px rgba(0, 0, 0, 0.16);
            text-align: center;
            padding: 14px 12px 10px;
        }

        .card-shell.layout-wave_split .identity {
            width: calc(100% - 28px);
            margin-top: -36px;
            border: 0;
            box-shadow: none;
            border-radius: 0;
            background: transparent;
            text-align: left;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 8px 0 6px;
        }

        .card-shell.layout-soft_fade .identity {
            width: calc(100% - 36px);
            margin-top: -74px;
            border: 0;
            box-shadow: none;
            border-radius: 0;
            background: transparent;
            text-align: left;
            padding: 8px 0;
        }

        .identity-logo {
            width: 42px;
            height: 42px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #d5dae1;
            background: #eef0f3;
            margin-bottom: 8px;
        }

        .card-shell.layout-wave_split .identity-logo {
            margin-bottom: 0;
            width: 56px;
            height: 56px;
            border-radius: 10px;
        }

        .card-shell.layout-soft_fade .identity-logo {
            position: absolute;
            right: 0;
            top: -40px;
            width: 56px;
            height: 56px;
            margin-bottom: 0;
        }

        .identity-name {
            margin: 0;
            font-size: {{ $displayNameSize }}px;
            line-height: 1.12;
            font-weight: 800;
            color: var(--profile-text);
        }

        .card-shell.layout-wave_split .identity-name,
        .card-shell.layout-soft_fade .identity-name {
            font-size: min({{ $displayNameSize }}px, 2rem);
        }

        .identity-title {
            margin: 5px 0 0;
            font-size: 0.98rem;
            font-weight: 500;
            color: var(--profile-text);
        }

        .identity-bio {
            margin: 6px auto 0;
            max-width: 90%;
            font-size: 0.82rem;
            color: var(--profile-text);
            line-height: 1.35;
        }

        .card-shell.layout-wave_split .identity-bio,
        .card-shell.layout-soft_fade .identity-bio {
            max-width: 100%;
            margin-left: 0;
            margin-right: 0;
        }

        @media (max-width: 430px) {
            body {
                padding: calc(env(safe-area-inset-top, 0px) + 12px) calc(env(safe-area-inset-right, 0px) + 10px) calc(env(safe-area-inset-bottom, 0px) + 16px) calc(env(safe-area-inset-left, 0px) + 10px);
            }

            .card-shell {
                width: min(100%, 360px);
                border-radius: 20px;
            }

            .cover {
                height: 220px;
            }

            .identity {
                width: calc(100% - 18px);
                margin-top: -42px;
                padding: 12px 10px 10px;
                border-radius: 16px;
            }

            .identity-name {
                font-size: clamp(1.4rem, 6vw, 2rem);
            }

            .links {
                padding-left: 8px;
                padding-right: 8px;
            }

            .link-item {
                gap: 8px;
                padding: 10px 6px;
            }
        }

        .identity-copy {
            min-width: 0;
        }

        .links {
            list-style: none;
            margin: 16px 0 0;
            padding: 0 10px 12px;
            display: grid;
            gap: 2px;
        }

        .link-item {
            display: flex;
            align-items: center;
            gap: 12px;
            border-radius: 12px;
            padding: 10px 10px;
            text-decoration: none;
            color: var(--profile-text);
            transition: background-color 0.18s ease, transform 0.18s ease;
        }

        .link-item:hover {
            background: #f5f7fa;
            transform: translateY(-1px);
        }

        .link-icon {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            background: transparent;
            border: 2px solid var(--profile-background);
            color: var(--profile-background);
            display: grid;
            place-items: center;
            font-size: 1.05rem;
            flex: 0 0 34px;
        }

        .link-label {
            font-size: 1.02rem;
            font-weight: 500;
            line-height: 1.3;
            word-break: break-word;
        }

        .video-block {
            padding: 0 10px 12px;
        }

        .video-block video {
            display: block;
            width: 100%;
            border-radius: 12px;
            border: 1px solid var(--line);
            background: #0f172a;
        }

        .badges {
            border-top: 1px solid var(--line);
            background: var(--soft-surface);
            padding: 12px 10px 14px;
        }

        .badges h2 {
            margin: 0 0 8px;
            font-size: 0.8rem;
            letter-spacing: 0.08em;
            color: #6a7280;
            text-transform: uppercase;
        }

        .badge-album {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 6px;
        }

        .badge-album img {
            width: 100%;
            aspect-ratio: 1;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #d7dbe3;
            background: #fff;
        }

        .empty {
            margin: 12px 0 0;
            padding: 0 16px 16px;
            color: #727987;
            font-size: 0.92rem;
        }

        .foot {
            margin: 0;
            padding: 0 0 14px;
            text-align: center;
            font-size: 0.76rem;
            color: #8a93a3;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
                display: block;
            }

            .card-shell {
                width: 360px;
                margin: 0 auto;
                box-shadow: none;
                border: 1px solid #d9dee7;
            }

            .contact-modal {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <article id="profile-card" class="card-shell layout-{{ $layoutStyle }}">
        <div class="cover">
            @if ($profile->avatar_url && $isAvatarVideo)
                <video autoplay muted loop playsinline>
                    <source src="{{ $profile->avatar_url }}">
                </video>
            @endif
        </div>

        <section class="identity">
            <div class="identity-copy">
                <h1 class="identity-name">{{ $displayName }}</h1>
                <p class="identity-title">{{ $profile->title }}</p>
                @if ($profile->bio)
                    <p class="identity-bio">{{ $profile->bio }}</p>
                @endif
            </div>
            @if ($profile->logo_url)
                <img src="{{ $profile->logo_url }}" alt="{{ $displayName }} logo" class="identity-logo">
            @endif
        </section>

        <section aria-label="Contact links">
            @if ($profile->links->count() > 0)
                <ul class="links">
                    @foreach ($profile->links as $link)
                        <li>
                            <a class="link-item" href="{{ $link->value }}" target="_blank" rel="noopener">
                                <span class="link-icon"><i class="bi {{ $linkTypes[$link->type]['icon'] ?? 'bi-link-45deg' }}"></i></span>
                                <span class="link-label">{{ $link->label }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="empty">No links yet.</p>
            @endif
        </section>

        @if (!empty($badgeImages))
            <section class="badges" aria-label="Badge album">
                <h2>Achievements</h2>
                <div class="badge-album">
                    @foreach ($badgeImages as $badge)
                        <img src="{{ $badge }}" alt="Achievement badge">
                    @endforeach
                </div>
            </section>
        @endif

        <p class="foot">Powered by Just A Tap</p>
    </article>

    <div class="save-contact-wrap">
        <button type="button" class="save-contact-button" id="save-contact-button">
            <i class="bi bi-person-vcard"></i>
            Save Contact
        </button>
    </div>

    <div class="contact-modal" id="contact-modal" aria-modal="true" role="dialog">
        <div class="contact-modal-card">
            <div class="contact-modal-header">
                <h2 class="contact-modal-title">Share your information with {{ $displayName }}.</h2>
                <button type="button" class="contact-modal-close" id="contact-modal-close" aria-label="Close">×</button>
            </div>

            <form class="contact-form" id="contact-form">
                <div class="field">
                    <label for="contact-name">Your name</label>
                    <input id="contact-name" name="name" type="text" placeholder="Your name" value="{{ auth()->user()?->name ?? '' }}">
                </div>

                <div class="field">
                    <label for="contact-email">Your email</label>
                    <div class="input-wrap">
                        <i class="bi bi-envelope"></i>
                        <input id="contact-email" name="email" type="email" placeholder="Your email">
                    </div>
                </div>

                <div class="field">
                    <label for="contact-phone">Your phone number</label>
                    <div class="input-wrap">
                        <i class="bi bi-telephone"></i>
                        <input id="contact-phone" name="phone" type="tel" placeholder="Your phone number">
                    </div>
                </div>

                <button type="submit" class="continue-button">Continue</button>
                <div class="privacy-note"><i class="bi bi-shield-lock"></i> Your data will always be kept private.</div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('contact-modal');
        const openButton = document.getElementById('save-contact-button');
        const closeButton = document.getElementById('contact-modal-close');
        const form = document.getElementById('contact-form');

        const escapeHtml = (value = '') => value
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/\"/g, '&quot;')
            .replace(/'/g, '&#039;');

        const createVCard = (name, email, phone) => {
            const safeName = (name || '{{ $displayName }}').trim() || '{{ $displayName }}';
            const safeEmail = (email || '').trim();
            const safePhone = (phone || '').trim();
            const vcard = [
                'BEGIN:VCARD',
                'VERSION:3.0',
                `FN:${safeName}`,
                `N:${safeName.split(' ').reverse().join(';')};${safeName.split(' ').slice(0, -1).join(' ')};;`,
            ];

            if (safeEmail) {
                vcard.push(`EMAIL:${safeEmail}`);
            }

            if (safePhone) {
                vcard.push(`TEL;TYPE=CELL:${safePhone}`);
            }

            vcard.push('END:VCARD');
            return vcard.join('\r\n');
        };

        const triggerDownload = (name, email, phone) => {
            const vcard = createVCard(name, email, phone);
            const blob = new Blob([vcard], { type: 'text/vcard;charset=utf-8' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = `${(name || '{{ $displayName }}').replace(/\s+/g, '-').toLowerCase() || 'contact'}.vcf`;
            document.body.appendChild(link);
            link.click();
            link.remove();
            setTimeout(() => URL.revokeObjectURL(url), 1000);
        };

        const sendContactShare = (name, email, phone) => {
            const trimmedEmail = (email || '').trim();
            if (!trimmedEmail) {
                return;
            }

            fetch('{{ route('profile.public.share', ['cardId' => $user->card_id]) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    name: name || '',
                    email: trimmedEmail,
                    phone: phone || '',
                })
            }).catch(() => {
                triggerDownload(name, email, phone);
            });
        };

        const closeModal = () => {
            modal.classList.remove('is-open');
        };

        if (openButton) {
            openButton.addEventListener('click', () => modal.classList.add('is-open'));
        }

        if (closeButton) {
            closeButton.addEventListener('click', () => {
                const name = document.getElementById('contact-name')?.value || '';
                const email = document.getElementById('contact-email')?.value || '';
                const phone = document.getElementById('contact-phone')?.value || '';
                sendContactShare(name, email, phone);
                triggerDownload(name, email, phone);
                closeModal();
            });
        }

        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                const name = document.getElementById('contact-name')?.value || '';
                const email = document.getElementById('contact-email')?.value || '';
                const phone = document.getElementById('contact-phone')?.value || '';
                sendContactShare(name, email, phone);
                triggerDownload(name, email, phone);
                closeModal();
            }
        });

        if (form) {
            form.addEventListener('submit', (event) => {
                event.preventDefault();
                const name = document.getElementById('contact-name')?.value || '';
                const email = document.getElementById('contact-email')?.value || '';
                const phone = document.getElementById('contact-phone')?.value || '';
                sendContactShare(name, email, phone);
                triggerDownload(name, email, phone);
                closeModal();
            });
        }

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && modal.classList.contains('is-open')) {
                const name = document.getElementById('contact-name')?.value || '';
                const email = document.getElementById('contact-email')?.value || '';
                const phone = document.getElementById('contact-phone')?.value || '';
                sendContactShare(name, email, phone);
                triggerDownload(name, email, phone);
                closeModal();
            }
        });
    </script>
</body>
</html>
