<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Saya — SewaAlat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --ink:#1A1710;--ink-80:#2E2B21;--ink-40:#6B6654;--ink-20:#A09880;
            --cream:#F5F0E8;--cream-d:#EDE5D4;--cream-dd:#E3D8C2;
            --green:#2D5A27;--green-l:#3D7A35;--green-ll:#5A9E50;
            --amber:#C4860A;--red:#C0392B;--white:#FDFAF5;
            --border:rgba(26,23,16,0.12);--shadow:0 2px 20px rgba(26,23,16,0.1);
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
        body::before{content:'';position:fixed;inset:0;pointer-events:none;z-index:1;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23n)' opacity='0.035'/%3E%3C/svg%3E");opacity:0.6;}

        .nav{position:sticky;top:0;z-index:100;background:rgba(245,240,232,0.9);backdrop-filter:blur(12px);border-bottom:1px solid var(--border);padding:0 max(2rem,calc((100vw - 1280px)/2 + 2rem));display:flex;align-items:center;justify-content:space-between;height:64px;}
        .nav-logo{font-family:var(--font-display);font-size:1.35rem;font-weight:900;color:var(--ink);letter-spacing:-0.02em;display:flex;align-items:center;gap:0.4rem;}
        .nav-logo-dot{width:8px;height:8px;border-radius:50%;background:var(--green);display:inline-block;margin-bottom:3px;}
        .nav-links{display:flex;align-items:center;gap:1.5rem;}
        .nav-links a{font-size:0.85rem;font-weight:500;color:var(--ink-40);transition:color .15s;}
        .nav-links a:hover,.nav-links a.active{color:var(--ink);}
        .nav-right{display:flex;align-items:center;gap:0.75rem;}
        .user-av{width:32px;height:32px;border-radius:50%;background:var(--green);color:white;display:flex;align-items:center;justify-content:center;font-size:0.72rem;font-weight:800;}
        .btn-sewa-nav{padding:0.5rem 1.1rem;border-radius:6px;font-size:0.84rem;font-weight:700;background:var(--green);color:white;transition:all .15s;}
        .btn-sewa-nav:hover{background:var(--green-l);}

        .page-wrap{max-width:1100px;margin:0 auto;padding:2.5rem max(1.5rem,2vw);}
        .page-eyebrow{font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.14em;color:var(--green);margin-bottom:0.4rem;display:flex;align-items:center;gap:0.4rem;}
        .page-eyebrow::before{content:'';width:18px;height:1.5px;background:var(--green);}
        .page-h1{font-family:var(--font-display);font-size:clamp(1.8rem,3.5vw,2.6rem);font-weight:900;letter-spacing:-0.025em;line-height:1.1;margin-bottom:0.3rem;}
        .page-sub{font-size:0.9rem;color:var(--ink-40);}

        .flash-success,.flash-error{display:flex;align-items:flex-start;gap:0.65rem;padding:0.9rem 1.1rem;border-radius:8px;margin-bottom:1.4rem;font-size:0.85rem;font-weight:500;}
        .flash-success{background:rgba(45,90,39,0.08);border:1px solid rgba(45,90,39,0.25);color:var(--green);}
        .flash-error  {background:rgba(192,57,43,0.08);border:1px solid rgba(192,57,43,0.25);color:var(--red);}

        /* Header row */
        .header-row{display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-bottom:1.6rem;}

        /* Stats strip */
        .stat-strip{display:grid;grid-template-columns:repeat(4,1fr);gap:0.75rem;margin-bottom:1.6rem;}
        .stat-box{background:var(--white);border:1px solid var(--border);border-radius:10px;padding:0.9rem 1.1rem;cursor:pointer;transition:all .15s;text-decoration:none;display:block;}
        .stat-box:hover{border-color:rgba(45,90,39,0.3);box-shadow:var(--shadow);}
        .stat-box.active{border-color:var(--green);background:rgba(45,90,39,0.05);}
        .stat-num{font-family:var(--font-display);font-size:1.8rem;font-weight:900;color:var(--ink);line-height:1;}
        .stat-lbl{font-size:0.68rem;font-weight:600;color:var(--ink-40);margin-top:0.15rem;text-transform:uppercase;letter-spacing:0.05em;}
        .stat-box.active .stat-lbl{color:var(--green);}

        /* Peminjaman cards */
        .pm-list{display:flex;flex-direction:column;gap:1rem;}
        .pm-card{background:var(--white);border:1px solid var(--border);border-radius:14px;overflow:hidden;transition:border-color .15s,box-shadow .15s;}
        .pm-card:hover{border-color:rgba(45,90,39,0.25);box-shadow:var(--shadow);}
        .pm-card.late{border-color:rgba(192,57,43,0.3);background:rgba(192,57,43,0.02);}
        .pm-card.done{opacity:0.8;}

        /* Card stripe top */
        .pm-stripe{height:3px;}
        .stripe-menunggu    {background:var(--amber);}
        .stripe-disetujui   {background:#2B6CB0;}
        .stripe-dipinjam    {background:var(--green);}
        .stripe-dikembalikan{background:var(--ink-20);}
        .stripe-ditolak     {background:var(--red);}

        /* Card layout */
        .pm-inner{display:grid;grid-template-columns:56px 1fr auto;gap:1.1rem;padding:1.1rem 1.2rem;align-items:start;}
        .pm-thumb{width:56px;height:56px;border-radius:10px;overflow:hidden;flex-shrink:0;background:var(--cream-d);display:flex;align-items:center;justify-content:center;font-size:1.4rem;}
        .pm-thumb img{width:100%;height:100%;object-fit:cover;}

        .pm-nomor{font-family:var(--font-mono);font-size:0.72rem;color:var(--green);font-weight:500;margin-bottom:0.15rem;}
        .pm-nama{font-size:0.95rem;font-weight:700;color:var(--ink);margin-bottom:0.1rem;line-height:1.3;}
        .pm-meta{font-size:0.75rem;color:var(--ink-40);}

        .pm-right{text-align:right;}
        .pm-biaya-lbl{font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--ink-40);}
        .pm-biaya-val{font-family:var(--font-display);font-size:1.2rem;font-weight:900;color:var(--ink);}

        /* Status badges */
        .pm-badge{display:inline-flex;align-items:center;gap:0.28rem;padding:0.28rem 0.75rem;border-radius:100px;font-size:0.68rem;font-weight:700;margin-top:0.35rem;}
        .pm-badge::before{content:'';width:5px;height:5px;border-radius:50%;flex-shrink:0;}
        .st-menunggu    {background:rgba(196,134,10,0.1);color:var(--amber);border:1px solid rgba(196,134,10,0.2);}
        .st-menunggu::before{background:var(--amber);animation:blink 1.5s infinite;}
        .st-disetujui   {background:rgba(43,108,176,0.1);color:#2B6CB0;border:1px solid rgba(43,108,176,0.2);}
        .st-disetujui::before{background:#2B6CB0;}
        .st-dipinjam    {background:rgba(45,90,39,0.1);color:var(--green);border:1px solid rgba(45,90,39,0.2);}
        .st-dipinjam::before{background:var(--green);}
        .st-dikembalikan{background:rgba(106,102,84,0.1);color:var(--ink-40);border:1px solid var(--border);}
        .st-dikembalikan::before{background:var(--ink-40);}
        .st-ditolak     {background:rgba(192,57,43,0.1);color:var(--red);border:1px solid rgba(192,57,43,0.2);}
        .st-ditolak::before{background:var(--red);}
        @keyframes blink{0%,100%{opacity:1}50%{opacity:.3}}

        /* Timeline progress bar */
        .pm-progress{padding:0.7rem 1.2rem;border-top:1px solid var(--border);display:flex;align-items:center;gap:0.3rem;}
        .prog-step{display:flex;flex-direction:column;align-items:center;gap:0.2rem;flex:1;}
        .prog-dot{width:9px;height:9px;border-radius:50%;border:2px solid var(--border);}
        .prog-dot.done{background:var(--green);border-color:var(--green);}
        .prog-dot.current{background:var(--amber);border-color:var(--amber);animation:blink 1.4s infinite;}
        .prog-dot.rejected{background:var(--red);border-color:var(--red);}
        .prog-label{font-size:0.55rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--ink-20);text-align:center;}
        .prog-dot.done ~ .prog-label,
        .prog-step:has(.prog-dot.done) .prog-label{color:var(--green);}
        .prog-step:has(.prog-dot.current) .prog-label{color:var(--amber);}
        .prog-line{flex:0 0 auto;width:100%;height:2px;background:var(--border);}
        .prog-line.done{background:var(--green);}

        /* Card footer */
        .pm-footer{padding:0.65rem 1.2rem;border-top:1px solid var(--border);background:rgba(26,23,16,0.02);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.5rem;}
        .pm-footer-date{font-size:0.72rem;color:var(--ink-40);}
        .pm-footer-actions{display:flex;align-items:center;gap:0.5rem;}
        .btn-sm{display:inline-flex;align-items:center;gap:0.3rem;padding:0.4rem 0.9rem;border-radius:6px;font-size:0.75rem;font-weight:700;transition:all .15s;}
        .btn-katalog{background:var(--green);color:white;}
        .btn-katalog:hover{background:var(--green-l);}
        .btn-cancel{background:rgba(192,57,43,0.1);color:var(--red);border:1px solid rgba(192,57,43,0.2);cursor:pointer;font-family:var(--font-body);}
        .btn-cancel:hover{background:rgba(192,57,43,0.18);}

        /* Late alert */
        .late-alert{display:flex;align-items:center;gap:0.5rem;padding:0.55rem 1.2rem;background:rgba(192,57,43,0.07);border-bottom:1px solid rgba(192,57,43,0.15);font-size:0.75rem;font-weight:700;color:var(--red);}

        /* Catatan petugas */
        .catatan-strip{padding:0.65rem 1.2rem;border-top:1px solid var(--border);background:rgba(43,108,176,0.04);font-size:0.78rem;color:#2B6CB0;font-style:italic;}

        /* Empty state */
        .empty-state{text-align:center;padding:4rem 1.5rem;background:var(--white);border:1px solid var(--border);border-radius:14px;}
        .empty-icon{font-size:3rem;margin-bottom:0.75rem;}
        .empty-h{font-family:var(--font-display);font-size:1.3rem;font-weight:700;color:var(--ink);margin-bottom:0.35rem;}
        .empty-p{font-size:0.85rem;color:var(--ink-40);margin-bottom:1.4rem;}
        .btn-go{display:inline-flex;align-items:center;gap:0.4rem;padding:0.7rem 1.6rem;border-radius:8px;font-size:0.88rem;font-weight:700;background:var(--green);color:white;transition:all .15s;}
        .btn-go:hover{background:var(--green-l);transform:translateY(-1px);}

        /* Pagination */
        .pag{display:flex;align-items:center;justify-content:center;gap:0.3rem;margin-top:1.5rem;}
        .pag-link{width:34px;height:34px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:0.78rem;font-weight:700;text-decoration:none;background:var(--white);border:1px solid var(--border);color:var(--ink-40);transition:all .15s;}
        .pag-link:hover,.pag-link.active{background:var(--green);color:white;border-color:var(--green);}
        .pag-link.disabled{opacity:0.3;pointer-events:none;}

        /* Confirm delete modal */
        .modal-overlay{display:none;position:fixed;inset:0;z-index:200;background:rgba(26,23,16,0.55);backdrop-filter:blur(4px);align-items:center;justify-content:center;}
        .modal-overlay.open{display:flex;}
        .modal-box{background:var(--white);border:1px solid var(--border);border-radius:14px;padding:1.8rem;max-width:400px;width:90%;box-shadow:var(--shadow-lg);}
        .modal-title{font-family:var(--font-display);font-size:1.2rem;font-weight:900;color:var(--ink);margin-bottom:0.5rem;}
        .modal-text{font-size:0.85rem;color:var(--ink-40);line-height:1.55;margin-bottom:1.3rem;}
        .modal-actions{display:flex;gap:0.6rem;}
        .modal-confirm{flex:1;padding:0.65rem;border-radius:8px;font-size:0.85rem;font-weight:700;background:var(--red);color:white;border:none;cursor:pointer;font-family:var(--font-body);transition:background .15s;}
        .modal-confirm:hover{background:#a93226;}
        .modal-cancel{flex:1;padding:0.65rem;border-radius:8px;font-size:0.85rem;font-weight:700;background:none;color:var(--ink-40);border:1.5px solid var(--border);cursor:pointer;font-family:var(--font-body);}

        @media(max-width:900px){.stat-strip{grid-template-columns:1fr 1fr;}.pm-inner{grid-template-columns:44px 1fr;}.pm-right{display:none;}.nav-links{display:none;}}
        @media(max-width:600px){.stat-strip{grid-template-columns:1fr 1fr;}}
    </style>
</head>
<body>

    <nav class="nav">
        <a href="/" class="nav-logo"><span class="nav-logo-dot"></span>SewaAlat</a>
        <div class="nav-links">
            <a href="/katalog">Katalog Alat</a>
            <a href="{{ route('peminjam.peminjamans.index') }}" class="active">Peminjaman Saya</a>
        </div>
        <div class="nav-right">
            <a href="{{ route('peminjam.peminjamans.create') }}" class="btn-sewa-nav">＋ Ajukan Sewa</a>
            <div class="user-av">{{ strtoupper(substr(auth()->user()->name,0,2)) }}</div>
        </div>
    </nav>

    <div class="page-wrap">

        @if(session('success'))
            <div class="flash-success">✓ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="flash-error">⚠ {{ session('error') }}</div>
        @endif

        <div class="header-row">
            <div>
                <div class="page-eyebrow">Akun Saya</div>
                <h1 class="page-h1">Peminjaman Saya</h1>
                <p class="page-sub">Pantau status pengajuan dan riwayat sewa Anda.</p>
            </div>
            <a href="{{ route('peminjam.peminjamans.create') }}" class="btn-go" style="padding:0.7rem 1.4rem">
                ＋ Ajukan Sewa Baru
            </a>
        </div>

        {{-- Stats / Tabs --}}
        <div class="stat-strip">
            @foreach([
                ['semua', 'Semua', $counts['semua']],
                ['menunggu', 'Menunggu', $counts['menunggu']],
                ['dipinjam', 'Sedang Dipinjam', $counts['dipinjam']],
                ['dikembalikan', 'Selesai', $counts['dikembalikan']],
            ] as [$val,$lbl,$count])
                <a href="{{ route('peminjam.peminjamans.index', ['status'=>$val==='semua'?null:$val]) }}"
                   class="stat-box {{ request('status',$val==='semua'?'semua':null) === ($val==='semua'?'semua':$val) ? 'active' : '' }}
                          {{ request()->missing('status') && $val==='semua' ? 'active':'' }}
                          {{ request('status')===$val ? 'active':'' }}">
                    <div class="stat-num">{{ $count }}</div>
                    <div class="stat-lbl">{{ $lbl }}</div>
                </a>
            @endforeach
        </div>

        {{-- List --}}
        @if($peminjamans->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">📋</div>
                <div class="empty-h">Belum Ada Peminjaman</div>
                <p class="empty-p">Kamu belum pernah mengajukan sewa alat.<br>Yuk mulai dari katalog kami!</p>
                <a href="/katalog" class="btn-go">Lihat Katalog Alat →</a>
            </div>
        @else
            <div class="pm-list">
                @foreach($peminjamans as $pm)
                    @php
                        $isLate = $pm->is_terlambat;
                        $statusMap = [
                            'menunggu'    => ['Menunggu Konfirmasi','st-menunggu','stripe-menunggu'],
                            'disetujui'   => ['Disetujui','st-disetujui','stripe-disetujui'],
                            'dipinjam'    => ['Sedang Dipinjam','st-dipinjam','stripe-dipinjam'],
                            'dikembalikan'=> ['Sudah Dikembalikan','st-dikembalikan','stripe-dikembalikan'],
                            'ditolak'     => ['Ditolak','st-ditolak','stripe-ditolak'],
                        ];
                        [$statusLabel,$statusClass,$stripeClass] = $statusMap[$pm->status] ?? ['—','',''];
                    @endphp

                    <div class="pm-card {{ $isLate ? 'late':'' }} {{ in_array($pm->status,['dikembalikan','ditolak']) ? 'done':'' }}">

                        {{-- Top stripe --}}
                        <div class="pm-stripe {{ $stripeClass }}"></div>

                        {{-- Late alert --}}
                        @if($isLate)
                            <div class="late-alert">
                                ⚠ Terlambat {{ $pm->keterlambatan_hari }} hari — segera kembalikan alat untuk menghindari denda lebih lanjut
                            </div>
                        @endif

                        {{-- Main content --}}
                        <div class="pm-inner">
                            <div class="pm-thumb">
                                @if($pm->alat?->foto)
                                    <img src="{{ asset('storage/'.$pm->alat->foto) }}" alt="">
                                @else
                                    {{ $pm->alat?->kategori?->ikon ?? '🔧' }}
                                @endif
                            </div>
                            <div>
                                <div class="pm-nomor">{{ $pm->nomor_pinjam }}</div>
                                <div class="pm-nama">{{ $pm->alat->nama ?? '—' }}</div>
                                <div class="pm-meta">
                                    {{ $pm->jumlah }}× unit
                                    @if($pm->alat?->kategori) · {{ $pm->alat->kategori->nama }} @endif
                                    · {{ $pm->durasi_hari }} hari sewa
                                </div>
                                <span class="pm-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                            </div>
                            <div class="pm-right">
                                <div class="pm-biaya-lbl">Total Biaya</div>
                                <div class="pm-biaya-val">Rp {{ number_format($pm->total_biaya,0,',','.') }}</div>
                                @if($isLate)
                                    <div style="font-size:0.68rem;color:var(--red);font-weight:600;margin-top:0.2rem">
                                        Est. denda: Rp {{ number_format($pm->keterlambatan_hari * ($pm->alat->denda_per_hari??0) * $pm->jumlah,0,',','.') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Progress timeline --}}
                        <div class="pm-progress">
                            @php
                                $steps = [
                                    ['Dikirim', in_array($pm->status, ['menunggu','disetujui','dipinjam','dikembalikan','ditolak'])],
                                    ['Disetujui', in_array($pm->status, ['disetujui','dipinjam','dikembalikan'])],
                                    ['Dipinjam', in_array($pm->status, ['dipinjam','dikembalikan'])],
                                    ['Selesai', $pm->status === 'dikembalikan'],
                                ];
                                $currentStep = match($pm->status) {
                                    'menunggu' => 0, 'disetujui' => 1,
                                    'dipinjam' => 2, 'dikembalikan' => 3,
                                    default => -1,
                                };
                            @endphp

                            @foreach($steps as $i => [$slabel, $isDone])
                                <div class="prog-step">
                                    <div class="prog-dot
                                        {{ $pm->status==='ditolak' && $i===1 ? 'rejected' : '' }}
                                        {{ $isDone && $pm->status!=='ditolak' ? ($i===$currentStep ? 'current' : 'done') : '' }}
                                        {{ $isDone && $i<$currentStep ? 'done' : '' }}">
                                    </div>
                                    <div class="prog-label">{{ $slabel }}</div>
                                </div>
                                @if($i < count($steps)-1)
                                    <div class="prog-line {{ $isDone && $steps[$i+1][1] ? 'done' : '' }}" style="flex:1"></div>
                                @endif
                            @endforeach
                        </div>

                        {{-- Catatan petugas --}}
                        @if($pm->catatan_petugas && in_array($pm->status,['disetujui','ditolak']))
                            <div class="catatan-strip">
                                💬 Petugas: "{{ $pm->catatan_petugas }}"
                                @if($pm->petugas) — {{ $pm->petugas->name }} @endif
                            </div>
                        @endif

                        {{-- Card footer --}}
                        <div class="pm-footer">
                            <div class="pm-footer-date">
                                📅 {{ $pm->tanggal_pinjam->format('d M Y') }}
                                → {{ $pm->tanggal_kembali_rencana->format('d M Y') }}
                                @if($pm->created_at)
                                    · Diajukan {{ $pm->created_at->diffForHumans() }}
                                @endif
                            </div>
                            <div class="pm-footer-actions">
                                @if($pm->status === 'menunggu')
                                    <button type="button" class="btn-sm btn-cancel"
                                            onclick="showDeleteModal('{{ $pm->id }}','{{ $pm->nomor_pinjam }}','{{ $pm->alat->nama ?? '' }}')">
                                        ✕ Batalkan
                                    </button>
                                @endif
                                @if($pm->status === 'dipinjam')
                                    <a href="/katalog" class="btn-sm" style="background:rgba(45,90,39,0.1);color:var(--green);border:1px solid rgba(45,90,39,0.2)">
                                        Sewa Lagi
                                    </a>
                                @endif
                                @if($pm->status === 'dikembalikan')
                                    <a href="{{ route('peminjam.peminjamans.create', ['alat'=>$pm->alat_id]) }}" class="btn-sm btn-katalog">
                                        Sewa Lagi →
                                    </a>
                                @endif
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($peminjamans->hasPages())
                <div class="pag">
                    <a href="{{ $peminjamans->previousPageUrl() }}" class="pag-link {{ $peminjamans->onFirstPage()?'disabled':'' }}">‹</a>
                    @foreach($peminjamans->getUrlRange(1,$peminjamans->lastPage()) as $pg=>$url)
                        <a href="{{ $url }}" class="pag-link {{ $pg==$peminjamans->currentPage()?'active':'' }}">{{ $pg }}</a>
                    @endforeach
                    <a href="{{ $peminjamans->nextPageUrl() }}" class="pag-link {{ !$peminjamans->hasMorePages()?'disabled':'' }}">›</a>
                </div>
            @endif
        @endif

    </div>

    {{-- Delete confirm modal --}}
    <div class="modal-overlay" id="deleteModal">
        <div class="modal-box">
            <div class="modal-title">Batalkan Pengajuan?</div>
            <p class="modal-text" id="deleteModalText">Pengajuan ini akan dibatalkan dan tidak bisa dikembalikan.</p>
            <div class="modal-actions">
                <form method="POST" id="deleteForm">
                    @csrf @method('DELETE')
                    <button type="submit" class="modal-confirm">Ya, Batalkan</button>
                </form>
                <button type="button" onclick="closeDeleteModal()" class="modal-cancel">Tidak</button>
            </div>
        </div>
    </div>

    <script>
        function showDeleteModal(id, nomor, nama) {
            document.getElementById('deleteModalText').textContent =
                `Yakin ingin membatalkan pengajuan ${nomor} (${nama})? Tindakan ini tidak dapat dibatalkan.`;
            document.getElementById('deleteForm').action = `/sewa/peminjamans/${id}`;
            document.getElementById('deleteModal').classList.add('open');
        }
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('open');
        }
        document.getElementById('deleteModal').addEventListener('click', e => {
            if (e.target === document.getElementById('deleteModal')) closeDeleteModal();
        });
        document.addEventListener('keydown', e => { if (e.key==='Escape') closeDeleteModal(); });
    </script>

</body>
</html>