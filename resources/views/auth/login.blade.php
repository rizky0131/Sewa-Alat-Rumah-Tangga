<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — SewaAlat</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">

    <style>
        :root {
            --font-body:    'DM Sans', sans-serif;
            --font-display: 'DM Serif Display', serif;

            --sage:         #4a7c59;
            --sage-light:   #6a9e78;
            --sage-pale:    #eef4f0;
            --sage-mid:     #d4e8da;

            --warm-bg:      #faf8f5;
            --warm-surface: #f4f1ec;
            --warm-line:    #e8e3db;
            --warm-line2:   #d6cfc5;

            --ink:          #1c1c1a;
            --ink-60:       #5a5954;
            --ink-35:       #9e9b96;

            --white:        #ffffff;

            --radius-sm:    6px;
            --radius-md:    10px;
            --radius-lg:    16px;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { height: 100%; }

        body {
            font-family: var(--font-body);
            background: var(--warm-bg);
            color: var(--ink);
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
            -webkit-font-smoothing: antialiased;
        }

        a { color: inherit; text-decoration: none; }

        /* ══════════════════════════════════
           LEFT PANEL — ilustrasi
        ══════════════════════════════════ */
        .left-panel {
            background: #182620;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 2.5rem;
        }

        /* subtle grid pattern */
        .left-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(74,124,89,.12) 1px, transparent 1px),
                linear-gradient(90deg, rgba(74,124,89,.12) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }

        /* radial glow */
        .left-panel::after {
            content: '';
            position: absolute;
            top: 30%;
            left: 20%;
            width: 420px;
            height: 420px;
            background: radial-gradient(circle, rgba(74,124,89,.22) 0%, transparent 70%);
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
            width: 16px; height: 1.5px;
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
            color: rgba(244,241,236,.5);
            line-height: 1.7;
            font-weight: 300;
            max-width: 320px;
            margin-bottom: 2rem;
        }

        /* stat chips */
        .left-stats {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .stat-chip {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 0.5rem 0.9rem;
            border-radius: 100px;
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.1);
        }

        .stat-chip-num {
            font-family: var(--font-display);
            font-size: 0.95rem;
            color: #f4f1ec;
        }

        .stat-chip-lbl {
            font-size: 0.68rem;
            color: rgba(244,241,236,.45);
            font-weight: 400;
        }

        .left-footer {
            position: relative;
            z-index: 2;
            font-size: 0.7rem;
            color: rgba(244,241,236,.25);
        }

        /* ══════════════════════════════════
           RIGHT PANEL — form
        ══════════════════════════════════ */
        .right-panel {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem 2rem;
            background: var(--warm-bg);
        }

        .form-wrap {
            width: 100%;
            max-width: 380px;
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
            width: 14px; height: 1.5px;
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
            margin-bottom: 2rem;
        }

        .form-sub a {
            color: var(--sage);
            font-weight: 500;
        }
        .form-sub a:hover { text-decoration: underline; }

        /* Alert status */
        .alert-status {
            padding: 0.75rem 1rem;
            border-radius: var(--radius-md);
            background: var(--sage-pale);
            border: 1px solid var(--sage-mid);
            font-size: 0.82rem;
            color: var(--sage);
            margin-bottom: 1.25rem;
        }

        /* Field group */
        .field {
            margin-bottom: 1.1rem;
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

        .field-input::placeholder { color: var(--ink-35); }

        .field-input:focus {
            border-color: var(--sage);
            box-shadow: 0 0 0 3px rgba(74,124,89,.12);
        }

        .field-input.is-error {
            border-color: #c0392b;
            box-shadow: 0 0 0 3px rgba(192,57,43,.1);
        }

        .field-error {
            margin-top: 0.35rem;
            font-size: 0.75rem;
            color: #c0392b;
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
        .pass-toggle:hover { color: var(--ink-60); }

        /* Row: remember + forgot */
        .form-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            margin-top: -0.15rem;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 0.8rem;
            color: var(--ink-60);
            cursor: pointer;
        }

        .remember-check {
            width: 15px;
            height: 15px;
            border-radius: 4px;
            border: 1.5px solid var(--warm-line2);
            background: var(--white);
            accent-color: var(--sage);
            cursor: pointer;
            flex-shrink: 0;
        }

        .forgot-link {
            font-size: 0.78rem;
            color: var(--ink-35);
            font-weight: 500;
            transition: color .15s;
        }
        .forgot-link:hover { color: var(--sage); }

        /* Submit button */
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
            transition: background .18s, transform .18s, box-shadow .18s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-submit:hover {
            background: var(--sage-light);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(74,124,89,.3);
        }

        .btn-submit:active { transform: translateY(0); }

        /* Divider */
        .form-divider {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 1.5rem 0;
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
            white-space: nowrap;
        }

        /* Register link */
        .register-prompt {
            text-align: center;
            font-size: 0.82rem;
            color: var(--ink-35);
            font-weight: 300;
        }

        .register-prompt a {
            color: var(--sage);
            font-weight: 600;
        }
        .register-prompt a:hover { text-decoration: underline; }

        /* ══════════════════════════════════
           RESPONSIVE
        ══════════════════════════════════ */
        @media (max-width: 800px) {
            body { grid-template-columns: 1fr; }
            .left-panel { display: none; }
            .right-panel { padding: 2.5rem 1.5rem; min-height: 100vh; }
        }
    </style>
</head>
<body>

    {{-- ═══════ LEFT PANEL ═══════ --}}
    <div class="left-panel">
        <a href="/" class="left-logo">
            <div class="left-logo-mark">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M3 8h10M8 3v10" stroke="#fff" stroke-width="2" stroke-linecap="round"/>
                    <circle cx="8" cy="8" r="2.5" fill="#fff" fill-opacity=".3"/>
                </svg>
            </div>
            <span class="left-logo-text">SewaAlat</span>
        </a>

        <div class="left-content">
            <div class="left-eyebrow">Platform Sewa Terpercaya</div>
            <h2 class="left-h">
                Semua Alat<br>
                yang Kamu<br>
                <em>Butuhkan</em>
            </h2>
            <p class="left-p">
                Sewa alat rumah tangga berkualitas tanpa perlu beli.
                Mudah, cepat, dan terpercaya.
            </p>
            <div class="left-stats">
                <div class="stat-chip">
                    <span class="stat-chip-num">100+</span>
                    <span class="stat-chip-lbl">Jenis Alat</span>
                </div>
                <div class="stat-chip">
                    <span class="stat-chip-num">500+</span>
                    <span class="stat-chip-lbl">Transaksi</span>
                </div>
                <div class="stat-chip">
                    <span class="stat-chip-num">4.9★</span>
                    <span class="stat-chip-lbl">Rating</span>
                </div>
            </div>
        </div>

        <div class="left-footer">© {{ date('Y') }} SewaAlat. Made with ♥ in Indonesia</div>
    </div>

    {{-- ═══════ RIGHT PANEL ═══════ --}}
    <div class="right-panel">
        <div class="form-wrap">

            <div class="form-eyebrow">Selamat datang kembali</div>
            <h1 class="form-h">Masuk ke Akun</h1>
            <p class="form-sub">
                Belum punya akun?
                <a href="{{ route('register') }}">Daftar gratis</a>
            </p>

            {{-- Session status --}}
            @if (session('status'))
                <div class="alert-status">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

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
                        autofocus
                        autocomplete="username"
                    >
                    @error('email')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="field">
                    <label class="field-label" for="password">Kata Sandi</label>
                    <div class="pass-wrap">
                        <input
                            id="password"
                            class="field-input {{ $errors->has('password') ? 'is-error' : '' }}"
                            type="password"
                            name="password"
                            placeholder="Masukkan kata sandi"
                            required
                            autocomplete="current-password"
                        >
                        <button type="button" class="pass-toggle" id="passToggle" aria-label="Tampilkan kata sandi">
                            <svg id="eyeIcon" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Remember + Forgot --}}
                <div class="form-row">
                    <label class="remember-label" for="remember_me">
                        <input id="remember_me" class="remember-check" type="checkbox" name="remember">
                        Ingat saya
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">Lupa kata sandi?</a>
                    @endif
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-submit">
                    Masuk
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <path d="M1 7h12M7 1l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </form>

            <div class="form-divider"><span>atau</span></div>

            <div class="register-prompt">
                Belum punya akun?
                <a href="{{ route('register') }}">Daftar sekarang →</a>
            </div>

        </div>
    </div>

    <script>
        (function () {
            const toggle = document.getElementById('passToggle');
            const input  = document.getElementById('password');
            const icon   = document.getElementById('eyeIcon');

            if (!toggle) return;

            toggle.addEventListener('click', function () {
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                icon.innerHTML = isPassword
                    ? '<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>'
                    : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
            });
        })();
    </script>

</body>
</html>