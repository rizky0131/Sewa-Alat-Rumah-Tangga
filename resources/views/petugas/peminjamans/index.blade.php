<x-petugas-layout title="Setujui Peminjaman">

    <x-slot name="header">
        <div>
            <h1 class="page-heading">Setujui Peminjaman</h1>
            <p class="page-sub">Tinjau dan proses pengajuan peminjaman dari anggota.</p>
        </div>
    </x-slot>

    <style>
        /* ── Local overrides — bright green theme ─────── */
        :root {
            --teal:    #1A7A4A;
            --teal-d:  #155F3A;
            --teal-l:  #22A05A;
            --teal-m:  #2DBE6C;
            --pale:    #E8F8EE;
            --danger:  #DC2626;
            --surface: #FFFFFF;
            --surface2: #F7FAF8;
            --border:  #E5E7EB;
            --border2: #F3F4F6;
            --text:    #111827;
            --muted:   #6B7280;
            --ring:    rgba(34,160,90,0.18);
        }

        /* ── Tabs ─────────────────────────────────────── */
        .tab-bar {
            display: flex;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 1.4rem;
            box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        }

        .tab-item {
            flex: 1;
            padding: 0.78rem 0.5rem;
            text-align: center;
            font-size: 0.78rem;
            font-weight: 600;
            text-decoration: none;
            color: var(--muted);
            transition: all 0.15s;
            border-right: 1px solid var(--border);
            position: relative;
        }

        .tab-item:last-child { border-right: none; }

        .tab-item:hover {
            background: var(--surface2);
            color: var(--text);
        }

        .tab-item.active {
            background: var(--teal);
            color: white;
            font-weight: 700;
        }

        .tab-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 18px;
            height: 18px;
            border-radius: 100px;
            font-size: 0.6rem;
            font-weight: 700;
            padding: 0 5px;
            margin-left: 0.35rem;
            background: rgba(0,0,0,0.08);
            color: inherit;
        }

        .tab-item.active .tab-badge {
            background: rgba(255,255,255,0.3);
            color: white;
        }

        .tab-badge.urgent {
            background: var(--danger);
            color: white;
            animation: badge-pulse 1.5s infinite;
        }

        @keyframes badge-pulse {
            0%, 100% { transform: scale(1); }
            50%       { transform: scale(1.15); }
        }

        /* ── Search bar ───────────────────────────────── */
        .search-bar {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0 1rem;
            margin-bottom: 1.2rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        .search-bar:focus-within {
            border-color: var(--teal-l);
            box-shadow: 0 0 0 3px var(--ring);
        }

        .search-bar input {
            flex: 1;
            background: none;
            border: none;
            outline: none;
            padding: 0.75rem 0;
            color: var(--text);
            font-family: var(--font-ui, sans-serif);
            font-size: 0.85rem;
        }

        .search-bar input::placeholder { color: var(--muted); }

        /* ── Cards grid ───────────────────────────────── */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
            gap: 1rem;
        }

        /* ── Single approval card ─────────────────────── */
        .pm-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            transition: border-color 0.15s, box-shadow 0.15s;
            display: flex;
            flex-direction: column;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }

        .pm-card:hover {
            border-color: rgba(34,160,90,0.35);
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        }

        .pm-card.is-late {
            border-color: rgba(220,38,38,0.25);
            background: #FFFBFB;
        }

        .pm-card.is-approved {
            border-color: rgba(37,99,235,0.2);
            background: #FAFBFF;
        }

        /* Card header */
        .pm-card-head {
            padding: 0.9rem 1.1rem;
            border-bottom: 1px solid var(--border2);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.6rem;
            background: var(--surface2);
        }

        .pm-nomor {
            font-family: monospace;
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--teal);
        }

        .pm-date { font-size: 0.67rem; color: var(--muted); margin-top: 1px; }

        /* Status pill */
        .sp {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.22rem 0.7rem;
            border-radius: 100px;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .sp::before {
            content: '';
            width: 5px; height: 5px;
            border-radius: 50%;
        }

        .sp-menunggu {
            background: #FEF9E7;
            color: #92400E;
            border: 1px solid #FDE68A;
        }
        .sp-menunggu::before { background: #F59E0B; animation: dot-pulse 1.4s infinite; }

        .sp-disetujui {
            background: #EFF6FF;
            color: #1D4ED8;
            border: 1px solid #BFDBFE;
        }
        .sp-disetujui::before { background: #3B82F6; }

        .sp-dipinjam {
            background: var(--pale);
            color: var(--teal);
            border: 1px solid rgba(34,160,90,0.25);
        }
        .sp-dipinjam::before { background: var(--teal-l); }

        .sp-ditolak {
            background: #FEF2F2;
            color: var(--danger);
            border: 1px solid #FECACA;
        }
        .sp-ditolak::before { background: var(--danger); }

        .sp-terlambat {
            background: #FEF2F2;
            color: #B91C1C;
            border: 1px solid #FECACA;
        }
        .sp-terlambat::before { background: var(--danger); animation: dot-pulse 0.9s infinite; }

        @keyframes dot-pulse {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.3; }
        }

        /* Card body */
        .pm-card-body {
            padding: 1rem 1.1rem;
            flex: 1;
        }

        /* Peminjam row */
        .peminjam-row {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            margin-bottom: 0.85rem;
        }

        .peminjam-av {
            width: 36px; height: 36px;
            border-radius: 9px;
            flex-shrink: 0;
            background: linear-gradient(135deg, var(--teal), var(--teal-m));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 800;
            color: white;
        }

        .peminjam-name { font-size: 0.88rem; font-weight: 700; color: var(--text); }
        .peminjam-email { font-size: 0.68rem; color: var(--muted); }

        /* Alat row */
        .alat-row {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding: 0.7rem 0.85rem;
            border-radius: 8px;
            background: var(--pale);
            border: 1px solid rgba(34,160,90,0.2);
            margin-bottom: 0.85rem;
        }

        .alat-thumb {
            width: 36px; height: 36px;
            border-radius: 7px;
            overflow: hidden;
            flex-shrink: 0;
            background: rgba(34,160,90,0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
        }

        .alat-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .alat-name { font-size: 0.82rem; font-weight: 700; color: var(--text); }
        .alat-meta { font-size: 0.67rem; color: var(--muted); margin-top: 1px; }

        /* Date-range strip */
        .date-strip {
            display: flex;
            align-items: stretch;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid var(--border);
            margin-bottom: 0.85rem;
        }

        .ds-cell {
            flex: 1;
            padding: 0.55rem 0.8rem;
            text-align: center;
            background: var(--surface);
        }

        .ds-cell:first-child { border-right: 1px solid var(--border); }

        .ds-label {
            font-size: 0.58rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--muted);
            margin-bottom: 0.15rem;
        }

        .ds-date { font-size: 0.8rem; font-weight: 700; color: var(--text); }

        .ds-arrow {
            padding: 0 0.6rem;
            color: var(--muted);
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            background: var(--surface);
            border-right: 1px solid var(--border);
        }

        .ds-durasi {
            padding: 0.55rem 0.9rem;
            background: var(--pale);
            border-left: 1px solid rgba(34,160,90,0.2);
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .ds-durasi-val { font-size: 0.95rem; font-weight: 800; color: var(--teal); }
        .ds-durasi-lbl { font-size: 0.55rem; color: var(--muted); font-weight: 700; letter-spacing: 0.06em; }

        /* Biaya row */
        .biaya-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            padding: 0.55rem 0.75rem;
            background: var(--surface2);
            border-radius: 7px;
            border: 1px solid var(--border2);
        }

        .biaya-label { font-size: 0.72rem; color: var(--muted); font-weight: 500; }
        .biaya-val   { font-size: 1rem; font-weight: 800; color: var(--teal); }

        /* Tujuan */
        .tujuan-box {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 7px;
            padding: 0.6rem 0.75rem;
            font-size: 0.75rem;
            color: var(--muted);
            line-height: 1.55;
            margin-bottom: 0.85rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Late warning banner */
        .late-banner {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.55rem 0.85rem;
            border-radius: 7px;
            margin-bottom: 0.85rem;
            background: #FEF2F2;
            border: 1px solid #FECACA;
            font-size: 0.75rem;
            color: #B91C1C;
            font-weight: 600;
        }

        /* ── Action panel ─────────────────────────────── */
        .pm-card-actions {
            border-top: 1px solid var(--border2);
            padding: 0.85rem 1.1rem;
            background: var(--surface2);
        }

        .action-row { display: flex; gap: 0.5rem; }

        .btn-approve {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            padding: 0.62rem;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 700;
            background: var(--teal);
            color: white;
            border: none;
            cursor: pointer;
            font-family: var(--font-ui, sans-serif);
            transition: all 0.15s;
        }

        .btn-approve:hover {
            background: var(--teal-d);
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(26,122,74,0.28);
        }

        .btn-reject-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            padding: 0.62rem 0.9rem;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 700;
            background: white;
            color: var(--muted);
            border: 1px solid var(--border);
            cursor: pointer;
            font-family: var(--font-ui, sans-serif);
            transition: all 0.15s;
        }

        .btn-reject-toggle:hover {
            border-color: #FECACA;
            color: var(--danger);
            background: #FEF2F2;
        }

        /* Reject accordion */
        .reject-panel {
            display: none;
            margin-top: 0.75rem;
            padding: 0.85rem;
            background: #FEF2F2;
            border: 1px solid #FECACA;
            border-radius: 8px;
        }

        .reject-panel.open { display: block; }

        .reject-label {
            font-size: 0.72rem;
            font-weight: 700;
            color: #B91C1C;
            margin-bottom: 0.5rem;
        }

        .reject-textarea {
            width: 100%;
            box-sizing: border-box;
            background: white;
            border: 1px solid #FECACA;
            border-radius: 7px;
            padding: 0.6rem 0.75rem;
            color: var(--text);
            font-family: var(--font-ui, sans-serif);
            font-size: 0.8rem;
            outline: none;
            resize: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .reject-textarea:focus {
            border-color: #F87171;
            box-shadow: 0 0 0 3px rgba(220,38,38,0.1);
        }

        .reject-actions { display: flex; gap: 0.5rem; margin-top: 0.5rem; }

        .btn-reject-confirm {
            flex: 1;
            padding: 0.55rem;
            border-radius: 7px;
            font-size: 0.78rem;
            font-weight: 700;
            background: var(--danger);
            color: white;
            border: none;
            cursor: pointer;
            font-family: var(--font-ui, sans-serif);
            transition: background 0.15s;
        }

        .btn-reject-confirm:hover { background: #B91C1C; }

        .btn-cancel-reject {
            padding: 0.55rem 0.85rem;
            border-radius: 7px;
            font-size: 0.78rem;
            font-weight: 700;
            background: white;
            color: var(--muted);
            border: 1px solid var(--border);
            cursor: pointer;
            font-family: var(--font-ui, sans-serif);
            transition: background 0.15s;
        }

        .btn-cancel-reject:hover { background: var(--surface2); }

        /* Disetujui: serahkan alat CTA */
        .btn-serahkan {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.65rem;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 700;
            background: #EFF6FF;
            color: #1D4ED8;
            border: 1px solid #BFDBFE;
            cursor: pointer;
            font-family: var(--font-ui, sans-serif);
            transition: all 0.15s;
        }

        .btn-serahkan:hover {
            background: #DBEAFE;
            border-color: #93C5FD;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37,99,235,0.12);
        }

        /* Dipinjam chip */
        .dipinjam-chip {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.62rem;
            border-radius: 8px;
            font-size: 0.78rem;
            font-weight: 700;
            background: var(--pale);
            color: var(--teal);
            border: 1px solid rgba(34,160,90,0.2);
        }

        /* Ditolak chip */
        .ditolak-chip {
            padding: 0.62rem 0.75rem;
            border-radius: 8px;
            font-size: 0.75rem;
            background: #FEF2F2;
            color: #B91C1C;
            border: 1px solid #FECACA;
            line-height: 1.4;
        }

        .catatan-petugas {
            font-size: 0.72rem;
            color: #EF4444;
            font-style: italic;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-top: 0.25rem;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--muted);
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }

        /* Pagination */
        .pag-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 1.5rem;
            flex-wrap: wrap;
            gap: 0.6rem;
        }

        .pag-info { font-size: 0.75rem; color: var(--muted); }

        .pag-links { display: flex; gap: 0.3rem; }

        .pag-link {
            width: 32px; height: 32px;
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            text-decoration: none;
            background: white;
            border: 1px solid var(--border);
            color: var(--muted);
            transition: all 0.15s;
        }

        .pag-link:hover,
        .pag-link.active {
            background: var(--teal);
            color: white;
            border-color: var(--teal);
        }

        .pag-link.disabled { opacity: 0.35; pointer-events: none; }

        @media(max-width:768px) {
            .cards-grid { grid-template-columns: 1fr; }
            .tab-item { font-size: 0.68rem; padding: 0.65rem 0.3rem; }
        }
    </style>

    {{-- Tab bar --}}
    <div class="tab-bar">
        <a href="{{ route('petugas.peminjamans.index', ['tab'=>'menunggu']) }}"
            class="tab-item {{ $tab==='menunggu' ? 'active' : '' }}">
            ⏳ Menunggu
            @if($counts['menunggu'] > 0)
            <span class="tab-badge {{ $tab!=='menunggu' ? 'urgent':'' }}">{{ $counts['menunggu'] }}</span>
            @endif
        </a>
        <a href="{{ route('petugas.peminjamans.index', ['tab'=>'disetujui']) }}"
            class="tab-item {{ $tab==='disetujui' ? 'active' : '' }}">
            ✓ Disetujui
            @if($counts['disetujui'] > 0)
            <span class="tab-badge">{{ $counts['disetujui'] }}</span>
            @endif
        </a>
        <a href="{{ route('petugas.peminjamans.index', ['tab'=>'dipinjam']) }}"
            class="tab-item {{ $tab==='dipinjam' ? 'active' : '' }}">
            📦 Dipinjam
            @if($counts['terlambat'] > 0)
            <span class="tab-badge {{ $tab!=='dipinjam' ? 'urgent':'' }}">{{ $counts['terlambat'] }} terlambat</span>
            @endif
        </a>
        <a href="{{ route('petugas.peminjamans.index', ['tab'=>'semua']) }}"
            class="tab-item {{ $tab==='semua' ? 'active' : '' }}">
            📋 Semua
        </a>
    </div>

    {{-- Search --}}
    <form method="GET" action="{{ route('petugas.peminjamans.index') }}">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <div class="search-bar">
            <span style="color:var(--muted);font-size:1rem">⌕</span>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari nomor, nama peminjam, atau alat...">
            @if(request('search'))
            <a href="{{ route('petugas.peminjamans.index', ['tab'=>$tab]) }}"
                style="color:var(--muted);text-decoration:none;font-size:0.85rem;padding:0.2rem 0.4rem;border-radius:4px;transition:background 0.15s"
                onmouseover="this.style.background='#F3F4F6'" onmouseout="this.style.background='none'">✕</a>
            @endif
        </div>
    </form>

    {{-- Context headline --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.9rem">
        <div style="font-size:0.78rem;color:var(--muted)">
            @if($tab==='menunggu')
                @if($counts['menunggu'] > 0)
                    <span style="color:var(--teal);font-weight:700">{{ $counts['menunggu'] }} pengajuan</span> memerlukan tindakan Anda.
                @else
                    Semua pengajuan sudah diproses 🎉
                @endif
            @elseif($tab==='dipinjam' && $counts['terlambat'] > 0)
                <span style="color:var(--danger);font-weight:700">{{ $counts['terlambat'] }} peminjaman</span> melebihi batas waktu pengembalian.
            @else
                {{ $peminjamans->total() }} data ditemukan.
            @endif
        </div>
        <span style="font-size:0.72rem;color:var(--muted)">{{ $peminjamans->total() }} total</span>
    </div>

    {{-- Cards --}}
    @if($peminjamans->isEmpty())
    <div class="empty-state">
        <div style="font-size:3rem;margin-bottom:0.75rem">
            {{ $tab==='menunggu' ? '🎉' : '📋' }}
        </div>
        <div style="font-size:1rem;font-weight:700;color:var(--text);margin-bottom:0.4rem">
            {{ $tab==='menunggu' ? 'Tidak ada pengajuan menunggu' : 'Tidak ada data' }}
        </div>
        <div style="font-size:0.82rem">
            {{ $tab==='menunggu' ? 'Semua pengajuan peminjaman sudah diproses.' : 'Coba filter atau tab yang berbeda.' }}
        </div>
    </div>
    @else
    <div class="cards-grid">
        @foreach($peminjamans as $pm)
        @php $isLate = $pm->is_terlambat; @endphp
        <div class="pm-card {{ $isLate ? 'is-late' : ($pm->status==='disetujui' ? 'is-approved' : '') }}">

            {{-- Header --}}
            <div class="pm-card-head">
                <a href="{{ route('petugas.peminjamans.show', $pm) }}"
                    style="font-size:0.68rem;color:var(--teal-l);text-decoration:none;font-weight:600">Lihat Detail →</a>
                <div style="text-align:right">
                    <div class="pm-nomor">{{ $pm->nomor_pinjam }}</div>
                    <div class="pm-date">{{ $pm->created_at->format('d M Y, H:i') }}</div>
                </div>
                @if($isLate)
                <span class="sp sp-terlambat">⚠ Terlambat {{ $pm->keterlambatan_hari }}h</span>
                @else
                <span class="sp sp-{{ $pm->status }}">{{ ucfirst($pm->status) }}</span>
                @endif
            </div>

            {{-- Body --}}
            <div class="pm-card-body">

                {{-- Late banner --}}
                @if($isLate)
                <div class="late-banner">
                    <span>⚠</span>
                    Terlambat <strong>{{ $pm->keterlambatan_hari }} hari</strong> dari batas
                    {{ $pm->tanggal_kembali_rencana->format('d M Y') }}
                </div>
                @endif

                {{-- Peminjam --}}
                <div class="peminjam-row">
                    <div class="peminjam-av">{{ strtoupper(substr($pm->peminjam->name??'?',0,2)) }}</div>
                    <div>
                        <div class="peminjam-name">{{ $pm->peminjam->name ?? '—' }}</div>
                        <div class="peminjam-email">{{ $pm->peminjam->email ?? '' }}</div>
                    </div>
                </div>

                {{-- Alat --}}
                <div class="alat-row">
                    <div class="alat-thumb">
                        @if($pm->alat?->foto)
                        <img src="{{ asset('storage/'.$pm->alat->foto) }}" alt="">
                        @else 🔧 @endif
                    </div>
                    <div style="flex:1;min-width:0">
                        <div class="alat-name">{{ $pm->alat->nama ?? '—' }}</div>
                        <div class="alat-meta">
                            {{ $pm->alat->kode ?? '' }} · {{ $pm->jumlah }} unit ·
                            Rp {{ number_format($pm->alat->harga_sewa_per_hari,0,',','.') }}/hari
                        </div>
                    </div>
                </div>

                {{-- Date range --}}
                <div class="date-strip">
                    <div class="ds-cell">
                        <div class="ds-label">Pinjam</div>
                        <div class="ds-date">{{ $pm->tanggal_pinjam->format('d M Y') }}</div>
                    </div>
                    <div class="ds-arrow">→</div>
                    <div class="ds-cell">
                        <div class="ds-label">Kembali</div>
                        <div class="ds-date" style="{{ $isLate ? 'color:var(--danger)' : '' }}">
                            {{ $pm->tanggal_kembali_rencana->format('d M Y') }}
                        </div>
                    </div>
                    <div class="ds-durasi">
                        <div class="ds-durasi-val">{{ $pm->durasi_hari }}</div>
                        <div class="ds-durasi-lbl">HARI</div>
                    </div>
                </div>

                {{-- Biaya --}}
                <div class="biaya-row">
                    <span class="biaya-label">Estimasi Total Biaya</span>
                    <span class="biaya-val">Rp {{ number_format($pm->total_biaya,0,',','.') }}</span>
                </div>

                {{-- Tujuan --}}
                @if($pm->tujuan_peminjaman)
                <div class="tujuan-box">
                    💬 "{{ $pm->tujuan_peminjaman }}"
                </div>
                @endif

            </div>

            {{-- Actions --}}
            <div class="pm-card-actions">

                @if($pm->status === 'menunggu')
                <div class="action-row">
                    <form method="POST"
                        action="{{ route('petugas.peminjamans.setujui', $pm) }}"
                        style="flex:1">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn-approve">✓ Setujui</button>
                    </form>
                    <button type="button" class="btn-reject-toggle"
                        onclick="toggleReject({{ $pm->id }})">✕ Tolak</button>
                </div>

                <div class="reject-panel" id="rp-{{ $pm->id }}">
                    <form method="POST" action="{{ route('petugas.peminjamans.tolak', $pm) }}">
                        @csrf @method('PATCH')
                        <div class="reject-label">✕ Alasan Penolakan</div>
                        <textarea name="catatan" class="reject-textarea" rows="2"
                            placeholder="Jelaskan alasan penolakan..."></textarea>
                        <div class="reject-actions">
                            <button type="submit" class="btn-reject-confirm">Konfirmasi Tolak</button>
                            <button type="button" class="btn-cancel-reject"
                                onclick="toggleReject({{ $pm->id }})">Batal</button>
                        </div>
                    </form>
                </div>

                @elseif($pm->status === 'disetujui')
                <form method="POST" action="{{ route('petugas.peminjamans.tandai', $pm) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn-serahkan">
                        📦 Tandai Sudah Diserahkan ke Peminjam
                    </button>
                </form>

                @elseif($pm->status === 'dipinjam')
                <div class="dipinjam-chip">
                    ✓ Sedang Dipinjam
                    @if($isLate)
                    · <span style="color:var(--danger)">Terlambat {{ $pm->keterlambatan_hari }} hari</span>
                    @else
                    · Kembali {{ $pm->tanggal_kembali_rencana->diffForHumans() }}
                    @endif
                </div>

                @elseif($pm->status === 'ditolak')
                <div class="ditolak-chip">
                    <div style="font-weight:700;margin-bottom:0.2rem">✕ Ditolak</div>
                    @if($pm->catatan_petugas)
                    <div class="catatan-petugas">"{{ $pm->catatan_petugas }}"</div>
                    @endif
                </div>
                @endif

            </div>

        </div>
        @endforeach
    </div>
    @endif

    {{-- Pagination --}}
    @if($peminjamans->hasPages())
    <div class="pag-wrap">
        <span class="pag-info">{{ $peminjamans->firstItem() }}–{{ $peminjamans->lastItem() }} dari {{ $peminjamans->total() }}</span>
        <div class="pag-links">
            <a href="{{ $peminjamans->previousPageUrl() }}" class="pag-link {{ $peminjamans->onFirstPage() ? 'disabled':'' }}">‹</a>
            @foreach($peminjamans->getUrlRange(1,$peminjamans->lastPage()) as $pg => $url)
            <a href="{{ $url }}" class="pag-link {{ $pg==$peminjamans->currentPage() ? 'active':'' }}">{{ $pg }}</a>
            @endforeach
            <a href="{{ $peminjamans->nextPageUrl() }}" class="pag-link {{ !$peminjamans->hasMorePages() ? 'disabled':'' }}">›</a>
        </div>
    </div>
    @endif

    <script>
        function toggleReject(id) {
            const panel = document.getElementById('rp-' + id);
            panel.classList.toggle('open');
            if (panel.classList.contains('open')) {
                panel.querySelector('textarea').focus();
            }
        }

        document.querySelectorAll('.btn-reject-toggle').forEach(btn => {
            btn.addEventListener('click', function() {
                const thisId = this.getAttribute('onclick').match(/\d+/)[0];
                document.querySelectorAll('.reject-panel').forEach(panel => {
                    if (!panel.id.endsWith(thisId)) panel.classList.remove('open');
                });
            });
        });

        setTimeout(() => {
            document.querySelectorAll('[data-flash]').forEach(el => {
                el.style.transition = 'opacity 0.4s';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 400);
            });
        }, 4000);
    </script>

</x-petugas-layout>