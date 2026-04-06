<x-petugas-layout title="Detail Peminjaman Aktif">

    <x-slot name="header">
        <div>
            <div style="display:flex;align-items:center;gap:0.7rem;flex-wrap:wrap">
                <h1 class="page-heading" style="font-size:1.25rem">{{ $peminjaman->nomor_pinjam }}</h1>
                @if($peminjaman->is_terlambat)
                    <span class="spill sp-late">⚠ Terlambat {{ $peminjaman->keterlambatan_hari }} hari</span>
                @else
                    <span class="spill sp-ok">✓ Aktif Dipinjam</span>
                @endif
            </div>
            <p class="page-sub">Pinjam {{ $peminjaman->tanggal_pinjam->format('d M Y') }} · Batas {{ $peminjaman->tanggal_kembali_rencana->format('d M Y') }}</p>
        </div>
        <a href="{{ route('petugas.pengembalians.index') }}" class="btn-back">← Kembali</a>
    </x-slot>

    <style>
        :root {
            --green:      #1A7A4A;
            --green-m:    #22A05A;
            --green-l:    #2DBE6C;
            --pale:       #E8F8EE;
            --pale-b:     rgba(34,160,90,0.2);
            --ring:       rgba(34,160,90,0.18);
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

        .spill { display:inline-flex;align-items:center;gap:0.25rem;padding:0.22rem 0.65rem;border-radius:100px;font-size:0.68rem;font-weight:700; }
        .spill::before { content:'';width:5px;height:5px;border-radius:50%; }
        .sp-ok   { background:var(--pale);color:var(--green);border:1px solid var(--pale-b); }
        .sp-ok::before { background:var(--green-l); }
        .sp-late { background:var(--danger-pale);color:#B91C1C;border:1px solid var(--danger-b); }
        .sp-late::before { background:var(--danger);animation:blink 0.9s infinite; }
        @keyframes blink { 0%,100%{opacity:1}50%{opacity:0.3} }

        .layout { display:grid;grid-template-columns:1fr 360px;gap:1.4rem;align-items:start; }

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

        /* Info rows */
        .ir { display:flex;align-items:flex-start;padding:0.58rem 0;border-bottom:1px solid var(--border2); }
        .ir:last-child { border-bottom:none; }
        .ir-key { font-size:0.72rem;font-weight:600;color:var(--muted);width:40%;flex-shrink:0; }
        .ir-val { font-size:0.8rem;color:var(--sub);flex:1; }

        /* Alat hero */
        .alat-hero { display:flex;align-items:flex-start;gap:0.9rem;padding:1rem 1.15rem; }
        .alat-img {
            width:64px;height:64px;border-radius:10px;overflow:hidden;flex-shrink:0;
            background:var(--pale);display:flex;align-items:center;justify-content:center;font-size:1.4rem;
        }
        .alat-img img { width:100%;height:100%;object-fit:cover; }

        /* Timeline */
        .tl { display:flex;align-items:center;gap:0;padding:1rem 1.15rem; }
        .tl-node { text-align:center;min-width:80px; }
        .tl-dot  { width:10px;height:10px;border-radius:50%;margin:0 auto 0.35rem; }
        .tl-done { background:var(--green-m); }
        .tl-warn { background:var(--danger);animation:blink 1s infinite; }
        .tl-pend { background:var(--green-l);animation:blink 1.4s infinite; }
        .tl-d    { font-size:0.75rem;font-weight:700;color:var(--text); }
        .tl-l    { font-size:0.6rem;color:var(--muted);margin-top:0.1rem; }
        .tl-line { flex:1;height:2px;border-radius:1px;margin:0 0.35rem; }
        .tl-line-done { background:var(--green-m); }
        .tl-line-pend { background:linear-gradient(to right,var(--green-m),var(--border)); }

        /* Late banner */
        .late-banner {
            margin-bottom:1rem;padding:0.9rem 1.1rem;border-radius:10px;
            background:var(--danger-pale);border:1px solid var(--danger-b);
            font-size:0.8rem;color:#B91C1C;line-height:1.6;
        }
        .late-banner strong { color:var(--danger); }

        /* Catatan block */
        .catatan-blk {
            font-style:italic;color:var(--muted);font-size:0.78rem;line-height:1.5;
            padding:0.6rem 0.8rem;background:var(--pale);
            border-left:3px solid var(--green-m);border-radius:0 7px 7px 0;
        }

        /* ── Return form (sticky sidebar) ───────────────── */
        .return-card {
            background:var(--surface);border:1px solid var(--border);
            border-radius:12px;overflow:hidden;
            position:sticky;top:1rem;
            box-shadow:0 2px 8px rgba(0,0,0,0.06);
        }
        .rc-head {
            padding:1rem 1.2rem;border-bottom:1px solid var(--border2);
            font-size:0.88rem;font-weight:700;color:var(--text);
            display:flex;align-items:center;gap:0.5rem;
            background:var(--surface2);
        }
        .rc-body { padding:1.2rem; }

        /* Form controls */
        .form-label { font-size:0.73rem;font-weight:700;color:var(--sub);margin-bottom:0.4rem;display:block; }
        .form-input {
            width:100%;box-sizing:border-box;
            background:var(--surface2);border:1px solid var(--border);border-radius:8px;
            padding:0.65rem 0.85rem;color:var(--text);
            font-family:var(--font-ui,sans-serif);font-size:0.82rem;
            outline:none;transition:border-color 0.2s,box-shadow 0.2s;
        }
        .form-input:focus { border-color:var(--green-m);box-shadow:0 0 0 3px var(--ring); }
        .form-group { margin-bottom:0.9rem; }

        /* Kondisi grid */
        .kd-grid { display:grid;grid-template-columns:repeat(3,1fr);gap:0.35rem;margin-bottom:0.9rem; }
        .kd-opt { position:relative; }
        .kd-opt input { position:absolute;opacity:0;width:0;height:0; }
        .kd-lbl {
            display:flex;flex-direction:column;align-items:center;gap:0.2rem;padding:0.55rem 0.2rem;
            border-radius:8px;cursor:pointer;text-align:center;
            background:var(--surface2);border:1.5px solid var(--border);transition:all 0.15s;
        }
        .kd-lbl:hover { border-color:#D1D5DB;background:white; }
        .kd-opt input:checked + .kd-lbl.kd-baik        { border-color:var(--green-m);background:var(--pale); }
        .kd-opt input:checked + .kd-lbl.kd-rusak_ringan { border-color:var(--gold);background:var(--gold-pale); }
        .kd-opt input:checked + .kd-lbl.kd-rusak_sedang { border-color:#F97316;background:#FFF7ED; }
        .kd-opt input:checked + .kd-lbl.kd-rusak_berat  { border-color:var(--danger);background:var(--danger-pale); }
        .kd-opt input:checked + .kd-lbl.kd-hilang       { border-color:#7C3AED;background:#F5F3FF; }
        .kd-em  { font-size:1.1rem; }
        .kd-tx  { font-size:0.58rem;font-weight:700;color:var(--muted); }

        /* Biaya field */
        .price-wrap { position:relative; }
        .price-pfx { position:absolute;left:0.85rem;top:50%;transform:translateY(-50%);color:var(--muted);font-size:0.75rem;pointer-events:none; }
        .price-wrap .form-input { padding-left:2.75rem; }

        /* Denda preview */
        .denda-preview {
            background:var(--surface2);border:1px solid var(--border);
            border-radius:9px;padding:0.75rem 0.9rem;margin-bottom:0.9rem;
        }
        .dp-row { display:flex;justify-content:space-between;font-size:0.75rem;padding:0.2rem 0; }
        .dp-key { color:var(--muted); }
        .dp-val { font-weight:700;color:var(--sub); }
        .dp-total { border-top:1px solid var(--border);margin-top:0.35rem;padding-top:0.4rem; }
        .dp-total .dp-key { font-weight:700;color:var(--text); }
        .dp-total .dp-val { color:var(--green);font-size:0.9rem; }

        /* Photo zone */
        .photo-zone {
            border:2px dashed var(--border);border-radius:9px;padding:1rem;text-align:center;
            cursor:pointer;position:relative;background:var(--surface2);transition:all 0.2s;
            margin-bottom:0.9rem;
        }
        .photo-zone:hover { border-color:var(--green-m);background:var(--pale); }
        .photo-zone input { position:absolute;inset:0;opacity:0;cursor:pointer; }
        #previewWrap { display:none; }
        #previewWrap img { width:100%;height:120px;object-fit:cover;border-radius:7px; }

        /* Error flash */
        .error-flash {
            background:var(--danger-pale);border:1px solid var(--danger-b);
            border-radius:8px;padding:0.7rem;margin-bottom:0.9rem;
            font-size:0.75rem;color:#B91C1C;
        }

        /* Submit button */
        .btn-submit {
            width:100%;padding:0.78rem;border-radius:9px;font-size:0.88rem;font-weight:700;
            background:var(--green);color:white;border:none;cursor:pointer;
            font-family:var(--font-ui,sans-serif);display:flex;align-items:center;justify-content:center;gap:0.45rem;
            transition:all 0.2s;
        }
        .btn-submit:hover { background:#155F3A;transform:translateY(-1px);box-shadow:0 6px 18px rgba(26,122,74,0.28); }

        @media(max-width:960px) { .layout { grid-template-columns:1fr; } .return-card { position:static; } }
    </style>

    <div class="layout">

        {{-- ─── LEFT ──────────────────────────────────── --}}
        <div>

            {{-- Late banner --}}
            @if($peminjaman->is_terlambat)
                <div class="late-banner">
                    <strong>⚠ Terlambat {{ $peminjaman->keterlambatan_hari }} hari!</strong><br>
                    Batas pengembalian: <strong>{{ $peminjaman->tanggal_kembali_rencana->format('d M Y') }}</strong><br>
                    Estimasi denda: <strong>Rp {{ number_format($peminjaman->keterlambatan_hari * ($peminjaman->alat->denda_per_hari??0) * $peminjaman->jumlah, 0, ',', '.') }}</strong>
                    (Rp {{ number_format($peminjaman->alat->denda_per_hari??0, 0, ',', '.') }} × {{ $peminjaman->jumlah }} unit × {{ $peminjaman->keterlambatan_hari }} hari)
                </div>
            @endif

            {{-- Peminjam --}}
            <div class="sc">
                <div class="sc-head"><div class="sc-ico">👤</div><span class="sc-ttl">Peminjam</span></div>
                <div style="display:flex;align-items:center;gap:0.9rem;padding:1rem 1.15rem">
                    <div style="width:44px;height:44px;border-radius:11px;flex-shrink:0;background:linear-gradient(135deg,var(--green),var(--green-l));display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:800;color:white">
                        {{ strtoupper(substr($peminjaman->peminjam->name??'?',0,2)) }}
                    </div>
                    <div>
                        <div style="font-size:0.95rem;font-weight:700;color:var(--text)">{{ $peminjaman->peminjam->name }}</div>
                        <div style="font-size:0.72rem;color:var(--muted)">{{ $peminjaman->peminjam->email }}</div>
                    </div>
                </div>
                <div class="sc-body" style="padding-top:0">
                    <div class="ir"><span class="ir-key">No. Transaksi</span><span class="ir-val" style="font-family:monospace;font-size:0.75rem;color:var(--green-m);font-weight:700">{{ $peminjaman->nomor_pinjam }}</span></div>
                    <div class="ir"><span class="ir-key">Tanggal Pinjam</span><span class="ir-val">{{ $peminjaman->tanggal_pinjam->format('d M Y') }}</span></div>
                    <div class="ir"><span class="ir-key">Disetujui oleh</span><span class="ir-val">{{ $peminjaman->petugas?->name ?? '—' }}</span></div>
                    @if($peminjaman->tujuan_peminjaman)
                        <div class="ir"><span class="ir-key">Tujuan</span><span class="ir-val" style="font-size:0.78rem;font-style:italic;color:var(--muted)">"{{ $peminjaman->tujuan_peminjaman }}"</span></div>
                    @endif
                </div>
            </div>

            {{-- Alat --}}
            <div class="sc">
                <div class="sc-head"><div class="sc-ico">🔧</div><span class="sc-ttl">Alat yang Dipinjam</span></div>
                <div class="alat-hero">
                    <div class="alat-img">
                        @if($peminjaman->alat?->foto)
                            <img src="{{ asset('storage/'.$peminjaman->alat->foto) }}" alt="">
                        @else 🔧 @endif
                    </div>
                    <div style="flex:1">
                        <div style="font-size:0.95rem;font-weight:700;color:var(--text);margin-bottom:0.15rem">{{ $peminjaman->alat->nama ?? '—' }}</div>
                        <div style="font-family:monospace;font-size:0.7rem;color:var(--green-m)">{{ $peminjaman->alat->kode ?? '' }}</div>
                        <div style="font-size:0.72rem;color:var(--muted);margin-top:0.15rem">{{ $peminjaman->alat->kategori->nama ?? '—' }}</div>
                    </div>
                </div>
                <div class="sc-body" style="padding-top:0">
                    <div class="ir"><span class="ir-key">Jumlah</span><span class="ir-val"><strong style="color:var(--green);font-size:1rem">{{ $peminjaman->jumlah }}</strong> unit</span></div>
                    <div class="ir"><span class="ir-key">Harga sewa/hari</span><span class="ir-val">Rp {{ number_format($peminjaman->alat->harga_sewa_per_hari??0,0,',','.') }}</span></div>
                    <div class="ir"><span class="ir-key">Denda/hari</span><span class="ir-val" style="color:var(--danger);font-weight:600">Rp {{ number_format($peminjaman->alat->denda_per_hari??0,0,',','.') }}</span></div>
                    <div class="ir"><span class="ir-key">Total biaya sewa</span><span class="ir-val" style="color:var(--green);font-weight:700">Rp {{ number_format($peminjaman->total_biaya,0,',','.') }}</span></div>
                </div>
            </div>

            {{-- Timeline --}}
            <div class="sc">
                <div class="sc-head"><div class="sc-ico">📅</div><span class="sc-ttl">Timeline Peminjaman</span></div>
                <div class="tl">
                    <div class="tl-node">
                        <div class="tl-dot tl-done"></div>
                        <div class="tl-d">{{ $peminjaman->tanggal_pinjam->format('d M') }}</div>
                        <div class="tl-l">Mulai Pinjam</div>
                    </div>
                    <div class="tl-line {{ $peminjaman->is_terlambat ? '' : 'tl-line-done' }}"
                         style="{{ $peminjaman->is_terlambat ? 'background:linear-gradient(to right,var(--green-m),var(--danger))' : '' }}"></div>
                    <div class="tl-node">
                        <div class="tl-dot {{ $peminjaman->is_terlambat ? 'tl-warn' : 'tl-pend' }}"></div>
                        <div class="tl-d" style="{{ $peminjaman->is_terlambat ? 'color:var(--danger)' : '' }}">{{ $peminjaman->tanggal_kembali_rencana->format('d M') }}</div>
                        <div class="tl-l">Batas Kembali</div>
                    </div>
                    <div class="tl-line" style="background:var(--border)"></div>
                    <div class="tl-node" style="opacity:0.35">
                        <div class="tl-dot" style="background:#CBD5E1"></div>
                        <div class="tl-d">—</div>
                        <div class="tl-l">Aktual Kembali</div>
                    </div>
                </div>
                <div style="padding:0 1.15rem 1rem;font-size:0.75rem;color:var(--muted)">
                    Durasi pinjam: <strong style="color:var(--text)">{{ $peminjaman->durasi_hari }} hari</strong>
                    @if(!$peminjaman->is_terlambat)
                        · Sisa: <strong style="color:var(--green-m)">{{ now()->diffInDays($peminjaman->tanggal_kembali_rencana, false) }} hari</strong>
                    @endif
                </div>
            </div>

            {{-- Catatan petugas --}}
            @if($peminjaman->catatan_petugas)
                <div class="sc">
                    <div class="sc-head"><div class="sc-ico">💬</div><span class="sc-ttl">Catatan Persetujuan</span></div>
                    <div class="sc-body">
                        <div class="catatan-blk">"{{ $peminjaman->catatan_petugas }}"</div>
                        <div style="font-size:0.7rem;color:var(--muted);margin-top:0.4rem">— {{ $peminjaman->petugas?->name ?? '—' }}, {{ $peminjaman->disetujui_at?->format('d M Y') }}</div>
                    </div>
                </div>
            @endif

        </div>

        {{-- ─── RIGHT: Return form ─────────────────────── --}}
        <div id="form-kembali">
            <div class="return-card">
                <div class="rc-head">↩ Catat Pengembalian</div>
                <div class="rc-body">

                    @if(session('error'))
                        <div class="error-flash">{{ session('error') }}</div>
                    @endif

                    <form method="POST"
                          action="{{ route('petugas.pengembalians.store') }}"
                          enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="peminjaman_id" value="{{ $peminjaman->id }}">

                        {{-- Tanggal kembali --}}
                        <div class="form-group">
                            <label class="form-label">Tanggal Kembali Aktual <span style="color:var(--danger)">*</span></label>
                            <input type="date" name="tanggal_kembali_aktual" id="tglAktual"
                                   value="{{ old('tanggal_kembali_aktual', now()->toDateString()) }}"
                                   class="form-input" oninput="recalc()">
                            @error('tanggal_kembali_aktual')
                                <p style="font-size:0.7rem;color:var(--danger);margin-top:0.25rem">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Denda live preview --}}
                        <div class="denda-preview">
                            <div class="dp-row">
                                <span class="dp-key">Keterlambatan</span>
                                <span class="dp-val" id="dpTerlambat" style="color:var(--jade)">0 hari</span>
                            </div>
                            <div class="dp-row">
                                <span class="dp-key">Denda terlambat</span>
                                <span class="dp-val" id="dpDenda">Rp 0</span>
                            </div>
                            <div class="dp-row">
                                <span class="dp-key">Biaya kerusakan</span>
                                <span class="dp-val" id="dpKerusakan">Rp 0</span>
                            </div>
                            <div class="dp-row dp-total">
                                <span class="dp-key">Total Tagihan</span>
                                <span class="dp-val" id="dpTotal">Rp 0</span>
                            </div>
                        </div>

                        {{-- Kondisi --}}
                        <div style="margin-bottom:0.9rem">
                            <label class="form-label">Kondisi Alat <span style="color:var(--danger)">*</span></label>
                            <div class="kd-grid">
                                @foreach(['baik'=>['✅','Baik'],'rusak_ringan'=>['⚠️','Rusak Ringan'],'rusak_sedang'=>['🔶','Rusak Sedang'],'rusak_berat'=>['❌','Rusak Berat'],'hilang'=>['💀','Hilang']] as $val=>[$em,$lbl])
                                    @if($val === 'hilang')
                                        </div><div class="kd-grid" style="grid-template-columns:1fr;">
                                    @endif
                                    <div class="kd-opt">
                                        <input type="radio" name="kondisi_kembali" id="k_{{ $val }}"
                                               value="{{ $val }}"
                                               {{ old('kondisi_kembali','baik')===$val ? 'checked':'' }}
                                               onchange="onKondisi('{{ $val }}')">
                                        <label for="k_{{ $val }}" class="kd-lbl kd-{{ $val }}">
                                            <span class="kd-em">{{ $em }}</span>
                                            <span class="kd-tx">{{ $lbl }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('kondisi_kembali') <p style="font-size:0.7rem;color:var(--danger)">{{ $message }}</p> @enderror
                        </div>

                        {{-- Biaya kerusakan --}}
                        <div id="biayaWrap" style="display:none;margin-bottom:0.9rem">
                            <label class="form-label">Biaya Kerusakan / Kehilangan</label>
                            <div class="price-wrap">
                                <span class="price-pfx">Rp</span>
                                <input type="number" name="biaya_kerusakan" id="biayaInput"
                                       value="{{ old('biaya_kerusakan',0) }}" min="0" step="1000"
                                       class="form-input" oninput="recalc()">
                            </div>
                        </div>
                        <input type="hidden" name="biaya_kerusakan" value="0" id="biayaHidden">

                        {{-- Catatan --}}
                        <div class="form-group">
                            <label class="form-label">Catatan (opsional)</label>
                            <textarea name="catatan" class="form-input" rows="2"
                                      style="resize:none"
                                      placeholder="Kondisi detail, keterangan khusus...">{{ old('catatan') }}</textarea>
                        </div>

                        {{-- Foto --}}
                        <div class="form-group">
                            <label class="form-label">Foto Bukti (opsional)</label>
                            <div class="photo-zone">
                                <input type="file" name="foto_bukti" accept="image/*" onchange="previewFoto(this)">
                                <div id="photoPlaceholder">
                                    <div style="font-size:1.3rem;margin-bottom:0.25rem">📷</div>
                                    <div style="font-size:0.73rem;color:var(--muted)">Klik atau drag foto</div>
                                    <div style="font-size:0.65rem;color:var(--muted)">JPG/PNG/WEBP · maks 3MB</div>
                                </div>
                                <div id="previewWrap">
                                    <img id="previewImg" src="" alt="">
                                    <div style="font-size:0.65rem;color:var(--muted);margin-top:0.3rem">Klik untuk ganti</div>
                                </div>
                            </div>
                            @error('foto_bukti') <p style="font-size:0.7rem;color:var(--danger)">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit" class="btn-submit">
                            ↩ Simpan Pengembalian
                        </button>
                    </form>

                </div>
            </div>
        </div>

    </div>

    <script>
        const dendaPerHari = {{ $peminjaman->alat->denda_per_hari ?? 0 }};
        const jumlah       = {{ $peminjaman->jumlah }};
        const tglRencana   = '{{ $peminjaman->tanggal_kembali_rencana->toDateString() }}';

        function recalc() {
            const aktual = document.getElementById('tglAktual').value;
            if (!aktual) return;

            const a = new Date(aktual), r = new Date(tglRencana);
            const terlambat = Math.max(0, Math.round((a - r) / (1000*60*60*24)));
            const denda     = terlambat * dendaPerHari * jumlah;
            const kerusakan = parseInt(document.getElementById('biayaInput')?.value) || 0;
            const total     = denda + kerusakan;

            document.getElementById('dpTerlambat').textContent = terlambat + ' hari';
            document.getElementById('dpTerlambat').style.color = terlambat > 0 ? 'var(--danger)' : 'var(--jade)';
            document.getElementById('dpDenda').textContent     = 'Rp ' + denda.toLocaleString('id-ID');
            document.getElementById('dpDenda').style.color     = denda > 0 ? 'var(--danger)' : 'var(--sub)';
            document.getElementById('dpKerusakan').textContent = 'Rp ' + kerusakan.toLocaleString('id-ID');
            document.getElementById('dpTotal').textContent     = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('dpTotal').style.color     = total > 0 ? 'var(--danger)' : 'var(--green)';
        }

        function onKondisi(val) {
            const wrap   = document.getElementById('biayaWrap');
            const hidden = document.getElementById('biayaHidden');
            const input  = document.getElementById('biayaInput');

            if (val !== 'baik') {
                wrap.style.display = 'block';
                hidden.disabled    = true;
            } else {
                wrap.style.display = 'none';
                hidden.disabled    = false;
                if (input) { input.value = 0; recalc(); }
            }
            recalc();
        }

        function previewFoto(input) {
            if (input.files?.[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('photoPlaceholder').style.display = 'none';
                    document.getElementById('previewWrap').style.display      = 'block';
                    document.getElementById('previewImg').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.addEventListener('DOMContentLoaded', recalc);

        if (window.location.hash === '#form-kembali') {
            document.getElementById('form-kembali')?.scrollIntoView({ behavior:'smooth' });
        }
    </script>

</x-petugas-layout>