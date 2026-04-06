<x-admin-layout title="Detail Alat" breadcrumb="Detail Alat">

    <x-slot name="header">
        <div style="display:flex;align-items:center;gap:1.1rem">
            <div class="alat-hero-thumb">
                @if($alat->foto)
                    <img src="{{ asset('storage/'.$alat->foto) }}" alt="{{ $alat->nama }}">
                @else
                    🔧
                @endif
            </div>
            <div>
                <div style="display:flex;align-items:center;gap:0.6rem;flex-wrap:wrap">
                    <h1 class="page-heading" style="font-size:1.45rem">{{ $alat->nama }}</h1>
                    <span class="badge {{ $alat->status === 'aktif' ? 'badge-green' : 'badge-slate' }}">{{ ucfirst($alat->status) }}</span>
                    @php $kondisiBadge = ['baik'=>'badge-green','rusak_ringan'=>'badge-amber','rusak_berat'=>'badge-red','perbaikan'=>'badge-blue']; @endphp
                    <span class="badge {{ $kondisiBadge[$alat->kondisi] ?? 'badge-slate' }}">{{ str_replace('_',' ',ucfirst($alat->kondisi)) }}</span>
                </div>
                <p class="page-sub">
                    <code style="font-size:0.75rem">{{ $alat->kode }}</code>
                    @if($alat->merk) · {{ $alat->merk }} @endif
                    · {{ $alat->kategori->ikon ?? '' }} {{ $alat->kategori->nama ?? '—' }}
                </p>
            </div>
        </div>
        <div style="display:flex;gap:0.5rem">
            <a href="{{ route('admin.alats.edit', $alat) }}" class="btn btn-primary">✎ Edit</a>
            <a href="{{ route('admin.alats.index') }}" class="btn btn-ghost">← Kembali</a>
        </div>
    </x-slot>

    <style>
        .alat-hero-thumb {
            width:60px;height:60px;border-radius:12px;overflow:hidden;flex-shrink:0;
            background:rgba(37,99,235,0.1);border:1px solid rgba(37,99,235,0.2);
            display:flex;align-items:center;justify-content:center;font-size:1.6rem;
        }
        .alat-hero-thumb img { width:100%;height:100%;object-fit:cover; }

        .show-layout { display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start; }

        /* Stats strip */
        .alat-stats { display:grid;grid-template-columns:repeat(4,1fr);gap:0;background:var(--ink-80);border:1px solid rgba(255,255,255,0.07);border-radius:8px;overflow:hidden;margin-bottom:1.2rem; }
        .as-item { padding:1rem;text-align:center;border-right:1px solid rgba(255,255,255,0.06); }
        .as-item:last-child { border-right:none; }
        .as-val   { font-family:var(--font-display);font-size:1.75rem;font-weight:700;color:var(--cream); }
        .as-label { font-size:0.68rem;color:var(--mist);margin-top:0.1rem; }

        /* Photo gallery */
        .alat-photo-lg {
            width:100%;height:220px;border-radius:8px;overflow:hidden;
            background:rgba(37,99,235,0.08);border:1px solid rgba(37,99,235,0.15);
            display:flex;align-items:center;justify-content:center;font-size:4rem;
            margin-bottom:1.2rem;
        }
        .alat-photo-lg img { width:100%;height:100%;object-fit:cover; }

        /* Info table */
        .info-table { width:100%;border-collapse:collapse; }
        .info-table tr { border-bottom:1px solid rgba(255,255,255,0.04); }
        .info-table tr:last-child { border-bottom:none; }
        .info-table td { padding:0.75rem 0;font-size:0.82rem; }
        .info-table td:first-child { color:var(--mist);width:45%;font-weight:600; }
        .info-table td:last-child  { color:var(--silver); }

        /* Riwayat */
        .riwayat-row {
            display:flex;align-items:center;gap:0.9rem;padding:0.85rem 1.3rem;
            border-bottom:1px solid rgba(255,255,255,0.04);transition:background 0.12s;
        }
        .riwayat-row:hover { background:rgba(255,255,255,0.03); }
        .riwayat-row:last-child { border-bottom:none; }
        .riwayat-avatar {
            width:34px;height:34px;border-radius:8px;flex-shrink:0;
            background:linear-gradient(135deg,#10B981,#06B6D4);
            display:flex;align-items:center;justify-content:center;
            font-size:0.75rem;font-weight:800;color:white;
        }
        .riwayat-user  { font-size:0.83rem;font-weight:700;color:var(--silver); }
        .riwayat-dates { font-size:0.7rem;color:var(--mist); }
        .riwayat-biaya { font-size:0.78rem;font-weight:700;color:var(--jade);text-align:right; }

        /* Related alats */
        .related-item {
            display:flex;align-items:center;gap:0.75rem;padding:0.75rem;
            border-radius:7px;text-decoration:none;transition:background 0.15s;
        }
        .related-item:hover { background:rgba(255,255,255,0.04); }
        .related-thumb {
            width:38px;height:38px;border-radius:7px;overflow:hidden;flex-shrink:0;
            background:rgba(37,99,235,0.1);border:1px solid rgba(37,99,235,0.15);
            display:flex;align-items:center;justify-content:center;font-size:0.95rem;
        }
        .related-thumb img { width:100%;height:100%;object-fit:cover; }

        /* Stok visual */
        .stok-visual {
            display:flex;align-items:center;gap:0.75rem;padding:1rem;
            background:rgba(255,255,255,0.03);border-radius:7px;border:1px solid rgba(255,255,255,0.07);
            margin-bottom:1rem;
        }
        .stok-circle {
            width:56px;height:56px;border-radius:50%;flex-shrink:0;
            display:flex;align-items:center;justify-content:center;flex-direction:column;
            font-family:var(--font-display);font-size:1.2rem;font-weight:700;
            border:3px solid;
        }
        .stok-circle-sub { font-size:0.6rem;font-weight:400;line-height:1;color:var(--mist); }

        @media(max-width:900px) { .show-layout { grid-template-columns:1fr; } .alat-stats { grid-template-columns:1fr 1fr; } }
    </style>

    <div class="show-layout">

        {{-- LEFT --}}
        <div>
            {{-- Stats --}}
            <div class="alat-stats">
                <div class="as-item">
                    <div class="as-val">{{ $alat->peminjamans_count }}</div>
                    <div class="as-label">Total Dipinjam</div>
                </div>
                <div class="as-item">
                    <div class="as-val" style="{{ $alat->peminjaman_aktif_count > 0 ? 'color:var(--jade)' : '' }}">
                        {{ $alat->peminjaman_aktif_count }}
                    </div>
                    <div class="as-label">Sedang Dipinjam</div>
                </div>
                <div class="as-item">
                    <div class="as-val">{{ $alat->peminjaman_selesai_count }}</div>
                    <div class="as-label">Selesai Kembali</div>
                </div>
                <div class="as-item">
                    <div class="as-val" style="{{ $alat->stok_tersedia == 0 ? 'color:var(--danger)' : 'color:var(--jade)' }}">
                        {{ $alat->stok_tersedia }}
                    </div>
                    <div class="as-label">Stok Tersedia</div>
                </div>
            </div>

            {{-- Photo --}}
            <div class="alat-photo-lg">
                @if($alat->foto)
                    <img src="{{ asset('storage/'.$alat->foto) }}" alt="{{ $alat->nama }}">
                @else
                    🔧
                @endif
            </div>

            {{-- Riwayat --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Riwayat Peminjaman Terbaru</span>
                    <a href="{{ route('admin.peminjamans.index', ['alat' => $alat->id]) }}"
                       class="btn btn-ghost btn-sm">Lihat Semua →</a>
                </div>

                @if($riwayat->isEmpty())
                    <div style="padding:2rem;text-align:center;color:var(--mist);font-size:0.82rem">
                        Belum pernah dipinjam.
                    </div>
                @else
                    @foreach($riwayat as $pinjam)
                        <div class="riwayat-row">
                            <div class="riwayat-avatar">
                                {{ strtoupper(substr($pinjam->peminjam->name ?? '?', 0, 2)) }}
                            </div>
                            <div style="flex:1;min-width:0">
                                <div class="riwayat-user">{{ $pinjam->peminjam->name ?? '—' }}</div>
                                <div class="riwayat-dates">
                                    {{ $pinjam->tanggal_pinjam->format('d M Y') }}
                                    → {{ $pinjam->tanggal_kembali_rencana->format('d M Y') }}
                                    · {{ $pinjam->jumlah }} unit
                                </div>
                            </div>
                            @php
                                $st = ['menunggu'=>['badge-amber','Menunggu'],'disetujui'=>['badge-blue','Disetujui'],'ditolak'=>['badge-red','Ditolak'],'dipinjam'=>['badge-green','Dipinjam'],'dikembalikan'=>['badge-slate','Selesai']];
                                [$badgeClass,$badgeLabel] = $st[$pinjam->status] ?? ['badge-slate',$pinjam->status];
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                            <div class="riwayat-biaya">
                                Rp {{ number_format($pinjam->total_biaya, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- RIGHT --}}
        <div>
            {{-- Stok visual --}}
            <div class="stok-visual">
                @php
                    $pct = $alat->stok_total > 0 ? ($alat->stok_tersedia / $alat->stok_total * 100) : 0;
                    $borderColor = $pct == 0 ? 'var(--danger)' : ($pct < 30 ? 'var(--gold)' : 'var(--jade)');
                    $textColor   = $borderColor;
                @endphp
                <div class="stok-circle" style="color:{{ $textColor }};border-color:{{ $borderColor }}">
                    {{ $alat->stok_tersedia }}
                    <span class="stok-circle-sub">tersedia</span>
                </div>
                <div>
                    <div style="font-size:0.82rem;font-weight:700;color:var(--cream)">
                        {{ $alat->stok_tersedia }} / {{ $alat->stok_total }} unit
                    </div>
                    <div style="font-size:0.7rem;color:var(--mist);margin-top:0.15rem">
                        {{ round($pct) }}% tersedia untuk dipinjam
                    </div>
                    <div style="width:100%;height:5px;background:rgba(255,255,255,0.08);border-radius:10px;margin-top:0.5rem;overflow:hidden">
                        <div style="width:{{ $pct }}%;height:100%;background:{{ $borderColor }};border-radius:10px;transition:width 0.5s"></div>
                    </div>
                </div>
            </div>

            {{-- Info card --}}
            <div class="card" style="margin-bottom:1rem">
                <div class="card-body">
                    <table class="info-table">
                        <tr><td>Kode</td><td><code>{{ $alat->kode }}</code></td></tr>
                        <tr><td>Kategori</td><td>{{ $alat->kategori->ikon ?? '' }} {{ $alat->kategori->nama ?? '—' }}</td></tr>
                        <tr><td>Merk</td><td>{{ $alat->merk ?? '—' }}</td></tr>
                        <tr>
                            <td>Harga Sewa</td>
                            <td style="color:var(--jade);font-weight:700">
                                Rp {{ number_format($alat->harga_sewa_per_hari, 0, ',', '.') }} / hari
                            </td>
                        </tr>
                        <tr>
                            <td>Denda</td>
                            <td>{{ $alat->denda_per_hari > 0 ? 'Rp '.number_format($alat->denda_per_hari,0,',','.').'/hari' : 'Tidak ada' }}</td>
                        </tr>
                        <tr><td>Kondisi</td><td><span class="badge {{ $kondisiBadge[$alat->kondisi] ?? 'badge-slate' }}">{{ str_replace('_',' ',ucfirst($alat->kondisi)) }}</span></td></tr>
                        <tr><td>Status</td><td><span class="badge {{ $alat->status==='aktif' ? 'badge-green' : 'badge-slate' }}">{{ ucfirst($alat->status) }}</span></td></tr>
                        <tr><td>Ditambahkan</td><td>{{ $alat->created_at->format('d M Y') }}</td></tr>
                    </table>

                    @if($alat->deskripsi)
                        <div style="margin-top:0.9rem;padding-top:0.9rem;border-top:1px solid rgba(255,255,255,0.06)">
                            <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--slate);margin-bottom:0.4rem">Deskripsi</div>
                            <p style="font-size:0.8rem;color:var(--mist);line-height:1.6">{{ $alat->deskripsi }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            <div class="card" style="padding:1.1rem;margin-bottom:1rem">
                <a href="{{ route('admin.alats.edit', $alat) }}"
                   class="btn btn-primary" style="width:100%;justify-content:center;margin-bottom:0.5rem">
                    ✎ Edit Alat
                </a>
                @if($alat->peminjaman_aktif_count == 0)
                    <form method="POST" action="{{ route('admin.alats.destroy', $alat) }}"
                          onsubmit="return confirm('Hapus alat {{ $alat->nama }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center">
                            🗑 Hapus Alat
                        </button>
                    </form>
                @else
                    <div style="font-size:0.72rem;text-align:center;color:var(--mist);padding:0.5rem;background:rgba(239,68,68,0.06);border:1px solid rgba(239,68,68,0.15);border-radius:5px">
                        Tidak bisa dihapus — ada {{ $alat->peminjaman_aktif_count }} peminjaman aktif
                    </div>
                @endif
            </div>

            {{-- Related alats --}}
            @if($related->count() > 0)
                <div class="card">
                    <div class="card-header"><span class="card-title">Alat Lain di Kategori Ini</span></div>
                    <div style="padding:0.5rem">
                        @foreach($related as $rel)
                            <a href="{{ route('admin.alats.show', $rel) }}" class="related-item">
                                <div class="related-thumb">
                                    @if($rel->foto)
                                        <img src="{{ asset('storage/'.$rel->foto) }}" alt="{{ $rel->nama }}">
                                    @else 🔧 @endif
                                </div>
                                <div style="flex:1;min-width:0">
                                    <div style="font-size:0.82rem;font-weight:700;color:var(--silver);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $rel->nama }}</div>
                                    <div style="font-size:0.68rem;color:var(--slate)">{{ $rel->kode }}</div>
                                </div>
                                <span class="badge {{ $rel->stok_tersedia > 0 ? 'badge-green' : 'badge-red' }}" style="font-size:0.62rem">
                                    {{ $rel->stok_tersedia }} unit
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

</x-admin-layout>