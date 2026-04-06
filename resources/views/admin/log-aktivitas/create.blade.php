<x-admin-layout title="Catat Log Manual" breadcrumb="Catat Log">

    <x-slot name="header">
        <div>
            <h1 class="page-heading">Catat Log Manual</h1>
            <p class="page-sub">Tambahkan entri log aktivitas secara manual oleh admin.</p>
        </div>
        <a href="{{ route('admin.log-aktivitas.index') }}" class="btn btn-ghost">← Kembali</a>
    </x-slot>

    <style>
        .create-layout { display:grid;grid-template-columns:1fr 320px;gap:1.5rem;align-items:start; }
        .fcard { background:var(--ink-80);border:1px solid rgba(255,255,255,0.07);border-radius:8px;margin-bottom:1.2rem;overflow:hidden; }
        .fcard-head { padding:1.1rem 1.4rem;border-bottom:1px solid rgba(255,255,255,0.07);display:flex;align-items:center;gap:0.7rem; }
        .fch-icon { width:32px;height:32px;border-radius:7px;background:rgba(37,99,235,0.15);color:var(--accent-l);display:flex;align-items:center;justify-content:center; }
        .fch-title { font-size:0.88rem;font-weight:700;color:var(--cream); }
        .fcard-body { padding:1.4rem; }

        /* Aksi cards */
        .aksi-grid { display:grid;grid-template-columns:repeat(3,1fr);gap:0.5rem; }
        .aksi-opt { position:relative; }
        .aksi-opt input { position:absolute;opacity:0;width:0;height:0; }
        .aksi-lbl {
            display:block;padding:0.65rem 0.7rem;border-radius:7px;cursor:pointer;
            font-size:0.72rem;font-weight:700;font-family:monospace;
            background:rgba(255,255,255,0.04);border:1.5px solid rgba(255,255,255,0.08);
            transition:all 0.15s;text-align:center;
        }
        .aksi-lbl:hover { border-color:rgba(255,255,255,0.2); }
        .aksi-opt input:checked + .aksi-lbl { border-color:var(--accent);background:rgba(37,99,235,0.12);color:var(--accent-l); }

        /* User select */
        .user-opt {
            display:flex;align-items:center;gap:0.7rem;padding:0.75rem;
            border-radius:7px;cursor:pointer;margin-bottom:0.35rem;
            background:rgba(255,255,255,0.03);border:1.5px solid rgba(255,255,255,0.07);transition:all 0.15s;
        }
        .user-opt:hover { border-color:rgba(37,99,235,0.3); }
        .user-opt.selected { border-color:var(--accent);background:rgba(37,99,235,0.1); }
        .user-opt input { display:none; }
        .user-av { width:30px;height:30px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:0.68rem;font-weight:800;color:white;flex-shrink:0; }
        .role-admin    { background:linear-gradient(135deg,#7C3AED,#4F46E5); }
        .role-petugas  { background:linear-gradient(135deg,#0D9488,#0891B2); }
        .role-peminjam { background:linear-gradient(135deg,#10B981,#06B6D4); }
        .uo-check { width:16px;height:16px;border-radius:50%;border:2px solid rgba(255,255,255,0.2);margin-left:auto;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:0.6rem;transition:all 0.15s; }
        .user-opt.selected .uo-check { background:var(--accent);border-color:var(--accent);color:white; }

        /* JSON editor */
        .json-editor {
            background:rgba(0,0,0,0.25);border:1px solid rgba(255,255,255,0.1);
            border-radius:6px;padding:0.85rem;width:100%;box-sizing:border-box;
            font-family:monospace;font-size:0.8rem;color:#34D399;line-height:1.6;
            outline:none;resize:vertical;transition:border-color 0.2s;
        }
        .json-editor:focus { border-color:rgba(37,99,235,0.4); }
        .json-error { color:var(--danger);font-size:0.72rem;margin-top:0.3rem; }
        .json-valid { color:var(--jade);font-size:0.72rem;margin-top:0.3rem; }

        /* Info box */
        .info-box { background:rgba(212,168,67,0.07);border:1px solid rgba(212,168,67,0.2);border-radius:7px;padding:1rem;font-size:0.75rem;color:var(--silver);line-height:1.8; }

        @media(max-width:1000px) { .create-layout { grid-template-columns:1fr; } .aksi-grid { grid-template-columns:repeat(2,1fr); } }
    </style>

    <form method="POST" action="{{ route('admin.log-aktivitas.store') }}" id="createLogForm">
        @csrf

        <div class="create-layout">
            {{-- MAIN --}}
            <div>

                {{-- Aksi --}}
                <div class="fcard">
                    <div class="fcard-head">
                        <div class="fch-icon">⚡</div>
                        <span class="fch-title">Pilih Aksi</span>
                    </div>
                    <div class="fcard-body">
                        <input type="hidden" name="aksi" id="aksiInput" value="{{ old('aksi') }}">
                        <div class="aksi-grid">
                            @foreach($aksiOptions as $val => $label)
                                <div class="aksi-opt">
                                    <div class="aksi-lbl {{ old('aksi')==$val ? 'selected':'' }}"
                                         onclick="selectAksi('{{ $val }}', this)">
                                        {{ $val }}
                                        <div style="font-size:0.62rem;font-weight:400;color:var(--mist);margin-top:0.15rem;font-family:var(--font-ui)">{{ $label }}</div>
                                    </div>
                                </div>
                            @endforeach
                            {{-- Custom aksi option --}}
                            <div class="aksi-opt">
                                <div class="aksi-lbl" id="customAksiBtn" onclick="toggleCustomAksi()">
                                    custom…
                                    <div style="font-size:0.62rem;font-weight:400;color:var(--mist);margin-top:0.15rem;font-family:var(--font-ui)">Aksi Kustom</div>
                                </div>
                            </div>
                        </div>
                        <div id="customAksiWrap" style="margin-top:0.75rem;display:none">
                            <input type="text" id="customAksiInput" class="form-input"
                                   placeholder="Ketik aksi kustom..."
                                   oninput="document.getElementById('aksiInput').value=this.value">
                        </div>
                        @error('aksi') <p class="form-error" style="margin-top:0.5rem">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Modul & Deskripsi --}}
                <div class="fcard">
                    <div class="fcard-head">
                        <div class="fch-icon">📝</div>
                        <span class="fch-title">Detail Log</span>
                    </div>
                    <div class="fcard-body">
                        <div class="form-row" style="margin-bottom:1rem">
                            <div class="form-group" style="margin:0">
                                <label class="form-label">Modul <span style="color:var(--danger)">*</span></label>
                                <select name="modul" class="form-select {{ $errors->has('modul') ? 'border-danger':'' }}">
                                    <option value="">— Pilih modul —</option>
                                    @foreach(['User','Alat','Kategori','Peminjaman','Pengembalian','Log','Laporan','Sistem'] as $m)
                                        <option value="{{ $m }}" {{ old('modul')==$m ? 'selected':'' }}>{{ $m }}</option>
                                    @endforeach
                                </select>
                                @error('modul') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="form-group" style="margin:0">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-textarea" rows="3"
                                      placeholder="Deskripsi lengkap aksi yang terjadi...">{{ old('deskripsi') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- User --}}
                <div class="fcard">
                    <div class="fcard-head">
                        <div class="fch-icon">👤</div>
                        <span class="fch-title">Atas Nama User (opsional)</span>
                    </div>
                    <div class="fcard-body">
                        <input type="hidden" name="user_id" id="userIdInput" value="{{ old('user_id') }}">
                        <p style="font-size:0.75rem;color:var(--mist);margin-bottom:0.9rem">
                            Kosongkan untuk menggunakan akun Anda sendiri sebagai pelaku aksi.
                        </p>
                        {{-- None option --}}
                        <div class="user-opt {{ !old('user_id') ? 'selected':'' }}" onclick="selectUser('', this)">
                            <div style="width:30px;height:30px;border-radius:7px;background:rgba(100,116,139,0.2);display:flex;align-items:center;justify-content:center;font-size:0.9rem;flex-shrink:0">⚙</div>
                            <div>
                                <div style="font-size:0.8rem;font-weight:700;color:var(--silver)">Gunakan akun saya</div>
                                <div style="font-size:0.68rem;color:var(--slate)">{{ auth()->user()->name }}</div>
                            </div>
                            <div class="uo-check" style="{{ !old('user_id') ? 'background:var(--accent);border-color:var(--accent);color:white' : '' }}">✓</div>
                        </div>
                        @foreach($users as $u)
                            <div class="user-opt {{ old('user_id')==$u->id ? 'selected':'' }}"
                                 onclick="selectUser('{{ $u->id }}', this)">
                                <div class="user-av role-{{ $u->role }}">{{ strtoupper(substr($u->name,0,2)) }}</div>
                                <div>
                                    <div style="font-size:0.8rem;font-weight:700;color:var(--silver)">{{ $u->name }}</div>
                                    <div style="font-size:0.68rem;color:var(--slate)">{{ $u->email }}</div>
                                </div>
                                <span class="badge badge-{{ $u->role==='admin'?'red':($u->role==='petugas'?'blue':'green') }}" style="font-size:0.6rem;margin-left:auto">{{ ucfirst($u->role) }}</span>
                                <div class="uo-check" style="{{ old('user_id')==$u->id ? 'background:var(--accent);border-color:var(--accent);color:white':'' }}">{{ old('user_id')==$u->id ? '✓':'' }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Data JSON --}}
                <div class="fcard">
                    <div class="fcard-head">
                        <div class="fch-icon" style="font-size:0.75rem">{ }</div>
                        <span class="fch-title">Data Tambahan (JSON)</span>
                    </div>
                    <div class="fcard-body">
                        <div class="form-group" style="margin:0">
                            <label class="form-label">Data Baru (JSON, opsional)</label>
                            <textarea name="data_baru" id="jsonInput" class="json-editor" rows="6"
                                      placeholder='{ "key": "value", "angka": 123 }'
                                      oninput="validateJson(this)">{{ old('data_baru') }}</textarea>
                            <div id="jsonStatus"></div>
                            <p class="form-hint">Format JSON valid. Gunakan untuk menyimpan data konteks aksi.</p>
                            @error('data_baru') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        {{-- Quick templates --}}
                        <div style="margin-top:0.75rem;display:flex;gap:0.4rem;flex-wrap:wrap">
                            <span style="font-size:0.7rem;color:var(--slate)">Template:</span>
                            @foreach([
                                'User'  => '{"user_id":1,"nama":"John Doe","role":"peminjam"}',
                                'Alat'  => '{"alat_id":1,"nama":"Vacuum Cleaner","stok":5}',
                                'Pinjam'=> '{"nomor":"PNJ-20250101-001","total":150000}',
                            ] as $label => $json)
                                <button type="button" onclick="setTemplate({{ Js::from($json) }})"
                                        class="btn btn-ghost" style="font-size:0.68rem;padding:0.2rem 0.6rem">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            {{-- SIDEBAR --}}
            <div>
                {{-- Preview --}}
                <div class="fcard" style="margin-bottom:1rem;padding:1.2rem">
                    <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--slate);margin-bottom:0.75rem">Pratinjau</div>
                    <div style="background:rgba(255,255,255,0.03);border-radius:7px;padding:0.85rem">
                        <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.5rem">
                            <span id="previewAksi" class="badge badge-slate" style="font-size:0.65rem;font-family:monospace">—</span>
                            <span id="previewModul" class="modul-chip" style="font-size:0.65rem">—</span>
                        </div>
                        <div id="previewDesc" style="font-size:0.78rem;color:var(--mist);line-height:1.4">Isi form untuk melihat pratinjau...</div>
                        <div id="previewUser" style="font-size:0.7rem;color:var(--slate);margin-top:0.4rem"></div>
                        <div style="font-size:0.68rem;color:var(--slate);margin-top:0.3rem">{{ now()->format('d M Y, H:i:s') }}</div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="fcard" style="padding:1.2rem;margin-bottom:1rem">
                    <button type="submit" class="btn btn-primary"
                            style="width:100%;justify-content:center;padding:0.7rem;margin-bottom:0.5rem">
                        ＋ Catat Log
                    </button>
                    <a href="{{ route('admin.log-aktivitas.index') }}"
                       class="btn btn-ghost" style="width:100%;justify-content:center">Batal</a>
                </div>

                {{-- Info --}}
                <div class="info-box">
                    <div style="font-size:0.75rem;font-weight:700;color:#FCD34D;margin-bottom:0.5rem">💡 Kapan Perlu Log Manual?</div>
                    • Aksi offline yang tidak tercatat sistem<br>
                    • Koreksi data administratif<br>
                    • Catatan kejadian khusus<br>
                    • Audit eksternal atau inspeksi
                </div>
            </div>
        </div>
    </form>

    <script>
        // Aksi selection
        function selectAksi(val, el) {
            document.getElementById('aksiInput').value = val;
            document.querySelectorAll('.aksi-lbl').forEach(l => l.classList.remove('selected'));
            el.classList.add('selected');
            document.getElementById('customAksiWrap').style.display = 'none';
            updatePreview();
        }
        function toggleCustomAksi() {
            const wrap = document.getElementById('customAksiWrap');
            wrap.style.display = wrap.style.display === 'none' ? 'block' : 'none';
            document.querySelectorAll('.aksi-lbl').forEach(l => l.classList.remove('selected'));
            document.getElementById('customAksiBtn').classList.add('selected');
        }

        // User selection
        let selectedUserName = '{{ auth()->user()->name }}';
        function selectUser(id, el) {
            document.getElementById('userIdInput').value = id;
            document.querySelectorAll('.user-opt').forEach(o => {
                o.classList.remove('selected');
                const check = o.querySelector('.uo-check');
                check.textContent = '';
                check.style.background = '';
                check.style.borderColor = '';
                check.style.color = '';
            });
            el.classList.add('selected');
            const check = el.querySelector('.uo-check');
            check.textContent = '✓';
            check.style.background = 'var(--accent)';
            check.style.borderColor = 'var(--accent)';
            check.style.color = 'white';
            selectedUserName = el.querySelector('[style*="font-weight:700"]')?.textContent?.trim() || '';
            updatePreview();
        }

        // JSON validation
        function validateJson(ta) {
            const status = document.getElementById('jsonStatus');
            const val = ta.value.trim();
            if (!val) { status.innerHTML = ''; return; }
            try {
                JSON.parse(val);
                status.innerHTML = '<div class="json-valid">✓ JSON valid</div>';
                ta.style.borderColor = 'rgba(16,185,129,0.4)';
            } catch (e) {
                status.innerHTML = `<div class="json-error">✕ JSON tidak valid: ${e.message}</div>`;
                ta.style.borderColor = 'rgba(239,68,68,0.4)';
            }
        }

        function setTemplate(json) {
            const ta = document.getElementById('jsonInput');
            ta.value = JSON.stringify(JSON.parse(json), null, 2);
            validateJson(ta);
        }

        // Live preview
        function updatePreview() {
            const aksi  = document.getElementById('aksiInput').value;
            const modul = document.querySelector('[name="modul"]').value;
            const desc  = document.querySelector('[name="deskripsi"]').value;

            document.getElementById('previewAksi').textContent  = aksi  || '—';
            document.getElementById('previewModul').textContent = modul || '—';
            document.getElementById('previewDesc').textContent  = desc  || 'Belum ada deskripsi';
            document.getElementById('previewUser').textContent  = selectedUserName ? '👤 ' + selectedUserName : '';
        }

        document.querySelector('[name="modul"]').addEventListener('change', updatePreview);
        document.querySelector('[name="deskripsi"]').addEventListener('input', updatePreview);
    </script>

</x-admin-layout>