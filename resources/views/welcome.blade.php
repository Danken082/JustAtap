<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Just A Tap</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Manrope:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            /* ---- 3-color brand system: Black / White / Gray ---- */
            --black:        #050506;
            --black-soft:   #0a0b0d;
            --panel:        #0e0f12;
            --panel-2:      #16171b;
            --line:         rgba(255, 255, 255, 0.09);
            --line-strong:  rgba(255, 255, 255, 0.26);
            --white:        #ffffff;
            --gray-100:     #eef0f3;
            --gray-300:     #b9bcc4;
            --gray-500:     #7a7d87;
            --gray-700:     #45474e;
            --glow: 0 0 0 1px rgba(255,255,255,0.06), 0 0 40px -8px rgba(255,255,255,0.25);
            --radius-lg: 22px;
            --radius-md: 14px;
            --radius-sm: 9px;
            --ease: cubic-bezier(.19, 1, .22, 1);
        }

        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--black);
            color: var(--white);
            font-family: 'Manrope', sans-serif;
            -webkit-font-smoothing: antialiased;
            position: relative;
        }

        /* Ambient HUD grid, fixed behind everything */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            background-image:
                linear-gradient(rgba(255,255,255,0.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.035) 1px, transparent 1px);
            background-size: 48px 48px;
            -webkit-mask-image: radial-gradient(1200px 700px at 50% 0%, #000 0%, transparent 78%);
            mask-image: radial-gradient(1200px 700px at 50% 0%, #000 0%, transparent 78%);
        }

        .shell { position: relative; z-index: 1; width: min(1440px, 92%); margin: 0 auto; }

        h1, h2, h3, .brand-name, .display { font-family: 'Space Grotesk', sans-serif; }
        .mono { font-family: 'JetBrains Mono', monospace; }

        a { color: inherit; }
        img, video { max-width: 100%; display: block; }
        ::selection { background: var(--white); color: var(--black); }
        :focus-visible { outline: 2px solid var(--white); outline-offset: 3px; border-radius: 4px; }

        /* ---------------- Reveal-on-scroll ---------------- */
        .reveal { opacity: 0; transform: translateY(24px); transition: opacity .7s var(--ease), transform .7s var(--ease); will-change: opacity, transform; }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .reveal-delay-1 { transition-delay: .05s; }
        .reveal-delay-2 { transition-delay: .12s; }
        .reveal-delay-3 { transition-delay: .19s; }

        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            .reveal { opacity: 1 !important; transform: none !important; transition: none !important; }
            .marquee-track, .scanline, .grid-pulse { animation: none !important; }
        }

        /* ---------------- Marquee ticker ---------------- */
        .marquee {
            position: relative;
            z-index: 1;
            overflow: hidden;
            border-bottom: 1px solid var(--line);
            background: var(--black-soft);
        }
        .marquee-track {
            display: flex;
            width: max-content;
            gap: 48px;
            padding: 9px 0;
            animation: marquee 26s linear infinite;
        }
        .marquee-track span {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.7rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--gray-500);
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        .marquee-track span::before { content: '◆'; font-size: 0.55rem; color: var(--gray-700); }
        @keyframes marquee { from { transform: translateX(0); } to { transform: translateX(-50%); } }

        /* ---------------- Header / Nav ---------------- */
        header.site-header {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(5, 5, 6, 0.7);
            backdrop-filter: blur(16px) saturate(140%);
            -webkit-backdrop-filter: blur(16px) saturate(140%);
            border-bottom: 1px solid var(--line);
        }

        .top-nav { display: flex; align-items: center; gap: 20px; padding: 15px 0; }

        .brand { display: flex; align-items: center; gap: 12px; text-decoration: none; flex-shrink: 0; }

        .brand-mark {
            width: 40px; height: 40px;
            border-radius: 11px;
            display: grid; place-items: center;
            position: relative;
            color: var(--black);
            font-family: 'JetBrains Mono', monospace;
            font-weight: 600;
            font-size: 0.78rem;
            letter-spacing: .01em;
            background: var(--white);
            box-shadow: var(--glow);
        }
        .brand-mark::after {
            content: '';
            position: absolute;
            inset: -5px;
            border: 1px solid var(--line-strong);
            border-radius: 14px;
        }

        .brand-name { letter-spacing: 0.3em; text-transform: uppercase; font-size: 0.76rem; font-weight: 600; color: var(--white); }

        .menu { margin: 0 auto; display: flex; align-items: center; gap: clamp(18px, 2.4vw, 36px); }

        .menu a {
            position: relative;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--gray-300);
            padding: 4px 0;
            transition: color .18s var(--ease);
        }
        .menu a::before {
            content: attr(data-index);
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.6rem;
            color: var(--gray-700);
            margin-right: 6px;
            vertical-align: middle;
        }
        .menu a::after {
            content: '';
            position: absolute; left: 0; right: 100%; bottom: -2px; height: 1px;
            background: var(--white);
            transition: right .3s var(--ease);
        }
        .menu a:hover { color: var(--white); }
        .menu a:hover::after { right: 0; }

        .nav-tools { display: flex; align-items: center; gap: 8px; margin-left: auto; }

        .icon-btn {
            width: 36px; height: 36px; border-radius: 999px;
            display: grid; place-items: center;
            border: 1px solid var(--line-strong);
            color: var(--white); text-decoration: none; background: transparent;
            transition: border-color .2s var(--ease), background .2s var(--ease), transform .2s var(--ease);
        }
        .icon-btn:hover { border-color: var(--white); background: rgba(255,255,255,0.07); transform: translateY(-1px); }

        .market {
            width: 36px; height: 36px; border-radius: 10px;
            display: grid; place-items: center; text-decoration: none;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 600; font-size: 0.68rem;
            color: var(--black); background: var(--white);
            border: 1px solid var(--line-strong);
            transition: transform .18s var(--ease), background .18s var(--ease);
        }
        .market:hover { transform: translateY(-1px); background: var(--gray-100); }

        .auth-link, .logout-btn {
            border: 1px solid var(--line-strong);
            background: var(--white); color: var(--black);
            text-decoration: none; border-radius: 999px;
            padding: 9px 16px; font-weight: 700; font-size: 0.78rem;
            cursor: pointer; white-space: nowrap;
            transition: background .18s var(--ease), transform .18s var(--ease), box-shadow .18s var(--ease);
        }
        .auth-link:hover, .logout-btn:hover { background: var(--gray-100); transform: translateY(-1px); box-shadow: var(--glow); }
        .auth-link.ghost { background: transparent; color: var(--white); border-color: var(--line-strong); }
        .auth-link.ghost:hover { border-color: var(--white); background: rgba(255,255,255,0.07); }

        .nav-tools form { margin: 0; }

        .menu-toggle {
            display: none; width: 40px; height: 40px; border-radius: 10px;
            border: 1px solid var(--line-strong); background: transparent; color: var(--white);
            align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0;
        }
        .menu-toggle span, .menu-toggle span::before, .menu-toggle span::after {
            content: ''; display: block; width: 18px; height: 1.6px; background: var(--white); position: relative;
            transition: transform .2s var(--ease), opacity .2s var(--ease);
        }
        .menu-toggle span::before { position: absolute; top: -6px; }
        .menu-toggle span::after { position: absolute; top: 6px; }
        header.nav-open .menu-toggle span { background: transparent; }
        header.nav-open .menu-toggle span::before { transform: translateY(6px) rotate(45deg); }
        header.nav-open .menu-toggle span::after { transform: translateY(-6px) rotate(-45deg); }

        /* ---------------- Flash ---------------- */
        .flash {
            margin-top: 18px; border-radius: var(--radius-sm);
            border: 1px solid var(--line-strong); background: var(--panel); color: var(--gray-100);
            padding: 12px 16px; font-size: 0.9rem; display: flex; align-items: center; gap: 10px;
        }
        .flash::before { content: ''; width: 8px; height: 8px; border-radius: 999px; background: var(--white); flex-shrink: 0; box-shadow: 0 0 12px 2px rgba(255,255,255,0.6); }

        /* ---------------- Hero ---------------- */
        .hero {
            margin-top: 22px;
            min-height: min(76vh, 680px);
            border-radius: var(--radius-lg);
            overflow: hidden;
            border: 1px solid var(--line-strong);
            position: relative;
            box-shadow: 0 30px 80px -30px rgba(0,0,0,0.8);
        }

        .hero-video {
            position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; z-index: 0;
            filter: grayscale(.55) contrast(1.12) brightness(.9);
        }

        .hero-overlay {
            position: absolute; inset: 0; z-index: 1;
            background:
                linear-gradient(180deg, rgba(5,5,6,0.2) 0%, rgba(5,5,6,0.4) 42%, rgba(5,5,6,0.94) 100%),
                linear-gradient(90deg, rgba(5,5,6,0.6) 0%, rgba(5,5,6,0) 58%);
        }

        /* Scanline sweep — signature motion element */
        .scanline {
            position: absolute; inset: 0; z-index: 2; pointer-events: none;
            background: linear-gradient(180deg, transparent 0%, rgba(255,255,255,0.06) 48%, transparent 100%);
            background-size: 100% 220%;
            animation: scan 7s linear infinite;
            mix-blend-mode: screen;
        }
        @keyframes scan { 0% { background-position: 0 -220%; } 100% { background-position: 0 220%; } }

        /* HUD corner brackets */
        .hud-frame { position: absolute; inset: 18px; z-index: 2; pointer-events: none; }
        .hud-frame span { position: absolute; width: 22px; height: 22px; border: 1.5px solid rgba(255,255,255,0.55); }
        .hud-frame span:nth-child(1) { top: 0; left: 0; border-right: none; border-bottom: none; }
        .hud-frame span:nth-child(2) { top: 0; right: 0; border-left: none; border-bottom: none; }
        .hud-frame span:nth-child(3) { bottom: 0; left: 0; border-right: none; border-top: none; }
        .hud-frame span:nth-child(4) { bottom: 0; right: 0; border-left: none; border-top: none; }

        .hero-content {
            position: relative;
            z-index: 3;
            height: 100%;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 28px;
            padding: clamp(24px, 6vw, 76px);
        }

        .hero-copy {
            position: static;
            max-width: 660px;
        }

        .eyebrow {
            display: inline-flex; align-items: center; gap: 9px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.7rem; letter-spacing: 0.16em; text-transform: uppercase;
            color: var(--gray-300);
            border: 1px solid var(--line-strong);
            padding: 6px 12px; border-radius: 999px; margin-bottom: 20px;
            background: rgba(255,255,255,0.04);
        }
        .eyebrow .dot { width: 6px; height: 6px; border-radius: 999px; background: var(--white); box-shadow: 0 0 10px 2px rgba(255,255,255,0.7); animation: pulse 1.8s ease-in-out infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .35; } }

        h1 {
            margin: 0; line-height: 1.03;
            font-size: clamp(2rem, 4.6vw, 3.7rem);
            font-weight: 700; letter-spacing: -0.02em;
            color: var(--white);
        }

        .hero-sub {
            margin: 18px 0 0; color: var(--gray-300);
            font-size: clamp(0.92rem, 1.4vw, 1.05rem);
            max-width: 48ch; line-height: 1.65;
        }

        .cta {
            position: static;
            flex-shrink: 0;
            z-index: 3;
            text-decoration: none; color: var(--black); background: var(--white);
            border-radius: 999px; display: inline-flex; align-items: center; gap: 10px;
            font-weight: 700; font-size: 0.92rem; padding: 15px 26px;
            transition: transform .25s var(--ease), box-shadow .25s var(--ease);
            box-shadow: var(--glow);
        }
        .cta:hover { transform: translateY(-2px); box-shadow: 0 0 0 1px rgba(255,255,255,0.15), 0 0 55px -6px rgba(255,255,255,0.5); }
        .cta svg { transition: transform .25s var(--ease); }
        .cta:hover svg { transform: translateX(4px); }

        .auth-badge {
            margin-top: 18px; display: inline-flex; align-items: center; gap: 8px;
            color: var(--gray-300); font-size: 0.84rem;
            font-family: 'JetBrains Mono', monospace;
            border-top: 1px solid var(--line); padding-top: 12px;
        }
        .auth-badge strong { color: var(--white); font-weight: 600; }

        @media (max-width: 720px) {
            .hero-copy { max-width: none; }
            .hud-frame { inset: 12px; }
            .hero { min-height: auto; }
            .hero-content { flex-direction: column; align-items: flex-start; justify-content: flex-end; min-height: 80vh; padding: 30px 22px 34px; }
        }

        /* ---------------- Products ---------------- */
        .products { margin-top: 66px; padding-bottom: 8px; }

        .section-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 20px; margin-bottom: 28px; flex-wrap: wrap; }

        .section-eyebrow {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.7rem; letter-spacing: 0.18em; text-transform: uppercase;
            color: var(--gray-500); margin: 0 0 8px;
        }

        .section-head h2 { margin: 0; font-size: clamp(1.6rem, 3vw, 2.15rem); letter-spacing: -0.02em; font-weight: 700; }

        .section-link {
            text-decoration: none; color: var(--gray-300); font-weight: 700; font-size: 0.86rem;
            border-bottom: 1px solid var(--line-strong); padding-bottom: 2px; flex-shrink: 0;
            transition: color .18s var(--ease), border-color .18s var(--ease);
        }
        .section-link:hover { color: var(--white); border-color: var(--white); }

        .product-grid { display: grid; gap: 18px; grid-template-columns: repeat(3, minmax(0, 1fr)); }

        .product-card {
            min-height: 370px; border-radius: var(--radius-md);
            position: relative; overflow: hidden;
            background-size: cover; background-position: center;
            border: 1px solid var(--line); text-decoration: none; color: var(--white); display: block;
            filter: grayscale(.25);
            transition: transform .4s var(--ease), filter .4s var(--ease), border-color .4s var(--ease), box-shadow .4s var(--ease);
        }
        .product-card:hover { transform: translateY(-5px); filter: grayscale(0); border-color: var(--line-strong); box-shadow: var(--glow); }

        .product-card::after {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(180deg, rgba(5,5,6,0) 40%, rgba(5,5,6,0.94) 100%);
        }

        .product-tag {
            position: absolute; top: 14px; left: 14px; z-index: 1;
            font-family: 'JetBrains Mono', monospace; font-size: 0.68rem;
            color: var(--gray-100); letter-spacing: .08em;
            border: 1px solid var(--line-strong); border-radius: 999px;
            padding: 4px 10px; background: rgba(5,5,6,0.5); backdrop-filter: blur(6px);
        }

        .product-copy { position: absolute; left: 20px; right: 20px; bottom: 18px; z-index: 1; display: flex; align-items: flex-end; justify-content: space-between; gap: 12px; }
        .product-copy h3 { margin: 0; font-size: 1.5rem; font-weight: 700; line-height: 1.1; }
        .product-copy p { margin: 5px 0 0; color: var(--gray-300); font-size: 0.8rem; line-height: 1.4; }

        .product-left { display: flex; flex-direction: column; gap: 10px; }
        .add-form { margin: 0; }

        .add-btn {
            border: 1px solid var(--line-strong); border-radius: 999px; background: var(--white); color: var(--black);
            font-weight: 700; font-size: 0.78rem; padding: 9px 14px; cursor: pointer;
            transition: background .18s var(--ease), transform .18s var(--ease);
        }
        .add-btn:hover { background: var(--gray-100); transform: translateY(-1px); }

        .shop-link { color: var(--white); font-size: 0.78rem; font-weight: 700; text-decoration: none; border-bottom: 1px solid var(--line-strong); padding-bottom: 2px; }
        .shop-link:hover { border-color: var(--white); }

        .product-arrow {
            width: 36px; height: 36px; border-radius: 999px; border: 1px solid var(--line-strong);
            display: grid; place-items: center; font-size: 1.05rem; line-height: 1; color: var(--white);
            text-decoration: none; flex-shrink: 0;
            transition: background .18s var(--ease), border-color .18s var(--ease);
        }
        .product-arrow:hover { background: rgba(255,255,255,0.08); border-color: var(--white); }

        .muted { color: var(--gray-500); text-align: center; padding: 40px 0; font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; }

        @media (max-width: 900px) { .product-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (max-width: 560px) { .product-grid { grid-template-columns: 1fr; } .product-card { min-height: 310px; } }

        /* ---------------- Responsive nav ---------------- */
        @media (max-width: 980px) {
            .menu-toggle { display: inline-flex; }
            .menu {
                position: fixed; inset: 0 0 0 auto; width: min(320px, 82vw); height: 100vh; margin: 0;
                background: var(--black-soft); border-left: 1px solid var(--line);
                flex-direction: column; align-items: flex-start; gap: 4px;
                padding: 88px 26px 26px;
                transform: translateX(100%); transition: transform .34s var(--ease); z-index: 49;
            }
            header.nav-open .menu { transform: translateX(0); }
            .menu a { width: 100%; padding: 13px 0; font-size: 1.02rem; border-bottom: 1px solid var(--line); }
            .menu a::after { display: none; }
            .nav-tools { margin-left: auto; flex-wrap: nowrap; }
        }
        @media (max-width: 640px) {
            .nav-tools .market { display: none; }
            .top-nav { gap: 10px; }
            .brand-name { display: none; }
        }

        /* ---------------- Footer ---------------- */
        footer.site-footer { margin-top: 96px; border-top: 1px solid var(--line); background: var(--black-soft); position: relative; z-index: 1; }

        .footer-top { display: grid; grid-template-columns: 1.3fr 1fr 1fr 1fr; gap: 40px; padding: 58px 0 42px; }

        .footer-brand-col .brand { margin-bottom: 14px; }
        .footer-tagline { color: var(--gray-500); font-size: 0.9rem; line-height: 1.65; max-width: 34ch; margin: 0 0 20px; }
        .footer-markets { display: flex; gap: 10px; }

        .footer-heading {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.7rem; letter-spacing: 0.18em; text-transform: uppercase; color: var(--gray-500); margin: 0 0 16px;
        }

        .footer-links { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 12px; }
        .footer-links a { text-decoration: none; color: var(--gray-300); font-size: 0.9rem; font-weight: 600; transition: color .18s var(--ease); }
        .footer-links a:hover { color: var(--white); }

        .footer-icons { display: flex; gap: 8px; margin-bottom: 18px; }

        .footer-auth { display: flex; flex-direction: column; gap: 10px; align-items: flex-start; }
        .footer-auth form { margin: 0; width: 100%; }
        .footer-auth .auth-link, .footer-auth .logout-btn { width: 100%; text-align: center; }

        .footer-bottom {
            border-top: 1px solid var(--line); padding: 22px 0 30px;
            display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;
        }
        .footer-bottom p { margin: 0; color: var(--gray-500); font-size: 0.78rem; font-family: 'JetBrains Mono', monospace; }

        .back-to-top {
            display: inline-flex; align-items: center; gap: 8px; color: var(--gray-300); text-decoration: none;
            font-size: 0.78rem; font-weight: 700; font-family: 'JetBrains Mono', monospace;
            border: 1px solid var(--line-strong); border-radius: 999px; padding: 8px 14px;
            transition: color .18s var(--ease), border-color .18s var(--ease);
        }
        .back-to-top:hover { color: var(--white); border-color: var(--white); }

        @media (max-width: 900px) { .footer-top { grid-template-columns: 1fr 1fr; row-gap: 34px; } }
        @media (max-width: 560px) {
            .footer-top { grid-template-columns: 1fr; padding: 46px 0 30px; }
            .footer-bottom { flex-direction: column; align-items: flex-start; }
        }

        :root {
  --ink: #F3F1EC;
  --ink-muted: #B9B6AC;
  --brass: #FFFFFF;
  --brass-hover: #FFFFFF;
  --scrim-top: rgba(10,10,9,0);
  --scrim-bottom: rgba(8,8,7,0.94);
}

.product-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 1.75rem;
}

