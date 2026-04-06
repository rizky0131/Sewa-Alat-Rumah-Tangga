<x-petugas-layout title="Pantau Pengembalian">

    <x-slot name="header">
        <div>
            <h1 class="page-heading">Pantau Pengembalian</h1>
            <p class="page-sub">Monitor alat yang sedang dipinjam dan catat pengembalian.</p>
        </div>
    </x-slot>

    <style>
        :root {
            --green:      #1A7A4A;
            --green-m:    #22A05A;
            --green-l:    #2DBE6C;
            --pale:       #E8F8EE;
            --pale-b:     rgba(34,160,90,0.2);
            --ring:       rgba(34,160,90,0.18);
            --danger:     #DC2626;
            --danger-pale:#FEF2F2;
            --danger-b:   #FECACA;
            --gold:       #D97706;
            --gold-pale:  #FFFBEB;
            --gold-b:     #FDE68A;
            --jade:       #16A34A;
            --surface:    #FFFFFF;
            --surface2:   #F7FAF8;
            --border:     #E5E7EB;
            --border2:    #F3F4F6;
            --text:       #111827;
            --sub:        #374151;
            --muted:      #6B7280;
        }

        /* ── Stats strip ─────────────────────────────── */
        .stat-strip {
            display:grid;grid-template-columns:repeat(4,1fr);gap:0.75rem;margin-bottom:1.4rem;
        }
        .stat-box {
            background:var(--surface);border:1px solid var(--border);border-radius:12px;
            padding:1rem 1.1rem;display:flex;align-items:center;gap:0.8rem;
            box-shadow:0 1px 4px rgba(0,0,0,0.04);transition:box-shadow 0.15s;
        }
        .stat-box:hover { box-shadow:0 4px 12px rgba(0,0,0,0.07); }
        .stat-icon { font-size:1.4rem;flex-shrink:0; }
        .stat-val  { font-size:1.5rem;font-weight:800;color:var(--text);line-height:1; }
        .stat-lbl  { font-size:0.67rem;color:var(--muted);margin-top:0.15rem;font-weight:500; }

        /* ── Tabs ────────────────────────────────────── */
        .tab-bar {
            display:flex;background:var(--surface);border:1px solid var(--border);
            border-radius:12px;overflow:hidden;margin-bottom:1.2rem;
            box-shadow:0 1px 4px rgba(0,0,0,0.04);
        }
        .tab-item {
            flex:1;padding:0.72rem 0.5rem;text-align:center;font-size:0.78rem;font-weight:600;
            text-decoration:none;color:var(--muted);transition:all 0.15s;
            border-right:1px solid var(--border);
        }
        .tab-item:last-child { border-right:none; }
        .tab-item.active { background:var(--green);color:white;font-weight:700; }
        .tab-item:not(.active):hover { background:var(--surface2);color:var(--text); }
        .tab-badge {
            display:inline-flex;align-items:center;justify-content:center;
            min-width:17px;height:17px;border-radius:100px;font-size:0.6rem;font-weight:700;
            padding:0 4px;margin-left:0.3rem;background:rgba(0,0,0,0.08);color:inherit;
        }
        .tab-item.active .tab-badge { background:rgba(255,255,255,0.3);color:white; }
        .tab-badge.hot { background:var(--danger);color:white;animation:pulse 1.3s infinite; }
        @keyframes pulse { 0%,100%{transform:scale(1)} 50%{transform:scale(1.2)} }

        /* ── Search ──────────────────────────────────── */
        .search-wrap {
            display:flex;align-items:center;gap:0.5rem;padding:0 1rem;
            background:var(--surface);border:1px solid var(--border);border-radius:10px;
            margin-bottom:1.1rem;transition:border-color 0.2s,box-shadow 0.2s;
            box-shadow:0 1px 3px rgba(0,0,0,0.04);
        }
        .search-wrap:focus-within { border-color:var(--green-m);box-shadow:0 0 0 3px var(--ring); }
        .search-wrap input {
            flex:1;background:none;border:none;outline:none;
            padding:0.75rem 0;font-size:0.84rem;color:var(--text);
            font-family:var(--font-ui,sans-serif);
        }
        .search-wrap input::placeholder { color:var(--muted); }

        /* ── Aktif cards grid ────────────────────────── */
        .aktif-grid {
            display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:1rem;
        }

        /* ── Aktif card ──────────────────────────────── */
        .aktif-card {
            background:var(--surface);border:1px solid var(--border);
            border-radius:12px;overflow:hidden;transition:border-color 0.15s,box-shadow 0.15s;
            display:flex;flex-direction:column;
            box-shadow:0 1px 4px rgba(0,0,0,0.04);
        }
        .aktif-card:hover { border-color:rgba(34,160,90,0.3);box-shadow:0 6px 20px rgba(0,0,0,0.08); }
        .aktif-card.is-late  { border-color:rgba(220,38,38,0.25);background:#FFFBFB; }
        .aktif-card.due-soon { border-color:rgba(217,119,6,0.25);background:#FFFDF5; }

        /* Card top stripe */
        .card-stripe { height:3px; }
        .stripe-ok   { background:linear-gradient(90deg,var(--green),var(--green-l)); }
        .stripe-late { background:var(--danger); }
        .stripe-soon { background:var(--gold); }

        /* Card head */
        .c-head {
            padding:0.85rem 1.1rem;border-bottom:1px solid var(--border2);
            display:flex;align-items:center;justify-content:space-between;gap:0.5rem;
            background:var(--surface2);
        }
        .c-nomor { font-family:monospace;font-size:0.8rem;font-weight:700;color:var(--green-m); }
        .c-date  { font-size:0.67rem;color:var(--muted);margin-top:1px; }

        /* Status pills */
        .spill {
            display:inline-flex;align-items:center;gap:0.25rem;padding:0.22rem 0.65rem;
            border-radius:100px;font-size:0.65rem;font-weight:700;letter-spacing:0.02em;
        }
        .spill::before { content:'';width:5px;height:5px;border-radius:50%;flex-shrink:0; }
        .sp-ok   { background:var(--pale);color:var(--green);border:1px solid var(--pale-b); }
        .sp-ok::before   { background:var(--green-l); }
        .sp-late { background:var(--danger-pale);color:#B91C1C;border:1px solid var(--danger-b); }
        .sp-late::before { background:var(--danger);animation:pulse 0.9s infinite; }
        .sp-soon { background:var(--gold-pale);color:var(--gold);border:1px solid var(--gold-b); }
        .sp-soon::before { background:var(--gold);animation:pulse 1.5s infinite; }

        /* Card body */
        .c-body { padding:0.9rem 1.1rem;flex:1; }

        /* Person row */
        .person-row { display:flex;align-items:center;gap:0.65rem;margin-bottom:0.8rem; }
        .person-av {
            width:34px;height:34px;border-radius:9px;flex-shrink:0;
            background:linear-gradient(135deg,var(--green),var(--green-l));
            display:flex;align-items:center;justify-content:center;
            font-size:0.7rem;font-weight:800;color:white;
        }
        .person-name  { font-size:0.85rem;font-weight:700;color:var(--text); }
        .person-email { font-size:0.67rem;color:var(--muted); }

        /* Alat mini */
        .alat-mini {
            display:flex;align-items:center;gap:0.6rem;padding:0.6rem 0.8rem;
            background:var(--pale);border:1px solid rgba(34,160,90,0.18);
            border-radius:8px;margin-bottom:0.8rem;
        }
        .alat-thumb {
            width:32px;height:32px;border-radius:7px;overflow:hidden;flex-shrink:0;
            background:rgba(34,160,90,0.1);display:flex;align-items:center;justify-content:center;font-size:0.85rem;
        }
        .alat-thumb img { width:100%;height:100%;object-fit:cover; }
        .alat-nm   { font-size:0.8rem;font-weight:700;color:var(--text); }
        .alat-meta { font-size:0.67rem;color:var(--muted); }

        /* Countdown bar */
        .countdown-wrap { margin-bottom:0.8rem; }
        .countdown-header { display:flex;align-items:center;justify-content:space-between;margin-bottom:0.4rem; }
        .countdown-label { font-size:0.7rem;color:var(--muted); }
        .countdown-days  { font-size:0.75rem;font-weight:800; }
        .progress-track  { height:5px;background:var(--border2);border-radius:100px;overflow:hidden; }
        .progress-fill   { height:100%;border-radius:100px;transition:width 0.3s; }

        /* Denda estimasi */
        .denda-chip {
            display:inline-flex;align-items:center;gap:0.3rem;padding:0.25rem 0.65rem;
            border-radius:7px;font-size:0.72rem;font-weight:700;
            background:var(--danger-pale);color:#B91C1C;border:1px solid var(--danger-b);
        }

        /* Card footer */
        .c-footer {
            border-top:1px solid var(--border2);padding:0.75rem 1.1rem;
            background:var(--surface2);display:flex;gap:0.5rem;
        }
        .btn-detail {
            flex:1;padding:0.55rem;border-radius:8px;font-size:0.78rem;font-weight:700;text-align:center;
            text-decoration:none;background:var(--surface);color:var(--muted);
            border:1px solid var(--border);transition:all 0.15s;
            display:flex;align-items:center;justify-content:center;
        }
        .btn-detail:hover { border-color:var(--green-m);color:var(--green);background:var(--pale); }
        .btn-catat {
            flex:1;padding:0.55rem;border-radius:8px;font-size:0.78rem;font-weight:700;text-align:center;
            text-decoration:none;background:var(--green);color:white;border:none;cursor:pointer;
            font-family:var(--font-ui,sans-serif);transition:all 0.15s;
            display:flex;align-items:center;justify-content:center;
        }
        .btn-catat:hover { background:#155F3A;transform:translateY(-1px);box-shadow:0 4px 12px rgba(26,122,74,0.25); }

        /* ── Selesai table ───────────────────────────── */
        .table-card {
            background:var(--surface);border:1px solid var(--border);
            border-radius:12px;overflow:hidden;
            box-shadow:0 1px 4px rgba(0,0,0,0.04);
        }
        .table-head {
            padding:0.85rem 1.2rem;border-bottom:1px solid var(--border2);
            display:flex;align-items:center;justify-content:space-between;
            background:var(--surface2);
        }
        .table-title { font-size:0.85rem;font-weight:700;color:var(--text); }

        .data-table { width:100%;border-collapse:collapse; }
        .data-table th {
            padding:0.65rem 1rem;text-align:left;font-size:0.67rem;font-weight:700;
            text-transform:uppercase;letter-spacing:0.08em;color:var(--muted);
            border-bottom:1px solid var(--border);white-space:nowrap;background:var(--surface2);
        }
        .data-table td {
            padding:0.78rem 1rem;border-bottom:1px solid var(--border2);vertical-align:middle;
            font-size:0.82rem;color:var(--sub);
        }
        .data-table tr:last-child td { border-bottom:none; }
        .data-table tr:hover td { background:var(--pale); }

        .kond-badge {
            display:inline-flex;align-items:center;padding:0.18rem 0.55rem;
            border-radius:100px;font-size:0.65rem;font-weight:700;
        }
        .kond-baik         { background:#DCFCE7;color:#15803D;border:1px solid #BBF7D0; }
        .kond-rusak_ringan { background:var(--gold-pale);color:var(--gold);border:1px solid var(--gold-b); }
        .kond-rusak_sedang { background:#FFF7ED;color:#C2410C;border:1px solid #FDBA74; }
        .kond-rusak_berat  { background:var(--danger-pale);color:var(--danger);border:1px solid var(--danger-b); }
        .kond-hilang       { background:#F5F3FF;color:#7C3AED;border:1px solid #DDD6FE; }

        .time-ok   { color:var(--jade);font-weight:700;font-size:0.75rem; }
        .time-late { color:var(--danger);font-weight:700;font-size:0.75rem; }
        .tagihan-ok  { color:var(--jade);font-weight:700; }
        .tagihan-has { color:var(--danger);font-weight:700; }

        /* Pagination */
        .pag-wrap {
            display:flex;align-items:center;justify-content:space-between;
            padding:0.85rem 1.2rem;border-top:1px solid var(--border2);
        }
        .pag-info { font-size:0.73rem;color:var(--muted); }
        .pag-links { display:flex;gap:0.25rem; }
        .pag-link {
            width:30px;height:30px;border-radius:6px;display:flex;align-items:center;justify-content:center;
            font-size:0.73rem;font-weight:700;text-decoration:none;
            background:var(--surface);border:1px solid var(--border);color:var(--muted);transition:all 0.15s;
        }
        .pag-link:hover,.pag-link.active { background:var(--green);color:white;border-color:var(--green); }
        .pag-link.disabled { opacity:0.3;pointer-events:none; }

        /* Empty */
        .empty-state {
            text-align:center;padding:3.5rem 1.5rem;color:var(--muted);
            background:var(--surface);border:1px solid var(--border);border-radius:12px;
            box-shadow:0 1px 4px rgba(0,0,0,0.04);
        }

        /* Alert banner */
        .alert-terlambat {
            display:flex;align-items:center;gap:0.5rem;margin-bottom:0.9rem;
            padding:0.7rem 1rem;background:var(--danger-pale);
            border:1px solid var(--danger-b);border-radius:9px;
            font-size:0.78rem;color:#B91C1C;font-weight:600;
        }

        /* Avatar mini in table */
        .av-mini {
            width:26px;height:26px;border-radius:6px;
            background:linear-gradient(135deg,var(--green),var(--green-l));
            display:flex;align-items:center;justify-content:center;
            font-size:0.6rem;font-weight:800;color:white;flex-shrink:0;
        }
        .alat-mini-img {
            width:26px;height:26px;border-radius:5px;overflow:hidden;
            background:var(--pale);display:flex;align-items:center;
            justify-content:center;font-size:0.7rem;flex-shrink:0;
        }
        .alat-mini-img img { width:100%;height:100%;object-fit:cover; }

        @media(max-width:900px) { .stat-strip { grid-template-columns:1fr 1fr; } .aktif-grid { grid-template-columns:1fr; } }
        @media(max-width:600px) { .stat-strip { grid-template-columns:1fr 1fr; } }
    </style>

    {{-- Stats --}}
    <div class="stat-strip">
        <div class="stat-box">
            <div class="stat-icon">📦</div>
            <div>
                <div class="stat-val" style="color:var(--green)">{{ $counts['aktif'] }}</div>
                <div class="stat-lbl">Sedang Dipinjam</div>
            </div>
        </div>
        <div class="stat-box" style="{{ $counts['terlambat'] > 0 ? 'border-color: var(--danger-b) !important;' : '' }}">
            <div class="stat-icon">⚠️</div>
            <div>
                <div class="stat-val" style="{{ $counts['terlambat'] > 0 ? 'color:var(--danger)' : '' }}">{{ $counts['terlambat'] }}</div>
                <div class="stat-lbl">Terlambat</div>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-icon">↩</div>
            <div>
                <div class="stat-val" style="color:var(--green-m)">{{ $counts['selesai'] }}</div>
                <div class="stat-lbl">Total Dikembalikan</div>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-icon">📅</div>
            <div>
                <div class="stat-val">{{ $counts['hari_ini'] }}</div>
                <div class="stat-lbl">Kembali Hari Ini</div>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="tab-bar">
        <a href="{{ route('petugas.pengembalians.index', ['tab'=>'aktif']) }}"
           class="tab-item {{ $tab==='aktif' ? 'active':'' }}">
            📦 Aktif Dipinjam
            @if($counts['aktif'] > 0)
                <span class="tab-badge">{{ $counts['aktif'] }}</span>
            @endif
        </a>
        <a href="{{ route('petugas.pengembalians.index', ['tab'=>'terlambat']) }}"
           class="tab-item {{ $tab==='terlambat' ? 'active':'' }}">
            ⚠ Terlambat
            @if($counts['terlambat'] > 0)
                <span class="tab-badge {{ $tab!=='terlambat' ? 'hot':'' }}">{{ $counts['terlambat'] }}</span>
            @endif
        </a>
        <a href="{{ route('petugas.pengembalians.index', ['tab'=>'selesai']) }}"
           class="tab-item {{ $tab==='selesai' ? 'active':'' }}">
            ✓ Sudah Dikembalikan
            <span class="tab-badge">{{ $counts['selesai'] }}</span>
        </a>
    </div>

    {{-- Search --}}
    <form method="GET" action="{{ route('petugas.pengembalians.index') }}">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <div class="search-wrap">
            <span style="color:var(--muted)">⌕</span>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nomor pinjam, nama peminjam, atau alat...">
            @if(request('search'))
                <a href="{{ route('petugas.pengembalians.index', ['tab'=>$tab]) }}"
                   style="color:var(--muted);text-decoration:none;font-size:0.85rem;padding:0.2rem 0.4rem;border-radius:4px;transition:background 0.15s"
                   onmouseover="this.style.background='var(--border2)'" onmouseout="this.style.background='none'">✕</a>
            @endif
        </div>
    </form>

    {{-- ─── TAB: Aktif / Terlambat ─────────────────── --}}
    @if(in_array($tab, ['aktif','terlambat']))

        @if($aktif->isEmpty())
            <div class="empty-state">
                <div style="font-size:2.5rem;margin-bottom:0.6rem">{{ $tab==='terlambat' ? '🎉' : '📦' }}</div>
                <div style="font-size:0.95rem;font-weight:700;color:var(--text);margin-bottom:0.3rem">
                    {{ $tab==='terlambat' ? 'Tidak ada peminjaman terlambat' : 'Tidak ada peminjaman aktif' }}
                </div>
                <div style="font-size:0.8rem">{{ $tab==='terlambat' ? 'Semua alat dikembalikan tepat waktu.' : 'Tidak ada alat yang sedang dipinjam.' }}</div>
            </div>
        @else
            @if($tab==='terlambat')
                <div class="alert-terlambat">
                    ⚠ <span><strong>{{ $counts['terlambat'] }} peminjaman</strong> melebihi batas waktu pengembalian. Segera hubungi peminjam.</span>
                </div>
            @endif

            <div class="aktif-grid">
                @foreach($aktif as $pm)
                    @php
                        $isLate   = $pm->is_terlambat;
                        $daysLeft = now()->diffInDays($pm->tanggal_kembali_rencana, false);
                        $isSoon   = !$isLate && $daysLeft <= 2;
                        $totalDays = $pm->durasi_hari;
                        $daysUsed  = $pm->tanggal_pinjam->diffInDays(now());
                        $pct = $totalDays > 0 ? min(100, round(($daysUsed / $totalDays) * 100)) : 100;
                    @endphp

                    <div class="aktif-card {{ $isLate ? 'is-late' : ($isSoon ? 'due-soon':'') }}">
                        <div class="card-stripe {{ $isLate ? 'stripe-late' : ($isSoon ? 'stripe-soon' : 'stripe-ok') }}"></div>

                        {{-- Head --}}
                        <div class="c-head">
                            <div>
                                <div class="c-nomor">{{ $pm->nomor_pinjam }}</div>
                                <div class="c-date">Pinjam: {{ $pm->tanggal_pinjam->format('d M Y') }}</div>
                            </div>
                            @if($isLate)
                                <span class="spill sp-late">⚠ Terlambat {{ $pm->keterlambatan_hari }}h</span>
                            @elseif($isSoon)
                                <span class="spill sp-soon">⏳ Segera</span>
                            @else
                                <span class="spill sp-ok">✓ On Track</span>
                            @endif
                        </div>

                        {{-- Body --}}
                        <div class="c-body">
                            <div class="person-row">
                                <div class="person-av">{{ strtoupper(substr($pm->peminjam->name??'?',0,2)) }}</div>
                                <div>
                                    <div class="person-name">{{ $pm->peminjam->name }}</div>
                                    <div class="person-email">{{ $pm->peminjam->email }}</div>
                                </div>
                            </div>

                            <div class="alat-mini">
                                <div class="alat-thumb">
                                    @if($pm->alat?->foto)
                                        <img src="{{ asset('storage/'.$pm->alat->foto) }}" alt="">
                                    @else 🔧 @endif
                                </div>
                                <div style="flex:1;min-width:0">
                                    <div class="alat-nm">{{ $pm->alat->nama ?? '—' }}</div>
                                    <div class="alat-meta">{{ $pm->alat->kode ?? '' }} · {{ $pm->jumlah }} unit</div>
                                </div>
                            </div>

                            <div class="countdown-wrap">
                                <div class="countdown-header">
                                    <span class="countdown-label">
                                        Batas kembali: <strong style="color:{{ $isLate ? 'var(--danger)' : 'var(--text)' }}">{{ $pm->tanggal_kembali_rencana->format('d M Y') }}</strong>
                                    </span>
                                    <span class="countdown-days" style="color:{{ $isLate ? 'var(--danger)' : ($isSoon ? 'var(--gold)' : 'var(--green-m)') }}">
                                        @if($isLate)
                                            +{{ $pm->keterlambatan_hari }} hari
                                        @elseif($daysLeft == 0)
                                            Hari ini
                                        @else
                                            {{ $daysLeft }} hari lagi
                                        @endif
                                    </span>
                                </div>
                                <div class="progress-track">
                                    <div class="progress-fill"
                                         style="width:{{ $pct }}%;background:{{ $isLate ? 'var(--danger)' : ($pct > 80 ? 'var(--gold)' : 'var(--green-m)') }}">
                                    </div>
                                </div>
                            </div>

                            @if($isLate)
                                <span class="denda-chip">
                                    💸 Est. denda: Rp {{ number_format($pm->keterlambatan_hari * ($pm->alat->denda_per_hari??0) * $pm->jumlah, 0, ',', '.') }}
                                </span>
                            @endif
                        </div>

                        {{-- Footer --}}
                        <div class="c-footer">
                            <a href="{{ route('petugas.pengembalians.aktif', $pm) }}" class="btn-detail">Detail →</a>
                            <a href="{{ route('petugas.pengembalians.aktif', $pm) }}#form-kembali" class="btn-catat">↩ Catat Kembali</a>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($aktif instanceof \Illuminate\Pagination\LengthAwarePaginator && $aktif->hasPages())
                <div style="display:flex;justify-content:center;gap:0.3rem;margin-top:1.2rem" class="pag-links">
                    <a href="{{ $aktif->previousPageUrl() }}" class="pag-link {{ $aktif->onFirstPage() ? 'disabled':'' }}">‹</a>
                    @foreach($aktif->getUrlRange(1,$aktif->lastPage()) as $pg => $url)
                        <a href="{{ $url }}" class="pag-link {{ $pg==$aktif->currentPage() ? 'active':'' }}">{{ $pg }}</a>
                    @endforeach
                    <a href="{{ $aktif->nextPageUrl() }}" class="pag-link {{ !$aktif->hasMorePages() ? 'disabled':'' }}">›</a>
                </div>
            @endif
        @endif

    {{-- ─── TAB: Selesai ───────────────────────────── --}}
    @else
        <div class="table-card">
            <div class="table-head">
                <span class="table-title">Riwayat Pengembalian</span>
                <span style="font-size:0.73rem;color:var(--muted)">{{ $selesai instanceof \Illuminate\Pagination\LengthAwarePaginator ? $selesai->total() : $selesai->count() }} data</span>
            </div>
            @if($selesai->isEmpty())
                <div class="empty-state" style="border:none;border-radius:0;box-shadow:none">
                    <div style="font-size:2rem;margin-bottom:0.5rem">↩</div>
                    Belum ada data pengembalian.
                </div>
            @else
                <div style="overflow-x:auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No. Pinjam</th>
                                <th>Peminjam</th>
                                <th>Alat</th>
                                <th>Tgl Kembali</th>
                                <th>Keterlambatan</th>
                                <th>Kondisi</th>
                                <th>Total Tagihan</th>
                                <th style="text-align:right">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($selesai as $pg)
                                <tr>
                                    <td>
                                        <div style="font-family:monospace;font-size:0.75rem;font-weight:700;color:var(--green-m)">
                                            {{ $pg->peminjaman->nomor_pinjam ?? '—' }}
                                        </div>
                                        <div style="font-size:0.65rem;color:var(--muted)">{{ $pg->created_at->format('d M Y') }}</div>
                                    </td>
                                    <td>
                                        <div style="display:flex;align-items:center;gap:0.5rem">
                                            <div class="av-mini">{{ strtoupper(substr($pg->peminjaman->peminjam->name??'?',0,2)) }}</div>
                                            <span style="font-size:0.8rem;font-weight:600;color:var(--text)">{{ $pg->peminjaman->peminjam->name ?? '—' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="display:flex;align-items:center;gap:0.5rem">
                                            <div class="alat-mini-img">
                                                @if($pg->peminjaman->alat?->foto)
                                                    <img src="{{ asset('storage/'.$pg->peminjaman->alat->foto) }}" alt="">
                                                @else 🔧 @endif
                                            </div>
                                            <div>
                                                <div style="font-size:0.8rem;font-weight:600;color:var(--text)">{{ $pg->peminjaman->alat->nama ?? '—' }}</div>
                                                <div style="font-size:0.65rem;color:var(--muted)">{{ $pg->peminjaman->jumlah }}x</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-size:0.8rem;font-weight:600;color:var(--text)">{{ $pg->tanggal_kembali_aktual->format('d M Y') }}</div>
                                        <div style="font-size:0.65rem;color:var(--muted)">Rencana: {{ $pg->peminjaman->tanggal_kembali_rencana->format('d M Y') }}</div>
                                    </td>
                                    <td>
                                        @if($pg->is_tepat_waktu)
                                            <span class="time-ok">✓ Tepat</span>
                                        @else
                                            <span class="time-late">⏰ +{{ $pg->keterlambatan_hari }}h</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php $kLabels = ['baik'=>'Baik','rusak_ringan'=>'Rusak Ringan','rusak_sedang'=>'Rusak Sedang','rusak_berat'=>'Rusak Berat','hilang'=>'Hilang']; @endphp
                                        <span class="kond-badge kond-{{ $pg->kondisi_kembali }}">{{ $kLabels[$pg->kondisi_kembali] ?? $pg->kondisi_kembali }}</span>
                                    </td>
                                    <td>
                                        @if($pg->total_tagihan > 0)
                                            <span class="tagihan-has">Rp {{ number_format($pg->total_tagihan,0,',','.') }}</span>
                                        @else
                                            <span class="tagihan-ok">Rp 0</span>
                                        @endif
                                    </td>
                                    <td style="text-align:right">
                                        <a href="{{ route('petugas.pengembalians.show', $pg) }}"
                                           class="btn-detail" style="display:inline-flex;padding:0.32rem 0.75rem;font-size:0.73rem;flex:unset">
                                            Detail →
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($selesai instanceof \Illuminate\Pagination\LengthAwarePaginator && $selesai->hasPages())
                    <div class="pag-wrap">
                        <span class="pag-info">{{ $selesai->firstItem() }}–{{ $selesai->lastItem() }} dari {{ $selesai->total() }}</span>
                        <div class="pag-links">
                            <a href="{{ $selesai->previousPageUrl() }}" class="pag-link {{ $selesai->onFirstPage() ? 'disabled':'' }}">‹</a>
                            @foreach($selesai->getUrlRange(1,$selesai->lastPage()) as $pg => $url)
                                <a href="{{ $url }}" class="pag-link {{ $pg==$selesai->currentPage() ? 'active':'' }}">{{ $pg }}</a>
                            @endforeach
                            <a href="{{ $selesai->nextPageUrl() }}" class="pag-link {{ !$selesai->hasMorePages() ? 'disabled':'' }}">›</a>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    @endif

</x-petugas-layout>