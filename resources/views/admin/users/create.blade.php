<x-admin-layout title="Tambah User" breadcrumb="Tambah User">

    {{-- ── PAGE HEADER ─────────────────────────────── --}}
    <x-slot name="header">
        <div>
            <h1 class="page-heading">Tambah User</h1>
            <p class="page-sub">Buat akun pengguna baru untuk sistem SewaAlat.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">
            ← Kembali
        </a>
    </x-slot>

    <style>
        .create-wrap {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 1.5rem;
            align-items: start;
        }

        /* ── Form Card ───────────────────────────────── */
        .form-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }
        .form-card-header {
            padding: 1rem 1.4rem;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 0.65rem;
        }
        .form-card-icon {
            width: 32px; height: 32px;
            border-radius: var(--radius);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.95rem;
        }
        .form-card-title {
            font-size: 0.88rem; font-weight: 700;
            color: var(--text-primary);
        }
        .form-card-body { padding: 1.4rem; }

        /* ── Field groups ────────────────────────────── */
        .field-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .form-group { margin-bottom: 1.1rem; }
        .form-group:last-child { margin-bottom: 0; }

        /* ── Role selector ───────────────────────────── */
        .role-group { margin-bottom: 1.1rem; }
        .role-label {
            display: block;
            font-size: 0.75rem; font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
            letter-spacing: 0.02em;
        }
        .role-options {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
        }
        .role-option { position: relative; }
        .role-option input[type="radio"] {
            position: absolute; opacity: 0; width: 0; height: 0;
        }
        .role-card {
            display: flex; flex-direction: column;
            align-items: center; gap: 0.4rem;
            padding: 0.85rem 0.5rem;
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            cursor: pointer;
            transition: all 0.15s;
            text-align: center;
            background: var(--white);
        }
        .role-card:hover {
            border-color: var(--border-2);
            background: var(--surface);
        }
        .role-icon { font-size: 1.3rem; }
        .role-name {
            font-size: 0.75rem; font-weight: 700;
            color: var(--text-secondary);
        }
        .role-desc {
            font-size: 0.65rem; color: var(--text-muted);
            line-height: 1.3;
        }

        /* Radio checked states per role */
        .role-option input[value="admin"]:checked   ~ .role-card {
            border-color: var(--red);
            background: var(--red-bg);
        }
        .role-option input[value="admin"]:checked   ~ .role-card .role-name { color: var(--red); }
        .role-option input[value="petugas"]:checked ~ .role-card {
            border-color: var(--blue);
            background: var(--blue-bg);
        }
        .role-option input[value="petugas"]:checked ~ .role-card .role-name { color: var(--blue); }
        .role-option input[value="peminjam"]:checked ~ .role-card {
            border-color: var(--green);
            background: var(--green-bg);
        }
        .role-option input[value="peminjam"]:checked ~ .role-card .role-name { color: var(--green-d); }

        /* ── Password strength ───────────────────────── */
        .password-wrap { position: relative; }
        .password-wrap .form-input { padding-right: 2.8rem; }
        .toggle-pw {
            position: absolute; right: 0.75rem; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            color: var(--text-muted); cursor: pointer;
            font-size: 0.9rem; padding: 0;
            transition: color 0.15s;
        }
        .toggle-pw:hover { color: var(--text-primary); }

        .pw-strength { margin-top: 0.45rem; }
        .pw-bars { display: flex; gap: 3px; margin-bottom: 0.3rem; }
        .pw-bar {
            flex: 1; height: 3px; border-radius: 2px;
            background: var(--border);
            transition: background 0.25s;
        }
        .pw-bar.weak   { background: var(--red); }
        .pw-bar.fair   { background: var(--amber); }
        .pw-bar.good   { background: var(--blue); }
        .pw-bar.strong { background: var(--green); }
        .pw-hint {
            font-size: 0.68rem; color: var(--text-muted);
        }

        /* ── Side panel ──────────────────────────────── */
        .side-panel { display: flex; flex-direction: column; gap: 1rem; }

        /* Preview card */
        .preview-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }
        .preview-header {
            padding: 0.85rem 1.1rem;
            border-bottom: 1px solid var(--border);
            font-size: 0.78rem; font-weight: 700;
            color: var(--text-secondary);
            text-transform: uppercase; letter-spacing: 0.08em;
        }
        .preview-body { padding: 1.25rem; }
        .preview-avatar {
            width: 56px; height: 56px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; font-weight: 700; color: white;
            margin: 0 auto 0.85rem;
            transition: background 0.3s;
        }
        .preview-name {
            font-size: 0.92rem; font-weight: 700;
            color: var(--text-primary); text-align: center;
            min-height: 1.2em;
        }
        .preview-email {
            font-size: 0.75rem; color: var(--text-muted);
            text-align: center; word-break: break-all;
            margin-top: 0.2rem; min-height: 1.1em;
        }
        .preview-phone {
            font-size: 0.75rem; color: var(--text-muted);
            text-align: center; margin-top: 0.15rem; min-height: 1em;
        }
        .preview-badge-wrap { text-align: center; margin-top: 0.75rem; }

        /* Tips card */
        .tips-card {
            background: var(--green-bg);
            border: 1px solid var(--green-mid);
            border-radius: var(--radius-lg);
            padding: 1.1rem;
        }
        .tips-title {
            font-size: 0.78rem; font-weight: 700;
            color: var(--green-d); margin-bottom: 0.6rem;
            display: flex; align-items: center; gap: 0.4rem;
        }
        .tips-list { list-style: none; }
        .tips-list li {
            font-size: 0.75rem; color: var(--text-secondary);
            padding: 0.3rem 0;
            border-bottom: 1px solid var(--green-mid);
            display: flex; gap: 0.5rem; align-items: flex-start;
        }
        .tips-list li:last-child { border-bottom: none; padding-bottom: 0; }
        .tips-list li::before {
            content: '✓';
            color: var(--green);
            font-weight: 700; font-size: 0.72rem;
            flex-shrink: 0; margin-top: 0.05rem;
        }

        /* ── Submit bar ──────────────────────────────── */
        .submit-bar {
            display: flex; align-items: center;
            justify-content: flex-end; gap: 0.6rem;
            padding: 1rem 1.4rem;
            border-top: 1px solid var(--border);
            background: var(--surface);
        }

        /* ── Validation error ────────────────────────── */
        .field-error {
            font-size: 0.72rem; color: var(--red);
            margin-top: 0.3rem;
            display: flex; align-items: center; gap: 0.3rem;
        }
        .field-error::before { content: '!'; font-weight: 700; }
        .form-input.error, .form-select.error {
            border-color: var(--red);
            background: var(--red-bg);
        }
        .form-input.error:focus, .form-select.error:focus {
            box-shadow: 0 0 0 3px rgba(192,57,43,0.1);
        }

        @media (max-width: 900px) {
            .create-wrap { grid-template-columns: 1fr; }
            .side-panel { order: -1; }
            .role-options { grid-template-columns: repeat(3, 1fr); }
            .field-row { grid-template-columns: 1fr; }
        }
    </style>

    <form method="POST" action="{{ route('admin.users.store') }}" id="createForm">
        @csrf

        <div class="create-wrap">

            {{-- ── LEFT: Main form ────────────────────── --}}
            <div>

                {{-- Informasi Dasar --}}
                <div class="form-card" style="margin-bottom:1rem">
                    <div class="form-card-header">
                        <div class="form-card-icon" style="background:var(--blue-bg)">👤</div>
                        <span class="form-card-title">Informasi Dasar</span>
                    </div>
                    <div class="form-card-body">

                        <div class="field-row">
                            <div class="form-group">
                                <label class="form-label" for="name">Nama Lengkap <span style="color:var(--red)">*</span></label>
                                <input
                                    type="text" id="name" name="name"
                                    class="form-input {{ $errors->has('name') ? 'error' : '' }}"
                                    value="{{ old('name') }}"
                                    placeholder="Nama lengkap pengguna"
                                    autocomplete="off" required>
                                @error('name')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="no_hp">No. Handphone</label>
                                <input
                                    type="tel" id="no_hp" name="no_hp"
                                    class="form-input {{ $errors->has('no_hp') ? 'error' : '' }}"
                                    value="{{ old('no_hp') }}"
                                    placeholder="08xxxxxxxxxx"
                                    maxlength="20">
                                @error('no_hp')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email">Alamat Email <span style="color:var(--red)">*</span></label>
                            <input
                                type="email" id="email" name="email"
                                class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                                value="{{ old('email') }}"
                                placeholder="contoh@email.com"
                                autocomplete="off" required>
                            @error('email')
                                <div class="field-error">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Role --}}
                <div class="form-card" style="margin-bottom:1rem">
                    <div class="form-card-header">
                        <div class="form-card-icon" style="background:var(--amber-bg)">🏷</div>
                        <span class="form-card-title">Peran Pengguna</span>
                    </div>
                    <div class="form-card-body">
                        <div class="role-group">
                            <span class="role-label">Pilih peran <span style="color:var(--red)">*</span></span>
                            <div class="role-options">

                                <label class="role-option">
                                    <input type="radio" name="role" value="admin"
                                           {{ old('role') == 'admin' ? 'checked' : '' }}>
                                    <div class="role-card">
                                        <span class="role-icon">🛡</span>
                                        <span class="role-name">Admin</span>
                                        <span class="role-desc">Akses penuh ke semua fitur</span>
                                    </div>
                                </label>

                                <label class="role-option">
                                    <input type="radio" name="role" value="petugas"
                                           {{ old('role') == 'petugas' ? 'checked' : '' }}>
                                    <div class="role-card">
                                        <span class="role-icon">👔</span>
                                        <span class="role-name">Petugas</span>
                                        <span class="role-desc">Kelola alat & transaksi</span>
                                    </div>
                                </label>

                                <label class="role-option">
                                    <input type="radio" name="role" value="peminjam"
                                           {{ old('role', 'peminjam') == 'peminjam' ? 'checked' : '' }}>
                                    <div class="role-card">
                                        <span class="role-icon">🙋</span>
                                        <span class="role-name">Peminjam</span>
                                        <span class="role-desc">Meminjam alat saja</span>
                                    </div>
                                </label>

                            </div>
                            @error('role')
                                <div class="field-error" style="margin-top:0.5rem">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Password --}}
                <div class="form-card">
                    <div class="form-card-header">
                        <div class="form-card-icon" style="background:var(--green-bg)">🔒</div>
                        <span class="form-card-title">Kata Sandi</span>
                    </div>
                    <div class="form-card-body">

                        <div class="field-row">
                            <div class="form-group">
                                <label class="form-label" for="password">Password <span style="color:var(--red)">*</span></label>
                                <div class="password-wrap">
                                    <input
                                        type="password" id="password" name="password"
                                        class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                                        placeholder="Min. 8 karakter"
                                        required>
                                    <button type="button" class="toggle-pw" onclick="togglePw('password', this)">👁</button>
                                </div>
                                <div class="pw-strength" id="pwStrength" style="display:none">
                                    <div class="pw-bars">
                                        <div class="pw-bar" id="bar1"></div>
                                        <div class="pw-bar" id="bar2"></div>
                                        <div class="pw-bar" id="bar3"></div>
                                        <div class="pw-bar" id="bar4"></div>
                                    </div>
                                    <span class="pw-hint" id="pwHint"></span>
                                </div>
                                @error('password')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="password_confirmation">Konfirmasi Password <span style="color:var(--red)">*</span></label>
                                <div class="password-wrap">
                                    <input
                                        type="password" id="password_confirmation" name="password_confirmation"
                                        class="form-input"
                                        placeholder="Ulangi password"
                                        required>
                                    <button type="button" class="toggle-pw" onclick="togglePw('password_confirmation', this)">👁</button>
                                </div>
                                <div class="field-error" id="pwMatchErr" style="display:none">Password tidak cocok</div>
                            </div>
                        </div>

                    </div>

                    <div class="submit-bar">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">Batal</a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            ＋ Simpan User
                        </button>
                    </div>
                </div>

            </div>

            {{-- ── RIGHT: Side panel ──────────────────── --}}
            <div class="side-panel">

                {{-- Live Preview --}}
                <div class="preview-card">
                    <div class="preview-header">Pratinjau Kartu</div>
                    <div class="preview-body">
                        <div class="preview-avatar" id="previewAvatar"
                             style="background: linear-gradient(135deg, var(--green), #059669)">
                            ?
                        </div>
                        <div class="preview-name" id="previewName" style="color:var(--text-muted)">
                            Nama pengguna
                        </div>
                        <div class="preview-email" id="previewEmail">
                            email@contoh.com
                        </div>
                        <div class="preview-phone" id="previewPhone"></div>
                        <div class="preview-badge-wrap">
                            <span class="badge badge-green" id="previewBadge">Peminjam</span>
                        </div>
                    </div>
                </div>

                {{-- Tips --}}
                <div class="tips-card">
                    <div class="tips-title">
                        <span>💡</span> Panduan Peran
                    </div>
                    <ul class="tips-list">
                        <li>Admin dapat mengelola semua data termasuk user lain</li>
                        <li>Petugas dapat memproses peminjaman dan pengembalian</li>
                        <li>Peminjam hanya dapat melihat katalog dan meminjam alat</li>
                        <li>Gunakan email yang valid agar dapat menerima notifikasi</li>
                        <li>Password minimal 8 karakter dengan kombinasi huruf & angka</li>
                    </ul>
                </div>

            </div>

        </div>
    </form>

    <script>
        // ── Live Preview ──────────────────────────────
        const nameEl    = document.getElementById('name');
        const emailEl   = document.getElementById('email');
        const phoneEl   = document.getElementById('no_hp');
        const roleEls   = document.querySelectorAll('input[name="role"]');

        const previewAvatar = document.getElementById('previewAvatar');
        const previewName   = document.getElementById('previewName');
        const previewEmail  = document.getElementById('previewEmail');
        const previewPhone  = document.getElementById('previewPhone');
        const previewBadge  = document.getElementById('previewBadge');

        const roleConfig = {
            admin:    { badge: 'badge-red',   gradient: 'linear-gradient(135deg,#C0392B,#E67E22)', label: 'Administrator' },
            petugas:  { badge: 'badge-blue',  gradient: 'linear-gradient(135deg,#2563EB,#7C3AED)', label: 'Petugas' },
            peminjam: { badge: 'badge-green', gradient: 'linear-gradient(135deg,var(--green),#059669)', label: 'Peminjam' },
        };

        function updatePreview() {
            const name = nameEl.value.trim();
            const email = emailEl.value.trim();
            const phone = phoneEl.value.trim();
            const role = document.querySelector('input[name="role"]:checked')?.value || 'peminjam';
            const cfg = roleConfig[role];

            previewAvatar.style.background = cfg.gradient;
            previewAvatar.textContent = name
                ? name.split(' ').map(w => w[0]).join('').substring(0, 2).toUpperCase()
                : '?';

            previewName.textContent = name || 'Nama pengguna';
            previewName.style.color = name ? 'var(--text-primary)' : 'var(--text-muted)';

            previewEmail.textContent = email || 'email@contoh.com';
            previewPhone.textContent = phone ? '📞 ' + phone : '';

            previewBadge.className = 'badge ' + cfg.badge;
            previewBadge.textContent = cfg.label;
        }

        nameEl.addEventListener('input', updatePreview);
        emailEl.addEventListener('input', updatePreview);
        phoneEl.addEventListener('input', updatePreview);
        roleEls.forEach(r => r.addEventListener('change', updatePreview));

        // ── Password strength ─────────────────────────
        const pwInput = document.getElementById('password');
        const bars    = [document.getElementById('bar1'), document.getElementById('bar2'),
                         document.getElementById('bar3'), document.getElementById('bar4')];
        const pwHint  = document.getElementById('pwHint');
        const pwStrength = document.getElementById('pwStrength');

        function calcStrength(pw) {
            let score = 0;
            if (pw.length >= 8)  score++;
            if (pw.length >= 12) score++;
            if (/[A-Z]/.test(pw) && /[a-z]/.test(pw)) score++;
            if (/\d/.test(pw))   score++;
            if (/[^A-Za-z0-9]/.test(pw)) score++;
            return Math.min(4, score);
        }

        const levels = [
            { cls: 'weak',   label: 'Terlalu lemah' },
            { cls: 'fair',   label: 'Cukup' },
            { cls: 'good',   label: 'Baik' },
            { cls: 'strong', label: 'Sangat kuat' },
        ];

        pwInput.addEventListener('input', () => {
            const pw = pwInput.value;
            if (!pw) { pwStrength.style.display = 'none'; return; }
            pwStrength.style.display = 'block';
            const score = calcStrength(pw);
            const lvl   = levels[Math.max(0, score - 1)];
            bars.forEach((b, i) => {
                b.className = 'pw-bar';
                if (i < score) b.classList.add(lvl.cls);
            });
            pwHint.textContent = lvl.label;
            pwHint.style.color = score <= 1 ? 'var(--red)' : score === 2 ? 'var(--amber)' : score === 3 ? 'var(--blue)' : 'var(--green)';
        });

        // ── Password match ────────────────────────────
        const pwConfirm = document.getElementById('password_confirmation');
        const pwMatchErr = document.getElementById('pwMatchErr');

        function checkMatch() {
            if (!pwConfirm.value) { pwMatchErr.style.display = 'none'; return; }
            if (pwInput.value !== pwConfirm.value) {
                pwMatchErr.style.display = 'flex';
                pwConfirm.classList.add('error');
            } else {
                pwMatchErr.style.display = 'none';
                pwConfirm.classList.remove('error');
            }
        }
        pwConfirm.addEventListener('input', checkMatch);
        pwInput.addEventListener('input', checkMatch);

        // ── Toggle password visibility ────────────────
        function togglePw(id, btn) {
            const input = document.getElementById(id);
            input.type  = input.type === 'password' ? 'text' : 'password';
            btn.textContent = input.type === 'password' ? '👁' : '🙈';
        }

        // ── Prevent submit if mismatch ────────────────
        document.getElementById('createForm').addEventListener('submit', function(e) {
            if (pwInput.value !== pwConfirm.value) {
                e.preventDefault();
                pwMatchErr.style.display = 'flex';
                pwConfirm.focus();
            }
        });

        // Init preview
        updatePreview();
    </script>

</x-admin-layout>