<x-petugas-layout title="Detail Pengembalian">

    <x-slot name="header">
        <div>
            <div style="display:flex;align-items:center;gap:0.7rem;flex-wrap:wrap">
                <h1 class="page-heading" style="font-size:1.25rem">Detail Pengembalian</h1>
                @if($pengembalian->is_tepat_waktu)
                    <span class="spill sp-ok">✓ Tepat Waktu</span>
                @else
                    <span class="spill sp-late">⏰ Terlambat {{ $pengembalian->keterlambatan_hari }} hari</span>
                @endif
                @if($pengembalian->is_rusak)
                    <span class="spill sp-warn">⚠ {{ ucfirst(str_replace('_',' ',$pengembalian->kondisi_kembali)) }}</span>
                @endif
            </div>
            <p class="page-sub">
                Dicatat {{ $pengembalian->created_at->format('d M Y, H:i') }}
                oleh {{ $pengembalian->petugas?->name ?? 'sistem' }}
            </p>
        </div>
        <a href="{{ route('petugas.pengembalians.index', ['tab'=>'selesai']) }}" class="btn-back">← Kembali</a>
    </x-slot>

    <style>
        :root {
            --green:      #1A7A4A;
            --green-m:    #22A05A;
            --green-l:    #2DBE6C;
            --pale:       #E8F8EE;
            --pale-b:     rgba(34,160,90,0.2);
            --danger:     #DC2626;
            --danger-pale:#FEF2F2;
            --danger-b:   #FECACA;
            --gold:       #D97706;
            --gold-pale:  #FFFBEB;
            --gold-b:     #FDE68A;
            --jade:       #16A34A;
            --surface:    #FFFFFF;
            --surface2:   #F7FAF8;
            --border:     #E5E7EB;
            --border2:    #F3F4F6;
            --text:       #111827;
            --sub:        #374151;
            --muted:      #6B7280;
        }

        .btn-back {
            display:inline-flex;align-items:center;padding:0.5rem 1rem;border-radius:8px;
            font-size:0.78rem;font-weight:700;text-decoration:none;
            background:var(--surface);border:1px solid var(--border);color:var(--muted);
            transition:all 0.15s;white-space:nowrap;
            box-shadow:0 1px 3px rgba(0,0,0,0.06);
        }
        .btn-back:hover { border-color:var(--green-m);color:var(--green);background:var(--pale); }

        /* Status pills */
        .spill { display:inline-flex;align-items:center;gap:0.25rem;padding:0.22rem 0.65rem;border-radius:100px;font-size:0.68rem;font-weight:700; }
        .spill::before { content:'';width:5px;height:5px;border-radius:50%; }
        .sp-ok   { background:var(--pale);color:var(--green);border:1px solid var(--pale-b); }
        .sp-ok::before { background:var(--green-l); }
        .sp-late { background:var(--danger-pale);color:#B91C1C;border:1px solid var(--danger-b); }
        .sp-late::before { background:var(--danger); }
        .sp-warn { background:var(--gold-pale);color:var(--gold);border:1px solid var(--gold-b); }
        .sp-warn::before { background:var(--gold); }

        .layout { display:grid;grid-template-columns:1fr 300px;gap:1.4rem;align-items:start; }

        /* Section card */
        .sc {
            background:var(--surface);border:1px solid var(--border);
            border-radius:12px;overflow:hidden;margin-bottom:1.1rem;
            box-shadow:0 1px 4px rgba(0,0,0,0.04);
        }
        .sc-head {
            padding:0.85rem 1.15rem;border-bottom:1px solid var(--border2);
            display:flex;align-items:center;gap:0.55rem;background:var(--surface2);
        }
        .sc-ico  { width:26px;height:26px;border-radius:7px;background:var(--pale);color:var(--green-m);display:flex;align-items:center;justify-content:center;font-size:0.8rem;flex-shrink:0; }
        .sc-ttl  { font-size:0.83rem;font-weight:700;color:var(--text); }
        .sc-body { padding:1rem 1.15rem; }

        .ir { display:flex;align-items:flex-start;padding:0.58rem 0;border-bottom:1px solid var(--border2); }
        .ir:last-child { border-bottom:none; }
        .ir-key { font-size:0.72rem;font-weight:600;color:var(--muted);width:42%;flex-shrink:0; }
        .ir-val { font-size:0.8rem;color:var(--sub);flex:1; }

        /* Receipt card */
        .receipt {
            background:var(--surface);border:1px solid var(--border);
            border-radius:12px;overflow:hidden;margin-bottom:1.1rem;
            box-shadow:0 2px 8px rgba(0,0,0,0.05);
        }
        .receipt-hero {
            background:linear-gradient(135deg, var(--pale), #F0FDF9);
            border-bottom:1px solid rgba(34,160,90,0.15);
            padding:1.2rem 1.3rem;
            display:flex;align-items:flex-start;gap:0.9rem;
        }
        .alat-thumb {
            width:56px;height:56px;border-radius:10px;overflow:hidden;flex-shrink:0;
            background:rgba(34,160,90,0.1);display:flex;align-items:center;justify-content:center;font-size:1.2rem;
        }
        .alat-thumb img { width:100%;height:100%;object-fit:cover; }

        .receipt-body { padding:0 1.3rem; }
        .rr { display:flex;justify-content:space-between;align-items:center;padding:0.72rem 0;border-bottom:1px solid var(--border2); }
        .rr:last-child { border-bottom:none; }
        .rr-key { font-size:0.75rem;color:var(--muted); }
        .rr-val { font-size:0.82rem;font-weight:700;color:var(--text); }

        .receipt-total {
            padding:1rem 1.3rem;
            display:flex;justify-content:space-between;align-items:center;
            border-top:2px dashed var(--border);
        }
        .rt-label { font-size:0.88rem;font-weight:700;color:var(--text); }
        .rt-val   { font-size:1.5rem;font-weight:800; }

        /* Kondisi badge */
        .kond { display:inline-flex;align-items:center;gap:0.35rem;padding:0.38rem 0.85rem;border-radius:8px;font-size:0.8rem;font-weight:700; }
        .kond-baik         { background:#DCFCE7;color:#15803D;border:1px solid #BBF7D0; }
        .kond-rusak_ringan { background:var(--gold-pale);color:var(--gold);border:1px solid var(--gold-b); }
        .kond-rusak_sedang { background:#FFF7ED;color:#C2410C;border:1px solid #FDBA74; }
        .kond-rusak_berat  { background:var(--danger-pale);color:var(--danger);border:1px solid var(--danger-b); }
        .kond-hilang       { background:#F5F3FF;color:#7C3AED;border:1px solid #DDD6FE; }

        /* Proof photo */
        .proof-photo { border-radius:9px;overflow:hidden;border:1px solid var(--border); }
        .proof-photo img { width:100%;max-height:260px;object-fit:cover;display:block; }
        .no-photo {
            padding:2rem;text-align:center;color:var(--muted);font-size:0.8rem;
            background:var(--surface2);border-radius:9px;border:2px dashed var(--border);
        }

        /* Sidebar summary */
        .sum-card {
            background:var(--surface);border:1px solid var(--border);
            border-radius:12px;overflow:hidden;margin-bottom:1rem;
            box-shadow:0 1px 4px rgba(0,0,0,0.04);
        }
        .sum-head { padding:0.85rem 1.1rem;border-bottom:1px solid var(--border2);font-size:0.82rem;font-weight:700;color:var(--text);background:var(--surface2); }
        .sum-row { display:flex;justify-content:space-between;align-items:center;padding:0.7rem 1.1rem;border-bottom:1px solid var(--border2); }
        .sum-row:last-child { border-bottom:none; }
        .sum-key { font-size:0.73rem;color:var(--muted); }
        .sum-val { font-size:0.8rem;font-weight:700;color:var(--text); }

        /* Link buttons in sidebar */
        .link-btn {
            display:flex;align-items:center;justify-content:center;gap:0.4rem;
            padding:0.62rem;border-radius:8px;font-size:0.8rem;font-weight:700;
            text-decoration:none;background:var(--surface2);color:var(--muted);
            border:1px solid var(--border);transition:all 0.15s;
        }
        .link-btn:hover { border-color:var(--green-m);color:var(--green);background:var(--pale); }

        @media(max-width:900px) { .layout { grid-template-columns:1fr; } }
    </style>

    <div class="layout">

        {{-- ─── LEFT ──────────────────────────────────── --}}
        <div>

            {{-- Receipt-style main card --}}
            <div class="receipt">

                {{-- Alat hero --}}
                <div class="receipt-hero">
                    <div class="alat-thumb">
                        @if($pengembalian->peminjaman->alat?->foto)
                            <img src="{{ asset('storage/'.$pengembalian->peminjaman->alat->foto) }}" alt="">
                        @else 🔧 @endif
                    </div>
                    <div style="flex:1">
                        <div style="font-size:0.95rem;font-weight:700;color:var(--text)">{{ $pengembalian->peminjaman->alat->nama ?? '—' }}</div>
                        <div style="font-size:0.7rem;color:var(--muted);margin-top:0.1rem">{{ $pengembalian->peminjaman->alat->kode ?? '' }} · {{ $pengembalian->peminjaman->jumlah }} unit</div>
                        <div style="margin-top:0.5rem">
                            @php
                                $kl = ['baik'=>['✅','Baik'],'rusak_ringan'=>['⚠️','Rusak Ringan'],'rusak_sedang'=>['🔶','Rusak Sedang'],'rusak_berat'=>['❌','Rusak Berat'],'hilang'=>['💀','Hilang']];
                                [$icon,$label] = $kl[$pengembalian->kondisi_kembali] ?? ['',''];
                            @endphp
                            <span class="kond kond-{{ $pengembalian->kondisi_kembali }}">{{ $icon }} {{ $label }}</span>
                        </div>
                    </div>
                    <div style="text-align:right;flex-shrink:0">
                        <div style="font-family:monospace;font-size:0.72rem;color:var(--green-m);font-weight:700">{{ $pengembalian->peminjaman->nomor_pinjam }}</div>
                        <div style="font-size:0.65rem;color:var(--muted);margin-top:0.15rem">{{ $pengembalian->created_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>

                <div class="receipt-body">
                    <div class="rr">
                        <span class="rr-key">Peminjam</span>
                        <span class="rr-val">{{ $pengembalian->peminjaman->peminjam->name ?? '—' }}</span>
                    </div>
                    <div class="rr">
                        <span class="rr-key">Rencana Kembali</span>
                        <span class="rr-val">{{ $pengembalian->peminjaman->tanggal_kembali_rencana->format('d M Y') }}</span>
                    </div>
                    <div class="rr">
                        <span class="rr-key">Aktual Kembali</span>
                        <span class="rr-val" style="{{ !$pengembalian->is_tepat_waktu ? 'color:var(--danger)' : 'color:var(--jade)' }}">
                            {{ $pengembalian->tanggal_kembali_aktual->format('d M Y') }}
                        </span>
                    </div>
                    <div class="rr">
                        <span class="rr-key">Keterlambatan</span>
                        @if($pengembalian->is_tepat_waktu)
                            <span class="rr-val" style="color:var(--jade)">✓ Tepat Waktu</span>
                        @else
                            <span class="rr-val" style="color:var(--danger)">{{ $pengembalian->keterlambatan_hari }} hari</span>
                        @endif
                    </div>
                    <div class="rr">
                        <span class="rr-key">Denda Keterlambatan</span>
                        <span class="rr-val" style="{{ $pengembalian->denda > 0 ? 'color:var(--danger)' : 'color:var(--jade)' }}">
                            Rp {{ number_format($pengembalian->denda,0,',','.') }}
                        </span>
                    </div>
                    <div class="rr">
                        <span class="rr-key">Biaya Kerusakan</span>
                        <span class="rr-val" style="{{ $pengembalian->biaya_kerusakan > 0 ? 'color:var(--danger)' : '' }}">
                            Rp {{ number_format($pengembalian->biaya_kerusakan,0,',','.') }}
                        </span>
                    </div>
                    @if($pengembalian->catatan)
                        <div class="rr">
                            <span class="rr-key">Catatan</span>
                            <span class="rr-val" style="font-size:0.75rem;font-style:italic;color:var(--muted);text-align:right;max-width:60%">"{{ $pengembalian->catatan }}"</span>
                        </div>
                    @endif
                    <div class="rr">
                        <span class="rr-key">Dicatat oleh</span>
                        <span class="rr-val">{{ $pengembalian->petugas?->name ?? 'Sistem' }}</span>
                    </div>
                </div>

                <div class="receipt-total"
                     style="{{ $pengembalian->total_tagihan > 0 ? 'background:var(--danger-pale)' : 'background:var(--pale)' }}">
                    <span class="rt-label">Total Tagihan</span>
                    <span class="rt-val" style="color:{{ $pengembalian->total_tagihan > 0 ? 'var(--danger)' : 'var(--jade)' }}">
                        Rp {{ number_format($pengembalian->total_tagihan,0,',','.') }}
                    </span>
                </div>
            </div>

            {{-- Foto bukti --}}
            <div class="sc">
                <div class="sc-head"><div class="sc-ico">📷</div><span class="sc-ttl">Foto Bukti</span></div>
                <div class="sc-body">
                    @if($pengembalian->foto_bukti)
                        <div class="proof-photo" style="margin-bottom:0.6rem">
                            <img src="{{ asset('storage/'.$pengembalian->foto_bukti) }}" alt="Foto bukti">
                        </div>
                        <a href="{{ asset('storage/'.$pengembalian->foto_bukti) }}" target="_blank"
                           style="font-size:0.75rem;color:var(--green-m);text-decoration:none;font-weight:600">🔍 Lihat fullsize →</a>
                    @else
                        <div class="no-photo">📷 Tidak ada foto bukti</div>
                    @endif
                </div>
            </div>

        </div>

        {{-- ─── RIGHT: Summary ──────────────────────────── --}}
        <div>
            <div class="sum-card">
                <div class="sum-head">Ringkasan</div>
                <div class="sum-row">
                    <span class="sum-key">No. Transaksi</span>
                    <span class="sum-val" style="font-family:monospace;font-size:0.72rem;color:var(--green-m)">{{ $pengembalian->peminjaman->nomor_pinjam }}</span>
                </div>
                <div class="sum-row">
                    <span class="sum-key">Peminjam</span>
                    <span class="sum-val">{{ $pengembalian->peminjaman->peminjam->name ?? '—' }}</span>
                </div>
                <div class="sum-row">
                    <span class="sum-key">Durasi Pinjam</span>
                    <span class="sum-val">{{ $pengembalian->peminjaman->durasi_hari }} hari</span>
                </div>
                <div class="sum-row">
                    <span class="sum-key">Biaya Sewa</span>
                    <span class="sum-val">Rp {{ number_format($pengembalian->peminjaman->total_biaya,0,',','.') }}</span>
                </div>
                <div class="sum-row"
                     style="{{ $pengembalian->total_tagihan > 0 ? 'background:var(--danger-pale)' : 'background:var(--pale)' }}">
                    <span class="sum-key" style="font-weight:700;color:var(--text)">Total Tagihan Ekstra</span>
                    <span class="sum-val" style="font-size:1rem;color:{{ $pengembalian->total_tagihan > 0 ? 'var(--danger)' : 'var(--jade)' }}">
                        Rp {{ number_format($pengembalian->total_tagihan,0,',','.') }}
                    </span>
                </div>
            </div>

            {{-- Link buttons --}}
            <div class="sum-card" style="padding:1rem">
                <a href="{{ route('petugas.peminjamans.show', $pengembalian->peminjaman) }}"
                   class="link-btn" style="margin-bottom:0.6rem">
                    Lihat Data Peminjaman →
                </a>
                <a href="{{ route('petugas.pengembalians.index', ['tab'=>'selesai']) }}"
                   class="link-btn">
                    ← Semua Pengembalian
                </a>
            </div>
        </div>

    </div>

</x-petugas-layout>