.product-card {
  position: relative;
  aspect-ratio: 4 / 5;
  border-radius: 6px;
  background-size: cover;
  background-position: center;
  overflow: hidden;
  isolation: isolate;
  box-shadow: 0 1px 2px rgba(0,0,0,.25);
  transition: transform .45s cubic-bezier(.22,1,.36,1), box-shadow .45s ease;
}

.product-card:hover,
.product-card:focus-within {
  transform: translateY(-4px);
  box-shadow: 0 18px 34px -14px rgba(0,0,0,.55);
}

.product-card:hover .product-card__scrim { opacity: .82; }

.product-card__scrim {
  position: absolute;
  inset: 0;
  background: linear-gradient(180deg, var(--scrim-top) 38%, var(--scrim-bottom) 100%);
  opacity: .94;
  transition: opacity .45s ease;
  z-index: 1;
  pointer-events: none;
}

.product-index {
  position: absolute;
  top: 1rem;
  left: 1rem;
  z-index: 2;
  padding: .3rem .55rem;
  font-size: .68rem;
  letter-spacing: .08em;
  color: var(--ink);
  background: rgba(15,15,14,.55);
  border: 1px solid rgba(243,241,236,.25);
  border-radius: 3px;
  backdrop-filter: blur(3px);
}

.product-body {
  position: absolute;
  inset: auto 0 0 0;
  z-index: 2;
  padding: 1.25rem;
  display: flex;
  flex-direction: column;
  gap: .9rem;
}

