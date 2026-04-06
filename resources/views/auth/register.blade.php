<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — SewaAlat</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">

    <style>
        :root {
            --font-body: 'DM Sans', sans-serif;
            --font-display: 'DM Serif Display', serif;

            --sage: #4a7c59;
            --sage-light: #6a9e78;
            --sage-pale: #eef4f0;
            --sage-mid: #d4e8da;

            --warm-bg: #faf8f5;
            --warm-surface: #f4f1ec;
            --warm-line: #e8e3db;
            --warm-line2: #d6cfc5;

            --ink: #1c1c1a;
            --ink-60: #5a5954;
            --ink-35: #9e9b96;

            --white: #ffffff;

            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 16px;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            height: 100%;
        }

        body {
            font-family: var(--font-body);
            background: var(--warm-bg);
            color: var(--ink);
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-areas: "form panel";
            -webkit-font-smoothing: antialiased;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        /* ══════════════════════════════════
           LEFT PANEL
        ══════════════════════════════════ */
        .left-panel {
            grid-area: panel;
            background: #182620;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(74, 124, 89, .12) 1px, transparent 1px),
                linear-gradient(90deg, rgba(74, 124, 89, .12) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }

        .left-panel::after {
            content: '';
            position: absolute;
            top: 35%;
            left: 15%;
            width: 460px;
            height: 460px;
            background: radial-gradient(circle, rgba(74, 124, 89, .2) 0%, transparent 70%);
            pointer-events: none;
        }

        .left-logo {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .left-logo-mark {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--sage);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .left-logo-text {
            font-family: var(--font-display);
            font-size: 1.2rem;
            color: #f4f1ec;
            letter-spacing: -0.01em;
        }

        .left-content {
            position: relative;
            z-index: 2;
        }

        .left-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .14em;
            color: #7ec89a;
            margin-bottom: 1rem;
        }

        .left-eyebrow::before {
            content: '';
            width: 16px;
            height: 1.5px;
            background: #7ec89a;
            display: block;
        }

        .left-h {
            font-family: var(--font-display);
            font-size: clamp(2rem, 3vw, 2.8rem);
            font-weight: 400;
            color: #f4f1ec;
            line-height: 1.15;
            letter-spacing: -0.015em;
            margin-bottom: 1rem;
        }

        .left-h em {
            font-style: italic;
            color: #a8dbb8;
        }

        .left-p {
            font-size: 0.88rem;
            color: rgba(244, 241, 236, .5);
            line-height: 1.7;
            font-weight: 300;
            max-width: 300px;
            margin-bottom: 2rem;
        }

        /* benefit list */
        .benefit-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .benefit-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .benefit-icon {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: rgba(74, 124, 89, .25);
            border: 1px solid rgba(74, 124, 89, .4);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .benefit-icon svg {
            display: block;
        }

        .benefit-text {
            font-size: 0.82rem;
            color: rgba(244, 241, 236, .6);
            line-height: 1.5;
            font-weight: 300;
        }

        .left-footer {
            position: relative;
            z-index: 2;
            font-size: 0.7rem;
            color: rgba(244, 241, 236, .25);
        }

        /* ══════════════════════════════════
           RIGHT PANEL — form
        ══════════════════════════════════ */
        .right-panel {
            grid-area: form;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem 2rem;
            background: var(--warm-bg);
            overflow-y: auto;
        }

        .form-wrap {
            width: 100%;
            max-width: 400px;
        }

        .form-eyebrow {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .14em;
            color: var(--sage);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .form-eyebrow::before {
            content: '';
            width: 14px;
            height: 1.5px;
            background: var(--sage);
            display: block;
        }

        .form-h {
            font-family: var(--font-display);
            font-size: 2rem;
            font-weight: 400;
            color: var(--ink);
            line-height: 1.15;
            letter-spacing: -0.015em;
            margin-bottom: 0.4rem;
        }

        .form-sub {
            font-size: 0.85rem;
            color: var(--ink-35);
            font-weight: 300;
            margin-bottom: 1.8rem;
        }

        .form-sub a {
            color: var(--sage);
            font-weight: 500;
        }

        .form-sub a:hover {
            text-decoration: underline;
        }

        /* Two-column grid for name + email */
        .field-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.9rem;
        }

        .left-panel {
            height: 100%;
            overflow: hidden;
        }
        

        .left-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: 70% center;
        }

        /* Field */
        .field {
            margin-bottom: 1rem;
        }

        .field:last-of-type {
            margin-bottom: 0;
        }

        .field-label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--ink-60);
            margin-bottom: 0.4rem;
            letter-spacing: .01em;
        }

        .field-input {
            width: 100%;
            height: 44px;
            padding: 0 0.95rem;
            border-radius: var(--radius-md);
            border: 1.5px solid var(--warm-line2);
            background: var(--white);
            font-family: var(--font-body);
            font-size: 0.9rem;
            font-weight: 400;
            color: var(--ink);
            outline: none;
            transition: border-color .18s, box-shadow .18s;
            -webkit-appearance: none;
        }

        .field-input::placeholder {
            color: var(--ink-35);
        }

        .field-input:focus {
            border-color: var(--sage);
            box-shadow: 0 0 0 3px rgba(74, 124, 89, .12);
        }

        .field-input.is-error {
            border-color: #c0392b;
            box-shadow: 0 0 0 3px rgba(192, 57, 43, .1);
        }

        .field-error {
            margin-top: 0.35rem;
            font-size: 0.72rem;
            color: #c0392b;
            line-height: 1.4;
        }

        /* Password wrapper */
        .pass-wrap {
            position: relative;
        }

        .pass-wrap .field-input {
            padding-right: 2.8rem;
        }

        .pass-toggle {
            position: absolute;
            right: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--ink-35);
            padding: 0;
            display: flex;
            align-items: center;
            transition: color .15s;
        }

        .pass-toggle:hover {
            color: var(--ink-60);
        }

        /* Password strength bar */
        .pass-strength {
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .strength-bars {
            display: flex;
            gap: 3px;
            flex: 1;
        }

        .strength-bar {
            height: 3px;
            flex: 1;
            border-radius: 2px;
            background: var(--warm-line2);
            transition: background .25s;
        }

        .strength-bar.active-weak {
            background: #c0392b;
        }

        .strength-bar.active-medium {
            background: var(--amber, #d97706);
        }

        .strength-bar.active-strong {
            background: var(--sage);
        }

        .strength-label {
            font-size: 0.68rem;
            color: var(--ink-35);
            font-weight: 500;
            min-width: 48px;
            text-align: right;
            transition: color .25s;
        }

        /* Divider between sections */
        .fields-divider {
            height: 1px;
            background: var(--warm-line);
            margin: 1.1rem 0;
        }

        /* Submit */
        .btn-submit {
            width: 100%;
            height: 46px;
            border-radius: var(--radius-md);
            background: var(--sage);
            color: #fff;
            border: none;
            cursor: pointer;
            font-family: var(--font-body);
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: .01em;
            margin-top: 1.4rem;
            transition: background .18s, transform .18s, box-shadow .18s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-submit:hover {
            background: var(--sage-light);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(74, 124, 89, .3);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* Terms note */
        .terms-note {
            margin-top: 0.85rem;
            font-size: 0.72rem;
            color: var(--ink-35);
            text-align: center;
            line-height: 1.5;
            font-weight: 300;
        }

        /* Divider */
        .form-divider {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 1.25rem 0;
        }

        .form-divider::before,
        .form-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--warm-line2);
        }

        .form-divider span {
            font-size: 0.72rem;
            color: var(--ink-35);
        }

        .login-prompt {
            text-align: center;
            font-size: 0.82rem;
            color: var(--ink-35);
            font-weight: 300;
        }

        .login-prompt a {
            color: var(--sage);
            font-weight: 600;
        }

        .login-prompt a:hover {
            text-decoration: underline;
        }

        /* ══════════════════════════════════
           RESPONSIVE
        ══════════════════════════════════ */
        @media (max-width: 860px) {
            body {
                grid-template-columns: 1fr;
                grid-template-areas: "form";
            }

            .left-panel {
                display: none;
            }

            .right-panel {
                padding: 2.5rem 1.5rem;
                min-height: 100vh;
            }
        }

        @media (max-width: 480px) {
            .field-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    {{-- ═══════ LEFT PANEL ═══════ --}}
    <div class="left-panel">
        <img src="{{ asset('storage/register.png') }}" alt="Banner" class="left-img">
    </div>

    {{-- ═══════ RIGHT PANEL ═══════ --}}
    <div class="right-panel">
        <div class="form-wrap">

            <div class="form-eyebrow">Buat akun baru</div>
            <h1 class="form-h">Daftar Gratis</h1>
            <p class="form-sub">
                <!-- Sudah punya akun?
                <a href="{{ route('login') }}">Masuk di sini</a> -->
            </p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Name + Email in grid --}}
                <div class="field-grid">
                    {{-- Name --}}
                    <div class="field">
                        <label class="field-label" for="name">Nama Lengkap</label>
                        <input
                            id="name"
                            class="field-input {{ $errors->has('name') ? 'is-error' : '' }}"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Nama kamu"
                            required
                            autofocus
                            autocomplete="name">
                        @error('name')
                        <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="field">
                        <label class="field-label" for="email">Alamat Email</label>
                        <input
                            id="email"
                            class="field-input {{ $errors->has('email') ? 'is-error' : '' }}"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="nama@email.com"
                            required
                            autocomplete="username">
                        @error('email')
                        <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="fields-divider"></div>

                {{-- Password --}}
                <div class="field">
                    <label class="field-label" for="password">Kata Sandi</label>
                    <div class="pass-wrap">
                        <input
                            id="password"
                            class="field-input {{ $errors->has('password') ? 'is-error' : '' }}"
                            type="password"
                            name="password"
                            placeholder="Minimal 8 karakter"
                            required
                            autocomplete="new-password">
                        <button type="button" class="pass-toggle" id="passToggle1" aria-label="Tampilkan kata sandi">
                            <svg id="eyeIcon1" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </button>
                    </div>
                    {{-- Strength bar --}}
                    <div class="pass-strength" id="strengthWrap" style="display:none">
                        <div class="strength-bars">
                            <div class="strength-bar" id="sb1"></div>
                            <div class="strength-bar" id="sb2"></div>
                            <div class="strength-bar" id="sb3"></div>
                            <div class="strength-bar" id="sb4"></div>
                        </div>
                        <span class="strength-label" id="strengthLabel"></span>
                    </div>
                    @error('password')
                    <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="field" style="margin-bottom:0">
                    <label class="field-label" for="password_confirmation">Konfirmasi Kata Sandi</label>
                    <div class="pass-wrap">
                        <input
                            id="password_confirmation"
                            class="field-input {{ $errors->has('password_confirmation') ? 'is-error' : '' }}"
                            type="password"
                            name="password_confirmation"
                            placeholder="Ulangi kata sandi"
                            required
                            autocomplete="new-password">
                        <button type="button" class="pass-toggle" id="passToggle2" aria-label="Tampilkan konfirmasi">
                            <svg id="eyeIcon2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </button>
                    </div>
                    @error('password_confirmation')
                    <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">
                    Buat Akun
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <path d="M1 7h12M7 1l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>

                <p class="terms-note">
                    Dengan mendaftar, kamu menyetujui syarat & ketentuan layanan SewaAlat.
                </p>
            </form>

            <div class="form-divider"><span>atau</span></div>

            <div class="login-prompt">
                Sudah punya akun?
                <a href="{{ route('login') }}">Masuk sekarang →</a>
            </div>

        </div>
    </div>

    <script>
        (function() {
            /* ── Toggle show/hide password ───── */
            function makeToggle(btnId, inputId, iconId) {
                const btn = document.getElementById(btnId);
                const input = document.getElementById(inputId);
                const icon = document.getElementById(iconId);
                if (!btn) return;
                btn.addEventListener('click', function() {
                    const show = input.type === 'password';
                    input.type = show ? 'text' : 'password';
                    icon.innerHTML = show ?
                        '<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>' :
                        '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
                });
            }

            makeToggle('passToggle1', 'password', 'eyeIcon1');
            makeToggle('passToggle2', 'password_confirmation', 'eyeIcon2');

            /* ── Password strength meter ──────── */
            const passInput = document.getElementById('password');
            const strengthWrap = document.getElementById('strengthWrap');
            const strengthLabel = document.getElementById('strengthLabel');
            const bars = [
                document.getElementById('sb1'),
                document.getElementById('sb2'),
                document.getElementById('sb3'),
                document.getElementById('sb4'),
            ];

            function calcStrength(val) {
                let score = 0;
                if (val.length >= 8) score++;
                if (val.length >= 12) score++;
                if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
                if (/[0-9]/.test(val)) score++;
                if (/[^A-Za-z0-9]/.test(val)) score++;
                return Math.min(4, Math.ceil(score * 4 / 5));
            }

            const levels = [{
                    cls: 'active-weak',
                    lbl: 'Lemah',
                    color: '#c0392b'
                },
                {
                    cls: 'active-weak',
                    lbl: 'Lemah',
                    color: '#c0392b'
                },
                {
                    cls: 'active-medium',
                    lbl: 'Sedang',
                    color: '#d97706'
                },
                {
                    cls: 'active-medium',
                    lbl: 'Sedang',
                    color: '#d97706'
                },
                {
                    cls: 'active-strong',
                    lbl: 'Kuat',
                    color: '#4a7c59'
                },
            ];

            passInput.addEventListener('input', function() {
                const val = this.value;
                if (!val) {
                    strengthWrap.style.display = 'none';
                    bars.forEach(b => {
                        b.className = 'strength-bar';
                    });
                    return;
                }
                strengthWrap.style.display = 'flex';
                const score = calcStrength(val);
                const lvl = levels[score];
                bars.forEach((b, i) => {
                    b.className = 'strength-bar' + (i < score ? ' ' + lvl.cls : '');
                });
                strengthLabel.textContent = lvl.lbl;
                strengthLabel.style.color = lvl.color;
            });
        })();
    </script>

</body>

</html>