<x-admin-layout title="Edit Kategori" breadcrumb="Edit Kategori">

    <x-slot name="header">
        <div style="display:flex;align-items:center;gap:1rem">
            <div style="width:44px;height:44px;border-radius:10px;font-size:1.4rem;display:flex;align-items:center;justify-content:center;background:rgba(37,99,235,0.12);border:1px solid rgba(37,99,235,0.2)">
                {{ $kategori->ikon ?? '🏷' }}
            </div>
            <div>
                <h1 class="page-heading" style="font-size:1.4rem">Edit: {{ $kategori->nama }}</h1>
                <p class="page-sub">Perbarui informasi kategori alat.</p>
            </div>
        </div>
        <div style="display:flex;gap:0.5rem">
            <a href="{{ route('admin.kategoris.show', $kategori) }}" class="btn btn-ghost">← Detail</a>
            <a href="{{ route('admin.kategoris.index') }}" class="btn btn-ghost">Semua</a>
        </div>
    </x-slot>

    <style>
        .edit-layout { display: grid; grid-template-columns: 1fr 320px; gap: 1.5rem; align-items: start; }
        .fcard { background: var(--ink-80); border: 1px solid rgba(255,255,255,0.07); border-radius: 8px; margin-bottom: 1.2rem; }
        .fcard-head { padding: 1.1rem 1.4rem; border-bottom: 1px solid rgba(255,255,255,0.07); display: flex; align-items: center; gap: 0.7rem; }
        .fch-icon { width: 32px; height: 32px; border-radius: 7px; background: rgba(37,99,235,0.15); color: var(--accent-l); display: flex; align-items: center; justify-content: center; font-size: 0.95rem; }
        .fch-title { font-size: 0.88rem; font-weight: 700; color: var(--cream); }
        .fcard-body { padding: 1.4rem; }

        /* Icon picker */
        .icon-picker { display: grid; grid-template-columns: repeat(8,1fr); gap: 0.4rem; }
        .icon-option { position: relative; }
        .icon-option input { position: absolute; opacity: 0; width: 0; height: 0; }
        .icon-btn-label {
            display: flex; flex-direction: column; align-items: center; gap: 0.2rem;
            padding: 0.55rem 0.2rem; border-radius: 7px; cursor: pointer;
            background: rgba(255,255,255,0.04); border: 1.5px solid rgba(255,255,255,0.07);
            transition: all 0.15s; text-align: center;
        }
        .icon-btn-label:hover { border-color: rgba(255,255,255,0.2); background: rgba(255,255,255,0.08); }
        .icon-btn-label .icon-em { font-size: 1.3rem; }
        .icon-btn-label .icon-lbl { font-size: 0.52rem; color: var(--mist); line-height: 1.2; }
        .icon-option input:checked + .icon-btn-label { border-color: var(--accent); background: rgba(37,99,235,0.15); }

        .custom-icon-wrap { display: flex; gap: 0.5rem; margin-top: 0.75rem; align-items: center; }
        .icon-preview { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; background: rgba(37,99,235,0.1); border: 1px solid rgba(37,99,235,0.25); flex-shrink: 0; }

        /* Slug */
        .slug-wrap { display: flex; }
        .slug-prefix { display: flex; align-items: center; padding: 0 0.8rem; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); border-right: none; border-radius: 6px 0 0 6px; font-size: 0.78rem; color: var(--mist); white-space: nowrap; }
        .slug-wrap .form-input { flex: 1; border-radius: 0 6px 6px 0; font-family: monospace; }

        /* Toggle */
        .ios-toggle { position: relative; width: 44px; height: 26px; }
        .ios-toggle input { opacity: 0; width: 0; height: 0; }
        .ios-slider { position: absolute; cursor: pointer; inset: 0; background: var(--slate); border-radius: 26px; transition: background 0.2s; }
        .ios-slider::before { content: ''; position: absolute; height: 20px; width: 20px; left: 3px; bottom: 3px; background: white; border-radius: 50%; transition: transform 0.2s; }
        .ios-toggle input:checked + .ios-slider { background: var(--jade); }
        .ios-toggle input:checked + .ios-slider::before { transform: translateX(18px); }

        /* Preview */
        .preview-card { background: var(--ink-60); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; overflow: hidden; }
        .preview-card-inner { padding: 1.2rem; }
        .preview-icon-bubble { width: 52px; height: 52px; border-radius: 12px; margin-bottom: 0.8rem; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; background: linear-gradient(135deg,rgba(37,99,235,0.2),rgba(37,99,235,0.06)); border: 1px solid rgba(37,99,235,0.2); }
        .preview-name { font-size: 1rem; font-weight: 700; color: var(--cream); }
        .preview-slug { font-size: 0.7rem; font-family: monospace; color: var(--mist); margin-top: 0.2rem; }
        .preview-desc { font-size: 0.75rem; color: var(--mist); margin-top: 0.3rem; line-height: 1.4; }

        /* Danger zone */
        .dz { background: rgba(239,68,68,0.06); border: 1px solid rgba(239,68,68,0.18); border-radius: 8px; padding: 1.1rem; }
        .dz-title { font-size: 0.78rem; font-weight: 700; color: #FCA5A5; margin-bottom: 0.4rem; }
        .dz-desc  { font-size: 0.73rem; color: var(--mist); line-height: 1.4; margin-bottom: 0.75rem; }

        @media(max-width:900px) { .edit-layout { grid-template-columns: 1fr; } .icon-picker { grid-template-columns: repeat(5,1fr); } }
    </style>

    <form method="POST" action="{{ route('admin.kategoris.update', $kategori) }}" id="editKatForm">
        @csrf @method('PUT')

        <div class="edit-layout">

            {{-- MAIN --}}
            <div>

                {{-- Info --}}
                <div class="fcard">
                    <div class="fcard-head">
                        <div class="fch-icon">🏷</div>
                        <span class="fch-title">Informasi Kategori</span>
                    </div>
                    <div class="fcard-body">
                        <div class="form-group">
                            <label class="form-label">Nama Kategori <span style="color:var(--danger)">*</span></label>
                            <input type="text" name="nama" id="namaInput"
                                   value="{{ old('nama', $kategori->nama) }}"
                                   class="form-input {{ $errors->has('nama') ? 'border-danger' : '' }}"
                                   oninput="onNamaChange(this.value)">
                            @error('nama') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Slug</label>
                            <div class="slug-wrap">
                                <span class="slug-prefix">kategoris/</span>
                                <input type="text" name="slug" id="slugInput"
                                       value="{{ old('slug', $kategori->slug) }}"
                                       class="form-input {{ $errors->has('slug') ? 'border-danger' : '' }}"
                                       oninput="slugManual = true; document.getElementById('previewSlug').textContent = this.value">
                            </div>
                            @error('slug') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="descInput"
                                      class="form-textarea"
                                      oninput="document.getElementById('previewDesc').textContent = this.value">{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
                        </div>

                        <div style="padding:1rem;background:rgba(255,255,255,0.03);border-radius:7px;border:1px solid rgba(255,255,255,0.07)">
                            <div style="display:flex;align-items:center;justify-content:space-between">
                                <div>
                                    <div style="font-size:0.85rem;font-weight:600;color:var(--silver)">Status Aktif</div>
                                    <div style="font-size:0.72rem;color:var(--mist);margin-top:0.1rem">Kategori nonaktif tidak tampil di halaman peminjam.</div>
                                </div>
                                <label class="ios-toggle">
                                    <input type="checkbox" name="is_aktif" value="1"
                                           {{ old('is_aktif', $kategori->is_aktif ? '1' : '0') == '1' ? 'checked' : '' }}>
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
                        <span class="fch-title">Ikon Kategori</span>
                    </div>
                    <div class="fcard-body">
                        <div class="icon-picker" id="iconPicker">
                            @foreach($iconOptions as $emoji => $label)
                                <div class="icon-option">
                                    <input type="radio" name="ikon" id="icon_{{ $loop->index }}"
                                           value="{{ $emoji }}"
                                           {{ old('ikon', $kategori->ikon) == $emoji ? 'checked' : '' }}
                                           onchange="updatePreviewIcon('{{ $emoji }}')">
                                    <label for="icon_{{ $loop->index }}" class="icon-btn-label">
                                        <span class="icon-em">{{ $emoji }}</span>
                                        <span class="icon-lbl">{{ $label }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <div class="custom-icon-wrap">
                            <div class="icon-preview" id="customPreview">{{ $kategori->ikon ?? '🏷' }}</div>
                            <div style="flex:1">
                                <label class="form-label">Atau ketik emoji sendiri</label>
                                <input type="text" id="customIconInput" class="form-input"
                                       placeholder="cth: 🏠"
                                       style="font-size:1.1rem"
                                       oninput="setCustomIcon(this.value)">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- SIDEBAR --}}
            <div>

                {{-- Preview --}}
                <div class="fcard" style="margin-bottom:1rem">
                    <div class="fcard-head">
                        <div class="fch-icon" style="font-size:0.8rem">👁</div>
                        <span class="fch-title">Pratinjau</span>
                    </div>
                    <div style="padding:1rem">
                        <div class="preview-card">
                            <div class="preview-card-inner">
                                <div class="preview-icon-bubble" id="previewIconBubble">{{ $kategori->ikon ?? '🏷' }}</div>
                                <div class="preview-name" id="previewName">{{ $kategori->nama }}</div>
                                <div class="preview-slug" id="previewSlug">{{ $kategori->slug }}</div>
                                <div class="preview-desc" id="previewDesc">{{ $kategori->deskripsi ?? 'Deskripsi kategori.' }}</div>
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
                    <a href="{{ route('admin.kategoris.show', $kategori) }}"
                       class="btn btn-ghost" style="width:100%;justify-content:center">
                        Batal
                    </a>
                </div>

                {{-- Danger zone --}}
                @if($kategori->alats()->count() === 0)
                    <div class="dz">
                        <div class="dz-title">⚠ Zona Berbahaya</div>
                        <div class="dz-desc">Kategori ini tidak memiliki alat. Anda dapat menghapusnya secara permanen.</div>
                        <form method="POST" action="{{ route('admin.kategoris.destroy', $kategori) }}"
                              onsubmit="return confirm('Hapus kategori {{ $kategori->nama }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center">
                                🗑 Hapus Kategori
                            </button>
                        </form>
                    </div>
                @else
                    <div style="background:rgba(37,99,235,0.06);border:1px solid rgba(37,99,235,0.15);border-radius:8px;padding:1rem">
                        <div style="font-size:0.75rem;font-weight:700;color:var(--accent-l);margin-bottom:0.4rem">ℹ Info</div>
                        <div style="font-size:0.73rem;color:var(--mist);line-height:1.5">
                            Kategori ini memiliki <strong style="color:var(--silver)">{{ $kategori->alats()->count() }} alat</strong>.
                            Pindahkan atau hapus semua alat terlebih dahulu sebelum menghapus kategori.
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </form>

    <script>
        let slugManual = false;

        function slugify(str) {
            return str.toLowerCase().replace(/\s+/g,'-').replace(/[^\w\-]+/g,'').replace(/\-\-+/g,'-').replace(/^-+/,'').replace(/-+$/,'');
        }
        function onNamaChange(val) {
            document.getElementById('previewName').textContent = val || 'Nama Kategori';
            if (!slugManual) {
                const s = slugify(val);
                document.getElementById('slugInput').value = s;
                document.getElementById('previewSlug').textContent = s;
            }
        }
        function updatePreviewIcon(emoji) {
            document.getElementById('previewIconBubble').textContent = emoji;
            document.getElementById('customPreview').textContent = emoji;
            document.getElementById('customIconInput').value = '';
        }
        function setCustomIcon(val) {
            if (val.trim()) {
                document.getElementById('previewIconBubble').textContent = val.trim();
                document.getElementById('customPreview').textContent = val.trim();
                document.querySelectorAll('#iconPicker input').forEach(r => r.checked = false);
                let h = document.getElementById('ikonHidden');
                if (!h) { h = document.createElement('input'); h.type='hidden'; h.name='ikon'; h.id='ikonHidden'; document.getElementById('editKatForm').appendChild(h); }
                h.value = val.trim();
            }
        }

        // Warn on unsaved changes
        let changed = false;
        document.getElementById('editKatForm').addEventListener('input', () => changed = true);
        document.getElementById('editKatForm').addEventListener('submit', () => changed = false);
        window.addEventListener('beforeunload', e => { if (changed) { e.preventDefault(); e.returnValue = ''; } });
    </script>

</x-admin-layout>