.product-title {
  font-size: 1.1rem;
  font-weight: 500;
  color: var(--ink);
  margin: 0 0 .3rem;
  line-height: 1.25;
}

.product-desc {
  font-size: .8rem;
  color: var(--ink-muted);
  line-height: 1.45;
  margin: 0;
  max-width: 32ch;
}

.product-body__actions {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: .75rem;
}

.add-form { margin: 0; }

.add-btn,
.shop-link {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: .6rem 1.1rem;
  font-size: .76rem;
  font-weight: 600;
  letter-spacing: .02em;
  color: #16150F;
  background: var(--brass);
  border: none;
  border-radius: 999px;
  cursor: pointer;
  text-decoration: none;
  transition: background .25s ease, transform .25s ease;
}

.add-btn:hover, .shop-link:hover {
  background: var(--brass-hover);
  transform: translateY(-1px);
}

.add-btn:focus-visible,
.shop-link:focus-visible,
.product-arrow:focus-visible {
  outline: 2px solid var(--brass);
  outline-offset: 2px;
}

.product-arrow {
  flex-shrink: 0;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 2.25rem;
  height: 2.25rem;
  color: var(--ink);
  background: rgba(243,241,236,.1);
  border: 1px solid rgba(243,241,236,.3);
  border-radius: 50%;
  text-decoration: none;
  transition: background .25s ease, transform .25s ease, border-color .25s ease;
}

