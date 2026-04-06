<x-admin-layout title="Manajemen Alat" breadcrumb="Alat">

    <x-slot name="header">
        <div>
            <h1 class="page-heading">Manajemen Alat</h1>
            <p class="page-sub">Kelola inventaris alat rumah tangga yang dapat dipinjam.</p>
        </div>
        <a href="{{ route('admin.alats.create') }}" class="btn btn-primary">＋ Tambah Alat</a>
    </x-slot>

    <style>
        /* Stats */
        .alat-stats { display:grid; grid-template-columns:repeat(5,1fr); gap:0.75rem; margin-bottom:1.5rem; }
        .astat {
            background:var(--ink-80); border:1px solid rgba(255,255,255,0.07);
            border-radius:8px; padding:0.9rem 1.1rem;
            display:flex; align-items:center; gap:0.75rem;
        }
        .astat-icon { width:34px;height:34px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0; }
        .astat-val   { font-family:var(--font-display);font-size:1.5rem;font-weight:700;color:var(--cream);line-height:1; }
        .astat-label { font-size:0.68rem;color:var(--mist);margin-top:0.1rem; }

        /* Filter bar */
        .filter-bar { background:var(--ink-80);border:1px solid rgba(255,255,255,0.07);border-radius:8px;padding:1rem 1.2rem;margin-bottom:1.2rem; }
        .filter-row { display:flex;gap:0.75rem;align-items:center;flex-wrap:wrap; }
        .search-wrap {
            display:flex;align-items:center;gap:0.5rem;flex:1;min-width:200px;
            background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.09);
            border-radius:6px;padding:0 0.9rem;transition:border-color 0.2s;
        }
        .search-wrap:focus-within { border-color:rgba(37,99,235,0.5); }
        .search-wrap input { flex:1;background:none;border:none;outline:none;padding:0.62rem 0;color:var(--cream);font-family:var(--font-ui);font-size:0.82rem; }
        .search-wrap input::placeholder { color:var(--slate); }
        .filter-select {
            background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.09);
            border-radius:6px;padding:0.62rem 0.9rem;color:var(--cream);
            font-family:var(--font-ui);font-size:0.8rem;outline:none;cursor:pointer;
            transition:border-color 0.2s; min-width:140px;
        }
        .filter-select:focus { border-color:rgba(37,99,235,0.5); }
        .filter-select option { background:var(--ink-80); }
        .filter-check-label {
            display:flex;align-items:center;gap:0.4rem;padding:0.6rem 0.9rem;
            background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.09);
            border-radius:6px;cursor:pointer;font-size:0.8rem;color:var(--mist);
            transition:all 0.15s;white-space:nowrap;
        }
        .filter-check-label:has(input:checked) { border-color:rgba(37,99,235,0.5);color:var(--accent-l);background:rgba(37,99,235,0.08); }
        .filter-check-label input { display:none; }

        /* Table card */
        .table-card { background:var(--ink-80);border:1px solid rgba(255,255,255,0.07);border-radius:8px;overflow:hidden; }
        .table-head {
            padding:0.85rem 1.3rem;
            border-bottom:1px solid rgba(255,255,255,0.06);
            display:flex;align-items:center;justify-content:space-between;gap:1rem;
        }
        .result-count { font-size:0.78rem;color:var(--mist); }

        /* Alat table */
        .data-table th { background:rgba(255,255,255,0.03); }
        .alat-thumb {
            width:38px;height:38px;border-radius:7px;overflow:hidden;flex-shrink:0;
            background:rgba(37,99,235,0.1);border:1px solid rgba(37,99,235,0.15);
            display:flex;align-items:center;justify-content:center;font-size:1rem;
        }
        .alat-thumb img { width:100%;height:100%;object-fit:cover; }
        .alat-name-col { display:flex;align-items:center;gap:0.75rem; }
        .alat-name-text { font-size:0.85rem;font-weight:700;color:var(--silver); }
        .alat-kode-text { font-size:0.68rem;color:var(--slate);font-family:monospace;margin-top:0.1rem; }

        .stock-bar-wrap { display:flex;align-items:center;gap:0.5rem; }
        .stock-bar { width:50px;height:5px;background:rgba(255,255,255,0.08);border-radius:10px;overflow:hidden; }
        .stock-bar-fill { height:100%;border-radius:10px;transition:width 0.3s; }
        .stock-text { font-size:0.78rem;font-weight:700; }

        .price-val { font-size:0.82rem;font-weight:700;color:var(--silver); }
        .price-denda { font-size:0.68rem;color:var(--mist); }

        .row-actions { display:flex;gap:0.3rem; }

        /* Kondisi badges */
        .kond-baik         { background:rgba(16,185,129,0.12);color:#34D399;border:1px solid rgba(16,185,129,0.2); }
        .kond-rusak_ringan { background:rgba(212,168,67,0.12);color:#FCD34D;border:1px solid rgba(212,168,67,0.2); }
        .kond-rusak_berat  { background:rgba(239,68,68,0.12);color:#FCA5A5;border:1px solid rgba(239,68,68,0.2); }
        .kond-perbaikan    { background:rgba(59,130,246,0.12);color:#93C5FD;border:1px solid rgba(59,130,246,0.2); }

        /* Empty */
        .empty-row td { text-align:center;padding:3rem;color:var(--mist);font-size:0.85rem; }

        /* Pagination */
        .pag-wrap { display:flex;align-items:center;justify-content:space-between;padding:0.9rem 1.3rem;border-top:1px solid rgba(255,255,255,0.06); }
        .pag-info { font-size:0.75rem;color:var(--mist); }
        .pag-links { display:flex;gap:0.3rem; }
        .pag-link { width:30px;height:30px;border-radius:5px;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:600;text-decoration:none;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);color:var(--mist);transition:all 0.15s; }
        .pag-link:hover,.pag-link.active { background:var(--accent);color:white;border-color:var(--accent); }
        .pag-link.disabled { opacity:0.3;pointer-events:none; }

        @media(max-width:1100px) { .alat-stats { grid-template-columns:repeat(3,1fr); } }
        @media(max-width:768px)  { .alat-stats { grid-template-columns:1fr 1fr; } .filter-row { flex-direction:column; } }
    </style>

    {{-- Stats --}}
    <div class="alat-stats">
        <div class="astat">
            <div class="astat-icon" style="background:rgba(37,99,235,0.12)">🛠</div>
            <div><div class="astat-val">{{ $stats['total'] }}</div><div class="astat-label">Total Alat</div></div>
        </div>
        <div class="astat">
            <div class="astat-icon" style="background:rgba(16,185,129,0.12)">✅</div>
            <div><div class="astat-val">{{ $stats['aktif'] }}</div><div class="astat-label">Aktif</div></div>
        </div>
        <div class="astat">
            <div class="astat-icon" style="background:rgba(59,130,246,0.12)">📦</div>
            <div><div class="astat-val">{{ $stats['tersedia'] }}</div><div class="astat-label">Tersedia</div></div>
        </div>
        <div class="astat">
            <div class="astat-icon" style="background:rgba(239,68,68,0.1)">⚠</div>
            <div>
                <div class="astat-val" style="{{ $stats['habis'] > 0 ? 'color:var(--danger)' : '' }}">{{ $stats['habis'] }}</div>
                <div class="astat-label">Stok Habis</div>
            </div>
        </div>
        <div class="astat">
            <div class="astat-icon" style="background:rgba(212,168,67,0.12)">🔧</div>
            <div>
                <div class="astat-val" style="{{ $stats['rusak'] > 0 ? 'color:var(--gold)' : '' }}">{{ $stats['rusak'] }}</div>
                <div class="astat-label">Perlu Perhatian</div>
            </div>
        </div>
    </div>

    {{-- Filter bar --}}
    <form method="GET" action="{{ route('admin.alats.index') }}">
        <div class="filter-bar">
            <div class="filter-row">
                <div class="search-wrap">
                    <span style="color:var(--slate)">⌕</span>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama, kode, atau merk...">
                </div>

                <select name="kategori" class="filter-select">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>
                            {{ $k->ikon }} {{ $k->nama }}
                        </option>
                    @endforeach
                </select>

                <select name="status" class="filter-select">
                    <option value="">Semua Status</option>
                    <option value="aktif"    {{ request('status')=='aktif'    ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status')=='nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>

                <select name="kondisi" class="filter-select">
                    <option value="">Semua Kondisi</option>
                    <option value="baik"         {{ request('kondisi')=='baik'         ? 'selected' : '' }}>Baik</option>
                    <option value="rusak_ringan"  {{ request('kondisi')=='rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="rusak_berat"   {{ request('kondisi')=='rusak_berat'  ? 'selected' : '' }}>Rusak Berat</option>
                    <option value="perbaikan"     {{ request('kondisi')=='perbaikan'    ? 'selected' : '' }}>Dalam Perbaikan</option>
                </select>

                <label class="filter-check-label">
                    <input type="checkbox" name="tersedia" value="1" {{ request('tersedia') ? 'checked' : '' }}>
                    📦 Tersedia saja
                </label>

                <button type="submit" class="btn btn-ghost">Cari</button>
                @if(request()->hasAny(['search','kategori','status','kondisi','tersedia']))
                    <a href="{{ route('admin.alats.index') }}" class="btn btn-ghost">✕</a>
                @endif
            </div>
        </div>
    </form>

    {{-- Table --}}
    <div class="table-card">
        <div class="table-head">
            <span class="card-title">Daftar Alat</span>
            <span class="result-count">{{ $alats->total() }} alat ditemukan</span>
        </div>

        <div style="overflow-x:auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:40%">Alat</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Harga / Hari</th>
                        <th>Kondisi</th>
                        <th>Status</th>
                        <th style="text-align:right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alats as $alat)
                        <tr>
                            <td>
                                <div class="alat-name-col">
                                    <div class="alat-thumb">
                                        @if($alat->foto)
                                            <img src="{{ asset('storage/'.$alat->foto) }}" alt="{{ $alat->nama }}">
                                        @else
                                            🔧
                                        @endif
                                    </div>
                                    <div>
                                        <div class="alat-name-text">{{ $alat->nama }}</div>
                                        <div class="alat-kode-text">
                                            {{ $alat->kode }}
                                            @if($alat->merk) · {{ $alat->merk }} @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span style="font-size:0.8rem;color:var(--silver)">
                                    {{ $alat->kategori->ikon ?? '' }} {{ $alat->kategori->nama ?? '—' }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $pct = $alat->stok_total > 0
                                        ? ($alat->stok_tersedia / $alat->stok_total * 100)
                                        : 0;
                                    $barColor = $pct == 0 ? '#EF4444' : ($pct < 30 ? '#D4A843' : '#10B981');
                                @endphp
                                <div class="stock-bar-wrap">
                                    <div class="stock-bar">
                                        <div class="stock-bar-fill"
                                             style="width:{{ $pct }}%;background:{{ $barColor }}"></div>
                                    </div>
                                    <span class="stock-text" style="color:{{ $barColor }}">
                                        {{ $alat->stok_tersedia }}/{{ $alat->stok_total }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="price-val">Rp {{ number_format($alat->harga_sewa_per_hari,0,',','.') }}</div>
                                @if($alat->denda_per_hari > 0)
                                    <div class="price-denda">Denda: Rp {{ number_format($alat->denda_per_hari,0,',','.') }}/hr</div>
                                @endif
                            </td>
                            <td>
                                @php
                                    $kondisiLabel = ['baik'=>'Baik','rusak_ringan'=>'Rusak Ringan','rusak_berat'=>'Rusak Berat','perbaikan'=>'Perbaikan'];
                                @endphp
                                <span class="badge kond-{{ $alat->kondisi }}">
                                    {{ $kondisiLabel[$alat->kondisi] ?? $alat->kondisi }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $alat->status === 'aktif' ? 'badge-green' : 'badge-slate' }}">
                                    {{ ucfirst($alat->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="row-actions" style="justify-content:flex-end">
                                    <a href="{{ route('admin.alats.show', $alat) }}" class="btn btn-ghost btn-sm">Detail</a>
                                    <a href="{{ route('admin.alats.edit', $alat) }}" class="btn btn-primary btn-sm">Edit</a>
                                    @if($alat->peminjaman_aktif_count == 0)
                                        <form method="POST" action="{{ route('admin.alats.destroy', $alat) }}"
                                              onsubmit="return confirm('Hapus alat {{ $alat->nama }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="empty-row">
                            <td colspan="7">
                                <div style="font-size:2rem;margin-bottom:0.5rem">🔍</div>
                                Tidak ada alat ditemukan.<br>
                                <a href="{{ route('admin.alats.create') }}"
                                   style="color:var(--accent-l);margin-top:0.4rem;display:inline-block">
                                    + Tambah alat baru
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($alats->hasPages())
            <div class="pag-wrap">
                <span class="pag-info">
                    {{ $alats->firstItem() }}–{{ $alats->lastItem() }} dari {{ $alats->total() }}
                </span>
                <div class="pag-links">
                    <a href="{{ $alats->previousPageUrl() }}" class="pag-link {{ $alats->onFirstPage() ? 'disabled' : '' }}">‹</a>
                    @foreach($alats->getUrlRange(1, $alats->lastPage()) as $page => $url)
                        <a href="{{ $url }}" class="pag-link {{ $page == $alats->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                    @endforeach
                    <a href="{{ $alats->nextPageUrl() }}" class="pag-link {{ !$alats->hasMorePages() ? 'disabled' : '' }}">›</a>
                </div>
            </div>
        @endif
    </div>

</x-admin-layout>