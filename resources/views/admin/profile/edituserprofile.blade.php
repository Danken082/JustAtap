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

        .right-stack {
            display: grid;
            gap: 14px;
            align-content: start;
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
            width: min(380px, 100%);
            margin: 0 auto;
            border-radius: 18px;
            border: 1px solid #d8deea;
            background: #f3f4f6;
            overflow: hidden;
            color: #2f3238;
            box-shadow: 0 18px 32px rgba(18, 24, 38, 0.12);
        }

        .preview-cover {
            position: relative;
            height: 225px;
            background: linear-gradient(140deg, #8aa0c8, #506383);
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
            border-top: 8px solid #8f949d;
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
            color: var(--preview-text, #22262d);
            font-weight: 800;
        }

        .preview-title {
            margin: 4px 0 0;
            color: var(--preview-text, #454b57);
            font-weight: 500;
        }

        .preview-bio {
            margin: 6px auto 0;
            max-width: 92%;
            color: var(--preview-text, #707887);
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
        }

        .preview-link i {
            width: 32px;
            height: 32px;
            border-radius: 999px;
            display: grid;
            place-items: center;
            background: #888c94;
            color: #fff;
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
            .wrap {
                width: min(96%, 720px);
            }

            .grid {
                grid-template-columns: 1fr;
            }

            .head {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
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

        @media (max-width: 430px) {
            .wrap {
                width: min(100%, 390px);
                padding: calc(env(safe-area-inset-top, 0px) + 14px) 10px calc(env(safe-area-inset-bottom, 0px) + 24px);
            }

            .panel {
                padding: 14px;
                border-radius: 18px;
            }

            .head {
                margin-bottom: 12px;
            }

            h1 {
                font-size: 1.8rem;
            }

            .preview {
                width: 100%;
                border-radius: 20px;
            }

            .preview-cover {
                height: 180px;
            }

            .preview-badges {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }

            .actions {
                flex-direction: column;
                gap: 10px;
            }

            .actions button {
                width: 100%;
                min-height: 46px;
                border-radius: 14px;
            }

            input,
            select,
            textarea {
                min-height: 46px;
                border-radius: 12px;
            }
        }
    </style>
</head>
<body>
    @php
        $publicCardUrl = $user->card_id ? route('profile.public', ['cardId' => $user->card_id]) : null;
        $selectedType = old('type', 'website');
        $selectedTypeMeta = $linkTypes[$selectedType] ?? reset($linkTypes);
        $groupedLinkTypes = collect($linkTypes)->groupBy('category', true);
        $layoutStyle = old('layout_style', $profile->layout_style ?? 'classic_card');
        $adminEditor = $adminEditor ?? false;
        $profileUpdateRoute = $adminEditor ? route('admin.users.profile.update', $user) : route('profile.update');
    @endphp

    <main class="wrap">
        <header class="head">
            <h1>Profile Builder</h1>
            <div>
                @if ($adminEditor)
                    <a href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                @else
                    <a href="{{ route('home') }}">Home</a>
                @endif
                @if ($publicCardUrl)
                    <span> | </span>
                    <a href="{{ $publicCardUrl }}" target="_blank">View Public Card</a>
                @endif
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

                <form method="POST" action="{{ $profileUpdateRoute }}" enctype="multipart/form-data">
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
                        <div>
                            <label for="display_name_font_size">Display Name Font Size</label>
                            <input id="display_name_font_size" type="number" min="14" max="40" name="display_name_font_size" value="{{ old('display_name_font_size', $profile->display_name_font_size ?? '24') }}">
                        </div>
                        <div>
                            <label for="layout_style">Layout Preset</label>
                            <select id="layout_style" name="layout_style">
                                <option value="classic_card" @selected($layoutStyle === 'classic_card')>Classic Card</option>
                                <option value="wave_split" @selected($layoutStyle === 'wave_split')>Wave Split</option>
                                <option value="soft_fade" @selected($layoutStyle === 'soft_fade')>Soft Fade</option>
                                <option value="hihello_card" @selected($layoutStyle === 'hihello_card')>HiHello Style</option>
                            </select>
                        </div>
                        <div class="full">
                            <label for="bio">Bio</label>
                            <textarea id="bio" name="bio">{{ old('bio', $profile->bio) }}</textarea>
                        </div>
                        <div class="full">
                            <input type="hidden" id="avatar_url" name="avatar_url" value="{{ $profile->avatar_url ?? '' }}">
                            <input type="hidden" id="avatar_offset_x" name="avatar_offset_x" value="{{ $profile->avatar_offset_x ?? 0 }}">
                            <input type="hidden" id="avatar_offset_y" name="avatar_offset_y" value="{{ $profile->avatar_offset_y ?? 0 }}">
                            <label for="avatar_image">Profile Picture / Video Upload (optional)</label>
                            <input id="avatar_image" type="file" name="avatar_image" accept="image/*,video/*,.mp4,.mov,.m4v,.webm,.avi">
                            <p class="small">Accepted: JPG, PNG, WEBP, GIF, MP4, MOV, M4V, WEBM, AVI. Maximum size: 4MB for images or 20MB for video.</p>

                            <div id="avatar_preview_album" style="display:grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 10px; margin-top: 12px;"></div>

                            @if ($profile->avatar_url)
                                <div class="avatar-control">
                                    @php $isAvatarVideo = preg_match('/\.(mp4|webm|mov|m4v|avi|quicktime)(\?.*)?$/i', (string) $profile->avatar_url); @endphp
                                    @if ($isAvatarVideo)
                                        <video controls preload="metadata" style="width:100%;max-height:220px;border-radius:10px;background:#0f172a;">
                                            <source src="{{ $profile->avatar_url }}">
                                        </video>
                                    @else
                                        <img src="{{ $profile->avatar_url }}" alt="Current profile picture">
                                    @endif
                                    <label style="display:flex;align-items:center;gap:8px;margin:0;">
                                        <input type="checkbox" name="remove_avatar" value="1" style="width:auto;">
                                        Remove current profile picture
                                    </label>
                                </div>
                            @endif
                        </div>
                        <div class="full">
                            <input type="hidden" id="logo_url" name="logo_url" value="{{ $profile->logo_url ?? '' }}">
                            <label for="logo_image">Logo Image Upload (optional)</label>
                            <input id="logo_image" type="file" name="logo_image" accept="image/png,image/jpeg,image/webp,image/gif">
                            <div id="logo_preview_album" style="display:grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 10px; margin-top: 12px;"></div>

                            @if ($profile->logo_url)
                                <div class="avatar-control" style="margin-top:12px;">
                                    <img src="{{ $profile->logo_url }}" alt="Current logo image">
                                    <label style="display:flex;align-items:center;gap:8px;margin:0;">
                                        <input type="checkbox" name="remove_logo" value="1" style="width:auto;">
                                        Remove current logo
                                    </label>
                                </div>
                            @endif
                        </div>
                        <div class="full">
                            <label for="badge_images">Badge / Achievement Images (max 10)</label>
                            <input type="hidden" name="existing_badge_images" id="existing_badge_images" value='{{ json_encode($profile->badge_images ?? []) }}'>
                            <input id="badge_images" type="file" name="badge_images[]" accept="image/png,image/jpeg,image/webp,image/gif" multiple>
                            <p class="small">Upload new badge images to add them to the album. Remove existing badges using the remove button below.</p>
                            <div id="badge_preview_album" style="display:grid; grid-template-columns: repeat(auto-fill, minmax(74px, 1fr)); gap: 10px; margin-top: 12px;"></div>
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

            <div class="right-stack">
            <article class="panel">
                <h2>Add Icon Links</h2>
                <p class="small">Choose an icon first, then add your account link, number, or contact info for that icon.</p>

                    <form method="POST" action="{{ $adminEditor ? route('admin.users.profile.links.add', $user) : route('profile.links.add') }}">
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
                            <input id="label" name="label" type="text" value="{{ old('label') }}" placeholder="Enter Label" required>
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
                        <form method="POST" action="{{ $adminEditor ? route('admin.users.profile.links.remove', ['user' => $user, 'link' => $link->id]) : route('profile.links.remove', ['link' => $link->id]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="danger">Remove</button>
                        </form>
                    </div>
                @endforeach
            </article>

        <section class="panel">
            <h2>Live Preview</h2>
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
                    <img
                        id="preview_logo"
                        class="preview-logo @if (!($profile->logo_url)) placeholder @endif"
                        src="{{ $profile->logo_url ?? 'data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=' }}"
                        alt="Profile logo preview"
                    >
                </div>

                <div class="preview-links">
                    @forelse ($profile->links as $link)
                        <a class="preview-link" href="{{ $link->value }}" target="_blank" rel="noopener">
                            <i class="bi {{ $linkTypes[$link->type]['icon'] ?? 'bi-link-45deg' }}"></i>
                            <span>{{ $link->label }}</span>
                        </a>
                    @empty
                        <p>Add your first contact link above.</p>
                    @endforelse
                </div>

                <div class="preview-badges-wrap" id="preview_badges_wrap" @if (empty($profile->badge_images)) style="display:none;" @endif>
                    <p class="preview-badges-title">Achievements</p>
                    <div class="preview-badges" id="preview_badges">
                        @if (!empty($profile->badge_images))
                            @foreach (array_slice($profile->badge_images, 0, 10) as $badge)
                                <img class="preview-badge" src="{{ $badge }}" alt="Badge image">
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            @if ($publicCardUrl)
            <div class="actions" style="margin-top: 14px; align-items: stretch;">
                <div style="width:100%;border:1px solid rgba(255,255,255,0.12);border-radius:12px;padding:12px;">
                    <h3 style="margin:0;">Scan Or Tap Access</h3>
                    <p class="small" style="margin-top:6px;">This is the profile link encoded in your card. Users can scan the QR code or tap your NFC card to open your live profile instantly.</p>
                    <div class="scan-grid">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ urlencode($publicCardUrl) }}&ecc=M&margin=10{{ !empty($profile->avatar_url) ? '&logo='.urlencode($profile->avatar_url) : (!empty($profile->logo_url) ? '&logo='.urlencode($profile->logo_url) : '') }}" alt="QR code for public profile">
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
            @endif
        </section>

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

        const previewCard = document.querySelector('.preview');
        const previewCover = document.getElementById('preview_cover');
        const previewAvatarMedia = document.getElementById('preview_avatar_media');
        const previewName = document.getElementById('preview_display_name');
        const previewTitle = document.getElementById('preview_title');
        const previewBio = document.getElementById('preview_bio');
        const previewLogo = document.getElementById('preview_logo');
        const previewBadgesWrap = document.getElementById('preview_badges_wrap');
        const previewBadges = document.getElementById('preview_badges');

        const emptyImage = 'data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=';

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
            if (!previewAvatarMedia) {
                return;
            }

            const existingVideo = previewCover?.querySelector('video[data-avatar-preview-video]');
            if (existingVideo) {
                existingVideo.remove();
            }

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
                video.style.position = 'absolute';
                video.style.left = '50%';
                video.style.top = '50%';
                video.style.width = '120%';
                video.style.height = '120%';
                video.style.objectFit = 'cover';
                video.style.transform = 'translate(calc(-50% + var(--avatar-x, 0px)), calc(-50% + var(--avatar-y, 0px)))';
                video.style.border = 'none';
                video.style.pointerEvents = 'auto';
                video.style.cursor = 'grab';
                video.style.userSelect = 'none';
                video.style.webkitUserDrag = 'none';
                video.style.display = 'block';
                previewCover?.appendChild(video);
                setupAvatarDrag(video);
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
            setupAvatarDrag(previewAvatarMedia);
        }

        function setupAvatarDrag(element) {
            if (!element || !previewCover) {
                return;
            }

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
                if (!dragState) {
                    return;
                }

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

            element.addEventListener('pointerup', () => {
                dragState = null;
                element.classList.remove('dragging');
            });

            element.addEventListener('pointerleave', () => {
                dragState = null;
                element.classList.remove('dragging');
            });

            element.addEventListener('pointercancel', () => {
                dragState = null;
                element.classList.remove('dragging');
            });
        }

        function updatePreviewBackground(coverImageUrl = null, asVideo = false) {
            if (!previewCover) {
                return;
            }

            const backgroundColor = backgroundColorInput?.value || '#111827';
            const pattern = backgroundPatternInput?.value || 'gradient';
            const fallback = pattern === 'solid'
                ? `linear-gradient(${backgroundColor}, ${backgroundColor})`
                : pattern === 'dots'
                    ? `radial-gradient(circle, rgba(255,255,255,.28) 1px, transparent 1.5px), linear-gradient(140deg, ${backgroundColor}, ${backgroundColor})`
                    : `linear-gradient(140deg, rgba(255,255,255,.2), rgba(0,0,0,.25)), linear-gradient(140deg, ${backgroundColor}, ${backgroundColor})`;

            previewCover.style.backgroundImage = fallback;
            previewCover.style.backgroundSize = pattern === 'dots' ? '14px 14px, cover' : 'cover';
            previewCover.style.backgroundPosition = 'center';
            setAvatarPreviewMedia(coverImageUrl, asVideo);
        }

        function updatePreviewStyling() {
            if (!previewCard) {
                return;
            }

            previewCard.classList.remove('layout-classic_card', 'layout-wave_split', 'layout-soft_fade', 'layout-hihello_card');
            previewCard.classList.add(`layout-${layoutStyleInput?.value || 'classic_card'}`);

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

            const textColor = textColorInput?.value || '#f9fafb';
            const backgroundColor = backgroundColorInput?.value || '#111827';
            previewCard.style.color = textColor;
            previewCard.querySelectorAll('.preview-name, .preview-title, .preview-bio, .preview-link, .preview-badges-title').forEach((element) => {
                element.style.color = textColor;
            });
            previewCard.querySelectorAll('.preview-link i').forEach((icon) => {
                icon.style.backgroundColor = 'transparent';
                icon.style.border = `2px solid ${backgroundColor}`;
                icon.style.color = backgroundColor;
            });

            const currentAvatar = avatarUrlInput?.value || '';
            updatePreviewBackground(currentAvatar, isVideoMedia(currentAvatar));
        }

        function setPreviewLogo(nextUrl) {
            if (!previewLogo) {
                return;
            }

            if (!nextUrl || nextUrl.trim() === '') {
                previewLogo.src = emptyImage;
                previewLogo.classList.add('placeholder');
                previewLogo.alt = 'Profile logo placeholder';
                return;
            }

            previewLogo.src = nextUrl;
            previewLogo.classList.remove('placeholder');
            previewLogo.alt = 'Profile logo preview';
        }

        function renderPreviewBadges(rawValue) {
            if (!previewBadges || !previewBadgesWrap) {
                return;
            }

            const source = Array.isArray(rawValue) ? rawValue : (typeof rawValue === 'string' ? rawValue : '');
            const badges = (Array.isArray(source) ? source : source
                .split(/\r?\n|,/)
                .map((item) => item.trim())
                .filter(Boolean))
                .slice(0, 10);

            previewBadges.innerHTML = '';

            if (badges.length === 0) {
                previewBadgesWrap.style.display = 'none';
                return;
            }

            badges.forEach((badgeUrl) => {
                const badgeImage = document.createElement('img');
                badgeImage.className = 'preview-badge';
                badgeImage.src = badgeUrl;
                badgeImage.alt = 'Badge image';
                previewBadges.appendChild(badgeImage);
            });

            previewBadgesWrap.style.display = 'block';
        }

        function renderBadgeAlbumPreview(filesOrUrls) {
            if (!badgePreviewAlbum) {
                return;
            }

            badgePreviewAlbum.innerHTML = '';

            const images = Array.isArray(filesOrUrls) ? filesOrUrls : [];

            if (!images.length) {
                badgePreviewAlbum.style.display = 'none';
                return;
            }

            badgePreviewAlbum.style.display = 'grid';

            images.forEach((item) => {
                const card = document.createElement('div');
                card.style.position = 'relative';
                card.style.borderRadius = '10px';
                card.style.overflow = 'hidden';
                card.style.border = '1px solid rgba(255,255,255,0.14)';

                const image = document.createElement('img');
                image.src = item;
                image.alt = 'Badge preview';
                image.style.width = '100%';
                image.style.aspectRatio = '1';
                image.style.objectFit = 'cover';
                image.style.display = 'block';
                image.style.background = '#0f172a';

                const removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.textContent = '×';
                removeButton.title = 'Remove badge';
                removeButton.style.position = 'absolute';
                removeButton.style.top = '4px';
                removeButton.style.right = '4px';
                removeButton.style.width = '20px';
                removeButton.style.height = '20px';
                removeButton.style.borderRadius = '50%';
                removeButton.style.border = '0';
                removeButton.style.background = 'rgba(15, 23, 42, 0.82)';
                removeButton.style.color = '#fff';
                removeButton.style.cursor = 'pointer';
                removeButton.style.fontSize = '14px';
                removeButton.style.lineHeight = '1';
                removeButton.addEventListener('click', () => {
                    const current = (() => {
                        try {
                            return JSON.parse(existingBadgeImagesInput?.value || '[]');
                        } catch (error) {
                            return [];
                        }
                    })();

                    const next = current.filter((value) => value !== item);
                    existingBadgeImagesInput.value = JSON.stringify(next);
                    renderBadgeAlbumPreview(next);
                    renderPreviewBadges(next);
                });

                card.appendChild(image);
                card.appendChild(removeButton);
                badgePreviewAlbum.appendChild(card);
            });
        }

        function renderImageAlbum(target, imageUrls) {
            if (!target) {
                return;
            }

            target.innerHTML = '';

            if (!imageUrls || imageUrls.length === 0) {
                target.style.display = 'none';
                return;
            }

            target.style.display = 'grid';

            imageUrls.forEach((item) => {
                const isVideo = typeof item === 'string' && /\.(mp4|webm|mov|m4v|avi|quicktime)(\?.*)?$/i.test(item);

                if (isVideo) {
                    const video = document.createElement('video');
                    video.src = item;
                    video.controls = true;
                    video.preload = 'metadata';
                    video.style.width = '100%';
                    video.style.aspectRatio = '1';
                    video.style.objectFit = 'cover';
                    video.style.borderRadius = '10px';
                    video.style.border = '1px solid rgba(255,255,255,0.14)';
                    video.style.background = '#0f172a';
                    target.appendChild(video);
                    return;
                }

                const image = document.createElement('img');
                image.src = item;
                image.alt = 'Preview image';
                image.style.width = '100%';
                image.style.aspectRatio = '1';
                image.style.objectFit = 'cover';
                image.style.borderRadius = '10px';
                image.style.border = '1px solid rgba(255,255,255,0.14)';
                image.style.background = '#0f172a';
                target.appendChild(image);
            });
        }

        displayNameInput?.addEventListener('input', () => {
            previewName.textContent = displayNameInput.value || '{{ $user->name }}';
        });

        displayNameFontSizeInput?.addEventListener('input', () => {
            const size = displayNameFontSizeInput.value;
            previewName.style.fontSize = `${size}px`;
        });

        titleInput?.addEventListener('input', () => {
            previewTitle.textContent = titleInput.value;
        });

        bioInput?.addEventListener('input', () => {
            previewBio.textContent = bioInput.value;
        });

        avatarUrlInput?.addEventListener('input', () => {
            const nextUrl = avatarUrlInput.value.trim();
            updatePreviewBackground(nextUrl);
        });

        avatarImageInput?.addEventListener('change', () => {
            const file = avatarImageInput.files?.[0];

            if (!file) {
                return;
            }

            const isVideo = file.type.startsWith('video/') || /\.(mp4|webm|mov|m4v|avi|quicktime)$/i.test(file.name);

            if (isVideo) {
                const previewUrl = URL.createObjectURL(file);
                renderImageAlbum(avatarPreviewAlbum, [previewUrl]);
                updatePreviewBackground(previewUrl, true);
                return;
            }

            const reader = new FileReader();

            reader.onload = (event) => {
                const result = event.target?.result;

                if (typeof result === 'string') {
                    renderImageAlbum(avatarPreviewAlbum, [result]);
                    updatePreviewBackground(result, false);
                }
            };

            reader.readAsDataURL(file);
        });

        logoUrlInput?.addEventListener('input', () => {
            const nextUrl = logoUrlInput.value.trim();
            setPreviewLogo(nextUrl);
        });

        logoImageInput?.addEventListener('change', () => {
            const file = logoImageInput.files?.[0];
            if (!file) {
                return;
            }

            const reader = new FileReader();

            reader.onload = (event) => {
                const result = event.target?.result;

                if (typeof result === 'string') {
                    renderImageAlbum(logoPreviewAlbum, [result]);
                    setPreviewLogo(result);
                }
            };

            reader.readAsDataURL(file);
        });

        badgeImagesInput?.addEventListener('change', () => {
            const files = Array.from(badgeImagesInput.files || []);
            const previews = files
                .filter((file) => file && file.type.startsWith('image/'))
                .map((file) => URL.createObjectURL(file));

            const current = (() => {
                try {
                    return JSON.parse(existingBadgeImagesInput?.value || '[]');
                } catch (error) {
                    return [];
                }
            })();

            const merged = [...current, ...previews];
            renderBadgeAlbumPreview(merged);
            renderPreviewBadges(merged);
        });

        const initialBadgeImages = (() => {
            try {
                const raw = existingBadgeImagesInput?.value ?? '[]';
                return JSON.parse(raw);
            } catch (error) {
                return [];
            }
        })();

        renderBadgeAlbumPreview(initialBadgeImages.length ? initialBadgeImages : []);
        renderPreviewBadges(initialBadgeImages.length ? initialBadgeImages : (badgeImagesInput?.value || ''));
        renderImageAlbum(avatarPreviewAlbum, [avatarUrlInput?.value || ''].filter(Boolean));
        renderImageAlbum(logoPreviewAlbum, [logoUrlInput?.value || ''].filter(Boolean));
        setAvatarPreviewMedia(avatarUrlInput?.value || '', isVideoMedia(avatarUrlInput?.value || ''));

        [backgroundColorInput, textColorInput, cardStyleInput, backgroundPatternInput, layoutStyleInput].forEach((input) => {
            input?.addEventListener('input', updatePreviewStyling);
            input?.addEventListener('change', updatePreviewStyling);
        });

        setPreviewLogo(logoUrlInput?.value || '');
        updatePreviewBackground(avatarUrlInput?.value || '', isVideoMedia(avatarUrlInput?.value || ''));
        updatePreviewStyling();
    </script>
</body>
</html>
