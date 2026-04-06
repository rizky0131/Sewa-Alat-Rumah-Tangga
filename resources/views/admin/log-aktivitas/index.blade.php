<x-admin-layout title="Log Aktivitas" breadcrumb="Log Aktivitas">

    <x-slot name="header">
        <div>
            <h1 class="page-heading">Log Aktivitas</h1>
            <p class="page-sub">Audit trail seluruh aksi yang terjadi di sistem.</p>
        </div>
        <div style="display:flex;gap:0.5rem">
            <a href="{{ route('admin.log-aktivitas.create') }}" class="btn btn-ghost">＋ Catat Manual</a>
            <button type="button" class="btn btn-danger btn-sm" onclick="showPurgeModal()">🗑 Purge</button>
        </div>
    </x-slot>

    <style>
        /* Stats */
        .log-stats { display:grid;grid-template-columns:repeat(4,1fr);gap:0.75rem;margin-bottom:1.5rem; }
        .ls { background:var(--ink-80);border:1px solid rgba(255,255,255,0.07);border-radius:8px;padding:0.9rem 1.1rem; }
        .ls-val   { font-family:var(--font-display);font-size:1.7rem;font-weight:700;color:var(--cream); }
        .ls-label { font-size:0.68rem;color:var(--mist);margin-top:0.2rem; }

        /* Filter bar */
        .filter-bar { background:var(--ink-80);border:1px solid rgba(255,255,255,0.07);border-radius:8px;padding:0.9rem 1.1rem;margin-bottom:1.1rem; }
        .filter-row { display:flex;gap:0.6rem;flex-wrap:wrap;align-items:center; }
        .search-wrap { display:flex;align-items:center;gap:0.5rem;flex:1;min-width:200px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:6px;padding:0 0.9rem;transition:border-color 0.2s; }
        .search-wrap:focus-within { border-color:rgba(37,99,235,0.4); }
        .search-wrap input { flex:1;background:none;border:none;outline:none;padding:0.62rem 0;color:var(--cream);font-family:var(--font-ui);font-size:0.82rem; }
        .search-wrap input::placeholder { color:var(--slate); }
        .filter-select { background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:6px;padding:0.62rem 0.9rem;color:var(--cream);font-family:var(--font-ui);font-size:0.8rem;outline:none;min-width:130px; }
        .filter-select option { background:var(--ink-80); }
        .filter-chip { display:flex;align-items:center;gap:0.35rem;padding:0.6rem 0.8rem;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:6px;cursor:pointer;font-size:0.8rem;color:var(--mist);white-space:nowrap; }
        .filter-chip:has(input:checked) { border-color:rgba(37,99,235,0.4);color:var(--accent-l);background:rgba(37,99,235,0.08); }
        .filter-chip input { display:none; }

        /* Log table */
        .log-wrap { background:var(--ink-80);border:1px solid rgba(255,255,255,0.07);border-radius:8px;overflow:hidden; }
        .log-head { padding:0.85rem 1.2rem;border-bottom:1px solid rgba(255,255,255,0.06);display:flex;align-items:center;justify-content:space-between; }

        /* Aksi badges — color-coded by category */
        .aksi-badge {
            display:inline-flex;align-items:center;gap:0.3rem;
            padding:0.22rem 0.65rem;border-radius:100px;font-size:0.66rem;font-weight:700;font-family:monospace;
            white-space:nowrap;
        }
        .aksi-login,.aksi-logout          { background:rgba(59,130,246,0.12);color:#93C5FD;border:1px solid rgba(59,130,246,0.2); }
        .aksi-crud_user                   { background:rgba(139,92,246,0.12);color:#C4B5FD;border:1px solid rgba(139,92,246,0.2); }
        .aksi-crud_alat,.aksi-crud_kategori { background:rgba(16,185,129,0.12);color:#34D399;border:1px solid rgba(16,185,129,0.2); }
        .aksi-crud_peminjaman,.aksi-crud_pengembalian { background:rgba(212,168,67,0.12);color:#FCD34D;border:1px solid rgba(212,168,67,0.2); }
        .aksi-setujui_peminjaman          { background:rgba(16,185,129,0.15);color:#34D399;border:1px solid rgba(16,185,129,0.3); }
        .aksi-tolak_peminjaman            { background:rgba(239,68,68,0.12);color:#FCA5A5;border:1px solid rgba(239,68,68,0.2); }
        .aksi-pantau_pengembalian         { background:rgba(249,115,22,0.12);color:#FDBA74;border:1px solid rgba(249,115,22,0.2); }
        .aksi-cetak_laporan               { background:rgba(100,116,139,0.15);color:var(--silver);border:1px solid rgba(255,255,255,0.12); }

        /* User avatar */
        .user-av { width:28px;height:28px;border-radius:6px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:0.65rem;font-weight:800;color:white; }
        .role-admin    { background:linear-gradient(135deg,#7C3AED,#4F46E5); }
        .role-petugas  { background:linear-gradient(135deg,#0D9488,#0891B2); }
        .role-peminjam { background:linear-gradient(135deg,#10B981,#06B6D4); }
        .role-system   { background:rgba(100,116,139,0.4);color:var(--slate); }

        /* Modul chip */
        .modul-chip { font-size:0.68rem;font-weight:700;color:var(--mist);padding:0.15rem 0.5rem;background:rgba(255,255,255,0.05);border-radius:4px; }

        /* Data diff */
        .diff-indicator {
            display:inline-flex;gap:0.25rem;align-items:center;
        }
        .diff-dot { width:7px;height:7px;border-radius:50%; }
        .diff-old { background:rgba(239,68,68,0.7); }
        .diff-new { background:rgba(16,185,129,0.7); }

        /* IP */
        .ip-text { font-family:monospace;font-size:0.68rem;color:var(--slate); }

        /* Time */
        .time-ago   { font-size:0.75rem;font-weight:700;color:var(--silver); }
        .time-exact { font-size:0.65rem;color:var(--slate);margin-top:0.1rem; }

        /* Pagination */
        .pag-wrap { display:flex;align-items:center;justify-content:space-between;padding:0.85rem 1.2rem;border-top:1px solid rgba(255,255,255,0.06); }
        .pag-info { font-size:0.75rem;color:var(--mist); }
        .pag-links { display:flex;gap:0.25rem; }
        .pag-link { width:30px;height:30px;border-radius:5px;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:600;text-decoration:none;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);color:var(--mist);transition:all 0.15s; }
        .pag-link:hover,.pag-link.active { background:var(--accent);color:white;border-color:var(--accent); }
        .pag-link.disabled { opacity:0.3;pointer-events:none; }

        /* Purge modal */
        .modal-overlay { display:none;position:fixed;inset:0;z-index:200;background:rgba(0,0,0,0.65);backdrop-filter:blur(4px);align-items:center;justify-content:center; }
        .modal-box { background:var(--ink-80);border:1px solid rgba(255,255,255,0.1);border-radius:12px;padding:1.8rem;width:400px;max-width:90vw; }

        /* Live search highlight */
        .highlight { background:rgba(37,99,235,0.25);border-radius:2px;padding:0 1px; }

        @media(max-width:1100px) { .log-stats { grid-template-columns:repeat(2,1fr); } }
        @media(max-width:768px)  { .log-stats { grid-template-columns:1fr 1fr; } }
    </style>

    {{-- Stats --}}
    <div class="log-stats">
        <div class="ls">
            <div style="font-size:1rem;margin-bottom:0.3rem">📋</div>
            <div class="ls-val">{{ number_format($stats['total']) }}</div>
            <div class="ls-label">Total Log</div>
        </div>
        <div class="ls">
            <div style="font-size:1rem;margin-bottom:0.3rem">📅</div>
            <div class="ls-val" style="color:var(--accent-l)">{{ $stats['today'] }}</div>
            <div class="ls-label">Hari Ini</div>
        </div>
        <div class="ls">
            <div style="font-size:1rem;margin-bottom:0.3rem">👤</div>
            <div class="ls-val">{{ $stats['users'] }}</div>
            <div class="ls-label">User Aktif</div>
        </div>
        <div class="ls">
            <div style="font-size:1rem;margin-bottom:0.3rem">📂</div>
            <div class="ls-val">{{ $stats['moduls'] }}</div>
            <div class="ls-label">Modul Tercatat</div>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.log-aktivitas.index') }}" id="filterForm">
        <div class="filter-bar">
            <div class="filter-row">
                <div class="search-wrap">
                    <span style="color:var(--slate)">⌕</span>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari aksi, deskripsi, atau nama user..."
                           id="searchInput">
                </div>

                <select name="aksi" class="filter-select" onchange="this.form.submit()">
                    <option value="">Semua Aksi</option>
                    @foreach($allAksi as $a)
                        <option value="{{ $a }}" {{ request('aksi')==$a ? 'selected':'' }}>{{ $a }}</option>
                    @endforeach
                </select>

                <select name="modul" class="filter-select" onchange="this.form.submit()">
                    <option value="">Semua Modul</option>
                    @foreach($allModul as $m)
                        <option value="{{ $m }}" {{ request('modul')==$m ? 'selected':'' }}>{{ $m }}</option>
                    @endforeach
                </select>

                <select name="user" class="filter-select" onchange="this.form.submit()">
                    <option value="">Semua User</option>
                    @foreach($allUsers as $u)
                        <option value="{{ $u->id }}" {{ request('user')==$u->id ? 'selected':'' }}>{{ $u->name }}</option>
                    @endforeach
                </select>

                <input type="date" name="date" value="{{ request('date') }}"
                       class="filter-select" onchange="this.form.submit()">

                <label class="filter-chip">
                    <input type="checkbox" name="today" value="1"
                           {{ request('today') ? 'checked':'' }} onchange="this.form.submit()">
                    📅 Hari ini
                </label>

                <button type="submit" class="btn btn-ghost">Cari</button>
                @if(request()->hasAny(['search','aksi','modul','user','date','today']))
                    <a href="{{ route('admin.log-aktivitas.index') }}" class="btn btn-ghost">✕</a>
                @endif
            </div>
        </div>
    </form>

    {{-- Log table --}}
    <div class="log-wrap">
        <div class="log-head">
            <span class="card-title">Riwayat Aktivitas</span>
            <span style="font-size:0.75rem;color:var(--mist)">{{ $logs->total() }} entri</span>
        </div>
        <div style="overflow-x:auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:130px">Waktu</th>
                        <th>User</th>
                        <th>Aksi</th>
                        <th>Modul</th>
                        <th style="width:35%">Deskripsi</th>
                        <th>Data</th>
                        <th>IP</th>
                        <th style="text-align:right">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>
                                <div class="time-ago">{{ $log->created_at->diffForHumans(short: true) }}</div>
                                <div class="time-exact">{{ $log->created_at->format('d M, H:i:s') }}</div>
                            </td>
                            <td>
                                @if($log->user)
                                    <div style="display:flex;align-items:center;gap:0.5rem">
                                        <div class="user-av role-{{ $log->user->role }}">
                                            {{ strtoupper(substr($log->user->name,0,2)) }}
                                        </div>
                                        <div>
                                            <div style="font-size:0.8rem;font-weight:700;color:var(--silver)">{{ $log->user->name }}</div>
                                            <div style="font-size:0.62rem;color:var(--slate)">{{ $log->user->role }}</div>
                                        </div>
                                    </div>
                                @else
                                    <div style="display:flex;align-items:center;gap:0.4rem">
                                        <div class="user-av role-system">⚙</div>
                                        <span style="font-size:0.75rem;color:var(--slate)">Sistem</span>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="aksi-badge aksi-{{ $log->aksi }}">
                                    {{ $log->aksi }}
                                </span>
                            </td>
                            <td>
                                <span class="modul-chip">{{ $log->modul }}</span>
                            </td>
                            <td>
                                <div style="font-size:0.8rem;color:var(--silver);line-height:1.4;max-width:320px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                    {{ $log->deskripsi ?? '—' }}
                                </div>
                            </td>
                            <td>
                                @if($log->data_lama || $log->data_baru)
                                    <div class="diff-indicator">
                                        @if($log->data_lama) <div class="diff-dot diff-old" title="Ada data lama"></div> @endif
                                        @if($log->data_baru) <div class="diff-dot diff-new" title="Ada data baru"></div> @endif
                                    </div>
                                @else
                                    <span style="color:var(--slate);font-size:0.7rem">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="ip-text">{{ $log->ip_address ?? '—' }}</span>
                            </td>
                            <td style="text-align:right">
                                <a href="{{ route('admin.log-aktivitas.show', $log) }}"
                                   class="btn btn-ghost btn-sm">→</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;padding:3rem;color:var(--mist)">
                                <div style="font-size:2rem;margin-bottom:0.5rem">📋</div>
                                Tidak ada log aktivitas ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="pag-wrap">
                <span class="pag-info">{{ $logs->firstItem() }}–{{ $logs->lastItem() }} dari {{ $logs->total() }}</span>
                <div class="pag-links">
                    <a href="{{ $logs->previousPageUrl() }}" class="pag-link {{ $logs->onFirstPage() ? 'disabled':'' }}">‹</a>
                    @php
                        $cur  = $logs->currentPage();
                        $last = $logs->lastPage();
                        $pages = array_unique(array_filter([1, $cur-1, $cur, $cur+1, $last]));
                        sort($pages);
                    @endphp
                    @foreach($pages as $i => $pg)
                        @if($i > 0 && $pages[$i-1] < $pg-1)
                            <span class="pag-link" style="pointer-events:none;opacity:0.4">…</span>
                        @endif
                        <a href="{{ $logs->url($pg) }}" class="pag-link {{ $pg == $cur ? 'active':'' }}">{{ $pg }}</a>
                    @endforeach
                    <a href="{{ $logs->nextPageUrl() }}" class="pag-link {{ !$logs->hasMorePages() ? 'disabled':'' }}">›</a>
                </div>
            </div>
        @endif
    </div>

    {{-- Purge Modal --}}
    <div class="modal-overlay" id="purgeModal" style="display:none">
        <div class="modal-box">
            <div style="font-size:1.1rem;font-weight:700;color:#FCA5A5;margin-bottom:0.5rem">🗑 Hapus Log Lama</div>
            <div style="font-size:0.8rem;color:var(--mist);margin-bottom:1.2rem;line-height:1.5">
                Hapus entri log yang lebih lama dari jumlah hari yang ditentukan.
                Aksi ini <strong style="color:#FCA5A5">tidak dapat dibatalkan</strong>.
            </div>
            <form method="POST" action="{{ route('admin.log-aktivitas.purge') }}"
                  onsubmit="return confirm('Yakin ingin menghapus log lama?')">
                @csrf
                <div class="form-group">
                    <label class="form-label">Hapus log lebih dari</label>
                    <div style="display:flex;align-items:center;gap:0.6rem">
                        <input type="number" name="days" value="90" min="7"
                               class="form-input" style="width:100px">
                        <span style="color:var(--mist);font-size:0.82rem">hari yang lalu</span>
                    </div>
                </div>
                <div style="display:flex;gap:0.5rem;margin-top:1rem">
                    <button type="submit" class="btn btn-danger" style="flex:1;justify-content:center">🗑 Hapus</button>
                    <button type="button" onclick="closePurgeModal()" class="btn btn-ghost" style="flex:1;justify-content:center">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Purge modal
        function showPurgeModal() {
            const m = document.getElementById('purgeModal');
            m.style.display = 'flex';
        }
        function closePurgeModal() {
            document.getElementById('purgeModal').style.display = 'none';
        }
        document.getElementById('purgeModal').addEventListener('click', e => {
            if (e.target === document.getElementById('purgeModal')) closePurgeModal();
        });
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closePurgeModal(); });

        // Auto-submit search with debounce
        let debounceTimer;
        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => this.form.submit(), 500);
        });
    </script>

</x-admin-layout>