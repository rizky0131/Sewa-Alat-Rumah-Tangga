<x-petugas-layout title="Detail Peminjaman">

    <x-slot name="header">
        <div>
            <div style="display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap">
                <h1 class="page-heading" style="font-size:1.3rem">
                    {{ $peminjaman->nomor_pinjam }}
                </h1>
                @if($peminjaman->is_terlambat)
                <span class="sp sp-terlambat">⚠ Terlambat {{ $peminjaman->keterlambatan_hari }} hari</span>
                @else
                <span class="sp sp-{{ $peminjaman->status }}">{{ ucfirst($peminjaman->status) }}</span>
                @endif
            </div>
            <p class="page-sub">Diajukan {{ $peminjaman->created_at->format('d M Y, H:i') }} · {{ $peminjaman->created_at->diffForHumans() }}</p>
        </div>
        <a href="{{ route('petugas.peminjamans.index') }}" class="btn-back">← Kembali</a>
    </x-slot>

    <style>
        :root {
            --green: #1A7A4A;
            --green-m: #22A05A;
            --green-l: #2DBE6C;
            --pale: #E8F8EE;
            --pale-b: rgba(34, 160, 90, 0.15);
            --ring: rgba(34, 160, 90, 0.18);
            --danger: #DC2626;
            --danger-pale: #FEF2F2;
            --danger-b: #FECACA;
            --blue: #2563EB;
            --blue-pale: #EFF6FF;
            --blue-b: #BFDBFE;
            --surface: #FFFFFF;
            --surface2: #F7FAF8;
            --border: #E5E7EB;
            --border2: #F3F4F6;
            --text: #111827;
            --sub: #374151;
            --muted: #6B7280;
            --jade: #16A34A;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.55rem 1rem;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 700;
            text-decoration: none;
            background: var(--surface);
            border: 1px solid var(--border);
            color: var(--muted);
            transition: all 0.15s;
            white-space: nowrap;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        .btn-back:hover {
            border-color: var(--green-m);
            color: var(--green);
            background: var(--pale);
        }

        /* Status pills */
        .sp {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.25rem 0.75rem;
            border-radius: 100px;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .sp::before {
            content: '';
            width: 5px;
            height: 5px;
            border-radius: 50%;
        }

        .sp-menunggu {
            background: #FEF9E7;
            color: #92400E;
            border: 1px solid #FDE68A;
        }

        .sp-menunggu::before {
            background: #F59E0B;
            animation: dot-pulse 1.4s infinite;
        }

        .sp-disetujui {
            background: var(--blue-pale);
            color: var(--blue);
            border: 1px solid var(--blue-b);
        }

        .sp-disetujui::before {
            background: var(--blue);
        }

        .sp-dipinjam {
            background: var(--pale);
            color: var(--green);
            border: 1px solid rgba(34, 160, 90, 0.25);
        }

        .sp-dipinjam::before {
            background: var(--green-l);
        }

        .sp-ditolak {
            background: var(--danger-pale);
            color: var(--danger);
            border: 1px solid var(--danger-b);
        }

        .sp-ditolak::before {
            background: var(--danger);
        }

        .sp-dikembalikan {
            background: #F1F5F9;
            color: #475569;
            border: 1px solid #CBD5E1;
        }

        .sp-dikembalikan::before {
            background: #94A3B8;
        }

        .sp-terlambat {
            background: var(--danger-pale);
            color: #B91C1C;
            border: 1px solid var(--danger-b);
        }

        .sp-terlambat::before {
            background: var(--danger);
            animation: dot-pulse 0.9s infinite;
        }

        @keyframes dot-pulse {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: 0.3
            }
        }

        /* Layout */
        .show-layout {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 1.4rem;
            align-items: start;
        }

        /* Generic section card */
        .s-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 1.1rem;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
        }

        .s-head {
            padding: 0.9rem 1.2rem;
            border-bottom: 1px solid var(--border2);
            display: flex;
            align-items: center;
            gap: 0.6rem;
            background: var(--surface2);
        }

        .s-icon {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            background: var(--pale);
            color: var(--green-m);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .s-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text);
        }

        .s-body {
            padding: 1.1rem 1.2rem;
        }

        /* Info rows */
        .info-row {
            display: flex;
            align-items: flex-start;
            padding: 0.65rem 0;
            border-bottom: 1px solid var(--border2);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-key {
            font-size: 0.73rem;
            font-weight: 600;
            color: var(--muted);
            width: 42%;
            flex-shrink: 0;
            padding-top: 0.05rem;
        }

        .info-val {
            font-size: 0.82rem;
            color: var(--sub);
            flex: 1;
            line-height: 1.4;
        }

        /* Peminjam card */
        .user-hero {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.1rem 1.2rem;
        }

        .user-av {
            width: 52px;
            height: 52px;
            border-radius: 13px;
            flex-shrink: 0;
            background: linear-gradient(135deg, var(--green), var(--green-l));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: 800;
            color: white;
        }

        .user-name {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text);
        }

        .user-email {
            font-size: 0.72rem;
            color: var(--muted);
            margin-top: 0.1rem;
        }

        /* Alat block */
        .alat-hero {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1.1rem 1.2rem;
            border-bottom: 1px solid var(--border2);
        }

        .alat-img {
            width: 72px;
            height: 72px;
            border-radius: 10px;
            overflow: hidden;
            flex-shrink: 0;
            background: var(--pale);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .alat-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .alat-name {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 0.2rem;
        }

        .alat-kode {
            font-family: monospace;
            font-size: 0.72rem;
            color: var(--green-m);
        }

        .alat-meta {
            font-size: 0.72rem;
            color: var(--muted);
            margin-top: 0.15rem;
        }

        /* Kondisi badge */
        .kondisi-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.22rem 0.65rem;
            border-radius: 100px;
            font-size: 0.68rem;
            font-weight: 700;
        }

        .kb-baik {
            background: #DCFCE7;
            color: #15803D;
            border: 1px solid #BBF7D0;
        }

        .kb-rusak {
            background: #FEF9E7;
            color: #92400E;
            border: 1px solid #FDE68A;
        }

        .kb-perbaikan {
            background: var(--danger-pale);
            color: var(--danger);
            border: 1px solid var(--danger-b);
        }

        /* Date timeline */
        .date-timeline {
            display: flex;
            align-items: center;
            gap: 0;
            padding: 1.2rem 1.2rem;
        }

        .dt-node {
            text-align: center;
            min-width: 90px;
        }

        .dt-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin: 0 auto 0.4rem;
        }

        .dt-dot-done {
            background: var(--green-m);
        }

        .dt-dot-active {
            background: #F59E0B;
            animation: dot-pulse 1.2s infinite;
        }

        .dt-dot-future {
            background: #CBD5E1;
        }

        .dt-dot-late {
            background: var(--danger);
            animation: dot-pulse 0.9s infinite;
        }

        .dt-date {
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--text);
        }

        .dt-label {
            font-size: 0.62rem;
            color: var(--muted);
            margin-top: 0.1rem;
        }

        .dt-line {
            flex: 1;
            height: 2px;
            background: var(--border);
            position: relative;
            margin: 0 0.4rem;
        }

        .dt-line-done {
            background: var(--green-m);
        }

        .dt-line-active {
            background: linear-gradient(to right, var(--green-m), var(--border));
        }

        /* Biaya breakdown */
        .biaya-table {
            width: 100%;
        }

        .bt-row {
            display: flex;
            justify-content: space-between;
            padding: 0.55rem 0;
            border-bottom: 1px solid var(--border2);
            font-size: 0.8rem;
        }

        .bt-row:last-child {
            border-bottom: none;
            font-size: 0.95rem;
            font-weight: 800;
            padding-top: 0.75rem;
        }

        .bt-key {
            color: var(--muted);
        }

        .bt-val {
            color: var(--sub);
            font-weight: 700;
        }

        .bt-row:last-child .bt-key {
            color: var(--text);
        }

        .bt-row:last-child .bt-val {
            color: var(--green);
        }

        /* Status timeline */
        .status-timeline {
            padding: 1.1rem 1.2rem;
        }

        .stl-item {
            display: flex;
            gap: 0.9rem;
            position: relative;
        }

        .stl-item:not(:last-child) .stl-line {
            position: absolute;
            left: 11px;
            top: 24px;
            width: 2px;
            height: calc(100% + 4px);
            background: var(--border2);
            z-index: 0;
        }

        .stl-circle {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            border: 2px solid var(--border);
            background: var(--surface);
            z-index: 1;
            position: relative;
            color: var(--muted);
        }

        .stl-circle.done {
            background: var(--green-m);
            border-color: var(--green-m);
            color: white;
        }

        .stl-circle.current {
            background: #F59E0B;
            border-color: #F59E0B;
            color: white;
            animation: dot-pulse 1.2s infinite;
        }

        .stl-circle.rejected {
            background: var(--danger);
            border-color: var(--danger);
            color: white;
        }

        .stl-content {
            padding-bottom: 1rem;
            flex: 1;
        }

        .stl-label {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--text);
        }

        .stl-time {
            font-size: 0.68rem;
            color: var(--muted);
            margin-top: 0.1rem;
        }

        .stl-note {
            font-size: 0.73rem;
            color: var(--muted);
            margin-top: 0.3rem;
            font-style: italic;
        }

        /* ─── SIDEBAR ACTION PANELS ───────────────────── */
        .action-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 1rem;
            position: sticky;
            top: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .ac-head {
            padding: 1rem 1.2rem;
            border-bottom: 1px solid var(--border2);
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--surface2);
        }

        .ac-body {
            padding: 1.2rem;
        }

        /* Approve form */
        .btn-approve-full {
            width: 100%;
            padding: 0.75rem;
            border-radius: 9px;
            font-size: 0.88rem;
            font-weight: 700;
            text-align: center;
            background: var(--green);
            color: white;
            border: none;
            cursor: pointer;
            font-family: var(--font-ui, sans-serif);
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-approve-full:hover {
            background: #155F3A;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(26, 122, 74, 0.28);
        }

        .catatan-input {
            width: 100%;
            box-sizing: border-box;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 0.65rem 0.85rem;
            color: var(--text);
            font-family: var(--font-ui, sans-serif);
            font-size: 0.8rem;
            outline: none;
            resize: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            margin-bottom: 0.85rem;
        }

        .catatan-input:focus {
            border-color: var(--green-m);
            box-shadow: 0 0 0 3px var(--ring);
        }

        /* Tolak panel */
        .tolak-toggle {
            width: 100%;
            margin-top: 0.6rem;
            padding: 0.62rem;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 700;
            background: var(--surface2);
            color: var(--muted);
            border: 1px solid var(--border);
            cursor: pointer;
            font-family: var(--font-ui, sans-serif);
            transition: all 0.15s;
        }

        .tolak-toggle:hover {
            border-color: var(--danger-b);
            color: var(--danger);
            background: var(--danger-pale);
        }

        .tolak-panel {
            display: none;
            margin-top: 0.75rem;
            padding: 1rem;
            background: var(--danger-pale);
            border: 1px solid var(--danger-b);
            border-radius: 9px;
        }

        .tolak-panel.open {
            display: block;
        }

        .tolak-label {
            font-size: 0.72rem;
            font-weight: 700;
            color: #B91C1C;
            margin-bottom: 0.5rem;
        }

        .tolak-textarea {
            width: 100%;
            box-sizing: border-box;
            background: white;
            border: 1px solid var(--danger-b);
            border-radius: 7px;
            padding: 0.6rem 0.75rem;
            color: var(--text);
            font-size: 0.8rem;
            font-family: var(--font-ui, sans-serif);
            outline: none;
            resize: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .tolak-textarea:focus {
            border-color: #F87171;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        .btn-tolak-confirm {
            width: 100%;
            margin-top: 0.5rem;
            padding: 0.62rem;
            border-radius: 7px;
            font-size: 0.8rem;
            font-weight: 700;
            background: var(--danger);
            color: white;
            border: none;
            cursor: pointer;
            font-family: var(--font-ui, sans-serif);
            transition: background 0.15s;
        }

        .btn-tolak-confirm:hover {
            background: #B91C1C;
        }

        /* Serahkan button */
        .btn-serahkan {
            width: 100%;
            padding: 0.75rem;
            border-radius: 9px;
            font-size: 0.85rem;
            font-weight: 700;
            text-align: center;
            background: var(--blue-pale);
            color: var(--blue);
            border: 1.5px solid var(--blue-b);
            cursor: pointer;
            font-family: var(--font-ui, sans-serif);
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-serahkan:hover {
            background: #DBEAFE;
            border-color: #93C5FD;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.12);
        }

        /* Status chips (read-only states) */
        .status-chip {
            width: 100%;
            padding: 0.75rem;
            border-radius: 9px;
            font-size: 0.82rem;
            font-weight: 700;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .chip-dipinjam {
            background: var(--pale);
            color: var(--green);
            border: 1.5px solid rgba(34, 160, 90, 0.25);
        }

        .chip-ditolak {
            background: var(--danger-pale);
            color: var(--danger);
            border: 1.5px solid var(--danger-b);
        }

        .chip-selesai {
            background: #F1F5F9;
            color: #475569;
            border: 1.5px solid #CBD5E1;
        }

        .chip-late {
            background: var(--danger-pale);
            color: #B91C1C;
            border: 1.5px solid var(--danger-b);
        }

        /* Pengembalian box */
        .pg-box {
            background: var(--pale);
            border: 1px solid rgba(34, 160, 90, 0.2);
            border-radius: 9px;
            padding: 0.9rem;
            margin-bottom: 0.75rem;
        }

        .pg-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.78rem;
            padding: 0.3rem 0;
        }

        .pg-key {
            color: var(--muted);
        }

        .pg-val {
            font-weight: 700;
            color: var(--text);
        }

        /* Alert box */
        .late-alert {
            background: var(--danger-pale);
            border: 1px solid var(--danger-b);
            border-radius: 9px;
            padding: 0.85rem 1rem;
            margin-bottom: 0.75rem;
            font-size: 0.78rem;
            color: #B91C1C;
            line-height: 1.5;
        }

        .late-alert strong {
            font-weight: 800;
            color: var(--danger);
        }

        /* Tujuan blockquote */
        .tujuan-block {
            background: var(--pale);
            border-left: 3px solid var(--green-m);
            border-radius: 0 8px 8px 0;
            padding: 0.75rem 1rem;
            font-size: 0.82rem;
            color: var(--muted);
            line-height: 1.6;
            font-style: italic;
        }

        /* Catatan petugas */
        .catatan-box {
            background: var(--danger-pale);
            border: 1px solid var(--danger-b);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.8rem;
            color: #B91C1C;
            line-height: 1.5;
            font-style: italic;
        }

        /* Stok check box */
        .stok-check {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 0.75rem;
            margin-bottom: 0.9rem;
        }

        .stok-check-title {
            font-size: 0.67rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--muted);
            margin-bottom: 0.5rem;
        }

        .stok-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem;
        }

        .stok-row+.stok-row {
            margin-top: 0.3rem;
        }

        /* Approve info box (disetujui state) */
        .info-box-blue {
            background: var(--blue-pale);
            border: 1px solid var(--blue-b);
            border-radius: 8px;
            padding: 0.75rem;
            margin-bottom: 1rem;
            font-size: 0.75rem;
            color: var(--blue);
            line-height: 1.5;
        }

        /* Proses pengembalian link */
        .btn-kembali-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.7rem;
            border-radius: 9px;
            font-size: 0.82rem;
            font-weight: 700;
            text-decoration: none;
            background: var(--pale);
            color: var(--green);
            border: 1.5px solid rgba(34, 160, 90, 0.3);
            transition: all 0.2s;
        }

        .btn-kembali-link:hover {
            background: rgba(34, 160, 90, 0.15);
            border-color: var(--green-m);
            transform: translateY(-1px);
        }

        /* Detail pengembalian link */
        .btn-detail-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            padding: 0.62rem;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 700;
            text-decoration: none;
            background: var(--surface2);
            color: var(--muted);
            border: 1px solid var(--border);
            transition: all 0.15s;
        }

        .btn-detail-link:hover {
            border-color: var(--green-m);
            color: var(--green);
            background: var(--pale);
        }

        @media(max-width:960px) {
            .show-layout {
                grid-template-columns: 1fr;
            }

            .action-card {
                position: static;
            }
        }
    </style>

    <div class="show-layout">

        {{-- ─── LEFT: Detail Info ─────────────────────── --}}
        <div>

            {{-- Peminjam --}}
            <div class="s-card">
                <div class="s-head">
                    <div class="s-icon">👤</div>
                    <span class="s-title">Peminjam</span>
                </div>
                <div class="user-hero">
                    <div class="user-av">{{ strtoupper(substr($peminjaman->peminjam->name??'?',0,2)) }}</div>
                    <div style="flex:1">
                        <div class="user-name">{{ $peminjaman->peminjam->name ?? '—' }}</div>
                        <div class="user-email">{{ $peminjaman->peminjam->email ?? '' }}</div>
                    </div>
                    <span class="kondisi-badge kb-baik">Peminjam</span>
                </div>
                <div style="padding:0 1.2rem 1rem">
                    <div class="info-row">
                        <span class="info-key">No. Transaksi</span>
                        <span class="info-val" style="font-family:monospace;color:var(--green-m);font-weight:700">{{ $peminjaman->nomor_pinjam }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Tanggal Pengajuan</span>
                        <span class="info-val">{{ $peminjaman->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    @if($peminjaman->tujuan_peminjaman)
                    <div class="info-row">
                        <span class="info-key">Tujuan Peminjaman</span>
                        <span class="info-val" style="font-size:0"> </span>
                    </div>
                    @endif
                </div>
                @if($peminjaman->tujuan_peminjaman)
                <div style="padding:0 1.2rem 1.2rem">
                    <div class="tujuan-block">"{{ $peminjaman->tujuan_peminjaman }}"</div>
                </div>
                @endif
            </div>

            {{-- Alat yang dipinjam --}}
            <div class="s-card">
                <div class="s-head">
                    <div class="s-icon">🔧</div>
                    <span class="s-title">Alat yang Dipinjam</span>
                </div>
                <div class="alat-hero">
                    <div class="alat-img">
                        @if($peminjaman->alat?->foto)
                        <img src="{{ asset('storage/'.$peminjaman->alat->foto) }}" alt="">
                        @else 🔧 @endif
                    </div>
                    <div style="flex:1;min-width:0">
                        <div class="alat-name">{{ $peminjaman->alat->nama ?? '—' }}</div>
                        <div class="alat-kode">{{ $peminjaman->alat->kode ?? '' }}</div>
                        <div class="alat-meta">Kategori: {{ $peminjaman->alat->kategori->nama ?? '—' }}</div>
                        <div style="display:flex;gap:0.5rem;margin-top:0.5rem;flex-wrap:wrap">
                            @php $k = $peminjaman->alat?->kondisi ?? ''; @endphp
                            <span class="kondisi-badge {{ in_array($k,['baik']) ? 'kb-baik' : (in_array($k,['rusak_ringan','rusak_berat']) ? 'kb-rusak' : 'kb-perbaikan') }}">
                                {{ str_replace('_',' ',ucfirst($k)) }}
                            </span>
                            <span class="kondisi-badge" style="background:var(--surface2);color:var(--muted);border:1px solid var(--border)">
                                Stok tersedia: {{ $peminjaman->alat->stok_tersedia ?? '—' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div style="padding:0 1.2rem 1.2rem">
                    <div class="info-row">
                        <span class="info-key">Jumlah Dipinjam</span>
                        <span class="info-val"><strong style="color:var(--green);font-size:1rem">{{ $peminjaman->jumlah }}</strong> unit</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Harga Sewa / Hari</span>
                        <span class="info-val">Rp {{ number_format($peminjaman->alat->harga_sewa_per_hari??0,0,',','.') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Denda / Hari (telat)</span>
                        <span class="info-val" style="color:var(--danger);font-weight:600">Rp {{ number_format($peminjaman->alat->denda_per_hari??0,0,',','.') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Merk</span>
                        <span class="info-val">{{ $peminjaman->alat->merk ?? '—' }}</span>
                    </div>
                </div>
            </div>

            {{-- Periode & Biaya --}}
            <div class="s-card">
                <div class="s-head">
                    <div class="s-icon">📅</div>
                    <span class="s-title">Periode & Biaya</span>
                </div>

                @php
                $now = now();
                $start = $peminjaman->tanggal_pinjam;
                $end = $peminjaman->tanggal_kembali_rencana;
                $isLate = $peminjaman->is_terlambat;
                $isDone = in_array($peminjaman->status, ['dikembalikan']);
                @endphp

                <div class="date-timeline">
                    <div class="dt-node">
                        <div class="dt-dot dt-dot-done"></div>
                        <div class="dt-date">{{ $start->format('d M') }}</div>
                        <div class="dt-label">Mulai Pinjam</div>
                    </div>
                    <div class="dt-line {{ $isLate && !$isDone ? 'dt-line-active' : ($isDone ? 'dt-line-done' : '') }}"></div>
                    <div class="dt-node">
                        @if($isDone)
                        <div class="dt-dot dt-dot-done"></div>
                        @elseif($isLate)
                        <div class="dt-dot dt-dot-late"></div>
                        @elseif($peminjaman->status === 'dipinjam')
                        <div class="dt-dot dt-dot-active"></div>
                        @else
                        <div class="dt-dot dt-dot-future"></div>
                        @endif
                        <div class="dt-date" style="{{ $isLate && !$isDone ? 'color:var(--danger)' : '' }}">
                            {{ $end->format('d M') }}
                        </div>
                        <div class="dt-label">{{ $isDone ? 'Dikembalikan' : 'Batas Kembali' }}</div>
                    </div>
                    @if($peminjaman->pengembalian)
                    <div class="dt-line dt-line-done"></div>
                    <div class="dt-node">
                        <div class="dt-dot dt-dot-done"></div>
                        <div class="dt-date">{{ $peminjaman->pengembalian->tanggal_kembali_aktual->format('d M') }}</div>
                        <div class="dt-label">Aktual Kembali</div>
                    </div>
                    @endif
                </div>

                <div style="padding:0 1.2rem 1.2rem">
                    <div style="background:var(--pale);border:1px solid rgba(34,160,90,0.2);border-radius:9px;padding:0.9rem 1rem">
                        <div class="biaya-table">
                            <div class="bt-row">
                                <span class="bt-key">Harga sewa / hari</span>
                                <span class="bt-val">Rp {{ number_format($peminjaman->alat->harga_sewa_per_hari??0,0,',','.') }}</span>
                            </div>
                            <div class="bt-row">
                                <span class="bt-key">× Jumlah unit</span>
                                <span class="bt-val">{{ $peminjaman->jumlah }} unit</span>
                            </div>
                            <div class="bt-row">
                                <span class="bt-key">× Durasi</span>
                                <span class="bt-val">{{ $peminjaman->durasi_hari }} hari</span>
                            </div>
                            <div class="bt-row">
                                <span class="bt-key">Total Biaya Sewa</span>
                                <span class="bt-val">Rp {{ number_format($peminjaman->total_biaya,0,',','.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Status Timeline --}}
            <div class="s-card">
                <div class="s-head">
                    <div class="s-icon">🔄</div>
                    <span class="s-title">Riwayat Status</span>
                </div>
                <div class="status-timeline">
                    @php
                    $statusFlow = [
                    ['menunggu','⏳','Pengajuan Diterima','Peminjaman diajukan oleh anggota'],
                    ];
                    if(in_array($peminjaman->status, ['disetujui','dipinjam','dikembalikan'])) {
                    $statusFlow[] = ['disetujui','✓','Disetujui oleh '.($peminjaman->petugas?->name ?? '—'),
                    $peminjaman->disetujui_at?->format('d M Y, H:i')];
                    }
                    if(in_array($peminjaman->status, ['dipinjam','dikembalikan'])) {
                    $statusFlow[] = ['dipinjam','📦','Alat Diserahkan','Alat sudah diterima peminjam'];
                    }
                    if($peminjaman->status === 'dikembalikan') {
                    $statusFlow[] = ['dikembalikan','↩','Alat Dikembalikan',
                    $peminjaman->pengembalian?->tanggal_kembali_aktual?->format('d M Y')];
                    }
                    if($peminjaman->status === 'ditolak') {
                    $statusFlow[] = ['ditolak','✕','Ditolak oleh '.($peminjaman->petugas?->name ?? '—'),
                    $peminjaman->disetujui_at?->format('d M Y, H:i')];
                    }
                    @endphp

                    @foreach($statusFlow as $i => [$s,$icon,$label,$time])
                    <div class="stl-item">
                        <div class="stl-line"></div>
                        <div class="stl-circle {{ $s === $peminjaman->status ? ($s==='ditolak' ? 'rejected' : 'current') : 'done' }}">
                            {{ $icon }}
                        </div>
                        <div class="stl-content">
                            <div class="stl-label">{{ $label }}</div>
                            @if($time)
                            <div class="stl-time">{{ $time }}</div>
                            @endif
                            @if($s === 'ditolak' && $peminjaman->catatan_petugas)
                            <div class="stl-note">"{{ $peminjaman->catatan_petugas }}"</div>
                            @endif
                            @if($s === 'disetujui' && $peminjaman->catatan_petugas && $peminjaman->status !== 'ditolak')
                            <div class="stl-note">"{{ $peminjaman->catatan_petugas }}"</div>
                            @endif
                        </div>
                    </div>
                    @endforeach

                    {{-- Future steps --}}
                    @if($peminjaman->status === 'menunggu')
                    @foreach([['✓','Perlu disetujui petugas'],['📦','Alat diserahkan'],['↩','Alat dikembalikan']] as [$ic,$lb])
                    <div class="stl-item">
                        <div class="stl-line"></div>
                        <div class="stl-circle" style="opacity:0.3">{{ $ic }}</div>
                        <div class="stl-content">
                            <div class="stl-label" style="opacity:0.4;color:var(--muted)">{{ $lb }}</div>
                        </div>
                    </div>
                    @endforeach
                    @elseif($peminjaman->status === 'disetujui')
                    @foreach([['📦','Alat diserahkan ke peminjam'],['↩','Alat dikembalikan']] as [$ic,$lb])
                    <div class="stl-item">
                        <div class="stl-line"></div>
                        <div class="stl-circle" style="opacity:0.3">{{ $ic }}</div>
                        <div class="stl-content">
                            <div class="stl-label" style="opacity:0.4;color:var(--muted)">{{ $lb }}</div>
                        </div>
                    </div>
                    @endforeach
                    @elseif($peminjaman->status === 'dipinjam')
                    <div class="stl-item">
                        <div class="stl-circle" style="opacity:0.3">↩</div>
                        <div class="stl-content">
                            <div class="stl-label" style="opacity:0.4;color:var(--muted)">Menunggu pengembalian</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Pengembalian detail (if returned) --}}
            @if($peminjaman->pengembalian)
            <div class="s-card">
                <div class="s-head">
                    <div class="s-icon">↩</div>
                    <span class="s-title">Data Pengembalian</span>
                </div>
                <div style="padding:1.1rem 1.2rem">
                    <div class="pg-box">
                        <div class="pg-row">
                            <span class="pg-key">Tgl Kembali Aktual</span>
                            <span class="pg-val">{{ $peminjaman->pengembalian->tanggal_kembali_aktual->format('d M Y') }}</span>
                        </div>
                        <div class="pg-row">
                            <span class="pg-key">Keterlambatan</span>
                            <span class="pg-val" style="{{ $peminjaman->pengembalian->keterlambatan_hari > 0 ? 'color:var(--danger)' : 'color:var(--jade)' }}">
                                {{ $peminjaman->pengembalian->keterlambatan_hari > 0
                                        ? $peminjaman->pengembalian->keterlambatan_hari.' hari'
                                        : 'Tepat waktu ✓' }}
                            </span>
                        </div>
                        <div class="pg-row">
                            <span class="pg-key">Kondisi Kembali</span>
                            <span class="pg-val">{{ ucfirst(str_replace('_',' ',$peminjaman->pengembalian->kondisi_kembali)) }}</span>
                        </div>
                        <div class="pg-row">
                            <span class="pg-key">Total Tagihan</span>
                            <span class="pg-val" style="{{ $peminjaman->pengembalian->total_tagihan > 0 ? 'color:var(--danger)' : 'color:var(--jade)' }}">
                                Rp {{ number_format($peminjaman->pengembalian->total_tagihan,0,',','.') }}
                            </span>
                        </div>
                    </div>
                    @if($peminjaman->pengembalian->catatan)
                    <div class="tujuan-block">"{{ $peminjaman->pengembalian->catatan }}"</div>
                    @endif
                </div>
            </div>
            @endif

        </div>

        {{-- ─── RIGHT: Action Sidebar ─────────────────── --}}
        <div>

            {{-- Late alert --}}
            @if($peminjaman->is_terlambat)
            <div class="late-alert">
                <strong>⚠ Terlambat {{ $peminjaman->keterlambatan_hari }} hari!</strong><br>
                Batas kembali: {{ $peminjaman->tanggal_kembali_rencana->format('d M Y') }}<br>
                Estimasi denda: <strong>Rp {{ number_format($peminjaman->keterlambatan_hari * ($peminjaman->alat->denda_per_hari??0) * $peminjaman->jumlah,0,',','.') }}</strong>
            </div>
            @endif

            {{-- ACTION PANEL --}}
            <div class="action-card">

                @if($peminjaman->status === 'menunggu')
                <div class="ac-head">⚡ Tindakan Diperlukan</div>
                <div class="ac-body">
                    <div style="font-size:0.75rem;color:var(--muted);margin-bottom:0.9rem;line-height:1.5">
                        Pengajuan ini menunggu persetujuan Anda. Pastikan stok alat tersedia sebelum menyetujui.
                    </div>

                    <div class="stok-check">
                        <div class="stok-check-title">Cek Stok</div>
                        <div class="stok-row">
                            <span style="color:var(--muted)">Tersedia</span>
                            <span style="font-weight:700;color:{{ ($peminjaman->alat->stok_tersedia??0) >= $peminjaman->jumlah ? 'var(--jade)' : 'var(--danger)' }}">
                                {{ $peminjaman->alat->stok_tersedia ?? 0 }} unit
                            </span>
                        </div>
                        <div class="stok-row">
                            <span style="color:var(--muted)">Diminta</span>
                            <span style="font-weight:700;color:var(--text)">{{ $peminjaman->jumlah }} unit</span>
                        </div>
                        @if(($peminjaman->alat->stok_tersedia??0) < $peminjaman->jumlah)
                            <div style="margin-top:0.5rem;font-size:0.72rem;color:var(--danger);font-weight:700">⚠ Stok tidak mencukupi!</div>
                            @else
                            <div style="margin-top:0.5rem;font-size:0.72rem;color:var(--jade);font-weight:700">✓ Stok mencukupi</div>
                            @endif
                    </div>

                    <form method="POST" action="{{ route('petugas.peminjamans.setujui', $peminjaman) }}">
                        @csrf @method('PATCH')
                        <textarea name="catatan" class="catatan-input" rows="2"
                            placeholder="Catatan untuk peminjam (opsional)..."></textarea>
                        <button type="submit" class="btn-approve-full"
                            {{ ($peminjaman->alat->stok_tersedia??0) < $peminjaman->jumlah ? 'disabled style=opacity:0.5;cursor:not-allowed' : '' }}>
                            ✓ Setujui Peminjaman
                        </button>
                    </form>

                    <button type="button" class="tolak-toggle"
                        onclick="document.getElementById('tolakPanel').classList.toggle('open')">
                        ✕ Tolak Pengajuan
                    </button>

                    <div class="tolak-panel" id="tolakPanel">
                        <form method="POST" action="{{ route('petugas.peminjamans.tolak', $peminjaman) }}">
                            @csrf @method('PATCH')
                            <div class="tolak-label">✕ Alasan Penolakan</div>
                            <textarea name="catatan" class="tolak-textarea" rows="3"
                                placeholder="Jelaskan alasan penolakan dengan jelas..."
                                required></textarea>
                            <button type="submit" class="btn-tolak-confirm">Konfirmasi Tolak</button>
                        </form>
                    </div>
                </div>

                @elseif($peminjaman->status === 'disetujui')
                <div class="ac-head">📦 Serahkan Alat</div>
                <div class="ac-body">
                    <div style="font-size:0.75rem;color:var(--muted);margin-bottom:1rem;line-height:1.5">
                        Peminjaman sudah disetujui. Serahkan alat ke peminjam dan tandai di sistem.
                    </div>
                    <div class="info-box-blue">
                        ℹ Disetujui oleh <strong>{{ $peminjaman->petugas?->name ?? 'petugas' }}</strong><br>
                        {{ $peminjaman->disetujui_at?->format('d M Y, H:i') }}
                        @if($peminjaman->catatan_petugas)
                        <br><em>"{{ $peminjaman->catatan_petugas }}"</em>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('petugas.peminjamans.tandai', $peminjaman) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn-serahkan">
                            📦 Tandai Sudah Diserahkan
                        </button>
                    </form>
                </div>

                @elseif($peminjaman->status === 'dipinjam')
                <div class="ac-head">{{ $peminjaman->is_terlambat ? '⚠ Terlambat' : '📦 Sedang Dipinjam' }}</div>
                <div class="ac-body">
                    @if($peminjaman->is_terlambat)
                    <div class="status-chip chip-late" style="margin-bottom:0.85rem">
                        ⚠ Terlambat {{ $peminjaman->keterlambatan_hari }} hari
                    </div>
                    @else
                    <div class="status-chip chip-dipinjam" style="margin-bottom:0.85rem">
                        ✓ Sedang Dipinjam
                    </div>
                    @endif
                    <div style="font-size:0.75rem;color:var(--muted);margin-bottom:0.85rem;line-height:1.5">
                        @if($peminjaman->is_terlambat)
                        Alat melebihi batas pengembalian. Hubungi peminjam atau proses pengembalian.
                        @else
                        Alat sedang digunakan. Kembali {{ $peminjaman->tanggal_kembali_rencana->diffForHumans() }}.
                        @endif
                    </div>
                    <a href="{{ route('petugas.pengembalians.aktif', $peminjaman->id) }}#form-kembali"
                        class="btn-kembali-link">
                        ↩ Proses Pengembalian
                    </a>
                </div>

                @elseif($peminjaman->status === 'ditolak')
                <div class="ac-head">✕ Ditolak</div>
                <div class="ac-body">
                    <div class="status-chip chip-ditolak" style="margin-bottom:0.85rem">
                        ✕ Pengajuan Ditolak
                    </div>
                    @if($peminjaman->catatan_petugas)
                    <div style="font-size:0.72rem;color:var(--muted);margin-bottom:0.4rem">Alasan:</div>
                    <div class="catatan-box">"{{ $peminjaman->catatan_petugas }}"</div>
                    @endif
                    <div style="margin-top:0.75rem;font-size:0.72rem;color:var(--muted)">
                        Ditolak oleh {{ $peminjaman->petugas?->name ?? '—' }}
                        {{ $peminjaman->disetujui_at ? '· '.$peminjaman->disetujui_at->format('d M Y') : '' }}
                    </div>
                </div>

                @elseif($peminjaman->status === 'dikembalikan')
                <div class="ac-head">✓ Selesai</div>
                <div class="ac-body">
                    <div class="status-chip chip-selesai" style="margin-bottom:0.85rem">
                        ↩ Alat Sudah Dikembalikan
                    </div>
                    @if($peminjaman->pengembalian)
                    <a href="{{ route('petugas.pengembalians.show', $peminjaman->pengembalian) }}"
                        class="btn-detail-link">
                        Detail Pengembalian →
                    </a>
                    @endif
                </div>
                @endif

            </div>

            {{-- Quick info card --}}
            <div class="s-card">
                <div class="s-head">
                    <div class="s-icon" style="font-size:0.75rem">ℹ</div>
                    <span class="s-title">Ringkasan</span>
                </div>
                <div style="padding:0.5rem 1.2rem 1rem">
                    <div class="info-row">
                        <span class="info-key">Durasi</span>
                        <span class="info-val"><strong>{{ $peminjaman->durasi_hari }}</strong> hari</span>
                    </div>
                    <div class="info-row">
                        <span class="info-key">Total Biaya</span>
                        <span class="info-val" style="color:var(--green);font-weight:700">Rp {{ number_format($peminjaman->total_biaya,0,',','.') }}</span>
                    </div>
                    @if($peminjaman->petugas)
                    <div class="info-row">
                        <span class="info-key">Diproses oleh</span>
                        <span class="info-val">{{ $peminjaman->petugas->name }}</span>
                    </div>
                    @endif
                    <div class="info-row">
                        <span class="info-key">Status</span>
                        <span class="info-val">
                            @if($peminjaman->is_terlambat)
                            <span class="sp sp-terlambat" style="font-size:0.65rem">⚠ Terlambat</span>
                            @else
                            <span class="sp sp-{{ $peminjaman->status }}" style="font-size:0.65rem">{{ ucfirst($peminjaman->status) }}</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>

</x-petugas-layout>