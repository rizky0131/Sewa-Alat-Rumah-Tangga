<x-admin-layout title="Edit Pengembalian" breadcrumb="Edit Pengembalian">

    <x-slot name="header">
        <div>
            <h1 class="page-heading" style="font-size:1.35rem">Edit Pengembalian</h1>
            <p class="page-sub">
                <span style="font-family:monospace;color:var(--accent-l)">{{ $pengembalian->peminjaman->nomor_pinjam }}</span>
                · {{ $pengembalian->peminjaman->peminjam->name ?? '—' }}
            </p>
        </div>
        <div style="display:flex;gap:0.5rem">
            <a href="{{ route('admin.pengembalians.show', $pengembalian) }}" class="btn btn-ghost">← Detail</a>
        </div>
    </x-slot>

    <style>
        .edit-layout { display:grid;grid-template-columns:1fr 320px;gap:1.5rem;align-items:start; }
        .fcard { background:var(--ink-80);border:1px solid rgba(255,255,255,0.07);border-radius:8px;margin-bottom:1.2rem;overflow:hidden; }
        .fcard-head { padding:1.1rem 1.4rem;border-bottom:1px solid rgba(255,255,255,0.07);display:flex;align-items:center;gap:0.7rem; }
        .fch-icon { width:32px;height:32px;border-radius:7px;background:rgba(37,99,235,0.15);color:var(--accent-l);display:flex;align-items:center;justify-content:center; }
        .fch-title { font-size:0.88rem;font-weight:700;color:var(--cream); }
        .fcard-body { padding:1.4rem; }

        /* Read-only strip */
        .ro-strip { background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);border-radius:7px;padding:1rem;margin-bottom:1rem; }
        .ro-grid  { display:grid;grid-template-columns:repeat(3,1fr);gap:0.75rem; }
        .ro-label { font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--slate);margin-bottom:0.2rem; }
        .ro-val   { font-size:0.88rem;font-weight:700;color:var(--cream); }

        /* Kondisi */
        .kondisi-grid { display:grid;grid-template-columns:repeat(5,1fr);gap:0.4rem; }
        .kond-opt { position:relative; }
        .kond-opt input { position:absolute;opacity:0;width:0;height:0; }
        .kond-lbl {
            display:flex;flex-direction:column;align-items:center;gap:0.3rem;padding:0.7rem 0.2rem;
            border-radius:7px;cursor:pointer;text-align:center;
            background:rgba(255,255,255,0.04);border:1.5px solid rgba(255,255,255,0.08);transition:all 0.15s;
        }
        .kond-lbl:hover { border-color:rgba(255,255,255,0.2); }
        .kond-opt input:checked + .kond-lbl.kond-baik         { border-color:#10B981;background:rgba(16,185,129,0.12); }
        .kond-opt input:checked + .kond-lbl.kond-rusak_ringan { border-color:#D4A843;background:rgba(212,168,67,0.12); }
        .kond-opt input:checked + .kond-lbl.kond-rusak_sedang { border-color:#F97316;background:rgba(249,115,22,0.12); }
        .kond-opt input:checked + .kond-lbl.kond-rusak_berat  { border-color:#EF4444;background:rgba(239,68,68,0.12); }
        .kond-opt input:checked + .kond-lbl.kond-hilang       { border-color:#8B5CF6;background:rgba(139,92,246,0.12); }
        .kond-em  { font-size:1.2rem; }
        .kond-txt { font-size:0.62rem;font-weight:700;color:var(--silver); }

        /* Price */
        .price-wrap { position:relative; }
        .price-px   { position:absolute;left:0.9rem;top:50%;transform:translateY(-50%);color:var(--mist);font-size:0.78rem;pointer-events:none; }
        .price-wrap .form-input { padding-left:2.8rem; }

        /* Photo management */
        .existing-photo { position:relative;border-radius:8px;overflow:hidden; }
        .existing-photo img { width:100%;height:180px;object-fit:cover;display:block; }
        .photo-overlay { position:absolute;inset:0;background:rgba(0,0,0,0);transition:0.2s;display:flex;align-items:center;justify-content:center;gap:0.5rem; }
        .existing-photo:hover .photo-overlay { background:rgba(0,0,0,0.5); }
        .existing-photo:hover .overlay-btn { opacity:1; }
        .overlay-btn { padding:0.5rem 0.9rem;border-radius:5px;font-size:0.75rem;font-weight:700;cursor:pointer;border:none;font-family:var(--font-ui);opacity:0;transition:opacity 0.2s; }
        .photo-zone { border:2px dashed rgba(255,255,255,0.12);border-radius:8px;padding:1.3rem;text-align:center;cursor:pointer;transition:0.2s;position:relative;background:rgba(255,255,255,0.02); }
        .photo-zone:hover { border-color:var(--accent);background:rgba(37,99,235,0.05); }
        .photo-zone input { position:absolute;inset:0;opacity:0;cursor:pointer; }
        .new-preview { display:none; }
        .new-preview img { width:100%;height:140px;object-fit:cover;border-radius:7px; }

        /* Summary sidebar */
        .summary-card { background:var(--ink-60);border:1px solid rgba(255,255,255,0.1);border-radius:8px;overflow:hidden; }
        .sc-row { display:flex;justify-content:space-between;padding:0.7rem 1.1rem;border-bottom:1px solid rgba(255,255,255,0.06); }
        .sc-row:last-child { border-bottom:none;background:rgba(16,185,129,0.08); }
        .sc-key { font-size:0.75rem;color:var(--mist); }
        .sc-val { font-size:0.82rem;font-weight:700;color:var(--silver); }
        .sc-total { font-size:1.1rem;font-weight:800;color:var(--jade); }

        @media(max-width:900px) { .edit-layout { grid-template-columns:1fr; } .kondisi-grid { grid-template-columns:repeat(3,1fr); } .ro-grid { grid-template-columns:1fr 1fr; } }
    </style>

    <form method="POST" action="{{ route('admin.pengembalians.update', $pengembalian) }}"
          enctype="multipart/form-data" id="editForm">
        @csrf @method('PUT')

        <div class="edit-layout">
            {{-- MAIN --}}
            <div>
                {{-- Read-only data --}}
                <div class="ro-strip">
                    <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--slate);margin-bottom:0.75rem">Data Terkunci</div>
                    <div class="ro-grid">
                        <div>
                            <div class="ro-label">Nomor Transaksi</div>
                            <div class="ro-val" style="font-family:monospace;font-size:0.78rem;color:var(--accent-l)">{{ $pengembalian->peminjaman->nomor_pinjam }}</div>
                        </div>
                        <div>
                            <div class="ro-label">Peminjam</div>
                            <div class="ro-val">{{ $pengembalian->peminjaman->peminjam->name ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="ro-label">Alat</div>
                            <div class="ro-val" style="font-size:0.82rem">{{ $pengembalian->peminjaman->alat->nama ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="ro-label">Tgl Kembali Aktual</div>
                            <div class="ro-val">{{ $pengembalian->tanggal_kembali_aktual->format('d M Y') }}</div>
                        </div>
                        <div>
                            <div class="ro-label">Keterlambatan</div>
                            <div class="ro-val" style="{{ $pengembalian->keterlambatan_hari > 0 ? 'color:#FCA5A5' : 'color:var(--jade)' }}">
                                {{ $pengembalian->is_tepat_waktu ? 'Tepat Waktu' : $pengembalian->keterlambatan_hari.' hari' }}
                            </div>
                        </div>
                        <div>
                            <div class="ro-label">Denda Terlambat</div>
                            <div class="ro-val" style="{{ $pengembalian->denda > 0 ? 'color:#FCA5A5' : 'color:var(--jade)' }}">
                                Rp {{ number_format($pengembalian->denda,0,',','.') }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kondisi --}}
                <div class="fcard">
                    <div class="fcard-head">
                        <div class="fch-icon">🔍</div>
                        <span class="fch-title">Kondisi Alat Saat Kembali</span>
                    </div>
                    <div class="fcard-body">
                        <div class="kondisi-grid">
                            @foreach(['baik'=>['✅','Baik'],'rusak_ringan'=>['⚠️','Rusak Ringan'],'rusak_sedang'=>['🔶','Rusak Sedang'],'rusak_berat'=>['❌','Rusak Berat'],'hilang'=>['💀','Hilang']] as $val=>[$em,$lbl])
                                <div class="kond-opt">
                                    <input type="radio" name="kondisi_kembali" id="kd_{{ $val }}"
                                           value="{{ $val }}"
                                           {{ old('kondisi_kembali', $pengembalian->kondisi_kembali)==$val ? 'checked':'' }}
                                           onchange="onKondisiChange('{{ $val }}')">
                                    <label for="kd_{{ $val }}" class="kond-lbl kond-{{ $val }}">
                                        <span class="kond-em">{{ $em }}</span>
                                        <span class="kond-txt">{{ $lbl }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('kondisi_kembali') <p class="form-error" style="margin-top:0.5rem">{{ $message }}</p> @enderror

                        {{-- Biaya kerusakan --}}
                        <div id="biayaWrap"
                             style="margin-top:1rem;display:{{ old('kondisi_kembali',$pengembalian->kondisi_kembali)!='baik' ? 'block':'none' }}">
                            <div style="background:rgba(239,68,68,0.07);border:1px solid rgba(239,68,68,0.2);border-radius:7px;padding:1rem">
                                <div style="font-size:0.78rem;font-weight:700;color:#FCA5A5;margin-bottom:0.75rem">💸 Biaya Kerusakan / Kehilangan</div>
                                <div class="form-group" style="margin:0">
                                    <label class="form-label">Biaya Tambahan</label>
                                    <div class="price-wrap">
                                        <span class="price-px">Rp</span>
                                        <input type="number" name="biaya_kerusakan" id="biayaInput"
                                               value="{{ old('biaya_kerusakan', $pengembalian->biaya_kerusakan) }}"
                                               min="0" step="1000" class="form-input"
                                               oninput="recalcTotal()">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Baik: show biaya_kerusakan=0 hidden --}}
                        <input type="hidden" id="biayaHidden" name="biaya_kerusakan"
                               value="{{ old('biaya_kerusakan', $pengembalian->biaya_kerusakan) }}"
                               style="display:none">
                    </div>
                </div>

                {{-- Catatan --}}
                <div class="fcard">
                    <div class="fcard-head">
                        <div class="fch-icon">📝</div>
                        <span class="fch-title">Catatan</span>
                    </div>
                    <div class="fcard-body">
                        <textarea name="catatan" class="form-textarea" rows="3"
                                  placeholder="Catatan kondisi, keterangan tambahan...">{{ old('catatan', $pengembalian->catatan) }}</textarea>
                    </div>
                </div>

                {{-- Foto Bukti --}}
                <div class="fcard">
                    <div class="fcard-head">
                        <div class="fch-icon">📷</div>
                        <span class="fch-title">Foto Bukti</span>
                    </div>
                    <div class="fcard-body">
                        @if($pengembalian->foto_bukti)
                            <div id="existingWrap">
                                <div class="existing-photo" style="margin-bottom:0.75rem">
                                    <img src="{{ asset('storage/'.$pengembalian->foto_bukti) }}" alt="Foto bukti" id="existingImg">
                                    <div class="photo-overlay">
                                        <button type="button" class="overlay-btn"
                                                style="background:var(--accent);color:white"
                                                onclick="showNewUpload()">🔄 Ganti</button>
                                        <button type="button" class="overlay-btn"
                                                style="background:var(--danger);color:white"
                                                onclick="deleteFoto()">🗑 Hapus</button>
                                    </div>
                                </div>
                                <input type="hidden" name="hapus_foto" id="hapusFotoInput" value="0">
                                <p style="font-size:0.72rem;color:var(--slate)">Hover untuk opsi ganti / hapus.</p>
                            </div>
                        @endif

                        <div id="newUploadWrap" style="{{ $pengembalian->foto_bukti ? 'display:none' : '' }}">
                            <div class="photo-zone">
                                <input type="file" name="foto_bukti" accept="image/*" onchange="previewNew(this)">
                                <div id="newPlaceholder">
                                    <div style="font-size:1.6rem;margin-bottom:0.4rem">📷</div>
                                    <div style="font-size:0.8rem;color:var(--mist)">Klik atau drag foto baru</div>
                                    <div style="font-size:0.7rem;color:var(--slate)">JPG, PNG, WEBP · Maks. 3 MB</div>
                                </div>
                                <div class="new-preview" id="newPreview">
                                    <img id="newPreviewImg" src="" alt="">
                                    <button type="button" onclick="cancelNew()"
                                            style="width:100%;margin-top:0.4rem;background:none;border:none;color:var(--mist);cursor:pointer;font-size:0.75rem">
                                        ✕ Batal
                                    </button>
                                </div>
                            </div>
                        </div>
                        @error('foto_bukti') <p class="form-error" style="margin-top:0.4rem">{{ $message }}</p> @enderror
                    </div>
                </div>

            </div>

            {{-- SIDEBAR --}}
            <div>
                {{-- Tagihan preview --}}
                <div class="fcard" style="margin-bottom:1rem">
                    <div class="fcard-head">
                        <div class="fch-icon" style="font-size:0.75rem">💰</div>
                        <span class="fch-title">Total Tagihan</span>
                    </div>
                    <div style="padding:0.75rem">
                        <div class="summary-card">
                            <div class="sc-row">
                                <span class="sc-key">Denda Terlambat</span>
                                <span class="sc-val" style="{{ $pengembalian->denda > 0 ? 'color:#FCA5A5' : '' }}">
                                    Rp {{ number_format($pengembalian->denda,0,',','.') }}
                                </span>
                            </div>
                            <div class="sc-row">
                                <span class="sc-key">Biaya Kerusakan</span>
                                <span class="sc-val" id="sidebarKerusakan">
                                    Rp {{ number_format($pengembalian->biaya_kerusakan,0,',','.') }}
                                </span>
                            </div>
                            <div class="sc-row">
                                <span class="sc-key">Total</span>
                                <span class="sc-total" id="sidebarTotal">
                                    Rp {{ number_format($pengembalian->total_tagihan,0,',','.') }}
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
                    <a href="{{ route('admin.pengembalians.show', $pengembalian) }}"
                       class="btn btn-ghost" style="width:100%;justify-content:center">Batal</a>
                </div>

                {{-- Info: what can be edited --}}
                <div style="background:rgba(212,168,67,0.07);border:1px solid rgba(212,168,67,0.2);border-radius:8px;padding:1rem">
                    <div style="font-size:0.75rem;font-weight:700;color:#FCD34D;margin-bottom:0.5rem">ℹ Yang Dapat Diedit</div>
                    <div style="font-size:0.73rem;color:var(--silver);line-height:1.8">
                        • Kondisi kembali alat<br>
                        • Biaya kerusakan / kehilangan<br>
                        • Catatan pengembalian<br>
                        • Foto bukti
                    </div>
                    <div style="font-size:0.7rem;color:var(--slate);margin-top:0.6rem;border-top:1px solid rgba(255,255,255,0.07);padding-top:0.6rem">
                        Denda terlambat dan tanggal tidak dapat diubah karena dihitung otomatis saat pengembalian diproses.
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        const dendaFixed = {{ $pengembalian->denda }};

        function onKondisiChange(val) {
            const biayaWrap = document.getElementById('biayaWrap');
            const biayaInput = document.getElementById('biayaInput');
            const biayaHidden = document.getElementById('biayaHidden');

            if (val !== 'baik') {
                biayaWrap.style.display = 'block';
                biayaInput.disabled = false;
                biayaHidden.name = '_unused';
                document.querySelector('input[name="biaya_kerusakan"]:not([id="biayaHidden"])').name = 'biaya_kerusakan';
            } else {
                biayaWrap.style.display = 'none';
                biayaInput.value = 0;
                recalcTotal();
            }
        }

        function recalcTotal() {
            const biaya = parseInt(document.getElementById('biayaInput')?.value) || 0;
            const total = dendaFixed + biaya;
            document.getElementById('sidebarKerusakan').textContent = 'Rp ' + biaya.toLocaleString('id-ID');
            document.getElementById('sidebarTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('sidebarTotal').style.color = total > 0 ? '#FCA5A5' : 'var(--jade)';
        }

        // Photo management
        function showNewUpload() {
            document.getElementById('existingWrap').style.display = 'none';
            document.getElementById('newUploadWrap').style.display = 'block';
        }
        function deleteFoto() {
            if (!confirm('Hapus foto bukti ini?')) return;
            document.getElementById('hapusFotoInput').value = '1';
            document.getElementById('existingImg').style.opacity = '0.3';
        }
        function previewNew(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('newPlaceholder').style.display = 'none';
                    document.getElementById('newPreview').style.display = 'block';
                    document.getElementById('newPreviewImg').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        function cancelNew() {
            document.querySelector('input[name="foto_bukti"]').value = '';
            document.getElementById('newPlaceholder').style.display = 'block';
            document.getElementById('newPreview').style.display = 'none';
            @if($pengembalian->foto_bukti)
            document.getElementById('existingWrap').style.display = 'block';
            document.getElementById('newUploadWrap').style.display = 'none';
            @endif
        }

        // Unsaved change warning
        let changed = false;
        document.getElementById('editForm').addEventListener('input', () => changed = true);
        document.getElementById('editForm').addEventListener('submit', () => changed = false);
        window.addEventListener('beforeunload', e => { if (changed) { e.preventDefault(); e.returnValue = ''; }});
    </script>

</x-admin-layout>