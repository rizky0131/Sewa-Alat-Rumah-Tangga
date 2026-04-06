<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lapor Pengembalian — SewaAlat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

    <style>
        /* ─── Tokens ─────────────────────────────────── */
        :root {
            --ink:#1A1710;--ink-80:#2E2B21;--ink-40:#6B6654;--ink-20:#A09880;
            --cream:#F5F0E8;--cream-d:#EDE5D4;--cream-dd:#E3D8C2;
            --green:#2D5A27;--green-l:#3D7A35;--green-ll:#5A9E50;
            --amber:#C4860A;--red:#C0392B;--white:#FDFAF5;
            --border:rgba(26,23,16,0.12);
            --shadow:0 2px 20px rgba(26,23,16,0.1);
            --shadow-lg:0 8px 48px rgba(26,23,16,0.18);
            --font-display:'Playfair Display',Georgia,serif;
            --font-body:'DM Sans',system-ui,sans-serif;
            --font-mono:'DM Mono',monospace;
        }
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
        html{scroll-behavior:smooth;}
        body{font-family:var(--font-body);background:var(--cream);color:var(--ink);overflow-x:hidden;}
        a{color:inherit;text-decoration:none;}
        img{display:block;max-width:100%;}
        body::before{content:'';position:fixed;inset:0;pointer-events:none;z-index:1;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23n)' opacity='0.035'/%3E%3C/svg%3E");
            opacity:0.6;}

        /* ─── Navbar ──────────────────────────────────── */
        .nav{position:sticky;top:0;z-index:100;background:rgba(245,240,232,0.92);
            backdrop-filter:blur(12px);border-bottom:1px solid var(--border);
            padding:0 max(2rem,calc((100vw - 1280px)/2 + 2rem));
            display:flex;align-items:center;justify-content:space-between;height:64px;}
        .nav-logo{font-family:var(--font-display);font-size:1.35rem;font-weight:900;
            color:var(--ink);letter-spacing:-0.02em;display:flex;align-items:center;gap:0.4rem;}
        .nav-logo-dot{width:8px;height:8px;border-radius:50%;background:var(--green);
            display:inline-block;margin-bottom:3px;}
        .nav-breadcrumb{display:flex;align-items:center;gap:0.45rem;font-size:0.8rem;color:var(--ink-40);}
        .nav-breadcrumb a:hover{color:var(--ink);}
        .nav-breadcrumb .sep{opacity:0.35;}
        .nav-user{display:flex;align-items:center;gap:0.65rem;}
        .user-av{width:32px;height:32px;border-radius:50%;background:var(--green);color:white;
            display:flex;align-items:center;justify-content:center;font-size:0.72rem;font-weight:800;}

        /* ─── Page ────────────────────────────────────── */
        .page-wrap{max-width:1100px;margin:0 auto;padding:2.5rem max(1.5rem,2vw);}
        .page-eyebrow{font-size:0.7rem;font-weight:700;text-transform:uppercase;
            letter-spacing:0.14em;color:var(--green);margin-bottom:0.4rem;
            display:flex;align-items:center;gap:0.4rem;}
        .page-eyebrow::before{content:'';width:18px;height:1.5px;background:var(--green);}
        .page-h1{font-family:var(--font-display);font-size:clamp(1.8rem,3.5vw,2.6rem);
            font-weight:900;letter-spacing:-0.025em;line-height:1.1;margin-bottom:0.35rem;}
        .page-sub{font-size:0.9rem;color:var(--ink-40);margin-bottom:1.8rem;}

        /* Flash */
        .flash-err,.flash-ok{display:flex;align-items:flex-start;gap:0.65rem;
            padding:0.9rem 1.1rem;border-radius:8px;margin-bottom:1.4rem;font-size:0.85rem;font-weight:500;}
        .flash-ok {background:rgba(45,90,39,0.08);border:1px solid rgba(45,90,39,0.25);color:var(--green);}
        .flash-err{background:rgba(192,57,43,0.08);border:1px solid rgba(192,57,43,0.25);color:var(--red);}

        /* ─── Layout ──────────────────────────────────── */
        .form-layout{display:grid;grid-template-columns:1fr 340px;gap:1.5rem;align-items:start;}

        /* ─── Form card ───────────────────────────────── */
        .fcard{background:var(--white);border:1px solid var(--border);border-radius:14px;overflow:hidden;}
        .fsec{padding:1.4rem 1.6rem;border-bottom:1px solid var(--border);}
        .fsec:last-child{border-bottom:none;}
        .fsec-title{display:flex;align-items:center;gap:0.55rem;
            font-size:0.72rem;font-weight:800;text-transform:uppercase;letter-spacing:0.1em;
            color:var(--ink-40);margin-bottom:1.2rem;}
        .fsec-num{width:22px;height:22px;border-radius:50%;background:var(--green);color:white;
            display:flex;align-items:center;justify-content:center;font-size:0.65rem;font-weight:800;flex-shrink:0;}

        /* Inputs */
        .fg{margin-bottom:1rem;}
        .fg:last-child{margin-bottom:0;}
        .fl{display:block;font-size:0.78rem;font-weight:700;color:var(--ink);margin-bottom:0.4rem;}
        .fl .req{color:var(--red);margin-left:2px;}
        .fhint{font-size:0.7rem;color:var(--ink-40);margin-top:0.25rem;}
        .ferr{font-size:0.7rem;color:var(--red);margin-top:0.25rem;font-weight:600;}

        .fi,.fta,.fsel{width:100%;background:var(--cream);border:1.5px solid var(--border);
            border-radius:8px;padding:0.7rem 0.9rem;
            font-family:var(--font-body);font-size:0.88rem;color:var(--ink);
            outline:none;transition:border-color .2s,box-shadow .2s;}
        .fi:focus,.fta:focus,.fsel:focus{
            border-color:var(--green);box-shadow:0 0 0 3px rgba(45,90,39,0.1);background:var(--white);}
        .fi.is-err,.fta.is-err,.fsel.is-err{
            border-color:var(--red);box-shadow:0 0 0 3px rgba(192,57,43,0.08);}
        .fta{resize:vertical;min-height:90px;}

        /* ─── Peminjaman selector cards ──────────────── */
        .pm-list{display:flex;flex-direction:column;gap:0.5rem;}
        .pm-opt{
            display:flex;align-items:center;gap:0.85rem;padding:0.85rem 1rem;
            border-radius:10px;cursor:pointer;
            background:var(--cream);border:1.5px solid var(--border);transition:all .15s;
            position:relative;
        }
        .pm-opt:hover{border-color:rgba(45,90,39,0.35);background:rgba(45,90,39,0.04);}
        .pm-opt.selected{border-color:var(--green);background:rgba(45,90,39,0.07);}
        .pm-opt.is-late{border-color:rgba(192,57,43,0.3);background:rgba(192,57,43,0.04);}
        .pm-opt.is-late.selected{border-color:var(--red);}

        .pm-radio{width:18px;height:18px;border-radius:50%;border:2px solid var(--border);
            flex-shrink:0;display:flex;align-items:center;justify-content:center;
            transition:all .15s;}
        .pm-opt.selected .pm-radio{background:var(--green);border-color:var(--green);}
        .pm-radio-dot{width:7px;height:7px;border-radius:50%;background:white;
            opacity:0;transition:opacity .15s;}
        .pm-opt.selected .pm-radio-dot{opacity:1;}

        .pm-thumb{width:42px;height:42px;border-radius:9px;overflow:hidden;flex-shrink:0;
            background:var(--cream-d);display:flex;align-items:center;justify-content:center;font-size:1.1rem;}
        .pm-thumb img{width:100%;height:100%;object-fit:cover;}

        .pm-nomor{font-family:var(--font-mono);font-size:0.68rem;color:var(--green);margin-bottom:0.1rem;}
        .pm-name{font-size:0.85rem;font-weight:700;color:var(--ink);}
        .pm-meta{font-size:0.7rem;color:var(--ink-40);}

        .pm-dates{margin-left:auto;text-align:right;flex-shrink:0;}
        .pm-date-val{font-size:0.75rem;font-weight:700;}
        .pm-date-lbl{font-size:0.62rem;color:var(--ink-40);}

        .late-badge{
            display:inline-flex;align-items:center;gap:0.25rem;
            font-size:0.62rem;font-weight:800;padding:0.18rem 0.55rem;border-radius:100px;
            background:rgba(192,57,43,0.12);color:var(--red);border:1px solid rgba(192,57,43,0.22);
            margin-top:0.2rem;
        }
        .late-badge::before{content:'';width:5px;height:5px;border-radius:50%;
            background:var(--red);animation:blink 1s infinite;}
        @keyframes blink{0%,100%{opacity:1}50%{opacity:0.2}}

        /* Late warning banner inside form */
        .late-warn{
            display:none;margin-top:0.75rem;padding:0.85rem 1rem;
            background:rgba(192,57,43,0.07);border:1px solid rgba(192,57,43,0.25);
            border-radius:8px;font-size:0.8rem;color:var(--red);line-height:1.55;
        }
        .late-warn.visible{display:block;}
        .late-warn strong{font-weight:800;}

        /* ─── Kondisi grid ────────────────────────────── */
        .kond-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:0.5rem;}
        .kond-opt{position:relative;}
        .kond-opt input{position:absolute;opacity:0;width:0;height:0;}
        .kond-lbl{
            display:flex;flex-direction:column;align-items:center;gap:0.25rem;
            padding:0.8rem 0.3rem;border-radius:9px;cursor:pointer;text-align:center;
            background:var(--cream);border:1.5px solid var(--border);transition:all .15s;
        }
        .kond-lbl:hover{border-color:rgba(26,23,16,0.25);}
        .kond-em{font-size:1.3rem;}
        .kond-tx{font-size:0.62rem;font-weight:700;color:var(--ink-40);}

        /* checked states */
        .kond-opt input:checked + .kond-lbl.kl-baik        {border-color:#2D5A27;background:rgba(45,90,39,0.1);}
        .kond-opt input:checked + .kond-lbl.kl-baik .kond-tx{color:var(--green);}
        .kond-opt input:checked + .kond-lbl.kl-ringan       {border-color:#C4860A;background:rgba(196,134,10,0.1);}
        .kond-opt input:checked + .kond-lbl.kl-ringan .kond-tx{color:var(--amber);}
        .kond-opt input:checked + .kond-lbl.kl-sedang       {border-color:#E67E22;background:rgba(230,126,34,0.1);}
        .kond-opt input:checked + .kond-lbl.kl-sedang .kond-tx{color:#E67E22;}
        .kond-opt input:checked + .kond-lbl.kl-berat        {border-color:#C0392B;background:rgba(192,57,43,0.1);}
        .kond-opt input:checked + .kond-lbl.kl-berat .kond-tx{color:var(--red);}
        .kond-opt input:checked + .kond-lbl.kl-hilang       {border-color:#7D3C98;background:rgba(125,60,152,0.1);}
        .kond-opt input:checked + .kond-lbl.kl-hilang .kond-tx{color:#7D3C98;}

        /* Last row (hilang) spans full */
        .kond-grid-last{display:grid;grid-template-columns:1fr;gap:0.5rem;margin-top:0.5rem;}
        .kond-lbl.kl-hilang{flex-direction:row;justify-content:center;gap:0.5rem;padding:0.65rem 1rem;}

        /* ─── Photo zone ──────────────────────────────── */
        .photo-zone{
            border:2px dashed var(--border);border-radius:9px;padding:1.3rem;
            text-align:center;cursor:pointer;position:relative;
            background:rgba(255,255,255,0.4);transition:all .2s;
        }
        .photo-zone:hover{border-color:var(--green);background:rgba(45,90,39,0.04);}
        .photo-zone input{position:absolute;inset:0;opacity:0;cursor:pointer;}
        #previewWrap{display:none;}
        #previewWrap img{width:100%;height:140px;object-fit:cover;border-radius:7px;}

        /* ─── Sidebar summary ─────────────────────────── */
        .sum-card{background:var(--white);border:1px solid var(--border);
            border-radius:14px;overflow:hidden;position:sticky;top:80px;}
        .sum-head{padding:1rem 1.2rem;border-bottom:1px solid var(--border);
            font-size:0.72rem;font-weight:800;text-transform:uppercase;
            letter-spacing:0.1em;color:var(--ink-40);}
        .sum-body{padding:1.1rem 1.2rem;}

        /* Alat preview */
        .sum-alat{display:flex;align-items:center;gap:0.75rem;
            padding-bottom:1rem;margin-bottom:1rem;border-bottom:1px solid var(--border);}
        .sum-thumb{width:44px;height:44px;border-radius:9px;overflow:hidden;flex-shrink:0;
            background:var(--cream-d);display:flex;align-items:center;justify-content:center;font-size:1.1rem;}
        .sum-thumb img{width:100%;height:100%;object-fit:cover;}
        .sum-alat-name{font-size:0.88rem;font-weight:700;color:var(--ink);line-height:1.3;}
        .sum-alat-meta{font-size:0.7rem;color:var(--ink-40);}

        .sum-row{display:flex;justify-content:space-between;align-items:center;
            padding:0.42rem 0;font-size:0.82rem;}
        .sum-key{color:var(--ink-40);}
        .sum-val{font-weight:700;color:var(--ink);}
        .sum-divider{border:none;border-top:1px dashed var(--border);margin:0.55rem 0;}

        /* Denda box */
        .denda-box{
            border-radius:9px;padding:0.9rem 1rem;margin-top:0.7rem;
            border:1px solid var(--border);background:var(--cream);
            transition:all .25s;
        }
        .denda-box.has-denda{
            background:rgba(192,57,43,0.06);
            border-color:rgba(192,57,43,0.28);
        }
        .denda-label{font-size:0.68rem;font-weight:700;text-transform:uppercase;
            letter-spacing:0.08em;color:var(--ink-40);margin-bottom:0.2rem;}
        .denda-box.has-denda .denda-label{color:var(--red);}
        .denda-val{font-family:var(--font-display);font-size:1.6rem;font-weight:900;
            color:var(--ink);line-height:1;}
        .denda-box.has-denda .denda-val{color:var(--red);}
        .denda-note{font-size:0.68rem;color:var(--ink-40);margin-top:0.2rem;}

        /* Status flow */
        .flow-box{margin-top:0.9rem;padding:0.75rem 0.9rem;
            background:rgba(45,90,39,0.06);border:1px solid rgba(45,90,39,0.2);
            border-radius:8px;}
        .flow-title{font-size:0.65rem;font-weight:800;text-transform:uppercase;
            letter-spacing:0.08em;color:var(--green);margin-bottom:0.5rem;}
        .flow-step{display:flex;align-items:flex-start;gap:0.5rem;
            font-size:0.75rem;color:var(--ink-40);padding:0.22rem 0;}
        .flow-dot{width:7px;height:7px;border-radius:50%;flex-shrink:0;margin-top:4px;}
        .fd-done{background:var(--green);}
        .fd-now {background:var(--amber);animation:blink 1.4s infinite;}
        .fd-next{background:var(--border);}
        .flow-step.now{color:var(--ink);font-weight:600;}

        /* Buttons */
        .btn-submit{
            width:100%;margin-top:1rem;padding:0.88rem;border-radius:9px;
            font-size:0.95rem;font-weight:800;
            background:var(--green);color:white;border:none;cursor:pointer;
            font-family:var(--font-body);transition:all .2s;
            display:flex;align-items:center;justify-content:center;gap:0.5rem;
        }
        .btn-submit:hover{background:var(--green-l);transform:translateY(-2px);
            box-shadow:0 8px 28px rgba(45,90,39,0.35);}
        .btn-submit:disabled{opacity:0.45;cursor:not-allowed;transform:none;box-shadow:none;}
        .btn-cancel{
            width:100%;margin-top:0.5rem;padding:0.68rem;border-radius:9px;
            font-size:0.85rem;font-weight:600;background:none;color:var(--ink-40);
            border:1.5px solid var(--border);cursor:pointer;font-family:var(--font-body);
            transition:all .15s;display:block;text-align:center;
        }
        .btn-cancel:hover{border-color:var(--ink);color:var(--ink);}

        /* Empty — no active borrows */
        .empty-wrap{
            background:var(--white);border:1px solid var(--border);border-radius:14px;
            padding:4rem 2rem;text-align:center;
        }
        .empty-icon{font-size:3rem;margin-bottom:0.75rem;}
        .empty-h{font-family:var(--font-display);font-size:1.3rem;font-weight:700;color:var(--ink);margin-bottom:0.35rem;}
        .empty-p{font-size:0.85rem;color:var(--ink-40);margin-bottom:1.4rem;}
        .btn-go{display:inline-flex;align-items:center;gap:0.4rem;padding:0.7rem 1.6rem;
            border-radius:8px;font-size:0.88rem;font-weight:700;background:var(--green);
            color:white;transition:all .15s;}
        .btn-go:hover{background:var(--green-l);transform:translateY(-1px);}

        @media(max-width:900px){
            .form-layout{grid-template-columns:1fr;}
            .sum-card{position:static;}
            .kond-grid{grid-template-columns:repeat(2,1fr);}
        }
    </style>
</head>
<body>

    {{-- Navbar --}}
    <nav class="nav">
        <a href="/" class="nav-logo">
            <span class="nav-logo-dot"></span>SewaAlat
        </a>
        <div class="nav-breadcrumb">
            <a href="/">Beranda</a>
            <span class="sep">/</span>
            <a href="{{ route('peminjam.peminjamans.index') }}">Peminjaman Saya</a>
            <span class="sep">/</span>
            <span style="color:var(--ink);font-weight:600">Lapor Pengembalian</span>
        </div>
        <div class="nav-user">
            <div class="user-av">{{ strtoupper(substr(auth()->user()->name,0,2)) }}</div>
        </div>
    </nav>

    <div class="page-wrap">

        @if(session('error'))
            <div class="flash-err">⚠ {{ session('error') }}</div>
        @endif

        <div class="page-eyebrow">Form Pengembalian</div>
        <h1 class="page-h1">Lapor Pengembalian Alat</h1>
        <p class="page-sub">
            Isi form ini sebelum menyerahkan alat ke petugas.
            Petugas akan memverifikasi kondisi dan menghitung tagihan akhir.
        </p>

        {{-- No active borrows --}}
        @if($peminjamansAktif->isEmpty())
            <div class="empty-wrap">
                <div class="empty-icon">📦</div>
                <div class="empty-h">Tidak Ada Alat yang Perlu Dikembalikan</div>
                <p class="empty-p">
                    Kamu tidak memiliki peminjaman aktif saat ini.<br>
                    Semua alat sudah dikembalikan atau belum ada yang dipinjam.
                </p>
                <a href="{{ route('peminjam.peminjamans.index') }}" class="btn-go">
                    ← Lihat Peminjaman Saya
                </a>
            </div>

        @else

        <form method="POST" action="{{ route('peminjam.pengembalians.store') }}"
              enctype="multipart/form-data" id="returnForm">
            @csrf
            <input type="hidden" name="peminjaman_id" id="peminjamanId"
                   value="{{ old('peminjaman_id', $peminjaman?->id) }}">

            <div class="form-layout">

                {{-- ─── FORM ──────────────────────────────── --}}
                <div>

                    {{-- Step 1 — pilih peminjaman --}}
                    <div class="fcard" style="margin-bottom:1.1rem">
                        <div class="fsec">
                            <div class="fsec-title">
                                <div class="fsec-num">1</div>
                                Pilih Peminjaman yang Dikembalikan
                            </div>

                            <div class="pm-list">
                                @foreach($peminjamansAktif as $pm)
                                    @php
                                        $isLate = $pm->is_terlambat;
                                        $isSelected = old('peminjaman_id', $peminjaman?->id) == $pm->id;
                                    @endphp
                                    <div class="pm-opt {{ $isLate ? 'is-late':'' }} {{ $isSelected ? 'selected':'' }}"
                                         id="pmOpt{{ $pm->id }}"
                                         onclick="selectPeminjaman(
                                             {{ $pm->id }},
                                             {{ Js::from($pm->nomor_pinjam) }},
                                             {{ Js::from($pm->alat->nama ?? '') }},
                                             {{ Js::from($pm->alat->kode ?? '') }},
                                             {{ $pm->jumlah }},
                                             {{ Js::from($pm->tanggal_kembali_rencana->toDateString()) }},
                                             {{ (float)($pm->alat->denda_per_hari ?? 0) }},
                                             {{ Js::from($pm->alat?->foto ? asset('storage/'.$pm->alat->foto) : '') }},
                                             {{ Js::from($pm->alat?->kategori?->ikon ?? '🔧') }}
                                         )">
                                        <div class="pm-radio">
                                            <div class="pm-radio-dot"></div>
                                        </div>
                                        <div class="pm-thumb">
                                            @if($pm->alat?->foto)
                                                <img src="{{ asset('storage/'.$pm->alat->foto) }}" alt="">
                                            @else
                                                {{ $pm->alat?->kategori?->ikon ?? '🔧' }}
                                            @endif
                                        </div>
                                        <div style="flex:1;min-width:0">
                                            <div class="pm-nomor">{{ $pm->nomor_pinjam }}</div>
                                            <div class="pm-name">{{ $pm->alat->nama ?? '—' }}</div>
                                            <div class="pm-meta">
                                                {{ $pm->jumlah }} unit
                                                · {{ $pm->durasi_hari }} hari
                                                · Rp {{ number_format($pm->total_biaya,0,',','.') }}
                                            </div>
                                            @if($isLate)
                                                <div class="late-badge">
                                                    ⚠ Terlambat {{ $pm->keterlambatan_hari }} hari
                                                </div>
                                            @endif
                                        </div>
                                        <div class="pm-dates">
                                            <div class="pm-date-lbl">Batas kembali</div>
                                            <div class="pm-date-val"
                                                 style="{{ $isLate ? 'color:var(--red)' : '' }}">
                                                {{ $pm->tanggal_kembali_rencana->format('d M Y') }}
                                            </div>
                                            @if($isLate)
                                                <div class="pm-date-lbl" style="color:var(--red);margin-top:0.15rem">
                                                    Est. denda:
                                                    Rp {{ number_format($pm->keterlambatan_hari * ($pm->alat->denda_per_hari??0) * $pm->jumlah,0,',','.') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Late warning --}}
                            <div class="late-warn" id="lateWarn">
                                <strong>⚠ Peminjaman Ini Terlambat!</strong><br>
                                Denda keterlambatan akan dihitung otomatis oleh sistem.
                                Jumlah tagihan akhir akan ditentukan petugas saat verifikasi.
                            </div>

                            @error('peminjaman_id')
                                <p class="ferr" style="margin-top:0.5rem">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Step 2 — tanggal kembali --}}
                    <div class="fcard" style="margin-bottom:1.1rem">
                        <div class="fsec">
                            <div class="fsec-title">
                                <div class="fsec-num">2</div>
                                Tanggal Pengembalian
                            </div>
                            <div class="fg">
                                <label class="fl" for="tglAktual">
                                    Tanggal Kembali Aktual <span class="req">*</span>
                                </label>
                                <input type="date" name="tanggal_kembali_aktual" id="tglAktual"
                                       value="{{ old('tanggal_kembali_aktual', now()->toDateString()) }}"
                                       max="{{ now()->toDateString() }}"
                                       class="fi {{ $errors->has('tanggal_kembali_aktual') ? 'is-err':'' }}"
                                       oninput="recalcDenda()">
                                <p class="fhint">Isi dengan tanggal hari ini atau tanggal saat kamu menyerahkan alat.</p>
                                @error('tanggal_kembali_aktual')
                                    <p class="ferr">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Step 3 — kondisi alat --}}
                    <div class="fcard" style="margin-bottom:1.1rem">
                        <div class="fsec">
                            <div class="fsec-title">
                                <div class="fsec-num">3</div>
                                Kondisi Alat Saat Dikembalikan
                            </div>

                            {{-- Kondisi 5 options --}}
                            <div class="kond-grid">
                                @foreach([
                                    ['baik',         'kl-baik',   '✅', 'Baik'],
                                    ['rusak_ringan',  'kl-ringan', '⚠️', 'Rusak Ringan'],
                                    ['rusak_sedang',  'kl-sedang', '🔶', 'Rusak Sedang'],
                                    ['rusak_berat',   'kl-berat',  '❌', 'Rusak Berat'],
                                ] as [$val,$cls,$em,$lbl])
                                    <div class="kond-opt">
                                        <input type="radio" name="kondisi_kembali"
                                               id="kd_{{ $val }}" value="{{ $val }}"
                                               {{ old('kondisi_kembali','baik')===$val ? 'checked':'' }}>
                                        <label for="kd_{{ $val }}" class="kond-lbl {{ $cls }}">
                                            <span class="kond-em">{{ $em }}</span>
                                            <span class="kond-tx">{{ $lbl }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="kond-grid-last">
                                <div class="kond-opt">
                                    <input type="radio" name="kondisi_kembali"
                                           id="kd_hilang" value="hilang"
                                           {{ old('kondisi_kembali')==='hilang' ? 'checked':'' }}>
                                    <label for="kd_hilang" class="kond-lbl kl-hilang">
                                        <span class="kond-em">💀</span>
                                        <span class="kond-tx">Hilang / Tidak Dapat Dikembalikan</span>
                                    </label>
                                </div>
                            </div>

                            {{-- Damage note shown when rusak / hilang --}}
                            <div id="rusakNote" style="display:none;margin-top:0.9rem">
                                <div style="padding:0.85rem 1rem;background:rgba(192,57,43,0.07);
                                     border:1px solid rgba(192,57,43,0.25);border-radius:8px;
                                     font-size:0.8rem;color:var(--red);line-height:1.5">
                                    <strong style="font-weight:800">ℹ Catatan Kondisi Bermasalah</strong><br>
                                    Biaya kerusakan atau kehilangan akan dihitung oleh petugas saat verifikasi.
                                    Jelaskan kondisi sedetail mungkin pada kolom catatan di bawah.
                                </div>
                            </div>

                            @error('kondisi_kembali')
                                <p class="ferr" style="margin-top:0.5rem">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Step 4 — catatan & foto --}}
                    <div class="fcard">
                        <div class="fsec">
                            <div class="fsec-title">
                                <div class="fsec-num">4</div>
                                Catatan &amp; Foto Bukti
                            </div>

                            <div class="fg">
                                <label class="fl" for="catatanInput">Catatan (opsional)</label>
                                <div style="position:relative">
                                    <textarea name="catatan" id="catatanInput"
                                              class="fta {{ $errors->has('catatan') ? 'is-err':'' }}"
                                              rows="3" maxlength="1000"
                                              placeholder="Deskripsikan kondisi alat, catatan khusus, atau keterangan tambahan..."
                                              oninput="updateCc()">{{ old('catatan') }}</textarea>
                                    <span id="cc" style="position:absolute;bottom:0.5rem;right:0.7rem;
                                          font-size:0.62rem;color:var(--ink-20)">0/1000</span>
                                </div>
                                @error('catatan') <p class="ferr">{{ $message }}</p> @enderror
                            </div>

                            <div class="fg" style="margin:0">
                                <label class="fl">Foto Bukti Kondisi (opsional)</label>
                                <div class="photo-zone" id="photoZone">
                                    <input type="file" name="foto_bukti" accept="image/*"
                                           onchange="previewFoto(this)">
                                    <div id="photoPlaceholder">
                                        <div style="font-size:1.5rem;margin-bottom:0.3rem">📷</div>
                                        <div style="font-size:0.8rem;color:var(--ink-40);font-weight:500">
                                            Foto kondisi alat
                                        </div>
                                        <div style="font-size:0.68rem;color:var(--ink-20);margin-top:0.15rem">
                                            JPG / PNG / WEBP · Maks 3 MB
                                        </div>
                                    </div>
                                    <div id="previewWrap">
                                        <img id="previewImg" src="" alt="Preview foto">
                                        <button type="button" onclick="removeFoto()"
                                                style="margin-top:0.4rem;background:none;border:none;
                                                       color:var(--ink-40);font-size:0.75rem;cursor:pointer;
                                                       font-family:var(--font-body)">
                                            ✕ Hapus foto
                                        </button>
                                    </div>
                                </div>
                                @error('foto_bukti') <p class="ferr" style="margin-top:0.35rem">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ─── SIDEBAR ─────────────────────────── --}}
                <div>
                    <div class="sum-card">
                        <div class="sum-head">Ringkasan Pengembalian</div>
                        <div class="sum-body">

                            {{-- Alat preview --}}
                            <div class="sum-alat">
                                <div class="sum-thumb" id="sumThumb">
                                    @if($peminjaman?->alat?->foto)
                                        <img src="{{ asset('storage/'.$peminjaman->alat->foto) }}" alt="">
                                    @else
                                        <span id="sumEmoji">{{ $peminjaman?->alat?->kategori?->ikon ?? '🔧' }}</span>
                                    @endif
                                </div>
                                <div>
                                    <div class="sum-alat-name" id="sumAlatName">
                                        {{ $peminjaman?->alat?->nama ?? '— Pilih peminjaman' }}
                                    </div>
                                    <div class="sum-alat-meta" id="sumNomor">
                                        {{ $peminjaman?->nomor_pinjam ?? '' }}
                                    </div>
                                </div>
                            </div>

                            <div class="sum-row">
                                <span class="sum-key">Batas kembali</span>
                                <span class="sum-val" id="sumBatas">—</span>
                            </div>
                            <div class="sum-row">
                                <span class="sum-key">Tgl kembali aktual</span>
                                <span class="sum-val" id="sumAktual">—</span>
                            </div>
                            <div class="sum-row">
                                <span class="sum-key">Keterlambatan</span>
                                <span class="sum-val" id="sumTerlambat">—</span>
                            </div>
                            <hr class="sum-divider">
                            <div class="sum-row">
                                <span class="sum-key">Biaya sewa</span>
                                <span class="sum-val" id="sumBiaya">—</span>
                            </div>

                            {{-- Denda box --}}
                            <div class="denda-box" id="dendaBox">
                                <div class="denda-label">Estimasi Denda</div>
                                <div class="denda-val" id="sumDenda">Rp 0</div>
                                <div class="denda-note" id="dendaNote">
                                    Tepat waktu — tidak ada denda 🎉
                                </div>
                            </div>

                            {{-- Flow info --}}
                            <div class="flow-box">
                                <div class="flow-title">Proses Selanjutnya</div>
                                <div class="flow-step">
                                    <div class="flow-dot fd-done"></div>
                                    <span>Peminjaman berjalan</span>
                                </div>
                                <div class="flow-step now">
                                    <div class="flow-dot fd-now"></div>
                                    <span><strong>Lapor pengembalian (sekarang)</strong></span>
                                </div>
                                <div class="flow-step">
                                    <div class="flow-dot fd-next"></div>
                                    <span>Petugas verifikasi kondisi</span>
                                </div>
                                <div class="flow-step">
                                    <div class="flow-dot fd-next"></div>
                                    <span>Tagihan akhir dikonfirmasi</span>
                                </div>
                                <div class="flow-step">
                                    <div class="flow-dot fd-next"></div>
                                    <span>Peminjaman selesai ✓</span>
                                </div>
                            </div>

                            <button type="submit" class="btn-submit" id="submitBtn" disabled>
                                <span>↩</span> Kirim Laporan Pengembalian
                            </button>
                            <a href="{{ route('peminjam.peminjamans.index') }}" class="btn-cancel">
                                Batal, kembali ke daftar
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </form>

        @endif
    </div>

    @php
        // Pre-compute JS data to avoid Blade parse issues
        $preselected = null;
        if ($peminjaman) {
            $preselected = [
                'id'       => $peminjaman->id,
                'nomor'    => $peminjaman->nomor_pinjam,
                'nama'     => $peminjaman->alat->nama ?? '',
                'kode'     => $peminjaman->alat->kode ?? '',
                'jumlah'   => $peminjaman->jumlah,
                'batas'    => $peminjaman->tanggal_kembali_rencana->toDateString(),
                'denda'    => (float)($peminjaman->alat->denda_per_hari ?? 0),
                'biaya'    => (float)$peminjaman->total_biaya,
                'foto'     => $peminjaman->alat?->foto ? asset('storage/'.$peminjaman->alat->foto) : '',
                'emoji'    => $peminjaman->alat?->kategori?->ikon ?? '🔧',
                'isLate'   => $peminjaman->is_terlambat,
                'lateDays' => $peminjaman->keterlambatan_hari,
            ];
        }
    @endphp

    <script>
        // ── State ──────────────────────────────────────────────────
        let pm = @json($preselected);   // pre-selected peminjaman (may be null)

        // ── Init ───────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', () => {
            updateCc();
            if (pm) {
                updateSidebar();
                recalcDenda();
                document.getElementById('submitBtn').disabled = false;
                // Show late warn if pre-selected and late
                if (pm.isLate) document.getElementById('lateWarn').classList.add('visible');
            }
            // Kondisi change listener
            document.querySelectorAll('input[name="kondisi_kembali"]').forEach(r => {
                r.addEventListener('change', onKondisiChange);
            });
        });

        // ── Select peminjaman ──────────────────────────────────────
        function selectPeminjaman(id, nomor, nama, kode, jumlah, batas, dendaPerHari, foto, emoji) {
            pm = { id, nomor, nama, kode, jumlah, batas, denda: dendaPerHari, foto, emoji };

            // Hidden field
            document.getElementById('peminjamanId').value = id;

            // Visual
            document.querySelectorAll('.pm-opt').forEach(el => el.classList.remove('selected'));
            const el = document.getElementById('pmOpt' + id);
            if (el) el.classList.add('selected');

            // Late warn
            const warn = document.getElementById('lateWarn');
            if (el && el.classList.contains('is-late')) {
                warn.classList.add('visible');
            } else {
                warn.classList.remove('visible');
            }

            updateSidebar();
            recalcDenda();
            document.getElementById('submitBtn').disabled = false;
        }

        function updateSidebar() {
            if (!pm) return;
            // Thumb
            const thumb = document.getElementById('sumThumb');
            thumb.innerHTML = pm.foto
                ? `<img src="${pm.foto}" alt="" style="width:100%;height:100%;object-fit:cover">`
                : `<span>${pm.emoji}</span>`;
            document.getElementById('sumAlatName').textContent = pm.nama || '—';
            document.getElementById('sumNomor').textContent    = pm.nomor || '';

            const batasFmt = new Date(pm.batas).toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'});
            document.getElementById('sumBatas').textContent = batasFmt;
            document.getElementById('sumBiaya').textContent = pm.biaya
                ? 'Rp ' + pm.biaya.toLocaleString('id-ID')
                : '—';
        }

        // ── Live denda calculator ──────────────────────────────────
        function recalcDenda() {
            const aktualVal = document.getElementById('tglAktual').value;
            if (!aktualVal) return;

            const aktual  = new Date(aktualVal);
            const sumEl   = document.getElementById('sumAktual');
            sumEl.textContent = aktual.toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'});

            if (!pm) return;

            const rencana   = new Date(pm.batas);
            const diffMs    = aktual - rencana;
            const terlambat = diffMs > 0 ? Math.round(diffMs/(1000*60*60*24)) : 0;
            const denda     = terlambat * (pm.denda ?? 0) * (pm.jumlah ?? 1);

            // Keterlambatan
            const terlambatEl = document.getElementById('sumTerlambat');
            if (terlambat > 0) {
                terlambatEl.textContent = `+${terlambat} hari`;
                terlambatEl.style.color = 'var(--red)';
            } else {
                terlambatEl.textContent = 'Tepat waktu ✓';
                terlambatEl.style.color = 'var(--green)';
            }

            // Denda box
            const box  = document.getElementById('dendaBox');
            const val  = document.getElementById('sumDenda');
            const note = document.getElementById('dendaNote');
            val.textContent = 'Rp ' + denda.toLocaleString('id-ID');
            if (denda > 0) {
                box.classList.add('has-denda');
                note.textContent = `Denda ${terlambat} hari × ${(pm.jumlah??1)} unit`;
            } else {
                box.classList.remove('has-denda');
                note.textContent = 'Tepat waktu — tidak ada denda 🎉';
            }
        }

        // ── Kondisi change ─────────────────────────────────────────
        function onKondisiChange() {
            const val = document.querySelector('input[name="kondisi_kembali"]:checked')?.value;
            const note = document.getElementById('rusakNote');
            note.style.display = (val && val !== 'baik') ? 'block' : 'none';
        }

        // ── Char counter ───────────────────────────────────────────
        function updateCc() {
            const len = document.getElementById('catatanInput').value.length;
            const el  = document.getElementById('cc');
            el.textContent = `${len}/1000`;
            el.style.color = len > 900 ? 'var(--amber)' : 'var(--ink-20)';
        }

        // ── Photo preview ──────────────────────────────────────────
        function previewFoto(input) {
            if (input.files?.[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('photoPlaceholder').style.display = 'none';
                    document.getElementById('previewWrap').style.display       = 'block';
                    document.getElementById('previewImg').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        function removeFoto() {
            document.querySelector('input[name="foto_bukti"]').value = '';
            document.getElementById('photoPlaceholder').style.display = 'block';
            document.getElementById('previewWrap').style.display       = 'none';
        }

        // Drag-drop
        const zone = document.getElementById('photoZone');
        if (zone) {
            zone.addEventListener('dragover',  e => { e.preventDefault(); zone.style.borderColor='var(--green)'; });
            zone.addEventListener('dragleave', () => { zone.style.borderColor=''; });
            zone.addEventListener('drop', e => {
                e.preventDefault(); zone.style.borderColor='';
                const f = e.dataTransfer.files[0];
                if (f) {
                    const input = document.querySelector('input[name="foto_bukti"]');
                    const dt = new DataTransfer(); dt.items.add(f); input.files = dt.files;
                    previewFoto(input);
                }
            });
        }
    </script>

</body>
</html>