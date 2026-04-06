<x-petugas-layout title="Dashboard" breadcrumb="Dashboard">

    <x-slot name="header">
        <div>
            <h1 class="page-heading">Dashboard Petugas</h1>
            <p class="page-sub">Selamat datang, {{ auth()->user()->name }} — pantau aktivitas hari ini.</p>
        </div>
        <div style="display:flex;gap:0.5rem">
            <a href="{{ route('petugas.peminjamans.index') }}" class="btn btn-primary">
                ✅ Lihat Antrian
            </a>
        </div>
    </x-slot>

    <style>
        :root {
            --green: #1A7A4A;
            --green-m: #22A05A;
            --green-l: #2DBE6C;
            --pale: #E8F8EE;
            --pale-b: rgba(34, 160, 90, 0.2);
            --danger: #DC2626;
            --danger-pale: #FEF2F2;
            --danger-b: #FECACA;
            --amber: #D97706;
            --amber-pale: #FFFBEB;
            --amber-b: #FDE68A;
            --blue: #2563EB;
            --blue-pale: #EFF6FF;
            --blue-b: #BFDBFE;
            --surface2: #F7FAF8;
            --border2: #F3F4F6;
        }

        /* ── Stats grid ──────────────────────────────── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.75rem;
        }

        .stat-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.25rem;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
            position: relative;
            overflow: hidden;
            transition: box-shadow 0.2s, transform 0.2s;
            text-decoration: none;
            display: block;
        }

        .stat-card:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            transform: translateY(-1px);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
        }

        .stat-card.green::before { background: var(--green-m); }
        .stat-card.amber::before { background: var(--amber); }
        .stat-card.red::before   { background: var(--danger); }
        .stat-card.blue::before  { background: var(--blue); }
        .stat-card.slate::before { background: #D1D5DB; }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            margin-bottom: 0.9rem;
        }

        .stat-icon.green { background: var(--pale); }
        .stat-icon.amber { background: var(--amber-pale); }
        .stat-icon.red   { background: var(--danger-pale); }
        .stat-icon.blue  { background: var(--blue-pale); }
        .stat-icon.slate { background: var(--surface2); }

        .stat-val {
            font-family: var(--font-display, 'DM Serif Display', serif);
            font-size: 2rem;
            font-weight: 400;
            color: var(--ink, #111827);
            line-height: 1;
        }

        .stat-val.danger { color: var(--danger); }
        .stat-val.warn   { color: var(--amber); }

        .stat-label {
            font-size: 0.75rem;
            color: var(--muted, #6B7280);
            margin-top: 0.3rem;
        }

        .stat-hint {
            font-size: 0.7rem;
            color: var(--muted, #6B7280);
            margin-top: 0.45rem;
            font-weight: 500;
        }

        .stat-hint.up     { color: var(--green-m); }
        .stat-hint.warn   { color: var(--amber); }
        .stat-hint.danger { color: var(--danger); }

        /* ── Two-column layout ───────────────────────── */
        .dash-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 1.5rem;
            align-items: start;
        }

        /* ── Section card ────────────────────────────── */
        .section-card {
            background: white;
            border: 1px solid var(--border, #E5E7EB);
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            margin-bottom: 1.25rem;
        }

        .section-card:last-child { margin-bottom: 0; }

        .section-header {
            padding: 0.9rem 1.25rem;
            border-bottom: 1px solid var(--border2, #F3F4F6);
            background: var(--surface2, #F7FAF8);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .section-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--ink, #111827);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-count {
            font-size: 0.65rem;
            font-weight: 700;
            background: var(--green-m);
            color: white;
            padding: 0.12rem 0.5rem;
            border-radius: 100px;
        }

        .section-count.urgent { background: var(--danger); }
        .section-count.warn   { background: var(--amber); }

        /* ── Queue rows ──────────────────────────────── */
        .queue-row {
            display: flex;
            align-items: center;
            gap: 0.9rem;
            padding: 0.9rem 1.25rem;
            border-bottom: 1px solid var(--border2, #F3F4F6);
            transition: background 0.12s;
        }

        .queue-row:last-child  { border-bottom: none; }
        .queue-row:hover       { background: var(--surface2, #F7FAF8); }

        .queue-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            flex-shrink: 0;
            background: var(--pale);
            border: 1.5px solid var(--pale-b);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--green);
        }

        .queue-name  { font-size: 0.83rem; font-weight: 600; color: var(--ink, #111827); }
        .queue-alat  { font-size: 0.72rem; color: var(--muted, #6B7280); margin-top: 0.1rem; }
        .queue-date  { font-size: 0.68rem; color: var(--muted, #6B7280); margin-top: 0.1rem; }

        .queue-actions {
            display: flex;
            gap: 0.4rem;
            margin-left: auto;
            flex-shrink: 0;
        }

        /* ── Terlambat rows ──────────────────────────── */
        .late-row {
            display: flex;
            align-items: center;
            gap: 0.9rem;
            padding: 0.9rem 1.25rem;
            border-bottom: 1px solid var(--border2, #F3F4F6);
            transition: background 0.12s;
        }

        .late-row:last-child { border-bottom: none; }
        .late-row:hover      { background: var(--danger-pale); }

        .late-days {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: var(--danger-pale);
            border: 1px solid var(--danger-b);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .late-days-num {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--danger);
            line-height: 1;
        }

        .late-days-lbl {
            font-size: 0.48rem;
            font-weight: 700;
            color: var(--danger);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* ── Side panel rows ─────────────────────────── */
        .side-stat-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1.25rem;
            border-bottom: 1px solid var(--border2, #F3F4F6);
            font-size: 0.82rem;
        }

        .side-stat-row:last-child { border-bottom: none; }

        .side-stat-label {
            color: var(--muted, #6B7280);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .side-stat-val         { font-weight: 700; color: var(--ink, #111827); }
        .side-stat-val.green   { color: var(--green-m); }
        .side-stat-val.red     { color: var(--danger); }
        .side-stat-val.amber   { color: var(--amber); }

        /* ── Quick actions ───────────────────────────── */
        .quick-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
            padding: 1rem;
        }

        .quick-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.35rem;
            padding: 0.8rem 0.5rem;
            background: var(--surface2, #F7FAF8);
            border: 1px solid var(--border, #E5E7EB);
            border-radius: 9px;
            text-decoration: none;
            transition: all 0.15s;
            text-align: center;
        }

        .quick-btn:hover { background: var(--pale); border-color: var(--pale-b); }
        .quick-btn-icon  { font-size: 1.25rem; }

        .quick-btn-label {
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--muted, #6B7280);
        }

        .quick-btn:hover .quick-btn-label { color: var(--green); }

        /* ── Empty state ─────────────────────────────── */
        .empty-row {
            padding: 2rem;
            text-align: center;
            color: var(--muted, #6B7280);
            font-size: 0.82rem;
        }

        .empty-icon { font-size: 1.75rem; margin-bottom: 0.5rem; }

        .inline-form { display: inline; }

        /* ── Return row ──────────────────────────────── */
        .return-row {
            display: flex;
            align-items: center;
            gap: 0.9rem;
            padding: 0.9rem 1.25rem;
            border-bottom: 1px solid var(--border2, #F3F4F6);
            transition: background 0.12s;
        }

        .return-row:last-child { border-bottom: none; }
        .return-row:hover      { background: var(--surface2, #F7FAF8); }

        .return-icon {
            width: 38px;
            height: 38px;
            border-radius: 9px;
            background: var(--blue-pale);
            border: 1px solid var(--blue-b);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            flex-shrink: 0;
        }

        /* ── Chart container ─────────────────────────── */
        .chart-wrap { padding: 1rem 1.25rem 1.25rem; position: relative; }

        .chart-canvas-wrap { position: relative; height: 180px; }

        .chart-legend {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 0.75rem;
        }

        .chart-legend-item {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--muted, #6B7280);
        }

        .chart-legend-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }

        /* Donut chart section */
        .donut-wrap {
            padding: 1rem 1.25rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .donut-canvas-wrap { position: relative; width: 110px; height: 110px; flex-shrink: 0; }

        .donut-center {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .donut-center-num {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--ink, #111827);
            line-height: 1;
        }

        .donut-center-lbl {
            font-size: 0.55rem;
            color: var(--muted, #6B7280);
            font-weight: 600;
            text-transform: uppercase;
        }

        .donut-items { flex: 1; display: flex; flex-direction: column; gap: 0.5rem; }

        .donut-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.75rem;
        }

        .donut-item-label { display: flex; align-items: center; gap: 0.4rem; color: var(--muted, #6B7280); }
        .donut-item-dot   { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
        .donut-item-val   { font-weight: 700; color: var(--ink, #111827); }

        @media(max-width:1024px) {
            .stats-grid  { grid-template-columns: repeat(2, 1fr); }
            .dash-grid   { grid-template-columns: 1fr; }
        }

        @media(max-width:600px) {
            .stats-grid { grid-template-columns: 1fr 1fr; }
        }
    </style>

    @php
        $countMenunggu      = $counts['menunggu'];
        $countDisetujui     = $counts['disetujui'];
        $countDipinjam      = $counts['dipinjam'];
        $countTerlambat     = $counts['terlambat'];
        $countKembaliHariIni = $counts['kembali_hari_ini'];
        $countKembaliMinggu = $counts['kembali_minggu'];
        $totalDendaMinggu   = $counts['denda_minggu'];

        /* Data untuk chart 7 hari terakhir */
        $chartLabels  = [];
        $chartPinjam  = [];
        $chartKembali = [];
        for ($i = 6; $i >= 0; $i--) {
            $date           = now()->subDays($i);
            $chartLabels[]  = $date->format('d M');
            $chartPinjam[]  = \App\Models\Peminjaman::whereDate('created_at', $date->toDateString())->count();
            $chartKembali[] = \App\Models\Pengembalian::whereDate('tanggal_kembali_aktual', $date->toDateString())->count();
        }
    @endphp

    {{-- ── Stats Row ────────────────────────────────── --}}
    <div class="stats-grid">
        <a href="{{ route('petugas.peminjamans.index') }}" class="stat-card {{ $countMenunggu > 0 ? 'red' : 'slate' }}">
            <div class="stat-icon {{ $countMenunggu > 0 ? 'red' : 'slate' }}">⏳</div>
            <div class="stat-val {{ $countMenunggu > 0 ? 'danger' : '' }}">{{ $countMenunggu }}</div>
            <div class="stat-label">Menunggu Persetujuan</div>
            <div class="stat-hint {{ $countMenunggu > 0 ? 'danger' : '' }}">
                {{ $countMenunggu > 0 ? 'Perlu tindakan segera' : 'Tidak ada antrian' }}
            </div>
        </a>

        <a href="{{ route('petugas.peminjamans.index', ['tab'=>'disetujui']) }}" class="stat-card blue">
            <div class="stat-icon blue">✅</div>
            <div class="stat-val">{{ $countDisetujui }}</div>
            <div class="stat-label">Disetujui, Belum Diambil</div>
            <div class="stat-hint">Menunggu serah terima alat</div>
        </a>

        <a href="{{ route('petugas.pengembalians.index') }}" class="stat-card green">
            <div class="stat-icon green">📦</div>
            <div class="stat-val">{{ $countDipinjam }}</div>
            <div class="stat-label">Sedang Dipinjam</div>
            <div class="stat-hint up">{{ $countKembaliHariIni }} dikembalikan hari ini</div>
        </a>

        <a href="{{ route('petugas.pengembalians.index', ['tab'=>'terlambat']) }}" class="stat-card {{ $countTerlambat > 0 ? 'amber' : 'slate' }}">
            <div class="stat-icon {{ $countTerlambat > 0 ? 'amber' : 'slate' }}">⚠️</div>
            <div class="stat-val {{ $countTerlambat > 0 ? 'warn' : '' }}">{{ $countTerlambat }}</div>
            <div class="stat-label">Terlambat Kembali</div>
            <div class="stat-hint {{ $countTerlambat > 0 ? 'warn' : '' }}">
                {{ $countTerlambat > 0 ? 'Perlu ditindaklanjuti' : 'Semua tepat waktu' }}
            </div>
        </a>
    </div>

    {{-- ── Main 2-col layout ──────────────────────────── --}}
    <div class="dash-grid">

        {{-- LEFT COLUMN --}}
        <div>

            {{-- Antrian menunggu persetujuan --}}
            <div class="section-card">
                <div class="section-header">
                    <span class="section-title">
                        ⏳ Antrian Persetujuan
                        @if($countMenunggu > 0)
                            <span class="section-count urgent">{{ $countMenunggu }}</span>
                        @endif
                    </span>
                    <a href="{{ route('petugas.peminjamans.index') }}" class="btn btn-sm btn-secondary">Lihat Semua →</a>
                </div>

                @if($pendingList->isEmpty())
                    <div class="empty-row">
                        <div class="empty-icon">🎉</div>
                        Tidak ada peminjaman yang menunggu persetujuan.
                    </div>
                @else
                    @foreach($pendingList as $p)
                        <div class="queue-row">
                            <div class="queue-avatar">{{ strtoupper(substr($p->peminjam->name ?? '?', 0, 2)) }}</div>
                            <div style="flex:1;min-width:0">
                                <div class="queue-name">{{ $p->peminjam->name }}</div>
                                <div class="queue-alat">🛠 {{ $p->alat->nama ?? '—' }} · {{ $p->nomor_pinjam }}</div>
                                <div class="queue-date">
                                    Diajukan {{ $p->created_at->diffForHumans() }}
                                    · Pinjam {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M') }}
                                    → {{ \Carbon\Carbon::parse($p->tanggal_kembali_rencana)->format('d M Y') }}
                                </div>
                            </div>
                            <div class="queue-actions">
                                <form method="POST" action="{{ route('petugas.peminjamans.setujui', $p) }}" class="inline-form">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success"
                                        onclick="return confirm('Setujui peminjaman {{ $p->nomor_pinjam }}?')">
                                        ✓ Setujui
                                    </button>
                                </form>
                                <a href="{{ route('petugas.peminjamans.show', $p) }}" class="btn btn-sm btn-danger">Detail</a>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            {{-- Sudah disetujui, belum diambil --}}
            @if($disetujuiList->isNotEmpty())
                <div class="section-card">
                    <div class="section-header">
                        <span class="section-title">
                            ✅ Menunggu Serah Terima
                            <span class="section-count">{{ $countDisetujui }}</span>
                        </span>
                        <a href="{{ route('petugas.peminjamans.index', ['tab'=>'disetujui']) }}" class="btn btn-sm btn-secondary">Lihat Semua →</a>
                    </div>
                    @foreach($disetujuiList as $p)
                        <div class="queue-row">
                            <div class="queue-avatar" style="background:var(--blue-pale);border-color:var(--blue-b);color:var(--blue)">
                                {{ strtoupper(substr($p->peminjam->name ?? '?', 0, 2)) }}
                            </div>
                            <div style="flex:1;min-width:0">
                                <div class="queue-name">{{ $p->peminjam->name }}</div>
                                <div class="queue-alat">🛠 {{ $p->alat->nama ?? '—' }} · {{ $p->nomor_pinjam }}</div>
                                <div class="queue-date">Ambil mulai {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}</div>
                            </div>
                            <div class="queue-actions">
                                <form method="POST" action="{{ route('petugas.peminjamans.tandai', $p) }}" class="inline-form">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-primary"
                                        onclick="return confirm('Tandai alat sudah diserahkan?')">
                                        📦 Diserahkan
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Terlambat kembali --}}
            <div class="section-card">
                <div class="section-header">
                    <span class="section-title">
                        ⚠️ Terlambat Kembali
                        @if($countTerlambat > 0)
                            <span class="section-count urgent">{{ $countTerlambat }}</span>
                        @endif
                    </span>
                    <a href="{{ route('petugas.pengembalians.index', ['tab'=>'terlambat']) }}" class="btn btn-sm btn-secondary">Lihat Semua →</a>
                </div>

                @if($terlambatList->isEmpty())
                    <div class="empty-row">
                        <div class="empty-icon">✅</div>
                        Semua peminjaman masih dalam batas waktu.
                    </div>
                @else
                    @foreach($terlambatList as $p)
                        @php
                            $batas        = \Carbon\Carbon::parse($p->tanggal_kembali_rencana)->startOfDay();
                            $hariTerlambat = (int) now()->startOfDay()->diffInDays($batas);
                        @endphp
                        <div class="late-row">
                            <div class="late-days">
                                <div class="late-days-num">{{ $hariTerlambat }}</div>
                                <div class="late-days-lbl">Hari</div>
                            </div>
                            <div style="flex:1;min-width:0">
                                <div class="queue-name">{{ $p->peminjam->name }}</div>
                                <div class="queue-alat">🛠 {{ $p->alat->nama ?? '—' }} · {{ $p->nomor_pinjam }}</div>
                                <div class="queue-date">
                                    Jatuh tempo: {{ $batas->format('d M Y') }}
                                    @if($p->alat && $p->alat->denda_per_hari > 0)
                                        · <span style="color:var(--danger);font-weight:600">
                                            Est. denda: Rp {{ number_format($hariTerlambat * $p->alat->denda_per_hari * $p->jumlah, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="queue-actions">
                                <a href="{{ route('petugas.pengembalians.aktif', $p) }}" class="btn btn-sm btn-danger">
                                    Proses ↩
                                </a>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            {{-- Pengembalian hari ini --}}
            <div class="section-card">
                <div class="section-header">
                    <span class="section-title">
                        ↩ Pengembalian Hari Ini
                        @if($countKembaliHariIni > 0)
                            <span class="section-count">{{ $countKembaliHariIni }}</span>
                        @endif
                    </span>
                    <a href="{{ route('petugas.pengembalians.index', ['tab'=>'selesai']) }}" class="btn btn-sm btn-secondary">Lihat Semua →</a>
                </div>

                @if($pengembalianHariIni->isEmpty())
                    <div class="empty-row">
                        <div class="empty-icon">📭</div>
                        Belum ada pengembalian hari ini.
                    </div>
                @else
                    @foreach($pengembalianHariIni as $pg)
                        <div class="return-row">
                            <div class="return-icon">↩</div>
                            <div style="flex:1;min-width:0">
                                <div class="queue-name">{{ $pg->peminjaman->peminjam->name ?? '—' }}</div>
                                <div class="queue-alat">🛠 {{ $pg->peminjaman->alat->nama ?? '—' }}</div>
                                <div class="queue-date">
                                    {{ $pg->created_at->format('H:i') }} · Kondisi:
                                    <strong>{{ ucfirst(str_replace('_',' ',$pg->kondisi_kembali)) }}</strong>
                                    @if($pg->total_tagihan > 0)
                                        · <span style="color:var(--danger)">Denda Rp {{ number_format($pg->total_tagihan,0,',','.') }}</span>
                                    @endif
                                </div>
                            </div>
                            <span class="badge {{ $pg->is_tepat_waktu ? 'badge-green' : 'badge-red' }}">
                                {{ $pg->is_tepat_waktu ? 'Tepat Waktu' : 'Terlambat' }}
                            </span>
                        </div>
                    @endforeach
                @endif
            </div>

        </div>

        {{-- RIGHT COLUMN --}}
        <div>

            {{-- ── GRAFIK: Aktivitas 7 Hari ─────────────── --}}
            <div class="section-card" style="margin-bottom:1rem">
                <div class="section-header">
                    <span class="section-title">📈 Aktivitas 7 Hari Terakhir</span>
                </div>
                <div class="chart-wrap">
                    <div class="chart-canvas-wrap">
                        <canvas id="chartAktivitas"></canvas>
                    </div>
                    <div class="chart-legend">
                        <div class="chart-legend-item">
                            <div class="chart-legend-dot" style="background:#22A05A"></div>
                            Peminjaman Baru
                        </div>
                        <div class="chart-legend-item">
                            <div class="chart-legend-dot" style="background:#2563EB"></div>
                            Pengembalian
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── GRAFIK: Donut Status Aktif ───────────── --}}
            <div class="section-card" style="margin-bottom:1rem">
                <div class="section-header">
                    <span class="section-title">🔵 Status Peminjaman Aktif</span>
                </div>
                <div class="donut-wrap">
                    <div class="donut-canvas-wrap">
                        <canvas id="chartDonut"></canvas>
                        <div class="donut-center">
                            <div class="donut-center-num">{{ $countMenunggu + $countDisetujui + $countDipinjam }}</div>
                            <div class="donut-center-lbl">Aktif</div>
                        </div>
                    </div>
                    <div class="donut-items">
                        <div class="donut-item">
                            <span class="donut-item-label">
                                <span class="donut-item-dot" style="background:#DC2626"></span>
                                Menunggu
                            </span>
                            <span class="donut-item-val">{{ $countMenunggu }}</span>
                        </div>
                        <div class="donut-item">
                            <span class="donut-item-label">
                                <span class="donut-item-dot" style="background:#2563EB"></span>
                                Disetujui
                            </span>
                            <span class="donut-item-val">{{ $countDisetujui }}</span>
                        </div>
                        <div class="donut-item">
                            <span class="donut-item-label">
                                <span class="donut-item-dot" style="background:#22A05A"></span>
                                Dipinjam
                            </span>
                            <span class="donut-item-val">{{ $countDipinjam }}</span>
                        </div>
                        @if($countTerlambat > 0)
                            <div class="donut-item">
                                <span class="donut-item-label">
                                    <span class="donut-item-dot" style="background:#D97706"></span>
                                    Terlambat
                                </span>
                                <span class="donut-item-val" style="color:var(--amber)">{{ $countTerlambat }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Ringkasan minggu ini --}}
            <div class="section-card" style="margin-bottom:1rem">
                <div class="section-header">
                    <span class="section-title">📊 Ringkasan Minggu Ini</span>
                </div>
                <div class="side-stat-row">
                    <span class="side-stat-label">📥 Pengembalian</span>
                    <span class="side-stat-val green">{{ $countKembaliMinggu }}</span>
                </div>
                <div class="side-stat-row">
                    <span class="side-stat-label">💰 Total Denda</span>
                    <span class="side-stat-val {{ $totalDendaMinggu > 0 ? 'red' : '' }}">
                        Rp {{ number_format($totalDendaMinggu, 0, ',', '.') }}
                    </span>
                </div>
                <div class="side-stat-row">
                    <span class="side-stat-label">📦 Masih Dipinjam</span>
                    <span class="side-stat-val">{{ $countDipinjam }}</span>
                </div>
                <div class="side-stat-row">
                    <span class="side-stat-label">⚠️ Terlambat</span>
                    <span class="side-stat-val {{ $countTerlambat > 0 ? 'amber' : '' }}">{{ $countTerlambat }}</span>
                </div>
                <div class="side-stat-row">
                    <span class="side-stat-label">🕐 Disetujui</span>
                    <span class="side-stat-val">{{ $countDisetujui }}</span>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="section-card" style="margin-bottom:1rem">
                <div class="section-header">
                    <span class="section-title">⚡ Aksi Cepat</span>
                </div>
                <div class="quick-actions">
                    <a href="{{ route('petugas.peminjamans.index') }}" class="quick-btn">
                        <span class="quick-btn-icon">⏳</span>
                        <span class="quick-btn-label">Antrian Persetujuan</span>
                    </a>
                    <a href="{{ route('petugas.pengembalians.index') }}" class="quick-btn">
                        <span class="quick-btn-icon">↩</span>
                        <span class="quick-btn-label">Proses Pengembalian</span>
                    </a>
                    <a href="{{ route('petugas.pengembalians.index', ['tab'=>'terlambat']) }}" class="quick-btn">
                        <span class="quick-btn-icon">⚠️</span>
                        <span class="quick-btn-label">Pantau Terlambat</span>
                    </a>
                    <a href="{{ route('petugas.laporan.index') }}" class="quick-btn">
                        <span class="quick-btn-icon">📊</span>
                        <span class="quick-btn-label">Cetak Laporan</span>
                    </a>
                    <a href="{{ route('petugas.laporan.peminjaman') }}" class="quick-btn">
                        <span class="quick-btn-icon">📋</span>
                        <span class="quick-btn-label">Laporan Peminjaman</span>
                    </a>
                    <a href="{{ route('petugas.laporan.rekap') }}" class="quick-btn">
                        <span class="quick-btn-icon">📅</span>
                        <span class="quick-btn-label">Rekap Bulanan</span>
                    </a>
                </div>
            </div>

            {{-- Info petugas --}}
            <div class="section-card">
                <div class="section-header">
                    <span class="section-title">👤 Sesi Petugas</span>
                </div>
                <div style="padding:1.1rem 1.25rem">
                    <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.85rem">
                        <div style="width:44px;height:44px;border-radius:50%;background:var(--pale);
                                    border:1.5px solid var(--pale-b);
                                    display:flex;align-items:center;justify-content:center;
                                    font-size:0.85rem;font-weight:700;color:var(--green);">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div>
                            <div style="font-size:0.88rem;font-weight:700;color:var(--ink,#111827)">
                                {{ auth()->user()->name }}
                            </div>
                            <div style="font-size:0.72rem;color:var(--green-m);font-weight:700;text-transform:uppercase;letter-spacing:0.05em">
                                Petugas
                            </div>
                        </div>
                    </div>
                    <div style="font-size:0.75rem;color:var(--muted,#6B7280);
                                background:var(--surface2,#F7FAF8);border:1px solid var(--border,#E5E7EB);
                                border-radius:8px;padding:0.55rem 0.75rem;">
                        🕐 Login sesi ini: {{ now()->format('d M Y, H:i') }} WIB
                    </div>
                </div>
            </div>

        </div>
    </div>

    @php
        $chartDonutData = [$countMenunggu, $countDisetujui, $countDipinjam, $countTerlambat];
    @endphp

    {{-- Chart.js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <script>
        // ── Data dari PHP ─────────────────────────────
        const labels  = @json($chartLabels);
        const pinjam  = @json($chartPinjam);
        const kembali = @json($chartKembali);

        // ── Donut data ────────────────────────────────
        const donutData  = @json($chartDonutData);
        const donutTotal = donutData.reduce((a, b) => a + b, 0);

        // ── Shared defaults ───────────────────────────
        Chart.defaults.font.family = "'Plus Jakarta Sans', 'Syne', system-ui, sans-serif";
        Chart.defaults.font.size   = 11;
        Chart.defaults.color       = '#6B7280';

        // ── Bar Chart: Aktivitas 7 Hari ───────────────
        const ctxBar = document.getElementById('chartAktivitas');
        if (ctxBar) {
            new Chart(ctxBar.getContext('2d'), {
                type: 'bar',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'Peminjaman Baru',
                            data: pinjam,
                            backgroundColor: 'rgba(34,160,90,0.85)',
                            borderRadius: 5,
                            borderSkipped: false,
                        },
                        {
                            label: 'Pengembalian',
                            data: kembali,
                            backgroundColor: 'rgba(37,99,235,0.75)',
                            borderRadius: 5,
                            borderSkipped: false,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#fff',
                            borderColor: '#E5E7EB',
                            borderWidth: 1,
                            titleColor: '#111827',
                            bodyColor: '#374151',
                            padding: 10,
                            boxPadding: 4,
                            callbacks: {
                                title: ctx => ctx[0].label,
                                label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.y}`
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            border: { display: false },
                            ticks: { font: { size: 10 } }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0, font: { size: 10 } },
                            grid: { color: '#F3F4F6' },
                            border: { display: false, dash: [4, 4] }
                        }
                    }
                }
            });
        }

        // ── Donut Chart: Status Aktif ─────────────────
        const ctxDonut = document.getElementById('chartDonut');
        if (ctxDonut) {
            new Chart(ctxDonut.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Menunggu', 'Disetujui', 'Dipinjam', 'Terlambat'],
                    datasets: [{
                        data: donutTotal > 0 ? donutData : [1, 0, 0, 0],
                        backgroundColor: ['#DC2626', '#2563EB', '#22A05A', '#D97706'],
                        borderWidth: 2,
                        borderColor: '#FFFFFF',
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#fff',
                            borderColor: '#E5E7EB',
                            borderWidth: 1,
                            titleColor: '#111827',
                            bodyColor: '#374151',
                            padding: 10,
                            callbacks: {
                                label: ctx => ` ${ctx.label}: ${donutTotal > 0 ? ctx.parsed : 0}`
                            }
                        }
                    }
                }
            });
        }
    </script>

</x-petugas-layout>