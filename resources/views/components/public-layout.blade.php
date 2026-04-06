{{-- resources/views/components/public-layout.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SewaAlat — Sewa Alat Rumah Tangga' }}</title>
    <meta name="description" content="{{ $description ?? 'Sewa alat rumah tangga berkualitas, mudah, dan terpercaya.' }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">

    <style>
        /* ══════════════════════════════════
           DESIGN TOKENS
        ══════════════════════════════════ */
        :root {
            --font-body:    'DM Sans', sans-serif;
            --font-display: 'DM Serif Display', serif;

            --sage:         #4a7c59;
            --sage-light:   #6a9e78;
            --sage-pale:    #eef4f0;
            --sage-mid:     #d4e8da;

            --warm-bg:      #faf8f5;
            --warm-surface: #f4f1ec;
            --warm-line:    #e8e3db;
            --warm-line2:   #d6cfc5;

            --ink:          #1c1c1a;
            --ink-60:       #5a5954;
            --ink-35:       #9e9b96;

            --white:        #ffffff;
            --amber:        #d97706;

            --radius-sm:    6px;
            --radius-md:    10px;
            --radius-lg:    16px;

            --nav-height:   62px;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }

        body {
            font-family: var(--font-body);
            background: var(--warm-bg);
            color: var(--ink);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        a { color: inherit; text-decoration: none; }
        img { display: block; max-width: 100%; }

        /* ══════════════════════════════════
           NAVBAR
        ══════════════════════════════════ */
        .nav {
            position: sticky;
            top: 0;
            z-index: 200;
            height: var(--nav-height);
            background: rgba(250, 248, 245, 0.88);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--warm-line);
            padding: 0 max(1.5rem, calc((100vw - 1280px) / 2 + 1.5rem));
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
        }

        /* Logo */
        .nav-logo {
            display: flex;
            align-items: center;
            gap: 7px;
            flex-shrink: 0;
            text-decoration: none;
        }

        .nav-logo-mark {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: var(--sage);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .nav-logo-mark svg { display: block; }

        .nav-logo-text {
            font-family: var(--font-display);
            font-size: 1.18rem;
            font-weight: 400;
            color: var(--ink);
            letter-spacing: -0.01em;
            line-height: 1;
        }
        .nav-logo-text img {
            height: 40px;
            width: auto;
            object-fit: contain;
        }

        /* Links */
        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            flex: 1;
            justify-content: center;
        }

        .nav-link {
            padding: 0.45rem 0.85rem;
            border-radius: var(--radius-sm);
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--ink-60);
            transition: background .15s, color .15s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .nav-link:hover { background: var(--warm-surface); color: var(--ink); }
        .nav-link.active { color: var(--ink); font-weight: 600; }

        /* ── Katalog dropdown ── */
        .nav-katalog-wrap {
            position: relative;
        }

        .nav-katalog-trigger {
            padding: 0.45rem 0.85rem;
            border-radius: var(--radius-sm);
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--ink-60);
            transition: background .15s, color .15s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            user-select: none;
        }
        .nav-katalog-trigger:hover,
        .nav-katalog-wrap:hover .nav-katalog-trigger {
            background: var(--warm-surface);
            color: var(--ink);
        }
        .nav-katalog-trigger.active { color: var(--ink); font-weight: 600; }

        .nav-katalog-arrow {
            width: 12px;
            height: 12px;
            transition: transform .2s;
            flex-shrink: 0;
            opacity: 0.55;
        }
        .nav-katalog-wrap:hover .nav-katalog-arrow {
            transform: rotate(180deg);
        }

        .nav-katalog-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            left: 50%;
            transform: translateX(-50%);
            width: 540px;
            background: var(--white);
            border: 1px solid var(--warm-line);
            border-radius: var(--radius-lg);
            box-shadow: 0 8px 32px rgba(28, 28, 26, .1), 0 2px 8px rgba(28, 28, 26, .06);
            padding: 1rem;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0;
            column-gap: 0;

            opacity: 0;
            visibility: hidden;
            transform: translateX(-50%) translateY(-6px);
            transition: opacity .18s ease, visibility .18s, transform .18s ease;
        }

        /* Arrow tip */
        .nav-katalog-dropdown::before {
            content: '';
            position: absolute;
            top: -6px;
            left: 50%;
            transform: translateX(-50%);
            width: 12px;
            height: 6px;
            background: var(--white);
            clip-path: polygon(50% 0%, 0% 100%, 100% 100%);
            filter: drop-shadow(0 -1px 0 var(--warm-line));
        }

        .nav-katalog-wrap:hover .nav-katalog-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
        }

        .nav-katalog-dropdown-header {
            grid-column: 1 / -1;
            font-size: 0.62rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: var(--ink-35);
            padding: 0 0.5rem 0.6rem;
            border-bottom: 1px solid var(--warm-line);
            margin-bottom: 0.35rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-katalog-dropdown-header a {
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--sage);
            text-transform: none;
            letter-spacing: 0;
        }
        .nav-katalog-dropdown-header a:hover { text-decoration: underline; }

        .nav-kat-col {
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
            padding: 0 0.75rem;
            border-right: 1px solid var(--warm-line);
        }
        .nav-kat-col:first-of-type { padding-left: 0; }
        .nav-kat-col:last-of-type  { padding-right: 0; border-right: none; }

        .nav-kat-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 0.55rem 0.65rem;
            border-radius: var(--radius-md);
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--ink-60);
            transition: background .13s, color .13s;
            text-decoration: none;
        }
        .nav-kat-item:hover {
            background: var(--sage-pale);
            color: var(--sage);
        }

        .nav-kat-icon {
            font-size: 1.1rem;
            line-height: 1;
            flex-shrink: 0;
        }

        .nav-kat-count {
            margin-left: auto;
            font-size: 0.6rem;
            font-weight: 700;
            color: var(--ink-35);
            background: var(--warm-surface);
            padding: 1px 5px;
            border-radius: 100px;
        }

        /* Actions */
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            flex-shrink: 0;
        }

        .nav-btn-ghost {
            padding: 0.48rem 1rem;
            border-radius: var(--radius-sm);
            font-size: 0.83rem;
            font-weight: 500;
            border: 1px solid var(--warm-line2);
            color: var(--ink-60);
            background: transparent;
            cursor: pointer;
            font-family: var(--font-body);
            transition: border-color .15s, color .15s, background .15s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .nav-btn-ghost:hover {
            border-color: var(--ink-60);
            color: var(--ink);
            background: var(--warm-surface);
        }

        .nav-btn-primary {
            padding: 0.48rem 1.1rem;
            border-radius: var(--radius-sm);
            font-size: 0.83rem;
            font-weight: 600;
            background: var(--sage);
            color: #fff;
            border: none;
            cursor: pointer;
            font-family: var(--font-body);
            transition: background .18s, transform .18s, box-shadow .18s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .nav-btn-primary:hover {
            background: var(--sage-light);
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(74, 124, 89, .28);
        }

        /* User pill */
        .nav-user {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px 10px 4px 4px;
            border-radius: 100px;
            border: 1px solid var(--warm-line2);
            background: var(--white);
            transition: border-color .15s, box-shadow .15s;
            text-decoration: none;
        }
        .nav-user:hover {
            border-color: var(--sage-mid);
            box-shadow: 0 2px 8px rgba(74, 124, 89, .1);
        }

        .nav-user-av {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: var(--sage);
            color: #fff;
            font-size: 0.62rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            letter-spacing: .02em;
            flex-shrink: 0;
        }

        .nav-user-name {
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--ink-60);
            max-width: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Divider */
        .nav-divider {
            width: 1px;
            height: 18px;
            background: var(--warm-line2);
        }

        /* ══════════════════════════════════
           MAIN
        ══════════════════════════════════ */
        main { min-height: calc(100vh - var(--nav-height)); }

        /* ══════════════════════════════════
           FOOTER
        ══════════════════════════════════ */
        .footer {
            background: #181e16;
            padding: 4rem max(1.5rem, calc((100vw - 1280px) / 2 + 1.5rem)) 0;
        }

        .footer-inner {
            display: grid;
            grid-template-columns: 1.6fr 1fr 1fr 1.4fr;
            gap: 3rem;
            padding-bottom: 3rem;
            border-bottom: 1px solid rgba(255,255,255,.07);
        }

        /* Brand col */
        .footer-brand {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 0.9rem;
        }

        .footer-logo-mark {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            background: var(--sage);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .footer-logo-text {
            font-family: var(--font-display);
            font-size: 1.12rem;
            font-weight: 400;
            color: #f4f1ec;
            letter-spacing: -0.01em;
        }

        .footer-desc {
            font-size: 0.82rem;
            line-height: 1.7;
            color: rgba(244, 241, 236, .45);
            max-width: 260px;
            font-weight: 300;
            margin-bottom: 1.2rem;
        }

        .footer-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.35rem 0.85rem;
            border-radius: 100px;
            background: rgba(74, 124, 89, .15);
            border: 1px solid rgba(74, 124, 89, .25);
            font-size: 0.65rem;
            font-weight: 600;
            color: #7ec89a;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .footer-status-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #7ec89a;
            animation: blink 2s ease-in-out infinite;
        }

        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }

        /* Link cols */
        .footer-col-h {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: rgba(244, 241, 236, .4);
            margin-bottom: 1rem;
        }

        .footer-link {
            display: block;
            font-size: 0.83rem;
            font-weight: 300;
            color: rgba(244, 241, 236, .5);
            padding: 0.28rem 0;
            transition: color .15s;
        }
        .footer-link:hover { color: #f4f1ec; }

        /* ── Footer address + map ── */
        .footer-address-block {
            font-size: 0.8rem;
            font-weight: 300;
            line-height: 1.75;
            color: rgba(244, 241, 236, .5);
            margin-bottom: 1.1rem;
        }

        .footer-address-block strong {
            font-weight: 600;
            color: rgba(244, 241, 236, .8);
            display: block;
            margin-bottom: 0.25rem;
            font-size: 0.82rem;
        }

        .footer-address-icon-row {
            display: flex;
            align-items: flex-start;
            gap: 7px;
            margin-bottom: 0.45rem;
        }

        .footer-address-icon {
            width: 14px;
            height: 14px;
            flex-shrink: 0;
            margin-top: 2px;
            opacity: 0.5;
        }

        .footer-map-wrap {
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,.08);
            height: 130px;
            position: relative;
            background: #1e2a1b;
        }

        .footer-map-wrap iframe {
            width: 100%;
            height: 100%;
            border: none;
            display: block;
            filter: grayscale(1) invert(0.85) hue-rotate(140deg) brightness(0.7) contrast(0.9);
        }

        .footer-map-open {
            position: absolute;
            bottom: 7px;
            right: 7px;
            background: rgba(24, 30, 22, .85);
            color: #7ec89a;
            font-size: 0.62rem;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 6px;
            border: 1px solid rgba(126,200,154,.2);
            text-decoration: none;
            backdrop-filter: blur(4px);
            transition: background .15s;
        }
        .footer-map-open:hover { background: rgba(74, 124, 89, .5); }

        /* Bottom bar */
        .footer-bottom {
            padding: 1.1rem 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .footer-copy {
            font-size: 0.72rem;
            color: rgba(244, 241, 236, .25);
        }

        .footer-made {
            display: flex;
            align-items: center;
            gap: 1.2rem;
            font-size: 0.72rem;
            color: rgba(244, 241, 236, .25);
        }

        .footer-made-heart { color: #7ec89a; }

        /* ══════════════════════════════════
           MOBILE NAV
        ══════════════════════════════════ */
        .nav-mobile-toggle {
            display: none;
            width: 36px;
            height: 36px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--warm-line2);
            background: transparent;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-direction: column;
            gap: 4px;
            padding: 0;
        }

        .nav-mobile-toggle span {
            display: block;
            width: 16px;
            height: 1.5px;
            background: var(--ink-60);
            border-radius: 2px;
            transition: all .2s;
        }

        .nav-mobile-drawer {
            display: none;
            position: fixed;
            inset: var(--nav-height) 0 0 0;
            background: rgba(250, 248, 245, .97);
            backdrop-filter: blur(16px);
            z-index: 190;
            padding: 1.5rem max(1.5rem, calc((100vw - 1280px) / 2 + 1.5rem));
            flex-direction: column;
            gap: 0.25rem;
            overflow-y: auto;
        }

        .nav-mobile-drawer.open { display: flex; }

        .nav-mobile-link {
            padding: 0.85rem 1rem;
            border-radius: var(--radius-md);
            font-size: 1rem;
            font-weight: 500;
            color: var(--ink-60);
            transition: background .15s, color .15s;
        }
        .nav-mobile-link:hover,
        .nav-mobile-link.active { background: var(--warm-surface); color: var(--ink); }

        .nav-mobile-sep {
            height: 1px;
            background: var(--warm-line);
            margin: 0.5rem 0;
        }

        .nav-mobile-kat-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.25rem;
            padding: 0.25rem 0;
        }

        .nav-mobile-kat-item {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 0.65rem 0.75rem;
            border-radius: var(--radius-md);
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--ink-60);
            transition: background .15s, color .15s;
        }
        .nav-mobile-kat-item:hover { background: var(--warm-surface); color: var(--ink); }

        /* ══════════════════════════════════
           RESPONSIVE
        ══════════════════════════════════ */
        @media (max-width: 1100px) {
            .footer-inner { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 900px) {
            .nav-links { display: none; }
            .nav-actions .nav-btn-ghost,
            .nav-actions .nav-btn-primary,
            .nav-divider { display: none; }
            .nav-mobile-toggle { display: flex; }

            .footer-inner { grid-template-columns: 1fr; gap: 2rem; }
            .footer { padding-left: 1.5rem; padding-right: 1.5rem; }
        }

        @media (max-width: 480px) {
            .nav-user-name { display: none; }
        }
    </style>

    {{ $styles ?? '' }}
</head>
<body>

    {{-- ═══════ NAVBAR ═══════ --}}
    <nav class="nav">

        <a href="/" class="nav-logo">
            <span class="nav-logo-text"><img src="{{ asset('storage/brand.png') }}" alt="brand"></span>
        </a>

        <div class="nav-links">

            {{-- Katalog dengan dropdown kategori --}}
            <div class="nav-katalog-wrap">
                <a href="/katalog"
                   class="nav-katalog-trigger {{ request()->is('katalog*') ? 'active' : '' }}">
                    Katalog Alat
                    <svg class="nav-katalog-arrow" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 4.5L6 8L10 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>

                <div class="nav-katalog-dropdown">
                    <div class="nav-katalog-dropdown-header">
                        <span>Semua Kategori</span>
                        <a href="/katalog">Lihat semua →</a>
                    </div>

                    @isset($kategoris)
                        @php
                            $allKats   = collect([null])->concat($kategoris);
                            $chunkSize = (int) ceil($allKats->count() / 3);
                            $cols      = $allKats->chunk($chunkSize);
                        @endphp

                        @foreach($cols as $col)
                            <div class="nav-kat-col">
                                @foreach($col as $kat)
                                    @if(is_null($kat))
                                        {{-- "Semua Alat" entry --}}
                                        <a href="/katalog" class="nav-kat-item">
                                            <span class="nav-kat-icon">🔧</span>
                                            <span>Semua Alat</span>
                                        </a>
                                    @else
                                        <a href="/katalog?kategori={{ $kat->slug }}" class="nav-kat-item">
                                            <span class="nav-kat-icon">{{ $kat->ikon ?? '📦' }}</span>
                                            <span>{{ $kat->nama }}</span>
                                            @if(isset($kat->alats_count))
                                                <span class="nav-kat-count">{{ $kat->alats_count }}</span>
                                            @endif
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        @endforeach
                    @else
                        <div class="nav-kat-col">
                            <a href="/katalog" class="nav-kat-item">
                                <span class="nav-kat-icon">🔧</span>
                                <span>Semua Alat</span>
                            </a>
                        </div>
                    @endisset
                </div>
            </div>

            <a href="/#cara-kerja"  class="nav-link">Cara Sewa</a>
            <a href="/#tentang"     class="nav-link">Testimoni</a>
        </div>

        <div class="nav-actions">
            @auth
                <a href="{{ route('dashboard') }}" class="nav-user">
                    <div class="nav-user-av">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                    <span class="nav-user-name">{{ auth()->user()->name }}</span>
                </a>
                <div class="nav-divider"></div>
                <form method="POST" action="{{ route('logout') }}" style="margin:0">
                    @csrf
                    <button type="submit" class="nav-btn-ghost">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}"    class="nav-btn-ghost">Masuk</a>
                <a href="{{ route('register') }}" class="nav-btn-primary">Daftar Gratis</a>
            @endauth

            <button class="nav-mobile-toggle" id="mobileToggle" aria-label="Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </nav>

    {{-- Mobile Drawer --}}
    <div class="nav-mobile-drawer" id="mobileDrawer">
        <a href="/katalog" class="nav-mobile-link {{ request()->is('katalog*') ? 'active' : '' }}">Katalog Alat</a>

        {{-- Mobile kategori grid --}}
        @isset($kategoris)
            @if($kategoris->count())
                <div class="nav-mobile-kat-grid">
                    @foreach($kategoris as $kat)
                        <a href="/katalog?kategori={{ $kat->slug }}" class="nav-mobile-kat-item">
                            <span style="font-size:1rem">{{ $kat->ikon ?? '📦' }}</span>
                            <span>{{ $kat->nama }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
        @endisset

        <a href="/#cara-kerja" class="nav-mobile-link">Cara Sewa</a>
        <a href="/#tentang"    class="nav-mobile-link">Testimoni</a>
        <div class="nav-mobile-sep"></div>
        @auth
            <a href="{{ route('dashboard') }}" class="nav-mobile-link">Dashboard</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-mobile-link" style="width:100%;text-align:left;background:none;border:none;cursor:pointer;font-family:var(--font-body);font-size:1rem;font-weight:500;color:var(--ink-60);padding:.85rem 1rem;border-radius:var(--radius-md);transition:background .15s,color .15s">
                    Keluar
                </button>
            </form>
        @else
            <a href="{{ route('login') }}"    class="nav-mobile-link">Masuk</a>
            <a href="{{ route('register') }}" class="nav-mobile-link" style="color:var(--sage);font-weight:600">Daftar Gratis →</a>
        @endauth
    </div>

    {{-- ═══════ CONTENT ═══════ --}}
    <main>{{ $slot }}</main>

    {{-- ═══════ FOOTER ═══════ --}}
    <footer class="footer">
        <div class="footer-inner">

            {{-- Brand --}}
            <div>
                <div class="nav-logo" style="margin-bottom:0.9rem">
                    <span class="footer-logo-text"><img src="{{ asset('storage/brand.png') }}" alt="brand" style="height:36px;filter:brightness(0) invert(1);opacity:.85"></span>
                </div>
                <p class="footer-desc">
                    Solusi sewa alat rumah tangga yang mudah, terjangkau, dan terpercaya untuk kebutuhan sehari-hari.
                </p>
                <div class="footer-status">
                    <span class="footer-status-dot"></span>
                    Layanan aktif
                    @isset($stats)
                        — {{ $stats['tersedia'] ?? 0 }} alat tersedia
                    @endisset
                </div>
            </div>

            {{-- Navigasi --}}
            <div>
                <div class="footer-col-h">Navigasi</div>
                <a href="/"             class="footer-link">Beranda</a>
                <a href="/katalog"      class="footer-link">Katalog Alat</a>
                <a href="/#cara-kerja"  class="footer-link">Cara Sewa</a>
                <a href="/#tentang"     class="footer-link">Testimoni</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="footer-link">Dashboard</a>
                @else
                    <a href="{{ route('login') }}"    class="footer-link">Masuk</a>
                    <a href="{{ route('register') }}" class="footer-link">Daftar</a>
                @endauth
            </div>

            {{-- Kategori --}}
            <div>
                <div class="footer-col-h">Kategori</div>
                @isset($kategoris)
                    @foreach($kategoris->take(6) as $kat)
                        <a href="/katalog?kategori={{ $kat->slug }}" class="footer-link">
                            {{ $kat->nama }}
                        </a>
                    @endforeach
                @endisset
            </div>

            {{-- Alamat & Peta --}}
            <div>
                <div class="footer-col-h">Lokasi Kami</div>

                <div class="footer-address-block">
                    <strong>SewaAlat Bantul</strong>

                    <div class="footer-address-icon-row">
                        <svg class="footer-address-icon" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7 1C4.79 1 3 2.79 3 5c0 3 4 8 4 8s4-5 4-8c0-2.21-1.79-4-4-4Z" stroke="rgba(244,241,236,.5)" stroke-width="1.2" fill="none"/>
                            <circle cx="7" cy="5" r="1.5" stroke="rgba(244,241,236,.5)" stroke-width="1.2" fill="none"/>
                        </svg>
                        <span>Jl. Bantul No. 12, Bantul,<br>Yogyakarta 55711</span>
                    </div>

                    <div class="footer-address-icon-row">
                        <svg class="footer-address-icon" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 3.5C2 3 2.5 2.5 3 2.5h1.5l1 2.5-.75.75c.56 1.1 1.4 1.94 2.5 2.5l.75-.75 2.5 1V10c0 .5-.5 1-1 1C4.96 11 2 8.04 2 4.5v-1Z" stroke="rgba(244,241,236,.5)" stroke-width="1.2" fill="none"/>
                        </svg>
                        <span>+62 889-8566-1044</span>
                    </div>

                    <div class="footer-address-icon-row">
                        <svg class="footer-address-icon" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="1.5" y="3" width="11" height="8" rx="1.5" stroke="rgba(244,241,236,.5)" stroke-width="1.2" fill="none"/>
                            <path d="M1.5 5l5.5 3.5L12.5 5" stroke="rgba(244,241,236,.5)" stroke-width="1.2"/>
                        </svg>
                        <span>sewaalat@gmail.com</span>
                    </div>

                    <div class="footer-address-icon-row">
                        <svg class="footer-address-icon" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="7" cy="7" r="5" stroke="rgba(244,241,236,.5)" stroke-width="1.2" fill="none"/>
                            <path d="M7 4v3l2 1.5" stroke="rgba(244,241,236,.5)" stroke-width="1.2" stroke-linecap="round"/>
                        </svg>
                        <span>Senin–Sabtu, 08.00–17.00 WIB</span>
                    </div>
                </div>

                {{-- Map embed --}}
                <div class="footer-map-wrap">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63257.41822836944!2d110.28522857910156!3d-7.888054499999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7b3154d6aa7381%3A0x5027a76e355c528!2sBantul%2C%20Kabupaten%20Bantul%2C%20Daerah%20Istimewa%20Yogyakarta!5e0!3m2!1sid!2sid!4v1712000000000!5m2!1sid!2sid"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Lokasi SewaAlat Bantul">
                    </iframe>
                    <a href="https://maps.google.com/?q=Bantul,Yogyakarta" target="_blank" rel="noopener" class="footer-map-open">
                        Buka Maps ↗
                    </a>
                </div>
            </div>

        </div>

        <div class="footer-bottom">
            <span class="footer-copy">© {{ date('Y') }} SewaAlat. Hak cipta dilindungi.</span>
            <div class="footer-made">
                <span>Made with <span class="footer-made-heart">♥</span> in Indonesia</span>
            </div>
        </div>
    </footer>

    {{-- ═══════ SCRIPTS ═══════ --}}
    <script>
        const toggle = document.getElementById('mobileToggle');
        const drawer = document.getElementById('mobileDrawer');

        toggle.addEventListener('click', () => {
            drawer.classList.toggle('open');
            const isOpen = drawer.classList.contains('open');
            document.body.style.overflow = isOpen ? 'hidden' : '';
            const lines = toggle.querySelectorAll('span');
            if (isOpen) {
                lines[0].style.transform = 'translateY(5.5px) rotate(45deg)';
                lines[1].style.opacity   = '0';
                lines[2].style.transform = 'translateY(-5.5px) rotate(-45deg)';
            } else {
                lines[0].style.transform = '';
                lines[1].style.opacity   = '';
                lines[2].style.transform = '';
            }
        });

        drawer.querySelectorAll('a').forEach(a => {
            a.addEventListener('click', () => {
                drawer.classList.remove('open');
                document.body.style.overflow = '';
                toggle.querySelectorAll('span').forEach(s => {
                    s.style.transform = ''; s.style.opacity = '';
                });
            });
        });

        const revealEls = document.querySelectorAll('.reveal');
        if (revealEls.length) {
            const revealObs = new IntersectionObserver(entries => {
                entries.forEach(e => {
                    if (e.isIntersecting) {
                        e.target.classList.add('visible');
                        revealObs.unobserve(e.target);
                    }
                });
            }, { threshold: 0.12 });
            revealEls.forEach(el => revealObs.observe(el));
        }
    </script>

    {{ $scripts ?? '' }}

</body>
</html>