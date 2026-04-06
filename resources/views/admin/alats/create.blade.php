<x-admin-layout title="Tambah Alat" breadcrumb="Tambah Alat">

    <x-slot name="header">
        <div>
            <h1 class="page-heading">Tambah Alat Baru</h1>
            <p class="page-sub">Daftarkan alat baru ke inventaris sistem peminjaman.</p>
        </div>
        <a href="{{ route('admin.alats.index') }}" class="btn btn-ghost">← Kembali</a>
    </x-slot>

    <style>
        .create-layout { display:grid; grid-template-columns:1fr 340px; gap:1.5rem; align-items:start; }
        .fcard { background:var(--ink-80);border:1px solid rgba(255,255,255,0.07);border-radius:8px;margin-bottom:1.2rem;overflow:hidden; }
        .fcard-head { padding:1.1rem 1.4rem;border-bottom:1px solid rgba(255,255,255,0.07);display:flex;align-items:center;gap:0.7rem; }
        .fch-icon { width:32px;height:32px;border-radius:7px;background:rgba(37,99,235,0.15);color:var(--accent-l);display:flex;align-items:center;justify-content:center;font-size:0.95rem; }
        .fch-title { font-size:0.88rem;font-weight:700;color:var(--cream); }
        .fcard-body { padding:1.4rem; }

        /* Photo drop zone */
        .photo-zone {
            border:2px dashed rgba(255,255,255,0.12);border-radius:10px;padding:2rem;
            text-align:center;cursor:pointer;transition:all 0.2s;position:relative;
            background:rgba(255,255,255,0.02);
        }
        .photo-zone:hover,.photo-zone.dragover {
            border-color:var(--accent);background:rgba(37,99,235,0.06);
        }
        .photo-zone input[type="file"] { position:absolute;inset:0;opacity:0;cursor:pointer; }
        .photo-zone-icon { font-size:2rem;margin-bottom:0.5rem; }
        .photo-zone-text { font-size:0.82rem;color:var(--mist); }
        .photo-zone-hint { font-size:0.7rem;color:var(--slate);margin-top:0.25rem; }
        .photo-preview {
            display:none;position:relative;
            width:100%;max-height:200px;overflow:hidden;border-radius:8px;
        }
        .photo-preview img { width:100%;height:200px;object-fit:cover;border-radius:8px; }
        .photo-preview-remove {
            position:absolute;top:8px;right:8px;
            width:28px;height:28px;border-radius:50%;
            background:rgba(0,0,0,0.7);color:white;border:none;cursor:pointer;
            display:flex;align-items:center;justify-content:center;font-size:0.8rem;
        }

        /* Kode input with badge */
        .kode-wrap { display:flex;gap:0.4rem;align-items:center; }
        .kode-prefix { background:rgba(37,99,235,0.12);color:var(--accent-l);border:1px solid rgba(37,99,235,0.25);border-radius:6px;padding:0.65rem 0.8rem;font-size:0.8rem;font-weight:700;font-family:monospace;flex-shrink:0; }

        /* Pricing grid */
        .pricing-grid { display:grid;grid-template-columns:1fr 1fr;gap:1rem; }
        .price-input-wrap { position:relative; }
        .price-prefix { position:absolute;left:0.9rem;top:50%;transform:translateY(-50%);color:var(--mist);font-size:0.78rem;font-weight:600;pointer-events:none; }
        .price-input-wrap .form-input { padding-left:2.8rem; }

        /* Kondisi selector */
        .kondisi-grid { display:grid;grid-template-columns:repeat(4,1fr);gap:0.5rem; }
        .kondisi-option { position:relative; }
        .kondisi-option input { position:absolute;opacity:0;width:0;height:0; }
        .kondisi-label {
            display:flex;flex-direction:column;align-items:center;gap:0.35rem;
            padding:0.75rem 0.3rem;border-radius:7px;cursor:pointer;text-align:center;
            background:rgba(255,255,255,0.04);border:1.5px solid rgba(255,255,255,0.08);
            transition:all 0.15s;
        }
        .kondisi-label:hover { border-color:rgba(255,255,255,0.18);background:rgba(255,255,255,0.07); }
        .kondisi-option input:checked + .kondisi-label { border-color:var(--accent);background:rgba(37,99,235,0.1); }
        .kondisi-em  { font-size:1.3rem; }
        .kondisi-lbl { font-size:0.68rem;font-weight:700;color:var(--silver); }

        /* Status toggle */
        .ios-toggle { position:relative;width:44px;height:26px; }
        .ios-toggle input { opacity:0;width:0;height:0; }
        .ios-slider { position:absolute;cursor:pointer;inset:0;background:var(--slate);border-radius:26px;transition:background 0.2s; }
        .ios-slider::before { content:'';position:absolute;height:20px;width:20px;left:3px;bottom:3px;background:white;border-radius:50%;transition:transform 0.2s; }
        .ios-toggle input:checked + .ios-slider { background:var(--jade); }
        .ios-toggle input:checked + .ios-slider::before { transform:translateX(18px); }

        /* Summary card */
        .summary-card { background:var(--ink-60);border:1px solid rgba(255,255,255,0.1);border-radius:8px;overflow:hidden; }
        .summary-photo { width:100%;height:140px;background:rgba(37,99,235,0.08);display:flex;align-items:center;justify-content:center;font-size:3rem; }
        .summary-photo img { width:100%;height:100%;object-fit:cover; }
        .summary-body { padding:1rem; }
        .summary-name { font-size:0.95rem;font-weight:700;color:var(--cream);margin-bottom:0.25rem; }
        .summary-kode { font-size:0.7rem;color:var(--slate);font-family:monospace; }
        .summary-price { font-size:1.1rem;font-weight:700;color:var(--jade);margin-top:0.6rem; }
        .summary-meta { margin-top:0.75rem;display:flex;flex-wrap:wrap;gap:0.35rem; }

        @media(max-width:1000px) { .create-layout { grid-template-columns:1fr; } .kondisi-grid { grid-template-columns:repeat(2,1fr); } }
    </style>

    <form method="POST" action="{{ route('admin.alats.store') }}"
          enctype="multipart/form-data" id="createAlatForm">
        @csrf

        <div class="create-layout">

            {{-- ── MAIN ──────────────────────────────── --}}
            <div>

                {{-- Foto --}}
                <div class="fcard">
                    <div class="fcard-head">
                        <div class="fch-icon">📷</div>
                        <span class="fch-title">Foto Alat</span>
                    </div>
                    <div class="fcard-body">
                        <div class="photo-zone" id="photoZone">
                            <input type="file" name="foto" id="fotoInput"
                                   accept="image/jpeg,image/png,image/webp"
                                   onchange="previewPhoto(this)">
                            <div id="photoPlaceholder">
                                <div class="photo-zone-icon">📸</div>
                                <div class="photo-zone-text">Klik atau drag foto ke sini</div>
                                <div class="photo-zone-hint">JPG, PNG, WEBP · Maks. 2 MB</div>
                            </div>
                            <div class="photo-preview" id="photoPreview">
                                <img id="photoPreviewImg" src="" alt="Preview">
                                <button type="button" class="photo-preview-remove" onclick="removePhoto()">✕</button>
                            </div>
                        </div>
                        @error('foto') <p class="form-error" style="margin-top:0.4rem">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Info Dasar --}}
                <div class="fcard">
                    <div class="fcard-head">
                        <div class="fch-icon">🏷</div>
                        <span class="fch-title">Informasi Alat</span>
                    </div>
                    <div class="fcard-body">
                        <div class="form-row" style="margin-bottom:1rem">
                            <div class="form-group" style="margin:0">
                                <label class="form-label">Kode Alat <span style="color:var(--danger)">*</span></label>
                                <div class="kode-wrap">
                                    <span class="kode-prefix">ALT-</span>
                                    <input type="text" name="kode" id="kodeInput"
                                           value="{{ old('kode', $suggestedKode) }}"
                                           class="form-input {{ $errors->has('kode') ? 'border-danger' : '' }}"
                                           placeholder="{{ $suggestedKode }}"
                                           oninput="updateSummary()">
                                </div>
                                @error('kode') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="form-group" style="margin:0">
                                <label class="form-label">Merk / Brand</label>
                                <input type="text" name="merk" value="{{ old('merk') }}"
                                       class="form-input" placeholder="cth: Philips, Makita..."
                                       oninput="updateSummary()">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nama Alat <span style="color:var(--danger)">*</span></label>
                            <input type="text" name="nama" id="namaInput"
                                   value="{{ old('nama') }}"
                                   class="form-input {{ $errors->has('nama') ? 'border-danger' : '' }}"
                                   placeholder="cth: Vacuum Cleaner Portable, Bor Listrik..."
                                   oninput="updateSummary()" autofocus>
                            @error('nama') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Kategori <span style="color:var(--danger)">*</span></label>
                            <select name="kategori_id" id="kategoriInput"
                                    class="form-select {{ $errors->has('kategori_id') ? 'border-danger' : '' }}"
                                    onchange="updateSummary()">
                                <option value="">— Pilih kategori —</option>
                                @foreach($kategoris as $kat)
                                    <option value="{{ $kat->id }}"
                                            {{ old('kategori_id', $selectedKategori) == $kat->id ? 'selected' : '' }}>
                                        {{ $kat->ikon }} {{ $kat->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_id') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-textarea" rows="3"
                                      placeholder="Spesifikasi, cara penggunaan, atau catatan penting...">{{ old('deskripsi') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Stok --}}
                <div class="fcard">
                    <div class="fcard-head">
                        <div class="fch-icon">📦</div>
                        <span class="fch-title">Stok Inventaris</span>
                    </div>
                    <div class="fcard-body">
                        <div class="form-row">
                            <div class="form-group" style="margin:0">
                                <label class="form-label">Stok Total <span style="color:var(--danger)">*</span></label>
                                <input type="number" name="stok_total" id="stokTotal"
                                       value="{{ old('stok_total', 1) }}" min="1"
                                       class="form-input {{ $errors->has('stok_total') ? 'border-danger' : '' }}"
                                       oninput="syncStok()">
                                <p class="form-hint">Jumlah unit yang dimiliki.</p>
                                @error('stok_total') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="form-group" style="margin:0">
                                <label class="form-label">Stok Tersedia <span style="color:var(--danger)">*</span></label>
                                <input type="number" name="stok_tersedia" id="stokTersedia"
                                       value="{{ old('stok_tersedia', 1) }}" min="0"
                                       class="form-input {{ $errors->has('stok_tersedia') ? 'border-danger' : '' }}">
                                <p class="form-hint">Unit yang siap dipinjam sekarang.</p>
                                @error('stok_tersedia') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Harga --}}
                <div class="fcard">
                    <div class="fcard-head">
                        <div class="fch-icon">💰</div>
                        <span class="fch-title">Harga Sewa & Denda</span>
                    </div>
                    <div class="fcard-body">
                        <div class="pricing-grid">
                            <div class="form-group" style="margin:0">
                                <label class="form-label">Harga Sewa / Hari <span style="color:var(--danger)">*</span></label>
                                <div class="price-input-wrap">
                                    <span class="price-prefix">Rp</span>
                                    <input type="number" name="harga_sewa_per_hari" id="hargaInput"
                                           value="{{ old('harga_sewa_per_hari', 0) }}" min="0" step="500"
                                           class="form-input {{ $errors->has('harga_sewa_per_hari') ? 'border-danger' : '' }}"
                                           oninput="updateSummary()">
                                </div>
                                @error('harga_sewa_per_hari') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="form-group" style="margin:0">
                                <label class="form-label">Denda Keterlambatan / Hari</label>
                                <div class="price-input-wrap">
                                    <span class="price-prefix">Rp</span>
                                    <input type="number" name="denda_per_hari"
                                           value="{{ old('denda_per_hari', 0) }}" min="0" step="500"
                                           class="form-input">
                                </div>
                                <p class="form-hint">0 = tidak ada denda.</p>
                            </div>
                        </div>

                        {{-- Price calculator --}}
                        <div style="margin-top:1rem;padding:0.9rem;background:rgba(37,99,235,0.06);border:1px solid rgba(37,99,235,0.18);border-radius:7px">
                            <div style="font-size:0.72rem;font-weight:700;color:var(--accent-l);margin-bottom:0.5rem">🧮 Kalkulator Biaya</div>
                            <div style="display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap">
                                <label style="font-size:0.78rem;color:var(--mist)">Hitung untuk</label>
                                <input type="number" id="calcHari" value="3" min="1"
                                       style="width:60px;background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.12);border-radius:5px;padding:0.35rem 0.5rem;color:var(--cream);font-family:var(--font-ui);font-size:0.82rem;outline:none;"
                                       oninput="calcBiaya()">
                                <label style="font-size:0.78rem;color:var(--mist)">hari =</label>
                                <span id="calcResult" style="font-size:0.95rem;font-weight:700;color:var(--jade)">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kondisi & Status --}}
                <div class="fcard">
                    <div class="fcard-head">
                        <div class="fch-icon">⚙️</div>
                        <span class="fch-title">Kondisi & Status</span>
                    </div>
                    <div class="fcard-body">
                        <div class="form-group">
                            <label class="form-label">Kondisi Alat <span style="color:var(--danger)">*</span></label>
                            <div class="kondisi-grid">
                                @php
                                    $kondisiOptions = [
                                        'baik'         => ['✅', 'Baik',          'Siap digunakan'],
                                        'rusak_ringan' => ['⚠️', 'Rusak Ringan',  'Masih bisa dipakai'],
                                        'rusak_berat'  => ['❌', 'Rusak Berat',   'Perlu perbaikan'],
                                        'perbaikan'    => ['🔧', 'Perbaikan',     'Sedang diperbaiki'],
                                    ];
                                @endphp
                                @foreach($kondisiOptions as $val => [$em, $lbl, $desc])
                                    <div class="kondisi-option">
                                        <input type="radio" name="kondisi" id="kondisi_{{ $val }}"
                                               value="{{ $val }}"
                                               {{ old('kondisi', 'baik') == $val ? 'checked' : '' }}>
                                        <label for="kondisi_{{ $val }}" class="kondisi-label">
                                            <span class="kondisi-em">{{ $em }}</span>
                                            <span class="kondisi-lbl">{{ $lbl }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('kondisi') <p class="form-error" style="margin-top:0.5rem">{{ $message }}</p> @enderror
                        </div>

                        <div style="padding:1rem;background:rgba(255,255,255,0.03);border-radius:7px;border:1px solid rgba(255,255,255,0.07)">
                            <div style="display:flex;align-items:center;justify-content:space-between">
                                <div>
                                    <div style="font-size:0.85rem;font-weight:600;color:var(--silver)">Status Alat</div>
                                    <div style="font-size:0.72rem;color:var(--mist);margin-top:0.1rem">Alat nonaktif tidak bisa dipinjam.</div>
                                </div>
                                <label class="ios-toggle">
                                    <input type="checkbox" name="status" value="aktif"
                                           {{ old('status', 'aktif') == 'aktif' ? 'checked' : '' }}>
                                    <span class="ios-slider"></span>
                                </label>
                            </div>
                            {{-- Fallback for unchecked --}}
                            <input type="hidden" name="_status_fallback" value="nonaktif">
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── SIDEBAR ───────────────────────────── --}}
            <div>
                {{-- Live summary --}}
                <div class="fcard" style="margin-bottom:1rem;overflow:hidden">
                    <div class="fcard-head">
                        <div class="fch-icon" style="font-size:0.8rem">👁</div>
                        <span class="fch-title">Pratinjau Kartu</span>
                    </div>
                    <div style="padding:0.75rem">
                        <div class="summary-card">
                            <div class="summary-photo" id="summaryPhoto">🔧</div>
                            <div class="summary-body">
                                <div class="summary-name" id="summaryName">Nama Alat</div>
                                <div class="summary-kode" id="summaryKode">ALT-001</div>
                                <div class="summary-price" id="summaryPrice">Rp 0 / hari</div>
                                <div class="summary-meta" id="summaryMeta">
                                    <span class="badge badge-green">Baik</span>
                                    <span class="badge badge-green">Aktif</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="fcard" style="padding:1.2rem;margin-bottom:1rem">
                    <button type="submit" class="btn btn-primary"
                            style="width:100%;justify-content:center;padding:0.7rem;margin-bottom:0.5rem">
                        ＋ Simpan Alat
                    </button>
                    <a href="{{ route('admin.alats.index') }}"
                       class="btn btn-ghost" style="width:100%;justify-content:center">
                        Batal
                    </a>
                </div>

                {{-- Checklist --}}
                <div style="background:rgba(37,99,235,0.06);border:1px solid rgba(37,99,235,0.18);border-radius:8px;padding:1.1rem">
                    <div style="font-size:0.75rem;font-weight:700;color:var(--accent-l);margin-bottom:0.6rem">📋 Checklist</div>
                    <div id="checklistItems" style="font-size:0.75rem;color:var(--silver);line-height:2">
                        <div id="chk-nama">○ Nama alat</div>
                        <div id="chk-kode">○ Kode alat</div>
                        <div id="chk-kategori">○ Kategori</div>
                        <div id="chk-harga">○ Harga sewa</div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        // Photo preview
        function previewPhoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('photoPlaceholder').style.display = 'none';
                    const preview = document.getElementById('photoPreview');
                    preview.style.display = 'block';
                    document.getElementById('photoPreviewImg').src = e.target.result;
                    document.getElementById('summaryPhoto').innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover">`;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        function removePhoto() {
            document.getElementById('fotoInput').value = '';
            document.getElementById('photoPlaceholder').style.display = 'block';
            document.getElementById('photoPreview').style.display = 'none';
            document.getElementById('summaryPhoto').innerHTML = '🔧';
        }

        // Drag & drop
        const zone = document.getElementById('photoZone');
        zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('dragover'); });
        zone.addEventListener('dragleave', () => zone.classList.remove('dragover'));
        zone.addEventListener('drop', e => {
            e.preventDefault(); zone.classList.remove('dragover');
            if (e.dataTransfer.files[0]) {
                document.getElementById('fotoInput').files = e.dataTransfer.files;
                previewPhoto(document.getElementById('fotoInput'));
            }
        });

        // Stok sync
        function syncStok() {
            const total = parseInt(document.getElementById('stokTotal').value) || 0;
            const tersedia = document.getElementById('stokTersedia');
            if (parseInt(tersedia.value) > total) tersedia.value = total;
        }

        // Live summary update
        function updateSummary() {
            const nama = document.getElementById('namaInput').value;
            const kode = document.getElementById('kodeInput').value;
            const harga = parseInt(document.getElementById('hargaInput').value) || 0;

            document.getElementById('summaryName').textContent = nama || 'Nama Alat';
            document.getElementById('summaryKode').textContent = kode || 'ALT-001';
            document.getElementById('summaryPrice').textContent = 'Rp ' + harga.toLocaleString('id-ID') + ' / hari';

            // Checklist
            const checks = {
                'chk-nama':     !!nama,
                'chk-kode':     !!kode,
                'chk-kategori': !!document.getElementById('kategoriInput').value,
                'chk-harga':    harga > 0,
            };
            Object.entries(checks).forEach(([id, done]) => {
                const el = document.getElementById(id);
                if (el) { el.textContent = (done ? '✓ ' : '○ ') + el.textContent.slice(2); el.style.color = done ? 'var(--jade)' : 'var(--silver)'; }
            });

            calcBiaya();
        }

        // Price calculator
        function calcBiaya() {
            const harga = parseInt(document.getElementById('hargaInput').value) || 0;
            const hari  = parseInt(document.getElementById('calcHari').value) || 0;
            document.getElementById('calcResult').textContent = 'Rp ' + (harga * hari).toLocaleString('id-ID');
        }

        // Handle status checkbox → hidden status field
        document.querySelector('input[name="status"]').addEventListener('change', function() {
            // The actual submit value: if checkbox checked = 'aktif', else fallback field 'nonaktif' is used
        });

        // Fix: override status field on submit
        document.getElementById('createAlatForm').addEventListener('submit', function() {
            const cb = this.querySelector('input[name="status"][type="checkbox"]');
            if (!cb.checked) cb.value = 'nonaktif';
            // remove fallback
            const fb = this.querySelector('input[name="_status_fallback"]');
            if (fb) fb.remove();
        });

        // Init
        updateSummary();
    </script>

</x-admin-layout>