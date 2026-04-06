<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajukan Sewa — SewaAlat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        :root {
            --ink:#1A1710;--ink-80:#2E2B21;--ink-40:#6B6654;--ink-20:#A09880;
            --cream:#F5F0E8;--cream-d:#EDE5D4;--cream-dd:#E3D8C2;
            --green:#2D5A27;--green-l:#3D7A35;--green-ll:#5A9E50;
            --amber:#C4860A;--red:#C0392B;--white:#FDFAF5;
            --border:rgba(26,23,16,0.12);--border-focus:rgba(45,90,39,0.45);
            --shadow:0 2px 20px rgba(26,23,16,0.1);--shadow-lg:0 8px 48px rgba(26,23,16,0.18);
            --font-display:'Playfair Display',Georgia,serif;
            --font-body:'DM Sans',system-ui,sans-serif;
            --font-mono:'DM Mono',monospace;
        }
        *,*::before,*::after { box-sizing:border-box;margin:0;padding:0; }
        html { scroll-behavior:smooth; }
        body { font-family:var(--font-body);background:var(--cream);color:var(--ink);overflow-x:hidden; }
        a { color:inherit;text-decoration:none; }

        /* Texture */
        body::before { content:'';position:fixed;inset:0;pointer-events:none;z-index:1;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23n)' opacity='0.035'/%3E%3C/svg%3E");opacity:0.6; }

        /* Nav */
        .nav { position:sticky;top:0;z-index:100;background:rgba(245,240,232,0.9);backdrop-filter:blur(12px);border-bottom:1px solid var(--border);padding:0 max(2rem,calc((100vw - 1280px)/2 + 2rem));display:flex;align-items:center;justify-content:space-between;height:64px; }
        .nav-logo { font-family:var(--font-display);font-size:1.35rem;font-weight:900;color:var(--ink);letter-spacing:-0.02em;display:flex;align-items:center;gap:0.4rem; }
        .nav-logo-dot { width:8px;height:8px;border-radius:50%;background:var(--green);display:inline-block;margin-bottom:3px; }
        .nav-breadcrumb { display:flex;align-items:center;gap:0.5rem;font-size:0.8rem;color:var(--ink-40); }
        .nav-breadcrumb a { transition:color .15s; }
        .nav-breadcrumb a:hover { color:var(--ink); }
        .nav-breadcrumb .sep { opacity:0.4; }
        .nav-user { display:flex;align-items:center;gap:0.75rem; }
        .user-av { width:32px;height:32px;border-radius:50%;background:var(--green);color:white;display:flex;align-items:center;justify-content:center;font-size:0.72rem;font-weight:800; }
        .user-name { font-size:0.82rem;font-weight:600;color:var(--ink-40); }

        /* Page */
        .page-wrap { max-width:1100px;margin:0 auto;padding:2.5rem max(1.5rem, 2vw); }
        .page-eyebrow { font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.14em;color:var(--green);margin-bottom:0.4rem;display:flex;align-items:center;gap:0.4rem; }
        .page-eyebrow::before { content:'';width:18px;height:1.5px;background:var(--green); }
        .page-h1 { font-family:var(--font-display);font-size:clamp(1.8rem,3.5vw,2.6rem);font-weight:900;letter-spacing:-0.025em;line-height:1.1;margin-bottom:0.4rem; }
        .page-sub { font-size:0.9rem;color:var(--ink-40);margin-bottom:2rem; }

        /* Alert flash */
        .flash-error, .flash-success {
            display:flex;align-items:flex-start;gap:0.65rem;padding:0.9rem 1.1rem;border-radius:8px;margin-bottom:1.4rem;font-size:0.85rem;font-weight:500;
        }
        .flash-error   { background:rgba(192,57,43,0.08);border:1px solid rgba(192,57,43,0.25);color:var(--red); }
        .flash-success { background:rgba(45,90,39,0.08);border:1px solid rgba(45,90,39,0.25);color:var(--green); }

        /* No HP banner — shown when user has no phone number */
        .nohp-banner {
            display:flex;align-items:flex-start;gap:0.75rem;padding:1rem 1.2rem;
            background:rgba(196,134,10,0.08);border:1.5px solid rgba(196,134,10,0.3);
            border-radius:10px;margin-bottom:1.5rem;
        }
        .nohp-banner-icon { font-size:1.4rem;flex-shrink:0;margin-top:0.1rem; }
        .nohp-banner-title { font-size:0.85rem;font-weight:800;color:var(--amber);margin-bottom:0.2rem; }
        .nohp-banner-desc  { font-size:0.78rem;color:var(--ink-40);line-height:1.5; }

        /* Layout */
        .form-layout { display:grid;grid-template-columns:1fr 340px;gap:1.5rem;align-items:start; }

        /* ── Form card ─────────────────────────────── */
        .form-card { background:var(--white);border:1px solid var(--border);border-radius:14px;overflow:hidden; }
        .form-section { padding:1.5rem 1.6rem;border-bottom:1px solid var(--border); }
        .form-section:last-child { border-bottom:none; }
        .form-section-title {
            display:flex;align-items:center;gap:0.55rem;
            font-size:0.75rem;font-weight:800;text-transform:uppercase;letter-spacing:0.1em;
            color:var(--ink-40);margin-bottom:1.2rem;
        }
        .form-section-num {
            width:22px;height:22px;border-radius:50%;background:var(--green);color:white;
            display:flex;align-items:center;justify-content:center;
            font-size:0.65rem;font-weight:800;flex-shrink:0;
        }

        /* Input styles */
        .form-group { margin-bottom:1.1rem; }
        .form-group:last-child { margin-bottom:0; }
        .form-label { display:block;font-size:0.78rem;font-weight:700;color:var(--ink);margin-bottom:0.4rem; }
        .form-label .req { color:var(--red);margin-left:2px; }
        .form-hint { font-size:0.7rem;color:var(--ink-40);margin-top:0.25rem; }
        .form-error-msg { font-size:0.7rem;color:var(--red);margin-top:0.25rem;font-weight:600; }

        .form-input, .form-textarea, .form-select {
            width:100%;background:var(--cream);border:1.5px solid var(--border);
            border-radius:8px;padding:0.7rem 0.9rem;
            font-family:var(--font-body);font-size:0.88rem;color:var(--ink);
            outline:none;transition:border-color .2s,box-shadow .2s;
        }
        .form-input:focus, .form-textarea:focus, .form-select:focus {
            border-color:var(--green);box-shadow:0 0 0 3px rgba(45,90,39,0.1);background:var(--white);
        }
        .form-input.error, .form-textarea.error, .form-select.error {
            border-color:var(--red);box-shadow:0 0 0 3px rgba(192,57,43,0.08);
        }
        .form-textarea { resize:vertical;min-height:100px; }

        /* Phone input with prefix */
        .phone-wrap { display:flex;align-items:stretch;gap:0; }
        .phone-prefix {
            flex-shrink:0;padding:0 0.85rem;background:var(--cream-dd);border:1.5px solid var(--border);
            border-right:none;border-radius:8px 0 0 8px;display:flex;align-items:center;
            font-size:0.85rem;font-weight:700;color:var(--ink-40);white-space:nowrap;
        }
        .phone-wrap .form-input {
            border-radius:0 8px 8px 0;
        }
        /* New phone badge */
        .new-badge {
            display:inline-flex;align-items:center;gap:0.3rem;margin-left:0.5rem;
            padding:0.1rem 0.45rem;background:rgba(196,134,10,0.12);border:1px solid rgba(196,134,10,0.3);
            border-radius:100px;font-size:0.62rem;font-weight:800;color:var(--amber);letter-spacing:0.05em;text-transform:uppercase;
            vertical-align:middle;
        }

        /* Alat search select */
        .alat-search-wrap { position:relative; }
        .alat-dropdown {
            position:absolute;top:100%;left:0;right:0;z-index:50;
            background:var(--white);border:1.5px solid var(--border);border-radius:8px;
            box-shadow:var(--shadow-lg);max-height:280px;overflow-y:auto;
            display:none;
        }
        .alat-dropdown.open { display:block; }
        .alat-opt {
            display:flex;align-items:center;gap:0.75rem;padding:0.85rem 1rem;
            cursor:pointer;transition:background .12s;border-bottom:1px solid var(--border);
        }
        .alat-opt:last-child { border-bottom:none; }
        .alat-opt:hover { background:var(--cream); }
        .alat-opt.selected { background:rgba(45,90,39,0.07); }
        .alat-opt-thumb {
            width:40px;height:40px;border-radius:8px;flex-shrink:0;overflow:hidden;
            background:var(--cream-d);display:flex;align-items:center;justify-content:center;font-size:1.1rem;
        }
        .alat-opt-thumb img { width:100%;height:100%;object-fit:cover; }
        .alat-opt-name { font-size:0.85rem;font-weight:700;color:var(--ink); }
        .alat-opt-meta { font-size:0.7rem;color:var(--ink-40); }
        .alat-opt-price { font-family:var(--font-mono);font-size:0.78rem;color:var(--green);margin-top:0.1rem; }
        .alat-opt-stok {
            margin-left:auto;flex-shrink:0;font-size:0.68rem;font-weight:700;
            padding:0.15rem 0.5rem;border-radius:100px;
        }
        .stok-ok { background:rgba(45,90,39,0.1);color:var(--green); }
        .stok-low { background:rgba(196,134,10,0.1);color:var(--amber); }

        /* Selected alat card */
        .selected-alat {
            display:none;padding:1rem;background:var(--cream);border:1.5px solid rgba(45,90,39,0.25);
            border-radius:8px;margin-top:0.65rem;
        }
        .selected-alat.visible { display:flex;gap:0.9rem; }
        .sa-thumb {
            width:56px;height:56px;border-radius:10px;overflow:hidden;flex-shrink:0;
            background:var(--cream-d);display:flex;align-items:center;justify-content:center;font-size:1.4rem;
        }
        .sa-thumb img { width:100%;height:100%;object-fit:cover; }
        .sa-name { font-size:0.9rem;font-weight:700;color:var(--ink);margin-bottom:0.1rem; }
        .sa-meta { font-size:0.72rem;color:var(--ink-40); }
        .sa-price { font-family:var(--font-mono);font-size:0.82rem;color:var(--green);margin-top:0.3rem; }
        .sa-change { margin-left:auto;font-size:0.72rem;font-weight:600;color:var(--ink-40);cursor:pointer;flex-shrink:0;transition:color .15s; }
        .sa-change:hover { color:var(--red); }

        /* Jumlah stepper */
        .jumlah-wrap { display:flex;align-items:center;gap:0; }
        .jumlah-btn {
            width:40px;height:42px;border-radius:8px 0 0 8px;background:var(--cream-d);
            border:1.5px solid var(--border);cursor:pointer;display:flex;align-items:center;justify-content:center;
            font-size:1.1rem;font-weight:700;color:var(--ink-40);transition:all .15s;user-select:none;
        }
        .jumlah-btn:last-child { border-radius:0 8px 8px 0; }
        .jumlah-btn:hover { background:var(--cream-dd);color:var(--ink); }
        .jumlah-input {
            flex:1;text-align:center;background:var(--cream);border-top:1.5px solid var(--border);border-bottom:1.5px solid var(--border);border-left:none;border-right:none;
            height:42px;font-family:var(--font-mono);font-size:1rem;font-weight:700;color:var(--ink);outline:none;
        }
        .jumlah-stok { font-size:0.72rem;color:var(--ink-40);margin-top:0.3rem; }
        .jumlah-stok strong { color:var(--green); }

        /* Date row */
        .date-row { display:grid;grid-template-columns:1fr auto 1fr;gap:0.5rem;align-items:end; }
        .date-arrow { padding-bottom:0.8rem;color:var(--ink-40);font-size:1.1rem;text-align:center; }

        /* Tujuan char counter */
        .textarea-wrap { position:relative; }
        .char-count { position:absolute;bottom:0.5rem;right:0.65rem;font-size:0.65rem;color:var(--ink-20); }

        /* ── Summary sidebar ────────────────────── */
        .summary-card {
            background:var(--white);border:1px solid var(--border);border-radius:14px;
            overflow:hidden;position:sticky;top:80px;
        }
        .sum-head {
            padding:1.1rem 1.3rem;border-bottom:1px solid var(--border);
            font-size:0.75rem;font-weight:800;text-transform:uppercase;letter-spacing:0.1em;color:var(--ink-40);
        }
        .sum-body { padding:1.1rem 1.3rem; }
        .sum-alat-row {
            display:flex;align-items:center;gap:0.7rem;
            padding-bottom:1rem;margin-bottom:1rem;border-bottom:1px solid var(--border);
        }
        .sum-thumb {
            width:42px;height:42px;border-radius:8px;flex-shrink:0;overflow:hidden;
            background:var(--cream-d);display:flex;align-items:center;justify-content:center;font-size:1.1rem;
        }
        .sum-thumb img { width:100%;height:100%;object-fit:cover; }
        .sum-alat-name { font-size:0.85rem;font-weight:700;color:var(--ink);line-height:1.3; }
        .sum-alat-meta { font-size:0.7rem;color:var(--ink-40); }

        .sum-row { display:flex;justify-content:space-between;align-items:center;padding:0.45rem 0;font-size:0.82rem; }
        .sum-key { color:var(--ink-40); }
        .sum-val { font-weight:700;color:var(--ink);font-family:var(--font-mono);font-size:0.8rem; }
        .sum-divider { border:none;border-top:1px dashed var(--border);margin:0.6rem 0; }

        .sum-total {
            background:rgba(45,90,39,0.06);border:1px solid rgba(45,90,39,0.18);
            border-radius:8px;padding:0.9rem 1rem;margin-top:0.75rem;
        }
        .sum-total-label { font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--green);margin-bottom:0.2rem; }
        .sum-total-val { font-family:var(--font-display);font-size:1.7rem;font-weight:900;color:var(--ink);line-height:1; }
        .sum-total-note { font-size:0.68rem;color:var(--ink-40);margin-top:0.2rem; }

        /* Denda warning */
        .denda-note {
            display:flex;align-items:flex-start;gap:0.5rem;margin-top:0.75rem;
            padding:0.75rem;background:rgba(196,134,10,0.07);border:1px solid rgba(196,134,10,0.2);
            border-radius:7px;font-size:0.72rem;color:var(--amber);line-height:1.4;
        }

        /* Submit button */
        .btn-submit {
            width:100%;margin-top:1rem;padding:0.88rem;border-radius:9px;
            font-size:0.95rem;font-weight:800;
            background:var(--green);color:white;border:none;cursor:pointer;
            font-family:var(--font-body);transition:all .2s;
            display:flex;align-items:center;justify-content:center;gap:0.5rem;
        }
        .btn-submit:hover { background:var(--green-l);transform:translateY(-2px);box-shadow:0 8px 28px rgba(45,90,39,0.35); }
        .btn-submit:disabled { opacity:0.5;cursor:not-allowed;transform:none;box-shadow:none; }

        .btn-cancel {
            width:100%;margin-top:0.5rem;padding:0.7rem;border-radius:9px;
            font-size:0.85rem;font-weight:600;background:none;color:var(--ink-40);
            border:1.5px solid var(--border);cursor:pointer;font-family:var(--font-body);transition:all .15s;
        }
        .btn-cancel:hover { border-color:var(--ink);color:var(--ink); }

        /* Status info box */
        .status-box {
            padding:0.8rem 1rem;background:rgba(45,90,39,0.06);border:1px solid rgba(45,90,39,0.18);
            border-radius:7px;margin-bottom:1rem;font-size:0.78rem;color:var(--ink-40);line-height:1.5;
        }
        .status-box strong { color:var(--green); }

        /* Contact info in sidebar */
        .sum-contact {
            display:flex;align-items:center;gap:0.5rem;padding:0.6rem 0.75rem;
            background:var(--cream);border:1px solid var(--border);border-radius:7px;
            margin-top:0.5rem;font-size:0.75rem;
        }
        .sum-contact-label { color:var(--ink-40); }
        .sum-contact-val { font-weight:700;color:var(--ink);margin-left:auto;font-family:var(--font-mono); }
        .sum-contact-missing { color:var(--amber);font-weight:700;margin-left:auto; }

        /* Step progress */
        .step-prog { display:flex;align-items:center;gap:0;margin-bottom:1rem; }
        .sp-step {
            display:flex;align-items:center;gap:0.4rem;padding:0.35rem 0.75rem;
            border-radius:100px;font-size:0.7rem;font-weight:700;
        }
        .sp-step.active { background:var(--green);color:white; }
        .sp-step.done   { background:rgba(45,90,39,0.1);color:var(--green); }
        .sp-step.pending{ background:var(--cream-d);color:var(--ink-40); }
        .sp-line { flex:1;height:1px;background:var(--border); }

        @media(max-width:900px) { .form-layout { grid-template-columns:1fr; } .summary-card { position:static; } }
    </style>
