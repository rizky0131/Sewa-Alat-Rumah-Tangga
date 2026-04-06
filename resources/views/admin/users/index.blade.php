<x-admin-layout title="Manajemen User" breadcrumb="Manajemen User">

    {{-- ── PAGE HEADER ─────────────────────────────── --}}
    <x-slot name="header">
        <div>
            <h1 class="page-heading">Manajemen User</h1>
            <p class="page-sub">Kelola semua pengguna sistem SewaAlat.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <span>＋</span> Tambah User
        </a>
    </x-slot>

    <style>
        /* ── Stats Row ───────────────────────────────── */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.75rem;
        }
        .mini-stat {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.1rem 1.2rem;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            box-shadow: var(--shadow-sm);
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .mini-stat:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-1px);
        }
        .mini-stat-icon {
            width: 42px; height: 42px;
            border-radius: var(--radius);
            flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
        }
        .mini-stat-val {
            font-family: var(--font-display);
            font-size: 1.75rem; font-weight: 700;
            color: var(--text-primary); line-height: 1;
        }
        .mini-stat-label {
            font-size: 0.72rem;
            color: var(--text-muted);
            margin-top: 0.15rem;
        }

        /* ── Toolbar ─────────────────────────────────── */
        .toolbar {
            display: flex; gap: 0.65rem;
            align-items: center;
            margin-bottom: 1.2rem;
            flex-wrap: wrap;
        }
        .search-wrap {
            display: flex; align-items: center; gap: 0.5rem;
            flex: 1; min-width: 220px;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 0 0.85rem;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .search-wrap:focus-within {
            border-color: var(--green);
            box-shadow: 0 0 0 3px rgba(45,122,79,0.1);
        }
        .search-wrap input {
            flex: 1; background: none; border: none; outline: none;
            padding: 0.62rem 0;
            color: var(--text-primary);
            font-family: var(--font-ui); font-size: 0.83rem;
        }
        .search-wrap input::placeholder { color: var(--text-muted); }
        .search-icon { color: var(--text-muted); font-size: 0.85rem; }

        .filter-tabs { display: flex; gap: 0.3rem; }
        .filter-tab {
            padding: 0.48rem 0.9rem;
            border-radius: var(--radius);
            font-size: 0.77rem; font-weight: 600;
            border: 1px solid var(--border);
            color: var(--text-secondary);
            background: var(--white);
            text-decoration: none;
            transition: all 0.15s;
        }
        .filter-tab:hover {
            color: var(--text-primary);
            border-color: var(--border-2);
            background: var(--surface);
        }
        .filter-tab.active {
            background: var(--green);
            color: white;
            border-color: var(--green);
        }

        /* ── User Cards Grid ─────────────────────────── */
        .users-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(285px, 1fr));
            gap: 1rem;
        }

        .user-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.25rem;
            box-shadow: var(--shadow-sm);
            transition: border-color 0.2s, box-shadow 0.2s, transform 0.2s;
            position: relative;
            overflow: hidden;
        }
        .user-card:hover {
            border-color: var(--border-2);
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        /* Top accent bar per role */
        .user-card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0;
            height: 3px;
        }
        .user-card.admin::before   { background: linear-gradient(90deg, #C0392B, #E67E22); }
        .user-card.petugas::before { background: linear-gradient(90deg, #2563EB, #7C3AED); }
        .user-card.peminjam::before{ background: linear-gradient(90deg, var(--green), #059669); }

        .user-card-top {
            display: flex; align-items: flex-start; gap: 0.85rem;
            margin-bottom: 1rem;
            padding-top: 0.25rem; /* offset the top bar */
        }

        .uc-avatar {
            width: 44px; height: 44px;
            border-radius: 10px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.88rem; font-weight: 700; color: white;
        }
        .uc-avatar.admin   { background: linear-gradient(135deg, #C0392B, #E67E22); }
        .uc-avatar.petugas { background: linear-gradient(135deg, #2563EB, #7C3AED); }
        .uc-avatar.peminjam{ background: linear-gradient(135deg, var(--green), #059669); }

        .uc-name {
            font-size: 0.88rem; font-weight: 700;
            color: var(--text-primary); line-height: 1.3;
        }
        .uc-email {
            font-size: 0.74rem; color: var(--text-muted);
            margin-top: 0.15rem; word-break: break-all;
        }

        .uc-meta {
            display: flex; align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        .uc-joined { font-size: 0.7rem; color: var(--text-muted); }

        .uc-stats {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 0.5rem; margin-bottom: 1rem;
        }
        .uc-stat {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 0.55rem 0.7rem;
            text-align: center;
        }
        .uc-stat-val {
            font-family: var(--font-display);
            font-size: 1.2rem; font-weight: 700;
            color: var(--text-primary);
        }
        .uc-stat-label { font-size: 0.65rem; color: var(--text-muted); }

        .uc-actions { display: flex; gap: 0.4rem; }
        .uc-actions .btn { flex: 1; justify-content: center; }

        /* Self tag */
        .self-tag {
            font-size: 0.6rem; font-weight: 700;
            letter-spacing: 0.08em; text-transform: uppercase;
            background: var(--green-bg);
            color: var(--green-d);
            border: 1px solid var(--green-mid);
            padding: 0.12rem 0.45rem; border-radius: 3px;
            vertical-align: middle;
        }

        /* Empty state */
        .empty-state {
            text-align: center; padding: 3.5rem 2rem;
        }
        .empty-icon { font-size: 2.5rem; margin-bottom: 0.75rem; }
        .empty-text { color: var(--text-secondary); font-size: 0.88rem; margin-bottom: 1.25rem; }

        /* ── Pagination ──────────────────────────────── */
        .pagination-wrap {
            display: flex; align-items: center;
            justify-content: space-between;
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid var(--border);
        }
        .pagination-info { font-size: 0.78rem; color: var(--text-muted); }
        .pagination-links { display: flex; gap: 0.3rem; }
        .page-link {
            width: 32px; height: 32px;
            border-radius: var(--radius);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.78rem; font-weight: 600;
            text-decoration: none;
            background: var(--white);
            border: 1px solid var(--border);
            color: var(--text-secondary);
            transition: all 0.15s;
        }
        .page-link:hover { background: var(--surface); border-color: var(--border-2); color: var(--text-primary); }
        .page-link.active { background: var(--green); color: white; border-color: var(--green); }
        .page-link.disabled { opacity: 0.35; pointer-events: none; }

        @media (max-width: 768px) {
            .stats-row { grid-template-columns: 1fr 1fr; }
            .users-grid { grid-template-columns: 1fr; }
            .filter-tabs { flex-wrap: wrap; }
        }
    </style>

    {{-- ── STATS ROW ────────────────────────────────── --}}
    <div class="stats-row">
        <div class="mini-stat">
            <div class="mini-stat-icon" style="background:var(--surface-2)">👥</div>
            <div>
                <div class="mini-stat-val">{{ $stats['total'] }}</div>
                <div class="mini-stat-label">Total User</div>
            </div>
        </div>
        <div class="mini-stat">
            <div class="mini-stat-icon" style="background:var(--red-bg)">🛡</div>
            <div>
                <div class="mini-stat-val">{{ $stats['admin'] }}</div>
                <div class="mini-stat-label">Administrator</div>
            </div>
        </div>
        <div class="mini-stat">
            <div class="mini-stat-icon" style="background:var(--blue-bg)">👔</div>
            <div>
                <div class="mini-stat-val">{{ $stats['petugas'] }}</div>
                <div class="mini-stat-label">Petugas</div>
            </div>
        </div>
        <div class="mini-stat">
            <div class="mini-stat-icon" style="background:var(--green-bg)">🙋</div>
            <div>
                <div class="mini-stat-val">{{ $stats['peminjam'] }}</div>
                <div class="mini-stat-label">Peminjam</div>
            </div>
        </div>
    </div>

    {{-- ── TOOLBAR ──────────────────────────────────── --}}
    <form method="GET" action="{{ route('admin.users.index') }}">
        <div class="toolbar">
            <div class="search-wrap">
                <span class="search-icon">⌕</span>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama atau email...">
            </div>

            <div class="filter-tabs">
                <a href="{{ route('admin.users.index', array_merge(request()->except('role','page'), [])) }}"
                   class="filter-tab {{ !request('role') ? 'active' : '' }}">Semua</a>
                <a href="{{ route('admin.users.index', array_merge(request()->except('page'), ['role'=>'admin'])) }}"
                   class="filter-tab {{ request('role')=='admin' ? 'active' : '' }}">Admin</a>
                <a href="{{ route('admin.users.index', array_merge(request()->except('page'), ['role'=>'petugas'])) }}"
                   class="filter-tab {{ request('role')=='petugas' ? 'active' : '' }}">Petugas</a>
                <a href="{{ route('admin.users.index', array_merge(request()->except('page'), ['role'=>'peminjam'])) }}"
                   class="filter-tab {{ request('role')=='peminjam' ? 'active' : '' }}">Peminjam</a>
            </div>

            <button type="submit" class="btn btn-ghost">Cari</button>
            @if(request('search') || request('role'))
                <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">✕ Reset</a>
            @endif
        </div>
    </form>

    {{-- ── USER CARDS ───────────────────────────────── --}}
    @if($users->isEmpty())
        <div class="card">
            <div class="empty-state">
                <div class="empty-icon">🔍</div>
                <div class="empty-text">
                    Tidak ada user ditemukan
                    @if(request('search')) untuk "<strong>{{ request('search') }}</strong>"@endif.
                </div>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary" style="display:inline-flex">
                    + Tambah User Baru
                </a>
            </div>
        </div>
    @else
        <div class="users-grid">
            @foreach($users as $user)
                <div class="user-card {{ $user->role }}">
                    <div class="user-card-top">
                        <div class="uc-avatar {{ $user->role }}">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div style="flex:1;min-width:0">
                            <div class="uc-name">
                                {{ $user->name }}
                                @if($user->id === auth()->id())
                                    <span class="self-tag">Anda</span>
                                @endif
                            </div>
                            <div class="uc-email">{{ $user->email }}</div>
                        </div>
                    </div>

                    <div class="uc-meta">
                        @php
                            $roleLabels = ['admin'=>'Administrator','petugas'=>'Petugas','peminjam'=>'Peminjam'];
                            $roleBadge  = ['admin'=>'badge-red','petugas'=>'badge-blue','peminjam'=>'badge-green'];
                        @endphp
                        <span class="badge {{ $roleBadge[$user->role] }}">
                            {{ $roleLabels[$user->role] }}
                        </span>
                        <span class="uc-joined">
                            Bergabung {{ $user->created_at->diffForHumans() }}
                        </span>
                    </div>

                    @if($user->isPeminjam())
                        <div class="uc-stats">
                            <div class="uc-stat">
                                <div class="uc-stat-val">{{ $user->peminjamans_count }}</div>
                                <div class="uc-stat-label">Total Pinjam</div>
                            </div>
                            <div class="uc-stat">
                                <div class="uc-stat-val">{{ $user->peminjaman_aktif_count }}</div>
                                <div class="uc-stat-label">Aktif</div>
                            </div>
                        </div>
                    @endif

                    <div class="uc-actions">
                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-ghost btn-sm">Detail</a>
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary btn-sm">Edit</a>
                        @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                  onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="pagination-wrap">
            <span class="pagination-info">
                Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }}
                dari {{ $users->total() }} user
            </span>
            <div class="pagination-links">
                <a href="{{ $users->previousPageUrl() }}"
                   class="page-link {{ $users->onFirstPage() ? 'disabled' : '' }}">‹</a>
                @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                    <a href="{{ $url }}"
                       class="page-link {{ $page == $users->currentPage() ? 'active' : '' }}">
                        {{ $page }}
                    </a>
                @endforeach
                <a href="{{ $users->nextPageUrl() }}"
                   class="page-link {{ !$users->hasMorePages() ? 'disabled' : '' }}">›</a>
            </div>
        </div>
    @endif

</x-admin-layout>