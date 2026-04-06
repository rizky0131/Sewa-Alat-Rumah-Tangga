<x-admin-layout title="Detail Pengembalian" breadcrumb="Detail Pengembalian">

    <x-slot name="header">
        <div>
            <div style="display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap">
                <h1 class="page-heading" style="font-size:1.4rem">Detail Pengembalian</h1>
                @if($pengembalian->is_tepat_waktu)
                    <span class="tag-tepat">✓ Tepat Waktu</span>
                @else
                    <span class="tag-late">⏰ Terlambat {{ $pengembalian->keterlambatan_hari }} hari</span>
                @endif
                @if($pengembalian->is_rusak)
                    <span class="tag-rusak">⚠ {{ str_replace('_',' ',ucfirst($pengembalian->kondisi_kembali)) }}</span>
                @endif
            </div>
            <p class="page-sub">Dicatat {{ $pengembalian->created_at->format('d M Y, H:i') }} oleh {{ $pengembalian->petugas->name ?? 'sistem' }}</p>
        </div>
        <div style="display:flex;gap:0.5rem">
            <a href="{{ route('admin.pengembalians.edit', $pengembalian) }}" class="btn btn-ghost">✎ Edit</a>
            <a href="{{ route('admin.pengembalians.index') }}" class="btn btn-ghost">← Kembali</a>
        </div>
    </x-slot>

    <style>
        .tag-tepat { background:rgba(16,185,129,0.12);color:#34D399;border:1px solid rgba(16,185,129,0.25);padding:0.28rem 0.75rem;border-radius:100px;font-size:0.72rem;font-weight:700; }
        .tag-late  { background:rgba(239,68,68,0.12);color:#FCA5A5;border:1px solid rgba(239,68,68,0.25);padding:0.28rem 0.75rem;border-radius:100px;font-size:0.72rem;font-weight:700; }
        .tag-rusak { background:rgba(212,168,67,0.12);color:#FCD34D;border:1px solid rgba(212,168,67,0.25);padding:0.28rem 0.75rem;border-radius:100px;font-size:0.72rem;font-weight:700; }

        .show-layout { display:grid;grid-template-columns:1fr 320px;gap:1.5rem;align-items:start; }

        /* Receipt card */
        .receipt-card {
            background:var(--ink-80);border:1px solid rgba(255,255,255,0.07);border-radius:8px;overflow:hidden;margin-bottom:1.2rem;
        }
        .receipt-header {
            background:linear-gradient(135deg,rgba(37,99,235,0.15),rgba(16,185,129,0.1));
            border-bottom:1px solid rgba(255,255,255,0.08);
            padding:1.3rem 1.5rem;
        }
        .receipt-title { font-size:1.1rem;font-weight:800;color:var(--cream);letter-spacing:-0.01em; }
        .receipt-sub   { font-size:0.72rem;color:var(--mist);margin-top:0.2rem; }
        .receipt-body  { padding:0 1.5rem; }
        .receipt-row   { display:flex;justify-content:space-between;align-items:center;padding:0.85rem 0;border-bottom:1px solid rgba(255,255,255,0.05); }
        .receipt-row:last-child { border-bottom:none; }
        .receipt-key   { font-size:0.78rem;color:var(--mist);font-weight:600; }
        .receipt-val   { font-size:0.85rem;font-weight:700;color:var(--silver); }
        .receipt-divider { border:none;border-top:2px dashed rgba(255,255,255,0.08);margin:0 1.5rem; }
        .receipt-total { padding:1rem 1.5rem;background:rgba(16,185,129,0.08); }

        /* Alat info block */
        .alat-block {
            display:flex;align-items:center;gap:1.1rem;
            padding:1.2rem 1.5rem;border-bottom:1px solid rgba(255,255,255,0.07);
        }
        .alat-thumb { width:56px;height:56px;border-radius:10px;overflow:hidden;flex-shrink:0;background:rgba(37,99,235,0.1);display:flex;align-items:center;justify-content:center;font-size:1.3rem; }
        .alat-thumb img { width:100%;height:100%;object-fit:cover; }

        /* Kondisi badge large */
        .kondisi-lg {
            display:inline-flex;align-items:center;gap:0.5rem;padding:0.5rem 1rem;
            border-radius:8px;font-size:0.85rem;font-weight:700;
        }
        .kond-baik         { background:rgba(16,185,129,0.15);color:#34D399;border:1px solid rgba(16,185,129,0.3); }
        .kond-rusak_ringan { background:rgba(212,168,67,0.15);color:#FCD34D;border:1px solid rgba(212,168,67,0.3); }
        .kond-rusak_sedang { background:rgba(249,115,22,0.15);color:#FDBA74;border:1px solid rgba(249,115,22,0.3); }
        .kond-rusak_berat  { background:rgba(239,68,68,0.15);color:#FCA5A5;border:1px solid rgba(239,68,68,0.3); }
        .kond-hilang       { background:rgba(139,92,246,0.15);color:#C4B5FD;border:1px solid rgba(139,92,246,0.3); }

        /* Tagihan breakdown */
        .tagihan-breakdown { background:var(--ink-60);border:1px solid rgba(255,255,255,0.1);border-radius:8px;overflow:hidden; }
        .tb-section { padding:0.75rem 1.1rem;background:rgba(255,255,255,0.03);border-bottom:1px solid rgba(255,255,255,0.07); }
        .tb-section-title { font-size:0.7rem;font-weight:800;text-transform:uppercase;letter-spacing:0.1em;color:var(--slate); }
        .tb-row { display:flex;justify-content:space-between;padding:0.65rem 1.1rem;border-bottom:1px solid rgba(255,255,255,0.05); }
        .tb-row:last-child { border-bottom:none; }
        .tb-key { font-size:0.76rem;color:var(--mist); }
        .tb-val { font-size:0.82rem;font-weight:700;color:var(--silver); }
        .tb-total-row { display:flex;justify-content:space-between;padding:1rem 1.1rem;background:rgba(16,185,129,0.08); }
        .tb-total-key { font-size:0.82rem;font-weight:700;color:var(--silver); }
        .tb-total-val { font-size:1.2rem;font-weight:800;color:var(--jade); }

        /* Proof photo */
        .proof-photo { width:100%;border-radius:8px;overflow:hidden;border:1px solid rgba(255,255,255,0.08); }
        .proof-photo img { width:100%;max-height:280px;object-fit:cover;display:block; }
        .proof-empty { padding:2rem;text-align:center;color:var(--mist);font-size:0.8rem;background:rgba(255,255,255,0.02);border-radius:8px;border:1px dashed rgba(255,255,255,0.1); }

        /* Info sidebar rows */
        .info-section { background:var(--ink-80);border:1px solid rgba(255,255,255,0.07);border-radius:8px;overflow:hidden;margin-bottom:1rem; }
        .info-row { display:flex;padding:0.7rem 1.1rem;border-bottom:1px solid rgba(255,255,255,0.05); }
        .info-row:last-child { border-bottom:none; }
        .info-key { font-size:0.72rem;color:var(--mist);font-weight:600;width:45%; }
        .info-val { font-size:0.8rem;color:var(--silver);flex:1; }

        @media(max-width:900px) { .show-layout { grid-template-columns:1fr; } }
    </style>

    <div class="show-layout">

        {{-- LEFT --}}
        <div>

            {{-- Receipt-style card --}}
            <div class="receipt-card">
                {{-- Alat block --}}
                <div class="alat-block">
                    <div class="alat-thumb">
                        @if($pengembalian->peminjaman->alat?->foto)
                            <img src="{{ asset('storage/'.$pengembalian->peminjaman->alat->foto) }}" alt="">
                        @else 🔧 @endif
                    </div>
                    <div>
                        <div style="font-size:1rem;font-weight:700;color:var(--cream)">
                            {{ $pengembalian->peminjaman->alat->nama ?? '—' }}
                        </div>
                        <div style="font-size:0.72rem;color:var(--mist)">
                            {{ $pengembalian->peminjaman->alat->kode ?? '' }}
                            · {{ $pengembalian->peminjaman->jumlah }} unit dikembalikan
                        </div>
                        <div style="margin-top:0.5rem">
                            @php
                                $kondisiLabels = ['baik'=>['✅','Baik'],'rusak_ringan'=>['⚠️','Rusak Ringan'],'rusak_sedang'=>['🔶','Rusak Sedang'],'rusak_berat'=>['❌','Rusak Berat'],'hilang'=>['💀','Hilang']];
                                [$kIcon,$kLabel] = $kondisiLabels[$pengembalian->kondisi_kembali] ?? ['',''];
                            @endphp
                            <span class="kondisi-lg kond-{{ $pengembalian->kondisi_kembali }}">
                                {{ $kIcon }} {{ $kLabel }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Receipt details --}}
                <div class="receipt-header">
                    <div class="receipt-title">Bukti Pengembalian</div>
                    <div class="receipt-sub">
                        Peminjaman {{ $pengembalian->peminjaman->nomor_pinjam }}
                        · {{ $pengembalian->peminjaman->peminjam->name }}
                    </div>
                </div>

                <div class="receipt-body">
                    <div class="receipt-row">
                        <span class="receipt-key">Tanggal Rencana Kembali</span>
                        <span class="receipt-val">{{ $pengembalian->peminjaman->tanggal_kembali_rencana->format('d M Y') }}</span>
                    </div>
                    <div class="receipt-row">
                        <span class="receipt-key">Tanggal Kembali Aktual</span>
                        <span class="receipt-val"
                              style="{{ !$pengembalian->is_tepat_waktu ? 'color:#FCA5A5' : 'color:var(--jade)' }}">
                            {{ $pengembalian->tanggal_kembali_aktual->format('d M Y') }}
                        </span>
                    </div>
                    <div class="receipt-row">
                        <span class="receipt-key">Keterlambatan</span>
                        @if($pengembalian->is_tepat_waktu)
                            <span class="receipt-val" style="color:var(--jade)">✓ Tepat Waktu</span>
                        @else
                            <span class="receipt-val" style="color:#FCA5A5">{{ $pengembalian->keterlambatan_hari }} hari</span>
                        @endif
                    </div>
                    @if($pengembalian->catatan)
                        <div class="receipt-row">
                            <span class="receipt-key">Catatan</span>
                            <span class="receipt-val" style="font-size:0.78rem;text-align:right;max-width:55%">{{ $pengembalian->catatan }}</span>
                        </div>
                    @endif
                    <div class="receipt-row">
                        <span class="receipt-key">Diproses oleh</span>
                        <span class="receipt-val">{{ $pengembalian->petugas->name ?? 'Sistem' }}</span>
                    </div>
                </div>

                <hr class="receipt-divider">

                <div class="receipt-total">
                    <div style="display:flex;justify-content:space-between;align-items:center">
                        <span style="font-size:0.85rem;font-weight:700;color:var(--silver)">Total Tagihan</span>
                        <span style="font-size:1.4rem;font-weight:800;color:{{ $pengembalian->total_tagihan > 0 ? '#FCA5A5' : 'var(--jade)' }}">
                            Rp {{ number_format($pengembalian->total_tagihan, 0, ',', '.') }}
                        </span>
                    </div>
                    @if($pengembalian->total_tagihan == 0)
                        <div style="font-size:0.72rem;color:var(--jade);margin-top:0.25rem">Tidak ada tagihan tambahan.</div>
                    @endif
                </div>
            </div>

            {{-- Foto bukti --}}
            <div class="card" style="margin-bottom:1rem">
                <div class="card-header"><span class="card-title">📷 Foto Bukti</span></div>
                <div style="padding:0.9rem">
                    @if($pengembalian->foto_bukti)
                        <div class="proof-photo">
                            <img src="{{ asset('storage/'.$pengembalian->foto_bukti) }}"
                                 alt="Foto bukti pengembalian"
                                 onclick="this.parentElement.style.cursor='zoom-in'"
                                 style="cursor:pointer">
                        </div>
                        <a href="{{ asset('storage/'.$pengembalian->foto_bukti) }}" target="_blank"
                           class="btn btn-ghost btn-sm" style="margin-top:0.5rem">
                            🔍 Lihat Fullsize
                        </a>
                    @else
                        <div class="proof-empty">📷 Tidak ada foto bukti</div>
                    @endif
                </div>
            </div>

        </div>

        {{-- RIGHT --}}
        <div>
            {{-- Tagihan breakdown --}}
            <div class="tagihan-breakdown" style="margin-bottom:1rem">
                <div class="tb-section"><div class="tb-section-title">Tagihan Sewa</div></div>
                <div class="tb-row">
                    <span class="tb-key">Total Biaya Sewa</span>
                    <span class="tb-val">Rp {{ number_format($pengembalian->peminjaman->total_biaya,0,',','.') }}</span>
                </div>

                <div class="tb-section"><div class="tb-section-title">Denda & Kerusakan</div></div>
                <div class="tb-row">
                    <span class="tb-key">Keterlambatan</span>
                    <span class="tb-val" style="{{ $pengembalian->denda > 0 ? 'color:#FCA5A5' : 'color:var(--jade)' }}">
                        {{ $pengembalian->keterlambatan_hari }} hari
                    </span>
                </div>
                <div class="tb-row">
                    <span class="tb-key">Denda Terlambat</span>
                    <span class="tb-val" style="{{ $pengembalian->denda > 0 ? 'color:#FCA5A5' : '' }}">
                        Rp {{ number_format($pengembalian->denda,0,',','.') }}
                    </span>
                </div>
                <div class="tb-row">
                    <span class="tb-key">Biaya Kerusakan</span>
                    <span class="tb-val" style="{{ $pengembalian->biaya_kerusakan > 0 ? 'color:#FCA5A5' : '' }}">
                        Rp {{ number_format($pengembalian->biaya_kerusakan,0,',','.') }}
                    </span>
                </div>
                <div class="tb-total-row">
                    <span class="tb-total-key">Total Tagihan Ekstra</span>
                    <span class="tb-total-val" style="{{ $pengembalian->total_tagihan > 0 ? 'color:#FCA5A5' : '' }}">
                        Rp {{ number_format($pengembalian->total_tagihan,0,',','.') }}
                    </span>
                </div>
            </div>

            {{-- Peminjaman link --}}
            <div class="info-section" style="margin-bottom:1rem">
                <div style="padding:0.85rem 1.1rem;border-bottom:1px solid rgba(255,255,255,0.07)">
                    <div style="font-size:0.78rem;font-weight:700;color:var(--cream)">Data Peminjaman</div>
                </div>
                <div class="info-row">
                    <span class="info-key">Nomor</span>
                    <span class="info-val" style="font-family:monospace;font-size:0.75rem;color:var(--accent-l)">
                        {{ $pengembalian->peminjaman->nomor_pinjam }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-key">Peminjam</span>
                    <span class="info-val">{{ $pengembalian->peminjaman->peminjam->name ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">Alat</span>
                    <span class="info-val">{{ $pengembalian->peminjaman->alat->nama ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-key">Jumlah</span>
                    <span class="info-val">{{ $pengembalian->peminjaman->jumlah }} unit</span>
                </div>
                <div style="padding:0.75rem 1.1rem">
                    <a href="{{ route('admin.peminjamans.show', $pengembalian->peminjaman) }}"
                       class="btn btn-ghost btn-sm" style="width:100%;justify-content:center">
                        Lihat Peminjaman →
                    </a>
                </div>
            </div>

            {{-- Actions --}}
            <div class="info-section" style="padding:1.1rem">
                <a href="{{ route('admin.pengembalians.edit', $pengembalian) }}"
                   class="btn btn-ghost" style="width:100%;justify-content:center;margin-bottom:0.5rem">
                    ✎ Edit Data Pengembalian
                </a>
                <a href="{{ route('admin.pengembalians.index') }}"
                   class="btn btn-ghost" style="width:100%;justify-content:center">
                    ← Semua Pengembalian
                </a>
            </div>
        </div>
    </div>

</x-admin-layout>