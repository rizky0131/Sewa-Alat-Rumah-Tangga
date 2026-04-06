<x-admin-layout title="Edit Alat" breadcrumb="Edit Alat">

    <x-slot name="header">
        <div style="display:flex;align-items:center;gap:1rem">
            <div style="width:46px;height:46px;border-radius:10px;overflow:hidden;background:rgba(37,99,235,0.1);border:1px solid rgba(37,99,235,0.2);display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0">
                @if($alat->foto)
                    <img src="{{ asset('storage/'.$alat->foto) }}" style="width:100%;height:100%;object-fit:cover">
                @else 🔧 @endif
            </div>
            <div>
                <h1 class="page-heading" style="font-size:1.35rem">Edit: {{ $alat->nama }}</h1>
                <p class="page-sub"><code>{{ $alat->kode }}</code> · {{ $alat->kategori->nama ?? '—' }}</p>
            </div>
        </div>
        <div style="display:flex;gap:0.5rem">
            <a href="{{ route('admin.alats.show', $alat) }}" class="btn btn-ghost">← Detail</a>
            <a href="{{ route('admin.alats.index') }}" class="btn btn-ghost">Semua Alat</a>
        </div>
    </x-slot>

    <style>
        .edit-layout { display:grid;grid-template-columns:1fr 320px;gap:1.5rem;align-items:start; }
        .fcard { background:var(--ink-80);border:1px solid rgba(255,255,255,0.07);border-radius:8px;margin-bottom:1.2rem;overflow:hidden; }
        .fcard-head { padding:1.1rem 1.4rem;border-bottom:1px solid rgba(255,255,255,0.07);display:flex;align-items:center;gap:0.7rem; }
        .fch-icon { width:32px;height:32px;border-radius:7px;background:rgba(37,99,235,0.15);color:var(--accent-l);display:flex;align-items:center;justify-content:center;font-size:0.95rem; }
        .fch-title { font-size:0.88rem;font-weight:700;color:var(--cream); }
        .fcard-body { padding:1.4rem; }

        /* Existing photo */
        .existing-photo { position:relative;border-radius:8px;overflow:hidden;margin-bottom:0.75rem; }
        .existing-photo img { width:100%;height:180px;object-fit:cover;display:block; }
        .existing-photo-overlay {
            position:absolute;inset:0;background:rgba(0,0,0,0);transition:background 0.2s;
            display:flex;align-items:center;justify-content:center;gap:0.5rem;
        }
        .existing-photo:hover .existing-photo-overlay { background:rgba(0,0,0,0.5); }
        .existing-photo:hover .photo-overlay-btn { opacity:1; }
        .photo-overlay-btn {
            padding:0.5rem 0.9rem;border-radius:5px;font-size:0.75rem;font-weight:700;
            cursor:pointer;border:none;font-family:var(--font-ui);opacity:0;transition:opacity 0.2s;
        }

        /* New photo drop zone */
        .photo-zone { border:2px dashed rgba(255,255,255,0.12);border-radius:10px;padding:1.5rem;text-align:center;cursor:pointer;transition:all 0.2s;position:relative;background:rgba(255,255,255,0.02); }
        .photo-zone:hover { border-color:var(--accent);background:rgba(37,99,235,0.06); }
        .photo-zone input { position:absolute;inset:0;opacity:0;cursor:pointer; }
        .new-preview { display:none;border-radius:8px;overflow:hidden; }
        .new-preview img { width:100%;height:160px;object-fit:cover; }

        /* Kode */
        .kode-wrap { display:flex;gap:0.4rem;align-items:center; }
        .kode-prefix { background:rgba(37,99,235,0.12);color:var(--accent-l);border:1px solid rgba(37,99,235,0.25);border-radius:6px;padding:0.65rem 0.8rem;font-size:0.8rem;font-weight:700;font-family:monospace;flex-shrink:0; }

        /* Pricing */
        .pricing-grid { display:grid;grid-template-columns:1fr 1fr;gap:1rem; }
        .price-wrap { position:relative; }
        .price-pfx { position:absolute;left:0.9rem;top:50%;transform:translateY(-50%);color:var(--mist);font-size:0.78rem;font-weight:600;pointer-events:none; }
        .price-wrap .form-input { padding-left:2.8rem; }

        /* Kondisi */
        .kondisi-grid { display:grid;grid-template-columns:repeat(4,1fr);gap:0.5rem; }
        .kondisi-opt { position:relative; }
        .kondisi-opt input { position:absolute;opacity:0;width:0;height:0; }
        .kondisi-lbl {
            display:flex;flex-direction:column;align-items:center;gap:0.35rem;padding:0.75rem 0.3rem;
            border-radius:7px;cursor:pointer;text-align:center;
            background:rgba(255,255,255,0.04);border:1.5px solid rgba(255,255,255,0.08);transition:all 0.15s;
        }
        .kondisi-lbl:hover { border-color:rgba(255,255,255,0.18); }
        .kondisi-opt input:checked + .kondisi-lbl { border-color:var(--accent);background:rgba(37,99,235,0.1); }
        .kondisi-em  { font-size:1.3rem; }
        .kondisi-txt { font-size:0.68rem;font-weight:700;color:var(--silver); }

        /* Toggle */
        .ios-toggle { position:relative;width:44px;height:26px; }
        .ios-toggle input { opacity:0;width:0;height:0; }
        .ios-slider { position:absolute;cursor:pointer;inset:0;background:var(--slate);border-radius:26px;transition:background 0.2s; }
        .ios-slider::before { content:'';position:absolute;height:20px;width:20px;left:3px;bottom:3px;background:white;border-radius:50%;transition:transform 0.2s; }
        .ios-toggle input:checked + .ios-slider { background:var(--jade); }
        .ios-toggle input:checked + .ios-slider::before { transform:translateX(18px); }

        /* Change summary */
        .change-pill {
            display:inline-flex;align-items:center;gap:0.3rem;padding:0.2rem 0.6rem;
            border-radius:100px;font-size:0.68rem;font-weight:700;
            background:rgba(212,168,67,0.15);color:#FCD34D;
            border:1px solid rgba(212,168,67,0.25);margin-left:0.5rem;
        }

        /* Danger */
        .dz { background:rgba(239,68,68,0.06);border:1px solid rgba(239,68,68,0.18);border-radius:8px;padding:1rem; }
        .dz-title { font-size:0.78rem;font-weight:700;color:#FCA5A5;margin-bottom:0.4rem; }
        .dz-desc  { font-size:0.73rem;color:var(--mist);line-height:1.4;margin-bottom:0.75rem; }

        @media(max-width:1000px) { .edit-layout { grid-template-columns:1fr; } .kondisi-grid { grid-template-columns:repeat(2,1fr); } }
    </style>

    <form method="POST" action="{{ route('admin.alats.update', $alat) }}"
          enctype="multipart/form-data" id="editAlatForm">
        @csrf @method('PUT')

        <div class="edit-layout">

            {{-- ── MAIN ──────────────────────────────── --}}
            <div>

                {{-- Foto --}}
                <div class="fcard">
                    <div class="fcard-head">
                        <div class="fch-icon">📷</div>
                        <span class="fch-title">Foto Alat</span>
                        @if($alat->foto) <span class="change-pill" id="fotoPill" style="display:none">● Akan diubah</span> @endif
                    </div>
                    <div class="fcard-body">
                        {{-- Existing photo --}}
                        @if($alat->foto)
                            <div id="existingPhotoWrap">
                                <div class="existing-photo">
                                    <img src="{{ asset('storage/'.$alat->foto) }}" alt="{{ $alat->nama }}" id="existingImg">
                                    <div class="existing-photo-overlay">
                                        <button type="button" class="photo-overlay-btn"
                                                style="background:var(--accent);color:white"
                                                onclick="showNewUpload()">🔄 Ganti</button>
                                        <button type="button" class="photo-overlay-btn"
                                                style="background:var(--danger);color:white"
                                                onclick="confirmDeletePhoto()">🗑 Hapus</button>
                                    </div>
                                </div>
                                <input type="hidden" name="hapus_foto" id="hapusFotoInput" value="0">
                                <p style="font-size:0.72rem;color:var(--slate);margin-top:0.4rem">
                                    Hover foto untuk opsi ganti atau hapus.
                                </p>
                            </div>
                        @endif

                        {{-- New upload area --}}
                        <div id="newUploadWrap" style="{{ $alat->foto ? 'display:none' : '' }}">
                            <div class="photo-zone" id="newPhotoZone">
                                <input type="file" name="foto" id="newFotoInput"
                                       accept="image/*" onchange="previewNewPhoto(this)">
                                <div id="newPhotoPlaceholder">
                                    <div style="font-size:1.8rem;margin-bottom:0.4rem">📸</div>
                                    <div style="font-size:0.82rem;color:var(--mist)">Klik atau drag foto baru</div>
                                    <div style="font-size:0.7rem;color:var(--slate)">JPG, PNG, WEBP · Maks. 2 MB</div>
                                </div>
                                <div class="new-preview" id="newPhotoPreview">
                                    <img id="newPreviewImg" src="" alt="New preview">
                                    <button type="button" onclick="cancelNewPhoto()"
                                            style="width:100%;background:none;border:none;color:var(--mist);cursor:pointer;padding:0.4rem;font-size:0.75rem">
                                        ✕ Batal ganti foto
                                    </button>
                                </div>
                            </div>
                        </div>
                        @error('foto') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Info --}}
                <div class="fcard">
                    <div class="fcard-head">
                        <div class="fch-icon">🏷</div>
                        <span class="fch-title">Informasi Alat</span>
                    </div>
                    <div class="fcard-body">
                        <div class="form-row" style="margin-bottom:1rem">
                            <div class="form-group" style="margin:0">
                                <label class="form-label">Kode Alat <span style="color:var(--danger)">*</span></label>
                                <input type="text" name="kode"
                                       value="{{ old('kode', $alat->kode) }}"
                                       class="form-input {{ $errors->has('kode') ? 'border-danger' : '' }}">
                                @error('kode') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="form-group" style="margin:0">
                                <label class="form-label">Merk / Brand</label>
                                <input type="text" name="merk" value="{{ old('merk', $alat->merk) }}"
                                       class="form-input" placeholder="cth: Philips, Makita...">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nama Alat <span style="color:var(--danger)">*</span></label>
                            <input type="text" name="nama" value="{{ old('nama', $alat->nama) }}"
                                   class="form-input {{ $errors->has('nama') ? 'border-danger' : '' }}">
                            @error('nama') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Kategori <span style="color:var(--danger)">*</span></label>
                            <select name="kategori_id"
                                    class="form-select {{ $errors->has('kategori_id') ? 'border-danger' : '' }}">
                                @foreach($kategoris as $kat)
                                    <option value="{{ $kat->id }}"
                                            {{ old('kategori_id', $alat->kategori_id) == $kat->id ? 'selected' : '' }}>
                                        {{ $kat->ikon }} {{ $kat->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" style="margin:0">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-textarea" rows="3">{{ old('deskripsi', $alat->deskripsi) }}</textarea>
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
                                       value="{{ old('stok_total', $alat->stok_total) }}" min="1"
                                       class="form-input {{ $errors->has('stok_total') ? 'border-danger' : '' }}"
                                       oninput="validateStok()">
                                @error('stok_total') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="form-group" style="margin:0">
                                <label class="form-label">Stok Tersedia <span style="color:var(--danger)">*</span></label>
                                <input type="number" name="stok_tersedia"
                                       value="{{ old('stok_tersedia', $alat->stok_tersedia) }}" min="0"
                                       class="form-input {{ $errors->has('stok_tersedia') ? 'border-danger' : '' }}"
                                       id="stokTersedia" oninput="validateStok()">
                                <p class="form-hint" id="stokHint">
                                    {{ $alat->peminjaman_aktif_count ?? 0 }} unit sedang dipinjam.
                                </p>
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
                                <div class="price-wrap">
                                    <span class="price-pfx">Rp</span>
                                    <input type="number" name="harga_sewa_per_hari"
                                           value="{{ old('harga_sewa_per_hari', $alat->harga_sewa_per_hari) }}"
                                           min="0" step="500"
                                           class="form-input {{ $errors->has('harga_sewa_per_hari') ? 'border-danger' : '' }}">
                                </div>
                                @error('harga_sewa_per_hari') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="form-group" style="margin:0">
                                <label class="form-label">Denda Keterlambatan / Hari</label>
                                <div class="price-wrap">
                                    <span class="price-pfx">Rp</span>
                                    <input type="number" name="denda_per_hari"
                                           value="{{ old('denda_per_hari', $alat->denda_per_hari) }}"
                                           min="0" step="500" class="form-input">
                                </div>
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
                                @foreach(['baik'=>['✅','Baik'],'rusak_ringan'=>['⚠️','Rusak Ringan'],'rusak_berat'=>['❌','Rusak Berat'],'perbaikan'=>['🔧','Perbaikan']] as $val => [$em, $lbl])
                                    <div class="kondisi-opt">
                                        <input type="radio" name="kondisi" id="k_{{ $val }}"
                                               value="{{ $val }}"
                                               {{ old('kondisi', $alat->kondisi) == $val ? 'checked' : '' }}>
                                        <label for="k_{{ $val }}" class="kondisi-lbl">
                                            <span class="kondisi-em">{{ $em }}</span>
                                            <span class="kondisi-txt">{{ $lbl }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div style="padding:1rem;background:rgba(255,255,255,0.03);border-radius:7px;border:1px solid rgba(255,255,255,0.07)">
                            <div style="display:flex;align-items:center;justify-content:space-between">
                                <div>
                                    <div style="font-size:0.85rem;font-weight:600;color:var(--silver)">Status Alat</div>
                                    <div style="font-size:0.72rem;color:var(--mist);margin-top:0.1rem">
                                        Saat ini: <strong>{{ ucfirst($alat->status) }}</strong>
                                    </div>
                                </div>
                                <label class="ios-toggle">
                                    <input type="checkbox" name="status" value="aktif"
                                           {{ old('status', $alat->status) == 'aktif' ? 'checked' : '' }}>
                                    <span class="ios-slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── SIDEBAR ───────────────────────────── --}}
            <div>
                {{-- Current data snapshot --}}
                <div class="fcard" style="margin-bottom:1rem;padding:1.2rem">
                    <div style="font-size:0.68rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:var(--slate);margin-bottom:0.75rem">Data Saat Ini</div>
                    <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.9rem">
                        <div style="width:42px;height:42px;border-radius:8px;overflow:hidden;background:rgba(37,99,235,0.1);flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:1rem">
                            @if($alat->foto)<img src="{{ asset('storage/'.$alat->foto) }}" style="width:100%;height:100%;object-fit:cover">@else 🔧 @endif
                        </div>
                        <div>
                            <div style="font-size:0.88rem;font-weight:700;color:var(--cream)">{{ $alat->nama }}</div>
                            <div style="font-size:0.7rem;color:var(--mist)">{{ $alat->kode }} · {{ $alat->merk ?? '' }}</div>
                        </div>
                    </div>
                    <div style="display:flex;gap:0.4rem;flex-wrap:wrap">
                        @php $kondisiBadge = ['baik'=>'badge-green','rusak_ringan'=>'badge-amber','rusak_berat'=>'badge-red','perbaikan'=>'badge-blue']; @endphp
                        <span class="badge {{ $kondisiBadge[$alat->kondisi] }}">{{ str_replace('_',' ',ucfirst($alat->kondisi)) }}</span>
                        <span class="badge {{ $alat->status==='aktif'?'badge-green':'badge-slate' }}">{{ ucfirst($alat->status) }}</span>
                        <span class="badge badge-slate">{{ $alat->stok_tersedia }}/{{ $alat->stok_total }} unit</span>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="fcard" style="padding:1.2rem;margin-bottom:1rem">
                    <button type="submit" class="btn btn-primary"
                            style="width:100%;justify-content:center;padding:0.7rem;margin-bottom:0.5rem">
                        ✓ Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.alats.show', $alat) }}"
                       class="btn btn-ghost" style="width:100%;justify-content:center">
                        Batal
                    </a>
                </div>

                {{-- Danger zone --}}
                @if($alat->peminjamans()->whereIn('status',['disetujui','dipinjam'])->count() == 0)
                    <div class="dz">
                        <div class="dz-title">⚠ Zona Berbahaya</div>
                        <div class="dz-desc">Hapus alat ini dari sistem. Data akan di-soft-delete dan tidak muncul di daftar.</div>
                        <form method="POST" action="{{ route('admin.alats.destroy', $alat) }}"
                              onsubmit="return confirm('Hapus alat {{ $alat->nama }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center">
                                🗑 Hapus Alat
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </form>

    <script>
        // Photo management
        function showNewUpload() {
            document.getElementById('existingPhotoWrap').style.display = 'none';
            document.getElementById('newUploadWrap').style.display = 'block';
            document.getElementById('fotoPill') && (document.getElementById('fotoPill').style.display = 'inline-flex');
        }
        function confirmDeletePhoto() {
            if (confirm('Hapus foto ini?')) {
                document.getElementById('hapusFotoInput').value = '1';
                document.getElementById('existingImg').style.opacity = '0.3';
                document.querySelector('.existing-photo-overlay').style.background = 'rgba(239,68,68,0.4)';
                document.getElementById('fotoPill') && (document.getElementById('fotoPill').style.display = 'inline-flex');
            }
        }
        function previewNewPhoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('newPhotoPlaceholder').style.display = 'none';
                    const prev = document.getElementById('newPhotoPreview');
                    prev.style.display = 'block';
                    document.getElementById('newPreviewImg').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        function cancelNewPhoto() {
            document.getElementById('newFotoInput').value = '';
            document.getElementById('newPhotoPlaceholder').style.display = 'block';
            document.getElementById('newPhotoPreview').style.display = 'none';
            if ({{ $alat->foto ? 'true' : 'false' }}) {
                document.getElementById('existingPhotoWrap').style.display = 'block';
                document.getElementById('newUploadWrap').style.display = 'none';
            }
        }

        // Stok validation
        function validateStok() {
            const total = parseInt(document.getElementById('stokTotal').value) || 0;
            const tersedia = document.getElementById('stokTersedia');
            if (parseInt(tersedia.value) > total) tersedia.value = total;
        }

        // Handle status checkbox → value
        document.getElementById('editAlatForm').addEventListener('submit', function() {
            const cb = this.querySelector('input[name="status"][type="checkbox"]');
            if (!cb.checked) {
                cb.value = 'nonaktif'; cb.checked = true;
            }
        });

        // Warn on unsaved changes
        let changed = false;
        document.getElementById('editAlatForm').addEventListener('input', () => changed = true);
        document.getElementById('editAlatForm').addEventListener('submit', () => changed = false);
        window.addEventListener('beforeunload', e => { if (changed) { e.preventDefault(); e.returnValue = ''; } });
    </script>

</x-admin-layout>