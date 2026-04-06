<x-admin-layout title="Detail Peminjaman" breadcrumb="Detail Peminjaman">

    <x-slot name="header">
        <div>
            <div style="display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap">
                <h1 class="page-heading" style="font-size:1.4rem;font-family:monospace">
                    {{ $peminjaman->nomor_pinjam }}
                </h1>
                @if($peminjaman->is_terlambat)
                    <span class="status-pill sp-terlambat">⚠ Terlambat {{ $peminjaman->keterlambatan_hari }} hari</span>
                @else
                    <span class="status-pill sp-{{ $peminjaman->status }}">{{ ucfirst($peminjaman->status) }}</span>
                @endif
            </div>
            <p class="page-sub">Dibuat {{ $peminjaman->created_at->format('d M Y, H:i') }}</p>
        </div>
        <div style="display:flex;gap:0.5rem">
            @if(in_array($peminjaman->status, ['menunggu','disetujui','dipinjam']))
                <a href="{{ route('admin.peminjamans.edit', $peminjaman) }}" class="btn btn-ghost">✎ Edit</a>
            @endif
            <a href="{{ route('admin.peminjamans.index') }}" class="btn btn-ghost">← Kembali</a>
        </div>
    </x-slot>

    <style>
        .status-pill { display:inline-flex;align-items:center;gap:0.35rem;padding:0.28rem 0.7rem;border-radius:100px;font-size:0.72rem;font-weight:700; }
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
        .sp-terlambat    { background:rgba(239,68,68,0.18);color:#FCA5A5;border:1px solid rgba(239,68,68,0.4); }
        .sp-terlambat::before    { background:var(--danger);animation:pulse-dot 1s infinite; }
        @keyframes pulse-dot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.4;transform:scale(0.7)} }

        .show-layout { display:grid;grid-template-columns:1fr 310px;gap:1.5rem;align-items:start; }

        /* Approval action panel */
        .action-panel {
            border:1px solid;border-radius:8px;padding:1.3rem;margin-bottom:1.2rem;
        }
        .action-panel.menunggu { background:rgba(212,168,67,0.06);border-color:rgba(212,168,67,0.3); }
        .action-panel.disetujui { background:rgba(59,130,246,0.06);border-color:rgba(59,130,246,0.3); }
        .action-panel.dipinjam  { background:rgba(16,185,129,0.06);border-color:rgba(16,185,129,0.3); }
        .action-panel.ditolak   { background:rgba(239,68,68,0.06);border-color:rgba(239,68,68,0.3); }
        .ap-title { font-size:0.88rem;font-weight:700;margin-bottom:0.5rem; }
        .ap-desc  { font-size:0.78rem;color:var(--mist);margin-bottom:1rem;line-height:1.5; }
        .ap-actions { display:flex;gap:0.5rem; }

        /* Timeline */
        .timeline { padding:0.5rem 0; }
        .tl-item { display:flex;gap:0.9rem;padding-bottom:1.2rem;position:relative; }
        .tl-item:not(:last-child)::before { content:'';position:absolute;left:15px;top:32px;bottom:0;width:1px;background:rgba(255,255,255,0.08); }
        .tl-dot { width:32px;height:32px;border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:0.85rem;border:2px solid; }
        .tl-dot.done    { background:rgba(16,185,129,0.15);border-color:var(--jade);color:var(--jade); }
        .tl-dot.active  { background:rgba(37,99,235,0.15);border-color:var(--accent);color:var(--accent-l);animation:pulse-ring 1.5s infinite; }
        .tl-dot.pending { background:rgba(255,255,255,0.04);border-color:rgba(255,255,255,0.1);color:var(--slate); }
        .tl-dot.rejected{ background:rgba(239,68,68,0.15);border-color:var(--danger);color:var(--danger); }
        @keyframes pulse-ring { 0%,100%{box-shadow:0 0 0 0 rgba(37,99,235,0.3)} 50%{box-shadow:0 0 0 5px rgba(37,99,235,0)} }
        .tl-body { flex:1;padding-top:0.35rem; }
        .tl-title { font-size:0.83rem;font-weight:700;color:var(--silver); }
        .tl-time  { font-size:0.7rem;color:var(--mist);margin-top:0.15rem; }
        .tl-note  { font-size:0.75rem;color:var(--mist);margin-top:0.35rem;padding:0.5rem 0.75rem;background:rgba(255,255,255,0.04);border-radius:5px;border-left:2px solid rgba(255,255,255,0.1); }

        /* Info block */
        .info-section { background:var(--ink-80);border:1px solid rgba(255,255,255,0.07);border-radius:8px;overflow:hidden;margin-bottom:1.2rem; }
        .info-section-head { padding:0.9rem 1.2rem;border-bottom:1px solid rgba(255,255,255,0.06);font-size:0.82rem;font-weight:700;color:var(--cream); }
        .info-row { display:flex;padding:0.75rem 1.2rem;border-bottom:1px solid rgba(255,255,255,0.04); }
        .info-row:last-child { border-bottom:none; }
        .info-key { font-size:0.78rem;color:var(--mist);width:45%;font-weight:600; }
        .info-val { font-size:0.82rem;color:var(--silver);flex:1; }

        /* Alat card */
        .alat-featured {
            display:flex;align-items:center;gap:1rem;padding:1.2rem;
            background:rgba(37,99,235,0.06);border:1px solid rgba(37,99,235,0.2);
            border-radius:8px;text-decoration:none;margin-bottom:1.2rem;
            transition:border-color 0.15s;
        }
        .alat-featured:hover { border-color:rgba(37,99,235,0.4); }
        .alat-featured-thumb {
            width:60px;height:60px;border-radius:10px;overflow:hidden;flex-shrink:0;
            background:rgba(37,99,235,0.1);display:flex;align-items:center;justify-content:center;font-size:1.4rem;
        }
        .alat-featured-thumb img { width:100%;height:100%;object-fit:cover; }

        /* Biaya breakdown */
        .biaya-row { display:flex;justify-content:space-between;padding:0.6rem 0;border-bottom:1px solid rgba(255,255,255,0.04); }
        .biaya-row:last-child { border-bottom:none;font-weight:700;font-size:1rem;color:var(--cream);padding-top:0.8rem;border-top:1px solid rgba(255,255,255,0.1); }
        .biaya-key { font-size:0.8rem;color:var(--mist); }
        .biaya-val { font-size:0.85rem;color:var(--silver);font-weight:600; }

        /* Return info card */
        .return-card { background:rgba(16,185,129,0.08);border:1px solid rgba(16,185,129,0.25);border-radius:8px;padding:1.2rem; }

        @media(max-width:900px) { .show-layout { grid-template-columns:1fr; } }
    </style>

    <div class="show-layout">

        {{-- LEFT --}}
        <div>

            {{-- Action Panel (conditional) --}}
            @if($peminjaman->status === 'menunggu')
                <div class="action-panel menunggu">
                    <div class="ap-title" style="color:#FCD34D">⏳ Menunggu Persetujuan Anda</div>
                    <div class="ap-desc">
                        Peminjaman ini diajukan oleh <strong>{{ $peminjaman->peminjam->name }}</strong>
                        untuk alat <strong>{{ $peminjaman->alat->nama }}</strong>.
                        Silakan setujui atau tolak berdasarkan ketersediaan stok dan kebijakan peminjaman.
                    </div>
                    <div class="ap-actions">
                        <form method="POST" action="{{ route('admin.peminjamans.setujui', $peminjaman) }}" style="flex:1">
                            @csrf
                            <input type="hidden" name="catatan" value="">
                            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                                ✓ Setujui Peminjaman
                            </button>
                        </form>
                        <button type="button" class="btn btn-danger" style="flex:1;justify-content:center"
                                onclick="showTolakPanel()">
                            ✕ Tolak
                        </button>
                    </div>

                    {{-- Tolak form (hidden until click) --}}
                    <div id="tolakPanel" style="display:none;margin-top:1rem">
                        <form method="POST" action="{{ route('admin.peminjamans.tolak', $peminjaman) }}">
                            @csrf
                            <div class="form-group">
                                <label class="form-label">Alasan Penolakan <span style="color:var(--danger)">*</span></label>
                                <textarea name="catatan" class="form-textarea" rows="2"
                                          placeholder="Jelaskan alasan penolakan...">{{ old('catatan') }}</textarea>
                                @error('catatan') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div style="display:flex;gap:0.5rem">
                                <button type="submit" class="btn btn-danger">Konfirmasi Penolakan</button>
                                <button type="button" onclick="hideTolakPanel()" class="btn btn-ghost">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>

            @elseif($peminjaman->status === 'disetujui')
                <div class="action-panel disetujui">
                    <div class="ap-title" style="color:#93C5FD">✓ Peminjaman Disetujui — Serahkan Alat</div>
                    <div class="ap-desc">
                        Alat belum diserahkan ke peminjam. Klik tombol di bawah setelah alat secara fisik sudah diberikan.
                    </div>
                    <form method="POST" action="{{ route('admin.peminjamans.tandai', $peminjaman) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">📦 Tandai Sudah Diserahkan</button>
                    </form>
                </div>

            @elseif($peminjaman->status === 'dipinjam')
                <div class="action-panel dipinjam">
                    <div class="ap-title" style="color:#34D399">
                        📦 Alat Sedang Dipinjam
                        @if($peminjaman->is_terlambat)
                            <span class="status-pill sp-terlambat" style="margin-left:0.5rem">⚠ Terlambat {{ $peminjaman->keterlambatan_hari }} hari</span>
                        @endif
                    </div>
                    <div class="ap-desc">
                        Batas kembali: <strong>{{ $peminjaman->tanggal_kembali_rencana->format('d M Y') }}</strong>
                        @if(!$peminjaman->is_terlambat)
                            · Sisa {{ $peminjaman->tanggal_kembali_rencana->diffForHumans() }}
                        @endif
                    </div>
                    <a href="{{ route('admin.pengembalians.create', ['peminjaman' => $peminjaman->id]) }}"
                       class="btn btn-primary">↩ Proses Pengembalian</a>
                </div>

            @elseif($peminjaman->status === 'ditolak')
                <div class="action-panel ditolak">
                    <div class="ap-title" style="color:#FCA5A5">✕ Peminjaman Ditolak</div>
                    <div class="ap-desc">
                        Ditolak oleh <strong>{{ $peminjaman->petugas->name ?? '—' }}</strong>
                        pada {{ $peminjaman->disetujui_at?->format('d M Y, H:i') }}.<br>
                        <em>Alasan: {{ $peminjaman->catatan_petugas ?? '—' }}</em>
                    </div>
                </div>
            @endif

            {{-- Alat featured --}}
            <a href="{{ route('admin.alats.show', $peminjaman->alat) }}" class="alat-featured">
                <div class="alat-featured-thumb">
                    @if($peminjaman->alat?->foto)
                        <img src="{{ asset('storage/'.$peminjaman->alat->foto) }}" alt="">
                    @else 🔧 @endif
                </div>
                <div>
                    <div style="font-size:0.95rem;font-weight:700;color:var(--cream)">{{ $peminjaman->alat->nama ?? '—' }}</div>
                    <div style="font-size:0.75rem;color:var(--mist)">
                        {{ $peminjaman->alat->kode ?? '' }}
                        · {{ $peminjaman->alat->kategori->ikon ?? '' }} {{ $peminjaman->alat->kategori->nama ?? '' }}
                    </div>
                    <div style="margin-top:0.35rem;display:flex;gap:0.35rem">
                        <span class="badge badge-green" style="font-size:0.62rem">{{ $peminjaman->jumlah }} unit</span>
                        <span class="badge badge-slate" style="font-size:0.62rem">Rp {{ number_format($peminjaman->alat->harga_sewa_per_hari,0,',','.') }}/hari</span>
                    </div>
                </div>
                <span style="margin-left:auto;color:var(--slate)">→</span>
            </a>

            {{-- Details --}}
            <div class="info-section">
                <div class="info-section-head">Detail Transaksi</div>
                <div class="info-row">
                    <div class="info-key">No. Transaksi</div>
                    <div class="info-val" style="font-family:monospace;color:var(--accent-l)">{{ $peminjaman->nomor_pinjam }}</div>
                </div>
                <div class="info-row">
                    <div class="info-key">Peminjam</div>
                    <div class="info-val">
                        <a href="{{ route('admin.users.show', $peminjaman->peminjam) }}" style="color:var(--accent-l);text-decoration:none">
                            {{ $peminjaman->peminjam->name ?? '—' }}
                        </a>
                        <div style="font-size:0.7rem;color:var(--slate)">{{ $peminjaman->peminjam->email ?? '' }}</div>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-key">Tanggal Pinjam</div>
                    <div class="info-val">{{ $peminjaman->tanggal_pinjam->format('d M Y') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-key">Tanggal Kembali</div>
                    <div class="info-val">
                        {{ $peminjaman->tanggal_kembali_rencana->format('d M Y') }}
                        <span class="badge badge-slate" style="font-size:0.62rem;margin-left:0.3rem">{{ $peminjaman->durasi_hari }} hari</span>
                    </div>
                </div>
                @if($peminjaman->tujuan_peminjaman)
                    <div class="info-row">
                        <div class="info-key">Tujuan Peminjaman</div>
                        <div class="info-val" style="font-size:0.78rem;line-height:1.5">{{ $peminjaman->tujuan_peminjaman }}</div>
                    </div>
                @endif
                @if($peminjaman->catatan_petugas)
                    <div class="info-row">
                        <div class="info-key">Catatan Petugas</div>
                        <div class="info-val" style="font-size:0.78rem;line-height:1.5">{{ $peminjaman->catatan_petugas }}</div>
                    </div>
                @endif
                @if($peminjaman->petugas)
                    <div class="info-row">
                        <div class="info-key">Diproses oleh</div>
                        <div class="info-val">{{ $peminjaman->petugas->name }}</div>
                    </div>
                @endif
            </div>

            {{-- Return info if done --}}
            @if($peminjaman->pengembalian)
                <div class="return-card">
                    <div style="font-size:0.82rem;font-weight:700;color:#34D399;margin-bottom:0.75rem">↩ Informasi Pengembalian</div>
                    <div class="info-row" style="padding:0.5rem 0;border-bottom:1px solid rgba(255,255,255,0.06)">
                        <div class="info-key" style="font-size:0.75rem">Tanggal Kembali Aktual</div>
                        <div class="info-val">{{ $peminjaman->pengembalian->tanggal_kembali_aktual }}</div>
                    </div>
                    <div class="info-row" style="padding:0.5rem 0;border-bottom:1px solid rgba(255,255,255,0.06)">
                        <div class="info-key" style="font-size:0.75rem">Kondisi Kembali</div>
                        <div class="info-val">{{ str_replace('_',' ',ucfirst($peminjaman->pengembalian->kondisi_kembali)) }}</div>
                    </div>
                    @if($peminjaman->pengembalian->denda > 0)
                        <div class="info-row" style="padding:0.5rem 0">
                            <div class="info-key" style="font-size:0.75rem">Denda</div>
                            <div class="info-val" style="color:#FCA5A5">Rp {{ number_format($peminjaman->pengembalian->denda,0,',','.') }}</div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        {{-- RIGHT --}}
        <div>

            {{-- Status timeline --}}
            <div class="info-section" style="margin-bottom:1rem">
                <div class="info-section-head">Status Perjalanan</div>
                <div style="padding:1rem 1.2rem">
                    <div class="timeline">
                        @php
                            $flowSteps = [
                                'menunggu'     => ['⏳','Pengajuan Masuk',  $peminjaman->created_at->format('d M Y, H:i')],
                                'disetujui'    => ['✓', 'Disetujui',        $peminjaman->disetujui_at?->format('d M Y, H:i')],
                                'dipinjam'     => ['📦','Alat Diserahkan',  null],
                                'dikembalikan' => ['↩', 'Alat Dikembalikan', $peminjaman->pengembalian?->created_at?->format('d M Y, H:i')],
                            ];
                            $statusOrder = ['menunggu'=>0,'disetujui'=>1,'dipinjam'=>2,'dikembalikan'=>3];
                            $curIdx = $statusOrder[$peminjaman->status] ?? 0;
                            if ($peminjaman->status === 'ditolak') {
                                $flowSteps = [
                                    'menunggu' => ['⏳','Pengajuan Masuk', $peminjaman->created_at->format('d M Y, H:i')],
                                    'ditolak'  => ['✕', 'Ditolak',         $peminjaman->disetujui_at?->format('d M Y, H:i')],
                                ];
                                $curIdx = 1;
                            }
                        @endphp

                        @foreach($flowSteps as $step => [$icon, $label, $time])
                            @php
                                $idx = $statusOrder[$step] ?? 0;
                                $isDone    = ($peminjaman->status !== 'ditolak') && ($idx < $curIdx);
                                $isActive  = ($step === $peminjaman->status);
                                $isPending = !$isDone && !$isActive;
                                $isRejected= ($step === 'ditolak');
                                $dotClass  = $isRejected ? 'rejected' : ($isDone ? 'done' : ($isActive ? 'active' : 'pending'));
                            @endphp
                            <div class="tl-item">
                                <div class="tl-dot {{ $dotClass }}">{{ $isDone ? '✓' : $icon }}</div>
                                <div class="tl-body">
                                    <div class="tl-title" style="{{ $isPending ? 'color:var(--slate)' : '' }}">{{ $label }}</div>
                                    @if($time)
                                        <div class="tl-time">{{ $time }}</div>
                                    @elseif($isActive)
                                        <div class="tl-time" style="color:var(--accent-l)">Saat ini</div>
                                    @else
                                        <div class="tl-time">—</div>
                                    @endif
                                    @if($step === 'disetujui' && $peminjaman->petugas && !$isPending)
                                        <div class="tl-note">oleh {{ $peminjaman->petugas->name }}</div>
                                    @endif
                                    @if($step === 'ditolak' && $peminjaman->catatan_petugas)
                                        <div class="tl-note">{{ $peminjaman->catatan_petugas }}</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Biaya breakdown --}}
            <div class="info-section" style="margin-bottom:1rem">
                <div class="info-section-head">Rincian Biaya</div>
                <div style="padding:1rem 1.2rem">
                    <div class="biaya-row">
                        <span class="biaya-key">Harga Sewa</span>
                        <span class="biaya-val">Rp {{ number_format($peminjaman->alat->harga_sewa_per_hari,0,',','.') }}/hari</span>
                    </div>
                    <div class="biaya-row">
                        <span class="biaya-key">Jumlah Unit</span>
                        <span class="biaya-val">{{ $peminjaman->jumlah }} unit</span>
                    </div>
                    <div class="biaya-row">
                        <span class="biaya-key">Durasi</span>
                        <span class="biaya-val">{{ $peminjaman->durasi_hari }} hari</span>
                    </div>
                    @if($peminjaman->pengembalian?->denda > 0)
                        <div class="biaya-row">
                            <span class="biaya-key">Denda Keterlambatan</span>
                            <span class="biaya-val" style="color:#FCA5A5">+ Rp {{ number_format($peminjaman->pengembalian->denda,0,',','.') }}</span>
                        </div>
                    @endif
                    <div class="biaya-row" style="margin-top:0.5rem">
                        <span class="biaya-key">Total</span>
                        <span style="font-size:1.2rem;font-weight:800;color:var(--jade)">
                            Rp {{ number_format($peminjaman->total_biaya + ($peminjaman->pengembalian?->denda ?? 0), 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="info-section" style="padding:1.1rem">
                @if(in_array($peminjaman->status, ['menunggu','disetujui','dipinjam']))
                    <a href="{{ route('admin.peminjamans.edit', $peminjaman) }}"
                       class="btn btn-ghost" style="width:100%;justify-content:center;margin-bottom:0.5rem">
                        ✎ Edit Peminjaman
                    </a>
                @endif
                @if(in_array($peminjaman->status, ['menunggu','ditolak']))
                    <form method="POST" action="{{ route('admin.peminjamans.destroy', $peminjaman) }}"
                          onsubmit="return confirm('Hapus peminjaman {{ $peminjaman->nomor_pinjam }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center">
                            🗑 Hapus
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <script>
        function showTolakPanel() { document.getElementById('tolakPanel').style.display = 'block'; }
        function hideTolakPanel() { document.getElementById('tolakPanel').style.display = 'none'; }
    </script>

</x-admin-layout>