<x-petugas-layout title="Cetak Laporan">

    <x-slot name="header">
        <div>
            <h1 class="page-heading">Cetak Laporan</h1>
            <p class="page-sub">Pilih jenis laporan, atur periode, lalu cetak atau ekspor.</p>
        </div>
    </x-slot>

    <style>
        :root {
            --green:      #1A7A4A;
            --green-m:    #22A05A;
            --green-l:    #2DBE6C;
            --pale:       #E8F8EE;
            --pale-b:     rgba(34,160,90,0.2);
            --danger:     #DC2626;
            --danger-pale:#FEF2F2;
            --gold:       #D97706;
            --gold-pale:  #FFFBEB;
            --blue:       #2563EB;
            --blue-pale:  #EFF6FF;
            --blue-b:     #BFDBFE;
            --purple:     #7C3AED;
            --purple-pale:#F5F3FF;
            --purple-b:   #DDD6FE;
            --surface:    #FFFFFF;
            --surface2:   #F7FAF8;
            --border:     #E5E7EB;
            --border2:    #F3F4F6;
            --text:       #111827;
            --sub:        #374151;
            --muted:      #6B7280;
        }

        /* Stats strip */
        .stats-strip {
            display:grid;grid-template-columns:repeat(4,1fr);gap:0.75rem;margin-bottom:2rem;
        }
        .stat-box {
            background:var(--surface);border:1px solid var(--border);border-radius:12px;
            padding:1.1rem 1.1rem;
            box-shadow:0 1px 4px rgba(0,0,0,0.04);
            transition:box-shadow 0.15s;
        }
        .stat-box:hover { box-shadow:0 4px 12px rgba(0,0,0,0.07); }
        .stat-icon-row { font-size:1rem;margin-bottom:0.45rem; }
        .stat-val { font-size:1.6rem;font-weight:800;color:var(--text);line-height:1; }
        .stat-lbl { font-size:0.67rem;color:var(--muted);margin-top:0.2rem;font-weight:500; }

        /* Report type cards */
        .report-grid {
            display:grid;grid-template-columns:repeat(2,1fr);gap:1rem;margin-bottom:2rem;
        }
        .report-card {
            background:var(--surface);border:1px solid var(--border);border-radius:14px;
            padding:1.5rem;text-decoration:none;transition:all 0.18s;display:block;
            position:relative;overflow:hidden;
            box-shadow:0 1px 4px rgba(0,0,0,0.04);
        }
        .report-card:hover { transform:translateY(-2px); }

        /* Color accents per type */
        .rc-peminjaman { border-top:3px solid var(--blue); }
        .rc-peminjaman:hover { border-color:var(--blue);box-shadow:0 8px 24px rgba(37,99,235,0.12); }

        .rc-pengembalian { border-top:3px solid var(--green-m); }
        .rc-pengembalian:hover { border-color:var(--green-m);box-shadow:0 8px 24px rgba(34,160,90,0.12); }

        .rc-alat { border-top:3px solid var(--gold); }
        .rc-alat:hover { border-color:var(--gold);box-shadow:0 8px 24px rgba(217,119,6,0.12); }

        .rc-rekap { border-top:3px solid var(--purple); }
        .rc-rekap:hover { border-color:var(--purple);box-shadow:0 8px 24px rgba(124,58,237,0.12); }

        .rc-icon  { font-size:2rem;margin-bottom:0.8rem;display:block; }
        .rc-title { font-size:1rem;font-weight:800;color:var(--text);margin-bottom:0.3rem; }
        .rc-desc  { font-size:0.78rem;color:var(--muted);line-height:1.55;margin-bottom:1rem; }
        .rc-meta  { display:flex;align-items:center;gap:0.5rem;flex-wrap:wrap; }

        .rc-tag { font-size:0.65rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:100px; }
        .tag-blue   { background:var(--blue-pale);color:var(--blue);border:1px solid var(--blue-b); }
        .tag-green  { background:var(--pale);color:var(--green);border:1px solid var(--pale-b); }
        .tag-teal   { background:#ECFDF5;color:#065F46;border:1px solid #A7F3D0; }
        .tag-purple { background:var(--purple-pale);color:var(--purple);border:1px solid var(--purple-b); }

        .rc-arrow { margin-left:auto;font-size:1rem;color:var(--muted);transition:transform 0.15s; }
        .report-card:hover .rc-arrow { transform:translateX(4px);color:var(--text); }

        /* Quick print section */
        .quick-section {
            background:var(--surface);border:1px solid var(--border);
            border-radius:14px;padding:1.4rem;
            box-shadow:0 1px 4px rgba(0,0,0,0.04);
        }
        .qs-title { font-size:0.88rem;font-weight:700;color:var(--text);margin-bottom:1rem; }
        .quick-grid { display:grid;grid-template-columns:repeat(3,1fr);gap:0.75rem; }
        .quick-btn {
            display:flex;flex-direction:column;align-items:center;gap:0.4rem;padding:0.9rem;
            border-radius:10px;text-decoration:none;font-size:0.75rem;font-weight:700;text-align:center;
            background:var(--surface2);border:1px solid var(--border);color:var(--muted);
            transition:all 0.15s;
        }
        .quick-btn:hover {
            border-color:var(--green-m);color:var(--green);
            background:var(--pale);transform:translateY(-1px);
            box-shadow:0 4px 10px rgba(34,160,90,0.1);
        }
        .quick-btn span:first-child { font-size:1.3rem; }

        @media(max-width:900px) { .stats-strip,.report-grid,.quick-grid { grid-template-columns:1fr 1fr; } }
        @media(max-width:600px) { .stats-strip { grid-template-columns:1fr 1fr; } .report-grid { grid-template-columns:1fr; } }
    </style>

    {{-- Stats --}}
    <div class="stats-strip">
        <div class="stat-box">
            <div class="stat-icon-row">📋</div>
            <div class="stat-val">{{ number_format($stats['total_peminjaman']) }}</div>
            <div class="stat-lbl">Total Peminjaman</div>
        </div>
        <div class="stat-box">
            <div class="stat-icon-row">↩</div>
            <div class="stat-val" style="color:var(--green-m)">{{ number_format($stats['total_pengembalian']) }}</div>
            <div class="stat-lbl">Total Pengembalian</div>
        </div>
        <div class="stat-box">
            <div class="stat-icon-row">💰</div>
            <div class="stat-val" style="font-size:1.1rem;color:var(--gold)">
                Rp {{ number_format($stats['total_denda']/1000,0) }}rb
            </div>
            <div class="stat-lbl">Total Denda Terkumpul</div>
        </div>
        <div class="stat-box">
            <div class="stat-icon-row">📅</div>
            <div class="stat-val" style="color:var(--green-m)">{{ $stats['bulan_ini_pinjam'] }}</div>
            <div class="stat-lbl">Pinjam Bulan Ini</div>
        </div>
    </div>

    {{-- Report type cards --}}
    <div class="report-grid">
        <a href="{{ route('petugas.laporan.peminjaman') }}" class="report-card rc-peminjaman">
            <span class="rc-icon">📋</span>
            <div class="rc-title">Laporan Peminjaman</div>
            <div class="rc-desc">Daftar seluruh transaksi peminjaman per periode. Filter berdasarkan status, alat, atau peminjam.</div>
            <div class="rc-meta">
                <span class="rc-tag tag-blue">Per periode</span>
                <span class="rc-tag tag-blue">Filter status</span>
                <span class="rc-arrow">→</span>
            </div>
        </a>

        <a href="{{ route('petugas.laporan.pengembalian') }}" class="report-card rc-pengembalian">
            <span class="rc-icon">↩</span>
            <div class="rc-title">Laporan Pengembalian</div>
            <div class="rc-desc">Rincian pengembalian alat, kondisi, denda keterlambatan, dan biaya kerusakan per periode.</div>
            <div class="rc-meta">
                <span class="rc-tag tag-green">Denda & tagihan</span>
                <span class="rc-tag tag-green">Kondisi alat</span>
                <span class="rc-arrow">→</span>
            </div>
        </a>

        <a href="{{ route('petugas.laporan.alat') }}" class="report-card rc-alat">
            <span class="rc-icon">🔧</span>
            <div class="rc-title">Laporan Inventaris Alat</div>
            <div class="rc-desc">Status stok, kondisi alat, frekuensi peminjaman, dan alat paling populer dalam periode.</div>
            <div class="rc-meta">
                <span class="rc-tag tag-teal">Stok & kondisi</span>
                <span class="rc-tag tag-teal">Popularitas</span>
                <span class="rc-arrow">→</span>
            </div>
        </a>

        <a href="{{ route('petugas.laporan.rekap') }}" class="report-card rc-rekap">
            <span class="rc-icon">📊</span>
            <div class="rc-title">Rekap Bulanan</div>
            <div class="rc-desc">Ringkasan aktivitas per bulan dalam satu tahun — total pinjam, kembali, denda, dan tren penggunaan.</div>
            <div class="rc-meta">
                <span class="rc-tag tag-purple">Per tahun</span>
                <span class="rc-tag tag-purple">Tren visual</span>
                <span class="rc-arrow">→</span>
            </div>
        </a>
    </div>

    {{-- Quick print shortcuts --}}
    <div class="quick-section">
        <div class="qs-title">⚡ Cetak Cepat</div>
        <div class="quick-grid">
            <a href="{{ route('petugas.laporan.peminjaman', ['dari'=>now()->startOfMonth()->toDateString(),'sampai'=>now()->toDateString()]) }}"
               class="quick-btn">
                <span>📋</span>
                <span>Pinjam Bulan Ini</span>
            </a>
            <a href="{{ route('petugas.laporan.pengembalian', ['dari'=>now()->startOfMonth()->toDateString(),'sampai'=>now()->toDateString()]) }}"
               class="quick-btn">
                <span>↩</span>
                <span>Kembali Bulan Ini</span>
            </a>
            <a href="{{ route('petugas.laporan.peminjaman', ['dari'=>now()->toDateString(),'sampai'=>now()->toDateString(),'status'=>'dipinjam']) }}"
               class="quick-btn">
                <span>📦</span>
                <span>Aktif Dipinjam</span>
            </a>
            <a href="{{ route('petugas.laporan.peminjaman', ['dari'=>now()->subDays(30)->toDateString(),'sampai'=>now()->toDateString(),'status'=>'ditolak']) }}"
               class="quick-btn">
                <span>✕</span>
                <span>Ditolak 30 Hari</span>
            </a>
            <a href="{{ route('petugas.laporan.alat') }}" class="quick-btn">
                <span>🔧</span>
                <span>Inventaris Saat Ini</span>
            </a>
            <a href="{{ route('petugas.laporan.rekap', ['tahun'=>now()->year]) }}" class="quick-btn">
                <span>📊</span>
                <span>Rekap Tahun Ini</span>
            </a>
        </div>
    </div>

</x-petugas-layout>