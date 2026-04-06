<x-petugas-layout title="Rekap Bulanan">

    <x-slot name="header">
        <div>
            <h1 class="page-heading">Rekap Bulanan {{ $tahun }}</h1>
            <p class="page-sub">Ringkasan aktivitas peminjaman per bulan sepanjang tahun.</p>
        </div>
        <div style="display:flex;gap:0.5rem">
            <button onclick="window.print()" class="btn btn-primary">🖨 Cetak</button>
            <a href="{{ route('petugas.laporan.index') }}" class="btn btn-secondary">← Laporan</a>
        </div>
    </x-slot>

    <style>
        /* ── Screen-only styles ──────────────────────── */
        .no-print {}

        /* Filter bar */
        .filter-bar {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1rem 1.25rem;
            margin-bottom: 1.25rem;
            box-shadow: var(--shadow-sm);
        }
        .filter-row { display: flex; gap: 0.75rem; align-items: flex-end; flex-wrap: wrap; }
        .filter-group { display: flex; flex-direction: column; gap: 0.3rem; }
        .filter-label {
            font-size: 0.68rem; font-weight: 700;
            color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.07em;
        }
        .filter-input {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius); padding: 0.55rem 0.85rem;
            color: var(--text-primary); font-family: var(--font-ui); font-size: 0.82rem;
            outline: none; transition: border-color 0.15s, box-shadow 0.15s;
        }
        .filter-input:focus {
            border-color: var(--green);
            box-shadow: 0 0 0 3px rgba(45,122,79,0.1);
        }
        .btn-filter {
            padding: 0.55rem 1.1rem; border-radius: var(--radius);
            font-size: 0.8rem; font-weight: 600; cursor: pointer;
            background: var(--green); color: white; border: none;
            font-family: var(--font-ui); transition: background 0.15s;
        }
        .btn-filter:hover { background: var(--green-d); }

        /* ── Document wrapper (screen) ───────────────── */
        .print-doc {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 2rem;
            box-shadow: var(--shadow-sm);
        }

        /* Doc header */
        .doc-header {
            display: flex; justify-content: space-between; align-items: flex-start;
            margin-bottom: 1.5rem; padding-bottom: 1.25rem;
            border-bottom: 2px solid var(--green);
        }
        .doc-logo-wrap { display: flex; align-items: center; gap: 0.6rem; }
        .doc-logo-mark {
            width: 38px; height: 38px; background: var(--green);
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
        }
        .doc-logo-mark svg { width: 20px; height: 20px; fill: white; }
        .doc-logo-name {
            font-family: var(--font-display);
            font-size: 1.35rem; font-weight: 700; color: var(--text-primary);
        }
        .doc-logo-tagline {
            font-size: 0.65rem; color: var(--text-muted); margin-top: 0.1rem;
        }
        .doc-meta { text-align: right; }
        .doc-title {
            font-size: 0.95rem; font-weight: 800;
            color: var(--text-primary); letter-spacing: 0.03em;
        }
        .doc-period { font-size: 0.72rem; color: var(--text-secondary); margin-top: 0.15rem; }
        .doc-generated { font-size: 0.68rem; color: var(--text-muted); margin-top: 0.1rem; }

        /* Summary grid */
        .sum-grid {
            display: grid; grid-template-columns: repeat(5, 1fr);
            gap: 0.75rem; margin-bottom: 1.5rem;
        }
        .sum-box {
            padding: 0.9rem 1rem; border-radius: var(--radius);
            background: var(--surface); border: 1px solid var(--border);
            position: relative; overflow: hidden;
        }
        .sum-box::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
        }
        .sum-box.default::before { background: var(--text-muted); }
        .sum-box.sum-green::before { background: var(--green); }
        .sum-box.sum-red::before   { background: var(--red); }
        .sum-box.sum-amber::before { background: var(--amber); }
        .sum-box.sum-blue::before  { background: var(--blue); }
        .sum-green { background: var(--green-bg); border-color: var(--green-mid); }
        .sum-red   { background: var(--red-bg);   border-color: #FEC9C7; }
        .sum-amber { background: var(--amber-bg); border-color: #FDE68A; }
        .sum-blue  { background: var(--blue-bg);  border-color: #BFDBFE; }
        .sum-num {
            font-family: var(--font-display);
            font-size: 1.5rem; font-weight: 700;
            color: var(--text-primary); line-height: 1;
        }
        .sum-lbl { font-size: 0.65rem; color: var(--text-muted); margin-top: 0.2rem; }

        /* Bar chart */
        .chart-section {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius); padding: 1.25rem; margin-bottom: 1.5rem;
        }
        .chart-title {
            font-size: 0.78rem; font-weight: 700;
            color: var(--text-primary); margin-bottom: 1rem;
        }
        .chart-wrap { display: flex; align-items: flex-end; gap: 0.35rem; height: 100px; }
        .chart-col { flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%; }
        .chart-val {
            font-size: 0.55rem; font-weight: 700;
            color: var(--text-secondary); margin-bottom: 0.15rem; height: 12px; line-height: 12px;
        }
        .chart-bar-track {
            flex: 1; width: 100%; background: var(--border);
            border-radius: 3px 3px 0 0; overflow: hidden;
            display: flex; flex-direction: column; justify-content: flex-end;
        }
        .chart-bar-fill {
            width: 100%; background: var(--green);
            border-radius: 2px 2px 0 0; min-height: 2px;
        }
        .chart-bar-fill.current-month { background: var(--blue); }
        .chart-label { font-size: 0.55rem; font-weight: 700; color: var(--text-muted); margin-top: 0.2rem; }

        /* Report table */
        .report-table { width: 100%; border-collapse: collapse; font-size: 0.78rem; margin-bottom: 1.25rem; }
        .report-table thead th {
            background: var(--text-primary); color: white;
            padding: 0.6rem 0.85rem; font-size: 0.65rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.08em; text-align: left;
        }
        .report-table thead th:first-child { border-radius: 6px 0 0 0; }
        .report-table thead th:last-child  { border-radius: 0 6px 0 0; }
        .report-table tbody td {
            padding: 0.6rem 0.85rem; border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }
        .row-even td { background: var(--surface); }
        .row-odd  td { background: var(--white); }
        .row-current td { background: var(--blue-bg) !important; }
        .text-center { text-align: center; }
        .text-right  { text-align: right; }
        .cell-primary { font-weight: 600; color: var(--text-primary); }
        .cell-sub { font-size: 0.68rem; color: var(--text-muted); }

        .report-table tfoot td {
            background: var(--surface-2); border-top: 2px solid var(--border-2);
            font-weight: 700; padding: 0.65rem 0.85rem;
        }
        .foot-label { text-align: right; padding-right: 0.7rem; color: var(--text-primary); font-weight: 700; }
        .foot-val { color: var(--text-primary); font-weight: 800; }
        .denda-val { color: var(--red); font-weight: 700; }

        .num-badge {
            display: inline-flex; padding: 0.15rem 0.6rem;
            border-radius: 100px; font-size: 0.65rem; font-weight: 800;
        }
        .num-pinjam  { background: var(--blue-bg);  color: #1D4ED8; }
        .num-kembali { background: var(--green-bg);  color: var(--green-d); }
        .num-late    { background: var(--red-bg);    color: var(--red); }
        .num-ok      { font-size: 0.65rem; font-weight: 700; color: var(--green-d); }

        .pct-bar-wrap { height: 4px; background: var(--border); border-radius: 2px; margin-bottom: 2px; overflow: hidden; }
        .pct-bar { height: 100%; border-radius: 2px; }

        .current-badge {
            display: inline-flex; margin-left: 0.4rem;
            padding: 0.1rem 0.45rem;
            background: var(--blue-bg); color: #1D4ED8;
            border-radius: 100px; font-size: 0.6rem; font-weight: 700;
        }

        /* Doc footer */
        .doc-footer {
            display: flex; justify-content: space-between;
            font-size: 0.68rem; color: var(--text-muted);
            padding-top: 0.85rem; border-top: 1px solid var(--border);
            margin-top: 0.5rem;
        }

        /* ══════════════════════════════════════════════
           PRINT STYLES — Professional A4
        ══════════════════════════════════════════════ */
        @media print {
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }

            .no-print { display: none !important; }

            /* Reset page chrome */
            body { background: white !important; font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif; }
            .main-wrap { margin-left: 0 !important; }
            .page-content { padding: 0 !important; }
            .page-header { display: none !important; }

            @page { margin: 1.6cm 1.8cm; size: A4 portrait; }

            /* Unwrap the card for print */
            .print-doc {
                border: none !important; border-radius: 0 !important;
                box-shadow: none !important; padding: 0 !important;
            }

            /* ── Print: Doc header ── */
            .doc-header {
                display: flex; justify-content: space-between; align-items: center;
                padding-bottom: 12pt; margin-bottom: 14pt;
                border-bottom: 2.5pt solid #2D7A4F !important;
            }
            .doc-logo-mark {
                width: 36pt; height: 36pt; background: #2D7A4F !important;
                border-radius: 7pt;
                display: flex; align-items: center; justify-content: center;
            }
            .doc-logo-mark svg { width: 18pt; height: 18pt; fill: white !important; }
            .doc-logo-name { font-size: 16pt; font-weight: 900; color: #1A2116 !important; }
            .doc-logo-tagline { font-size: 7pt; color: #52614A !important; }
            .doc-title { font-size: 10pt; font-weight: 800; color: #1A2116 !important; }
            .doc-period, .doc-generated { font-size: 7pt; color: #52614A !important; }

            /* ── Print: Summary grid ── */
            .sum-grid {
                display: grid; grid-template-columns: repeat(5, 1fr);
                gap: 6pt; margin-bottom: 14pt;
            }
            .sum-box {
                padding: 7pt 8pt; border-radius: 5pt;
                border: 1pt solid #E2E5DC !important;
            }
            .sum-box::before { height: 2.5pt !important; }
            .sum-green { background: #EAF4EE !important; border-color: #C5DFCF !important; }
            .sum-red   { background: #FEF0EF !important; border-color: #FEC9C7 !important; }
            .sum-amber { background: #FEF3E2 !important; border-color: #FDE68A !important; }
            .sum-blue  { background: #EFF4FE !important; border-color: #BFDBFE !important; }
            .sum-num { font-size: 14pt !important; color: #1A2116 !important; }
            .sum-lbl { font-size: 6pt !important; color: #8A9882 !important; }

            /* ── Print: Chart ── */
            .chart-section {
                background: #F7F8F5 !important;
                border: 1pt solid #E2E5DC !important;
                border-radius: 5pt; padding: 10pt; margin-bottom: 14pt;
            }
            .chart-title { font-size: 8pt !important; color: #1A2116 !important; }
            .chart-wrap { height: 80pt; }
            .chart-bar-track { background: #E2E5DC !important; }
            .chart-bar-fill { background: #2D7A4F !important; }
            .chart-bar-fill.current-month { background: #2563EB !important; }
            .chart-val { font-size: 5pt !important; }
            .chart-label { font-size: 5.5pt !important; }

            /* ── Print: Table ── */
            .report-table { font-size: 7.5pt; margin-bottom: 10pt; }
            .report-table thead th {
                background: #1A2116 !important; color: white !important;
                padding: 5pt 7pt; font-size: 6pt;
                border-radius: 0 !important;
            }
            .report-table tbody td { padding: 5pt 7pt; }
            .row-even td { background: #F7F8F5 !important; }
            .row-odd  td { background: white !important; }
            .row-current td { background: #EFF4FE !important; }
            .cell-primary { color: #1A2116 !important; }
            .cell-sub { font-size: 6pt !important; color: #8A9882 !important; }

            .report-table tfoot td {
                background: #EFF1EC !important;
                border-top: 1.5pt solid #CBD0C4 !important;
                font-size: 7.5pt;
            }

            .num-badge { font-size: 6pt; padding: 1pt 4pt; }
            .num-pinjam  { background: #EFF4FE !important; color: #1D4ED8 !important; }
            .num-kembali { background: #EAF4EE !important; color: #1F5C39 !important; }
            .num-late    { background: #FEF0EF !important; color: #C0392B !important; }
            .num-ok      { font-size: 6pt !important; color: #1F5C39 !important; }
            .denda-val   { color: #C0392B !important; }
            .current-badge { font-size: 5.5pt; background: #EFF4FE !important; color: #1D4ED8 !important; }

            .pct-bar-wrap { height: 3pt; }

            /* ── Print: Footer ── */
            .doc-footer {
                font-size: 6.5pt !important; color: #8A9882 !important;
                border-top: 1pt solid #E2E5DC !important;
                padding-top: 8pt;
            }

            /* Page break control */
            .report-table { page-break-inside: auto; }
            .report-table tr { page-break-inside: avoid; page-break-after: auto; }
        }
    </style>

    {{-- Filter (screen only) --}}
    <form method="GET" action="{{ route('petugas.laporan.rekap') }}" class="no-print">
        <div class="filter-bar">
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">Tahun</label>
                    <select name="tahun" class="filter-input">
                        @foreach($tahunTersedia as $t)
                            <option value="{{ $t }}" {{ $tahun==$t ? 'selected':'' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-filter">Tampilkan</button>
            </div>
        </div>
    </form>

    {{-- ── Document ───────────────────────────────── --}}
    <div class="print-doc">

        {{-- Header --}}
        <div class="doc-header">
            <div class="doc-logo-wrap">
                <div class="doc-logo-mark">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                    </svg>
                </div>
                <div>
                    <div class="doc-logo-name">PinjamAlat</div>
                    <div class="doc-logo-tagline">Sistem Manajemen Peminjaman Alat</div>
                </div>
            </div>
            <div class="doc-meta">
                <div class="doc-title">REKAP BULANAN {{ $tahun }}</div>
                <div class="doc-period">Ringkasan aktivitas peminjaman alat tahun {{ $tahun }}</div>
                <div class="doc-generated">Dicetak: {{ now()->format('d M Y, H:i') }} · oleh {{ auth()->user()->name }}</div>
            </div>
        </div>

        {{-- Year summary --}}
        <div class="sum-grid">
            <div class="sum-box default">
                <div class="sum-num">{{ number_format($ringkasan['total_pinjam']) }}</div>
                <div class="sum-lbl">Total Peminjaman</div>
            </div>
            <div class="sum-box sum-green">
                <div class="sum-num">{{ number_format($ringkasan['total_kembali']) }}</div>
                <div class="sum-lbl">Total Dikembalikan</div>
            </div>
            <div class="sum-box sum-red">
                <div class="sum-num">{{ number_format($ringkasan['total_terlambat']) }}</div>
                <div class="sum-lbl">Total Terlambat</div>
            </div>
            <div class="sum-box sum-amber">
                <div class="sum-num" style="font-size:1rem">Rp {{ number_format($ringkasan['total_denda']/1000,0) }}rb</div>
                <div class="sum-lbl">Total Denda</div>
            </div>
            @if($ringkasan['bulan_tersibuk'])
                <div class="sum-box sum-blue">
                    <div class="sum-num">{{ \Carbon\Carbon::create()->month($ringkasan['bulan_tersibuk']['bulan'])->format('M') }}</div>
                    <div class="sum-lbl">Bulan Tersibuk ({{ $ringkasan['bulan_tersibuk']['pinjam'] }}×)</div>
                </div>
            @endif
        </div>

        {{-- Bar chart --}}
        @php
            $maxPinjam = $bulanData->max('pinjam');
            $maxPinjam = max($maxPinjam, 1);
            $namaBulan = ['','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        @endphp
        <div class="chart-section">
            <div class="chart-title">Grafik Peminjaman per Bulan</div>
            <div class="chart-wrap">
                @foreach($bulanData as $row)
                    @php $pct = round(($row['pinjam'] / $maxPinjam) * 100); @endphp
                    <div class="chart-col">
                        <div class="chart-val">{{ $row['pinjam'] > 0 ? $row['pinjam'] : '' }}</div>
                        <div class="chart-bar-track">
                            <div class="chart-bar-fill {{ $row['bulan'] == now()->month && $tahun == now()->year ? 'current-month' : '' }}"
                                 style="height:{{ $pct }}%"></div>
                        </div>
                        <div class="chart-label">{{ $namaBulan[$row['bulan']] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Monthly table --}}
        <table class="report-table">
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th class="text-center">Pengajuan Baru</th>
                    <th class="text-center">Pengembalian</th>
                    <th class="text-center">Terlambat</th>
                    <th class="text-center">% Terlambat</th>
                    <th class="text-right">Total Denda</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bulanData as $row)
                    @php
                        $isCurrent = $row['bulan'] == now()->month && $tahun == now()->year;
                        $pctLate   = $row['kembali'] > 0 ? round(($row['terlambat'] / $row['kembali']) * 100) : 0;
                    @endphp
                    <tr class="{{ $loop->iteration % 2 === 0 ? 'row-even' : 'row-odd' }} {{ $isCurrent ? 'row-current' : '' }}">
                        <td>
                            <span class="cell-primary">{{ $namaBulan[$row['bulan']] }} {{ $tahun }}</span>
                            @if($isCurrent)<span class="current-badge">Berjalan</span>@endif
                        </td>
                        <td class="text-center">
                            @if($row['pinjam'] > 0)
                                <span class="num-badge num-pinjam">{{ $row['pinjam'] }}</span>
                            @else
                                <span style="color:var(--border-2)">—</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($row['kembali'] > 0)
                                <span class="num-badge num-kembali">{{ $row['kembali'] }}</span>
                            @else
                                <span style="color:var(--border-2)">—</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($row['terlambat'] > 0)
                                <span class="num-badge num-late">{{ $row['terlambat'] }}</span>
                            @else
                                <span class="num-ok">✓ 0</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($row['kembali'] > 0)
                                <div class="pct-bar-wrap">
                                    <div class="pct-bar"
                                         style="width:{{ $pctLate }}%;background:{{ $pctLate > 30 ? 'var(--red)' : ($pctLate > 10 ? 'var(--amber)' : 'var(--green)') }}">
                                    </div>
                                </div>
                                <div style="font-size:0.62rem;font-weight:700;color:{{ $pctLate > 30 ? 'var(--red)' : 'var(--text-muted)' }}">
                                    {{ $pctLate }}%
                                </div>
                            @else
                                <span style="color:var(--border-2)">—</span>
                            @endif
                        </td>
                        <td class="text-right {{ $row['denda'] > 0 ? 'denda-val' : '' }}">
                            {{ $row['denda'] > 0 ? 'Rp '.number_format($row['denda'],0,',','.') : '—' }}
                        </td>
                        <td class="cell-sub">
                            @if($row['pinjam'] == 0 && $row['bulan'] > now()->month && $tahun >= now()->year)
                                Belum terjadi
                            @elseif($row['pinjam'] == $bulanData->max('pinjam') && $row['pinjam'] > 0)
                                🏆 Bulan tersibuk
                            @elseif($row['terlambat'] > 0 && $pctLate > 30)
                                ⚠ Tingkat keterlambatan tinggi
                            @elseif($row['pinjam'] > 0)
                                Normal
                            @else
                                Tidak ada aktivitas
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td class="foot-label">TOTAL {{ $tahun }}</td>
                    <td class="foot-val text-center">{{ $ringkasan['total_pinjam'] }}</td>
                    <td class="foot-val text-center">{{ $ringkasan['total_kembali'] }}</td>
                    <td class="foot-val text-center">{{ $ringkasan['total_terlambat'] }}</td>
                    <td class="foot-val text-center">
                        {{ $ringkasan['total_kembali'] > 0 ? round(($ringkasan['total_terlambat']/$ringkasan['total_kembali'])*100).'%' : '—' }}
                    </td>
                    <td class="foot-val text-right denda-val">Rp {{ number_format($ringkasan['total_denda'],0,',','.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        {{-- Footer --}}
        <div class="doc-footer">
            <div>Laporan ini digenerate otomatis oleh sistem PinjamAlat · Dokumen resmi untuk keperluan internal</div>
            <div>{{ now()->format('d M Y, H:i') }} WIB</div>
        </div>

    </div>

</x-petugas-layout>