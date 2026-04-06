<x-admin-layout title="Data Peminjaman" breadcrumb="Peminjaman">
    
    <x-slot name="header">
        <div>
            <h1 class="page-heading">Data Peminjaman</h1>
            <p class="page-sub">Kelola seluruh transaksi peminjaman alat.</p>
        </div>
        <a href="{{ route('admin.peminjamans.create') }}" class="btn btn-primary">＋ Buat Peminjaman</a>
    </x-slot>

    <style>
        /* Stats */
        .pm-stats { display:grid;grid-template-columns:repeat(5,1fr);gap:0.75rem;margin-bottom:1.5rem; }
        .pms {
            background:var(--ink-80);border:1px solid rgba(255,255,255,0.07);
            border-radius:8px;padding:0.9rem 1.1rem;cursor:pointer;text-decoration:none;
            transition:border-color 0.15s,transform 0.15s;display:block;
        }
        .pms:hover { border-color:rgba(255,255,255,0.2);transform:translateY(-1px); }
        .pms.active { border-color:var(--accent);background:rgba(37,99,235,0.08); }
        .pms-top { display:flex;align-items:center;justify-content:space-between;margin-bottom:0.4rem; }
        .pms-icon { font-size:1rem; }
        .pms-val  { font-family:var(--font-display);font-size:1.7rem;font-weight:700;color:var(--cream); }
        .pms-label { font-size:0.68rem;color:var(--mist); }

        /* Status tab bar */
        .status-tabs { display:flex;gap:0;background:var(--ink-80);border:1px solid rgba(255,255,255,0.07);border-radius:8px;overflow:hidden;margin-bottom:1.1rem; }
        .stab {
            flex:1;padding:0.7rem 0.5rem;text-align:center;font-size:0.76rem;font-weight:700;
            text-decoration:none;color:var(--mist);transition:all 0.15s;
            border-right:1px solid rgba(255,255,255,0.06);
        }
        .stab:last-child { border-right:none; }
        .stab:hover { background:rgba(255,255,255,0.04);color:var(--cream); }
        .stab.active { background:var(--accent);color:white; }
        .stab-count { display:inline-block;background:rgba(255,255,255,0.2);border-radius:100px;padding:0.05rem 0.4rem;font-size:0.65rem;margin-left:0.3rem; }
        .stab.active .stab-count { background:rgba(255,255,255,0.3); }

        /* Filter row */
        .filter-row { display:flex;gap:0.6rem;align-items:center;margin-bottom:1.1rem;flex-wrap:wrap; }
        .search-wrap { display:flex;align-items:center;gap:0.5rem;flex:1;min-width:200px;background:var(--ink-80);border:1px solid rgba(255,255,255,0.08);border-radius:6px;padding:0 0.9rem;transition:border-color 0.2s; }
        .search-wrap:focus-within { border-color:rgba(37,99,235,0.5); }
        .search-wrap input { flex:1;background:none;border:none;outline:none;padding:0.62rem 0;color:var(--cream);font-family:var(--font-ui);font-size:0.82rem; }
        .search-wrap input::placeholder { color:var(--slate); }
        .filter-select { background:var(--ink-80);border:1px solid rgba(255,255,255,0.08);border-radius:6px;padding:0.62rem 0.9rem;color:var(--cream);font-family:var(--font-ui);font-size:0.8rem;outline:none; }
        .filter-select option { background:var(--ink-80); }
        .filter-check { display:flex;align-items:center;gap:0.4rem;padding:0.6rem 0.9rem;background:var(--ink-80);border:1px solid rgba(255,255,255,0.08);border-radius:6px;cursor:pointer;font-size:0.8rem;color:var(--mist);white-space:nowrap; }
        .filter-check:has(input:checked) { border-color:rgba(239,68,68,0.5);color:#FCA5A5;background:rgba(239,68,68,0.08); }
        .filter-check input { display:none; }

        /* Table */
        .pm-table-wrap { background:var(--ink-80);border:1px solid rgba(255,255,255,0.07);border-radius:8px;overflow:hidden; }
        .pm-table-head { padding:0.85rem 1.2rem;border-bottom:1px solid rgba(255,255,255,0.06);display:flex;align-items:center;justify-content:space-between; }

        /* Status badge with indicator dot */
        .status-pill {
            display:inline-flex;align-items:center;gap:0.35rem;
            padding:0.28rem 0.7rem;border-radius:100px;font-size:0.68rem;font-weight:700;letter-spacing:0.03em;
        }
        .status-pill::before { content:'';width:6px;height:6px;border-radius:50%; }
        .sp-menunggu     { background:rgba(212,168,67,0.12);color:#FCD34D;border:1px solid rgba(212,168,67,0.25); }
        .sp-menunggu::before     { background:var(--gold);animation:pulse-dot 1.5s infinite; }
        .sp-disetujui    { background:rgba(59,130,246,0.12);color:#93C5FD;border:1px solid rgba(59,130,246,0.25); }
        .sp-disetujui::before    { background:var(--accent); }
        .sp-ditolak      { background:rgba(239,68,68,0.12);color:#FCA5A5;border:1px solid rgba(239,68,68,0.25); }
        .sp-ditolak::before      { background:var(--danger); }
        .sp-dipinjam     { background:rgba(16,185,129,0.12);color:#34D399;border:1px solid rgba(16,185,129,0.25); }
        .sp-dipinjam::before     { background:var(--jade); }
        .sp-dikembalikan { background:rgba(100,116,139,0.15);color:var(--silver);border:1px solid rgba(255,255,255,0.1); }
        .sp-dikembalikan::before { background:var(--mist); }
        .sp-terlambat    { background:rgba(239,68,68,0.18);color:#FCA5A5;border:1px solid rgba(239,68,68,0.35);animation:flash-border 1s infinite; }
        .sp-terlambat::before    { background:var(--danger);animation:pulse-dot 1s infinite; }

        @keyframes pulse-dot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.4;transform:scale(0.7)} }
        @keyframes flash-border { 0%,100%{border-color:rgba(239,68,68,0.35)} 50%{border-color:rgba(239,68,68,0.7)} }

        /* Alat + user cells */
        .user-cell { display:flex;align-items:center;gap:0.6rem; }
        .user-av   { width:30px;height:30px;border-radius:7px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:800;color:white;background:linear-gradient(135deg,#10B981,#06B6D4); }
        .user-name { font-size:0.82rem;font-weight:700;color:var(--silver); }
        .user-email { font-size:0.68rem;color:var(--slate); }

        .alat-cell { display:flex;align-items:center;gap:0.6rem; }
        .alat-thumb-sm { width:30px;height:30px;border-radius:6px;overflow:hidden;flex-shrink:0;background:rgba(37,99,235,0.1);border:1px solid rgba(37,99,235,0.15);display:flex;align-items:center;justify-content:center;font-size:0.75rem; }
        .alat-thumb-sm img { width:100%;height:100%;object-fit:cover; }

        /* Date cell */
        .date-range { font-size:0.78rem;color:var(--silver); }
        .date-due   { font-size:0.68rem;color:var(--mist); }
        .date-late  { color:#FCA5A5;font-weight:700; }

        /* Nomor pinjam */
        .nomor-pinjam { font-family:monospace;font-size:0.78rem;font-weight:700;color:var(--accent-l);letter-spacing:0.03em; }

        /* Actions */
        .quick-actions { display:flex;gap:0.3rem;justify-content:flex-end; }

        /* Pagination */
        .pag-wrap { display:flex;align-items:center;justify-content:space-between;padding:0.85rem 1.2rem;border-top:1px solid rgba(255,255,255,0.06); }
        .pag-info { font-size:0.75rem;color:var(--mist); }
        .pag-links { display:flex;gap:0.25rem; }
        .pag-link { width:30px;height:30px;border-radius:5px;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:600;text-decoration:none;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);color:var(--mist);transition:all 0.15s; }
        .pag-link:hover,.pag-link.active { background:var(--accent);color:white;border-color:var(--accent); }
        .pag-link.disabled { opacity:0.3;pointer-events:none; }

        @media(max-width:1200px) { .pm-stats { grid-template-columns:repeat(3,1fr); } }
        @media(max-width:768px)  { .pm-stats { grid-template-columns:1fr 1fr; } .status-tabs { flex-wrap:wrap; } }
    </style>

    {{-- Stats --}}
    <div class="pm-stats">
        @php
          

            $statDefs = [
                ['total',        '📋', 'Total Peminjaman', ''],
                ['menunggu',     '⏳', 'Menunggu',         'var(--gold)'],
                ['dipinjam',     '📦', 'Sedang Dipinjam',  ''],
                ['terlambat',    '⚠',  'Terlambat',        ''],
                ['dikembalikan', '✅', 'Selesai',           ''],
            ];
        @endphp
        <a href="{{ route('admin.peminjamans.index') }}"
           class="pms {{ !request('status') && !request('terlambat') ? 'active' : '' }}">
            <div class="pms-top"><span class="pms-icon">📋</span></div>
            <div class="pms-val">{{ $stats['total'] }}</div>
            <div class="pms-label">Total Peminjaman</div>
        </a>
        <a href="{{ route('admin.peminjamans.index', ['status'=>'menunggu']) }}"
           class="pms {{ request('status')=='menunggu' ? 'active' : '' }}">
            <div class="pms-top"><span class="pms-icon">⏳</span>
                @if($stats['menunggu'] > 0) <span class="badge badge-amber" style="font-size:0.62rem">Aksi!</span> @endif
            </div>
            <div class="pms-val" style="{{ $stats['menunggu'] > 0 ? 'color:var(--gold)' : '' }}">{{ $stats['menunggu'] }}</div>
            <div class="pms-label">Menunggu Persetujuan</div>
        </a>
        <a href="{{ route('admin.peminjamans.index', ['status'=>'dipinjam']) }}"
           class="pms {{ request('status')=='dipinjam' ? 'active' : '' }}">
            <div class="pms-top"><span class="pms-icon">📦</span></div>
            <div class="pms-val">{{ $stats['dipinjam'] }}</div>
            <div class="pms-label">Sedang Dipinjam</div>
        </a>
        <a href="{{ route('admin.peminjamans.index', ['terlambat'=>'1']) }}"
           class="pms {{ request('terlambat') ? 'active' : '' }}">
            <div class="pms-top"><span class="pms-icon">⚠</span>
                @if($stats['terlambat'] > 0) <span class="badge badge-red" style="font-size:0.62rem">Urgent</span> @endif
            </div>
            <div class="pms-val" style="{{ $stats['terlambat'] > 0 ? 'color:var(--danger)' : '' }}">{{ $stats['terlambat'] }}</div>
            <div class="pms-label">Terlambat Kembali</div>
        </a>
        <a href="{{ route('admin.peminjamans.index', ['status'=>'dikembalikan']) }}"
           class="pms {{ request('status')=='dikembalikan' ? 'active' : '' }}">
            <div class="pms-top"><span class="pms-icon">✅</span></div>
            <div class="pms-val">{{ $stats['dikembalikan'] }}</div>
            <div class="pms-label">Selesai</div>
        </a>
    </div>

    {{-- Status flow tabs --}}
    <div class="status-tabs">
        @php
            $tabs = [
                ''             => ['Semua', $stats['total']],
                'menunggu'     => ['⏳ Menunggu', $stats['menunggu']],
                'disetujui'    => ['✓ Disetujui', \App\Models\Peminjaman::where('status','disetujui')->count()],
                'dipinjam'     => ['📦 Dipinjam', $stats['dipinjam']],
                'dikembalikan' => ['✅ Selesai', $stats['dikembalikan']],
                'ditolak'      => ['✕ Ditolak', \App\Models\Peminjaman::where('status','ditolak')->count()],
            ];
        @endphp
        @foreach($tabs as $val => [$label,$count])
            <a href="{{ route('admin.peminjamans.index', $val ? ['status'=>$val] : []) }}"
               class="stab {{ request('status') == $val && !request('terlambat') ? 'active' : ((!request('status') && !request('terlambat') && $val=='') ? 'active' : '') }}">
                {{ $label }}
                <span class="stab-count">{{ $count }}</span>
            </a>
        @endforeach
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.peminjamans.index') }}">
        @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
        <div class="filter-row">
            <div class="search-wrap">
                <span style="color:var(--slate)">⌕</span>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nomor, nama peminjam, atau alat...">
            </div>
            <label class="filter-check">
                <input type="checkbox" name="terlambat" value="1" {{ request('terlambat') ? 'checked' : '' }}>
                ⚠ Terlambat saja
            </label>
            <button type="submit" class="btn btn-ghost">Cari</button>
            @if(request()->hasAny(['search','terlambat']))
                <a href="{{ route('admin.peminjamans.index', request()->only('status')) }}" class="btn btn-ghost">✕ Reset</a>
            @endif
        </div>
    </form>

    {{-- Table --}}
    <div class="pm-table-wrap">
        <div class="pm-table-head">
            <span class="card-title">Daftar Peminjaman</span>
            <span style="font-size:0.75rem;color:var(--mist)">{{ $peminjamans->total() }} data</span>
        </div>

        <div style="overflow-x:auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No. Transaksi</th>
                        <th>Peminjam</th>
                        <th>Alat</th>
                        <th>Durasi & Tanggal</th>
                        <th>Total Biaya</th>
                        <th>Status</th>
                        <th style="text-align:right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjamans as $p)
                        @php $terlambat = $p->is_terlambat; @endphp
                        <tr style="{{ $terlambat ? 'background:rgba(239,68,68,0.04)' : '' }}">
                            <td>
                                <div class="nomor-pinjam">{{ $p->nomor_pinjam }}</div>
                                <div style="font-size:0.68rem;color:var(--slate)">
                                    {{ $p->created_at->format('d M Y') }}
                                </div>
                            </td>
                            <td>
                                <div class="user-cell">
                                    <div class="user-av">{{ strtoupper(substr($p->peminjam->name??'?',0,2)) }}</div>
                                    <div>
                                        <div class="user-name">{{ $p->peminjam->name ?? '—' }}</div>
                                        <div class="user-email">{{ $p->peminjam->email ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="alat-cell">
                                    <div class="alat-thumb-sm">
                                        @if($p->alat?->foto)
                                            <img src="{{ asset('storage/'.$p->alat->foto) }}" alt="">
                                        @else 🔧 @endif
                                    </div>
                                    <div>
                                        <div style="font-size:0.82rem;font-weight:700;color:var(--silver)">{{ $p->alat->nama ?? '—' }}</div>
                                        <div style="font-size:0.68rem;color:var(--slate)">
                                            {{ $p->jumlah }}x · {{ $p->alat->kode ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="date-range">
                                    {{ $p->tanggal_pinjam->format('d M') }}
                                    → {{ $p->tanggal_kembali_rencana->format('d M Y') }}
                                </div>
                                <div class="date-due {{ $terlambat ? 'date-late' : '' }}">
                                    {{ $p->durasi_hari }} hari
                                    @if($terlambat)
                                        · ⚠ Telat {{ $p->keterlambatan_hari }} hari
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div style="font-size:0.85rem;font-weight:700;color:var(--jade)">
                                    Rp {{ number_format($p->total_biaya,0,',','.') }}
                                </div>
                            </td>
                            <td>
                                @if($terlambat)
                                    <span class="status-pill sp-terlambat">Terlambat</span>
                                @else
                                    <span class="status-pill sp-{{ $p->status }}">
                                        {{ ucfirst($p->status) }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="quick-actions">
                                    @if($p->status === 'menunggu')
                                        <form method="POST" action="{{ route('admin.peminjamans.setujui', $p) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm">✓ Setujui</button>
                                        </form>
                                        <button type="button" class="btn btn-danger btn-sm"
                                                onclick="showTolakModal('{{ $p->id }}','{{ $p->nomor_pinjam }}')">
                                            ✕ Tolak
                                        </button>
                                    @elseif($p->status === 'disetujui')
                                        <form method="POST" action="{{ route('admin.peminjamans.tandaiDipinjam', $p) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm">📦 Serahkan</button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.peminjamans.show', $p) }}" class="btn btn-ghost btn-sm">Detail</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center;padding:3rem;color:var(--mist)">
                                <div style="font-size:2rem;margin-bottom:0.5rem">📋</div>
                                Tidak ada data peminjaman.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($peminjamans->hasPages())
            <div class="pag-wrap">
                <span class="pag-info">{{ $peminjamans->firstItem() }}–{{ $peminjamans->lastItem() }} dari {{ $peminjamans->total() }}</span>
                <div class="pag-links">
                    <a href="{{ $peminjamans->previousPageUrl() }}" class="pag-link {{ $peminjamans->onFirstPage() ? 'disabled' : '' }}">‹</a>
                    @foreach($peminjamans->getUrlRange(1, $peminjamans->lastPage()) as $pg => $url)
                        <a href="{{ $url }}" class="pag-link {{ $pg == $peminjamans->currentPage() ? 'active' : '' }}">{{ $pg }}</a>
                    @endforeach
                    <a href="{{ $peminjamans->nextPageUrl() }}" class="pag-link {{ !$peminjamans->hasMorePages() ? 'disabled' : '' }}">›</a>
                </div>
            </div>
        @endif
    </div>

    {{-- Tolak Modal --}}
    <div id="tolakModal" style="display:none;position:fixed;inset:0;z-index:200;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);align-items:center;justify-content:center">
        <div style="background:var(--ink-80);border:1px solid rgba(255,255,255,0.1);border-radius:12px;padding:1.8rem;width:420px;max-width:90vw">
            <div style="font-size:1.1rem;font-weight:700;color:var(--cream);margin-bottom:0.4rem">Tolak Peminjaman</div>
            <div id="tolakNomor" style="font-size:0.78rem;color:var(--mist);font-family:monospace;margin-bottom:1.1rem"></div>

            <form id="tolakForm" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Alasan Penolakan <span style="color:var(--danger)">*</span></label>
                    <textarea name="catatan" class="form-textarea" rows="3"
                              placeholder="Jelaskan alasan penolakan peminjaman ini..."></textarea>
                </div>
                <div style="display:flex;gap:0.5rem;margin-top:1rem">
                    <button type="submit" class="btn btn-danger" style="flex:1;justify-content:center">✕ Tolak Peminjaman</button>
                    <button type="button" onclick="closeTolakModal()" class="btn btn-ghost" style="flex:1;justify-content:center">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showTolakModal(id, nomor) {
            document.getElementById('tolakNomor').textContent = nomor;
            document.getElementById('tolakForm').action = `/admin/peminjamans/${id}/tolak`;
            const modal = document.getElementById('tolakModal');
            modal.style.display = 'flex';
            setTimeout(() => modal.querySelector('textarea').focus(), 100);
        }
        function closeTolakModal() {
            document.getElementById('tolakModal').style.display = 'none';
        }
        document.getElementById('tolakModal').addEventListener('click', function(e) {
            if (e.target === this) closeTolakModal();
        });
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeTolakModal(); });
    </script>

</x-admin-layout>