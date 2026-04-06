<x-admin-layout title="Detail Kategori" breadcrumb="Detail Kategori">

    <x-slot name="header">
        <div style="display:flex;align-items:center;gap:1.1rem">
            <div class="kat-hero-icon">{{ $kategori->ikon ?? '🏷' }}</div>
            <div>
                <div style="display:flex;align-items:center;gap:0.6rem;flex-wrap:wrap">
                    <h1 class="page-heading" style="font-size:1.5rem">{{ $kategori->nama }}</h1>
                    @if(!$kategori->is_aktif)
                        <span class="badge badge-slate">Nonaktif</span>
                    @else
                        <span class="badge badge-green">Aktif</span>
                    @endif
                </div>
                <p class="page-sub">
                    <code style="font-size:0.75rem;color:var(--mist)">{{ $kategori->slug }}</code>
                    · Dibuat {{ $kategori->created_at->format('d M Y') }}
                </p>
            </div>
        </div>
        <div style="display:flex;gap:0.5rem">
            <a href="{{ route('admin.kategoris.edit', $kategori) }}" class="btn btn-primary">✎ Edit</a>
            <a href="{{ route('admin.kategoris.index') }}" class="btn btn-ghost">← Kembali</a>
        </div>
    </x-slot>

    <style>
        .kat-hero-icon {
            width: 58px; height: 58px; border-radius: 14px; font-size: 1.7rem;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
            background: linear-gradient(135deg,rgba(37,99,235,0.2),rgba(37,99,235,0.05));
            border: 1px solid rgba(37,99,235,0.25);
        }

        .show-layout { display: grid; grid-template-columns: 1fr 300px; gap: 1.5rem; align-items: start; }

        /* Stats strip */
        .kat-stats {
            display: grid; grid-template-columns: repeat(3,1fr); gap: 0;
            background: var(--ink-80); border: 1px solid rgba(255,255,255,0.07);
            border-radius: 8px; overflow: hidden; margin-bottom: 1.2rem;
        }
        .ks-item { padding: 1.1rem; text-align: center; border-right: 1px solid rgba(255,255,255,0.06); }
        .ks-item:last-child { border-right: none; }
        .ks-val   { font-family: var(--font-display); font-size: 1.9rem; font-weight: 700; color: var(--cream); }
        .ks-label { font-size: 0.7rem; color: var(--mist); margin-top: 0.15rem; }

        /* Alat list */
        .alat-row {
            display: flex; align-items: center; gap: 1rem;
            padding: 0.9rem 1.3rem;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            transition: background 0.15s;
        }
        .alat-row:hover { background: rgba(255,255,255,0.03); }
        .alat-row:last-child { border-bottom: none; }

        .alat-img {
            width: 42px; height: 42px; border-radius: 8px; flex-shrink: 0;
            background: rgba(37,99,235,0.1); border: 1px solid rgba(37,99,235,0.15);
            display: flex; align-items: center; justify-content: center; font-size: 1rem;
            overflow: hidden;
        }
        .alat-img img { width: 100%; height: 100%; object-fit: cover; }

        .alat-info { flex: 1; min-width: 0; }
        .alat-nama { font-size: 0.86rem; font-weight: 700; color: var(--silver); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .alat-kode { font-size: 0.68rem; color: var(--slate); font-family: monospace; }

        .alat-stok { text-align: center; }
        .alat-stok-val   { font-family: var(--font-display); font-size: 1.1rem; font-weight: 700; color: var(--cream); }
        .alat-stok-label { font-size: 0.62rem; color: var(--mist); }

        /* Kondisi & Status badges */
        .alat-badges { display: flex; flex-direction: column; gap: 0.25rem; align-items: flex-end; }

        /* Info card sidebar */
        .info-sidebar {
            background: var(--ink-80); border: 1px solid rgba(255,255,255,0.07);
            border-radius: 8px; overflow: hidden;
        }
        .info-block { padding: 1rem 1.3rem; border-bottom: 1px solid rgba(255,255,255,0.06); }
        .info-block:last-child { border-bottom: none; }
        .info-block-label {
            font-size: 0.65rem; font-weight: 700; letter-spacing: 0.12em;
            text-transform: uppercase; color: var(--slate); margin-bottom: 0.4rem;
        }
        .info-block-val { font-size: 0.86rem; font-weight: 600; color: var(--silver); }
        .info-block-val.code { font-family: monospace; font-size: 0.8rem; color: var(--mist); }

        /* Empty alat */
        .empty-alat { text-align: center; padding: 2.5rem 1rem; color: var(--mist); font-size: 0.82rem; }

        @media(max-width:900px) { .show-layout { grid-template-columns: 1fr; } }
    </style>

    <div class="show-layout">

        {{-- LEFT: Alat list --}}
        <div>
            {{-- Stats --}}
            <div class="kat-stats">
                <div class="ks-item">
                    <div class="ks-val">{{ $kategori->alats_count }}</div>
                    <div class="ks-label">Total Alat</div>
                </div>
                <div class="ks-item">
                    <div class="ks-val" style="{{ $kategori->alat_aktif_count > 0 ? 'color:var(--jade)' : '' }}">
                        {{ $kategori->alat_aktif_count }}
                    </div>
                    <div class="ks-label">Alat Aktif</div>
                </div>
                <div class="ks-item">
                    <div class="ks-val">{{ $kategori->alat_tersedia_count }}</div>
                    <div class="ks-label">Tersedia Dipinjam</div>
                </div>
            </div>

            {{-- Alat in this category --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Daftar Alat dalam Kategori Ini</span>
                    <a href="{{ route('admin.alats.create', ['kategori' => $kategori->id]) }}"
                       class="btn btn-primary btn-sm">+ Tambah Alat</a>
                </div>

                @if($alats->isEmpty())
                    <div class="empty-alat">
                        <div style="font-size:2rem;margin-bottom:0.5rem">🛠</div>
                        Belum ada alat dalam kategori ini.<br>
                        <a href="{{ route('admin.alats.create', ['kategori' => $kategori->id]) }}"
                           style="color:var(--accent-l);margin-top:0.5rem;display:inline-block">
                            Tambahkan alat pertama →
                        </a>
                    </div>
                @else
                    @foreach($alats as $alat)
                        <div class="alat-row">
                            <div class="alat-img">
                                @if($alat->foto)
                                    <img src="{{ asset('storage/' . $alat->foto) }}" alt="{{ $alat->nama }}">
                                @else
                                    🔧
                                @endif
                            </div>
                            <div class="alat-info">
                                <div class="alat-nama">{{ $alat->nama }}</div>
                                <div class="alat-kode">{{ $alat->kode }} · {{ $alat->merk ?? '—' }}</div>
                            </div>
                            <div class="alat-stok">
                                <div class="alat-stok-val"
                                     style="{{ $alat->stok_tersedia == 0 ? 'color:var(--danger)' : 'color:var(--jade)' }}">
                                    {{ $alat->stok_tersedia }}
                                </div>
                                <div class="alat-stok-label">Tersedia</div>
                            </div>
                            <div class="alat-stok">
                                <div class="alat-stok-val">{{ $alat->stok_total }}</div>
                                <div class="alat-stok-label">Total</div>
                            </div>
                            <div class="alat-badges">
                                @php
                                    $kondisiBadge = ['baik'=>'badge-green','rusak_ringan'=>'badge-amber','rusak_berat'=>'badge-red','perbaikan'=>'badge-blue'];
                                    $kondisiLabel = ['baik'=>'Baik','rusak_ringan'=>'Rusak Ringan','rusak_berat'=>'Rusak Berat','perbaikan'=>'Perbaikan'];
                                @endphp
                                <span class="badge {{ $kondisiBadge[$alat->kondisi] ?? 'badge-slate' }}" style="font-size:0.62rem">
                                    {{ $kondisiLabel[$alat->kondisi] ?? $alat->kondisi }}
                                </span>
                                <span class="badge {{ $alat->status === 'aktif' ? 'badge-green' : 'badge-slate' }}" style="font-size:0.62rem">
                                    {{ ucfirst($alat->status) }}
                                </span>
                            </div>
                            <a href="{{ route('admin.alats.show', $alat) }}" class="btn btn-ghost btn-sm">Detail</a>
                        </div>
                    @endforeach

                    @if($alats->hasPages())
                        <div style="padding:0.75rem 1.3rem;border-top:1px solid rgba(255,255,255,0.06);display:flex;justify-content:flex-end">
                            {{ $alats->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>

        {{-- RIGHT: Info sidebar --}}
        <div>
            <div class="info-sidebar">
                <div class="info-block">
                    <div class="info-block-label">Nama Kategori</div>
                    <div class="info-block-val">{{ $kategori->nama }}</div>
                </div>
                <div class="info-block">
                    <div class="info-block-label">Slug</div>
                    <div class="info-block-val code">{{ $kategori->slug }}</div>
                </div>
                <div class="info-block">
                    <div class="info-block-label">Ikon</div>
                    <div class="info-block-val" style="font-size:1.4rem">{{ $kategori->ikon ?? '—' }}</div>
                </div>
                <div class="info-block">
                    <div class="info-block-label">Status</div>
                    <div class="info-block-val">
                        @if($kategori->is_aktif)
                            <span style="color:var(--jade)">● Aktif</span>
                        @else
                            <span style="color:var(--mist)">○ Nonaktif</span>
                        @endif
                    </div>
                </div>
                <div class="info-block">
                    <div class="info-block-label">Deskripsi</div>
                    <div class="info-block-val" style="font-weight:400;font-size:0.8rem;color:var(--mist);line-height:1.5">
                        {{ $kategori->deskripsi ?? '—' }}
                    </div>
                </div>
                <div class="info-block">
                    <div class="info-block-label">Dibuat</div>
                    <div class="info-block-val">{{ $kategori->created_at->format('d M Y, H:i') }}</div>
                </div>
                <div class="info-block">
                    <div class="info-block-label">Terakhir Diperbarui</div>
                    <div class="info-block-val">{{ $kategori->updated_at->diffForHumans() }}</div>
                </div>
            </div>

            {{-- Actions --}}
            <div style="background:var(--ink-80);border:1px solid rgba(255,255,255,0.07);border-radius:8px;padding:1.1rem;margin-top:1rem">
                <a href="{{ route('admin.kategoris.edit', $kategori) }}"
                   class="btn btn-primary" style="width:100%;justify-content:center;margin-bottom:0.5rem">
                    ✎ Edit Kategori
                </a>

                {{-- Toggle status --}}
                <form method="POST" action="{{ route('admin.kategoris.update', $kategori) }}" style="margin-bottom:0.5rem">
                    @csrf @method('PUT')
                    <input type="hidden" name="nama"      value="{{ $kategori->nama }}">
                    <input type="hidden" name="slug"      value="{{ $kategori->slug }}">
                    <input type="hidden" name="ikon"      value="{{ $kategori->ikon }}">
                    <input type="hidden" name="deskripsi" value="{{ $kategori->deskripsi }}">
                    <input type="hidden" name="is_aktif"  value="{{ $kategori->is_aktif ? '0' : '1' }}">
                    <button type="submit" class="btn btn-ghost" style="width:100%;justify-content:center">
                        {{ $kategori->is_aktif ? '⏸ Nonaktifkan' : '▶ Aktifkan' }} Kategori
                    </button>
                </form>

                @if($kategori->alats_count === 0)
                    <form method="POST" action="{{ route('admin.kategoris.destroy', $kategori) }}"
                          onsubmit="return confirm('Hapus kategori {{ $kategori->nama }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center">
                            🗑 Hapus Kategori
                        </button>
                    </form>
                @else
                    <div style="font-size:0.72rem;color:var(--mist);text-align:center;padding:0.5rem;background:rgba(239,68,68,0.06);border-radius:5px;border:1px solid rgba(239,68,68,0.15)">
                        Tidak bisa dihapus — masih ada {{ $kategori->alats_count }} alat
                    </div>
                @endif
            </div>
        </div>

    </div>

</x-admin-layout>