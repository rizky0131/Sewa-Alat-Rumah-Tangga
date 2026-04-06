<x-admin-layout title="Edit Peminjaman" breadcrumb="Edit Peminjaman">

    <x-slot name="header">
        <div>
            <h1 class="page-heading" style="font-family:monospace;font-size:1.4rem">{{ $peminjaman->nomor_pinjam }}</h1>
            <p class="page-sub">
                Edit data peminjaman ·
                <span class="status-pill sp-{{ $peminjaman->status }}" style="font-size:0.7rem">{{ ucfirst($peminjaman->status) }}</span>
            </p>
        </div>
        <div style="display:flex;gap:0.5rem">
            <a href="{{ route('admin.peminjamans.show', $peminjaman) }}" class="btn btn-ghost">← Detail</a>
        </div>
    </x-slot>

    <style>
        .status-pill { display:inline-flex;align-items:center;gap:0.3rem;padding:0.2rem 0.65rem;border-radius:100px;font-size:0.72rem;font-weight:700; }
        .status-pill::before { content:'';width:5px;height:5px;border-radius:50%; }
        .sp-menunggu  { background:rgba(212,168,67,0.12);color:#FCD34D;border:1px solid rgba(212,168,67,0.25); }
        .sp-menunggu::before  { background:var(--gold); }
        .sp-disetujui { background:rgba(59,130,246,0.12);color:#93C5FD;border:1px solid rgba(59,130,246,0.25); }
        .sp-disetujui::before { background:var(--accent); }
        .sp-dipinjam  { background:rgba(16,185,129,0.12);color:#34D399;border:1px solid rgba(16,185,129,0.25); }
        .sp-dipinjam::before  { background:var(--jade); }

        .edit-layout { display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start; }
        .fcard { background:var(--ink-80);border:1px solid rgba(255,255,255,0.07);border-radius:8px;margin-bottom:1.2rem;overflow:hidden; }
        .fcard-head { padding:1.1rem 1.4rem;border-bottom:1px solid rgba(255,255,255,0.07);display:flex;align-items:center;gap:0.7rem; }
        .fch-icon { width:32px;height:32px;border-radius:7px;background:rgba(37,99,235,0.15);color:var(--accent-l);display:flex;align-items:center;justify-content:center; }
        .fch-title { font-size:0.88rem;font-weight:700;color:var(--cream); }
        .fcard-body { padding:1.4rem; }

        /* Read-only info strip */
        .readonly-strip {
            background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);
            border-radius:7px;padding:1rem;margin-bottom:1rem;
        }
        .ro-grid { display:grid;grid-template-columns:repeat(3,1fr);gap:0.75rem; }
        .ro-item-label { font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--slate);margin-bottom:0.25rem; }
        .ro-item-val   { font-size:0.88rem;font-weight:700;color:var(--cream); }

        /* Alat preview */
        .alat-preview-card {
            display:flex;align-items:center;gap:0.9rem;padding:0.9rem;
            background:rgba(37,99,235,0.06);border:1px solid rgba(37,99,235,0.2);border-radius:7px;
        }
        .ap-thumb { width:46px;height:46px;border-radius:9px;overflow:hidden;flex-shrink:0;background:rgba(37,99,235,0.1);display:flex;align-items:center;justify-content:center;font-size:1.1rem; }
        .ap-thumb img { width:100%;height:100%;object-fit:cover; }

        /* Date grid */
        .date-grid { display:grid;grid-template-columns:1fr 1fr;gap:1rem; }

        /* Biaya preview */
        .biaya-preview { background:var(--ink-60);border:1px solid rgba(255,255,255,0.1);border-radius:8px;overflow:hidden; }
        .bp-row { display:flex;justify-content:space-between;padding:0.65rem 1rem;border-bottom:1px solid rgba(255,255,255,0.05); }
        .bp-row:last-child { border-bottom:none;background:rgba(16,185,129,0.08); }
        .bp-key { font-size:0.75rem;color:var(--mist); }
        .bp-val { font-size:0.8rem;font-weight:700;color:var(--silver); }
        .bp-total { font-size:1.05rem;font-weight:800;color:var(--jade); }

        /* Danger zone */
        .dz { background:rgba(239,68,68,0.06);border:1px solid rgba(239,68,68,0.18);border-radius:8px;padding:1rem;margin-top:1rem; }

        @media(max-width:900px) { .edit-layout { grid-template-columns:1fr; } .ro-grid { grid-template-columns:1fr 1fr; } }
    </style>

    <form method="POST" action="{{ route('admin.peminjamans.update', $peminjaman) }}"
          id="editPinjamanForm">
        @csrf @method('PUT')

        <div class="edit-layout">
            {{-- MAIN --}}
            <div>

                {{-- Read-only info strip --}}
                <div class="readonly-strip">
                    <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--slate);margin-bottom:0.75rem">
                        Data Terkunci (Tidak Dapat Diubah)
                    </div>
                    <div class="ro-grid">
                        <div>
                            <div class="ro-item-label">Nomor</div>
                            <div class="ro-item-val" style="font-family:monospace;font-size:0.82rem;color:var(--accent-l)">{{ $peminjaman->nomor_pinjam }}</div>
                        </div>
                        <div>
                            <div class="ro-item-label">Peminjam</div>
                            <div class="ro-item-val">{{ $peminjaman->peminjam->name }}</div>
                        </div>
                        <div>
                            <div class="ro-item-label">Jumlah</div>
                            <div class="ro-item-val">{{ $peminjaman->jumlah }} unit</div>
                        </div>
                        <div>
                            <div class="ro-item-label">Tanggal Pinjam</div>
                            <div class="ro-item-val">{{ $peminjaman->tanggal_pinjam->format('d M Y') }}</div>
                        </div>
                        <div>
                            <div class="ro-item-label">Status</div>
                            <div class="ro-item-val"><span class="status-pill sp-{{ $peminjaman->status }}">{{ ucfirst($peminjaman->status) }}</span></div>
                        </div>
                        <div>
                            <div class="ro-item-label">Dibuat</div>
                            <div class="ro-item-val" style="font-size:0.78rem">{{ $peminjaman->created_at->format('d M Y') }}</div>
                        </div>
                    </div>
                </div>

                {{-- Alat preview --}}
                <div class="fcard">
                    <div class="fcard-head">
                        <div class="fch-icon">🛠</div>
                        <span class="fch-title">Alat yang Dipinjam</span>
                    </div>
                    <div class="fcard-body">
                        <div class="alat-preview-card">
                            <div class="ap-thumb">
                                @if($peminjaman->alat?->foto)
                                    <img src="{{ asset('storage/'.$peminjaman->alat->foto) }}" alt="">
                                @else 🔧 @endif
                            </div>
                            <div>
                                <div style="font-size:0.9rem;font-weight:700;color:var(--cream)">
                                    {{ $peminjaman->alat->nama ?? '—' }}
                                </div>
                                <div style="font-size:0.72rem;color:var(--mist)">
                                    {{ $peminjaman->alat->kode ?? '' }}
                                    · {{ $peminjaman->alat->kategori->ikon ?? '' }} {{ $peminjaman->alat->kategori->nama ?? '' }}
                                </div>
                                <div style="margin-top:0.35rem;font-size:0.75rem;font-weight:700;color:var(--jade)">
                                    Rp {{ number_format($peminjaman->alat->harga_sewa_per_hari,0,',','.') }} / hari
                                </div>
                            </div>
                            <div style="margin-left:auto">
                                <span class="badge badge-slate" style="font-size:0.65rem">{{ $peminjaman->jumlah }} unit</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tanggal Kembali (editable) --}}
                <div class="fcard">
                    <div class="fcard-head">
                        <div class="fch-icon">📅</div>
                        <span class="fch-title">Ubah Tanggal Kembali</span>
                    </div>
                    <div class="fcard-body">
                        <div class="date-grid">
                            <div class="form-group" style="margin:0">
                                <label class="form-label">Tanggal Pinjam</label>
                                <input type="text" value="{{ $peminjaman->tanggal_pinjam->format('d M Y') }}"
                                       class="form-input" disabled
                                       style="opacity:0.5;cursor:not-allowed">
                                <p class="form-hint">Tidak dapat diubah.</p>
                            </div>
                            <div class="form-group" style="margin:0">
                                <label class="form-label">Tanggal Kembali <span style="color:var(--danger)">*</span></label>
                                <input type="date" name="tanggal_kembali_rencana" id="tglKembali"
                                       value="{{ old('tanggal_kembali_rencana', $peminjaman->tanggal_kembali_rencana->toDateString()) }}"
                                       min="{{ $peminjaman->tanggal_pinjam->toDateString() }}"
                                       class="form-input {{ $errors->has('tanggal_kembali_rencana') ? 'border-danger':'' }}"
                                       oninput="recalcBiaya()">
                                @error('tanggal_kembali_rencana')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Recalc preview --}}
                        <div style="margin-top:1rem;padding:0.85rem;background:rgba(37,99,235,0.07);border:1px solid rgba(37,99,235,0.2);border-radius:7px">
                            <div style="display:flex;align-items:center;gap:0.5rem;font-size:0.72rem;color:var(--accent-l);font-weight:700;margin-bottom:0.4rem">
                                🧮 Perhitungan Otomatis
                            </div>
                            <div style="display:flex;gap:1.5rem;flex-wrap:wrap;font-size:0.78rem;color:var(--silver)">
                                <span>Durasi: <strong id="previewDurasi">{{ $peminjaman->durasi_hari }}</strong> hari</span>
                                <span>Total: <strong id="previewTotal" style="color:var(--jade)">
                                    Rp {{ number_format($peminjaman->total_biaya, 0, ',', '.') }}
                                </strong></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tujuan & catatan --}}
                <div class="fcard">
                    <div class="fcard-head">
                        <div class="fch-icon">📝</div>
                        <span class="fch-title">Tujuan & Catatan</span>
                    </div>
                    <div class="fcard-body">
                        <div class="form-group">
                            <label class="form-label">Tujuan Peminjaman</label>
                            <textarea name="tujuan_peminjaman" class="form-textarea" rows="2"
                                      placeholder="Tujuan penggunaan alat...">{{ old('tujuan_peminjaman', $peminjaman->tujuan_peminjaman) }}</textarea>
                        </div>
                        <div class="form-group" style="margin:0">
                            <label class="form-label">Catatan Petugas</label>
                            <textarea name="catatan_petugas" class="form-textarea" rows="2"
                                      placeholder="Catatan atau keterangan dari petugas...">{{ old('catatan_petugas', $peminjaman->catatan_petugas) }}</textarea>
                        </div>
                    </div>
                </div>

            </div>

            {{-- SIDEBAR --}}
            <div>
                {{-- Biaya summary --}}
                <div class="fcard" style="margin-bottom:1rem">
                    <div class="fcard-head">
                        <div class="fch-icon" style="font-size:0.75rem">💰</div>
                        <span class="fch-title">Biaya Saat Ini</span>
                    </div>
                    <div style="padding:0.75rem">
                        <div class="biaya-preview">
                            <div class="bp-row">
                                <span class="bp-key">Harga/hari</span>
                                <span class="bp-val">Rp {{ number_format($peminjaman->alat->harga_sewa_per_hari,0,',','.') }}</span>
                            </div>
                            <div class="bp-row">
                                <span class="bp-key">× Jumlah</span>
                                <span class="bp-val">{{ $peminjaman->jumlah }} unit</span>
                            </div>
                            <div class="bp-row">
                                <span class="bp-key">× Durasi</span>
                                <span class="bp-val" id="sidebarDurasi">{{ $peminjaman->durasi_hari }} hari</span>
                            </div>
                            <div class="bp-row">
                                <span class="bp-key">Total</span>
                                <span class="bp-total" id="sidebarTotal">
                                    Rp {{ number_format($peminjaman->total_biaya, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="fcard" style="padding:1.2rem;margin-bottom:1rem">
                    <button type="submit" class="btn btn-primary"
                            style="width:100%;justify-content:center;padding:0.7rem;margin-bottom:0.5rem">
                        ✓ Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.peminjamans.show', $peminjaman) }}"
                       class="btn btn-ghost" style="width:100%;justify-content:center">
                        Batal
                    </a>
                </div>

                {{-- Status action shortcuts --}}
                @if($peminjaman->status === Peminjaman::STATUS_MENUNGGU)
                    <div style="background:rgba(212,168,67,0.07);border:1px solid rgba(212,168,67,0.2);border-radius:8px;padding:1rem">
                        <div style="font-size:0.75rem;font-weight:700;color:#FCD34D;margin-bottom:0.6rem">⏳ Aksi Cepat</div>
                        <form method="POST" action="{{ route('admin.peminjamans.setujui', $peminjaman) }}" style="margin-bottom:0.4rem">
                            @csrf
                            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;font-size:0.78rem">✓ Setujui Peminjaman</button>
                        </form>
                    </div>
                @elseif($peminjaman->status === Peminjaman::STATUS_DISETUJUI)
                    <div style="background:rgba(59,130,246,0.07);border:1px solid rgba(59,130,246,0.2);border-radius:8px;padding:1rem">
                        <div style="font-size:0.75rem;font-weight:700;color:#93C5FD;margin-bottom:0.6rem">✓ Aksi Cepat</div>
                        <form method="POST" action="{{ route('admin.peminjamans.tandai', $peminjaman) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;font-size:0.78rem">📦 Tandai Dipinjam</button>
                        </form>
                    </div>
                @endif

                {{-- Danger zone --}}
                @if(in_array($peminjaman->status, ['menunggu','ditolak']))
                    <div class="dz">
                        <div style="font-size:0.75rem;font-weight:700;color:#FCA5A5;margin-bottom:0.4rem">⚠ Hapus Peminjaman</div>
                        <div style="font-size:0.72rem;color:var(--mist);margin-bottom:0.7rem">Data akan dihapus (soft delete).</div>
                        <form method="POST" action="{{ route('admin.peminjamans.destroy', $peminjaman) }}"
                              onsubmit="return confirm('Hapus peminjaman {{ $peminjaman->nomor_pinjam }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center;font-size:0.78rem">
                                🗑 Hapus
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </form>

    <script>
        const hargaPerHari = {{ $peminjaman->alat->harga_sewa_per_hari }};
        const jumlah = {{ $peminjaman->jumlah }};
        const tglPinjam = '{{ $peminjaman->tanggal_pinjam->toDateString() }}';

        function recalcBiaya() {
            const tglK = document.getElementById('tglKembali').value;
            if (!tglK) return;

            const d1 = new Date(tglPinjam), d2 = new Date(tglK);
            const durasi = Math.max(0, Math.round((d2 - d1) / (1000 * 60 * 60 * 24)));
            const total  = hargaPerHari * jumlah * durasi;

            document.getElementById('previewDurasi').textContent = durasi;
            document.getElementById('previewTotal').textContent  = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('sidebarDurasi').textContent = durasi + ' hari';
            document.getElementById('sidebarTotal').textContent  = 'Rp ' + total.toLocaleString('id-ID');
        }

        // Warn on unsaved changes
        let changed = false;
        document.getElementById('editPinjamanForm').addEventListener('input', () => changed = true);
        document.getElementById('editPinjamanForm').addEventListener('submit', () => changed = false);
        window.addEventListener('beforeunload', e => { if (changed) { e.preventDefault(); e.returnValue = ''; }});
    </script>

</x-admin-layout>