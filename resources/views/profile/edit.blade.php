<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile Builder | Smart Tap</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Manrope:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Manrope', 'sans-serif'],
                        display: ['Space Grotesk', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                    colors: {
                        surface: '#0a0a0b',
                        card: '#111113',
                        border: 'rgba(255,255,255,0.06)',
                        'border-light': 'rgba(255,255,255,0.10)',
                    },
                    animation: {
                        'fade-up': 'fadeUp 0.5s cubic-bezier(0.16, 1, 0.3, 1)',
                        'glow-pulse': 'glowPulse 3s ease-in-out infinite',
                        'slide-down': 'slideDown 0.3s cubic-bezier(0.16, 1, 0.3, 1)',
                        'shimmer': 'shimmer 4s ease-in-out infinite',
                        'border-glow': 'borderGlow 3s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeUp: {
                            '0%': { opacity: '0', transform: 'translateY(12px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        glowPulse: {
                            '0%, 100%': { opacity: '0.6' },
                            '50%': { opacity: '1' },
                        },
                        slideDown: {
                            '0%': { opacity: '0', transform: 'translateY(-8px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        shimmer: {
                            '0%, 100%': { backgroundPosition: '0% 50%' },
                            '50%': { backgroundPosition: '100% 50%' },
                        },
                        borderGlow: {
                            '0%, 100%': { borderColor: 'rgba(255,255,255,0.06)' },
                            '50%': { borderColor: 'rgba(255,255,255,0.18)' },
                        },
                    },
                },
            },
        };
    </script>

    <style>
        * {
            box-sizing: border-box;
        }
        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: #050506;
            position: relative;
            font-family: 'Manrope', sans-serif;
            color: #f0f0f0;
        }

        /* ——— Ambient grid ——— */
        /* body::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.025) 1px, transparent 1px);
            background-size: 48px 48px;
            -webkit-mask-image: radial-gradient(1200px 700px at 50% 0%, #000 0%, transparent 80%);
            mask-image: radial-gradient(1200px 700px at 50% 0%, #000 0%, transparent 80%);
        } */

        /* ——— Subtle radial glow ——— */
        body::after {
            content: '';
            position: fixed;
            top: -40%;
            left: 50%;
            transform: translateX(-50%);
            width: 900px;
            height: 900px;
            background: radial-gradient(circle at center, rgba(160, 160, 180, 0.06) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
            border-radius: 50%;
        }

        ::selection {
            background: #888;
            color: #000;
        }

        /* ——— Utility ——— */
        .glass {
            background: rgba(17, 17, 19, 0.72);
            backdrop-filter: blur(18px) saturate(1.2);
            -webkit-backdrop-filter: blur(18px) saturate(1.2);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .glass-light {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px) saturate(1.1);
            -webkit-backdrop-filter: blur(12px) saturate(1.1);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .glow-ring {
            box-shadow: 0 0 60px -20px rgba(160, 160, 170, 0.15);
        }

        /* ——— Web3 accent glow ——— */
        .accent-glow {
            position: relative;
        }
        .accent-glow::before {
            content: '';
            position: absolute;
            inset: -1px;
            border-radius: inherit;
            padding: 1px;
            background: linear-gradient(135deg, rgba(255,255,255,0.12), rgba(255,255,255,0.02), rgba(255,255,255,0.08));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }

        /* ——— Tab states ——— */
        .tab-pane {
            display: none;
        }
        .tab-pane.is-active {
            display: block;
            animation: fadeUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .tab-button.is-active {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.07);
            border-color: rgba(255, 255, 255, 0.18);
        }

        /* ——— Removed underline from tab buttons ——— */

        .icon-choice.is-active {
            border-color: #ffffff !important;
            background: rgba(255, 255, 255, 0.08) !important;
            box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.25) inset;
        }
        .icon-choice.is-active i {
            color: #ffffff !important;
        }

        .status-dot,
        .error-dot {
            animation: pulseDot 2s ease-in-out infinite;
        }
        @keyframes pulseDot {
            0%,
            100% {
                opacity: 1;
            }
            50% {
                opacity: .3;
            }
        }

        .icon-groups {
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.15) transparent;
        }
        .icon-groups::-webkit-scrollbar {
            width: 5px;
        }
        .icon-groups::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 999px;
        }

        /* ——— Preview card (unchanged surface) ——— */
        .preview {
            width: min(380px, 100%);
            margin: 0 auto;
            border-radius: 18px;
            border: 1px solid #d8deea;
            background: #f3f4f6;
            overflow: hidden;
            color: #2f3238;
            box-shadow: 0 18px 32px rgba(0, 0, 0, 0.28);
        }

        .preview-cover {
            position: relative;
            height: 225px;
            background: linear-gradient(140deg, #9199a6, #4d5057);
            background-size: cover;
            background-position: center;
            overflow: hidden;
        }

        .preview-avatar-stage {
            position: absolute;
            inset: 0;
            overflow: hidden;
            z-index: 1;
            pointer-events: none;
        }

        .preview-avatar-media {
            position: absolute;
            left: 50%;
            top: 50%;
            width: 140%;
            height: 140%;
            object-fit: cover;
            transform: translate(calc(-50% + var(--avatar-x, 0px)), calc(-50% + var(--avatar-y, 0px)));
            border: none;
            display: none;
            pointer-events: auto;
            cursor: grab;
            user-select: none;
            -webkit-user-drag: none;
        }

        .preview-avatar-media.dragging {
            cursor: grabbing;
        }

        .preview-cover::after {
            content: '';
            position: absolute;
            left: -8%;
            right: -8%;
            bottom: -26px;
            height: 72px;
            background: #f3f4f6;
            border-radius: 50% 50% 0 0;
        }

        .preview.layout-wave_split .preview-cover::after {
            left: -20%;
            right: -20%;
            bottom: -48px;
            height: 96px;
            border-radius: 55% 45% 0 0;
            border-top: 8px solid #9199a3;
        }

        .preview.layout-soft_fade .preview-cover::after {
            left: 0;
            right: 0;
            bottom: 0;
            height: 126px;
            border-radius: 0;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.98) 56%, #fff 100%);
        }

        .preview.layout-hihello_card .preview-cover {
            height: 200px;
        }

        .preview.layout-hihello_card .preview-cover::after {
            bottom: -20px;
            height: 56px;
            background: #f3f4f6;
        }

        .preview.layout-hihello_card .preview-identity {
            width: calc(100% - 25px);
            margin: -18px auto 10px;
            border-radius: 14px;
            padding: 10px 10px 8px;
            background: rgba(255, 255, 255, 0.96);
        }

        .preview.layout-hihello_card .preview-badges-wrap {
            padding-top: 8px;
        }

        .preview-identity {
            position: relative;
            z-index: 1;
            width: calc(100% - 30px);
            margin: -30px auto 8px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid #e2e7ef;
            text-align: center;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.12);
            padding: 12px 10px 10px;
        }

        .preview.layout-wave_split .preview-identity {
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

        .preview.layout-soft_fade .preview-identity {
            width: calc(100% - 36px);
            margin-top: -74px;
            border: 0;
            box-shadow: none;
            border-radius: 0;
            background: transparent;
            text-align: left;
            padding: 8px 0;
        }

        .preview-logo {
            width: 42px;
            height: 42px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #d7dce4;
            background: #eceff4;
            margin-bottom: 7px;
        }

        .preview.layout-wave_split .preview-logo {
            margin-bottom: 0;
            width: 56px;
            height: 56px;
            border-radius: 10px;
        }

        .preview.layout-soft_fade .preview-logo {
            position: absolute;
            right: 0;
            top: -40px;
            width: 56px;
            height: 56px;
            margin-bottom: 0;
        }

        .preview-logo.placeholder {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.62rem;
            letter-spacing: 0.04em;
            color: #6d7481;
            text-transform: uppercase;
        }

        .preview-name {
            margin: 0;
            line-height: 1.15;
            color: #1c1e22;
            font-weight: 800;
        }
        .preview-title {
            margin: 4px 0 0;
            color: #45484f;
            font-weight: 500;
        }
        .preview-bio {
            margin: 6px auto 0;
            max-width: 92%;
            color: #6d7078;
            font-size: 0.82rem;
            line-height: 1.35;
        }

        .preview.layout-wave_split .preview-bio,
        .preview.layout-soft_fade .preview-bio {
            max-width: 100%;
            margin-left: 0;
            margin-right: 0;
        }

        .preview-copy {
            min-width: 0;
        }

        .preview-links {
            margin: 0;
            padding: 0 12px 10px;
            display: grid;
            gap: 4px;
        }

        .preview-link {
            text-decoration: none;
            border: 0;
            border-radius: 10px;
            padding: 10px 8px;
            display: flex;
            align-items: center;
            gap: 9px;
            color: #2f3238;
            transition: background .15s ease;
        }
        .preview-link:hover {
            background: rgba(0, 0, 0, 0.04);
        }

        .preview-link i {
            width: 32px;
            height: 32px;
            border-radius: 999px;
            display: grid;
            place-items: center;
            background: #34363b;
            color: #fff;
            flex-shrink: 0;
        }

        .preview-link span {
            line-height: 1.25;
        }

        .preview-badges-wrap {
            border-top: 1px solid #e2e7ef;
            background: transparent;
            padding: 10px 12px 14px;
        }

        .preview-badges-title {
            margin: 0 0 8px;
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #6d7685;
            font-weight: 700;
        }

        .preview-badges {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 7px;
        }

        .preview-badge {
            width: 100%;
            aspect-ratio: 1;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #d7dce4;
            background: #fff;
        }

        /* ——— Mobile nav toggle ——— */
        #mobileMenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.3s ease, visibility 0.3s ease;
            opacity: 0;
            visibility: hidden;
        }
        #mobileMenu.open {
            max-height: 480px;
            opacity: 1;
            visibility: visible;
        }

        /* ——— QR code responsive ——— */
        .qr-container {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .qr-container .qr-image {
            width: 88px;
            height: 88px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease;
            flex-shrink: 0;
        }
        .qr-container .qr-image:hover {
            transform: scale(1.03);
        }
        .qr-container .qr-details {
            flex: 1;
            min-width: 140px;
        }
        .qr-container .qr-details .qr-url {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.3);
            word-break: break-all;
            line-height: 1.4;
            max-width: 220px;
        }
        .qr-container .qr-details .qr-copy-btn {
            margin-top: 4px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(255, 255, 255, 0.03);
            padding: 4px 14px;
            font-size: 0.65rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .qr-container .qr-details .qr-copy-btn:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
            border-color: rgba(255, 255, 255, 0.2);
        }

        @media (min-width: 640px) {
            .qr-container .qr-image {
                width: 100px;
                height: 100px;
            }
            .qr-container .qr-details .qr-url {
                max-width: 280px;
                font-size: 0.75rem;
            }
        }

        @media (min-width: 768px) {
            .qr-container .qr-image {
                width: 110px;
                height: 110px;
            }
            .qr-container .qr-details .qr-url {
                max-width: 340px;
            }
        }

        @media (min-width: 1024px) {
            .qr-container .qr-image {
                width: 120px;
                height: 120px;
            }
        }

        /* ——— Responsive tweaks ——— */
        @media (max-width: 640px) {
            .preview {
                width: 100%;
            }
            .preview-cover {
                height: 180px;
            }
            .preview-identity {
                padding: 10px 8px 8px;
            }
            .qr-container .qr-image {
                width: 72px;
                height: 72px;
            }
        }

        @media (min-width: 641px) and (max-width: 1024px) {
            .preview {
                width: min(340px, 100%);
            }
        }

        /* ——— Design & Info form styling ——— */
        .form-section-title {
            font-size: 0.6rem;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: rgba(255, 255, 255, 0.2);
            font-weight: 700;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            margin-bottom: 0.75rem;
        }

        .field-group {
            border: 1px solid rgba(255, 255, 255, 0.04);
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            background: rgba(255, 255, 255, 0.015);
            transition: border-color 0.2s ease;
        }
        .field-group:hover {
            border-color: rgba(255, 255, 255, 0.08);
        }

        .field-group .label-wrap {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            margin-bottom: 0.25rem;
        }
        .field-group .label-wrap i {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.2);
        }
        .field-group .label-wrap label {
            font-size: 0.7rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.45);
            letter-spacing: 0.02em;
        }

        .field-group input,
        .field-group select,
        .field-group textarea {
            background: transparent !important;
            border: none !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            font-size: 0.85rem !important;
            color: #fff !important;
            width: 100%;
            outline: none;
        }
        .field-group input::placeholder,
        .field-group textarea::placeholder {
            color: rgba(255, 255, 255, 0.15);
        }
        .field-group input:focus,
        .field-group select:focus,
        .field-group textarea:focus {
            outline: none;
            box-shadow: none;
        }

        .field-group select option {
            background: #111113;
            color: #fff;
        }

        /* color picker inline */
        .color-picker-wrap {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .color-picker-wrap input[type="color"] {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.08);
            padding: 2px;
            cursor: pointer;
            background: transparent;
            flex-shrink: 0;
        }
        .color-picker-wrap input[type="color"]::-webkit-color-swatch-wrapper {
            padding: 0;
        }
        .color-picker-wrap input[type="color"]::-webkit-color-swatch {
            border: none;
            border-radius: 50%;
        }
        .color-picker-wrap .color-label {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.3);
        }

        /* file upload inline */
        .file-upload-wrap {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        .file-upload-wrap input[type="file"] {
            font-size: 0.7rem !important;
            color: rgba(255, 255, 255, 0.3) !important;
        }
        .file-upload-wrap input[type="file"]::file-selector-button {
            border: none;
            background: rgba(255, 255, 255, 0.06);
            color: rgba(255, 255, 255, 0.6);
            padding: 0.3rem 0.9rem;
            border-radius: 999px;
            font-size: 0.65rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s ease;
            margin-right: 0.5rem;
        }
        .file-upload-wrap input[type="file"]::file-selector-button:hover {
            background: rgba(255, 255, 255, 0.12);
        }

        .file-hint {
            font-size: 0.6rem;
            color: rgba(255, 255, 255, 0.15);
            margin-top: 0.2rem;
        }

        /* compact media preview */
        .media-preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(56px, 1fr));
            gap: 0.5rem;
            margin-top: 0.5rem;
        }
        .media-preview-grid .preview-item {
            aspect-ratio: 1;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.06);
            overflow: hidden;
            background: rgba(255, 255, 255, 0.02);
            position: relative;
        }
        .media-preview-grid .preview-item img,
        .media-preview-grid .preview-item video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .media-preview-grid .preview-item .remove-btn {
            position: absolute;
            top: 2px;
            right: 2px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: none;
            background: rgba(0, 0, 0, 0.6);
            color: #fff;
            font-size: 10px;
            line-height: 1;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s ease;
        }
        .media-preview-grid .preview-item .remove-btn:hover {
            background: rgba(200, 50, 50, 0.8);
        }
    </style>
