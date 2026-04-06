<x-petugas-layout title="Laporan Peminjaman">

    <x-slot name="header">
        <div>
            <h1 class="page-heading">Laporan Peminjaman</h1>
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
            --green:       #1A7A4A;
            --green-m:     #22A05A;
            --green-l:     #2DBE6C;
            --pale:        #E8F8EE;
            --pale-b:      rgba(34,160,90,0.2);
            --ring:        rgba(34,160,90,0.18);
            --danger:      #DC2626;
            --danger-pale: #FEF2F2;
            --danger-b:    #FECACA;
            --blue:        #2563EB;
            --blue-pale:   #EFF6FF;
            --blue-b:      #BFDBFE;
            --surface:     #FFFFFF;
            --surface2:    #F7FAF8;
            --border:      #E5E7EB;
            --border2:     #F3F4F6;
            --text:        #111827;
            --sub:         #374151;
            --muted:       #6B7280;
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
        .filter-input option { background:#fff;color:var(--text); }
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
            display:flex;align-items:flex-start;justify-content:space-between;
            margin-bottom:1.75rem;padding-bottom:1.25rem;
            border-bottom:3px solid #1A7A4A;
        }
        .doc-brand { display:flex;flex-direction:column;gap:0.1rem; }
        .doc-logo { font-size:1.5rem;font-weight:900;color:#1A7A4A;letter-spacing:-0.02em;line-height:1; }
        .doc-tagline { font-size:0.62rem;color:#6B7280;letter-spacing:0.06em;text-transform:uppercase; }
        .doc-info { text-align:right; }
        .doc-title { font-size:0.88rem;font-weight:800;color:#111827;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.3rem; }
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
            background:#9CA3AF;
        }
        .sum-green::before { background:#16A34A; }
        .sum-blue::before  { background:#2563EB; }
        .sum-red::before   { background:#DC2626; }
        .sum-teal::before  { background:#0D9488; }
        .sum-green { background:#F0FDF4;border-color:#BBF7D0; }
        .sum-blue  { background:#EFF6FF;border-color:#BFDBFE; }
        .sum-red   { background:#FEF2F2;border-color:#FECACA; }
        .sum-teal  { background:#F0FDFA;border-color:#99F6E4; }
        .sum-num { font-size:1.4rem;font-weight:800;color:#111827;line-height:1;margin-top:0.1rem; }
        .sum-lbl { font-size:0.6rem;color:#6B7280;margin-top:0.2rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em; }

        /* ── Table ───────────────────────────────────── */
        .report-table { width:100%;border-collapse:collapse;font-size:0.72rem;margin-bottom:1.5rem; }
        .report-table thead tr { background:linear-gradient(135deg,#1A7A4A,#22A05A); }
        .report-table th {
            color:white;padding:0.6rem 0.7rem;font-size:0.58rem;font-weight:700;
            text-transform:uppercase;letter-spacing:0.06em;text-align:left;white-space:nowrap;
        }
        .report-table td { padding:0.5rem 0.7rem;border-bottom:1px solid #F3F4F6;vertical-align:middle; }
        .row-even td { background:#FFFFFF; }
        .row-odd  td { background:#F9FAFB; }
        .report-table tbody tr:hover td { background:#F0FDF4!important; }

        .report-table tfoot td {
            background:#F3F4F6;font-weight:700;
            border-top:2px solid #E5E7EB;
        }

        .col-no  { width:36px;text-align:center;color:#9CA3AF;font-weight:700; }
        .col-num { width:45px; }
        .text-center { text-align:center; }
        .text-right  { text-align:right; }
        .mono { font-family:monospace;font-size:0.68rem;color:#2563EB;font-weight:700; }
        .cell-primary { font-weight:700;color:#111827; }
        .cell-sub { font-size:0.62rem;color:#9CA3AF;margin-top:1px; }
        .empty-row { text-align:center;padding:2.5rem;color:#9CA3AF;font-style:italic; }

        .foot-label { text-align:right;font-size:0.72rem;font-weight:700;color:#374151;padding-right:0.7rem; }
        .foot-val   { font-weight:800;color:#1A7A4A;font-size:0.85rem; }

        /* Status badges */
        .st-badge { display:inline-flex;padding:0.14rem 0.5rem;border-radius:100px;font-size:0.6rem;font-weight:700; }
        .st-menunggu     { background:#FEF9E7;color:#92400E; }
        .st-disetujui    { background:#EFF6FF;color:#1D4ED8; }
        .st-dipinjam     { background:#F0FDF4;color:#15803D; }
        .st-dikembalikan { background:#F1F5F9;color:#475569; }
        .st-ditolak      { background:#FEF2F2;color:#991B1B; }

        /* Doc footer */
        .doc-footer {
            display:flex;justify-content:space-between;align-items:center;
            font-size:0.65rem;color:#9CA3AF;
            padding-top:0.85rem;border-top:1px solid #E5E7EB;
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
            .sum-num { font-size:13pt!important; }
            .sum-lbl { font-size:6pt!important; }

            .sum-green { background:#F0FDF4!important;-webkit-print-color-adjust:exact;print-color-adjust:exact; }
            .sum-blue  { background:#EFF6FF!important;-webkit-print-color-adjust:exact;print-color-adjust:exact; }
            .sum-red   { background:#FEF2F2!important;-webkit-print-color-adjust:exact;print-color-adjust:exact; }
            .sum-teal  { background:#F0FDFA!important;-webkit-print-color-adjust:exact;print-color-adjust:exact; }

            .report-table { font-size:7pt!important;margin-bottom:10pt!important; }
            .report-table thead tr {
                background:#1A7A4A!important;
                -webkit-print-color-adjust:exact;print-color-adjust:exact;
            }
            .report-table th { padding:4pt 5pt!important;font-size:5.5pt!important;color:white!important; }
            .report-table td { padding:3.5pt 5pt!important;border-bottom:0.5pt solid #E5E7EB!important; }
            .row-even td { background:#FFFFFF!important; }
            .row-odd  td { background:#F9FAFB!important;-webkit-print-color-adjust:exact;print-color-adjust:exact; }

            .report-table tfoot td {
                background:#F3F4F6!important;border-top:1.5pt solid #D1D5DB!important;
                -webkit-print-color-adjust:exact;print-color-adjust:exact;
            }

            .st-menunggu     { background:#FEF9E7!important;color:#92400E!important;-webkit-print-color-adjust:exact;print-color-adjust:exact; }
            .st-disetujui    { background:#EFF6FF!important;color:#1D4ED8!important;-webkit-print-color-adjust:exact;print-color-adjust:exact; }
            .st-dipinjam     { background:#F0FDF4!important;color:#15803D!important;-webkit-print-color-adjust:exact;print-color-adjust:exact; }
            .st-dikembalikan { background:#F1F5F9!important;color:#475569!important;-webkit-print-color-adjust:exact;print-color-adjust:exact; }
            .st-ditolak      { background:#FEF2F2!important;color:#991B1B!important;-webkit-print-color-adjust:exact;print-color-adjust:exact; }

            .doc-footer { padding-top:6pt!important;font-size:6pt!important; }

            @page { margin:1.5cm 1.2cm;size:A4 landscape; }
        }
    </style>

    {{-- Filter (no-print) --}}
    <form method="GET" action="{{ route('petugas.laporan.peminjaman') }}" class="no-print">
        <div class="filter-bar">
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">Dari</label>
                    <input type="date" name="dari" value="{{ $dari }}" class="filter-input">
                </div>
                <div class="filter-group">
                    <label class="filter-label">Sampai</label>
                    <input type="date" name="sampai" value="{{ $sampai }}" class="filter-input">
                </div>
                <div class="filter-group">
                    <label class="filter-label">Status</label>
                    <select name="status" class="filter-input">
                        @foreach(['semua'=>'Semua Status','menunggu'=>'Menunggu','disetujui'=>'Disetujui','dipinjam'=>'Dipinjam','dikembalikan'=>'Dikembalikan','ditolak'=>'Ditolak'] as $v=>$l)
                            <option value="{{ $v }}" {{ $status===$v ? 'selected':'' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-filter">Tampilkan</button>
            </div>
        </div>
    </form>

    {{-- ── Printable document ────────────────────────── --}}
    <div class="print-doc" id="printArea">

        {{-- Doc header --}}
        <div class="doc-header">
            <div class="doc-brand">
                <div class="doc-logo">SewaAlat</div>
                <div class="doc-tagline">Platform Sewa Alat Terpercaya</div>
            </div>
            <div class="doc-info">
                <div class="doc-title">Laporan Peminjaman Alat</div>
                <div class="doc-period">Periode: {{ \Carbon\Carbon::parse($dari)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($sampai)->format('d M Y') }}</div>
                <div class="doc-generated">Dicetak: {{ now()->format('d M Y, H:i') }} · oleh {{ auth()->user()->name }}</div>
            </div>
        </div>

        {{-- Summary --}}
        <div class="sum-grid">
            <div class="sum-box">
                <div class="sum-lbl">Total Transaksi</div>
                <div class="sum-num">{{ $ringkasan['total'] }}</div>
            </div>
            <div class="sum-box sum-green">
                <div class="sum-lbl">Dikembalikan</div>
                <div class="sum-num">{{ $ringkasan['dikembalikan'] }}</div>
            </div>
            <div class="sum-box sum-blue">
                <div class="sum-lbl">Masih Dipinjam</div>
                <div class="sum-num">{{ $ringkasan['dipinjam'] }}</div>
            </div>
            <div class="sum-box sum-red">
                <div class="sum-lbl">Ditolak</div>
                <div class="sum-num">{{ $ringkasan['ditolak'] }}</div>
            </div>
            <div class="sum-box sum-teal">
                <div class="sum-lbl">Total Biaya Sewa</div>
                <div class="sum-num" style="font-size:1rem">Rp {{ number_format($ringkasan['total_biaya'],0,',','.') }}</div>
            </div>
        </div>

        {{-- Table --}}
        <table class="report-table">
            <thead>
                <tr>
                    <th class="col-no">No</th>
                    <th>No. Transaksi</th>
                    <th>Peminjam</th>
                    <th>Alat</th>
                    <th class="col-num">Jml</th>
                    <th>Tgl Pinjam</th>
                    <th>Batas Kembali</th>
                    <th>Durasi</th>
                    <th>Total Biaya</th>
                    <th>Status</th>
                    <th>Diproses</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjamans as $i => $pm)
                    <tr class="{{ $i % 2 === 0 ? 'row-even':'row-odd' }}">
                        <td class="col-no">{{ $i + 1 }}</td>
                        <td class="mono">{{ $pm->nomor_pinjam }}</td>
                        <td>
                            <div class="cell-primary">{{ $pm->peminjam->name ?? '—' }}</div>
                            <div class="cell-sub">{{ $pm->peminjam->email ?? '' }}</div>
                        </td>
                        <td>
                            <div class="cell-primary">{{ $pm->alat->nama ?? '—' }}</div>
                            <div class="cell-sub mono">{{ $pm->alat->kode ?? '' }}</div>
                        </td>
                        <td class="col-num text-center">{{ $pm->jumlah }}</td>
                        <td class="text-center">{{ $pm->tanggal_pinjam->format('d/m/Y') }}</td>
                        <td class="text-center">{{ $pm->tanggal_kembali_rencana->format('d/m/Y') }}</td>
                        <td class="text-center">{{ $pm->durasi_hari }}h</td>
                        <td class="text-right cell-primary">Rp {{ number_format($pm->total_biaya,0,',','.') }}</td>
                        <td class="text-center">
                            <span class="st-badge st-{{ $pm->status }}">{{ ucfirst($pm->status) }}</span>
                        </td>
                        <td style="font-size:0.7rem;color:#6B7280">{{ $pm->petugas?->name ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="11" class="empty-row">Tidak ada data untuk periode ini.</td></tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="8" class="foot-label">TOTAL</td>
                    <td class="foot-val text-right">Rp {{ number_format($ringkasan['total_biaya'],0,',','.') }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>

        {{-- Doc footer --}}
        <div class="doc-footer">
            <div><span class="doc-footer-brand">SewaAlat</span> · Laporan digenerate otomatis oleh sistem</div>
            <div>Dicetak {{ now()->format('d M Y, H:i') }}</div>
        </div>

    </div>

</x-petugas-layout>