.product-arrow:hover {
  background: var(--ink);
  color: #16150F;
  border-color: var(--ink);
  transform: translateX(2px);
}

.muted {
  color: var(--ink-muted);
  font-family: "IBM Plex Mono", monospace;
  font-size: .85rem;
  padding: 2rem 0;
}

@media (prefers-reduced-motion: reduce) {
  .product-card, .product-card__scrim, .add-btn, .shop-link, .product-arrow {
    transition: none;
  }
}

.icon-btn.cart-btn {
  position: relative;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 50%;
  color: inherit;
  text-decoration: none;
  transition: background .2s ease, transform .2s ease;
}

.icon-btn.cart-btn:hover {
  background: rgba(0, 0, 0, .06);
  transform: translateY(-1px);
}

.cart-badge {
  position: absolute;
  top: -2px;
  right: -4px;
  min-width: 18px;
  height: 18px;
  padding: 0 4px;
  border-radius: 999px;
  background: #E11D2E;
  color: #fff;
  font-size: .68rem;
  font-weight: 700;
  line-height: 18px;
  text-align: center;
  box-shadow: 0 0 0 2px #fff; /* swap #fff for your page bg color */
  animation: badge-pop .35s cubic-bezier(.34, 1.56, .64, 1);
}

@keyframes badge-pop {
  0%   { transform: scale(0); opacity: 0; }
  60%  { transform: scale(1.2); opacity: 1; }
  100% { transform: scale(1); }
}