</head>
<body class="font-sans antialiased">

    @php
        $publicCardUrl = route('profile.public', ['cardId' => $user->card_id]);
        $selectedType = old('type', 'website');
        $selectedTypeMeta = $linkTypes[$selectedType] ?? reset($linkTypes);
        $groupedLinkTypes = collect($linkTypes)->groupBy('category', true);
        $layoutStyle = old('layout_style', $profile->layout_style ?? 'classic_card');
        $qrSize = '200x200';
    @endphp

    <!-- ============================================================ -->
    <!--  STICKY HEADER                                               -->
    <!-- ============================================================ -->
    <header class="sticky top-0 z-50 w-full border-b border-white/5 bg-[#080809]/80 backdrop-blur-2xl saturate-150">
        <div class="mx-auto w-[94%] max-w-[1200px] px-3 py-3 md:py-4">

            <div class="flex items-center justify-between gap-3">

                <div class="flex items-center gap-3 shrink-0">
    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' fill='%23333'/%3E%3C/svg%3E" 
         alt="Smart Tap Logo" 
         class="h-12 w-12 rounded-xl object-cover border border-white/5">
</div>

                <!-- Desktop Nav -->
                <nav class="hidden md:flex items-center gap-0.5 text-sm font-medium" aria-label="Main navigation">
                    @if (! $adminEditor)
                        <button class="tab-button inline-flex items-center gap-2 rounded-full border border-transparent px-4 py-2 text-white/40 transition hover:text-white hover:bg-white/5" data-tab="personal-info">
                            <span>Account</span>
                        </button>
                    @endif
                    <button class="tab-button inline-flex items-center gap-2 rounded-full border border-transparent px-4 py-2 text-white/40 transition hover:text-white hover:bg-white/5" data-tab="design-info">
                        <span>Design</span>
                    </button>
                    <button class="tab-button is-active inline-flex items-center gap-2 rounded-full border border-transparent px-4 py-2 text-white/40 transition hover:text-white hover:bg-white/5" data-tab="profile-links">
                        <span>Links</span>
                    </button>
                </nav>

                <!-- Right actions -->
                <div class="flex items-center gap-2 shrink-0">

                    <a href="{{ route('home') }}" class="hidden md:inline-flex items-center gap-2 rounded-full border border-white/10 px-4 py-2 text-sm font-semibold text-white/50 transition hover:border-white/30 hover:bg-white/5 hover:text-white">
                        <span class="hidden lg:inline">Home</span>
                    </a>

                    <a href="{{ $publicCardUrl }}" target="_blank" class="hidden md:inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-sm font-bold text-black transition hover:bg-white/90 active:scale-[0.97]">
                        <i class="bi bi-box-arrow-up-right"></i>
                        <span class="hidden lg:inline">View Card</span>
                    </a>

                    <a href="{{ $publicCardUrl }}" target="_blank" class="md:hidden inline-flex h-9 w-9 items-center justify-center rounded-full border border-white/10 text-white/50 transition hover:border-white/30 hover:bg-white/5 hover:text-white" aria-label="View public card">
                        <i class="bi bi-box-arrow-up-right text-sm"></i>
                    </a>

                    <button id="menuToggle" class="md:hidden inline-flex h-9 w-9 items-center justify-center rounded-full border border-white/10 text-white/50 transition hover:border-white/30 hover:bg-white/5 hover:text-white" aria-label="Toggle menu" aria-expanded="false">
                        <i id="menuIcon" class="bi bi-list text-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile dropdown -->
            <div id="mobileMenu" class="md:hidden mt-3 border-t border-white/5 pt-3">
                <nav class="flex flex-col gap-1 text-sm font-medium" aria-label="Mobile navigation">
                    @if (! $adminEditor)
                        <button class="tab-button flex items-center gap-3 rounded-xl border border-transparent px-4 py-3 text-white/50 transition hover:text-white hover:bg-white/5" data-tab="personal-info">
                            <span>Account Settings</span>
                        </button>
                    @endif
                    <button class="tab-button flex items-center gap-3 rounded-xl border border-transparent px-4 py-3 text-white/50 transition hover:text-white hover:bg-white/5" data-tab="design-info">
                        <span>Design &amp; Info</span>
                    </button>
                    <button class="tab-button is-active flex items-center gap-3 rounded-xl border border-transparent px-4 py-3 text-white/50 transition hover:text-white hover:bg-white/5" data-tab="profile-links">
                        <span>Profile Links</span>
                    </button>
                    <div class="mt-2 flex flex-wrap gap-2 border-t border-white/5 pt-3">
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 rounded-full border border-white/10 px-4 py-2 text-sm font-semibold text-white/50 transition hover:border-white/30 hover:bg-white/5 hover:text-white">
                            </i> Home
                        </a>
                        <a href="{{ $publicCardUrl }}" target="_blank" class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-sm font-bold text-black transition hover:bg-white/90">
                            <i class="bi bi-box-arrow-up-right"></i> View Card
                        </a>
                    </div>
                </nav>
            </div>
        </div>
    </header>

    <!-- ============================================================ -->
    <!--  MAIN CONTENT                                                -->
    <!-- ============================================================ -->
    <main class="relative z-10 mx-auto w-[94%] max-w-[1200px] py-6 pb-12 md:py-8">

        <!-- Status & Error -->
        @if (session('status'))
            <p class="relative mb-5 flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-5 py-3.5 text-sm text-white/80 backdrop-blur-sm animate-fade-up">
                <span class="status-dot h-2 w-2 shrink-0 rounded-full bg-white"></span>
                {{ session('status') }}
            </p>
        @endif

        @if ($errors->any())
            <p class="relative mb-5 flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-5 py-3.5 text-sm font-semibold text-white backdrop-blur-sm animate-fade-up">
                <span class="error-dot h-2 w-2 shrink-0 rounded-full bg-white"></span>
                {{ $errors->first() }}
            </p>
        @endif

        <!-- ========================================================== -->
        <!--  TAB: PERSONAL INFO                                       -->
        <!-- ========================================================== -->
        @if (! $adminEditor)
            <section class="tab-pane" id="personal-info-pane" data-tab-content="personal-info">
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-[1.1fr_0.9fr]">

                    <!-- Left: Preview + QR -->
                    <div class="grid content-start gap-5">
                        <div class="glass glow-ring sticky top-24 rounded-2xl border border-white/5 p-5 shadow-2xl shadow-black/30 accent-glow">
                            <h3 class="font-mono text-[0.55rem] uppercase tracking-[0.2em] text-white/30 mb-4 flex items-center gap-2">
                                <span class="inline-block w-1.5 h-1.5 rounded-full bg-white/20"></span>
                                Live Preview
                            </h3>
                            <div class="preview layout-{{ $layoutStyle }}">
                                <div id="preview_cover" class="preview-cover">
                                    <div class="preview-avatar-stage">
                                        <img id="preview_avatar_media" class="preview-avatar-media" alt="Profile avatar preview">
                                    </div>
                                </div>

                                <div class="preview-identity">
                                    <div class="preview-copy">
                                        <h2 id="preview_display_name" class="preview-name" style="font-size: {{ $profile->display_name_font_size ?? 24 }}px;">{{ $profile->display_name ?? $user->name }}</h2>
                                        <p id="preview_title" class="preview-title">{{ $profile->title }}</p>
                                        <p id="preview_bio" class="preview-bio">{{ $profile->bio }}</p>
                                    </div>
                                    <img id="preview_logo" class="preview-logo @if (!($profile->logo_url)) placeholder @endif" src="{{ $profile->logo_url ?? 'data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=' }}" alt="Profile logo preview">
                                </div>

                                <div class="preview-links">
                                    @forelse ($profile->links as $link)
                                        <a class="preview-link" href="{{ $link->value }}" target="_blank" rel="noopener">
                                            <i class="bi {{ $linkTypes[$link->type]['icon'] ?? 'bi-link-45deg' }}"></i>
                                            <span>{{ $link->label }}</span>
                                        </a>
                                    @empty
                                        <p class="text-sm text-neutral-500 px-2 py-2">Add your first contact link above.</p>
                                    @endforelse
                                </div>

                                <div class="preview-badges-wrap" id="preview_badges_wrap" @if (empty($profile->badge_images)) style="display:none;" @endif>
                                    <div class="preview-badges" id="preview_badges">
                                        @if (!empty($profile->badge_images))
                                            @foreach (array_slice($profile->badge_images, 0, 10) as $badge)
                                                <img class="preview-badge" src="{{ $badge }}" alt="Badge image">
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- QR Code Section -->
                            <div class="mt-5 pt-4 border-t border-white/5">
                                <p class="font-mono text-[0.5rem] uppercase tracking-[0.2em] text-white/25 mb-3 flex items-center gap-2">
                                    <i class="bi bi-qr-code"></i> Share your card
                                </p>
                                <div class="qr-container">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size={{ $qrSize }}&data={{ urlencode($publicCardUrl) }}&bgcolor=f3f4f6&color=1a1a1a&margin=12"
                                         alt="QR Code for public profile"
                                         class="qr-image"
                                         loading="lazy">
                                    <div class="qr-details">
                                        <p class="text-xs font-semibold text-white/60">Scan to view profile</p>
                                        <p class="qr-url">{{ $publicCardUrl }}</p>
                                        <button class="qr-copy-btn" onclick="copyToClipboard('{{ $publicCardUrl }}', this)">
                                            <i class="bi bi-clipboard"></i> Copy link
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Form -->
                    <div class="grid content-start gap-5">
                        <article class="glass glow-ring rounded-2xl border border-white/5 p-5 shadow-2xl shadow-black/30">
                            <h2 class="font-display text-lg font-bold text-white mb-1">Personal Information</h2>
                            <p class="text-sm text-white/40 mb-4">Update the name and email connected to your account.</p>

                            <form method="POST" action="{{ route('profile.personal-info.update') }}" class="mb-5">
                                @csrf
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div>
                                        <label for="account_name" class="block text-sm font-semibold text-white/60 mb-1.5">Name</label>
                                        <input id="account_name" type="text" name="name" value="{{ old('name', $user->name) }}" required
                                        class="w-full rounded-xl border border-white/10 bg-white/5 px-3.5 py-2.5 text-sm text-white placeholder-white/30 transition focus:border-white/40 focus:bg-white/10 focus:outline-none">
                                    </div>
                                    <div>
                                        <label for="account_email" class="block text-sm font-semibold text-white/60 mb-1.5">Email</label>
                                        <input id="account_email" type="email" name="email" value="{{ old('email', $user->email) }}" required
                                        class="w-full rounded-xl border border-white/10 bg-white/5 px-3.5 py-2.5 text-sm text-white placeholder-white/30 transition focus:border-white/40 focus:bg-white/10 focus:outline-none">
                                    </div>
                                </div>
                                <div class="mt-4 flex flex-wrap gap-3">
                                    <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-white px-5 py-2.5 text-sm font-bold text-black transition hover:bg-white/90 active:scale-[0.98]">
                                        <i class="bi bi-check2"></i> Save Personal Information
                                    </button>
                                </div>
                            </form>

                            <div class="flex items-center gap-3 rounded-xl border border-white/5 bg-white/5 px-4 py-3">
                                <i class="bi bi-graph-up text-lg text-white/50"></i>
                                <p class="m-0 text-sm text-white/60">
                                    <strong class="font-display text-white">{{ number_format($profile->profile_view_count ?? 0) }}</strong>
                                    <span class="text-white/40"> live profile views</span>
                                </p>
                            </div>

                            <form method="POST" action="{{ route('profile.password.update') }}" style="margin-top:20px; border-top:1px solid rgba(255,255,255,0.09); padding-top:20px;">
                                @csrf
                                <h2 style="margin-bottom:10px;">Change Password</h2>
                                <div class="fields">
                                    <div>
                                        <label for="current_password">Current password</label>
                                        <input id="current_password" type="password" name="current_password" required autocomplete="current-password">
                                    </div>
                                    <div>
                                        <label for="new_password">New password</label>
                                        <input id="new_password" type="password" name="password" required autocomplete="new-password">
                                    </div>
                                    <div>
                                        <label for="password_confirmation">Confirm new password</label>
                                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
                                    </div>
                                </div>
                                <div class="actions">
                                    <button type="submit">Update Password</button>
                                </div>
                            </form>
                        </article>
                    </div>
                </div>
            </section>
        @endif

        <!-- ========================================================== -->
        <!--  TAB: DESIGN & INFO  — REDESIGNED                         -->
        <!-- ========================================================== -->
        <section class="tab-pane" id="design-info-pane" data-tab-content="design-info">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-[1.1fr_0.9fr]">

                <!-- Left: Preview + QR -->
                <div class="grid content-start gap-5">
                    <div class="glass glow-ring sticky top-24 rounded-2xl border border-white/5 p-5 shadow-2xl shadow-black/30 accent-glow">
                        <h3 class="font-mono text-[0.55rem] uppercase tracking-[0.2em] text-white/30 mb-4 flex items-center gap-2">
                            <span class="inline-block w-1.5 h-1.5 rounded-full bg-white/20"></span>
                            Live Preview
                        </h3>
                        <div class="preview layout-{{ $layoutStyle }}">
                            <div id="preview_cover" class="preview-cover">
                                <div class="preview-avatar-stage">
                                    <img id="preview_avatar_media" class="preview-avatar-media" alt="Profile avatar preview">
                                </div>
                            </div>

                            <div class="preview-identity">
                                <div class="preview-copy">
                                    <h2 id="preview_display_name" class="preview-name" style="font-size: {{ $profile->display_name_font_size ?? 24 }}px;">{{ $profile->display_name ?? $user->name }}</h2>
                                    <p id="preview_title" class="preview-title">{{ $profile->title }}</p>
                                    <p id="preview_bio" class="preview-bio">{{ $profile->bio }}</p>
                                </div>
                                <img id="preview_logo" class="preview-logo @if (!($profile->logo_url)) placeholder @endif" src="{{ $profile->logo_url ?? 'data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=' }}" alt="Profile logo preview">
                            </div>

                            <div class="preview-links">
                                @forelse ($profile->links as $link)
                                    <a class="preview-link" href="{{ $link->value }}" target="_blank" rel="noopener">
                                        <i class="bi {{ $linkTypes[$link->type]['icon'] ?? 'bi-link-45deg' }}"></i>
                                        <span>{{ $link->label }}</span>
                                    </a>
                                @empty
                                    <p class="text-sm text-neutral-500 px-2 py-2">Add your first contact link above.</p>
                                @endforelse
                            </div>

                            <div class="preview-badges-wrap" id="preview_badges_wrap" @if (empty($profile->badge_images)) style="display:none;" @endif>
                                <div class="preview-badges" id="preview_badges">
                                    @if (!empty($profile->badge_images))
                                        @foreach (array_slice($profile->badge_images, 0, 10) as $badge)
                                            <img class="preview-badge" src="{{ $badge }}" alt="Badge image">
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- QR Code Section -->
                        <div class="mt-5 pt-4 border-t border-white/5">
                            <p class="font-mono text-[0.5rem] uppercase tracking-[0.2em] text-white/25 mb-3 flex items-center gap-2">
                                <i class="bi bi-qr-code"></i> Share your card
                            </p>
                            <div class="qr-container">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size={{ $qrSize }}&data={{ urlencode($publicCardUrl) }}&bgcolor=f3f4f6&color=1a1a1a&margin=12"
                                     alt="QR Code for public profile"
                                     class="qr-image"
                                     loading="lazy">
                                <div class="qr-details">
                                    <p class="text-xs font-semibold text-white/60">Scan to view profile</p>
                                    <p class="qr-url">{{ $publicCardUrl }}</p>
                                    <button class="qr-copy-btn" onclick="copyToClipboard('{{ $publicCardUrl }}', this)">
                                        <i class="bi bi-clipboard"></i> Copy link
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Form — Redesigned -->
                <div class="grid content-start gap-5">
                    <article class="glass glow-ring rounded-2xl border border-white/5 p-5 shadow-2xl shadow-black/30">
                        <h2 class="font-display text-lg font-bold text-white mb-1">Design &amp; Info</h2>
                        <p class="text-sm text-white/40 mb-4">Customize your digital profile card and virtual contact details.</p>

                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profile-design-form" novalidate>
                            @csrf

                            <!-- ===== SECTION: Identity ===== -->
                            <div class="form-section-title">Identity</div>
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                                <div class="field-group">
                                    <div class="label-wrap">
                                        <i class="bi bi-person"></i>
                                        <label for="display_name">Display Name</label>
                                    </div>
                                    <input id="display_name" type="text" name="display_name" value="{{ old('display_name', $profile->display_name ?? $user->name) }}" required placeholder="Your display name">
                                </div>

                                <div class="field-group">
                                    <div class="label-wrap">
                                        <i class="bi bi-briefcase"></i>
                                        <label for="title">Role / Title</label>
                                    </div>
                                    <input id="title" type="text" name="title" value="{{ old('title', $profile->title) }}" placeholder="e.g. Product Designer">
                                </div>

                                <div class="field-group sm:col-span-2">
                                    <div class="label-wrap">
                                        <i class="bi bi-text-paragraph"></i>
                                        <label for="bio">Bio</label>
                                    </div>
                                    <textarea id="bio" name="bio" rows="2" placeholder="Tell the world about yourself…">{{ old('bio', $profile->bio) }}</textarea>
                                </div>

                                <div class="field-group">
                                    <div class="label-wrap">
                                        <i class="bi bi-fonts"></i>
                                        <label for="display_name_font_size">Name Font Size</label>
                                    </div>
                                    <input id="display_name_font_size" type="number" min="14" max="40" name="display_name_font_size" value="{{ old('display_name_font_size', $profile->display_name_font_size ?? '24') }}" placeholder="24">
                                </div>
                            </div>

                            <!-- ===== SECTION: Layout ===== -->
                            <div class="form-section-title mt-5">Layout &amp; Style</div>
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                                <div class="field-group">
                                    <div class="label-wrap">
                                        <i class="bi bi-grid"></i>
                                        <label for="layout_style">Layout</label>
                                    </div>
                                    <select id="layout_style" name="layout_style">
                                        <option value="classic_card" @selected($layoutStyle === 'classic_card')>Classic</option>
                                        <option value="wave_split" @selected($layoutStyle === 'wave_split')>Wave Split</option>
                                        <option value="soft_fade" @selected($layoutStyle === 'soft_fade')>Soft Fade</option>
                                        <option value="hihello_card" @selected($layoutStyle === 'hihello_card')>HiHello</option>
                                    </select>
                                </div>

                                <div class="field-group">
                                    <div class="label-wrap">
                                        <i class="bi bi-window"></i>
                                        <label for="card_style">Card Style</label>
                                    </div>
                                    <select id="card_style" name="card_style">
                                        <option value="glass" @selected(old('card_style', $profile->card_style) === 'glass')>Glass</option>
                                        <option value="clean" @selected(old('card_style', $profile->card_style) === 'clean')>Clean</option>
                                        <option value="bold" @selected(old('card_style', $profile->card_style) === 'bold')>Bold</option>
                                    </select>
                                </div>

                                <div class="field-group">
                                    <div class="label-wrap">
                                        <i class="bi bi-pattern"></i>
                                        <label for="background_pattern">Pattern</label>
                                    </div>
                                    <select id="background_pattern" name="background_pattern">
                                        <option value="gradient" @selected(old('background_pattern', $profile->background_pattern) === 'gradient')>Gradient</option>
                                        <option value="dots" @selected(old('background_pattern', $profile->background_pattern) === 'dots')>Dots</option>
                                        <option value="solid" @selected(old('background_pattern', $profile->background_pattern) === 'solid')>Solid</option>
                                    </select>
                                </div>
                            </div>

                            <!-- ===== SECTION: Colors ===== -->
                            <div class="form-section-title mt-5">Colors</div>
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">

                                <div class="field-group">
                                    <div class="label-wrap">
                                        <i class="bi bi-fill"></i>
                                        <label>Background</label>
                                    </div>
                                    <div class="color-picker-wrap">
                                        <input id="background_color" type="color" name="background_color" value="{{ old('background_color', $profile->background_color) }}">
                                        <span class="color-label">{{ $profile->background_color ?? '#1a1a1a' }}</span>
                                    </div>
                                </div>

                                <div class="field-group">
                                    <div class="label-wrap">
                                        <i class="bi bi-fonts"></i>
                                        <label>Text</label>
                                    </div>
                                    <div class="color-picker-wrap">
                                        <input id="text_color" type="color" name="text_color" value="{{ old('text_color', $profile->text_color) }}">
                                        <span class="color-label">{{ $profile->text_color ?? '#ffffff' }}</span>
                                    </div>
                                </div>

                            </div>

                            <!-- ===== SECTION: Media ===== -->
                            <div class="form-section-title mt-5">Media</div>

                            <!-- Avatar -->
                            <div class="field-group">
                                <div class="label-wrap">
                                    <i class="bi bi-person-circle"></i>
                                    <label for="avatar_image">Profile Picture / Video</label>
                                </div>
                                <div class="file-upload-wrap">
                                    <input id="avatar_image" type="file" name="avatar_image" accept="image/*,video/*,.mp4,.mov,.m4v,.webm,.avi">
                                    <span class="file-hint">JPG, PNG, WEBP, GIF, MP4, MOV — 4MB / 20MB</span>
                                </div>
                                <input type="hidden" id="avatar_url" name="avatar_url" value="{{ $profile->avatar_url ?? '' }}">
                                <input type="hidden" id="avatar_offset_x" name="avatar_offset_x" value="{{ $profile->avatar_offset_x ?? 0 }}">
                                <input type="hidden" id="avatar_offset_y" name="avatar_offset_y" value="{{ $profile->avatar_offset_y ?? 0 }}">
                                <div id="avatar_preview_album" class="media-preview-grid"></div>
                                @if ($profile->avatar_url)
                                    <div class="mt-2 flex items-center gap-3 text-xs text-white/30">
                                        @php $isAvatarVideo = preg_match('/\.(mp4|webm|mov|m4v|avi|quicktime)(\?.*)?$/i', (string) $profile->avatar_url); @endphp
                                        @if ($isAvatarVideo)
                                            <video controls preload="metadata" class="h-12 w-12 rounded-lg object-cover bg-black/40">
                                                <source src="{{ $profile->avatar_url }}">
                                            </video>
                                        @else
                                            <img src="{{ $profile->avatar_url }}" alt="Current" class="h-12 w-12 rounded-lg object-cover border border-white/10">
                                        @endif
                                        <label class="flex items-center gap-1.5 cursor-pointer">
                                            <input type="checkbox" name="remove_avatar" value="1" class="accent-white">
                                            Remove
                                        </label>
                                    </div>
                                @endif
                            </div>

                            <!-- Logo -->
                            <div class="field-group">
                                <div class="label-wrap">
                                    <i class="bi bi-image"></i>
                                    <label for="logo_image">Logo Image</label>
                                </div>
                                <div class="file-upload-wrap">
                                    <input id="logo_image" type="file" name="logo_image" accept="image/png,image/jpeg,image/webp,image/gif">
                                    <span class="file-hint">PNG, JPEG, WEBP, GIF</span>
                                </div>
                                <input type="hidden" id="logo_url" name="logo_url" value="{{ $profile->logo_url ?? '' }}">
                                <div id="logo_preview_album" class="media-preview-grid"></div>
                                @if ($profile->logo_url)
                                    <div class="mt-2 flex items-center gap-3 text-xs text-white/30">
                                        <img src="{{ $profile->logo_url }}" alt="Current logo" class="h-12 w-12 rounded-lg object-cover border border-white/10">
                                        <label class="flex items-center gap-1.5 cursor-pointer">
                                            <input type="checkbox" name="remove_logo" value="1" class="accent-white">
                                            Remove
                                        </label>
                                    </div>
                                @endif
                            </div>

                            <!-- Badges -->
                            <div class="field-group">
                                <div class="label-wrap">
                                    <i class="bi bi-patch-check"></i>
                                    <label for="badge_images">Badges (max 10)</label>
                                </div>
                                <div class="file-upload-wrap">
                            <input
                        id="badge_images"
                        type="file"
                        name="badge_images[]"
                        accept="image/*"
                        multiple
                    >
                                    <span class="file-hint">Upload multiple</span>
                                </div>
                                <input type="hidden" name="existing_badge_images" id="existing_badge_images" value='{{ json_encode($profile->badge_images ?? []) }}'>
                                <div id="badge_preview_album" class="media-preview-grid"></div>
                            </div>

                            <div id="profile-form-errors" class="mt-5 hidden rounded-xl border border-red-400/20 bg-red-400/10 px-4 py-3 text-sm text-red-200" role="alert" aria-live="polite">
                                <p class="font-semibold">Please fix the following:</p>
                                <ul class="mt-1 list-disc pl-5"></ul>
                            </div>

                            <!-- Submit -->
                            <div class="mt-5 pt-3 border-t border-white/5 flex flex-wrap gap-3">
                                <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-white px-6 py-2.5 text-sm font-bold text-black transition hover:bg-white/90 active:scale-[0.98]">
                                    <i class="bi bi-check2"></i> Save Profile
                                </button>
                            </div>

                        </form>
                    </article>
                </div>
            </div>
        </section>

        <!-- ========================================================== -->
        <!--  TAB: PROFILE LINKS                                       -->
        <!-- ========================================================== -->
        <section class="tab-pane is-active" id="profile-links-pane" data-tab-content="profile-links">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-[1.1fr_0.9fr]">

                <!-- Left: Preview + QR -->
                <div class="grid content-start gap-5">
                    <div class="glass glow-ring sticky top-24 rounded-2xl border border-white/5 p-5 shadow-2xl shadow-black/30 accent-glow">
                        <h3 class="font-mono text-[0.55rem] uppercase tracking-[0.2em] text-white/30 mb-4 flex items-center gap-2">
                            <span class="inline-block w-1.5 h-1.5 rounded-full bg-white/20"></span>
                            Live Preview
                        </h3>
                        <div class="preview layout-{{ $layoutStyle }}">
                            <div id="preview_cover" class="preview-cover">
                                <div class="preview-avatar-stage">
                                    <img id="preview_avatar_media" class="preview-avatar-media" alt="Profile avatar preview">
                                </div>
                            </div>

                            <div class="preview-identity">
                                <div class="preview-copy">
                                    <h2 id="preview_display_name" class="preview-name" style="font-size: {{ $profile->display_name_font_size ?? 24 }}px;">{{ $profile->display_name ?? $user->name }}</h2>
                                    <p id="preview_title" class="preview-title">{{ $profile->title }}</p>
                                    <p id="preview_bio" class="preview-bio">{{ $profile->bio }}</p>
                                </div>
                                <img id="preview_logo" class="preview-logo @if (!($profile->logo_url)) placeholder @endif" src="{{ $profile->logo_url ?? 'data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=' }}" alt="Profile logo preview">
                            </div>

                            <div class="preview-links">
                                @forelse ($profile->links as $link)
                                    <a class="preview-link" href="{{ $link->value }}" target="_blank" rel="noopener">
                                        <i class="bi {{ $linkTypes[$link->type]['icon'] ?? 'bi-link-45deg' }}"></i>
                                        <span>{{ $link->label }}</span>
                                    </a>
                                @empty
                                    <p class="text-sm text-neutral-500 px-2 py-2">Add your first contact link above.</p>
                                @endforelse
                            </div>

                            <div class="preview-badges-wrap" id="preview_badges_wrap" @if (empty($profile->badge_images)) style="display:none;" @endif>
                                <div class="preview-badges" id="preview_badges">
                                    @if (!empty($profile->badge_images))
                                        @foreach (array_slice($profile->badge_images, 0, 10) as $badge)
                                            <img class="preview-badge" src="{{ $badge }}" alt="Badge image">
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- QR Code Section -->
                        <div class="mt-5 pt-4 border-t border-white/5">
                            <p class="font-mono text-[0.5rem] uppercase tracking-[0.2em] text-white/25 mb-3 flex items-center gap-2">
                                <i class="bi bi-qr-code"></i> Share your card
                            </p>
                            <div class="qr-container">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size={{ $qrSize }}&data={{ urlencode($publicCardUrl) }}&bgcolor=f3f4f6&color=1a1a1a&margin=12"
                                     alt="QR Code for public profile"
                                     class="qr-image"
                                     loading="lazy">
                                <div class="qr-details">
                                    <p class="text-xs font-semibold text-white/60">Scan to view profile</p>
                                    <p class="qr-url">{{ $publicCardUrl }}</p>
                                    <button class="qr-copy-btn" onclick="copyToClipboard('{{ $publicCardUrl }}', this)">
                                        <i class="bi bi-clipboard"></i> Copy link
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Form -->
                <div class="grid content-start gap-5">
                    <article class="glass glow-ring rounded-2xl border border-white/5 p-5 shadow-2xl shadow-black/30">
                        <h2 class="font-display text-lg font-bold text-white mb-1">Add Icon Links</h2>
                        <p class="text-sm text-white/40 mb-4">Choose an icon first, then add your account link, number, or contact info.</p>

                        <form method="POST" action="{{ route('profile.links.add') }}">
                            @csrf
                            <input id="type" name="type" type="hidden" value="{{ $selectedType }}">

                            <div class="icon-groups grid max-h-[380px] gap-3 overflow-auto pr-1.5 mt-2" aria-label="Choose icon type">
                                @foreach ($groupedLinkTypes as $category => $types)
                                    <section class="icon-group rounded-2xl border border-white/5 bg-white/5 p-3">
                                        <p class="group-title font-mono text-[0.55rem] uppercase tracking-[0.18em] text-white/30 mb-2.5">{{ $category }}</p>
                                        <div class="icon-grid grid grid-cols-2 gap-2 sm:grid-cols-3">
                                            @foreach ($types as $type => $meta)
                                                <button type="button"
                                                class="icon-choice flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-2.5 py-2 text-left text-white/70 transition hover:border-white/30 hover:-translate-y-0.5 @if ($selectedType === $type) is-active @endif"
                                                data-link-choice
                                                data-type="{{ $type }}"
                                                data-label="{{ $meta['label'] }}"
                                                data-placeholder="{{ $meta['placeholder'] }}">
                                                <i class="bi {{ $meta['icon'] }} text-white/40"></i>
                                                <span class="text-xs leading-tight">{{ $meta['label'] }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                </section>
                            @endforeach
                        </div>

                        <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div>
                                <label for="label" class="block text-sm font-semibold text-white/60 mb-1.5">Label</label>
                                <input id="label" name="label" type="text" value="{{ old('label') }}" placeholder="Enter Label" required
                                class="w-full rounded-xl border border-white/10 bg-white/5 px-3.5 py-2.5 text-sm text-white placeholder-white/30 transition focus:border-white/40 focus:bg-white/10 focus:outline-none">
                            </div>
                            <div>
                                <label for="value" class="block text-sm font-semibold text-white/60 mb-1.5">URL / Contact</label>
                                <input id="value" name="value" type="text" value="{{ old('value') }}" placeholder="{{ $selectedTypeMeta['placeholder'] ?? 'your-link-or-contact' }}" required
                                class="w-full rounded-xl border border-white/10 bg-white/5 px-3.5 py-2.5 text-sm text-white placeholder-white/30 transition focus:border-white/40 focus:bg-white/10 focus:outline-none">
                            </div>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-3">
                            <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-white px-5 py-2.5 text-sm font-bold text-black transition hover:bg-white/90 active:scale-[0.98]">
                                Add Link
                            </button>
                        </div>
                    </form>

                    <!-- Existing links -->
                    @foreach ($profile->links as $link)
                        <div class="mt-2.5 flex items-center justify-between rounded-xl border border-white/5 bg-white/5 px-3.5 py-2.5">
                            <span class="flex items-center gap-2 text-sm text-white/70">
                                <i class="bi {{ $linkTypes[$link->type]['icon'] ?? 'bi-link-45deg' }} text-white/30"></i>
                                {{ $link->label }}
                            </span>
                            <form method="POST" action="{{ route('profile.links.remove', ['link' => $link->id]) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1.5 rounded-full border border-white/10 bg-transparent px-3.5 py-1.5 text-xs font-bold text-white/40 transition hover:border-white/30 hover:bg-white/5 hover:text-white">
                                    <i class="bi bi-trash3"></i> Remove
                                </button>
                            </form>
                        </div>
                    @endforeach
                </article>
            </div>
        </div>
    </section>

</main>

<!-- ============================================================ -->
<!--  JAVASCRIPT  (preserved + enhanced)                         -->
<!-- ============================================================ -->
<script>
    // ─── Copy to clipboard helper ──────────────────────────────────
    function copyToClipboard(text, btn) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(() => {
                const orig = btn.innerHTML;
                btn.innerHTML = '<i class="bi bi-check2"></i> Copied!';
                setTimeout(() => { btn.innerHTML = orig; }, 2000);
            }).catch(() => {
                fallbackCopy(text, btn);
            });
        } else {
            fallbackCopy(text, btn);
        }
    }

    function fallbackCopy(text, btn) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        textarea.style.pointerEvents = 'none';
        document.body.appendChild(textarea);
        textarea.select();
        try {
            document.execCommand('copy');
            const orig = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check2"></i> Copied!';
            setTimeout(() => { btn.innerHTML = orig; }, 2000);
        } catch (e) {
            alert('Unable to copy. Please select and copy the URL manually.');
        }
        document.body.removeChild(textarea);
    }

    // ─── DOM refs ──────────────────────────────────────────────────────
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
    const avatarOffsetXInput = document.getElementById('avatar_offset_x');
    const avatarOffsetYInput = document.getElementById('avatar_offset_y');
    const avatarImageInput = document.getElementById('avatar_image');
    const avatarPreviewAlbum = document.getElementById('avatar_preview_album');
    const logoUrlInput = document.getElementById('logo_url');
    const logoImageInput = document.getElementById('logo_image');
    const logoPreviewAlbum = document.getElementById('logo_preview_album');
    const displayNameFontSizeInput = document.getElementById('display_name_font_size');
    const layoutStyleInput = document.getElementById('layout_style');
    const badgeImagesInput = document.getElementById('badge_images');
    const badgePreviewAlbum = document.getElementById('badge_preview_album');
    const existingBadgeImagesInput = document.getElementById('existing_badge_images');
    const backgroundColorInput = document.getElementById('background_color');
    const textColorInput = document.getElementById('text_color');
    const cardStyleInput = document.getElementById('card_style');
    const backgroundPatternInput = document.getElementById('background_pattern');
    const profileDesignForm = document.getElementById('profile-design-form');
    const profileFormErrors = document.getElementById('profile-form-errors');

    const emptyImage = 'data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=';

    // ─── Helpers ──────────────────────────────────────────────────────
    function updateAllPreviewElements(updateFn) {
        document.querySelectorAll('.preview').forEach(updateFn);
    }

    function validateProfileDesignForm() {
        if (!profileDesignForm) return [];

        const errors = [];
        const avatarFile = avatarImageInput?.files?.[0];
        const logoFile = logoImageInput?.files?.[0];
        const badgeFiles = Array.from(badgeImagesInput?.files || []);
        const avatarExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'mp4', 'mov', 'm4v', 'webm', 'avi'];
        const imageExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        const getExtension = (file) => file.name.split('.').pop()?.toLowerCase() || '';

        profileDesignForm.querySelectorAll('input, select, textarea').forEach((input) => {
            if (!input.checkValidity()) {
                const label = input.labels?.[0]?.textContent?.trim() || 'This field';
                errors.push(`${label}: ${input.validationMessage}`);
            }
        });

        if (avatarFile) {
            if (!avatarExtensions.includes(getExtension(avatarFile))) {
                errors.push('The avatar image must be a JPG, JPEG, PNG, WEBP, GIF, MP4, MOV, M4V, WEBM, or AVI file.');
            }
            if (avatarFile.size > 4 * 1024 * 1024) {
                errors.push('The avatar file must be 4 MB or smaller.');
            }
        }

        if (logoFile) {
            if (!imageExtensions.includes(getExtension(logoFile))) {
                errors.push('The logo must be a JPG, JPEG, PNG, WEBP, or GIF file.');
            }
            if (logoFile.size > 4 * 1024 * 1024) {
                errors.push('The logo file must be 4 MB or smaller.');
            }
        }

        if (badgeFiles.length > 10) {
            errors.push('You can upload a maximum of 10 badge images.');
        }
        if (badgeFiles.some((file) => !imageExtensions.includes(getExtension(file)))) {
            errors.push('Badge images must be JPG, JPEG, PNG, WEBP, or GIF files.');
        }

        return [...new Set(errors)];
    }

    function showProfileFormErrors(errors) {
        if (!profileFormErrors) return;
        const list = profileFormErrors.querySelector('ul');
        if (!list) return;
        list.innerHTML = errors.map((error) => `<li>${error}</li>`).join('');
        profileFormErrors.classList.toggle('hidden', errors.length === 0);
    }

    profileDesignForm?.addEventListener('submit', (event) => {
        const errors = validateProfileDesignForm();
        showProfileFormErrors(errors);

        if (errors.length > 0) {
            event.preventDefault();
            profileFormErrors?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

    function setActiveIcon(choiceButton) {
        iconChoices.forEach((button) => button.classList.remove('is-active'));
        choiceButton.classList.add('is-active');

        const selectedType = choiceButton.dataset.type || 'website';
        const selectedPlaceholder = choiceButton.dataset.placeholder || 'your-link-or-contact';

        linkTypeInput.value = selectedType;
        valueInput.type = selectedType === 'email' ? 'email' : 'text';
        valueInput.placeholder = selectedPlaceholder;
    }

    valueInput.type = linkTypeInput?.value === 'email' ? 'email' : 'text';

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

    function isVideoMedia(value) {
        return typeof value === 'string' && /\.(mp4|webm|mov|m4v|avi|quicktime)(\?.*)?$/i.test(value);
    }

    function normalizeAvatarOffset(value) {
        const number = Number.parseInt(String(value ?? '').replace(/px$/i, ''), 10);
        return Number.isFinite(number) ? number : 0;
    }

    function clamp(value, min, max) {
        return Math.min(Math.max(value, min), max);
    }

    function setAvatarPreviewMedia(src, asVideo = false) {
        updateAllPreviewElements((previewCard) => {
            const previewCover = previewCard.querySelector('#preview_cover');
            const previewAvatarMedia = previewCard.querySelector('#preview_avatar_media');

            if (!previewCover || !previewAvatarMedia) return;

            const existingVideo = previewCover.querySelector('video[data-avatar-preview-video]');
            if (existingVideo) existingVideo.remove();

            if (!src || src.trim() === '') {
                previewAvatarMedia.src = '';
                previewAvatarMedia.style.display = 'none';
                const offsetX = normalizeAvatarOffset(avatarOffsetXInput?.value || 0);
                const offsetY = normalizeAvatarOffset(avatarOffsetYInput?.value || 0);
                previewAvatarMedia.style.setProperty('--avatar-x', `${offsetX}px`);
                previewAvatarMedia.style.setProperty('--avatar-y', `${offsetY}px`);
                return;
            }

            if (asVideo) {
                const video = document.createElement('video');
                video.src = src;
                video.muted = true;
                video.loop = true;
                video.autoplay = true;
                video.playsInline = true;
                video.controls = false;
                video.dataset.avatarPreviewVideo = '1';
                Object.assign(video.style, {
                    position: 'absolute',
                    left: '50%',
                    top: '50%',
                    width: '120%',
                    height: '120%',
                    objectFit: 'cover',
                    transform: 'translate(calc(-50% + var(--avatar-x, 0px)), calc(-50% + var(--avatar-y, 0px)))',
                    border: 'none',
                    pointerEvents: 'auto',
                    cursor: 'grab',
                    userSelect: 'none',
                    webkitUserDrag: 'none',
                    display: 'block'
                });
                previewCover.appendChild(video);
                setupAvatarDrag(video, previewCover);
                return;
            }

            previewAvatarMedia.src = src;
            previewAvatarMedia.style.display = 'block';
            const offsetX = normalizeAvatarOffset(avatarOffsetXInput?.value || 0);
            const offsetY = normalizeAvatarOffset(avatarOffsetYInput?.value || 0);
            previewAvatarMedia.style.setProperty('--avatar-x', `${offsetX}px`);
            previewAvatarMedia.style.setProperty('--avatar-y', `${offsetY}px`);
            previewAvatarMedia.dataset.dragX = String(offsetX);
            previewAvatarMedia.dataset.dragY = String(offsetY);
            setupAvatarDrag(previewAvatarMedia, previewCover);
        });
    }

    function setupAvatarDrag(element, previewCover) {
        if (!element || !previewCover) return;
        let dragState = null;

        element.addEventListener('pointerdown', (event) => {
            element.setPointerCapture(event.pointerId);
            dragState = {
                startX: event.clientX,
                startY: event.clientY,
                currentX: Number(element.dataset.dragX || 0),
                currentY: Number(element.dataset.dragY || 0),
            };
            element.classList.add('dragging');
        });

        element.addEventListener('pointermove', (event) => {
            if (!dragState) return;
            const dx = event.clientX - dragState.startX;
            const dy = event.clientY - dragState.startY;
            const nextX = clamp(dragState.currentX + dx, -80, 80);
            const nextY = clamp(dragState.currentY + dy, -80, 80);
            element.dataset.dragX = String(nextX);
            element.dataset.dragY = String(nextY);
            element.style.setProperty('--avatar-x', `${nextX}px`);
            element.style.setProperty('--avatar-y', `${nextY}px`);
            if (avatarOffsetXInput) avatarOffsetXInput.value = String(nextX);
            if (avatarOffsetYInput) avatarOffsetYInput.value = String(nextY);
        });

        ['pointerup', 'pointerleave', 'pointercancel'].forEach((evt) => {
            element.addEventListener(evt, () => {
                dragState = null;
                element.classList.remove('dragging');
            });
        });
    }

    function updatePreviewBackground(coverImageUrl = null, asVideo = false) {
        updateAllPreviewElements((previewCard) => {
            const cover = previewCard.querySelector('#preview_cover');
            if (!cover) return;
            const backgroundColor = backgroundColorInput?.value || '#111827';
            const pattern = backgroundPatternInput?.value || 'gradient';
            const fallback = pattern === 'solid'
                ? `linear-gradient(${backgroundColor}, ${backgroundColor})`
                : pattern === 'dots'
                    ? `radial-gradient(circle, rgba(255,255,255,.28) 1px, transparent 1.5px), linear-gradient(140deg, ${backgroundColor}, ${backgroundColor})`
                    : `linear-gradient(140deg, rgba(255,255,255,.2), rgba(0,0,0,.25)), linear-gradient(140deg, ${backgroundColor}, ${backgroundColor})`;
            cover.style.backgroundImage = fallback;
            cover.style.backgroundSize = pattern === 'dots' ? '14px 14px, cover' : 'cover';
            cover.style.backgroundPosition = 'center';
        });
        setAvatarPreviewMedia(coverImageUrl, asVideo);
    }

    function updatePreviewStyling() {
        const backgroundColor = backgroundColorInput?.value || '#111827';
        const textColor = textColorInput?.value || '#f9fafb';

        updateAllPreviewElements((previewCard) => {
            previewCard.classList.remove('layout-classic_card', 'layout-wave_split', 'layout-soft_fade',
                'layout-hihello_card');
            previewCard.classList.add(`layout-${layoutStyleInput?.value || 'classic_card'}`);

            previewCard.style.color = textColor;
            previewCard.querySelectorAll('.preview-name, .preview-title, .preview-bio, .preview-link, .preview-link span').forEach((element) => {
                element.style.color = textColor;
            });
            previewCard.querySelectorAll('.preview-link i').forEach((icon) => {
                icon.style.backgroundColor = 'transparent';
                icon.style.border = `2px solid ${backgroundColor}`;
                icon.style.color = backgroundColor;
            });

            if (cardStyleInput.value === 'bold') {
                previewCard.style.boxShadow = '0 25px 60px rgba(0,0,0,0.42)';
            } else {
                previewCard.style.boxShadow = '0 12px 30px rgba(0,0,0,0.24)';
            }
            previewCard.style.borderRadius = cardStyleInput.value === 'clean' ? '8px' : '16px';
        });

        document.querySelectorAll('.color-picker-wrap').forEach((wrapper) => {
            const input = wrapper.querySelector('input[type="color"]');
            const label = wrapper.querySelector('.color-label');
            if (input && label) label.textContent = input.value;
        });

        const currentAvatar = avatarUrlInput?.value || '';
        updatePreviewBackground(currentAvatar, isVideoMedia(currentAvatar));
    }

    function setPreviewLogo(nextUrl) {
        updateAllPreviewElements((previewCard) => {
            const logo = previewCard.querySelector('.preview-logo');
            if (!logo) return;
            if (!nextUrl || nextUrl.trim() === '') {
                logo.src = emptyImage;
                logo.classList.add('placeholder');
                logo.alt = 'Profile logo placeholder';
                return;
            }
            logo.src = nextUrl;
            logo.classList.remove('placeholder');
            logo.alt = 'Profile logo preview';
        });
    }

    function renderPreviewBadges(rawValue) {
        updateAllPreviewElements((previewCard) => {
            const badges = previewCard.querySelector('#preview_badges');
            const badgesWrap = previewCard.querySelector('#preview_badges_wrap');
            if (!badges || !badgesWrap) return;

            const badgeArray = (Array.isArray(rawValue) ? rawValue :
                    (typeof rawValue === 'string' ? rawValue : '')
                    .split(/\r?\n|,/)
                    .map((item) => item.trim())
                    .filter(Boolean))
                .slice(0, 10);

            badges.innerHTML = '';
            if (badgeArray.length === 0) {
                badgesWrap.style.display = 'none';
                return;
            }
            badgeArray.forEach((badgeUrl) => {
                const img = document.createElement('img');
                img.className = 'preview-badge';
                img.src = badgeUrl;
                img.alt = 'Badge image';
                badges.appendChild(img);
            });
            badgesWrap.style.display = 'block';
        });
    }

    function renderBadgeAlbumPreview(filesOrUrls) {
        if (!badgePreviewAlbum) return;
        badgePreviewAlbum.innerHTML = '';
        const images = Array.isArray(filesOrUrls) ? filesOrUrls : [];
        if (!images.length) { badgePreviewAlbum.style.display = 'none'; return; }
        badgePreviewAlbum.style.display = 'grid';
        images.forEach((item) => {
            const card = document.createElement('div');
            card.style.cssText =
                'position:relative;border-radius:10px;overflow:hidden;border:1px solid rgba(255,255,255,0.1);';
            const img = document.createElement('img');
            img.src = item;
            img.alt = 'Badge preview';
            img.style.cssText =
                'width:100%;aspect-ratio:1;object-fit:cover;display:block;background:#0f172a;';
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.textContent = '×';
            btn.title = 'Remove badge';
            btn.style.cssText =
                'position:absolute;top:4px;right:4px;width:20px;height:20px;border-radius:50%;border:0;background:rgba(15,23,42,0.82);color:#fff;cursor:pointer;font-size:14px;line-height:1;';
            btn.addEventListener('click', () => {
                let current = [];
                try { current = JSON.parse(existingBadgeImagesInput?.value || '[]'); } catch (_) {}
                const next = current.filter((v) => v !== item);
                existingBadgeImagesInput.value = JSON.stringify(next);
                renderBadgeAlbumPreview(next);
                renderPreviewBadges(next);
            });
            card.appendChild(img);
            card.appendChild(btn);
            badgePreviewAlbum.appendChild(card);
        });
    }

    function renderImageAlbum(target, imageUrls) {
        if (!target) return;
        target.innerHTML = '';
        if (!imageUrls || imageUrls.length === 0) { target.style.display = 'none'; return; }
        target.style.display = 'grid';
        imageUrls.forEach((item) => {
            const isVideo = typeof item === 'string' && /\.(mp4|webm|mov|m4v|avi|quicktime)(\?.*)?$/i.test(item);
            if (isVideo) {
                const video = document.createElement('video');
                video.src = item;
                video.controls = true;
                video.preload = 'metadata';
                video.style.cssText =
                    'width:100%;aspect-ratio:1;object-fit:cover;border-radius:10px;border:1px solid rgba(255,255,255,0.1);background:#0f172a;';
                target.appendChild(video);
                return;
            }
            const img = document.createElement('img');
            img.src = item;
            img.alt = 'Preview image';
            img.style.cssText =
                'width:100%;aspect-ratio:1;object-fit:cover;border-radius:10px;border:1px solid rgba(255,255,255,0.1);background:#0f172a;';
            target.appendChild(img);
        });
    }

    // ─── Event listeners ─────────────────────────────────────────────
    displayNameInput?.addEventListener('input', () => {
        updateAllPreviewElements((previewCard) => {
            const el = previewCard.querySelector('#preview_display_name');
            if (el) el.textContent = displayNameInput.value || '{{ $user->name }}';
        });
    });

    displayNameFontSizeInput?.addEventListener('input', () => {
        updateAllPreviewElements((previewCard) => {
            const el = previewCard.querySelector('#preview_display_name');
            if (el) el.style.fontSize = `${displayNameFontSizeInput.value}px`;
        });
    });

    titleInput?.addEventListener('input', () => {
        updateAllPreviewElements((previewCard) => {
            const el = previewCard.querySelector('#preview_title');
            if (el) el.textContent = titleInput.value;
        });
    });

    bioInput?.addEventListener('input', () => {
        updateAllPreviewElements((previewCard) => {
            const el = previewCard.querySelector('#preview_bio');
            if (el) el.textContent = bioInput.value;
        });
    });

    avatarUrlInput?.addEventListener('input', () => {
        updatePreviewBackground(avatarUrlInput.value.trim());
    });

    avatarImageInput?.addEventListener('change', () => {
        const file = avatarImageInput.files?.[0];
        if (!file) return;
        const isVideo = file.type.startsWith('video/') || /\.(mp4|webm|mov|m4v|avi|quicktime)$/i.test(file.name);
        if (isVideo) {
            const url = URL.createObjectURL(file);
            renderImageAlbum(avatarPreviewAlbum, [url]);
            updatePreviewBackground(url, true);
            return;
        }
        const reader = new FileReader();
        reader.onload = (e) => {
            const result = e.target?.result;
            if (typeof result === 'string') {
                renderImageAlbum(avatarPreviewAlbum, [result]);
                updatePreviewBackground(result, false);
            }
        };
        reader.readAsDataURL(file);
    });

    logoUrlInput?.addEventListener('input', () => setPreviewLogo(logoUrlInput.value.trim()));

    logoImageInput?.addEventListener('change', () => {
        const file = logoImageInput.files?.[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (e) => {
            const result = e.target?.result;
            if (typeof result === 'string') {
                renderImageAlbum(logoPreviewAlbum, [result]);
                setPreviewLogo(result);
            }
        };
        reader.readAsDataURL(file);
    });

    badgeImagesInput?.addEventListener('change', () => {
        const files = Array.from(badgeImagesInput.files || []);
        const previews = files.filter(f => f && f.type.startsWith('image/')).map(f => URL.createObjectURL(f));
        let current = [];
        try { current = JSON.parse(existingBadgeImagesInput?.value || '[]'); } catch (_) {}
        const merged = [...current, ...previews];
        renderBadgeAlbumPreview(merged);
        renderPreviewBadges(merged);
    });

    // ─── Init ─────────────────────────────────────────────────────────
    const initialBadgeImages = (() => {
        try { return JSON.parse(existingBadgeImagesInput?.value || '[]'); } catch (_) { return []; }
    })();
    renderBadgeAlbumPreview(initialBadgeImages.length ? initialBadgeImages : []);
    renderPreviewBadges(initialBadgeImages.length ? initialBadgeImages : (badgeImagesInput?.value || ''));
    renderImageAlbum(avatarPreviewAlbum, [avatarUrlInput?.value || ''].filter(Boolean));
    renderImageAlbum(logoPreviewAlbum, [logoUrlInput?.value || ''].filter(Boolean));
    setAvatarPreviewMedia(avatarUrlInput?.value || '', isVideoMedia(avatarUrlInput?.value || ''));

    [backgroundColorInput, textColorInput, cardStyleInput, backgroundPatternInput, layoutStyleInput]
    .forEach((input) => {
        input?.addEventListener('input', updatePreviewStyling);
        input?.addEventListener('change', updatePreviewStyling);
    });

    setPreviewLogo(logoUrlInput?.value || '');
    updatePreviewBackground(avatarUrlInput?.value || '', isVideoMedia(avatarUrlInput?.value || ''));
    updatePreviewStyling();

    // ─── Tab switching ──────────────────────────────────────────────
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanes = document.querySelectorAll('.tab-pane');

    function switchTab(tabName) {
        tabPanes.forEach((pane) => pane.classList.remove('is-active'));
        tabButtons.forEach((btn) => btn.classList.remove('is-active'));

        const selectedPane = document.getElementById(`${tabName}-pane`);
        if (selectedPane) selectedPane.classList.add('is-active');

        const selectedButton = document.querySelector(`[data-tab="${tabName}"]`);
        if (selectedButton) selectedButton.classList.add('is-active');

        const mobileMenu = document.getElementById('mobileMenu');
        const menuIcon = document.getElementById('menuIcon');
        if (mobileMenu?.classList.contains('open')) {
            mobileMenu.classList.remove('open');
            if (menuIcon) {
                menuIcon.className = 'bi bi-list text-lg';
            }
            const toggle = document.getElementById('menuToggle');
            if (toggle) toggle.setAttribute('aria-expanded', 'false');
        }
    }

    tabButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const tabName = button.dataset.tab;
            switchTab(tabName);
        });
    });

    // ─── Mobile menu toggle ─────────────────────────────────────────
    const menuToggle = document.getElementById('menuToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    const menuIcon = document.getElementById('menuIcon');

    if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', () => {
            const isOpen = mobileMenu.classList.toggle('open');
            menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            if (menuIcon) {
                menuIcon.className = isOpen ? 'bi bi-x-lg text-lg' : 'bi bi-list text-lg';
            }
        });
    }
</script>

</body>
</html>