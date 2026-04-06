<x-admin-layout title="Edit User" breadcrumb="Edit User">

    <x-slot name="header">
        <div style="display:flex;align-items:center;gap:1rem">
            <div class="show-avatar {{ $user->role }}">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div>
                <h1 class="page-heading" style="font-size:1.4rem">Edit: {{ $user->name }}</h1>
                <p class="page-sub">Perbarui data akun pengguna.</p>
            </div>
        </div>
        <div style="display:flex;gap:0.5rem">
            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-ghost">← Detail</a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">Semua User</a>
        </div>
    </x-slot>

    <style>
        /* ── Avatar ──────────────────────────────────── */
        .show-avatar {
            width: 46px; height: 46px; border-radius: 10px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; font-weight: 700; color: white;
        }
        .show-avatar.admin   { background: linear-gradient(135deg,#C0392B,#E67E22); }
        .show-avatar.petugas { background: linear-gradient(135deg,#2563EB,#7C3AED); }
        .show-avatar.peminjam{ background: linear-gradient(135deg,var(--green),#059669); }

        /* ── Layout ──────────────────────────────────── */
        .edit-layout {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 1.5rem;
            align-items: start;
        }

        /* ── Form card ───────────────────────────────── */
        .form-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            margin-bottom: 1.2rem;
            overflow: hidden;
        }
        .form-card:last-child { margin-bottom: 0; }

        .form-card-header {
            padding: 0.9rem 1.25rem;
            border-bottom: 1px solid var(--border);
            background: var(--surface);
            display: flex; align-items: center; gap: 0.65rem;
        }
        .fch-icon {
            width: 32px; height: 32px; border-radius: var(--radius);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.95rem; flex-shrink: 0;
        }
        .fch-title { font-size: 0.88rem; font-weight: 700; color: var(--text-primary); }

        .form-card-body { padding: 1.25rem; }

        /* Field error */
        .form-error {
            font-size: 0.72rem; color: var(--red);
            margin-top: 0.3rem;
            display: flex; align-items: center; gap: 0.3rem;
        }
        .form-error::before { content: '!'; font-weight: 700; }
        .border-danger {
            border-color: var(--red) !important;
            background: var(--red-bg) !important;
        }
        .border-danger:focus {
            box-shadow: 0 0 0 3px rgba(192,57,43,0.1) !important;
        }

        /* ── Field row ───────────────────────────────── */
        .field-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        /* ── Role selector ───────────────────────────── */
        .role-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.6rem;
        }
        .role-option { position: relative; }
        .role-option input[type="radio"] {
            position: absolute; opacity: 0; width: 0; height: 0;
        }
        .role-label {
            display: flex; flex-direction: column; align-items: center;
            gap: 0.35rem; padding: 0.9rem 0.4rem;
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            cursor: pointer; transition: all 0.15s;
            text-align: center;
        }
        .role-label:hover {
            border-color: var(--border-2);
            background: var(--surface);
        }
        .role-option input:checked + .role-label {
            border-color: var(--green);
            background: var(--green-bg);
        }
        .role-option input[value="admin"]:checked + .role-label {
            border-color: var(--red);
            background: var(--red-bg);
        }
        .role-option input[value="petugas"]:checked + .role-label {
            border-color: var(--blue);
            background: var(--blue-bg);
        }
        .role-option input:checked + .role-label .role-check { opacity: 1; }

        .role-emoji { font-size: 1.35rem; }
        .role-name  { font-size: 0.72rem; font-weight: 700; color: var(--text-secondary); }
        .role-check {
            width: 16px; height: 16px; border-radius: 50%;
            background: var(--green); color: white;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.55rem; opacity: 0; transition: opacity 0.15s;
        }
        .role-option input[value="admin"]:checked + .role-label .role-check { background: var(--red); }
        .role-option input[value="petugas"]:checked + .role-label .role-check { background: var(--blue); }

        /* ── Password collapsible ────────────────────── */
        .pw-toggle-header {
            display: flex; align-items: center; justify-content: space-between;
            cursor: pointer; padding: 0.9rem 1.25rem;
            border-top: 1px solid var(--border);
            transition: background 0.15s;
        }
        .pw-toggle-header:hover { background: var(--surface); }
        .pw-toggle-left { display: flex; align-items: center; gap: 0.65rem; }
        .pw-toggle-caret {
            color: var(--text-muted); font-size: 0.8rem;
            transition: transform 0.2s;
        }
        .pw-toggle-caret.open { transform: rotate(180deg); }
        .pw-fields { display: none; padding: 0 1.25rem 1.25rem; }
        .pw-fields.open { display: block; }

        /* ── Danger zone ─────────────────────────────── */
        .danger-zone {
            background: var(--red-bg);
            border: 1px solid #FEC9C7;
            border-radius: var(--radius-lg);
            padding: 1.1rem;
        }
        .dz-title {
            font-size: 0.78rem; font-weight: 700;
            color: var(--red); margin-bottom: 0.45rem;
        }
        .dz-desc {
            font-size: 0.74rem; color: var(--text-secondary);
            margin-bottom: 0.85rem; line-height: 1.45;
        }

        /* ── Sidebar info card ───────────────────────── */
        .info-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            padding: 1.15rem;
            margin-bottom: 1rem;
        }
        .info-card-label {
            font-size: 0.65rem; font-weight: 700;
            letter-spacing: 0.1em; text-transform: uppercase;
            color: var(--text-muted); margin-bottom: 0.85rem;
        }
        .info-user-row {
            display: flex; align-items: center; gap: 0.7rem; margin-bottom: 0.9rem;
        }

        /* Notice chip */
        .notice-chip {
            display: flex; align-items: flex-start; gap: 0.5rem;
            padding: 0.65rem 0.8rem;
            background: var(--amber-bg);
            border: 1px solid #FDE68A;
            border-radius: var(--radius);
            font-size: 0.73rem; color: var(--amber);
            line-height: 1.4;
        }

        @media (max-width: 900px) {
            .edit-layout { grid-template-columns: 1fr; }
            .field-row { grid-template-columns: 1fr; }
        }
    </style>

    {{-- Form hapus diletakkan DI LUAR form edit agar tidak nested --}}
    @if($user->id !== auth()->id())
        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" id="deleteForm"
              style="display:none">
            @csrf @method('DELETE')
        </form>
    @endif

    <form method="POST" action="{{ route('admin.users.update', $user) }}" id="editForm">
        @csrf @method('PUT')

        <div class="edit-layout">

            {{-- ── MAIN FORM ──────────────────────────── --}}
            <div>

                {{-- Informasi Akun --}}
                <div class="form-card">
                    <div class="form-card-header">
                        <div class="fch-icon" style="background:var(--blue-bg)">👤</div>
                        <span class="fch-title">Informasi Akun</span>
                    </div>
                    <div class="form-card-body">

                        <div class="form-group">
                            <label class="form-label" for="name">
                                Nama Lengkap <span style="color:var(--red)">*</span>
                            </label>
                            <input type="text" id="name" name="name"
                                   value="{{ old('name', $user->name) }}"
                                   class="form-input {{ $errors->has('name') ? 'border-danger' : '' }}"
                                   placeholder="Nama lengkap user">
                            @error('name')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field-row">
                            <div class="form-group">
                                <label class="form-label" for="email">
                                    Alamat Email <span style="color:var(--red)">*</span>
                                </label>
                                <input type="email" id="email" name="email"
                                       value="{{ old('email', $user->email) }}"
                                       class="form-input {{ $errors->has('email') ? 'border-danger' : '' }}"
                                       placeholder="email@contoh.com">
                                @error('email')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="no_hp">No. Handphone</label>
                                <input type="tel" id="no_hp" name="no_hp"
                                       value="{{ old('no_hp', $user->no_hp) }}"
                                       class="form-input {{ $errors->has('no_hp') ? 'border-danger' : '' }}"
                                       placeholder="08xxxxxxxxxx"
                                       maxlength="20">
                                @error('no_hp')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Role --}}
                <div class="form-card">
                    <div class="form-card-header">
                        <div class="fch-icon" style="background:var(--amber-bg)">🏷</div>
                        <span class="fch-title">Hak Akses / Role</span>
                        @if($user->id === auth()->id())
                            <span class="badge badge-amber" style="margin-left:auto;font-size:0.62rem">
                                ⚠ Tidak bisa ubah role sendiri
                            </span>
                        @endif
                    </div>
                    <div class="form-card-body">
                        <div class="role-grid">
                            @foreach(['admin'=>['🛡','Admin'],'petugas'=>['👔','Petugas'],'peminjam'=>['🙋','Peminjam']] as $val => [$emoji,$label])
                                <div class="role-option">
                                    <input type="radio" name="role" id="role_{{ $val }}" value="{{ $val }}"
                                           {{ old('role', $user->role) == $val ? 'checked' : '' }}
                                           {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                    <label for="role_{{ $val }}" class="role-label"
                                           style="{{ $user->id === auth()->id() ? 'opacity:0.45;cursor:not-allowed' : '' }}">
                                        <span class="role-emoji">{{ $emoji }}</span>
                                        <span class="role-name">{{ $label }}</span>
                                        <span class="role-check">✓</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        {{-- Hidden fallback jika role di-disable --}}
                        @if($user->id === auth()->id())
                            <input type="hidden" name="role" value="{{ $user->role }}">
                        @endif

                        @error('role')
                            <p class="form-error" style="margin-top:0.5rem">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password section (collapsible) --}}
                    <div class="pw-toggle-header" onclick="togglePassword(this)">
                        <div class="pw-toggle-left">
                            <div class="fch-icon" style="background:var(--green-bg);width:28px;height:28px;font-size:0.85rem">🔒</div>
                            <div>
                                <div style="font-size:0.84rem;font-weight:700;color:var(--text-primary)">Ubah Password</div>
                                <div style="font-size:0.7rem;color:var(--text-muted)">Kosongkan jika tidak ingin mengubah</div>
                            </div>
                        </div>
                        <span class="pw-toggle-caret">⌄</span>
                    </div>

                    <div class="pw-fields" id="pwFields">
                        <div class="field-row" style="margin-top:0.25rem">
                            <div class="form-group">
                                <label class="form-label">Password Baru</label>
                                <input type="password" name="password"
                                       class="form-input {{ $errors->has('password') ? 'border-danger' : '' }}"
                                       placeholder="Min. 8 karakter">
                                @error('password')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation"
                                       class="form-input"
                                       placeholder="Ulangi password baru">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── SIDEBAR ─────────────────────────────── --}}
            <div>

                {{-- Data saat ini --}}
                <div class="info-card">
                    <div class="info-card-label">Data Saat Ini</div>
                    <div class="info-user-row">
                        <div class="show-avatar {{ $user->role }}" style="width:42px;height:42px;border-radius:9px">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div style="min-width:0">
                            <div style="font-size:0.87rem;font-weight:700;color:var(--text-primary);
                                        white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                {{ $user->name }}
                            </div>
                            <div style="font-size:0.72rem;color:var(--text-muted);word-break:break-all">
                                {{ $user->email }}
                            </div>
                            @if($user->no_hp)
                                <div style="font-size:0.72rem;color:var(--text-muted);margin-top:0.1rem">
                                    📞 {{ $user->no_hp }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div style="display:flex;flex-wrap:wrap;gap:0.35rem">
                        @php $roleBadge = ['admin'=>'badge-red','petugas'=>'badge-blue','peminjam'=>'badge-green']; @endphp
                        <span class="badge {{ $roleBadge[$user->role] }}">{{ ucfirst($user->role) }}</span>
                        <span class="badge badge-slate" style="font-size:0.62rem">
                            Bergabung {{ $user->created_at->format('d M Y') }}
                        </span>
                    </div>
                </div>

                {{-- Tombol simpan --}}
                <div class="info-card">
                    <button type="submit" class="btn btn-primary"
                            style="width:100%;justify-content:center;padding:0.68rem;margin-bottom:0.5rem;display:flex">
                        ✓ Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.users.show', $user) }}"
                       class="btn btn-ghost"
                       style="width:100%;justify-content:center;display:flex">
                        Batal
                    </a>
                </div>

                {{-- Notice self --}}
                @if($user->id === auth()->id())
                    <div class="notice-chip" style="margin-bottom:1rem">
                        <span style="flex-shrink:0">ℹ️</span>
                        <span>Anda sedang mengedit akun sendiri. Role tidak dapat diubah.</span>
                    </div>
                @endif

                {{-- Danger zone --}}
                @if($user->id !== auth()->id())
                    <div class="danger-zone">
                        <div class="dz-title">⚠ Zona Berbahaya</div>
                        <div class="dz-desc">
                            Menghapus user bersifat permanen. Pastikan user tidak memiliki peminjaman aktif.
                        </div>
                        <button type="button" class="btn btn-danger"
                                style="width:100%;justify-content:center;display:flex"
                                onclick="confirmDelete()">
                            🗑 Hapus User Ini
                        </button>
                    </div>
                @endif

            </div>
        </div>
    </form>

    <script>
        // Auto-open password section jika ada error password
        @if($errors->has('password') || $errors->has('password_confirmation'))
            document.addEventListener('DOMContentLoaded', () => openPassword());
        @endif

        function togglePassword(header) {
            const fields = document.getElementById('pwFields');
            const caret  = header.querySelector('.pw-toggle-caret');
            fields.classList.toggle('open');
            caret.classList.toggle('open');
        }
        function openPassword() {
            document.getElementById('pwFields').classList.add('open');
            document.querySelector('.pw-toggle-caret').classList.add('open');
        }

        // Konfirmasi & submit form hapus (terpisah dari form edit)
        function confirmDelete() {
            if (confirm('YAKIN hapus user {{ $user->name }}? Ini tidak dapat dibatalkan!')) {
                formChanged = false; // reset flag agar tidak muncul beforeunload
                document.getElementById('deleteForm').submit();
            }
        }

        // Warn before leaving with unsaved changes
        let formChanged = false;
        document.getElementById('editForm').addEventListener('input', () => formChanged = true);
        document.getElementById('editForm').addEventListener('submit', () => formChanged = false);
        window.addEventListener('beforeunload', e => {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
    </script>

</x-admin-layout>