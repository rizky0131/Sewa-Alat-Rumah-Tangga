<x-public-layout
    title="SewaAlat — Sewa Alat Rumah Tangga"
    :description="'Sewa alat rumah tangga berkualitas, mudah, dan terpercaya. Tersedia '.$stats['total_alat'].'+ alat siap pakai.'"
    :kategoris="$kategoris"
    :stats="$stats">

    <x-slot name="styles">
        <style>
            /* ── Google Fonts ─────────────────────── */
            @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&family=DM+Serif+Display:ital@0;1&display=swap');

            /* ── Design Tokens ────────────────────── */
            :root {
                --font-body: 'DM Sans', sans-serif;
                --font-display: 'DM Serif Display', serif;

                /* Palette */
                --sage:        #4a7c59;
                --sage-light:  #6a9e78;
                --sage-pale:   #eef4f0;
                --sage-mid:    #d4e8da;

                --warm-bg:     #faf8f5;
                --warm-surface:#f4f1ec;
                --warm-line:   #e8e3db;
                --warm-line2:  #d6cfC5;

                --ink:         #1c1c1a;
                --ink-60:      #5a5954;
                --ink-35:      #9e9b96;

                --amber:       #d97706;
                --red-soft:    #c0392b;

                --white:       #ffffff;

                --radius-sm:  6px;
                --radius-md:  10px;
                --radius-lg:  16px;
                --radius-xl:  24px;

                --shadow-sm:  0 1px 3px rgba(28,28,26,.07), 0 1px 2px rgba(28,28,26,.04);
                --shadow-md:  0 4px 14px rgba(28,28,26,.08), 0 2px 6px rgba(28,28,26,.04);
                --shadow-lg:  0 12px 32px rgba(28,28,26,.10), 0 4px 12px rgba(28,28,26,.06);
            }

            *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

            body {
                font-family: var(--font-body);
                background: var(--warm-bg);
                color: var(--ink);
                -webkit-font-smoothing: antialiased;
            }

            a { text-decoration: none; color: inherit; }

            /* ══════════════════════════════════════
               BANNER
            ══════════════════════════════════════ */
            .banner-wrap {
                position: relative;
                width: 100%;
                height: 560px;
                overflow: hidden;
                background: #1a2e23;
            }

            .banner-slide {
                position: absolute;
                inset: 0;
                opacity: 0;
                transition: opacity 1.2s ease-in-out;
            }

            .banner-slide.active { opacity: 1; z-index: 1; }

            .banner-slide img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: center;
            }

            /* scrim: dark bottom-to-top + left tint */
            .banner-scrim {
                position: absolute;
                inset: 0;
                background:
                    linear-gradient(to right, rgba(20,32,24,0.72) 0%, rgba(20,32,24,0.30) 55%, transparent 100%),
                    linear-gradient(to top,    rgba(20,32,24,0.60) 0%, transparent 55%);
                z-index: 2;
            }

            .banner-body {
                position: absolute;
                inset: 0;
                z-index: 3;
                display: flex;
                flex-direction: column;
                justify-content: center;
                padding: 0 max(2rem, calc((100vw - 1280px)/2 + 2rem));
            }

            .banner-tag {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                background: rgba(255,255,255,.12);
                border: 1px solid rgba(255,255,255,.22);
                backdrop-filter: blur(6px);
                color: #fff;
                font-size: 0.72rem;
                font-weight: 600;
                letter-spacing: .1em;
                text-transform: uppercase;
                padding: 0.3rem 0.85rem;
                border-radius: 100px;
                width: fit-content;
                margin-bottom: 1.1rem;
            }

            .banner-tag-dot {
                width: 6px; height: 6px;
                border-radius: 50%;
                background: #6ee7a0;
                animation: blink 2s ease-in-out infinite;
            }

            @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }

            .banner-h {
                font-family: var(--font-display);
                font-size: clamp(2.2rem, 4.5vw, 3.8rem);
                font-weight: 400;
                color: #fff;
                line-height: 1.12;
                letter-spacing: -0.01em;
                margin-bottom: 0.85rem;
                max-width: 560px;
            }

            .banner-h em {
                font-style: italic;
                color: #a8dbb8;
            }

            .banner-p {
                font-size: 1rem;
                color: rgba(255,255,255,.72);
                line-height: 1.6;
                margin-bottom: 1.8rem;
                max-width: 380px;
                font-weight: 300;
            }

            .banner-actions {
                display: flex;
                gap: 0.75rem;
                align-items: center;
                flex-wrap: wrap;
            }

            .btn-primary {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 0.78rem 1.6rem;
                border-radius: var(--radius-md);
                font-size: 0.9rem;
                font-weight: 600;
                background: var(--sage);
                color: #fff;
                transition: background .2s, transform .2s, box-shadow .2s;
                font-family: var(--font-body);
            }
            .btn-primary:hover {
                background: var(--sage-light);
                transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(74,124,89,.4);
            }

            .btn-outline-light {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 0.76rem 1.4rem;
                border-radius: var(--radius-md);
                font-size: 0.88rem;
                font-weight: 500;
                border: 1.5px solid rgba(255,255,255,.35);
                color: rgba(255,255,255,.85);
                transition: all .2s;
                font-family: var(--font-body);
            }
            .btn-outline-light:hover {
                background: rgba(255,255,255,.1);
                border-color: rgba(255,255,255,.6);
                color: #fff;
            }

            /* dots */
            .banner-dots {
                position: absolute;
                bottom: 1.4rem;
                left: max(2rem, calc((100vw - 1280px)/2 + 2rem));
                z-index: 4;
                display: flex;
                gap: 6px;
            }

            .b-dot {
                width: 20px; height: 3px;
                border-radius: 2px;
                background: rgba(255,255,255,.35);
                cursor: pointer;
                transition: background .25s, width .25s;
            }
            .b-dot.active {
                width: 32px;
                background: #fff;
            }

            /* stat strip on banner */
            .banner-stats {
                position: absolute;
                bottom: 0;
                right: 0;
                z-index: 4;
                display: flex;
                background: rgba(255,255,255,.92);
                backdrop-filter: blur(10px);
                border-top-left-radius: var(--radius-lg);
                overflow: hidden;
            }

            .bs-item {
                padding: 1rem 1.5rem;
                border-left: 1px solid var(--warm-line);
                text-align: center;
            }
            .bs-item:first-child { border-left: none; }

            .bs-num {
                font-family: var(--font-display);
                font-size: 1.5rem;
                font-weight: 400;
                color: var(--ink);
                line-height: 1;
            }

            .bs-lbl {
                font-size: 0.65rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: .08em;
                color: var(--ink-35);
                margin-top: 3px;
            }

            /* ══════════════════════════════════════
               MARQUEE
            ══════════════════════════════════════ */
            .marquee-band {
                background: var(--sage);
                padding: 0.7rem 0;
                overflow: hidden;
            }

            .marquee-track {
                display: flex;
                gap: 0;
                white-space: nowrap;
                animation: marquee 30s linear infinite;
            }
            .marquee-track:hover { animation-play-state: paused; }

            .marquee-item {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 0 2rem;
                font-size: 0.7rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: .12em;
                color: rgba(255,255,255,.55);
            }
            .marquee-item .mi-icon { font-size: 0.95rem; }
            .marquee-sep {
                color: rgba(255,255,255,.3);
                font-size: 0.7rem;
            }

            @keyframes marquee {
                from { transform: translateX(0) }
                to   { transform: translateX(-50%) }
            }

            /* ══════════════════════════════════════
               LAYOUT HELPERS
            ══════════════════════════════════════ */
            .section {
                padding: 5rem max(2rem, calc((100vw - 1280px)/2 + 2rem));
            }

            .section-alt { background: var(--warm-surface); }

            .section-dark {
                background: #182620;
                color: var(--warm-bg);
            }

            /* section header */
            .sec-eyebrow {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                font-size: 0.7rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .14em;
                color: var(--sage);
                margin-bottom: 0.6rem;
            }
            .sec-eyebrow::before {
                content: '';
                width: 18px; height: 1.5px;
                background: var(--sage);
                display: block;
            }
            .section-dark .sec-eyebrow { color: #6ee7a0; }
            .section-dark .sec-eyebrow::before { background: #6ee7a0; }

            .sec-h2 {
                font-family: var(--font-display);
                font-size: clamp(1.7rem, 2.8vw, 2.5rem);
                font-weight: 400;
                letter-spacing: -0.015em;
                line-height: 1.15;
                color: var(--ink);
                margin-bottom: 0.5rem;
            }
            .section-dark .sec-h2 { color: #f4f1ec; }

            .sec-sub {
                font-size: 0.95rem;
                color: var(--ink-60);
                max-width: 440px;
                line-height: 1.65;
                font-weight: 300;
            }
            .section-dark .sec-sub { color: rgba(244,241,236,.5); }

            .sec-header {
                display: flex;
                align-items: flex-end;
                justify-content: space-between;
                margin-bottom: 2.5rem;
                flex-wrap: wrap;
                gap: 1rem;
            }

            .sec-link {
                font-size: 0.82rem;
                font-weight: 600;
                color: var(--sage);
                display: inline-flex;
                align-items: center;
                gap: 4px;
                transition: gap .15s;
            }
            .sec-link:hover { gap: 8px; }
            .section-dark .sec-link { color: #6ee7a0; }

            /* ══════════════════════════════════════
               KATEGORI PILLS
            ══════════════════════════════════════ */
            .kat-row {
                display: flex;
                flex-wrap: wrap;
                gap: 0.6rem;
                margin-bottom: 2.5rem;
            }

            .kat-pill {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 0.55rem 1rem;
                border-radius: 100px;
                font-size: 0.8rem;
                font-weight: 500;
                border: 1.5px solid var(--warm-line2);
                color: var(--ink-60);
                background: var(--white);
                transition: all .18s;
                cursor: pointer;
                font-family: var(--font-body);
            }
            .kat-pill:hover,
            .kat-pill.active {
                background: var(--sage);
                color: #fff;
                border-color: var(--sage);
                box-shadow: 0 3px 12px rgba(74,124,89,.22);
            }

            .kat-badge {
                font-size: 0.62rem;
                font-weight: 700;
                padding: 1px 6px;
                border-radius: 100px;
                background: var(--warm-surface);
                color: var(--ink-35);
                transition: background .18s, color .18s;
            }
            .kat-pill:hover .kat-badge,
            .kat-pill.active .kat-badge {
                background: rgba(255,255,255,.2);
                color: rgba(255,255,255,.9);
            }

            /* ══════════════════════════════════════
               ALAT CARDS
            ══════════════════════════════════════ */
            .alat-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(255px, 1fr));
                gap: 1.15rem;
            }

            .alat-card {
                background: var(--white);
                border: 1px solid var(--warm-line);
                border-radius: var(--radius-lg);
                overflow: hidden;
                transition: transform .22s, box-shadow .22s, border-color .22s;
                position: relative;
            }
            .alat-card:hover {
                transform: translateY(-5px);
                box-shadow: var(--shadow-lg);
                border-color: var(--sage-mid);
            }

            /* image area */
            .alat-img-area {
                position: relative;
                width: 100%;
                height: 176px;
                background: linear-gradient(145deg, var(--sage-pale) 0%, var(--warm-surface) 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
            }
            .alat-img-area img {
                width: 100%; height: 100%;
                object-fit: cover;
            }
            .alat-img-placeholder {
                font-size: 2.8rem;
                opacity: .55;
            }

            .badge-pop {
                position: absolute;
                top: 10px; left: 10px;
                font-size: 0.58rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .08em;
                padding: 3px 8px;
                border-radius: 100px;
                background: var(--amber);
                color: #fff;
            }

            .badge-status {
                position: absolute;
                top: 10px; right: 10px;
                display: inline-flex;
                align-items: center;
                gap: 4px;
                font-size: 0.6rem;
                font-weight: 700;
                padding: 3px 8px;
                border-radius: 100px;
                backdrop-filter: blur(6px);
            }
            .badge-ok  { background: rgba(74,124,89,.13); color: var(--sage); }
            .badge-no  { background: rgba(192,57,43,.1);  color: var(--red-soft); }

            .status-dot {
                width: 5px; height: 5px;
                border-radius: 50%;
            }
            .badge-ok .status-dot { background: var(--sage); }
            .badge-no .status-dot { background: var(--red-soft); }

            /* card body */
            .alat-body { padding: 1rem 1.1rem; }

            .alat-kat {
                font-size: 0.62rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .1em;
                color: var(--sage);
                margin-bottom: 4px;
                display: flex;
                align-items: center;
                gap: 4px;
            }

            .alat-name {
                font-size: 0.93rem;
                font-weight: 600;
                color: var(--ink);
                line-height: 1.35;
                margin-bottom: 2px;
            }

            .alat-meta {
                font-size: 0.7rem;
                color: var(--ink-35);
                margin-bottom: 0.9rem;
            }

            .alat-footer {
                display: flex;
                align-items: center;
                justify-content: space-between;
                border-top: 1px solid var(--warm-line);
                padding-top: 0.75rem;
            }

            .price-lbl {
                font-size: 0.58rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: .06em;
                color: var(--ink-35);
                margin-bottom: 1px;
            }

            .price-val {
                font-family: var(--font-display);
                font-size: 1rem;
                font-weight: 400;
                color: var(--ink);
            }

            .price-unit {
                font-size: 0.62rem;
                color: var(--ink-35);
                font-family: var(--font-body);
            }

            .btn-sewa {
                display: inline-flex;
                align-items: center;
                gap: 4px;
                padding: 0.45rem 0.95rem;
                border-radius: var(--radius-sm);
                font-size: 0.78rem;
                font-weight: 600;
                background: var(--sage);
                color: #fff;
                transition: background .18s, transform .15s;
                font-family: var(--font-body);
            }
            .btn-sewa:hover {
                background: var(--sage-light);
                transform: scale(1.04);
            }

            .btn-habis {
                padding: 0.45rem 0.95rem;
                border-radius: var(--radius-sm);
                font-size: 0.78rem;
                font-weight: 600;
                background: var(--warm-surface);
                color: var(--ink-35);
                cursor: not-allowed;
            }

            /* ══════════════════════════════════════
               HOW IT WORKS
            ══════════════════════════════════════ */
            .steps-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 1.25rem;
                margin-top: 2rem;
                position: relative;
            }

            /* connector line */
            .steps-grid::before {
                content: '';
                position: absolute;
                top: 2.4rem;
                left: calc(12.5% + 1.2rem);
                right: calc(12.5% + 1.2rem);
                height: 1px;
                background: repeating-linear-gradient(90deg,
                    var(--warm-line2) 0px, var(--warm-line2) 6px,
                    transparent 6px, transparent 12px);
                z-index: 0;
            }

            .step-card {
                background: var(--white);
                border: 1px solid var(--warm-line);
                border-radius: var(--radius-lg);
                padding: 1.6rem 1.35rem;
                position: relative;
                z-index: 1;
                transition: box-shadow .2s, border-color .2s;
            }
            .step-card:hover {
                box-shadow: var(--shadow-md);
                border-color: var(--sage-mid);
            }

            .step-num {
                width: 36px; height: 36px;
                border-radius: 50%;
                background: var(--sage-pale);
                color: var(--sage);
                font-size: 0.78rem;
                font-weight: 700;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 1rem;
                border: 1.5px solid var(--sage-mid);
            }

            .step-icon { font-size: 1.5rem; margin-bottom: 0.65rem; }

            .step-title {
                font-size: 0.9rem;
                font-weight: 600;
                color: var(--ink);
                margin-bottom: 0.4rem;
            }

            .step-desc {
                font-size: 0.78rem;
                color: var(--ink-60);
                line-height: 1.6;
                font-weight: 300;
            }

            /* ══════════════════════════════════════
               TESTIMONIALS
            ══════════════════════════════════════ */
            .testi-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 1.15rem;
                margin-top: 2.5rem;
            }

            .testi-card {
                padding: 1.6rem;
                background: rgba(255,255,255,.05);
                border: 1px solid rgba(244,241,236,.1);
                border-radius: var(--radius-lg);
                transition: background .2s, border-color .2s;
            }
            .testi-card:hover {
                background: rgba(255,255,255,.08);
                border-color: rgba(244,241,236,.18);
            }

            .testi-stars {
                color: #f59e0b;
                font-size: 0.75rem;
                letter-spacing: .1em;
                margin-bottom: 0.8rem;
            }

            .testi-text {
                font-size: 0.88rem;
                line-height: 1.7;
                color: rgba(244,241,236,.65);
                margin-bottom: 1.2rem;
                font-style: italic;
                font-weight: 300;
            }

            .testi-author {
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .testi-av {
                width: 36px; height: 36px;
                border-radius: 50%;
                flex-shrink: 0;
                background: var(--sage);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.72rem;
                font-weight: 700;
                color: #fff;
                letter-spacing: .02em;
            }

            .testi-name {
                font-size: 0.82rem;
                font-weight: 600;
                color: #f4f1ec;
            }

            .testi-loc {
                font-size: 0.68rem;
                color: rgba(244,241,236,.38);
                margin-top: 1px;
            }

            /* ══════════════════════════════════════
               CTA BAND
            ══════════════════════════════════════ */
            .cta-band {
                background: var(--sage);
                padding: 3.5rem max(2rem, calc((100vw - 1280px)/2 + 2rem));
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 2rem;
                position: relative;
                overflow: hidden;
            }

            /* subtle pattern */
            .cta-band::before {
                content: '';
                position: absolute;
                inset: 0;
                background-image: radial-gradient(circle at 70% 50%,
                    rgba(255,255,255,.07) 0%, transparent 65%);
                pointer-events: none;
            }

            .cta-h2 {
                font-family: var(--font-display);
                font-size: clamp(1.6rem, 2.8vw, 2.3rem);
                font-weight: 400;
                color: #fff;
                line-height: 1.15;
                letter-spacing: -0.01em;
            }

            .cta-sub {
                font-size: 0.9rem;
                color: rgba(255,255,255,.6);
                margin-top: 5px;
                font-weight: 300;
            }

            .btn-cta-white {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 0.85rem 2rem;
                border-radius: var(--radius-md);
                font-size: 0.9rem;
                font-weight: 600;
                background: #fff;
                color: var(--sage);
                transition: all .2s;
                white-space: nowrap;
                font-family: var(--font-body);
            }
            .btn-cta-white:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(0,0,0,.18);
            }

            /* ══════════════════════════════════════
               REVEAL ANIMATION
            ══════════════════════════════════════ */
            .reveal {
                opacity: 0;
                transform: translateY(20px);
                transition: opacity .55s ease, transform .55s ease;
            }
            .reveal.visible {
                opacity: 1;
                transform: translateY(0);
            }
            .reveal-d1 { transition-delay: .08s; }
            .reveal-d2 { transition-delay: .16s; }
            .reveal-d3 { transition-delay: .24s; }
            .reveal-d4 { transition-delay: .32s; }

            /* ══════════════════════════════════════
               VIEW MORE BUTTON
            ══════════════════════════════════════ */
            .btn-more {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 0.8rem 1.8rem;
                border-radius: var(--radius-md);
                font-size: 0.87rem;
                font-weight: 500;
                border: 1.5px solid var(--warm-line2);
                color: var(--ink-60);
                background: var(--white);
                transition: all .18s;
                font-family: var(--font-body);
            }
            .btn-more:hover {
                border-color: var(--sage);
                color: var(--sage);
                box-shadow: 0 2px 8px rgba(74,124,89,.12);
            }

            /* ══════════════════════════════════════
               RESPONSIVE
            ══════════════════════════════════════ */
            @media (max-width: 900px) {
                .banner-wrap { height: 480px; }
                .banner-stats { display: none; }
                .steps-grid { grid-template-columns: 1fr 1fr; }
                .steps-grid::before { display: none; }
                .testi-grid { grid-template-columns: 1fr; }
            }

            @media (max-width: 600px) {
                .banner-wrap { height: 420px; }
                .alat-grid { grid-template-columns: 1fr; }
                .steps-grid { grid-template-columns: 1fr; }
                .banner-h { font-size: 2rem; }
                .sec-h2 { font-size: 1.7rem; }
            }
        </style>
    </x-slot>

    {{-- ══════════════════════════════════════
         BANNER
    ══════════════════════════════════════ --}}
    <div class="banner-wrap" id="banner">
        <div class="banner-slide active" data-slide="0">
            <img src="{{ asset('storage/banner1.png') }}" alt="Banner 1">
        </div>
        <div class="banner-slide" data-slide="1">
            <img src="{{ asset('storage/banner2.png') }}" alt="Banner 2">
        </div>
        <div class="banner-slide" data-slide="2">
            <img src="{{ asset('storage/banner3.png') }}" alt="Banner 3">
        </div>

        <div class="banner-scrim"></div>

        <div class="banner-body">
            <div class="banner-tag">
                <span class="banner-tag-dot"></span>
                Promo Berlaku Minggu Ini
            </div>
            <h2 class="banner-h">
                Sewa Alat,<br>
                <em>Tanpa Beli</em>
            </h2>
            <p class="banner-p">
                Diskon hingga 30% untuk semua kategori alat rumah tangga.
                Cepat, mudah, dan terpercaya.
            </p>
            <div class="banner-actions">
                <a href="/katalog" class="btn-primary">
                    Lihat Katalog
                    <svg width="13" height="13" viewBox="0 0 13 13" fill="none">
                        <path d="M1 6.5h11M6.5 1l5.5 5.5-5.5 5.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
                <a href="#cara-kerja" class="btn-outline-light">Cara Kerja</a>
            </div>
        </div>

        <div class="banner-dots" id="bannerDots">
            <div class="b-dot active" data-idx="0"></div>
            <div class="b-dot" data-idx="1"></div>
            <div class="b-dot" data-idx="2"></div>
        </div>

        <div class="banner-stats">
            <div class="bs-item">
                <div class="bs-num" data-count="{{ $stats['total_alat'] }}">{{ $stats['total_alat'] }}+</div>
                <div class="bs-lbl">Jenis Alat</div>
            </div>
            <div class="bs-item">
                <div class="bs-num" data-count="{{ $stats['total_pinjam'] }}">{{ $stats['total_pinjam'] }}+</div>
                <div class="bs-lbl">Transaksi</div>
            </div>
            <div class="bs-item">
                <div class="bs-num" data-count="{{ $stats['tersedia'] }}">{{ $stats['tersedia'] }}</div>
                <div class="bs-lbl">Tersedia</div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════
         MARQUEE
    ══════════════════════════════════════ --}}
    <div class="marquee-band">
        <div class="marquee-track">
            @foreach(array_fill(0, 2, null) as $_)
                @foreach($kategoris as $kat)
                    <span class="marquee-item">
                        <span class="mi-icon">{{ $kat->ikon ?? '▸' }}</span>
                        {{ $kat->nama }}
                    </span>
                    <span class="marquee-item marquee-sep">·</span>
                @endforeach
                <span class="marquee-item"><span class="mi-icon">✦</span> Sewa Mudah & Cepat</span>
                <span class="marquee-item marquee-sep">·</span>
                <span class="marquee-item"><span class="mi-icon">✦</span> Harga Terjangkau</span>
                <span class="marquee-item marquee-sep">·</span>
                <span class="marquee-item"><span class="mi-icon">✦</span> Stok Selalu Update</span>
                <span class="marquee-item marquee-sep">·</span>
            @endforeach
        </div>
    </div>

    {{-- ══════════════════════════════════════
         KATALOG
    ══════════════════════════════════════ --}}
    <section class="section" id="katalog">
        <div class="sec-header">
            <div>
                <div class="sec-eyebrow">Katalog Kami</div>
                <h2 class="sec-h2">Alat Pilihan<br>Terpopuler</h2>
                <p class="sec-sub">Dipilih berdasarkan yang paling sering disewa pelanggan kami.</p>
            </div>
            <a href="/katalog" class="sec-link">Lihat semua alat →</a>
        </div>

        <div class="kat-row">
            <a href="/katalog" class="kat-pill active">
                Semua <span class="kat-badge">{{ $stats['total_alat'] }}</span>
            </a>
            @foreach($kategoris as $kat)
            <a href="/katalog?kategori={{ $kat->slug }}" class="kat-pill">
                {{ $kat->ikon ?? '' }} {{ $kat->nama }}
                <span class="kat-badge">{{ $kat->alats_count }}</span>
            </a>
            @endforeach
        </div>

        <div class="alat-grid">
            @foreach($alatUnggulan as $i => $alat)
            <div class="alat-card reveal reveal-d{{ ($i % 4) + 1 }}">
                @if($alat->foto)
                    <div class="alat-img-area">
                        <img src="{{ asset('storage/'.$alat->foto) }}" alt="{{ $alat->nama }}">
                        @if($alat->peminjamans_count >= 5)
                            <div class="badge-pop">Populer</div>
                        @endif
                        <div class="badge-status {{ $alat->stok_tersedia > 0 ? 'badge-ok' : 'badge-no' }}">
                            <span class="status-dot"></span>
                            {{ $alat->stok_tersedia > 0 ? $alat->stok_tersedia.' tersedia' : 'Habis' }}
                        </div>
                    </div>
                @else
                    <div class="alat-img-area">
                        <div class="alat-img-placeholder">{{ $alat->kategori?->ikon ?? '🔧' }}</div>
                        @if($alat->peminjamans_count >= 5)
                            <div class="badge-pop">Populer</div>
                        @endif
                        <div class="badge-status {{ $alat->stok_tersedia > 0 ? 'badge-ok' : 'badge-no' }}">
                            <span class="status-dot"></span>
                            {{ $alat->stok_tersedia > 0 ? $alat->stok_tersedia.' tersedia' : 'Habis' }}
                        </div>
                    </div>
                @endif

                <div class="alat-body">
                    <div class="alat-kat">{{ $alat->kategori?->ikon ?? '' }} {{ $alat->kategori?->nama ?? 'Alat' }}</div>
                    <div class="alat-name">{{ $alat->nama }}</div>
                    <div class="alat-meta">
                        {{ $alat->merk ?? '' }}{{ $alat->merk ? ' · ' : '' }}Stok {{ $alat->stok_total }} unit
                    </div>
                    <div class="alat-footer">
                        <div>
                            <div class="price-lbl">Harga sewa</div>
                            <div>
                                <span class="price-val">Rp {{ number_format($alat->harga_sewa_per_hari, 0, ',', '.') }}</span>
                                <span class="price-unit">/hari</span>
                            </div>
                        </div>
                        @if($alat->stok_tersedia > 0)
                            @auth
                                <a href="{{ route('peminjam.peminjamans.create', ['alat' => $alat->id]) }}" class="btn-sewa">Sewa →</a>
                            @else
                                <a href="{{ route('login') }}" class="btn-sewa">Sewa →</a>
                            @endauth
                        @else
                            <span class="btn-habis">Habis</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div style="text-align:center; margin-top:2.5rem">
            <a href="/katalog" class="btn-more">
                Lihat {{ $stats['total_alat'] }}+ Alat Lainnya →
            </a>
        </div>
    </section>

    {{-- ══════════════════════════════════════
         CARA KERJA
    ══════════════════════════════════════ --}}
    <section class="section section-alt" id="cara-kerja">
        <div class="sec-header">
            <div>
                <div class="sec-eyebrow">Cara Kerja</div>
                <h2 class="sec-h2">Sewa dalam<br>4 Langkah</h2>
                <p class="sec-sub">Proses yang simpel — dari pilih alat sampai pakai.</p>
            </div>
        </div>

        <div class="steps-grid">
            @foreach([
                ['','Pilih Alat','Telusuri katalog dan pilih alat yang kamu butuhkan.'],
                ['','Isi Formulir','Tentukan tanggal sewa dan tujuan penggunaan.'],
                ['','Disetujui','Petugas memverifikasi dan menyetujui permintaanmu.'],
                ['','Ambil & Pakai','Ambil alat, gunakan, dan kembalikan tepat waktu.'],
            ] as $i => [$icon, $title, $desc])
            <div class="step-card reveal reveal-d{{ $i + 1 }}">
                <div class="step-num">{{ $i + 1 }}</div>
                <div class="step-icon">{{ $icon }}</div>
                <div class="step-title">{{ $title }}</div>
                <div class="step-desc">{{ $desc }}</div>
            </div>
            @endforeach
        </div>
    </section>

    {{-- ══════════════════════════════════════
         TESTIMONIAL
    ══════════════════════════════════════ --}}
    <section class="section section-dark" id="tentang">
        <div class="sec-header">
            <div>
                <div class="sec-eyebrow">Testimoni</div>
                <h2 class="sec-h2">Kata Mereka<br>yang Sudah Sewa</h2>
            </div>
        </div>
        <div class="testi-grid">
            @foreach([
                ['Budi S.','Yogyakarta','Sangat membantu! Alat yang dipinjam dalam kondisi baik dan bersih. Prosesnya juga cepat dan mudah.'],
                ['Rina M.','Sleman','Tidak perlu beli alat mahal cukup untuk sekali pakai. Harga sewa sangat terjangkau dan pelayanannya ramah.'],
                ['Agus W.','Bantul','Prosedur peminjaman mudah, alat lengkap, dan petugas responsif. Recommended banget buat yang butuh alat sesekali!'],
            ] as [$nama, $kota, $teks])
            <div class="testi-card reveal">
                <div class="testi-stars">★★★★★</div>
                <p class="testi-text">"{{ $teks }}"</p>
                <div class="testi-author">
                    <div class="testi-av">{{ strtoupper(substr($nama, 0, 2)) }}</div>
                    <div>
                        <div class="testi-name">{{ $nama }}</div>
                        <div class="testi-loc">{{ $kota }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    {{-- ══════════════════════════════════════
         CTA BAND
    ══════════════════════════════════════ --}}
    <div class="cta-band">
        <div>
            <div class="cta-h2">Siap Mulai<br>Menyewa?</div>
            <div class="cta-sub">Daftar gratis dan mulai sewa dalam 5 menit.</div>
        </div>
        @auth
            <a href="/katalog" class="btn-cta-white">Lihat Katalog →</a>
        @else
            <a href="{{ route('register') }}" class="btn-cta-white">Daftar Sekarang →</a>
        @endauth
    </div>

    {{-- ══════════════════════════════════════
         SCRIPTS
    ══════════════════════════════════════ --}}
    <x-slot name="scripts">
        <script>
        (function () {
            /* ── Banner Slider ─────────────────── */
            const bannerSlides = document.querySelectorAll('.banner-slide');
            const bannerDots   = document.querySelectorAll('.b-dot');

            if (!bannerSlides.length) return;

            let bannerCurrent = 0;
            let bannerTimer   = null;

            function bannerGoTo(idx) {
                bannerSlides[bannerCurrent].classList.remove('active');
                bannerDots[bannerCurrent].classList.remove('active');
                bannerCurrent = (idx + bannerSlides.length) % bannerSlides.length;
                bannerSlides[bannerCurrent].classList.add('active');
                bannerDots[bannerCurrent].classList.add('active');
            }

            function bannerNext() {
                bannerGoTo(bannerCurrent + 1);
            }

            function bannerStart() {
                clearInterval(bannerTimer);
                bannerTimer = setInterval(bannerNext, 4000);
            }

            bannerDots.forEach(dot => {
                dot.addEventListener('click', () => {
                    bannerGoTo(parseInt(dot.dataset.idx));
                    bannerStart();
                });
            });

            /* pause on hover */
            const bannerWrap = document.getElementById('banner');
            if (bannerWrap) {
                bannerWrap.addEventListener('mouseenter', () => clearInterval(bannerTimer));
                bannerWrap.addEventListener('mouseleave', bannerStart);
            }

            bannerStart();

            /* ── Kategori active ───────────────── */
            document.querySelectorAll('.kat-pill').forEach(pill => {
                pill.addEventListener('click', function () {
                    document.querySelectorAll('.kat-pill').forEach(p => p.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        })();
        </script>
    </x-slot>

</x-public-layout>