@media (prefers-reduced-motion: reduce) {
  .cart-badge { animation: none; }
}

.toast-container {
  position: fixed;
  bottom: 1.5rem;
  right: 1.5rem;
  z-index: 9999;
  display: flex;
  flex-direction: column-reverse;
  gap: .75rem;
  pointer-events: none;
}

.toast {
  pointer-events: auto;
  display: flex;
  align-items: center;
  gap: .75rem;
  min-width: 260px;
  max-width: 340px;
  background: var(--panel-2);
  color: var(--white);
  border: 1px solid var(--line-strong);
  padding: .9rem 1rem;
  border-radius: var(--radius-sm);
  box-shadow: 0 10px 30px -8px rgba(0,0,0,.6);
  font-size: .88rem;
  font-family: 'Manrope', sans-serif;
  transform: translateX(120%);
  opacity: 0;
  transition: transform .35s var(--ease), opacity .35s ease;
}

.toast.show {
  transform: translateX(0);
  opacity: 1;
}

.toast-icon {
  flex-shrink: 0;
  width: 22px;
  height: 22px;
  border-radius: 50%;
  background: var(--white);
  display: flex;
  align-items: center;
  justify-content: center;
}

.toast-message { flex: 1; }

.toast-close {
  background: none;
  border: none;
  color: var(--gray-500);
  cursor: pointer;
  font-size: 1rem;
}

.toast-close:hover { color: var(--white); }

@media (max-width: 480px) {
  .toast-container { right: 1rem; left: 1rem; bottom: 1rem; }
  .toast { max-width: none; }
}

.lazada-logo {
    width: 32px;
    height: 32px;
    display: block;
    object-fit: contain;
    filter: brightness(0) saturate(100%)
            invert(45%) sepia(99%)
            saturate(3500%) hue-rotate(359deg)
            brightness(102%) contrast(101%);
}

.market svg {
    width: 32px;
    height: 32px;
    display: block;
}

.menu-toggle {
    position: relative;
    z-index: 99999;
}

.menu-toggle span,
.menu-toggle span::before,
.menu-toggle span::after {
    position: absolute;
    display: block;
    width: 24px;
    height: 2px;
    background: currentColor;
    content: "";
    transition: 0.25s ease;
}

