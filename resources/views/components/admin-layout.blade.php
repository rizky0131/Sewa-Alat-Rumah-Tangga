<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} — Admin · SewaAlat</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;0,9..144,700;1,9..144,400&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ── CSS Variables ────────────────────────────── */
        :root {
            --white:       #FFFFFF;
            --surface:     #F7F8F5;
            --surface-2:   #EFF1EC;
            --border:      #E2E5DC;
            --border-2:    #CBD0C4;
            --text-primary:#1A2116;
            --text-secondary:#52614A;
            --text-muted:  #8A9882;

            /* Brand Green — matches SewaAlat */
            --green:       #2D7A4F;
            --green-d:     #1F5C39;
            --green-l:     #3D9E68;
            --green-bg:    #EAF4EE;
            --green-mid:   #C5DFCF;

            /* Accents */
            --amber:       #C07A1B;
            --amber-bg:    #FEF3E2;
            --red:         #C0392B;
            --red-bg:      #FEF0EF;
            --blue:        #2563EB;
            --blue-bg:     #EFF4FE;

            --sidebar-w:   256px;
            --topbar-h:    60px;
            --radius:      8px;
            --radius-lg:   12px;

            --font-display:'Fraunces', Georgia, serif;
            --font-ui:     'Plus Jakarta Sans', system-ui, sans-serif;

            --shadow-sm:   0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md:   0 4px 12px rgba(0,0,0,0.08), 0 2px 4px rgba(0,0,0,0.04);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: var(--font-ui);
            background: var(--surface);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Layout Shell ─────────────────────────────── */
        .app-shell {
            display: flex;
            min-height: 100vh;
        }

        /* ── Sidebar ──────────────────────────────────── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--white);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; bottom: 0; left: 0;
            z-index: 50;
            transition: transform 0.28s cubic-bezier(0.4,0,0.2,1);
        }

        /* Logo */
        .sidebar-logo {
            padding: 1.4rem 1.25rem 1.2rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 0.7rem;
        }
        .logo-mark {
            width: 34px; height: 34px;
            background: var(--green);
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .logo-mark svg { width: 18px; height: 18px; fill: white; }
        .logo-text {
            font-family: var(--font-display);
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.2px;
        }
        .logo-badge {
            margin-left: auto;
            font-size: 0.6rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            background: var(--green-bg);
            color: var(--green);
            border: 1px solid var(--green-mid);
            padding: 0.18rem 0.5rem;
            border-radius: 4px;
        }

        /* Nav group label */
        .nav-group-label {
            font-size: 0.62rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 1.3rem 1.25rem 0.45rem;
        }

        /* Nav */
        .sidebar-nav {
            flex: 1;
            padding: 0.4rem 0 1rem;
            overflow-y: auto;
        }
        .sidebar-nav::-webkit-scrollbar { width: 0; }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.6rem 1rem;
            margin: 0.1rem 0.6rem;
            border-radius: var(--radius);
            color: var(--text-secondary);
            font-size: 0.82rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s;
        }
        .nav-item:hover {
            background: var(--surface);
            color: var(--text-primary);
        }
        .nav-item.active {
            background: var(--green-bg);
            color: var(--green-d);
            font-weight: 600;
        }
        .nav-icon {
            width: 20px; height: 20px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            opacity: 0.6;
            font-size: 0.95rem;
            transition: opacity 0.15s;
        }
        .nav-item.active .nav-icon { opacity: 1; }
        .nav-item:hover .nav-icon { opacity: 0.85; }

        .nav-badge {
            margin-left: auto;
            background: var(--green);
            color: white;
            font-size: 0.62rem;
            font-weight: 700;
            padding: 0.15rem 0.5rem;
            border-radius: 100px;
            min-width: 18px;
            text-align: center;
        }
        .nav-badge.danger {
            background: var(--red);
        }

        /* Active indicator bar */
        .nav-item.active::before {
            content: '';
            display: none;
        }

        /* Sidebar footer */
        .sidebar-footer {
            padding: 0.85rem 1rem;
            border-top: 1px solid var(--border);
        }
        .user-row {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.6rem 0.75rem;
            border-radius: var(--radius);
            transition: background 0.15s;
            cursor: pointer;
            text-decoration: none;
        }
        .user-row:hover { background: var(--surface); }
        .user-avatar {
            width: 34px; height: 34px;
            background: var(--green-bg);
            border: 1.5px solid var(--green-mid);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; font-weight: 700;
            color: var(--green-d);
            flex-shrink: 0;
        }
        .user-info { flex: 1; min-width: 0; }
        .user-name {
            font-size: 0.82rem; font-weight: 600;
            color: var(--text-primary);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .user-role {
            font-size: 0.68rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .logout-btn {
            display: flex; align-items: center; gap: 0.55rem;
            width: 100%;
            padding: 0.5rem 0.75rem;
            margin-top: 0.3rem;
            background: none; border: none;
            cursor: pointer;
            color: var(--text-muted);
            font-size: 0.78rem; font-weight: 500;
            font-family: var(--font-ui);
            border-radius: var(--radius);
            transition: all 0.15s;
        }
        .logout-btn:hover {
            color: var(--red);
            background: var(--red-bg);
        }

        /* ── Main Content ─────────────────────────────── */
        .main-wrap {
            margin-left: var(--sidebar-w);
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Topbar ───────────────────────────────────── */
        .topbar {
            position: sticky; top: 0; z-index: 40;
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(16px) saturate(180%);
            border-bottom: 1px solid var(--border);
            padding: 0 1.75rem;
            height: var(--topbar-h);
            display: flex; align-items: center; gap: 1rem;
        }

        .sidebar-toggle {
            display: none;
            background: none; border: none;
            color: var(--text-muted);
            font-size: 1.1rem; cursor: pointer;
            padding: 0.3rem;
        }

        .breadcrumb {
            display: flex; align-items: center; gap: 0.4rem;
            font-size: 0.78rem;
            color: var(--text-muted);
            flex: 1;
        }
        .breadcrumb-sep { color: var(--border-2); }
        .breadcrumb-current { color: var(--text-primary); font-weight: 600; }

        .topbar-right {
            display: flex; align-items: center; gap: 0.6rem;
            margin-left: auto;
        }

        .topbar-search {
            display: flex; align-items: center; gap: 0.5rem;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 0.4rem 0.85rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .topbar-search:focus-within {
            border-color: var(--green);
            box-shadow: 0 0 0 3px rgba(45,122,79,0.1);
        }
        .topbar-search input {
            background: none; border: none; outline: none;
            color: var(--text-primary);
            font-family: var(--font-ui);
            font-size: 0.8rem;
            width: 160px;
        }
        .topbar-search input::placeholder { color: var(--text-muted); }
        .search-icon { color: var(--text-muted); font-size: 0.85rem; }

        .icon-btn {
            width: 36px; height: 36px;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            display: flex; align-items: center; justify-content: center;
            color: var(--text-secondary);
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.15s;
            position: relative;
            text-decoration: none;
        }
        .icon-btn:hover {
            background: var(--surface);
            border-color: var(--border-2);
            color: var(--text-primary);
        }
        .icon-btn .badge-dot {
            position: absolute; top: 8px; right: 8px;
            width: 6px; height: 6px;
            background: var(--green);
            border-radius: 50%;
            border: 1.5px solid var(--white);
        }

        /* ── Page Content ─────────────────────────────── */
        .page-content {
            flex: 1;
            padding: 2rem 1.75rem;
            max-width: 1400px;
            width: 100%;
        }

        .page-header {
            margin-bottom: 1.75rem;
            display: flex; align-items: flex-start;
            justify-content: space-between; gap: 1rem;
        }
        .page-heading {
            font-family: var(--font-display);
            font-size: 1.8rem; font-weight: 700;
            color: var(--text-primary); line-height: 1.2;
        }
        .page-sub {
            font-size: 0.82rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
        }

        /* ── Scrollbar ────────────────────────────────── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: var(--surface); }
        ::-webkit-scrollbar-thumb { background: var(--border-2); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-muted); }

        /* ── Mobile ───────────────────────────────────── */
        .sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(26,33,22,0.35);
            z-index: 49;
            backdrop-filter: blur(3px);
        }
        .sidebar-overlay.open { display: block; }

        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open {
                transform: translateX(0);
                box-shadow: var(--shadow-md);
            }
            .main-wrap { margin-left: 0; }
            .sidebar-toggle { display: flex; }
            .topbar-search { display: none; }
        }

        /* ── Utility / Components ─────────────────────── */

        /* Card */
        .card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }
        .card-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-title {
            font-size: 0.88rem; font-weight: 700;
            color: var(--text-primary);
        }
        .card-body { padding: 1.25rem; }

        /* Table */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table thead th {
            padding: 0.7rem 1rem;
            text-align: left;
            font-size: 0.68rem; font-weight: 700;
            letter-spacing: 0.1em; text-transform: uppercase;
            color: var(--text-muted);
            background: var(--surface);
            border-bottom: 1px solid var(--border);
        }
        .data-table tbody td {
            padding: 0.85rem 1rem;
            font-size: 0.82rem;
            color: var(--text-secondary);
            border-bottom: 1px solid var(--border);
        }
        .data-table tbody tr:last-child td { border-bottom: none; }
        .data-table tbody tr { transition: background 0.12s; }
        .data-table tbody tr:hover td {
            background: var(--surface);
            color: var(--text-primary);
        }

        /* Badge */
        .badge {
            display: inline-flex; align-items: center; gap: 0.3rem;
            padding: 0.22rem 0.65rem; border-radius: 100px;
            font-size: 0.68rem; font-weight: 700;
            letter-spacing: 0.03em; text-transform: uppercase;
        }
        .badge::before {
            content: ''; width: 5px; height: 5px; border-radius: 50%;
        }
        .badge-green  { background: var(--green-bg);  color: var(--green-d);  border: 1px solid var(--green-mid); }
        .badge-green::before  { background: var(--green); }
        .badge-blue   { background: var(--blue-bg);   color: #1D4ED8;         border: 1px solid #BFDBFE; }
        .badge-blue::before   { background: var(--blue); }
        .badge-amber  { background: var(--amber-bg);  color: var(--amber);    border: 1px solid #FDE68A; }
        .badge-amber::before  { background: var(--amber); }
        .badge-red    { background: var(--red-bg);    color: var(--red);      border: 1px solid #FEC9C7; }
        .badge-red::before    { background: var(--red); }
        .badge-slate  { background: var(--surface-2); color: var(--text-secondary); border: 1px solid var(--border); }
        .badge-slate::before  { background: var(--border-2); }

        /* Buttons */
        .btn {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.55rem 1.1rem;
            border-radius: var(--radius);
            font-family: var(--font-ui);
            font-size: 0.78rem; font-weight: 600;
            letter-spacing: 0.01em;
            cursor: pointer; border: none;
            transition: all 0.15s;
            text-decoration: none;
        }
        .btn-primary {
            background: var(--green);
            color: white;
            box-shadow: 0 1px 2px rgba(45,122,79,0.25);
        }
        .btn-primary:hover {
            background: var(--green-d);
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(45,122,79,0.3);
        }
        .btn-ghost {
            background: var(--white);
            color: var(--text-secondary);
            border: 1px solid var(--border);
        }
        .btn-ghost:hover {
            background: var(--surface);
            border-color: var(--border-2);
            color: var(--text-primary);
        }
        .btn-danger {
            background: var(--red-bg);
            color: var(--red);
            border: 1px solid #FEC9C7;
        }
        .btn-danger:hover {
            background: var(--red);
            color: white;
            border-color: var(--red);
        }
        .btn-sm { padding: 0.35rem 0.75rem; font-size: 0.72rem; }

        /* Stat card */
        .stat-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.25rem;
            box-shadow: var(--shadow-sm);
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .stat-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-1px);
        }
        .stat-icon {
            width: 40px; height: 40px;
            border-radius: var(--radius);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; margin-bottom: 1rem;
        }
        .stat-icon.green { background: var(--green-bg); }
        .stat-icon.amber { background: var(--amber-bg); }
        .stat-icon.red   { background: var(--red-bg); }
        .stat-icon.blue  { background: var(--blue-bg); }

        .stat-val {
            font-family: var(--font-display);
            font-size: 2rem; font-weight: 700;
            color: var(--text-primary); line-height: 1;
        }
        .stat-label {
            font-size: 0.75rem; color: var(--text-muted); margin-top: 0.3rem;
        }
        .stat-trend { font-size: 0.72rem; margin-top: 0.5rem; font-weight: 600; }
        .stat-trend.up   { color: var(--green); }
        .stat-trend.down { color: var(--red); }

        /* Form elements */
        .form-label {
            display: block;
            font-size: 0.75rem; font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 0.4rem;
            letter-spacing: 0.02em;
        }
        .form-input, .form-select, .form-textarea {
            width: 100%;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 0.6rem 0.85rem;
            color: var(--text-primary);
            font-family: var(--font-ui);
            font-size: 0.85rem;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: var(--green);
            box-shadow: 0 0 0 3px rgba(45,122,79,0.1);
        }
        .form-input::placeholder { color: var(--text-muted); }
        .form-select { appearance: none; }
        .form-textarea { resize: vertical; min-height: 90px; }
        .form-group { margin-bottom: 1.1rem; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

        /* Alert */
        .alert {
            padding: 0.85rem 1rem;
            border-radius: var(--radius);
            font-size: 0.82rem;
            margin-bottom: 1.2rem;
            border-left: 3px solid;
        }
        .alert-success {
            background: var(--green-bg);
            border-color: var(--green);
            color: var(--green-d);
        }
        .alert-error {
            background: var(--red-bg);
            border-color: var(--red);
            color: var(--red);
        }
        .alert-info {
            background: var(--blue-bg);
            border-color: var(--blue);
            color: #1D4ED8;
        }

        /* Divider between nav groups */
        .nav-divider {
            height: 1px;
            background: var(--border);
            margin: 0.6rem 1.25rem;
        }
    </style>
</head>
<body>
<div class="app-shell">

    {{-- ═══ SIDEBAR ═══ --}}
    <aside class="sidebar" id="sidebar">

        {{-- Logo --}}
        <div class="sidebar-logo">
            <span class="logo-text"><img src="{{ asset('storage/brand.png') }}" alt="brand"></span>
            <span class="logo-badge">Admin</span>
        </div>

        {{-- Navigation --}}
        <nav class="sidebar-nav">

            <div class="nav-group-label">Utama</div>

            <a href="{{ route('admin.dashboard') }}"
               class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="nav-icon">⊞</span>
                Dashboard
            </a>

            <div class="nav-group-label">Manajemen</div>

            <a href="{{ route('admin.users.index') }}"
               class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <span class="nav-icon">👥</span>
                Manajemen User
            </a>

            <a href="{{ route('admin.alats.index') }}"
               class="nav-item {{ request()->routeIs('admin.alats.*') ? 'active' : '' }}">
                <span class="nav-icon">🛠</span>
                Manajemen Alat
                @php $stokHabis = \App\Models\Alat::where('stok_tersedia', 0)->count(); @endphp
                @if($stokHabis > 0)
                    <span class="nav-badge danger">{{ $stokHabis }}</span>
                @endif
            </a>

            <a href="{{ route('admin.kategoris.index') }}"
               class="nav-item {{ request()->routeIs('admin.kategoris.*') ? 'active' : '' }}">
                <span class="nav-icon">🏷</span>
                Kategori
            </a>

            <div class="nav-group-label">Transaksi</div>

            <a href="{{ route('admin.peminjamans.index') }}"
               class="nav-item {{ request()->routeIs('admin.peminjamans.*') ? 'active' : '' }}">
                <span class="nav-icon">📋</span>
                Data Peminjaman
                @php $pending = \App\Models\Peminjaman::where('status','menunggu')->count(); @endphp
                @if($pending > 0)
                    <span class="nav-badge">{{ $pending }}</span>
                @endif
            </a>

            <a href="{{ route('admin.pengembalians.index') }}"
               class="nav-item {{ request()->routeIs('admin.pengembalians.*') ? 'active' : '' }}">
                <span class="nav-icon">↩</span>
                Pengembalian
            </a>

            <div class="nav-group-label">Sistem</div>

            <a href="{{ route('admin.log-aktivitas.index') }}"
               class="nav-item {{ request()->routeIs('admin.log.*') ? 'active' : '' }}">
                <span class="nav-icon">📝</span>
                Log Aktivitas
            </a>

        </nav>

        {{-- User profile & logout --}}
        <div class="sidebar-footer">
            <div class="user-row">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="user-info">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">Administrator</div>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <span>⎋</span> Keluar dari Akun
                </button>
            </form>
        </div>
    </aside>

    {{-- Mobile overlay --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    {{-- ═══ MAIN CONTENT ═══ --}}
    <div class="main-wrap">

        {{-- Topbar --}}
        <header class="topbar">
            <button class="sidebar-toggle" onclick="toggleSidebar()" aria-label="Toggle menu">
                ☰
            </button>

            {{-- Breadcrumb --}}
            <nav class="breadcrumb">
                <span>SewaAlat</span>
                <span class="breadcrumb-sep">›</span>
                <span>Admin</span>
                @isset($breadcrumb)
                    <span class="breadcrumb-sep">›</span>
                    <span class="breadcrumb-current">{{ $breadcrumb }}</span>
                @endisset
            </nav>

            {{-- Right side --}}
            <div class="topbar-right">
                <div class="topbar-search">
                    <span class="search-icon">⌕</span>
                    <input type="text" placeholder="Cari alat, user...">
                </div>

                <a href="#" class="icon-btn" title="Notifikasi">
                    🔔
                    @php $notifCount = \App\Models\Peminjaman::where('status','menunggu')->count(); @endphp
                    @if($notifCount > 0)
                        <span class="badge-dot"></span>
                    @endif
                </a>

                <a href="{{ route('admin.log-aktivitas.index') }}" class="icon-btn" title="Log Aktivitas">
                    📋
                </a>
            </div>
        </header>

        {{-- Page content --}}
        <main class="page-content">

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="alert alert-success">✓ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">✕ {{ session('error') }}</div>
            @endif
            @if(session('info'))
                <div class="alert alert-info">ℹ {{ session('info') }}</div>
            @endif

            {{-- Page header --}}
            @isset($header)
                <div class="page-header">
                    {{ $header }}
                </div>
            @endisset

            {{-- Main slot --}}
            {{ $slot }}

        </main>
    </div>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.toggle('open');
        overlay.classList.toggle('open');
        document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebarOverlay').classList.remove('open');
            document.body.style.overflow = '';
        }
    });

    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(el => {
            el.style.transition = 'opacity 0.5s, transform 0.5s';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-6px)';
            setTimeout(() => el.remove(), 500);
        });
    }, 4000);
</script>
</body>
</html>