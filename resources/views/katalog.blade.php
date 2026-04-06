<x-public-layout
    title="Katalog Alat — SewaAlat"
    description="Temukan alat yang Anda butuhkan. Ribuan alat rumah tangga siap disewa kapan saja."
    :kategoris="$kategoris">

    <x-slot name="styles">
    <style>
        /* ─────────────────────────────────────────
           LOCAL TOKENS
        ───────────────────────────────────────── */
        :root {
            --green:      #4a7c59;
            --green-l:    #6a9e78;
            --green-pale: #eef4f0;
            --amber:      #d97706;
            --red:        #c0392b;
            --white:      #ffffff;
            --cream:      #faf8f5;
            --cream-d:    #f4f1ec;
            --cream-dd:   #e8e3db;
            --ink:        #1c1c1a;
            --ink-40:     #9e9b96;
            --ink-20:     #ccc9c4;
            --border:     rgba(28,28,26,.1);
            --shadow-lg:  0 20px 60px rgba(28,28,26,.12);
            --font-display: 'DM Serif Display', Georgia, serif;
            --font-body:    'DM Sans', system-ui, sans-serif;
            --font-mono:    'DM Mono', 'Courier New', monospace;
        }

        /* ─────────────────────────────────────────
           HERO
        ───────────────────────────────────────── */
        .k-hero {
            padding: 3.5rem max(2rem, calc((100vw - 1280px)/2 + 2rem)) 0;
        }

        .k-hero-inner {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 2rem;
            flex-wrap: wrap;
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--border);
        }

        .k-hero-left { flex: 1; min-width: 280px; }

        .k-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .15em;
            color: var(--green);
            margin-bottom: 0.75rem;
        }

        .k-eyebrow-line {
            width: 24px;
            height: 1.5px;
            background: var(--green);
            border-radius: 2px;
        }

        .k-hero-title {
            font-family: var(--font-display);
            font-size: clamp(2.4rem, 5vw, 3.8rem);
            font-weight: 400;
            line-height: 1.0;
            letter-spacing: -0.02em;
            color: var(--ink);
            margin-bottom: 0.9rem;
        }

        .k-hero-title em {
            font-style: italic;
            color: var(--green);
        }

        .k-hero-sub {
            font-size: 0.88rem;
            color: var(--ink-40);
            line-height: 1.65;
            max-width: 380px;
        }

        .k-hero-stats {
            display: flex;
            align-items: flex-end;
            gap: 2rem;
            flex-shrink: 0;
        }

        .k-stat { text-align: right; }

        .k-stat-num {
            font-family: var(--font-display);
            font-size: 2.2rem;
            font-weight: 400;
            color: var(--ink);
            line-height: 1;
            letter-spacing: -0.03em;
        }

        .k-stat-label {
            font-size: 0.62rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: var(--ink-40);
            margin-top: 0.2rem;
        }

        .k-stat-divider {
            width: 1px;
            height: 40px;
            background: var(--border);
            align-self: center;
        }

        /* ─────────────────────────────────────────
           LAYOUT
        ───────────────────────────────────────── */
        .k-layout {
            padding: 2.5rem max(2rem, calc((100vw - 1280px)/2 + 2rem));
            display: grid;
            grid-template-columns: 220px 1fr;
            gap: 2.5rem;
            align-items: start;
        }

        /* ─────────────────────────────────────────
           SIDEBAR
        ───────────────────────────────────────── */
        .k-sidebar {
            position: sticky;
            top: 78px;
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }

        .k-sidebar-section {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
            margin-top: 5px
        }

        .k-sidebar-head {
            padding: 0.8rem 1.1rem;
            font-size: 0.6rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: var(--ink-40);
            border-bottom: 1px solid var(--border);
            background: var(--cream-d);
        }

        .k-sidebar-body { padding: 0.5rem; }

        .k-kat-item {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 0.5rem 0.7rem;
            border-radius: 9px;
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--ink-40);
            text-decoration: none;
            transition: background .15s, color .15s;
        }

        .k-kat-item:hover { background: var(--green-pale); color: var(--green); }

        .k-kat-item.active {
            background: var(--green);
            color: #fff;
            font-weight: 600;
        }

        .k-kat-icon { font-size: 1rem; line-height: 1; flex-shrink: 0; }

        .k-kat-badge {
            margin-left: auto;
            font-size: 0.58rem;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 100px;
            background: rgba(28,28,26,.07);
            color: var(--ink-40);
            flex-shrink: 0;
        }

        .k-kat-item.active .k-kat-badge {
            background: rgba(255,255,255,.22);
            color: rgba(255,255,255,.9);
        }

        /* Toggle ketersediaan */
        .k-toggle-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1.1rem;
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--ink);
            cursor: pointer;
        }

        .k-toggle {
            position: relative;
            width: 36px;
            height: 20px;
            flex-shrink: 0;
        }

        .k-toggle input { opacity: 0; width: 0; height: 0; position: absolute; }

        .k-toggle-track {
            position: absolute;
            inset: 0;
            border-radius: 100px;
            background: var(--cream-dd);
            transition: background .2s;
            cursor: pointer;
        }

        .k-toggle-track::after {
            content: '';
            position: absolute;
            top: 3px; left: 3px;
            width: 14px; height: 14px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 1px 4px rgba(0,0,0,.15);
            transition: transform .2s;
        }

        .k-toggle input:checked + .k-toggle-track { background: var(--green); }
        .k-toggle input:checked + .k-toggle-track::after { transform: translateX(16px); }

        /* Sort */
        .k-sort-select {
            width: 100%;
            background: none;
            border: none;
            padding: 0.75rem 2.2rem 0.75rem 1.1rem;
            font-family: var(--font-body);
            font-size: 0.82rem;
            color: var(--ink);
            outline: none;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath d='M2 4.5L6 8L10 4.5' stroke='%239e9b96' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round' fill='none'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
        }

        .k-reset-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            width: 100%;
            padding: 0.6rem 1rem;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--ink-40);
            background: none;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            cursor: pointer;
            font-family: var(--font-body);
            transition: all .15s;
            text-decoration: none;
            text-align: center;
            margin-top: 5px;
        }

        .k-reset-btn:hover { border-color: var(--red); color: var(--red); }

        /* ─────────────────────────────────────────
           SEARCH BAR
        ───────────────────────────────────────── */
        .k-searchbar {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .k-search-wrap {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 0.65rem;
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: 11px;
            padding: 0 1rem;
            transition: border-color .18s, box-shadow .18s;
        }

        .k-search-wrap:focus-within {
            border-color: var(--green);
            box-shadow: 0 0 0 3px rgba(74,124,89,.1);
        }

        .k-search-input {
            flex: 1;
            background: none;
            border: none;
            outline: none;
            padding: 0.75rem 0;
            font-family: var(--font-body);
            font-size: 0.88rem;
            color: var(--ink);
        }

        .k-search-input::placeholder { color: var(--ink-20); }

        .k-search-btn {
            padding: 0.72rem 1.4rem;
            border-radius: 8px;
            font-size: 0.84rem;
            font-weight: 700;
            background: var(--green);
            color: #fff;
            border: none;
            cursor: pointer;
            font-family: var(--font-body);
            transition: background .18s, transform .15s;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .k-search-btn:hover { background: var(--green-l); transform: translateY(-1px); }

        .k-result-pill {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 0.44rem 0.9rem;
            border-radius: 100px;
            background: var(--cream-d);
            border: 1px solid var(--border);
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--ink-40);
            white-space: nowrap;
        }

        .k-result-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--green);
            animation: kpulse 2.2s ease-in-out infinite;
        }

        @keyframes kpulse { 0%,100%{opacity:1} 50%{opacity:.25} }

        /* Active filter chips */
        .k-active-filters {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            flex-wrap: wrap;
            margin-bottom: 1.25rem;
        }

        .k-filter-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 0.28rem 0.7rem;
            border-radius: 100px;
            font-size: 0.72rem;
            font-weight: 600;
            background: var(--green);
            color: #fff;
            text-decoration: none;
            transition: background .15s;
        }

        .k-filter-chip:hover { background: #c0392b; }
        .k-filter-chip-x { font-size: 0.65rem; opacity: .8; }

        /* ─────────────────────────────────────────
           GRID & CARDS
        ───────────────────────────────────────── */
        .k-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
            gap: 1.25rem;
        }

        .k-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            position: relative;
            transition: transform .22s cubic-bezier(.34,1.56,.64,1), box-shadow .22s, border-color .22s;
            animation: cardIn .45s cubic-bezier(.22,1,.36,1) both;
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .k-card:nth-child(1)  { animation-delay: .03s; }
        .k-card:nth-child(2)  { animation-delay: .07s; }
        .k-card:nth-child(3)  { animation-delay: .11s; }
        .k-card:nth-child(4)  { animation-delay: .15s; }
        .k-card:nth-child(5)  { animation-delay: .19s; }
        .k-card:nth-child(6)  { animation-delay: .23s; }
        .k-card:nth-child(7)  { animation-delay: .27s; }
        .k-card:nth-child(8)  { animation-delay: .31s; }
        .k-card:nth-child(9)  { animation-delay: .35s; }
        .k-card:nth-child(10) { animation-delay: .38s; }
        .k-card:nth-child(11) { animation-delay: .41s; }
        .k-card:nth-child(12) { animation-delay: .44s; }

        .k-card:hover {
            transform: translateY(-6px) scale(1.01);
            box-shadow: var(--shadow-lg);
            border-color: rgba(74,124,89,.2);
        }

        /* Thumb */
        .k-thumb {
            width: 100%;
            height: 185px;
            position: relative;
            overflow: hidden;
            background: linear-gradient(145deg, var(--cream-d), var(--cream-dd));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            flex-shrink: 0;
        }

        .k-thumb img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .4s cubic-bezier(.25,1,.5,1);
        }

        .k-card:hover .k-thumb img { transform: scale(1.07); }

        /* Subtle gradient vignette on thumb */
        .k-thumb-vignette {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(28,28,26,.2) 0%, transparent 55%);
            pointer-events: none;
        }

        /* Badges */
        .k-badge-pop {
            position: absolute;
            top: 0.65rem; left: 0.65rem;
            z-index: 2;
            font-size: 0.55rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .1em;
            padding: 0.22rem 0.6rem;
            border-radius: 100px;
            background: var(--amber);
            color: #fff;
        }

        .k-badge-avail {
            position: absolute;
            top: 0.65rem; right: 0.65rem;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 0.58rem;
            font-weight: 700;
            padding: 0.22rem 0.55rem;
            border-radius: 100px;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .k-avail-ok { background: rgba(255,255,255,.9); color: var(--green); }
        .k-avail-no { background: rgba(255,255,255,.9); color: var(--red); }

        .k-avail-dot { width: 5px; height: 5px; border-radius: 50%; flex-shrink: 0; }
        .k-avail-ok .k-avail-dot { background: var(--green); animation: kdot 2s ease-in-out infinite; }
        .k-avail-no .k-avail-dot { background: var(--red); }

        @keyframes kdot { 0%,100%{opacity:1} 50%{opacity:.2} }

        /* Card body */
        .k-card-body {
            padding: 1rem 1.1rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .k-card-cat {
            font-size: 0.6rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: var(--green);
            margin-bottom: 0.35rem;
        }

        .k-card-name {
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--ink);
            line-height: 1.35;
            margin-bottom: 0.2rem;
            flex: 1;
        }

        .k-card-merk {
            font-size: 0.67rem;
            color: var(--ink-40);
            font-family: var(--font-mono);
            margin-bottom: 0.9rem;
        }

        .k-card-foot {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 0.85rem;
            border-top: 1px solid var(--border);
            gap: 0.5rem;
        }

        .k-price-lbl {
            font-size: 0.54rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--ink-40);
            margin-bottom: 0.1rem;
        }

        .k-price {
            font-family: var(--font-mono);
            font-size: 0.88rem;
            font-weight: 500;
            color: var(--ink);
        }

        .k-price-unit { font-size: 0.6rem; color: var(--ink-40); }

        .k-btn-sewa {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            padding: 0.44rem 0.9rem;
            border-radius: 8px;
            font-size: 0.74rem;
            font-weight: 700;
            background: var(--green);
            color: #fff;
            text-decoration: none;
            transition: background .15s, transform .15s;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .k-btn-sewa:hover { background: var(--green-l); transform: scale(1.04); }

        .k-btn-habis {
            padding: 0.44rem 0.8rem;
            border-radius: 8px;
            font-size: 0.74rem;
            font-weight: 700;
            background: var(--cream-dd);
            color: var(--ink-20);
            cursor: not-allowed;
            flex-shrink: 0;
        }

        /* ─────────────────────────────────────────
           EMPTY STATE
        ───────────────────────────────────────── */
        .k-empty {
            padding: 5rem 2rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }

        .k-empty-icon {
            width: 72px; height: 72px;
            border-radius: 20px;
            background: var(--cream-d);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .k-empty-title {
            font-family: var(--font-display);
            font-size: 1.4rem;
            color: var(--ink);
            font-weight: 400;
        }

        .k-empty-sub {
            font-size: 0.84rem;
            color: var(--ink-40);
            max-width: 280px;
            line-height: 1.6;
        }

        /* ─────────────────────────────────────────
           PAGINATION
        ───────────────────────────────────────── */
        .k-pag {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 2.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
            flex-wrap: wrap;
            gap: 1rem;
        }

        .k-pag-info { font-size: 0.78rem; color: var(--ink-40); }

        .k-pag-links { display: flex; align-items: center; gap: 0.3rem; }

        .k-pag-link {
            min-width: 36px; height: 36px;
            padding: 0 0.6rem;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem; font-weight: 700;
            background: var(--white);
            border: 1px solid var(--border);
            color: var(--ink-40);
            text-decoration: none;
            transition: all .15s;
        }

        .k-pag-link:hover, .k-pag-link.active {
            background: var(--green);
            color: #fff;
            border-color: var(--green);
        }

        .k-pag-link.disabled { opacity: .3; pointer-events: none; }
        .k-pag-dots { font-size: 0.8rem; color: var(--ink-40); padding: 0 0.25rem; }

        /* ─────────────────────────────────────────
           RESPONSIVE
        ───────────────────────────────────────── */
        @media (max-width: 960px) {
            .k-layout { grid-template-columns: 1fr; }
            .k-sidebar { position: static; }
            .k-hero-stats { display: none; }
        }

        @media (max-width: 640px) {
            .k-grid { grid-template-columns: repeat(2, 1fr); }
            .k-searchbar { flex-wrap: wrap; }
            .k-result-pill { display: none; }
        }

        @media (max-width: 380px) {
            .k-grid { grid-template-columns: 1fr; }
        }
    </style>
    </x-slot>

    {{-- ════ HERO ════ --}}
    <!-- <section class="k-hero">
        <div class="k-hero-inner">
            <div class="k-hero-left">
                <div class="k-eyebrow">
                    <span class="k-eyebrow-line"></span>
                    Katalog Lengkap
                </div>
            </div>
        </div>
    </section> -->

    {{-- ════ LAYOUT ════ --}}
    <div class="k-layout">

        {{-- ── SIDEBAR ── --}}
        <aside class="k-sidebar">
            <form method="GET" action="{{ route('katalog') }}" id="filterForm">

                {{-- Kategori --}}
                <div class="k-sidebar-section">
                    <div class="k-sidebar-head">Kategori</div>
                    <div class="k-sidebar-body">
                        <a href="{{ route('katalog', array_filter(['tersedia' => request('tersedia'), 'sort' => $sort !== 'populer' ? $sort : null, 'q' => request('q')])) }}"
                           class="k-kat-item {{ !request('kategori') ? 'active' : '' }}">
                            <span class="k-kat-icon">🔧</span>
                            <span>Semua Alat</span>
                            <span class="k-kat-badge">{{ $alats->total() }}</span>
                        </a>
                        @foreach($kategoris as $kat)
                            <a href="{{ route('katalog', ['kategori' => $kat->slug, 'tersedia' => request('tersedia'), 'sort' => $sort, 'q' => request('q')]) }}"
                               class="k-kat-item {{ request('kategori') === $kat->slug ? 'active' : '' }}">
                                <span class="k-kat-icon">{{ $kat->ikon ?? '📦' }}</span>
                                <span>{{ $kat->nama }}</span>
                                <span class="k-kat-badge">{{ $kat->alats_count }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Ketersediaan --}}
                <div class="k-sidebar-section">
                    <label class="k-toggle-row" for="tersedia-toggle">
                        <span>Tersedia saja</span>
                        <div class="k-toggle">
                            <input type="checkbox"
                                   id="tersedia-toggle"
                                   name="tersedia"
                                   value="1"
                                   {{ request('tersedia') ? 'checked' : '' }}
                                   onchange="document.getElementById('filterForm').submit()">
                            <span class="k-toggle-track"></span>
                        </div>
                    </label>
                </div>

                {{-- Urutkan --}}
                <div class="k-sidebar-section">
                    <div class="k-sidebar-head">Urutkan</div>
                    <select name="sort" class="k-sort-select"
                            onchange="document.getElementById('filterForm').submit()">
                        <option value="populer"    {{ $sort === 'populer'    ? 'selected' : '' }}>✦ Paling Populer</option>
                        <option value="harga_asc"  {{ $sort === 'harga_asc'  ? 'selected' : '' }}>↑ Harga Terendah</option>
                        <option value="harga_desc" {{ $sort === 'harga_desc' ? 'selected' : '' }}>↓ Harga Tertinggi</option>
                        <option value="nama"       {{ $sort === 'nama'       ? 'selected' : '' }}>A–Z Nama</option>
                    </select>
                </div>

                @if(request('kategori'))<input type="hidden" name="kategori" value="{{ request('kategori') }}">@endif
                @if(request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif

                {{-- Reset --}}
                @if(request()->hasAny(['kategori', 'tersedia', 'q']) || $sort !== 'populer')
                    <a href="{{ route('katalog') }}" class="k-reset-btn">
                        <svg width="11" height="11" viewBox="0 0 12 12" fill="none">
                            <path d="M9 3L3 9M3 3l6 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        Hapus semua filter
                    </a>
                @endif

            </form>
        </aside>

        {{-- ── MAIN ── --}}
        <main class="k-main">

            {{-- Search --}}
            <form method="GET" action="{{ route('katalog') }}">
                @if(request('kategori'))<input type="hidden" name="kategori" value="{{ request('kategori') }}">@endif
                @if(request('tersedia'))<input type="hidden" name="tersedia" value="1">@endif
                <input type="hidden" name="sort" value="{{ $sort }}">

                <div class="k-searchbar">
                    <div class="k-search-wrap">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" style="color:var(--ink-40);flex-shrink:0">
                            <circle cx="7" cy="7" r="5" stroke="currentColor" stroke-width="1.5"/>
                            <path d="m11 11 3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <input class="k-search-input" type="text" name="q"
                               value="{{ request('q') }}"
                               placeholder="Cari alat, merek, atau kode...">
                        @if(request('q'))
                            <a href="{{ route('katalog', request()->except('q')) }}"
                               style="color:var(--ink-40);font-size:0.8rem;text-decoration:none;flex-shrink:0;line-height:1">✕</a>
                        @endif
                    </div>
                    <button type="submit" class="k-search-btn">Cari</button>
                    <div class="k-result-pill">
                        <span class="k-result-dot"></span>
                        {{ $alats->total() }} alat
                    </div>
                </div>
            </form>

            {{-- Active filter chips --}}
            @if(request()->hasAny(['kategori', 'tersedia', 'q']))
                <div class="k-active-filters">
                    @if(request('kategori'))
                        @php $activeKat = $kategoris->firstWhere('slug', request('kategori')); @endphp
                        @if($activeKat)
                            <a href="{{ route('katalog', request()->except('kategori')) }}" class="k-filter-chip">
                                {{ $activeKat->ikon ?? '' }} {{ $activeKat->nama }}
                                <span class="k-filter-chip-x">✕</span>
                            </a>
                        @endif
                    @endif
                    @if(request('tersedia'))
                        <a href="{{ route('katalog', request()->except('tersedia')) }}" class="k-filter-chip">
                            ✓ Tersedia saja <span class="k-filter-chip-x">✕</span>
                        </a>
                    @endif
                    @if(request('q'))
                        <a href="{{ route('katalog', request()->except('q')) }}" class="k-filter-chip">
                            "{{ Str::limit(request('q'), 20) }}" <span class="k-filter-chip-x">✕</span>
                        </a>
                    @endif
                </div>
            @endif

            {{-- Grid --}}
            @if($alats->isEmpty())
                <div class="k-empty">
                    <div class="k-empty-icon">🔍</div>
                    <div class="k-empty-title">Tidak ada alat ditemukan</div>
                    <p class="k-empty-sub">Coba kata kunci lain atau hapus beberapa filter untuk melihat lebih banyak alat.</p>
                    <a href="{{ route('katalog') }}"
                       style="margin-top:1.2rem;color:var(--green);font-size:0.84rem;font-weight:600;text-decoration:none">
                        ← Lihat semua alat
                    </a>
                </div>
            @else
                <div class="k-grid">
                    @foreach($alats as $alat)
                        <div class="k-card">
                            {{-- Thumb --}}
                            <div class="k-thumb">
                                @if($alat->foto)
                                    <img src="{{ asset('storage/'.$alat->foto) }}" alt="{{ $alat->nama }}" loading="lazy">
                                @else
                                    {{ $alat->kategori?->ikon ?? '🔧' }}
                                @endif
                                <div class="k-thumb-vignette"></div>

                                @if($alat->peminjamans_count >= 5)
                                    <div class="k-badge-pop">🔥 Populer</div>
                                @endif

                                <div class="k-badge-avail {{ $alat->stok_tersedia > 0 ? 'k-avail-ok' : 'k-avail-no' }}">
                                    <span class="k-avail-dot"></span>
                                    {{ $alat->stok_tersedia > 0 ? $alat->stok_tersedia.' unit' : 'Habis' }}
                                </div>
                            </div>

                            {{-- Body --}}
                            <div class="k-card-body">
                                <div class="k-card-cat">
                                    {{ $alat->kategori?->ikon ?? '' }}
                                    {{ $alat->kategori?->nama ?? 'Alat' }}
                                </div>
                                <div class="k-card-name">{{ $alat->nama }}</div>
                                <div class="k-card-merk">
                                    {{ $alat->merk ? $alat->merk.' · ' : '' }}{{ $alat->kode }}
                                </div>

                                <div class="k-card-foot">
                                    <div>
                                        <div class="k-price-lbl">Harga sewa</div>
                                        <div>
                                            <span class="k-price">Rp {{ number_format($alat->harga_sewa_per_hari, 0, ',', '.') }}</span>
                                            <span class="k-price-unit">/hari</span>
                                        </div>
                                    </div>

                                    @if($alat->stok_tersedia > 0)
                                        @auth
                                            <a href="{{ route('peminjam.peminjamans.create', ['alat' => $alat->id]) }}"
                                               class="k-btn-sewa">Sewa →</a>
                                        @else
                                            <a href="{{ route('login') }}" class="k-btn-sewa">Sewa →</a>
                                        @endauth
                                    @else
                                        <span class="k-btn-habis">Habis</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($alats->hasPages())
                    @php
                        $cur   = $alats->currentPage();
                        $last  = $alats->lastPage();
                        $pages = array_unique(array_filter([1, $cur-1, $cur, $cur+1, $last]));
                        sort($pages);
                    @endphp
                    <nav class="k-pag">
                        <span class="k-pag-info">
                            Menampilkan {{ $alats->firstItem() }}–{{ $alats->lastItem() }}
                            dari {{ $alats->total() }} alat
                        </span>
                        <div class="k-pag-links">
                            <a href="{{ $alats->previousPageUrl() }}"
                               class="k-pag-link {{ $alats->onFirstPage() ? 'disabled' : '' }}">‹</a>

                            @foreach($pages as $i => $pg)
                                @if($i > 0 && $pages[$i-1] < $pg - 1)
                                    <span class="k-pag-dots">…</span>
                                @endif
                                <a href="{{ $alats->url($pg) }}"
                                   class="k-pag-link {{ $pg == $cur ? 'active' : '' }}">{{ $pg }}</a>
                            @endforeach

                            <a href="{{ $alats->nextPageUrl() }}"
                               class="k-pag-link {{ !$alats->hasMorePages() ? 'disabled' : '' }}">›</a>
                        </div>
                    </nav>
                @endif
            @endif

        </main>
    </div>

</x-public-layout>