.menu-toggle span {
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.menu-toggle span::before {
    top: -7px;
    left: 0;
}

.menu-toggle span::after {
    top: 7px;
    left: 0;
}

/* X when menu is open */
.menu-toggle.is-open span {
    background: transparent;
}

.menu-toggle.is-open span::before {
    top: 0;
    transform: rotate(45deg);
}

.menu-toggle.is-open span::after {
    top: 0;
    transform: rotate(-45deg);
}

.menu-toggle,
.menu-toggle span,
.menu-toggle span::before,
.menu-toggle span::after {
    box-sizing: content-box !important;
    font-family: inherit !important;
}

.menu-toggle span,
.menu-toggle span::before,
.menu-toggle span::after {
    position: absolute !important;
    display: block !important;
    width: 24px !important;
    height: 2px !important;
    background: currentColor !important;
    content: "" !important;
    transition: 0.25s ease !important;
    margin: 0 !important;
    border: none !important;
    border-radius: 0 !important;
    box-shadow: none !important;
    font-size: 0 !important;
    line-height: 0 !important;
}

.menu-toggle span {
    top: 50% !important;
    left: 50% !important;
    transform: translate(-50%, -50%) !important;
}

.menu-toggle span::before {
    top: -7px !important;
    left: 0 !important;
}

.menu-toggle span::after {
    top: 7px !important;
    left: 0 !important;
}

.menu-toggle.is-open span {
    background: transparent !important;
}

.menu-toggle.is-open span::before {
    top: 0 !important;
    transform: rotate(45deg) !important;
}

.menu-toggle.is-open span::after {
    top: 0 !important;
    transform: rotate(-45deg) !important;
}

    </style>
</head>
<body>
    @php($guestCartCount = array_sum(session('guest_cart', [])))

    <div class="marquee" aria-hidden="true">
        <div class="marquee-track">
            <span>Tap to connect</span>
            <span>NFC enabled</span>
            <span>Instant share</span>
            <span>No app required</span>
            <span>Made for Philippines</span>
            <span>NFC enabled</span>
            <span>Instant share</span>
            <span>No app required</span>
            <span>Made for Philippines</span>
        </div>
    </div>

    <header class="site-header" id="siteHeader">
        <div class="shell">
            <nav class="top-nav" aria-label="Main navigation">
                <a href="{{ route('home') }}" class="brand" aria-label="Just A Tap home">
                    <span class="brand-mark">JAT</span>
                </a>

                <div class="menu" id="siteMenu" aria-label="Main menu">
                    <a href="#" data-index="">Home</a>
                    <a href="{{ route('shop.index') }}" data-index="">Products</a>
                    <a href="#" data-index="">About Us</a>
                    <a href="#" data-index="">Contact</a>
                </div>

                <div class="nav-tools">
                        <!-- <a class="market shopee" href="https://shopee.com" target="_blank" rel="noopener" title="Shop on Shopee" aria-label="Shopee"><i class="shopee-icon"></i></a>
                        <a class="market lazada" href="https://lazada.com" target="_blank" rel="noopener" title="Shop on Lazada" aria-label="Lazada"><i class="lazada-icon"></i></a> -->

                    <a class="icon-btn" href="#" aria-label="Search">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8"></circle>
                            <path d="M20 20L17.2 17.2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
                        </svg>
                    </a>
                    <a class="icon-btn" href="#" aria-label="Account">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.8"></circle>
                            <path d="M4 20C5.4 16.7 8.2 15 12 15C15.8 15 18.6 16.7 20 20" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
                        </svg>
                    </a>

                    <!-- New Add Functions -->

                   <a class="icon-btn cart-btn" href="{{ route('cart.index') }}" aria-label="Cart ({{ $guestCartCount }} items)" data-cart-count="{{ $guestCartCount }}">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <path d="M3 5H6L8.3 15H18.2L20.5 8H7.2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
        <circle cx="10" cy="19" r="1.4" fill="currentColor"></circle>
        <circle cx="17" cy="19" r="1.4" fill="currentColor"></circle>
    </svg>

    @if ($guestCartCount > 0)
        <span class="cart-badge" id="cartBadge" aria-hidden="true">{{ $guestCartCount > 99 ? '99+' : $guestCartCount }}</span>
    @endif
</a>

                    @auth
                        <a href="{{ route('profile.edit') }}" class="auth-link ghost">Profile Builder</a>
                        @if (auth()->user()->isCorporate())
                            <a href="{{ route('corporate.cards.index') }}" class="auth-link ghost">Corporate Cards</a>
                        @endif
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="auth-link ghost">Admin</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="logout-btn">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="auth-link">Login</a>
                    @endauth

                    <button type="button" class="menu-toggle" id="menuToggle" aria-label="Toggle menu" aria-expanded="false" aria-controls="siteMenu">
                        <span></span>
                    </button>
                </div>
            </nav>
        </div>
    </header>

    <div class="shell">
        <!-- @if (session('status'))
            <p class="flash">{{ session('status') }}</p>
        @endif -->

        <section class="hero reveal reveal-delay-2" id="top">
            <video class="hero-video" autoplay muted loop playsinline>
                <source src="https://res.cloudinary.com/bqgunan0/video/upload/v1787468597/JUST_A_TAP_v3.mp4" type="video/mp4">
            </video>
            <div class="hero-overlay"></div>
            <div class="scanline"></div>

            <div class="hero-content">
                <div class="hero-copy">
                    <span class="eyebrow"><span class="dot"></span> NFC Networking </span>
                    <h1>Smart Tap, Digital Business Cards &amp; NFC Networking Solutions Philippines</h1>
                    <p class="hero-sub">One tap shares your contact, socials and portfolio instantly — no app required, no paper wasted.</p>

                    @auth
                        <p class="auth-badge">Logged in as <strong>{{ auth()->user()->name }}</strong> · {{ auth()->user()->email }}</p>
                    @endauth
                </div>

                <a class="cta" href="#">
                    Explore Now
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M5 12H19M19 12L13 6M19 12L13 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </a>
            </div>
        </section>

        <section class="products reveal reveal-delay-3" aria-label="Our products">
            <div class="section-head">
                <div>
                    <p class="section-eyebrow">Catalogue</p>
                    <h2>Our Products</h2>
                </div>
                <a href="{{ route('shop.index') }}" class="section-link">View full shop &rarr;</a>
            </div>

            <div class="product-grid">
    @forelse ($homeProducts as $index => $product)
        <article class="product-card reveal reveal-delay-{{ ($index % 3) + 1 }}" style="background-image: url('{{ $product['image'] }}');">
            <div class="product-card__scrim" aria-hidden="true"></div>

            <span class="product-index mono">{{ sprintf('%03d', $index + 1) }}</span>

            <div class="product-body">
                <div class="product-body__text">
                    <h3 class="product-title">{{ $product['name'] }}</h3>
                    <p class="product-desc">{{ \Illuminate\Support\Str::limit($product['description'], 60) }}</p>
                </div>

                <div class="product-body__actions">
                    @guest
                        <form method="POST" action="{{ route('cart.add', ['product' => $product['id']]) }}" class="add-form">
                            @csrf
                            <input type="hidden" name="color" value="{{ $product['colors'][0] }}">
                            <input type="hidden" name="size" value="{{ $product['sizes'][0] }}">
                            <button type="submit" class="add-btn"><span>Add to Cart</span></button>
                        </form>
                    @else
                        <a href="{{ route('shop.index') }}" class="shop-link"><span>Guest shop</span></a>
                    @endguest

                    <a class="product-arrow" href="{{ route('shop.index') }}" aria-label="Go to guest shop">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                            <path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
            </div>
        </article>
    @empty
        <p class="muted">// No products available yet.</p>
    @endforelse
</div>
        </section>
    </div>

    <footer class="site-footer">
        <div class="shell footer-top">
            <div class="footer-brand-col">
                <a href="{{ route('home') }}" class="brand" aria-label="Just A Tap home">
                    <span class="brand-mark">JAT</span>
                </a>
                <p class="footer-tagline">Smart Tap, digital business cards &amp; NFC networking solutions — built for Philippines, made for one tap.</p>
                <div class="footer-markets">
                     <a class="market shopee"
   href="https://shopee.com"
   target="_blank"
   rel="noopener"
   title="Shop on Shopee"
   aria-label="Shopee">

    <svg viewBox="0 0 64 64"
         width="40"
         height="40"
         xmlns="http://www.w3.org/2000/svg">

        <!-- Orange rounded-square background -->
        <rect x="2" y="2"
              width="60"
              height="60"
              rx="16"
              fill="#EE4D2D"/>

        <!-- Shopping bag -->
        <path d="M14 25
                 Q14 22 17 22
                 H47
                 Q50 22 50 25
                 L48 48
                 Q48 52 44 52
                 H20
                 Q16 52 16 48
                 Z"
              fill="#FFFFFF"/>

        <!-- Bag handle -->
        <path d="M23 23
                 C23 16 27 12 32 12
                 C37 12 41 16 41 23"
              fill="none"
              stroke="#FFFFFF"
              stroke-width="4"
              stroke-linecap="round"/>

        <!-- Shopee S -->
        <text x="32"
              y="44"
              text-anchor="middle"
              font-family="Arial, Helvetica, sans-serif"
              font-size="25"
              font-weight="400"
              fill="#EE4D2D">S</text>

    </svg>

</a>
                     <a class="market lazada"
   href="https://lazada.com"
   target="_blank"
   rel="noopener"
   title="Shop on Lazada"
   aria-label="Lazada">

    <svg viewBox="0 0 64 64"
         width="40"
         height="40"
         xmlns="http://www.w3.org/2000/svg">

        <!-- Blue rounded-square background -->
        <rect width="64"
              height="64"
              rx="16"
              fill="#1239A6"/>

        <!-- Lazada heart / box -->
        <defs>
            <linearGradient id="lazadaGradient"
                            x1="0"
                            y1="0"
                            x2="1"
                            y2="0">
                <stop offset="0%" stop-color="#FF7200"/>
                <stop offset="52%" stop-color="#FF4D36"/>
                <stop offset="100%" stop-color="#F900A8"/>
            </linearGradient>
        </defs>

        <path
            d="M12 23
               L25 15
               L32 20
               L39 15
               L52 23
               L52 40
               L32 51
               L12 40
               Z"
            fill="url(#lazadaGradient)"/>

        <!-- 3D center fold -->
        <path
            d="M12 23
               L32 35
               L32 51
               L12 40
               Z"
            fill="#FF5A0A"
            opacity="0.9"/>

        <path
            d="M52 23
               L32 35
               L32 51
               L52 40
               Z"
            fill="#F50086"
            opacity="0.9"/>

        <!-- Top center indentation -->
        <path
            d="M25 15
               C27 18 29 20 32 20
               C35 20 37 18 39 15
               L39 27
               C37 30 35 31 32 31
               C29 31 27 30 25 27
               Z"
            fill="url(#lazadaGradient)"/>

        <!-- Laz text -->
        <text
            x="32"
            y="37"
            text-anchor="middle"
            font-family="Arial, Helvetica, sans-serif"
            font-size="15"
            font-weight="700"
            fill="#FFFFFF">
            Laz
        </text>

    </svg>

</a>
                </div>
            </div>

            <div class="footer-menu-col">
                <p class="footer-heading">Links</p>
                <ul class="footer-links">
                    <li><a href="#">Home</a></li>
                    <li><a href="{{ route('shop.index') }}">Products</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>

            <div class="footer-tools-col">
                <p class="footer-heading">Menu</p>
                <div class="footer-icons">
                    <a class="icon-btn" href="#" aria-label="Search">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8"></circle>
                            <path d="M20 20L17.2 17.2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
                        </svg>
                    </a>
                    <a class="icon-btn" href="#" aria-label="Account">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.8"></circle>
                            <path d="M4 20C5.4 16.7 8.2 15 12 15C15.8 15 18.6 16.7 20 20" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
                        </svg>
                    </a>
                    <a class="icon-btn" href="{{ route('cart.index') }}" aria-label="Cart">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M3 5H6L8.3 15H18.2L20.5 8H7.2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                            <circle cx="10" cy="19" r="1.4" fill="currentColor"></circle>
                            <circle cx="17" cy="19" r="1.4" fill="currentColor"></circle>
                        </svg>
                    </a>
                </div>
                <ul class="footer-links">
                    <!-- @guest
                        <li><a href="{{ route('cart.index') }}">Cart ({{ $guestCartCount }})</a></li>
                    @endguest -->
                    @auth
                        <li><a href="{{ route('profile.edit') }}">Profile Builder</a></li>
                    @endauth
                </ul>
            </div>

            <!-- <div class="footer-auth-col">
                <p class="footer-heading">Your Account</p>
                <div class="footer-auth">
                    @guest
                        <a href="{{ route('login') }}" class="auth-link">Login</a>
                    @endguest

                    @auth
                        <a href="{{ route('profile.edit') }}" class="auth-link ghost">Profile Builder</a>
                        @if (auth()->user()->isCorporate())
                            <a href="{{ route('corporate.cards.index') }}" class="auth-link ghost">Corporate Cards</a>
                        @endif
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="auth-link ghost">Admin</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="logout-btn">Logout</button>
                        </form>
                    @endauth
                </div>
            </div> -->
        </div>

        <div class="shell footer-bottom">
            <p>© {{ date('Y') }} Just A Tap. All rights reserved.</p>
            <a href="#top" class="back-to-top">Back to top &uarr;</a>
        </div>
    </footer>

    <script>
        // Scroll reveal
        const revealItems = document.querySelectorAll('.reveal');
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -30px 0px' });
        revealItems.forEach((item) => revealObserver.observe(item));

        // Mobile menu toggle
        const header = document.getElementById('siteHeader');
const menuToggle = document.getElementById('menuToggle');
const siteMenu = document.getElementById('siteMenu');

if (menuToggle && header && siteMenu) {
    menuToggle.addEventListener('click', () => {
        const isOpen = header.classList.toggle('nav-open');

        menuToggle.classList.toggle('is-open', isOpen);
        menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        menuToggle.setAttribute('aria-label', isOpen ? 'Close menu' : 'Open menu');
    });

    siteMenu.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            header.classList.remove('nav-open');
            menuToggle.classList.remove('is-open');
            menuToggle.setAttribute('aria-expanded', 'false');
            menuToggle.setAttribute('aria-label', 'Open menu');
        });
    });
}


        // New Add Functions

        function showToast(message, duration = 4000) {
            let container = document.getElementById('toastContainer');
            if (!container) {
                container = document.createElement('div');
                container.id = 'toastContainer';
                container.className = 'toast-container';
                container.setAttribute('aria-live', 'polite');
                document.body.appendChild(container);
            }

            const toast = document.createElement('div');
            toast.className = 'toast';
            toast.setAttribute('role', 'status');
            toast.innerHTML = `
                <span class="toast-icon" aria-hidden="true">
                    <svg width="12" height="12" viewBox="0 0 16 16" fill="none">
                        <path d="M3 8.5L6.5 12L13 4.5" stroke="#050506" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <span class="toast-message">${message}</span>
                <button class="toast-close" aria-label="Dismiss">&times;</button>
            `;

            container.appendChild(toast);
            requestAnimationFrame(() => toast.classList.add('show'));
            playAddedSound();

            const remove = () => {
                toast.classList.remove('show');
                toast.addEventListener('transitionend', () => toast.remove(), { once: true });
            };

            const timer = setTimeout(remove, duration);
            toast.querySelector('.toast-close').addEventListener('click', () => {
                clearTimeout(timer);
                remove();
            });
        }

        let audioCtx;
        function playAddedSound() {
            try {
                audioCtx = audioCtx || new (window.AudioContext || window.webkitAudioContext)();
                const now = audioCtx.currentTime;
                [880, 1320].forEach((freq, i) => {
                    const osc = audioCtx.createOscillator();
                    const gain = audioCtx.createGain();
                    osc.type = 'sine';
                    osc.frequency.value = freq;
                    gain.gain.setValueAtTime(0, now + i * 0.09);
                    gain.gain.linearRampToValueAtTime(0.18, now + i * 0.09 + 0.02);
                    gain.gain.exponentialRampToValueAtTime(0.001, now + i * 0.09 + 0.25);
                    osc.connect(gain).connect(audioCtx.destination);
                    osc.start(now + i * 0.09);
                    osc.stop(now + i * 0.09 + 0.26);
                });
            } catch (e) {}
        }

        document.addEventListener('click', () => {
            audioCtx = audioCtx || new (window.AudioContext || window.webkitAudioContext)();
        }, { once: true });

        @if (session('status'))
            document.addEventListener('DOMContentLoaded', () => {
                showToast(@json(session('status')));
            });
        @endif
    </script>
</body>
</html>