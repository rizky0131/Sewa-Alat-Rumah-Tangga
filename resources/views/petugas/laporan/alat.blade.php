<x-petugas-layout title="Laporan Inventaris Alat">

    <x-slot name="header">
        <div>
            <h1 class="page-heading">Laporan Inventaris Alat</h1>
            <p class="page-sub">{{ \Carbon\Carbon::parse($dari)->format('d M Y') }} — {{ \Carbon\Carbon::parse($sampai)->format('d M Y') }}</p>
        </div>
        <div style="display:flex;gap:0.5rem">
            <button onclick="window.print()" class="btn-print">🖨 Cetak</button>
            <a href="{{ route('petugas.laporan.index') }}" class="btn-back">← Laporan</a>
        </div>
    </x-slot>

    <style>
        /* ── Screen Variables ─────────────────────────── */
        :root {
            --green:      #1A7A4A;
            --green-m:    #22A05A;
            --green-l:    #2DBE6C;
            --pale:       #E8F8EE;
            --pale-b:     rgba(34,160,90,0.2);
            --ring:       rgba(34,160,90,0.18);
            --danger:     #DC2626;
            --danger-pale:#FEF2F2;
            --gold:       #D97706;
            --gold-pale:  #FFFBEB;
            --blue:       #2563EB;
            --blue-pale:  #EFF6FF;
            --surface:    #FFFFFF;
            --surface2:   #F7FAF8;
            --border:     #E5E7EB;
            --border2:    #F3F4F6;
            --text:       #111827;
            --sub:        #374151;
            --muted:      #6B7280;
        }

        /* ── Screen UI ───────────────────────────────── */
        .btn-print {
            display:inline-flex;align-items:center;gap:0.4rem;
            padding:0.55rem 1.1rem;border-radius:8px;font-size:0.8rem;font-weight:700;
            cursor:pointer;border:none;background:var(--green);color:white;
            font-family:var(--font-ui,sans-serif);transition:all 0.15s;
        }
        .btn-print:hover { background:#155F3A;transform:translateY(-1px);box-shadow:0 4px 12px rgba(26,122,74,0.25); }

        .btn-back {
            display:inline-flex;align-items:center;padding:0.5rem 1rem;border-radius:8px;
            font-size:0.78rem;font-weight:700;text-decoration:none;
            background:var(--surface);border:1px solid var(--border);color:var(--muted);
            transition:all 0.15s;box-shadow:0 1px 3px rgba(0,0,0,0.06);
        }
        .btn-back:hover { border-color:var(--green-m);color:var(--green);background:var(--pale); }

        /* Filter bar */
        .filter-bar {
            background:var(--surface);border:1px solid var(--border);
            border-radius:12px;padding:1rem 1.2rem;margin-bottom:1.2rem;
            box-shadow:0 1px 4px rgba(0,0,0,0.04);
        }
        .filter-row { display:flex;gap:0.75rem;align-items:flex-end;flex-wrap:wrap; }
        .filter-group { display:flex;flex-direction:column;gap:0.3rem; }
        .filter-label { font-size:0.68rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.07em; }
        .filter-input {
            background:var(--surface2);border:1px solid var(--border);border-radius:7px;
            padding:0.55rem 0.8rem;color:var(--text);font-family:var(--font-ui,sans-serif);
            font-size:0.8rem;outline:none;min-width:130px;
            transition:border-color 0.2s,box-shadow 0.2s;
        }
        .filter-input:focus { border-color:var(--green-m);box-shadow:0 0 0 3px var(--ring); }
        .btn-filter {
            padding:0.55rem 1.1rem;border-radius:7px;font-size:0.8rem;font-weight:700;
            cursor:pointer;background:var(--green);color:white;border:none;
            font-family:var(--font-ui,sans-serif);transition:all 0.15s;
        }
        .btn-filter:hover { background:#155F3A; }

        /* ── Print document wrapper ───────────────────── */
        .print-doc {
            background:white;border-radius:12px;padding:2.5rem;color:#1a202c;
            box-shadow:0 2px 12px rgba(0,0,0,0.08);border:1px solid var(--border);
        }

        /* ── Doc header ──────────────────────────────── */
        .doc-header {
            display:flex;justify-content:space-between;align-items:flex-start;
            margin-bottom:1.75rem;padding-bottom:1.25rem;
            border-bottom:3px solid #1A7A4A;
        }
        .doc-brand { display:flex;flex-direction:column;gap:0.1rem; }
        .doc-logo {
            font-size:1.5rem;font-weight:900;color:#1A7A4A;
            letter-spacing:-0.02em;line-height:1;
        }
        .doc-tagline { font-size:0.62rem;color:#6B7280;letter-spacing:0.06em;text-transform:uppercase; }
        .doc-info { text-align:right; }
        .doc-title {
            font-size:0.88rem;font-weight:800;color:#111827;
            text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.3rem;
        }
        .doc-period { font-size:0.72rem;color:#374151;font-weight:600;margin-bottom:0.15rem; }
        .doc-generated { font-size:0.65rem;color:#6B7280; }

        /* ── Summary grid ────────────────────────────── */
        .sum-grid {
            display:grid;grid-template-columns:repeat(5,1fr);gap:0.6rem;margin-bottom:1.5rem;
        }
        .sum-box {
            padding:0.85rem 0.9rem;border-radius:9px;
            background:#F9FAFB;border:1px solid #E5E7EB;
            position:relative;overflow:hidden;
        }
        .sum-box::before {
            content:'';position:absolute;top:0;left:0;right:0;height:3px;
        }
        .sum-box         ::before { background:#9CA3AF; }
        .sum-box.sum-green::before { background:#16A34A; }
        .sum-box.sum-blue ::before { background:#2563EB; }
        .sum-box.sum-teal ::before { background:#0D9488; }
        .sum-box.sum-amber::before { background:#D97706; }
        .sum-green { background:#F0FDF4;border-color:#BBF7D0; }
        .sum-blue  { background:#EFF6FF;border-color:#BFDBFE; }
        .sum-teal  { background:#F0FDFA;border-color:#99F6E4; }
        .sum-amber { background:#FFFBEB;border-color:#FDE68A; }
        .sum-num { font-size:1.5rem;font-weight:800;color:#111827;line-height:1;margin-top:0.1rem; }
        .sum-lbl { font-size:0.6rem;color:#6B7280;margin-top:0.2rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em; }

        /* ── Table ───────────────────────────────────── */
        .report-table { width:100%;border-collapse:collapse;font-size:0.72rem;margin-bottom:1.5rem; }
        .report-table thead tr {
            background:linear-gradient(135deg,#1A7A4A,#22A05A);
        }
        .report-table th {
            color:white;padding:0.6rem 0.7rem;font-size:0.58rem;font-weight:700;
            text-transform:uppercase;letter-spacing:0.06em;text-align:left;white-space:nowrap;
        }
        .report-table th:first-child { border-radius:0; }
        .report-table td { padding:0.5rem 0.7rem;border-bottom:1px solid #F3F4F6;vertical-align:middle; }
        .row-even td { background:#FFFFFF; }
        .row-odd  td { background:#F9FAFB; }
        .row-warning td { background:#FFFBEB!important; }
        .report-table tbody tr:hover td { background:#F0FDF4!important; }
        .col-no { width:32px;text-align:center;color:#9CA3AF;font-weight:700; }
        .text-center { text-align:center; }
        .text-right  { text-align:right; }
        .mono { font-family:monospace;font-size:0.67rem;color:#2563EB;font-weight:700; }
        .cell-primary { font-weight:700;color:#111827; }
        .cell-sub { font-size:0.63rem;color:#9CA3AF;margin-top:1px; }
        .font-bold { font-weight:800;color:#111827; }
        .empty-row { text-align:center;padding:2.5rem;color:#9CA3AF;font-style:italic; }

        /* Kondisi badges */
        .kond-badge { display:inline-flex;padding:0.14rem 0.5rem;border-radius:100px;font-size:0.6rem;font-weight:700; }
        .kond-baik         { background:#DCFCE7;color:#15803D; }
        .kond-rusak_ringan { background:#FEF9E7;color:#92400E; }
        .kond-rusak_berat  { background:#FEE2E2;color:#991B1B; }
        .kond-perbaikan    { background:#FEF3C7;color:#92400E; }
        .kond-lain         { background:#F1F5F9;color:#475569; }

        /* Status badges */
        .status-badge { display:inline-flex;padding:0.14rem 0.5rem;border-radius:100px;font-size:0.6rem;font-weight:700; }
        .st-aktif    { background:#DCFCE7;color:#15803D; }
        .st-nonaktif { background:#F1F5F9;color:#6B7280; }

        /* Stok */
        .stok-ok   { font-weight:800;color:#15803D; }
        .stok-warn { font-weight:800;color:#DC2626; }
        .stok-bar-track { height:3px;background:#E5E7EB;border-radius:2px;margin-top:3px; }
        .stok-bar-fill  { height:100%;border-radius:2px; }

        /* Freq badge */
        .freq-badge {
            display:inline-flex;padding:0.14rem 0.55rem;border-radius:100px;
            font-size:0.62rem;font-weight:800;background:#F0FDF4;color:#15803D;border:1px solid #BBF7D0;
        }

        /* Top section */
        .top-section {
            background:linear-gradient(135deg,#F0FDF4,#ECFDF5);
            border:1px solid #BBF7D0;border-radius:10px;padding:1.1rem;margin-bottom:1.5rem;
        }
        .top-title { font-size:0.75rem;font-weight:800;color:#111827;margin-bottom:0.7rem;display:flex;align-items:center;gap:0.4rem; }
        .top-list { display:flex;flex-direction:column;gap:0.35rem; }
        .top-item {
            display:flex;align-items:center;gap:0.65rem;font-size:0.72rem;
            padding:0.4rem 0.6rem;background:white;border-radius:7px;border:1px solid #D1FAE5;
        }
        .top-rank {
            width:22px;height:22px;border-radius:50%;
            background:linear-gradient(135deg,#1A7A4A,#22A05A);color:white;
            display:flex;align-items:center;justify-content:center;font-size:0.6rem;font-weight:800;flex-shrink:0;
        }
        .top-rank.rank-1 { background:linear-gradient(135deg,#D97706,#F59E0B); }
        .top-rank.rank-2 { background:linear-gradient(135deg,#6B7280,#9CA3AF); }
        .top-rank.rank-3 { background:linear-gradient(135deg,#C2410C,#EA580C); }
        .top-name { flex:1;font-weight:600;color:#111827; }
        .top-count { color:#15803D;font-weight:800;font-size:0.7rem; }

        /* Footer */
        .doc-footer {
            display:flex;justify-content:space-between;align-items:center;
            font-size:0.65rem;color:#9CA3AF;padding-top:0.85rem;
            border-top:1px solid #E5E7EB;margin-top:0.5rem;
        }
        .doc-footer-brand { font-weight:700;color:#1A7A4A; }

        /* ── PRINT STYLES ─────────────────────────────── */
        @media print {
            .no-print { display:none!important; }

            body, html { background:white!important; }

            .print-doc {
                box-shadow:none!important;border:none!important;
                padding:0!important;border-radius:0!important;
            }

            .doc-header {
                border-bottom:2.5pt solid #1A7A4A!important;
                margin-bottom:14pt!important;padding-bottom:10pt!important;
            }

            .doc-logo { font-size:16pt!important;color:#1A7A4A!important; }
            .doc-title { font-size:9pt!important; }
            .doc-period,.doc-generated { font-size:7pt!important; }

            .sum-grid { gap:4pt!important;margin-bottom:12pt!important; }
            .sum-box { padding:6pt 8pt!important;border-radius:4pt!important; }
            .sum-num { font-size:14pt!important; }
            .sum-lbl { font-size:6pt!important; }

            .report-table { font-size:7pt!important;margin-bottom:10pt!important; }
            .report-table thead tr { background:#1A7A4A!important;-webkit-print-color-adjust:exact;print-color-adjust:exact; }
            .report-table th { padding:4pt 5pt!important;font-size:5.5pt!important;color:white!important; }
            .report-table td { padding:3.5pt 5pt!important;border-bottom:0.5pt solid #E5E7EB!important; }
            .row-even td { background:#FFFFFF!important; }
            .row-odd td  { background:#F9FAFB!important;-webkit-print-color-adjust:exact;print-color-adjust:exact; }
            .row-warning td { background:#FFFBEB!important;-webkit-print-color-adjust:exact;print-color-adjust:exact; }

            .sum-green { background:#F0FDF4!important;-webkit-print-color-adjust:exact;print-color-adjust:exact; }
            .sum-blue  { background:#EFF6FF!important;-webkit-print-color-adjust:exact;print-color-adjust:exact; }
            .sum-teal  { background:#F0FDFA!important;-webkit-print-color-adjust:exact;print-color-adjust:exact; }
            .sum-amber { background:#FFFBEB!important;-webkit-print-color-adjust:exact;print-color-adjust:exact; }

            .kond-baik         { background:#DCFCE7!important;color:#15803D!important;-webkit-print-color-adjust:exact;print-color-adjust:exact; }
            .kond-rusak_ringan { background:#FEF9E7!important;color:#92400E!important;-webkit-print-color-adjust:exact;print-color-adjust:exact; }
            .kond-rusak_berat  { background:#FEE2E2!important;color:#991B1B!important;-webkit-print-color-adjust:exact;print-color-adjust:exact; }
            .kond-perbaikan    { background:#FEF3C7!important;color:#92400E!important;-webkit-print-color-adjust:exact;print-color-adjust:exact; }
            .st-aktif          { background:#DCFCE7!important;color:#15803D!important;-webkit-print-color-adjust:exact;print-color-adjust:exact; }
            .freq-badge        { background:#F0FDF4!important;color:#15803D!important;-webkit-print-color-adjust:exact;print-color-adjust:exact; }

            .top-section {
                background:#F0FDF4!important;border:0.75pt solid #BBF7D0!important;
                padding:8pt!important;margin-bottom:10pt!important;
                -webkit-print-color-adjust:exact;print-color-adjust:exact;
            }
            .top-item { padding:3pt 5pt!important;border:0.5pt solid #D1FAE5!important; }
            .top-rank { background:#1A7A4A!important;-webkit-print-color-adjust:exact;print-color-adjust:exact; }
            .top-rank.rank-1 { background:#D97706!important;-webkit-print-color-adjust:exact;print-color-adjust:exact; }
            .top-rank.rank-2 { background:#6B7280!important;-webkit-print-color-adjust:exact;print-color-adjust:exact; }
            .top-rank.rank-3 { background:#C2410C!important;-webkit-print-color-adjust:exact;print-color-adjust:exact; }

            .doc-footer { padding-top:6pt!important;margin-top:4pt!important;font-size:6pt!important; }

            @page { margin:1.5cm 1.2cm;size:A4 landscape; }
        }
    </style>

    {{-- Filter (no-print) --}}
    <form method="GET" action="{{ route('petugas.laporan.alat') }}" class="no-print">
        <div class="filter-bar">
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">Periode Dari</label>
                    <input type="date" name="dari" value="{{ $dari }}" class="filter-input">
                </div>
                <div class="filter-group">
                    <label class="filter-label">Sampai</label>
                    <input type="date" name="sampai" value="{{ $sampai }}" class="filter-input">
                </div>
                <button type="submit" class="btn-filter">Tampilkan</button>
            </div>
        </div>
    </form>

    {{-- ── Printable document ────────────────────────── --}}
    <div class="print-doc">

        {{-- Document header --}}
        <div class="doc-header">
            <div class="doc-brand">
                <div class="doc-logo">SewaAlat</div>
                <div class="doc-tagline">Platform Sewa Alat Terpercaya</div>
            </div>
            <div class="doc-info">
                <div class="doc-title">Laporan Inventaris Alat</div>
                <div class="doc-period">Frekuensi penggunaan: {{ \Carbon\Carbon::parse($dari)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($sampai)->format('d M Y') }}</div>
                <div class="doc-generated">Dicetak: {{ now()->format('d M Y, H:i') }} · oleh {{ auth()->user()->name }}</div>
            </div>
        </div>

        {{-- Summary --}}
        <div class="sum-grid">
            <div class="sum-box">
                <div class="sum-lbl">Total Alat</div>
                <div class="sum-num">{{ $ringkasan['total_alat'] }}</div>
            </div>
            <div class="sum-box sum-green">
                <div class="sum-lbl">Status Aktif</div>
                <div class="sum-num">{{ $ringkasan['aktif'] }}</div>
            </div>
            <div class="sum-box sum-blue">
                <div class="sum-lbl">Total Stok</div>
                <div class="sum-num">{{ $ringkasan['total_stok'] }}</div>
            </div>
            <div class="sum-box sum-teal">
                <div class="sum-lbl">Stok Tersedia</div>
                <div class="sum-num">{{ $ringkasan['stok_tersedia'] }}</div>
            </div>
            <div class="sum-box sum-amber">
                <div class="sum-lbl">Dipinjam Periode Ini</div>
                <div class="sum-num">{{ $ringkasan['total_peminjaman'] }}</div>
            </div>
        </div>

        {{-- Table --}}
        <table class="report-table">
            <thead>
                <tr>
                    <th class="col-no">No</th>
                    <th>Kode</th>
                    <th>Nama Alat</th>
                    <th>Kategori</th>
                    <th>Merk</th>
                    <th class="text-center">Kondisi</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Stok Total</th>
                    <th class="text-center">Stok Tersedia</th>
                    <th class="text-right">Harga/Hari</th>
                    <th class="text-right">Denda/Hari</th>
                    <th class="text-center">Frek. Dipinjam</th>
                </tr>
            </thead>
            <tbody>
                @forelse($alats as $i => $alat)
                    @php
                        $stokPct     = $alat->stok_total > 0 ? ($alat->stok_tersedia / $alat->stok_total) * 100 : 0;
                        $stokWarning = $stokPct < 20;
                    @endphp
                    <tr class="{{ $i % 2 === 0 ? 'row-even':'row-odd' }} {{ $alat->kondisi !== 'baik' ? 'row-warning':'' }}">
                        <td class="col-no">{{ $i + 1 }}</td>
                        <td class="mono">{{ $alat->kode }}</td>
                        <td>
                            <div class="cell-primary">{{ $alat->nama }}</div>
                            @if($alat->total_dipinjam > 0)
                                <div class="cell-sub" style="color:var(--green-m)">★ Populer periode ini</div>
                            @endif
                        </td>
                        <td class="cell-sub">{{ $alat->kategori->nama ?? '—' }}</td>
                        <td class="cell-sub">{{ $alat->merk ?? '—' }}</td>
                        <td class="text-center">
                            @php $kl=['baik'=>['Baik','kond-baik'],'rusak_ringan'=>['Rusak Ringan','kond-rusak_ringan'],'rusak_berat'=>['Rusak Berat','kond-rusak_berat'],'perbaikan'=>['Perbaikan','kond-perbaikan']]; @endphp
                            <span class="kond-badge {{ $kl[$alat->kondisi][1] ?? 'kond-lain' }}">{{ $kl[$alat->kondisi][0] ?? ucfirst(str_replace('_',' ',$alat->kondisi)) }}</span>
                        </td>
                        <td class="text-center">
                            <span class="status-badge {{ $alat->status === 'aktif' ? 'st-aktif':'st-nonaktif' }}">{{ ucfirst($alat->status) }}</span>
                        </td>
                        <td class="text-center font-bold">{{ $alat->stok_total }}</td>
                        <td class="text-center">
                            <span class="{{ $stokWarning ? 'stok-warn' : 'stok-ok' }}">{{ $alat->stok_tersedia }}</span>
                            <div class="stok-bar-track">
                                <div class="stok-bar-fill" style="width:{{ $stokPct }}%;background:{{ $stokWarning ? '#DC2626' : ($stokPct > 60 ? '#16A34A' : '#D97706') }}"></div>
                            </div>
                        </td>
                        <td class="text-right cell-primary">Rp {{ number_format($alat->harga_sewa_per_hari,0,',','.') }}</td>
                        <td class="text-right" style="color:var(--danger);font-weight:600">Rp {{ number_format($alat->denda_per_hari,0,',','.') }}</td>
                        <td class="text-center">
                            @if($alat->total_dipinjam > 0)
                                <span class="freq-badge">{{ $alat->total_dipinjam }}×</span>
                            @else
                                <span style="color:#D1D5DB">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="12" class="empty-row">Tidak ada data alat.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{-- Top 5 --}}
        @php $topAlats = $alats->where('total_dipinjam','>',0)->take(5); @endphp
        @if($topAlats->isNotEmpty())
            <div class="top-section">
                <div class="top-title">🏆 Top Alat Paling Sering Dipinjam Periode Ini</div>
                <div class="top-list">
                    @foreach($topAlats as $j => $ta)
                        <div class="top-item">
                            <span class="top-rank {{ $j===0?'rank-1':($j===1?'rank-2':($j===2?'rank-3':'')) }}">{{ $j + 1 }}</span>
                            <span class="top-name">{{ $ta->nama }}</span>
                            <span class="top-count">{{ $ta->total_dipinjam }}× dipinjam</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Footer --}}
        <div class="doc-footer">
            <div><span class="doc-footer-brand">SewaAlat</span> · Laporan digenerate otomatis oleh sistem</div>
            <div>Dicetak {{ now()->format('d M Y, H:i') }}</div>
        </div>

    </div>

</x-petugas-layout>