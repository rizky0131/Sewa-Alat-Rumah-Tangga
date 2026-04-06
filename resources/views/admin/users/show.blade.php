<x-admin-layout title="Detail User" breadcrumb="Detail User">

    <x-slot name="header">
        <div style="display:flex;align-items:center;gap:1rem">
            <div class="show-avatar {{ $user->role }}">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div>
                <h1 class="page-heading" style="font-size:1.5rem">{{ $user->name }}</h1>
                <p class="page-sub">{{ $user->email }} · Bergabung {{ $user->created_at->format('d M Y') }}</p>
            </div>
        </div>
        <div style="display:flex;gap:0.5rem">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">✎ Edit</a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">← Kembali</a>
        </div>
    </x-slot>

    <style>
        /* ── Avatar ──────────────────────────────────── */
        .show-avatar {
            width: 52px; height: 52px; border-radius: 12px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; font-weight: 700; color: white;
        }
        .show-avatar.admin   { background: linear-gradient(135deg,#C0392B,#E67E22); }
        .show-avatar.petugas { background: linear-gradient(135deg,#2563EB,#7C3AED); }
        .show-avatar.peminjam{ background: linear-gradient(135deg,var(--green),#059669); }

        /* ── Layout ──────────────────────────────────── */
        .show-layout {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 1.5rem;
            align-items: start;
        }

        /* ── Stats strip ─────────────────────────────── */
        .stats-strip {
            display: grid; grid-template-columns: repeat(3, 1fr);
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            margin-bottom: 1.2rem;
        }
        .strip-item {
            padding: 1.15rem 1rem;
            text-align: center;
            border-right: 1px solid var(--border);
        }
        .strip-item:last-child { border-right: none; }
        .strip-val {
            font-family: var(--font-display);
            font-size: 1.9rem; font-weight: 700;
            color: var(--text-primary); line-height: 1;
        }
        .strip-val.active { color: var(--green); }
        .strip-label {
            font-size: 0.7rem; color: var(--text-muted);
            margin-top: 0.2rem;
        }

        /* ── Section card ────────────────────────────── */
        .section-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            margin-bottom: 1.2rem;
        }
        .section-card:last-child { margin-bottom: 0; }

        .section-header {
            padding: 0.9rem 1.25rem;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            background: var(--surface);
        }
        .section-title {
            font-size: 0.82rem; font-weight: 700;
            color: var(--text-primary);
        }

        /* ── Riwayat rows ────────────────────────────── */
        .riwayat-row {
            display: flex; align-items: center; gap: 0.85rem;
            padding: 0.85rem 1.25rem;
            border-bottom: 1px solid var(--border);
            transition: background 0.12s;
        }
        .riwayat-row:last-child { border-bottom: none; }
        .riwayat-row:hover { background: var(--surface); }

        .riwayat-icon {
            width: 36px; height: 36px;
            border-radius: var(--radius);
            background: var(--green-bg);
            border: 1px solid var(--green-mid);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem; flex-shrink: 0;
        }
        .riwayat-name {
            font-size: 0.83rem; font-weight: 600;
            color: var(--text-primary);
        }
        .riwayat-date {
            font-size: 0.7rem; color: var(--text-muted);
            margin-top: 0.1rem;
        }

        /* ── Empty state ─────────────────────────────── */
        .empty-section {
            padding: 2rem; text-align: center;
            color: var(--text-muted); font-size: 0.82rem;
        }

        /* ── Log timeline ────────────────────────────── */
        .log-timeline { padding: 0.25rem 1.25rem 0.75rem; }
        .log-item {
            display: flex; gap: 0.75rem;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border);
        }
        .log-item:last-child { border-bottom: none; }

        .log-dot-wrap {
            display: flex; flex-direction: column;
            align-items: center; padding-top: 0.3rem;
        }
        .log-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: var(--green); flex-shrink: 0;
        }
        .log-dot.danger { background: var(--red); }
        .log-dot.success{ background: var(--green); }

        .log-body { flex: 1; }
        .log-desc {
            font-size: 0.8rem; color: var(--text-primary);
            line-height: 1.45;
        }
        .log-meta {
            font-size: 0.68rem; color: var(--text-muted);
            margin-top: 0.15rem;
        }
        .log-module {
            display: inline-block;
            font-size: 0.62rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.07em;
            background: var(--blue-bg); color: var(--blue);
            border-radius: 3px; padding: 0.1rem 0.4rem;
            margin-left: 0.4rem; vertical-align: middle;
        }

        /* ── Profile card (right) ────────────────────── */
        .profile-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .profile-hero {
            padding: 1.35rem 1.25rem;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 1rem;
            position: relative;
        }
        .profile-hero::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 3px;
        }
        .profile-hero.admin::before   { background: linear-gradient(90deg,#C0392B,#E67E22); }
        .profile-hero.petugas::before { background: linear-gradient(90deg,#2563EB,#7C3AED); }
        .profile-hero.peminjam::before{ background: linear-gradient(90deg,var(--green),#059669); }

        .profile-avatar-lg {
            width: 56px; height: 56px;
            border-radius: 12px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; font-weight: 700; color: white;
        }
        .profile-avatar-lg.admin   { background: linear-gradient(135deg,#C0392B,#E67E22); }
        .profile-avatar-lg.petugas { background: linear-gradient(135deg,#2563EB,#7C3AED); }
        .profile-avatar-lg.peminjam{ background: linear-gradient(135deg,var(--green),#059669); }

        .profile-name {
            font-family: var(--font-display);
            font-size: 1.15rem; font-weight: 700;
            color: var(--text-primary); line-height: 1.2;
        }
        .profile-email {
            font-size: 0.75rem; color: var(--text-muted);
            margin-top: 0.15rem; word-break: break-all;
        }

        /* Meta grid */
        .profile-meta { display: grid; grid-template-columns: 1fr 1fr; }
        .meta-item {
            padding: 0.85rem 1.1rem;
            border-right: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }
        .meta-item:nth-child(even) { border-right: none; }
        .meta-item:nth-last-child(-n+2) { border-bottom: none; }
        .meta-label {
            font-size: 0.65rem; font-weight: 700;
            letter-spacing: 0.1em; text-transform: uppercase;
            color: var(--text-muted);
        }
        .meta-val {
            font-size: 0.82rem; font-weight: 600;
            color: var(--text-primary); margin-top: 0.2rem;
        }
        .meta-val .verified { color: var(--green); }
        .meta-val .unverified { color: var(--amber); }
        .meta-val .active-dot { color: var(--green); }

        /* Actions card */
        .actions-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            padding: 1.1rem;
        }
        .actions-label {
            font-size: 0.68rem; font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase; letter-spacing: 0.1em;
            margin-bottom: 0.75rem;
        }

        @media (max-width: 900px) {
            .show-layout { grid-template-columns: 1fr; }
        }
    </style>

    <div class="show-layout">

        {{-- ── LEFT COLUMN ─────────────────────────── --}}
        <div>

            {{-- Stats strip --}}
            <div class="stats-strip">
                <div class="strip-item">
                    <div class="strip-val">{{ $user->peminjamans_count }}</div>
                    <div class="strip-label">Total Peminjaman</div>
                </div>
                <div class="strip-item">
                    <div class="strip-val {{ $user->peminjaman_aktif_count > 0 ? 'active' : '' }}">
                        {{ $user->peminjaman_aktif_count }}
                    </div>
                    <div class="strip-label">Sedang Aktif</div>
                </div>
                <div class="strip-item">
                    <div class="strip-val">{{ $user->peminjaman_selesai_count }}</div>
                    <div class="strip-label">Sudah Kembali</div>
                </div>
            </div>

            {{-- Riwayat Peminjaman --}}
            <div class="section-card">
                <div class="section-header">
                    <span class="section-title">Riwayat Peminjaman Terbaru</span>
                    @if($user->isPeminjam())
                        <a href="{{ route('admin.peminjamans.index', ['peminjam' => $user->id]) }}"
                           class="btn btn-ghost btn-sm">Lihat Semua →</a>
                    @endif
                </div>

                @if($riwayat->isEmpty())
                    <div class="empty-section">Belum ada riwayat peminjaman.</div>
                @else
                    @foreach($riwayat as $pinjam)
                        <div class="riwayat-row">
                            <div class="riwayat-icon">🛠</div>
                            <div style="flex:1;min-width:0">
                                <div class="riwayat-name">{{ $pinjam->alat->nama ?? '—' }}</div>
                                <div class="riwayat-date">
                                    {{ $pinjam->tanggal_pinjam->format('d M Y') }}
                                    → {{ $pinjam->tanggal_kembali_rencana->format('d M Y') }}
                                </div>
                            </div>
                            @php
                                $statusMap = [
                                    'menunggu'     => ['badge-amber', 'Menunggu'],
                                    'disetujui'    => ['badge-blue',  'Disetujui'],
                                    'ditolak'      => ['badge-red',   'Ditolak'],
                                    'dipinjam'     => ['badge-green', 'Dipinjam'],
                                    'dikembalikan' => ['badge-slate', 'Selesai'],
                                ];
                                [$badge, $label] = $statusMap[$pinjam->status] ?? ['badge-slate', $pinjam->status];
                            @endphp
                            <span class="badge {{ $badge }}">{{ $label }}</span>
                        </div>
                    @endforeach
                @endif
            </div>

            {{-- Log Aktivitas --}}
            <div class="section-card">
                <div class="section-header">
                    <span class="section-title">Log Aktivitas Terbaru</span>
                </div>

                @if($logAktivitas->isEmpty())
                    <div class="empty-section">Belum ada aktivitas tercatat.</div>
                @else
                    <div class="log-timeline">
                        @foreach($logAktivitas as $log)
                            <div class="log-item">
                                <div class="log-dot-wrap">
                                    <div class="log-dot {{ str_contains($log->aksi,'hapus') ? 'danger' : (str_contains($log->aksi,'tambah') ? 'success' : '') }}"></div>
                                </div>
                                <div class="log-body">
                                    <div class="log-desc">
                                        {{ $log->deskripsi }}
                                        <span class="log-module">{{ $log->modul }}</span>
                                    </div>
                                    <div class="log-meta">
                                        {{ $log->created_at->format('d M Y, H:i') }}
                                        · {{ $log->ip_address }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        {{-- ── RIGHT COLUMN ────────────────────────── --}}
        <div>

            {{-- Profile card --}}
            <div class="profile-card">
                <div class="profile-hero {{ $user->role }}">
                    <div class="profile-avatar-lg {{ $user->role }}">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div style="min-width:0">
                        <div class="profile-name">{{ $user->name }}</div>
                        <div class="profile-email">{{ $user->email }}</div>
                        <div style="margin-top:0.55rem;display:flex;gap:0.35rem;flex-wrap:wrap">
                            @php
                                $roleLabels = ['admin'=>'Administrator','petugas'=>'Petugas','peminjam'=>'Peminjam'];
                                $roleBadge  = ['admin'=>'badge-red','petugas'=>'badge-blue','peminjam'=>'badge-green'];
                            @endphp
                            <span class="badge {{ $roleBadge[$user->role] }}">
                                {{ $roleLabels[$user->role] }}
                            </span>
                            @if($user->id === auth()->id())
                                <span class="badge badge-slate">Anda</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="profile-meta">
                    <div class="meta-item">
                        <div class="meta-label">ID User</div>
                        <div class="meta-val">#{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-label">Role</div>
                        <div class="meta-val">{{ ucfirst($user->role) }}</div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-label">Bergabung</div>
                        <div class="meta-val">{{ $user->created_at->format('d M Y') }}</div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-label">Terakhir Update</div>
                        <div class="meta-val">{{ $user->updated_at->diffForHumans() }}</div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-label">Email Verified</div>
                        <div class="meta-val">
                            @if($user->email_verified_at)
                                <span class="verified">✓ Terverifikasi</span>
                            @else
                                <span class="unverified">⚠ Belum</span>
                            @endif
                        </div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-label">Status</div>
                        <div class="meta-val">
                            <span class="active-dot">● Aktif</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="actions-card">
                <div class="actions-label">Tindakan</div>

                <a href="{{ route('admin.users.edit', $user) }}"
                   class="btn btn-primary"
                   style="width:100%;justify-content:center;margin-bottom:0.5rem;display:flex">
                    ✎ Edit Data User
                </a>

                @if($user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                          onsubmit="return confirm('Yakin hapus user {{ $user->name }}? Tindakan ini tidak dapat dibatalkan.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger"
                                style="width:100%;justify-content:center;display:flex">
                            🗑 Hapus User
                        </button>
                    </form>
                @endif
            </div>

        </div>
    </div>

</x-admin-layout>