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
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            font-family: 'Manrope', sans-serif;
            color: {{ $profile->text_color }};
            background:
                @if ($profile->background_pattern === 'dots')
                    radial-gradient(circle, {{ $profile->accent_color }}33 1.4px, transparent 1.4px) 0 0 / 18px 18px,
                @endif
                @if ($profile->background_pattern === 'solid')
                    {{ $profile->background_color }};
                @else
                    linear-gradient(135deg, {{ $profile->background_color }}, {{ $profile->accent_color }});
                @endif
        }

        .card {
            width: min(520px, 92%);
            border: 1px solid rgba(255, 255, 255, 0.24);
            border-radius: {{ $profile->card_style === 'clean' ? '10px' : '20px' }};
            padding: 22px;
            backdrop-filter: blur({{ $profile->card_style === 'bold' ? '0px' : '10px' }});
            background: rgba(0, 0, 0, {{ $profile->card_style === 'bold' ? '0.35' : '0.18' }});
            box-shadow: {{ $profile->card_style === 'bold' ? '0 28px 70px rgba(0,0,0,0.48)' : '0 15px 45px rgba(0,0,0,0.3)' }};
        }

        .head {
            display: flex;
            gap: 14px;
            align-items: center;
        }

        .avatar {
            width: 72px;
            height: 72px;
            border-radius: 999px;
            object-fit: cover;
            border: 2px solid {{ $profile->accent_color }};
            background: rgba(255,255,255,0.2);
        }

        h1 {
            margin: 0;
            font-size: clamp(1.5rem, 4vw, 2rem);
        }

        .title {
            margin: 4px 0 0;
            opacity: 0.92;
            font-weight: 600;
        }

        .bio {
            margin: 14px 0 0;
            opacity: 0.95;
            line-height: 1.5;
        }

        .links {
            margin-top: 16px;
            display: grid;
            gap: 9px;
        }

        .link {
            text-decoration: none;
            color: inherit;
            border: 1px solid {{ $profile->accent_color }}80;
            border-radius: 12px;
            padding: 10px 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255,255,255,0.06);
        }

        .foot {
            margin-top: 14px;
            font-size: 0.84rem;
            opacity: 0.88;
        }
    </style>
</head>
<body>
    <article class="card">
        <div class="head">
            @if ($profile->avatar_url)
                <img src="{{ $profile->avatar_url }}" alt="{{ $profile->display_name ?? $user->name }}" class="avatar">
            @else
                <div class="avatar"></div>
            @endif
            <div>
                <h1>{{ $profile->display_name ?? $user->name }}</h1>
                <p class="title">{{ $profile->title }}</p>
            </div>
        </div>

        <p class="bio">{{ $profile->bio }}</p>

        <section class="links" aria-label="Contact links">
            @forelse ($profile->links as $link)
                <a class="link" href="{{ $link->value }}" target="_blank" rel="noopener">
                    <i class="bi {{ $linkTypes[$link->type]['icon'] ?? 'bi-link-45deg' }}"></i>
                    <span>{{ $link->label }}</span>
                </a>
            @empty
                <p>No links yet.</p>
            @endforelse
        </section>

        <p class="foot">Powered by Just A Tap virtual profile.</p>
    </article>
</body>
</html>
