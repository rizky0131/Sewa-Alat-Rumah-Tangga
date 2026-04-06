<x-admin-layout title="Tambah Kategori" breadcrumb="Tambah Kategori">

    <x-slot name="header">
        <div>
            <h1 class="page-heading">Tambah Kategori</h1>
            <p class="page-sub">Buat kategori baru untuk pengelompokan alat.</p>
        </div>
        <a href="{{ route('admin.kategoris.index') }}" class="btn btn-ghost">← Kembali</a>
    </x-slot>

    <style>
        .form-layout { display: grid; grid-template-columns: 1fr 320px; gap: 1.5rem; align-items: start; }
        .fcard { background: var(--ink-80); border: 1px solid rgba(255,255,255,0.07); border-radius: 8px; margin-bottom: 1.2rem; }
        .fcard-head {
            padding: 1.1rem 1.4rem; border-bottom: 1px solid rgba(255,255,255,0.07);
            display: flex; align-items: center; gap: 0.7rem;
        }
        .fch-icon { width: 32px; height: 32px; border-radius: 7px; background: rgba(37,99,235,0.15); color: var(--accent-l); display: flex; align-items: center; justify-content: center; font-size: 0.95rem; }
        .fch-title { font-size: 0.88rem; font-weight: 700; color: var(--cream); }
        .fcard-body { padding: 1.4rem; }

        /* Icon picker */
        .icon-picker { display: grid; grid-template-columns: repeat(8,1fr); gap: 0.4rem; margin-top: 0.5rem; }
        .icon-option { position: relative; }
        .icon-option input { position: absolute; opacity: 0; width: 0; height: 0; }
        .icon-btn-label {
            display: flex; flex-direction: column; align-items: center; gap: 0.2rem;
            padding: 0.55rem 0.3rem; border-radius: 7px; cursor: pointer;
            background: rgba(255,255,255,0.04);
            border: 1.5px solid rgba(255,255,255,0.07);
            transition: all 0.15s; text-align: center;
        }
        .icon-btn-label:hover { border-color: rgba(255,255,255,0.2); background: rgba(255,255,255,0.08); }
        .icon-btn-label .icon-em { font-size: 1.3rem; }
        .icon-btn-label .icon-lbl { font-size: 0.52rem; color: var(--mist); line-height: 1.2; }
        .icon-option input:checked + .icon-btn-label {
            border-color: var(--accent); background: rgba(37,99,235,0.15);
        }

        /* Custom icon input */
        .custom-icon-wrap { display: flex; gap: 0.5rem; margin-top: 0.75rem; align-items: center; }
        .icon-preview {
            width: 44px; height: 44px; border-radius: 10px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center; font-size: 1.4rem;
            background: rgba(37,99,235,0.1); border: 1px solid rgba(37,99,235,0.25);
        }

        /* Slug input with generate btn */
        .slug-wrap { display: flex; gap: 0.4rem; }
        .slug-wrap .form-input { flex: 1; font-family: monospace; }
        .slug-prefix {
            display: flex; align-items: center; padding: 0 0.8rem;
            background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1);
            border-right: none; border-radius: 6px 0 0 6px;
            font-size: 0.78rem; color: var(--mist); white-space: nowrap;
        }
        .slug-wrap .form-input { border-radius: 0 6px 6px 0; }

        /* Toggle */
        .toggle-row { display: flex; align-items: center; justify-content: space-between; }
        .toggle-label { font-size: 0.85rem; font-weight: 600; color: var(--silver); }
        .toggle-hint  { font-size: 0.72rem; color: var(--mist); margin-top: 0.1rem; }
        .ios-toggle { position: relative; width: 44px; height: 26px; flex-shrink: 0; }
        .ios-toggle input { opacity: 0; width: 0; height: 0; }
        .ios-slider {
            position: absolute; cursor: pointer; inset: 0;
            background: var(--slate); border-radius: 26px;
            transition: background 0.2s;
        }
        .ios-slider::before {
            content: ''; position: absolute;
            height: 20px; width: 20px; left: 3px; bottom: 3px;
            background: white; border-radius: 50%; transition: transform 0.2s;
        }
        .ios-toggle input:checked + .ios-slider { background: var(--jade); }
        .ios-toggle input:checked + .ios-slider::before { transform: translateX(18px); }

        /* Live preview card */
        .preview-card {
            background: var(--ink-60); border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px; overflow: hidden;
        }
        .preview-card-inner { padding: 1.3rem; }
        .preview-icon-bubble {
            width: 52px; height: 52px; border-radius: 12px; margin-bottom: 0.9rem;
            display: flex; align-items: center; justify-content: center; font-size: 1.6rem;
            background: linear-gradient(135deg,rgba(37,99,235,0.2),rgba(37,99,235,0.06));
            border: 1px solid rgba(37,99,235,0.2);
        }
        .preview-name { font-size: 1rem; font-weight: 700; color: var(--cream); }
        .preview-slug { font-size: 0.7rem; font-family: monospace; color: var(--mist); margin-top: 0.2rem; }
        .preview-desc { font-size: 0.75rem; color: var(--mist); margin-top: 0.35rem; line-height: 1.4; }
        .preview-footer {
            padding: 0.75rem 1.3rem;
            border-top: 1px solid rgba(255,255,255,0.08);
            display: flex; gap: 0.4rem;
        }
        .preview-meta { flex: 1; text-align: center; background: rgba(255,255,255,0.04); border-radius: 5px; padding: 0.4rem; }
        .preview-meta-val { font-family: var(--font-display); font-size: 1.1rem; font-weight: 700; color: var(--cream); }
        .preview-meta-label { font-size: 0.6rem; color: var(--mist); }

        @media(max-width:900px) { .form-layout { grid-template-columns: 1fr; } .icon-picker { grid-template-columns: repeat(5,1fr); } }
    </style>

    <form method="POST" action="{{ route('admin.kategoris.store') }}" id="katForm">
        @csrf
        <div class="form-layout">

            {{-- ── MAIN ──────────────────────────────── --}}
            <div>

                {{-- Info dasar --}}
                <div class="fcard">
                    <div class="fcard-head">
                        <div class="fch-icon">🏷</div>
                        <span class="fch-title">Informasi Kategori</span>
                    </div>
                    <div class="fcard-body">
                        <div class="form-group">
                            <label class="form-label">Nama Kategori <span style="color:var(--danger)">*</span></label>
                            <input type="text" name="nama" id="namaInput"
                                   value="{{ old('nama') }}"
                                   class="form-input {{ $errors->has('nama') ? 'border-danger' : '' }}"
                                   placeholder="cth: Dapur, Kebersihan, Perkakas..."
                                   oninput="onNamaChange(this.value)" autofocus>
                            @error('nama') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Slug
                                <span style="font-weight:400;color:var(--mist)">(opsional — otomatis dari nama)</span>
                            </label>
                            <div class="slug-wrap">
                                <span class="slug-prefix">kategoris/</span>
                                <input type="text" name="slug" id="slugInput"
                                       value="{{ old('slug') }}"
                                       class="form-input {{ $errors->has('slug') ? 'border-danger' : '' }}"
                                       placeholder="cth: dapur"
                                       oninput="slugManual = true">
                            </div>
                            @error('slug') <p class="form-error">{{ $message }}</p> @enderror
                            <p class="form-hint">Hanya huruf kecil, angka, dan tanda hubung (-).</p>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Deskripsi <span style="font-weight:400;color:var(--mist)">(opsional)</span></label>
                            <textarea name="deskripsi" id="descInput"
                                      class="form-textarea {{ $errors->has('deskripsi') ? 'border-danger' : '' }}"
                                      placeholder="Jelaskan jenis alat yang termasuk dalam kategori ini..."
                                      oninput="document.getElementById('previewDesc').textContent = this.value || 'Deskripsi kategori akan tampil di sini.'">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        {{-- Status toggle --}}
                        <div style="padding: 1rem; background: rgba(255,255,255,0.03); border-radius: 7px; border: 1px solid rgba(255,255,255,0.07)">
                            <div class="toggle-row">
                                <div>
                                    <div class="toggle-label">Status Aktif</div>
                                    <div class="toggle-hint">Kategori nonaktif tidak akan tampil di halaman peminjam.</div>
                                </div>
                                <label class="ios-toggle">
                                    <input type="checkbox" name="is_aktif" value="1"
                                           {{ old('is_aktif', '1') == '1' ? 'checked' : '' }}>
                                    <span class="ios-slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Icon picker --}}
                <div class="fcard">
                    <div class="fcard-head">
                        <div class="fch-icon">🎨</div>
                        <span class="fch-title">Pilih Ikon</span>
                    </div>
                    <div class="fcard-body">
                        <div class="icon-picker" id="iconPicker">
                            @foreach($iconOptions as $emoji => $label)
                                <div class="icon-option">
                                    <input type="radio" name="ikon" id="icon_{{ $loop->index }}"
                                           value="{{ $emoji }}"
                                           {{ old('ikon') == $emoji ? 'checked' : '' }}
                                           onchange="updatePreviewIcon('{{ $emoji }}')">
                                    <label for="icon_{{ $loop->index }}" class="icon-btn-label">
                                        <span class="icon-em">{{ $emoji }}</span>
                                        <span class="icon-lbl">{{ $label }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <div class="custom-icon-wrap" style="margin-top:1rem">
                            <div class="icon-preview" id="customPreview">🏷</div>
                            <div style="flex:1">
                                <label class="form-label">Atau ketik emoji / kode ikon sendiri</label>
                                <input type="text" id="customIconInput"
                                       class="form-input" placeholder="cth: 🏠 atau fa-wrench"
                                       style="font-size:1.1rem"
                                       oninput="setCustomIcon(this.value)">
                            </div>
                        </div>
                        @error('ikon') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

            </div>

            {{-- ── SIDEBAR ───────────────────────────── --}}
            <div>

                {{-- Live preview --}}
                <div class="fcard" style="margin-bottom:1rem">
                    <div class="fcard-head">
                        <div class="fch-icon" style="font-size:0.8rem">👁</div>
                        <span class="fch-title">Pratinjau</span>
                    </div>
                    <div style="padding:1rem">
                        <div class="preview-card">
                            <div class="preview-card-inner">
                                <div class="preview-icon-bubble" id="previewIconBubble">🏷</div>
                                <div class="preview-name"  id="previewName">Nama Kategori</div>
                                <div class="preview-slug"  id="previewSlug">slug-kategori</div>
                                <div class="preview-desc"  id="previewDesc">Deskripsi kategori akan tampil di sini.</div>
                            </div>
                            <div class="preview-footer">
                                <div class="preview-meta"><div class="preview-meta-val">0</div><div class="preview-meta-label">Alat</div></div>
                                <div class="preview-meta"><div class="preview-meta-val">0</div><div class="preview-meta-label">Aktif</div></div>
                                <div class="preview-meta"><div class="preview-meta-val">0</div><div class="preview-meta-label">Stok</div></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="fcard" style="padding:1.2rem;margin-bottom:1rem">
                    <button type="submit" class="btn btn-primary"
                            style="width:100%;justify-content:center;padding:0.7rem;margin-bottom:0.5rem">
                        ＋ Simpan Kategori
                    </button>
                    <a href="{{ route('admin.kategoris.index') }}"
                       class="btn btn-ghost" style="width:100%;justify-content:center">
                        Batal
                    </a>
                </div>

                {{-- Tips --}}
                <div style="background:rgba(37,99,235,0.06);border:1px solid rgba(37,99,235,0.18);border-radius:8px;padding:1.1rem">
                    <div style="font-size:0.75rem;font-weight:700;color:var(--accent-l);margin-bottom:0.6rem">💡 Tips</div>
                    <div style="font-size:0.75rem;color:var(--silver);line-height:1.5">
                        • Gunakan nama yang singkat dan deskriptif<br>
                        • Pilih ikon yang merepresentasikan jenis alat<br>
                        • Slug otomatis dibuat dari nama jika dikosongkan<br>
                        • Kategori nonaktif tidak tampil di halaman peminjam
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        let slugManual = false;

        function slugify(str) {
            return str.toLowerCase()
                .replace(/\s+/g, '-')
                .replace(/[^\w\-]+/g, '')
                .replace(/\-\-+/g, '-')
                .replace(/^-+/, '')
                .replace(/-+$/, '');
        }

        function onNamaChange(val) {
            document.getElementById('previewName').textContent = val || 'Nama Kategori';
            if (!slugManual) {
                const slug = slugify(val);
                document.getElementById('slugInput').value = slug;
                document.getElementById('previewSlug').textContent = slug || 'slug-kategori';
            }
        }

        document.getElementById('slugInput').addEventListener('input', function() {
            document.getElementById('previewSlug').textContent = this.value || 'slug-kategori';
        });

        function updatePreviewIcon(emoji) {
            document.getElementById('previewIconBubble').textContent = emoji;
            document.getElementById('customPreview').textContent = emoji;
            document.getElementById('customIconInput').value = '';
        }

        function setCustomIcon(val) {
            if (val.trim()) {
                document.getElementById('previewIconBubble').textContent = val.trim();
                document.getElementById('customPreview').textContent = val.trim();
                // Uncheck all radio icons
                document.querySelectorAll('#iconPicker input[type="radio"]').forEach(r => r.checked = false);
                // Set hidden field — we reuse the ikon name field by injecting hidden
                let hidden = document.getElementById('ikonHidden');
                if (!hidden) {
                    hidden = document.createElement('input');
                    hidden.type = 'hidden'; hidden.name = 'ikon'; hidden.id = 'ikonHidden';
                    document.getElementById('katForm').appendChild(hidden);
                }
                hidden.value = val.trim();
            }
        }

        // Populate preview from old values on page load
        window.addEventListener('DOMContentLoaded', () => {
            const nama = document.getElementById('namaInput').value;
            const desc = document.getElementById('descInput').value;
            const icon = document.querySelector('#iconPicker input:checked');
            if (nama) onNamaChange(nama);
            if (desc) document.getElementById('previewDesc').textContent = desc;
            if (icon) updatePreviewIcon(icon.value);
        });
    </script>

</x-admin-layout>