</head>
<body>

    {{-- NAV --}}
    <nav class="nav">
        <a href="/" class="nav-logo">
            <span class="nav-logo-dot"></span>SewaAlat
        </a>
        <div class="nav-breadcrumb">
            <a href="/">Beranda</a>
            <span class="sep">/</span>
            <a href="{{ route('peminjam.peminjamans.index') }}">Peminjaman Saya</a>
            <span class="sep">/</span>
            <span style="color:var(--ink);font-weight:600">Ajukan Sewa</span>
        </div>
        <div class="nav-user">
            <div class="user-av">{{ strtoupper(substr(auth()->user()->name,0,2)) }}</div>
            <span class="user-name">{{ auth()->user()->name }}</span>
        </div>
    </nav>

    <div class="page-wrap">

        {{-- Flash messages --}}
        @if(session('error'))
            <div class="flash-error">⚠ {{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="flash-success">✓ {{ session('success') }}</div>
        @endif

        {{-- Banner: No HP belum diisi --}}
        @if(!auth()->user()->no_hp)
            <div class="nohp-banner">
                <div class="nohp-banner-icon">📱</div>
                <div>
                    <div class="nohp-banner-title">Nomor HP belum terdaftar</div>
                    <div class="nohp-banner-desc">
                        Isi nomor HP Anda di bawah agar petugas dapat menghubungi Anda saat pengajuan dikonfirmasi.
                        Nomor ini akan disimpan ke profil Anda secara otomatis.
                    </div>
                </div>
            </div>
        @endif

        <div class="page-eyebrow">Form Pengajuan</div>
        <h1 class="page-h1">Ajukan Sewa Alat</h1>
        <p class="page-sub">Isi formulir di bawah. Pengajuan akan dikonfirmasi oleh petugas.</p>

        {{-- Step indicator --}}
        <div class="step-prog">
            <div class="sp-step active">① Isi Formulir</div>
            <div class="sp-line"></div>
            <div class="sp-step pending">② Menunggu Konfirmasi</div>
            <div class="sp-line"></div>
            <div class="sp-step pending">③ Ambil Alat</div>
            <div class="sp-line"></div>
            <div class="sp-step pending">④ Kembalikan</div>
        </div>

        <form method="POST" action="{{ route('peminjam.peminjamans.store') }}" id="sewaForm">
            @csrf

            <div class="form-layout">

                {{-- ─── FORM ────────────────────── --}}
                <div class="form-card">

                    {{-- Pilih alat --}}
                    <div class="form-section">
                        <div class="form-section-title">
                            <div class="form-section-num">1</div>
                            Pilih Alat
                        </div>

                        <input type="hidden" name="alat_id" id="alatIdInput" value="{{ old('alat_id', $alat?->id) }}">

                        {{-- Search input --}}
                        <div id="alatSearchWrap" class="{{ $alat ? 'hidden' : '' }}" style="{{ $alat ? 'display:none' : '' }}">
                            <div class="form-group">
                                <label class="form-label">Cari Alat <span class="req">*</span></label>
                                <div class="alat-search-wrap">
                                    <input type="text" id="alatSearch" class="form-input {{ $errors->has('alat_id') ? 'error' : '' }}"
                                           placeholder="Ketik nama alat, merek, atau kode..."
                                           autocomplete="off">
                                    <div class="alat-dropdown" id="alatDropdown">
                                        @foreach($alats as $a)
                                            <div class="alat-opt"
                                                 data-id="{{ $a->id }}"
                                                 data-nama="{{ $a->nama }}"
                                                 data-kode="{{ $a->kode }}"
                                                 data-harga="{{ $a->harga_sewa_per_hari }}"
                                                 data-denda="{{ $a->denda_per_hari }}"
                                                 data-stok="{{ $a->stok_tersedia }}"
                                                 data-foto="{{ $a->foto ? asset('storage/'.$a->foto) : '' }}"
                                                 onclick="selectAlat(this)">
                                                <div class="alat-opt-thumb">
                                                    @if($a->foto)
                                                        <img src="{{ asset('storage/'.$a->foto) }}" alt="">
                                                    @else
                                                        🔧
                                                    @endif
                                                </div>
                                                <div style="flex:1;min-width:0">
                                                    <div class="alat-opt-name">{{ $a->nama }}</div>
                                                    <div class="alat-opt-meta">{{ $a->kode }}{{ $a->merk ? ' · '.$a->merk : '' }}</div>
                                                    <div class="alat-opt-price">Rp {{ number_format($a->harga_sewa_per_hari,0,',','.') }}/hari</div>
                                                </div>
                                                <span class="alat-opt-stok {{ $a->stok_tersedia > 2 ? 'stok-ok' : 'stok-low' }}">
                                                    {{ $a->stok_tersedia }} unit
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @error('alat_id') <p class="form-error-msg">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Selected alat display --}}
                        <div class="selected-alat {{ $alat ? 'visible' : '' }}" id="selectedAlatCard">
                            <div class="sa-thumb" id="saThumb">
                                @if($alat?->foto)
                                    <img src="{{ asset('storage/'.$alat->foto) }}" alt="" id="saImg">
                                @else
                                    <span id="saEmoji">{{ $alat?->kategori?->ikon ?? '🔧' }}</span>
                                @endif
                            </div>
                            <div style="flex:1;min-width:0">
                                <div class="sa-name" id="saName">{{ $alat?->nama ?? '' }}</div>
                                <div class="sa-meta" id="saMeta">
                                    {{ $alat?->kode ?? '' }}{{ $alat?->merk ? ' · '.$alat->merk : '' }}
                                    @if($alat) · Stok tersedia: <strong style="color:var(--green)">{{ $alat->stok_tersedia }}</strong> unit @endif
                                </div>
                                <div class="sa-price" id="saPrice">
                                    @if($alat) Rp {{ number_format($alat->harga_sewa_per_hari,0,',','.') }}/hari @endif
                                </div>
                            </div>
                            <span class="sa-change" onclick="changeAlat()">Ganti ✕</span>
                        </div>
                    </div>

                    {{-- Jumlah --}}
                    <div class="form-section">
                        <div class="form-section-title">
                            <div class="form-section-num">2</div>
                            Jumlah Unit
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jumlah yang Dipinjam <span class="req">*</span></label>
                            <div class="jumlah-wrap">
                                <button type="button" class="jumlah-btn" onclick="changeJumlah(-1)">−</button>
                                <input type="number" name="jumlah" id="jumlahInput"
                                       value="{{ old('jumlah', 1) }}" min="1" max="99"
                                       class="jumlah-input" oninput="recalc()">
                                <button type="button" class="jumlah-btn" onclick="changeJumlah(1)">+</button>
                            </div>
                            <div class="jumlah-stok" id="stokInfo">
                                @if($alat)
                                    Stok tersedia: <strong>{{ $alat->stok_tersedia }}</strong> unit
                                @else
                                    Pilih alat terlebih dahulu
                                @endif
                            </div>
                            @error('jumlah') <p class="form-error-msg">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Tanggal --}}
                    <div class="form-section">
                        <div class="form-section-title">
                            <div class="form-section-num">3</div>
                            Periode Sewa
                        </div>
                        <div class="date-row">
                            <div class="form-group" style="margin:0">
                                <label class="form-label">Tanggal Mulai Pinjam <span class="req">*</span></label>
                                <input type="date" name="tanggal_pinjam" id="tglPinjam"
                                       value="{{ old('tanggal_pinjam', now()->toDateString()) }}"
                                       min="{{ now()->toDateString() }}"
                                       class="form-input {{ $errors->has('tanggal_pinjam') ? 'error':'' }}"
                                       oninput="recalc()">
                                @error('tanggal_pinjam') <p class="form-error-msg">{{ $message }}</p> @enderror
                            </div>
                            <div class="date-arrow">→</div>
                            <div class="form-group" style="margin:0">
                                <label class="form-label">Tanggal Rencana Kembali <span class="req">*</span></label>
                                <input type="date" name="tanggal_kembali_rencana" id="tglKembali"
                                       value="{{ old('tanggal_kembali_rencana', now()->addDays(3)->toDateString()) }}"
                                       min="{{ now()->addDay()->toDateString() }}"
                                       class="form-input {{ $errors->has('tanggal_kembali_rencana') ? 'error':'' }}"
                                       oninput="recalc()">
                                @error('tanggal_kembali_rencana') <p class="form-error-msg">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Duration chip --}}
                        <div id="durasiChip" style="display:none;margin-top:0.75rem;padding:0.55rem 0.9rem;background:rgba(45,90,39,0.07);border:1px solid rgba(45,90,39,0.2);border-radius:100px;display:inline-flex;align-items:center;gap:0.5rem;font-size:0.78rem;font-weight:700;color:var(--green)">
                            <span>📅</span>
                            <span id="durasiText">3 hari</span>
                        </div>
                    </div>

                    {{-- Tujuan --}}
                    <div class="form-section">
                        <div class="form-section-title">
                            <div class="form-section-num">4</div>
                            Tujuan Penggunaan
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jelaskan keperluan Anda <span class="req">*</span></label>
                            <div class="textarea-wrap">
                                <textarea name="tujuan_peminjaman" id="tujuanInput"
                                          class="form-textarea {{ $errors->has('tujuan_peminjaman') ? 'error':'' }}"
                                          rows="4" maxlength="500"
                                          placeholder="Contoh: Untuk membersihkan talang rumah setelah hujan lebat minggu ini..."
                                          oninput="updateCharCount()">{{ old('tujuan_peminjaman') }}</textarea>
                                <span class="char-count" id="charCount">0/500</span>
                            </div>
                            <p class="form-hint">Minimal 10 karakter. Tujuan yang jelas mempercepat proses persetujuan.</p>
                            @error('tujuan_peminjaman') <p class="form-error-msg">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- ── No HP ──────────────────────────────── --}}
                    <div class="form-section">
                        <div class="form-section-title">
                            <div class="form-section-num">5</div>
                            Kontak
                            @if(!auth()->user()->no_hp)
                                <span class="new-badge">⚡ Wajib diisi</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="noHpInput">
                                Nomor HP / WhatsApp
                                <span class="req">*</span>
                            </label>

                            <div class="phone-wrap">
                                <div class="phone-prefix">🇮🇩 +62</div>
                                <input
                                    type="tel"
                                    name="no_hp"
                                    id="noHpInput"
                                    class="form-input {{ $errors->has('no_hp') ? 'error' : '' }}"
                                    {{-- Strip leading +62 or 0 for display, re-apply on server --}}
                                    value="{{ old('no_hp', ltrim(preg_replace('/^\+62|^62/', '', auth()->user()->no_hp ?? ''), '0')) }}"
                                    placeholder="8xx-xxxx-xxxx"
                                    maxlength="15"
                                    inputmode="tel"
                                    oninput="updateNoHpSummary()"
                                >
                            </div>

                            @if(auth()->user()->no_hp)
                                <p class="form-hint">Nomor tersimpan di profil Anda. Ubah jika perlu — perubahan otomatis tersimpan.</p>
                            @else
                                <p class="form-hint" style="color:var(--amber);font-weight:600">
                                    ⚠ Nomor HP belum ada di profil Anda. Wajib diisi agar petugas bisa menghubungi Anda.
                                </p>
                            @endif

                            @error('no_hp') <p class="form-error-msg">{{ $message }}</p> @enderror
                        </div>
                    </div>

                </div>

                {{-- ─── SIDEBAR ─────────────────── --}}
                <div>
                    <div class="summary-card">
                        <div class="sum-head">Ringkasan Sewa</div>
                        <div class="sum-body">

                            {{-- Alat display --}}
                            <div class="sum-alat-row" id="sumAlatRow">
                                <div class="sum-thumb" id="sumThumb">
                                    @if($alat?->foto)
                                        <img src="{{ asset('storage/'.$alat->foto) }}" alt="">
                                    @else
                                        <span id="sumEmoji">{{ $alat ? ($alat->kategori?->ikon ?? '🔧') : '🔧' }}</span>
                                    @endif
                                </div>
                                <div>
                                    <div class="sum-alat-name" id="sumName" style="font-size:0.88rem;font-weight:700;color:var(--ink)">
                                        {{ $alat?->nama ?? '—' }}
                                    </div>
                                    <div class="sum-alat-meta" id="sumKat" style="font-size:0.7rem;color:var(--ink-40)">
                                        {{ $alat?->kategori?->nama ?? 'Pilih alat dulu' }}
                                    </div>
                                </div>
                            </div>

                            <div class="sum-row">
                                <span class="sum-key">Harga/hari</span>
                                <span class="sum-val" id="sumHarga">—</span>
                            </div>
                            <div class="sum-row">
                                <span class="sum-key">Jumlah unit</span>
                                <span class="sum-val" id="sumJumlah">1 unit</span>
                            </div>
                            <div class="sum-row">
                                <span class="sum-key">Durasi</span>
                                <span class="sum-val" id="sumDurasi">— hari</span>
                            </div>
                            <hr class="sum-divider">

                            <div class="sum-total">
                                <div class="sum-total-label">Estimasi Total Biaya</div>
                                <div class="sum-total-val" id="sumTotal">Rp 0</div>
                                <div class="sum-total-note">Harga × Jumlah × Durasi</div>
                            </div>

                            <div class="denda-note">
                                <span>⚠</span>
                                <div>Denda keterlambatan: <strong id="sumDenda" style="font-weight:700">—</strong>/hari/unit jika terlambat kembali.</div>
                            </div>

                            {{-- Contact row in sidebar --}}
                            <div class="sum-contact">
                                <span style="font-size:0.9rem">📱</span>
                                <span class="sum-contact-label">No. HP</span>
                                <span id="sumNoHp" class="{{ auth()->user()->no_hp ? 'sum-contact-val' : 'sum-contact-missing' }}">
                                    {{ auth()->user()->no_hp ? '+62'.ltrim(preg_replace('/^\+62|^62/', '', auth()->user()->no_hp), '0') : 'Belum diisi' }}
                                </span>
                            </div>

                            <div class="status-box" style="margin-top:0.75rem">
                                Status awal pengajuan: <strong>Menunggu Konfirmasi</strong>.<br>
                                Petugas akan memverifikasi dan menghubungi Anda.
                            </div>

                            <button type="submit" class="btn-submit" id="submitBtn">
                                <span>📋</span> Kirim Pengajuan
                            </button>
                            <a href="{{ route('peminjam.peminjamans.index') }}">
                                <button type="button" class="btn-cancel">Batal</button>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    @php
        $alatJs = $alat ? [
            'id'       => $alat->id,
            'nama'     => $alat->nama,
            'kode'     => $alat->kode,
            'merk'     => $alat->merk ?? '',
            'harga'    => (float) $alat->harga_sewa_per_hari,
            'denda'    => (float) $alat->denda_per_hari,
            'stok'     => $alat->stok_tersedia,
            'foto'     => $alat->foto ? asset('storage/'.$alat->foto) : '',
            'kategori' => $alat->kategori?->nama ?? '',
        ] : null;
    @endphp

    <script>
        // ── State ─────────────────────────────────────
        let selectedAlat = @json($alatJs);

        // ── Init ──────────────────────────────────────
        document.addEventListener('DOMContentLoaded', () => {
            updateCharCount();
            recalc();
            if (selectedAlat) updateSummaryCard();
            updateNoHpSummary();
        });

        // ── Alat search ───────────────────────────────
        const searchInput = document.getElementById('alatSearch');
        const dropdown    = document.getElementById('alatDropdown');

        if (searchInput) {
            searchInput.addEventListener('focus', () => dropdown.classList.add('open'));
            searchInput.addEventListener('input', () => {
                const q = searchInput.value.toLowerCase();
                dropdown.querySelectorAll('.alat-opt').forEach(opt => {
                    const match = opt.dataset.nama.toLowerCase().includes(q) ||
                                  opt.dataset.kode.toLowerCase().includes(q);
                    opt.style.display = match ? '' : 'none';
                });
                dropdown.classList.add('open');
            });
            document.addEventListener('click', e => {
                if (!e.target.closest('.alat-search-wrap')) dropdown.classList.remove('open');
            });
        }

        function selectAlat(el) {
            selectedAlat = {
                id:       el.dataset.id,
                nama:     el.dataset.nama,
                kode:     el.dataset.kode,
                harga:    parseFloat(el.dataset.harga),
                denda:    parseFloat(el.dataset.denda),
                stok:     parseInt(el.dataset.stok),
                foto:     el.dataset.foto,
                kategori: '',
            };
            document.getElementById('alatIdInput').value = selectedAlat.id;

            document.getElementById('alatSearchWrap').style.display = 'none';
            const card = document.getElementById('selectedAlatCard');
            card.classList.add('visible');

            const thumb = document.getElementById('saThumb');
            thumb.innerHTML = selectedAlat.foto
                ? `<img src="${selectedAlat.foto}" alt="" style="width:100%;height:100%;object-fit:cover">`
                : `<span>🔧</span>`;

            document.getElementById('saName').textContent  = selectedAlat.nama;
            document.getElementById('saMeta').innerHTML    = `${selectedAlat.kode} · Stok tersedia: <strong style="color:var(--green)">${selectedAlat.stok}</strong> unit`;
            document.getElementById('saPrice').textContent = `Rp ${selectedAlat.harga.toLocaleString('id-ID')}/hari`;

            dropdown.classList.remove('open');
            updateStokInfo();
            updateSummaryCard();
            recalc();
        }

        function changeAlat() {
            selectedAlat = null;
            document.getElementById('alatIdInput').value = '';
            document.getElementById('alatSearchWrap').style.display = 'block';
            document.getElementById('selectedAlatCard').classList.remove('visible');
            document.getElementById('alatSearch').value = '';
            document.getElementById('alatSearch').focus();
            document.getElementById('alatDropdown').querySelectorAll('.alat-opt').forEach(o => o.style.display='');
            dropdown.classList.add('open');
            recalc();
        }

        function updateStokInfo() {
            const el = document.getElementById('stokInfo');
            if (!selectedAlat) { el.innerHTML = 'Pilih alat terlebih dahulu'; return; }
            el.innerHTML = `Stok tersedia: <strong>${selectedAlat.stok}</strong> unit`;
        }

        function updateSummaryCard() {
            if (!selectedAlat) return;
            const thumb = document.getElementById('sumThumb');
            thumb.innerHTML = selectedAlat.foto
                ? `<img src="${selectedAlat.foto}" alt="" style="width:100%;height:100%;object-fit:cover">`
                : `<span>🔧</span>`;
            document.getElementById('sumName').textContent  = selectedAlat.nama;
            document.getElementById('sumKat').textContent   = selectedAlat.kategori || selectedAlat.kode;
            document.getElementById('sumHarga').textContent = `Rp ${selectedAlat.harga.toLocaleString('id-ID')}`;
            document.getElementById('sumDenda').textContent = `Rp ${selectedAlat.denda.toLocaleString('id-ID')}`;
        }

        // ── No HP summary sync ─────────────────────────
        function updateNoHpSummary() {
            const raw = document.getElementById('noHpInput').value.trim();
            const el  = document.getElementById('sumNoHp');
            if (!raw) {
                el.textContent = 'Belum diisi';
                el.className   = 'sum-contact-missing';
            } else {
                el.textContent = `+62${raw}`;
                el.className   = 'sum-contact-val';
            }
        }

        // ── Calculator ────────────────────────────────
        function recalc() {
            const jumlah    = parseInt(document.getElementById('jumlahInput').value) || 1;
            const tglPinjam = document.getElementById('tglPinjam').value;
            const tglKembali= document.getElementById('tglKembali').value;

            let durasi = 0;
            if (tglPinjam && tglKembali) {
                const d1 = new Date(tglPinjam), d2 = new Date(tglKembali);
                durasi = Math.max(0, Math.round((d2-d1) / (1000*60*60*24)));
            }

            const harga = selectedAlat?.harga ?? 0;
            const total = harga * jumlah * durasi;

            document.getElementById('sumJumlah').textContent = `${jumlah} unit`;
            document.getElementById('sumDurasi').textContent = durasi > 0 ? `${durasi} hari` : '— hari';
            document.getElementById('sumTotal').textContent  = `Rp ${total.toLocaleString('id-ID')}`;

            const chip = document.getElementById('durasiChip');
            const chipText = document.getElementById('durasiText');
            if (durasi > 0) {
                chip.style.display = 'inline-flex';
                chipText.textContent = `${durasi} hari`;
            } else {
                chip.style.display = 'none';
            }

            if (selectedAlat) {
                document.getElementById('sumHarga').textContent = `Rp ${selectedAlat.harga.toLocaleString('id-ID')}`;
            }

            const submitBtn = document.getElementById('submitBtn');
            if (selectedAlat && jumlah > selectedAlat.stok) {
                document.getElementById('stokInfo').innerHTML =
                    `<span style="color:var(--red);font-weight:700">⚠ Melebihi stok! Tersedia: ${selectedAlat.stok} unit</span>`;
                submitBtn.disabled = true;
            } else {
                updateStokInfo();
                submitBtn.disabled = false;
            }

            if (tglPinjam) {
                const nextDay = new Date(tglPinjam);
                nextDay.setDate(nextDay.getDate() + 1);
                document.getElementById('tglKembali').min = nextDay.toISOString().split('T')[0];
            }
        }

        function changeJumlah(delta) {
            const input = document.getElementById('jumlahInput');
            const cur   = parseInt(input.value) || 1;
            const max   = selectedAlat?.stok ?? 99;
            input.value = Math.max(1, Math.min(max, cur + delta));
            recalc();
        }

        // ── Char counter ──────────────────────────────
        function updateCharCount() {
            const val = document.getElementById('tujuanInput').value.length;
            const el  = document.getElementById('charCount');
            el.textContent = `${val}/500`;
            el.style.color = val < 10 ? 'var(--red)' : (val > 450 ? 'var(--amber)' : 'var(--ink-20)');
        }
    </script>

</body>
</html>