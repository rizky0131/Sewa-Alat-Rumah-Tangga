<x-admin-layout title="Kategori Alat" breadcrumb="Kategori">

    <x-slot name="header">
        <div>
            <h1 class="page-heading">Kategori Alat</h1>
            <p class="page-sub">Kelola pengelompokan alat berdasarkan kategori.</p>
        </div>
        <a href="{{ route('admin.kategoris.create') }}" class="btn btn-primary">
            <span>＋</span> Tambah Kategori
        </a>
    </x-slot>

    <style>
        /* ── Stats row ────────────────────────────── */
        .k-stats { display: grid; grid-template-columns: repeat(4,1fr); gap: 1rem; margin-bottom: 1.75rem; }
        .k-stat {
            background: var(--ink-80); border: 1px solid rgba(255,255,255,0.07);
            border-radius: 8px; padding: 1.1rem 1.3rem;
            display: flex; align-items: center; gap: 0.9rem;
        }
        .k-stat-icon {
            width: 38px; height: 38px; border-radius: 8px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center; font-size: 1.1rem;
        }
        .k-stat-val   { font-family: var(--font-display); font-size: 1.7rem; font-weight: 700; color: var(--cream); line-height: 1; }
        .k-stat-label { font-size: 0.72rem; color: var(--mist); margin-top: 0.15rem; }

        /* ── Toolbar ──────────────────────────────── */
        .toolbar { display: flex; gap: 0.75rem; align-items: center; margin-bottom: 1.2rem; flex-wrap: wrap; }
        .search-wrap {
            display: flex; align-items: center; gap: 0.5rem; flex: 1; min-width: 220px;
            background: var(--ink-80); border: 1px solid rgba(255,255,255,0.08);
            border-radius: 6px; padding: 0 0.9rem; transition: border-color 0.2s;
        }
        .search-wrap:focus-within { border-color: rgba(37,99,235,0.5); }
        .search-wrap input {
            flex: 1; background: none; border: none; outline: none; padding: 0.65rem 0;
            color: var(--cream); font-family: var(--font-ui); font-size: 0.83rem;
        }
        .search-wrap input::placeholder { color: var(--slate); }
        .filter-tabs { display: flex; gap: 0.35rem; }
        .filter-tab {
            padding: 0.5rem 1rem; border-radius: 5px; font-size: 0.77rem; font-weight: 600;
            border: 1px solid rgba(255,255,255,0.08); color: var(--mist);
            background: var(--ink-80); text-decoration: none; transition: all 0.15s;
        }
        .filter-tab:hover  { color: var(--cream); border-color: rgba(255,255,255,0.18); }
        .filter-tab.active { background: var(--accent); color: white; border-color: var(--accent); }

        /* ── Category grid ────────────────────────── */
        .cat-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1rem; }

        .cat-card {
            background: var(--ink-80); border: 1px solid rgba(255,255,255,0.07);
            border-radius: 10px; overflow: hidden;
            transition: border-color 0.2s, transform 0.2s;
            display: flex; flex-direction: column;
        }
        .cat-card:hover { border-color: rgba(37,99,235,0.4); transform: translateY(-2px); }
        .cat-card.nonaktif { opacity: 0.6; }

        /* Color accent top strip — derived from hash of name */
        .cat-card-top {
            padding: 1.4rem 1.4rem 1rem;
            display: flex; align-items: flex-start; gap: 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .cat-icon-bubble {
            width: 52px; height: 52px; border-radius: 12px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center; font-size: 1.6rem;
            background: linear-gradient(135deg, rgba(37,99,235,0.15), rgba(37,99,235,0.05));
            border: 1px solid rgba(37,99,235,0.2);
        }
        .cat-name { font-size: 0.95rem; font-weight: 700; color: var(--cream); line-height: 1.3; }
        .cat-slug { font-size: 0.7rem; color: var(--slate); font-family: monospace; margin-top: 0.2rem; }
        .cat-desc { font-size: 0.75rem; color: var(--mist); margin-top: 0.35rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

        .cat-card-body { padding: 0.9rem 1.4rem; flex: 1; }
        .cat-meta-row { display: flex; gap: 0.5rem; align-items: center; }
        .cat-meta-item {
            flex: 1; background: rgba(255,255,255,0.04); border-radius: 6px;
            padding: 0.55rem; text-align: center;
        }
        .cat-meta-val   { font-family: var(--font-display); font-size: 1.3rem; font-weight: 700; color: var(--cream); }
        .cat-meta-label { font-size: 0.62rem; color: var(--mist); margin-top: 0.1rem; }

        .cat-card-footer {
            padding: 0.9rem 1.4rem;
            border-top: 1px solid rgba(255,255,255,0.06);
            display: flex; gap: 0.4rem; align-items: center;
        }
        .cat-card-footer .btn { flex: 1; justify-content: center; }

        /* Toggle switch */
        .toggle-form { display: inline-flex; }
        .toggle-switch {
            position: relative; width: 34px; height: 20px;
            background: var(--slate); border-radius: 100px; cursor: pointer;
            border: none; transition: background 0.2s; flex-shrink: 0;
        }
        .toggle-switch::after {
            content: ''; position: absolute;
            top: 3px; left: 3px;
            width: 14px; height: 14px; border-radius: 50%;
            background: white; transition: transform 0.2s;
        }
        .toggle-switch.on { background: var(--jade); }
        .toggle-switch.on::after { transform: translateX(14px); }

        /* Empty state */
        .empty-state { text-align: center; padding: 3.5rem 1rem; }
        .empty-icon  { font-size: 3rem; margin-bottom: 0.75rem; }
        .empty-title { font-size: 0.95rem; font-weight: 700; color: var(--silver); }
        .empty-sub   { font-size: 0.8rem; color: var(--mist); margin-top: 0.3rem; }

        /* Pagination */
        .pag-wrap { display: flex; align-items: center; justify-content: space-between; margin-top: 1.5rem; }
        .pag-info { font-size: 0.78rem; color: var(--mist); }
        .pag-links { display: flex; gap: 0.3rem; }
        .pag-link {
            width: 32px; height: 32px; border-radius: 5px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.78rem; font-weight: 600; text-decoration: none;
            background: var(--ink-80); border: 1px solid rgba(255,255,255,0.08);
            color: var(--mist); transition: all 0.15s;
        }
        .pag-link:hover, .pag-link.active { background: var(--accent); color: white; border-color: var(--accent); }
        .pag-link.disabled { opacity: 0.3; pointer-events: none; }

        @media(max-width:768px) {
            .k-stats { grid-template-columns: 1fr 1fr; }
            .cat-grid { grid-template-columns: 1fr; }
        }
    </style>

    {{-- Stats row --}}
    <div class="k-stats">
        <div class="k-stat">
            <div class="k-stat-icon" style="background:rgba(37,99,235,0.12)">🏷</div>
            <div>
                <div class="k-stat-val">{{ $stats['total'] }}</div>
                <div class="k-stat-label">Total Kategori</div>
            </div>
        </div>
        <div class="k-stat">
            <div class="k-stat-icon" style="background:rgba(16,185,129,0.12)">✅</div>
            <div>
                <div class="k-stat-val">{{ $stats['aktif'] }}</div>
                <div class="k-stat-label">Aktif</div>
            </div>
        </div>
        <div class="k-stat">
            <div class="k-stat-icon" style="background:rgba(239,68,68,0.1)">⏸</div>
            <div>
                <div class="k-stat-val">{{ $stats['nonaktif'] }}</div>
                <div class="k-stat-label">Nonaktif</div>
            </div>
        </div>
        <div class="k-stat">
            <div class="k-stat-icon" style="background:rgba(212,168,67,0.12)">🛠</div>
            <div>
                <div class="k-stat-val">{{ $stats['total_alat'] }}</div>
                <div class="k-stat-label">Total Alat</div>
            </div>
        </div>
    </div>

    {{-- Toolbar --}}
    <form method="GET" action="{{ route('admin.kategoris.index') }}">
        <div class="toolbar">
            <div class="search-wrap">
                <span style="color:var(--slate);font-size:0.85rem">⌕</span>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama atau deskripsi...">
            </div>
            <div class="filter-tabs">
                <a href="{{ route('admin.kategoris.index', request()->except(['status','page'])) }}"
                   class="filter-tab {{ !request('status') ? 'active' : '' }}">Semua</a>
                <a href="{{ route('admin.kategoris.index', array_merge(request()->except('page'), ['status'=>'aktif'])) }}"
                   class="filter-tab {{ request('status')=='aktif' ? 'active' : '' }}">Aktif</a>
                <a href="{{ route('admin.kategoris.index', array_merge(request()->except('page'), ['status'=>'nonaktif'])) }}"
                   class="filter-tab {{ request('status')=='nonaktif' ? 'active' : '' }}">Nonaktif</a>
            </div>
            <button type="submit" class="btn btn-ghost">Cari</button>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.kategoris.index') }}" class="btn btn-ghost">✕ Reset</a>
            @endif
        </div>
    </form>

    {{-- Category grid --}}
    @if($kategoris->isEmpty())
        <div class="card">
            <div class="empty-state">
                <div class="empty-icon">🏷</div>
                <div class="empty-title">Belum ada kategori ditemukan</div>
                <div class="empty-sub">
                    @if(request('search')) Tidak ada hasil untuk "{{ request('search') }}"
                    @else Mulai dengan menambahkan kategori pertama
                    @endif
                </div>
                <a href="{{ route('admin.kategoris.create') }}" class="btn btn-primary" style="margin-top:1.2rem;display:inline-flex">
                    + Tambah Kategori
                </a>
            </div>
        </div>
    @else
        <div class="cat-grid">
            @foreach($kategoris as $kategori)
                <div class="cat-card {{ !$kategori->is_aktif ? 'nonaktif' : '' }}">
                    <div class="cat-card-top">
                        <div class="cat-icon-bubble">
                            {{ $kategori->ikon ?? '🏷' }}
                        </div>
                        <div style="flex:1;min-width:0">
                            <div style="display:flex;align-items:center;gap:0.5rem;flex-wrap:wrap">
                                <span class="cat-name">{{ $kategori->nama }}</span>
                                @if(!$kategori->is_aktif)
                                    <span class="badge badge-slate" style="font-size:0.6rem">Nonaktif</span>
                                @endif
                            </div>
                            <div class="cat-slug">{{ $kategori->slug }}</div>
                            @if($kategori->deskripsi)
                                <div class="cat-desc">{{ $kategori->deskripsi }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="cat-card-body">
                        <div class="cat-meta-row">
                            <div class="cat-meta-item">
                                <div class="cat-meta-val">{{ $kategori->alats_count }}</div>
                                <div class="cat-meta-label">Total Alat</div>
                            </div>
                            <div class="cat-meta-item">
                                <div class="cat-meta-val" style="{{ $kategori->alat_aktif_count > 0 ? 'color:var(--jade)' : '' }}">
                                    {{ $kategori->alat_aktif_count }}
                                </div>
                                <div class="cat-meta-label">Aktif</div>
                            </div>
                            <div class="cat-meta-item">
                                <div class="cat-meta-val">{{ $kategori->total_stok ?? 0 }}</div>
                                <div class="cat-meta-label">Stok Tersedia</div>
                            </div>
                        </div>
                    </div>

                    <div class="cat-card-footer">
                        <a href="{{ route('admin.kategoris.show', $kategori) }}" class="btn btn-ghost btn-sm">Detail</a>
                        <a href="{{ route('admin.kategoris.edit', $kategori) }}" class="btn btn-primary btn-sm">Edit</a>

                        {{-- Toggle aktif/nonaktif --}}
                        <form method="POST" action="{{ route('admin.kategoris.update', $kategori) }}" class="toggle-form">
                            @csrf @method('PUT')
                            <input type="hidden" name="nama"      value="{{ $kategori->nama }}">
                            <input type="hidden" name="slug"      value="{{ $kategori->slug }}">
                            <input type="hidden" name="ikon"      value="{{ $kategori->ikon }}">
                            <input type="hidden" name="deskripsi" value="{{ $kategori->deskripsi }}">
                            <input type="hidden" name="is_aktif"  value="{{ $kategori->is_aktif ? '0' : '1' }}">
                            <button type="submit" title="{{ $kategori->is_aktif ? 'Nonaktifkan' : 'Aktifkan' }}"
                                    class="toggle-switch {{ $kategori->is_aktif ? 'on' : '' }}">
                            </button>
                        </form>

                        @if($kategori->alats_count === 0)
                            <form method="POST" action="{{ route('admin.kategoris.destroy', $kategori) }}"
                                  onsubmit="return confirm('Hapus kategori {{ $kategori->nama }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="pag-wrap">
            <span class="pag-info">
                Menampilkan {{ $kategoris->firstItem() }}–{{ $kategoris->lastItem() }}
                dari {{ $kategoris->total() }} kategori
            </span>
            <div class="pag-links">
                <a href="{{ $kategoris->previousPageUrl() }}"
                   class="pag-link {{ $kategoris->onFirstPage() ? 'disabled' : '' }}">‹</a>
                @foreach($kategoris->getUrlRange(1, $kategoris->lastPage()) as $page => $url)
                    <a href="{{ $url }}" class="pag-link {{ $page == $kategoris->currentPage() ? 'active' : '' }}">
                        {{ $page }}
                    </a>
                @endforeach
                <a href="{{ $kategoris->nextPageUrl() }}"
                   class="pag-link {{ !$kategoris->hasMorePages() ? 'disabled' : '' }}">›</a>
            </div>
        </div>
    @endif

</x-admin-layout>