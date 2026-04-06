<x-admin-layout title="Data Pengembalian" breadcrumb="Pengembalian">

    <x-slot name="header">
        <div>
            <h1 class="page-heading">Data Pengembalian</h1>
            <p class="page-sub">Riwayat seluruh pengembalian alat beserta kondisi dan tagihan.</p>
        </div>
        <a href="{{ route('admin.pengembalians.create') }}" class="btn btn-primary">＋ Proses Pengembalian</a>
    </x-slot>

    <style>
        /* Stats */
        .pg-stats { display:grid;grid-template-columns:repeat(5,1fr);gap:0.75rem;margin-bottom:1.5rem; }
        .pgs {
            background:var(--ink-80);border:1px solid rgba(255,255,255,0.07);
            border-radius:8px;padding:1rem 1.1rem;
        }
        .pgs-icon { font-size:1.2rem;margin-bottom:0.4rem; }
        .pgs-val  { font-family:var(--font-display);font-size:1.7rem;font-weight:700;color:var(--cream);line-height:1; }
        .pgs-label { font-size:0.68rem;color:var(--mist);margin-top:0.2rem; }

        /* Filter */
        .filter-bar { background:var(--ink-80);border:1px solid rgba(255,255,255,0.07);border-radius:8px;padding:0.9rem 1.1rem;margin-bottom:1.1rem; }
        .filter-row { display:flex;gap:0.6rem;flex-wrap:wrap;align-items:center; }
        .search-wrap { display:flex;align-items:center;gap:0.5rem;flex:1;min-width:200px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:6px;padding:0 0.9rem; }
        .search-wrap:focus-within { border-color:rgba(37,99,235,0.4); }
        .search-wrap input { flex:1;background:none;border:none;outline:none;padding:0.62rem 0;color:var(--cream);font-size:0.82rem;font-family:var(--font-ui); }
        .search-wrap input::placeholder { color:var(--slate); }
        .filter-select { background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:6px;padding:0.62rem 0.9rem;color:var(--cream);font-family:var(--font-ui);font-size:0.8rem;outline:none;min-width:150px; }
        .filter-select option { background:var(--ink-80); }
        .filter-chip { display:flex;align-items:center;gap:0.4rem;padding:0.6rem 0.9rem;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:6px;cursor:pointer;font-size:0.8rem;color:var(--mist);white-space:nowrap;transition:all 0.15s; }
        .filter-chip:has(input:checked) { border-color:rgba(239,68,68,0.5);color:#FCA5A5;background:rgba(239,68,68,0.07); }
        .filter-chip input { display:none; }

        /* Table card */
        .table-card { background:var(--ink-80);border:1px solid rgba(255,255,255,0.07);border-radius:8px;overflow:hidden; }
        .table-head { padding:0.85rem 1.2rem;border-bottom:1px solid rgba(255,255,255,0.06);display:flex;align-items:center;justify-content:space-between; }

        /* Kondisi badges */
        .kond-baik         { background:rgba(16,185,129,0.12);color:#34D399;border:1px solid rgba(16,185,129,0.2); }
        .kond-rusak_ringan { background:rgba(212,168,67,0.12);color:#FCD34D;border:1px solid rgba(212,168,67,0.2); }
        .kond-rusak_sedang { background:rgba(249,115,22,0.12);color:#FCA5A5;border:1px solid rgba(249,115,22,0.2); }
        .kond-rusak_berat  { background:rgba(239,68,68,0.12);color:#FCA5A5;border:1px solid rgba(239,68,68,0.2); }
        .kond-hilang       { background:rgba(139,92,246,0.12);color:#C4B5FD;border:1px solid rgba(139,92,246,0.2); }

        /* On-time vs late indicator */
        .time-good { color:#34D399;font-weight:700;font-size:0.78rem; }
        .time-late { color:#FCA5A5;font-weight:700;font-size:0.78rem; }

        /* User/alat cells */
        .user-cell { display:flex;align-items:center;gap:0.6rem; }
        .user-av   { width:30px;height:30px;border-radius:7px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:800;color:white;background:linear-gradient(135deg,#10B981,#06B6D4); }
        .alat-cell { display:flex;align-items:center;gap:0.6rem; }
        .alat-sm   { width:30px;height:30px;border-radius:6px;overflow:hidden;flex-shrink:0;background:rgba(37,99,235,0.1);display:flex;align-items:center;justify-content:center;font-size:0.75rem; }
        .alat-sm img { width:100%;height:100%;object-fit:cover; }

        .nomor-badge { font-family:monospace;font-size:0.72rem;font-weight:700;color:var(--accent-l); }

        /* Denda highlight */
        .denda-none { color:var(--jade);font-weight:700;font-size:0.82rem; }
        .denda-has  { color:#FCA5A5;font-weight:700;font-size:0.82rem; }

        /* Pagination */
        .pag-wrap { display:flex;align-items:center;justify-content:space-between;padding:0.85rem 1.2rem;border-top:1px solid rgba(255,255,255,0.06); }
        .pag-info { font-size:0.75rem;color:var(--mist); }
        .pag-links { display:flex;gap:0.25rem; }
        .pag-link { width:30px;height:30px;border-radius:5px;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:600;text-decoration:none;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);color:var(--mist);transition:all 0.15s; }
        .pag-link:hover,.pag-link.active { background:var(--accent);color:white;border-color:var(--accent); }
        .pag-link.disabled { opacity:0.3;pointer-events:none; }

        @media(max-width:1100px) { .pg-stats { grid-template-columns:repeat(3,1fr); } }
        @media(max-width:768px)  { .pg-stats { grid-template-columns:1fr 1fr; } }
    </style>

    {{-- Stats --}}
    <div class="pg-stats">
        <div class="pgs">
            <div class="pgs-icon">↩</div>
            <div class="pgs-val">{{ $stats['total'] }}</div>
            <div class="pgs-label">Total Pengembalian</div>
        </div>
        <div class="pgs">
            <div class="pgs-icon">✅</div>
            <div class="pgs-val" style="color:var(--jade)">{{ $stats['tepat_waktu'] }}</div>
            <div class="pgs-label">Tepat Waktu</div>
        </div>
        <div class="pgs">
            <div class="pgs-icon">⏰</div>
            <div class="pgs-val" style="{{ $stats['terlambat'] > 0 ? 'color:var(--gold)' : '' }}">{{ $stats['terlambat'] }}</div>
            <div class="pgs-label">Terlambat</div>
        </div>
        <div class="pgs">
            <div class="pgs-icon">🔧</div>
            <div class="pgs-val" style="{{ $stats['rusak'] > 0 ? 'color:var(--danger)' : '' }}">{{ $stats['rusak'] }}</div>
            <div class="pgs-label">Kondisi Bermasalah</div>
        </div>
        <div class="pgs">
            <div class="pgs-icon">💰</div>
            <div class="pgs-val" style="font-size:1.1rem;color:var(--jade)">
                Rp {{ number_format($stats['total_denda']/1000,0)}}rb
            </div>
            <div class="pgs-label">Total Tagihan</div>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.pengembalians.index') }}">
        <div class="filter-bar">
            <div class="filter-row">
                <div class="search-wrap">
                    <span style="color:var(--slate)">⌕</span>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nomor transaksi, peminjam, atau alat...">
                </div>
                <select name="kondisi" class="filter-select">
                    <option value="">Semua Kondisi</option>
                    @foreach(['baik'=>'Baik','rusak_ringan'=>'Rusak Ringan','rusak_sedang'=>'Rusak Sedang','rusak_berat'=>'Rusak Berat','hilang'=>'Hilang'] as $val=>$lbl)
                        <option value="{{ $val }}" {{ request('kondisi')==$val ? 'selected':'' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
                <label class="filter-chip">
                    <input type="checkbox" name="terlambat" value="1" {{ request('terlambat') ? 'checked':'' }}>
                    ⏰ Terlambat saja
                </label>
                <label class="filter-chip">
                    <input type="checkbox" name="rusak" value="1" {{ request('rusak') ? 'checked':'' }}>
                    🔧 Kondisi rusak
                </label>
                <button type="submit" class="btn btn-ghost">Cari</button>
                @if(request()->hasAny(['search','kondisi','terlambat','rusak']))
                    <a href="{{ route('admin.pengembalians.index') }}" class="btn btn-ghost">✕</a>
                @endif
            </div>
        </div>
    </form>

    {{-- Table --}}
    <div class="table-card">
        <div class="table-head">
            <span class="card-title">Riwayat Pengembalian</span>
            <span style="font-size:0.75rem;color:var(--mist)">{{ $pengembalians->total() }} data</span>
        </div>
        <div style="overflow-x:auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No. Transaksi</th>
                        <th>Peminjam</th>
                        <th>Alat</th>
                        <th>Tgl Kembali Aktual</th>
                        <th>Keterlambatan</th>
                        <th>Kondisi</th>
                        <th>Total Tagihan</th>
                        <th style="text-align:right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengembalians as $pg)
                        <tr style="{{ $pg->is_rusak ? 'background:rgba(239,68,68,0.03)' : '' }}">
                            <td>
                                <div class="nomor-badge">
                                    {{ $pg->peminjaman->nomor_pinjam ?? '—' }}
                                </div>
                                <div style="font-size:0.68rem;color:var(--slate)">
                                    {{ $pg->created_at->format('d M Y') }}
                                </div>
                            </td>
                            <td>
                                <div class="user-cell">
                                    <div class="user-av">{{ strtoupper(substr($pg->peminjaman->peminjam->name??'?',0,2)) }}</div>
                                    <div>
                                        <div style="font-size:0.82rem;font-weight:700;color:var(--silver)">{{ $pg->peminjaman->peminjam->name ?? '—' }}</div>
                                        <div style="font-size:0.68rem;color:var(--slate)">{{ $pg->peminjaman->peminjam->email ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="alat-cell">
                                    <div class="alat-sm">
                                        @if($pg->peminjaman->alat?->foto)
                                            <img src="{{ asset('storage/'.$pg->peminjaman->alat->foto) }}" alt="">
                                        @else 🔧 @endif
                                    </div>
                                    <div>
                                        <div style="font-size:0.82rem;font-weight:700;color:var(--silver)">{{ $pg->peminjaman->alat->nama ?? '—' }}</div>
                                        <div style="font-size:0.68rem;color:var(--slate)">
                                            {{ $pg->peminjaman->jumlah }}x · {{ $pg->peminjaman->alat->kode ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-size:0.82rem;font-weight:700;color:var(--silver)">
                                    {{ $pg->tanggal_kembali_aktual->format('d M Y') }}
                                </div>
                                <div style="font-size:0.68rem;color:var(--mist)">
                                    Rencana: {{ $pg->peminjaman->tanggal_kembali_rencana->format('d M Y') }}
                                </div>
                            </td>
                            <td>
                                @if($pg->is_tepat_waktu)
                                    <span class="time-good">✓ Tepat Waktu</span>
                                @else
                                    <span class="time-late">⏰ +{{ $pg->keterlambatan_hari }} hari</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $kondisiLabels = ['baik'=>'Baik','rusak_ringan'=>'Rusak Ringan','rusak_sedang'=>'Rusak Sedang','rusak_berat'=>'Rusak Berat','hilang'=>'Hilang'];
                                @endphp
                                <span class="badge kond-{{ $pg->kondisi_kembali }}">
                                    {{ $kondisiLabels[$pg->kondisi_kembali] ?? $pg->kondisi_kembali }}
                                </span>
                            </td>
                            <td>
                                @if($pg->total_tagihan > 0)
                                    <div class="denda-has">Rp {{ number_format($pg->total_tagihan,0,',','.') }}</div>
                                    @if($pg->denda > 0)
                                        <div style="font-size:0.65rem;color:var(--mist)">Denda: Rp {{ number_format($pg->denda,0,',','.') }}</div>
                                    @endif
                                @else
                                    <span class="denda-none">Rp 0</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex;gap:0.3rem;justify-content:flex-end">
                                    <a href="{{ route('admin.pengembalians.show', $pg) }}" class="btn btn-ghost btn-sm">Detail</a>
                                    <a href="{{ route('admin.pengembalians.edit', $pg) }}" class="btn btn-primary btn-sm">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;padding:3rem;color:var(--mist)">
                                <div style="font-size:2rem;margin-bottom:0.5rem">↩</div>
                                Belum ada data pengembalian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pengembalians->hasPages())
            <div class="pag-wrap">
                <span class="pag-info">{{ $pengembalians->firstItem() }}–{{ $pengembalians->lastItem() }} dari {{ $pengembalians->total() }}</span>
                <div class="pag-links">
                    <a href="{{ $pengembalians->previousPageUrl() }}" class="pag-link {{ $pengembalians->onFirstPage() ? 'disabled':'' }}">‹</a>
                    @foreach($pengembalians->getUrlRange(1, $pengembalians->lastPage()) as $pg => $url)
                        <a href="{{ $url }}" class="pag-link {{ $pg == $pengembalians->currentPage() ? 'active':'' }}">{{ $pg }}</a>
                    @endforeach
                    <a href="{{ $pengembalians->nextPageUrl() }}" class="pag-link {{ !$pengembalians->hasMorePages() ? 'disabled':'' }}">›</a>
                </div>
            </div>
        @endif
    </div>

</x-admin-layout>