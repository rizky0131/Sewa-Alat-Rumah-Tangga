<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} — Petugas · SewaAlat</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ── CSS Variables ────────────────────────────── */
        :root {
            --bg:           #F7FAF8;
            --bg-card:      #FFFFFF;
            --bg-sidebar:   #FFFFFF;

            --ink:          #111827;
            --ink-2:        #1F2937;
            --ink-3:        #374151;
            --muted:        #6B7280;
            --border:       #E5E7EB;
            --border-soft:  #F3F4F6;

            /* SewaAlat green palette */
            --green:        #1A7A4A;   /* deep brand green */
            --green-m:      #22A05A;   /* mid green */
            --green-l:      #2DBE6C;   /* accent */
            --green-pale:   #E8F8EE;   /* tinted bg */
            --green-ring:   rgba(34,160,90,0.18);

            --amber:        #D97706;
            --red:          #DC2626;
            --blue:         #2563EB;

            --sidebar-w:    256px;

            --font-display: 'DM Serif Display', Georgia, serif;
            --font-ui:      'Plus Jakarta Sans', system-ui, sans-serif;

            --shadow-sm:    0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md:    0 4px 16px rgba(0,0,0,0.07);
            --shadow-card:  0 2px 8px rgba(0,0,0,0.05), 0 0 0 1px rgba(0,0,0,0.04);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: var(--font-ui);
            background: var(--bg);
            color: var(--ink);
            min-height: 100vh;
        }

        /* ── Layout Shell ─────────────────────────────── */
        .app-shell { display: flex; min-height: 100vh; }

        /* ── Sidebar ──────────────────────────────────── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            position: fixed; top: 0; bottom: 0; left: 0;
            z-index: 50;
            transition: transform 0.3s ease;
            box-shadow: var(--shadow-sm);
        }

        /* Logo */
        .sidebar-logo {
            padding: 1.5rem 1.4rem 1.25rem;
            border-bottom: 1px solid var(--border-soft);
        }
        .logo-row {
            display: flex; align-items: center; gap: 0.65rem;
        }
        .logo-mark {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--green), var(--green-l));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; font-weight: 800; color: white;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(26,122,74,0.3);
        }
        .logo-text {
            font-family: var(--font-display);
            font-size: 1.25rem; font-weight: 400; color: var(--ink);
            letter-spacing: -0.01em;
        }
        .logo-text span { color: var(--green-m); }

        .petugas-chip {
            display: inline-flex; align-items: center; gap: 0.35rem;
            margin-top: 0.75rem;
            background: var(--green-pale);
            border: 1px solid rgba(34,160,90,0.2);
            border-radius: 100px; padding: 0.22rem 0.65rem;
        }
        .petugas-chip-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: var(--green-l);
            animation: pulse-dot 2s ease-in-out infinite;
        }
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.5; transform: scale(0.75); }
        }
        .petugas-chip-text {
            font-size: 0.62rem; font-weight: 700;
            letter-spacing: 0.1em; text-transform: uppercase;
            color: var(--green);
        }

        /* Nav group labels */
        .nav-group-label {
            font-size: 0.58rem; font-weight: 700;
            letter-spacing: 0.16em; text-transform: uppercase;
            color: var(--muted);
            padding: 1.1rem 1.3rem 0.35rem;
        }

        /* Nav */
        .sidebar-nav { flex: 1; padding: 0.25rem 0.75rem 1rem; overflow-y: auto; }
        .sidebar-nav::-webkit-scrollbar { width: 0; }

        .nav-item {
            display: flex; align-items: center; gap: 0.65rem;
            padding: 0.6rem 0.85rem;
            color: var(--muted);
            font-size: 0.83rem; font-weight: 500;
            text-decoration: none;
            transition: all 0.15s;
            border-radius: 8px;
            margin-bottom: 1px;
            position: relative;
        }
        .nav-item:hover {
            color: var(--ink-2);
            background: var(--border-soft);
        }
        .nav-item.active {
            color: var(--green);
            background: var(--green-pale);
            font-weight: 600;
        }
        .nav-icon {
            width: 18px; text-align: center; font-size: 0.95rem; flex-shrink: 0;
            opacity: 0.5; transition: opacity 0.15s;
        }
        .nav-item:hover .nav-icon { opacity: 0.8; }
        .nav-item.active .nav-icon { opacity: 1; }
        .nav-counter {
            margin-left: auto;
            background: var(--red);
            color: white;
            font-size: 0.6rem; font-weight: 700;
            padding: 0.1rem 0.42rem; border-radius: 100px;
            line-height: 1.6;
        }

        /* Divider */
        .nav-divider {
            height: 1px; background: var(--border-soft);
            margin: 0.5rem 0;
        }

        /* Sidebar footer */
        .sidebar-footer {
            padding: 1rem 0.75rem;
            border-top: 1px solid var(--border-soft);
        }
        .user-card {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 10px; padding: 0.8rem;
            margin-bottom: 0.6rem;
            display: flex; align-items: center; gap: 0.65rem;
        }
        .user-avatar {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--green), var(--green-l));
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.78rem; font-weight: 800; color: white;
            flex-shrink: 0;
        }
        .user-info { flex: 1; min-width: 0; }
        .user-name {
            font-size: 0.82rem; font-weight: 700; color: var(--ink);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .user-role-tag {
            font-size: 0.62rem; color: var(--green-m);
            letter-spacing: 0.06em; font-weight: 600; text-transform: uppercase;
        }
        .logout-link {
            display: flex; align-items: center; gap: 0.5rem;
            width: 100%; padding: 0.5rem 0.85rem;
            background: none; border: none; cursor: pointer;
            color: var(--muted);
            font-size: 0.78rem; font-weight: 500; font-family: var(--font-ui);
            border-radius: 8px; transition: all 0.15s;
        }
        .logout-link:hover { color: var(--red); background: #FEF2F2; }

        /* ── Main ─────────────────────────────────────── */
        .main-wrap {
            margin-left: var(--sidebar-w);
            flex: 1; min-height: 100vh;
            display: flex; flex-direction: column;
        }

        /* ── Topbar ───────────────────────────────────── */
        .topbar {
            position: sticky; top: 0; z-index: 40;
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: 0 2rem;
            height: 60px;
            display: flex; align-items: center; gap: 1rem;
            box-shadow: var(--shadow-sm);
        }

        .sidebar-toggle {
            display: none; background: none; border: none;
            color: var(--muted); font-size: 1.1rem; cursor: pointer; padding: 0.3rem;
        }

        /* Quick stats row in topbar */
        .topbar-stats {
            display: flex; gap: 0.4rem; align-items: center; flex: 1;
        }
        .topbar-stat {
            display: flex; align-items: center; gap: 0.4rem;
            background: var(--border-soft); border: 1px solid var(--border);
            border-radius: 8px; padding: 0.3rem 0.8rem;
            font-size: 0.74rem; font-weight: 500; color: var(--muted);
            text-decoration: none; transition: all 0.15s;
        }
        .topbar-stat:hover { border-color: var(--green-m); color: var(--ink); }
        .topbar-stat-num { font-weight: 800; color: var(--ink); }
        .topbar-stat.urgent { border-color: rgba(220,38,38,0.25); background: #FEF2F2; }
        .topbar-stat.urgent .topbar-stat-num { color: var(--red); }

        .topbar-right { display: flex; align-items: center; gap: 0.65rem; margin-left: auto; }
        .topbar-time { font-size: 0.74rem; color: var(--muted); font-weight: 500; }

        .icon-btn {
            width: 36px; height: 36px;
            background: var(--border-soft); border: 1px solid var(--border);
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            color: var(--muted); font-size: 0.88rem; cursor: pointer;
            transition: all 0.15s; text-decoration: none; position: relative;
        }
        .icon-btn:hover { background: var(--green-pale); border-color: var(--green-m); color: var(--green); }
        .badge-dot {
            position: absolute; top: 7px; right: 7px;
            width: 6px; height: 6px; background: var(--red);
            border-radius: 50%; border: 1.5px solid white;
        }

        /* ── Page Content ─────────────────────────────── */
        .page-content {
            flex: 1; padding: 2rem;
            max-width: 1400px; width: 100%;
        }

        /* Page header slot */
        .page-header {
            margin-bottom: 1.75rem;
            display: flex; align-items: flex-start;
            justify-content: space-between; gap: 1rem;
        }
        .page-heading {
            font-family: var(--font-display);
            font-size: 1.9rem; font-weight: 400;
            color: var(--ink); line-height: 1.2;
        }
        .page-sub {
            font-size: 0.8rem; color: var(--muted); margin-top: 0.2rem;
        }

        /* ── Utility classes ──────────────────────────── */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px; overflow: hidden;
            box-shadow: var(--shadow-card);
        }
        .card-header {
            padding: 1rem 1.3rem;
            border-bottom: 1px solid var(--border-soft);
            display: flex; align-items: center; justify-content: space-between;
            background: white;
        }
        .card-title { font-size: 0.88rem; font-weight: 700; color: var(--ink); }
        .card-body { padding: 1.3rem; }

        /* Table */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table thead th {
            padding: 0.65rem 1rem;
            text-align: left; font-size: 0.67rem;
            font-weight: 700; letter-spacing: 0.1em;
            text-transform: uppercase; color: var(--muted);
            background: var(--bg);
            border-bottom: 1px solid var(--border);
        }
        .data-table tbody td {
            padding: 0.85rem 1rem;
            font-size: 0.82rem; color: var(--ink-3);
            border-bottom: 1px solid var(--border-soft);
        }
        .data-table tbody tr:last-child td { border-bottom: none; }
        .data-table tbody tr { transition: background 0.12s; }
        .data-table tbody tr:hover td { background: var(--green-pale); }

        /* Badge */
        .badge {
            display: inline-flex; align-items: center; gap: 0.3rem;
            padding: 0.22rem 0.65rem; border-radius: 100px;
            font-size: 0.67rem; font-weight: 700;
            letter-spacing: 0.04em; text-transform: uppercase;
        }
        .badge::before { content: ''; width: 5px; height: 5px; border-radius: 50%; }
        .badge-green  { background: #DCFCE7; color: #15803D; }
        .badge-green::before  { background: #16A34A; }
        .badge-teal   { background: var(--green-pale); color: var(--green); }
        .badge-teal::before   { background: var(--green-m); }
        .badge-amber  { background: #FEF3C7; color: var(--amber); }
        .badge-amber::before  { background: var(--amber); }
        .badge-red    { background: #FEE2E2; color: var(--red); }
        .badge-red::before    { background: var(--red); }
        .badge-slate  { background: #F1F5F9; color: var(--muted); }
        .badge-slate::before  { background: #94A3B8; }
        .badge-blue   { background: #DBEAFE; color: var(--blue); }
        .badge-blue::before   { background: var(--blue); }

        /* Buttons */
        .btn {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.55rem 1.15rem; border-radius: 8px;
            font-family: var(--font-ui); font-size: 0.79rem; font-weight: 600;
            cursor: pointer; border: none; transition: all 0.15s;
            text-decoration: none; letter-spacing: 0.01em;
        }
        .btn-primary { background: var(--green); color: white; }
        .btn-primary:hover { background: var(--green-m); transform: translateY(-1px); box-shadow: 0 4px 14px rgba(26,122,74,0.28); }
        .btn-secondary { background: white; color: var(--ink-3); border: 1px solid var(--border); }
        .btn-secondary:hover { background: var(--green-pale); border-color: var(--green-m); color: var(--green); }
        .btn-success { background: var(--green-m); color: white; }
        .btn-success:hover { background: var(--green); }
        .btn-danger { background: white; color: var(--red); border: 1px solid rgba(220,38,38,0.2); }
        .btn-danger:hover { background: var(--red); color: white; }
        .btn-sm { padding: 0.35rem 0.8rem; font-size: 0.72rem; border-radius: 6px; }
        .btn-xs { padding: 0.22rem 0.55rem; font-size: 0.68rem; border-radius: 5px; }

        /* Stat card */
        .stat-card {
            background: white; border: 1px solid var(--border);
            border-radius: 12px; padding: 1.4rem;
            box-shadow: var(--shadow-card);
            position: relative; overflow: hidden;
        }
        .stat-card::after {
            content: ''; position: absolute;
            bottom: -20px; right: -20px;
            width: 80px; height: 80px;
            border-radius: 50%;
            opacity: 0.06;
        }
        .stat-card.teal::after   { background: var(--green); width: 90px; height: 90px; }
        .stat-card.amber::after  { background: var(--amber); }
        .stat-card.green::after  { background: #16A34A; }
        .stat-card.red::after    { background: var(--red); }
        .stat-card.blue::after   { background: var(--blue); }

        .stat-stripe {
            position: absolute; top: 0; left: 0; right: 0; height: 3px;
            border-radius: 12px 12px 0 0;
        }
        .stat-card.teal .stat-stripe   { background: linear-gradient(90deg, var(--green), var(--green-l)); }
        .stat-card.amber .stat-stripe  { background: var(--amber); }
        .stat-card.green .stat-stripe  { background: #16A34A; }
        .stat-card.red .stat-stripe    { background: var(--red); }
        .stat-card.blue .stat-stripe   { background: var(--blue); }

        .stat-icon {
            width: 42px; height: 42px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.15rem; margin-bottom: 1rem;
        }
        .stat-val {
            font-family: var(--font-display);
            font-size: 2.2rem; font-weight: 400; color: var(--ink); line-height: 1;
        }
        .stat-label { font-size: 0.75rem; color: var(--muted); margin-top: 0.3rem; }
        .stat-trend { font-size: 0.72rem; margin-top: 0.5rem; font-weight: 600; }
        .stat-trend.up   { color: var(--green-m); }
        .stat-trend.down { color: var(--red); }
        .stat-trend.warn { color: var(--amber); }

        /* Action panel */
        .action-panel { display: flex; gap: 0.5rem; align-items: center; }

        /* Form */
        .form-label {
            display: block; font-size: 0.75rem; font-weight: 700;
            color: var(--ink-3); margin-bottom: 0.4rem;
        }
        .form-input, .form-select, .form-textarea {
            width: 100%;
            background: white; border: 1px solid var(--border);
            border-radius: 8px; padding: 0.65rem 0.95rem;
            color: var(--ink); font-family: var(--font-ui); font-size: 0.85rem;
            outline: none; transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: var(--green-m);
            box-shadow: 0 0 0 3px var(--green-ring);
        }
        .form-textarea { resize: vertical; min-height: 90px; }
        .form-group { margin-bottom: 1.1rem; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .form-hint { font-size: 0.72rem; color: var(--muted); margin-top: 0.3rem; }
        .form-error { font-size: 0.72rem; color: var(--red); margin-top: 0.3rem; }

        /* Alert */
        .alert {
            padding: 0.85rem 1.1rem; border-radius: 8px;
            font-size: 0.82rem; margin-bottom: 1.2rem;
            display: flex; align-items: center; gap: 0.6rem;
            border: 1px solid;
        }
        .alert-success { background: #F0FDF4; border-color: #BBF7D0; color: #15803D; }
        .alert-error   { background: #FEF2F2; border-color: #FECACA; color: #DC2626; }
        .alert-info    { background: var(--green-pale); border-color: rgba(34,160,90,0.25); color: var(--green); }
        .alert-warning { background: #FFFBEB; border-color: #FDE68A; color: var(--amber); }

        /* Mobile sidebar overlay */
        .sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(17,24,39,0.4);
            backdrop-filter: blur(2px);
            z-index: 49;
        }
        .sidebar-overlay.open { display: block; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #D1D5DB; }

        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: 8px 0 32px rgba(0,0,0,0.12); }
            .main-wrap { margin-left: 0; }
            .sidebar-toggle { display: flex; }
            .topbar-stats { display: none; }
        }
    </style>
</head>
<body>
<div class="app-shell">

    {{-- ═══ SIDEBAR ═══ --}}
    <aside class="sidebar" id="sidebar">

        {{-- Logo --}}
        <div class="sidebar-logo">
            <div class="logo-row">
                <!-- <div class="logo-mark">PA</div> -->
                <span class="logo-text"><img src="{{ asset('storage/brand.png') }}" alt="brand"></span>
            </div>
            <div class="petugas-chip">
                <div class="petugas-chip-dot"></div>
                <span class="petugas-chip-text">Mode Petugas</span>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="sidebar-nav">

            <div class="nav-group-label">Utama</div>

            <a href="{{ route('petugas.dashboard') }}"
               class="nav-item {{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}">
                <span class="nav-icon">⊞</span>
                Dashboard
            </a>

            <div class="nav-divider"></div>
            <div class="nav-group-label">Tugas Utama</div>

            <a href="{{ route('petugas.peminjamans.index') }}"
               class="nav-item {{ request()->routeIs('petugas.peminjamans.*') ? 'active' : '' }}">
                <span class="nav-icon">✅</span>
                Setujui Peminjaman
                @php $pending = \App\Models\Peminjaman::where('status','menunggu')->count(); @endphp
                @if($pending > 0)
                    <span class="nav-counter">{{ $pending }}</span>
                @endif
            </a>

            <a href="{{ route('petugas.pengembalians.index') }}"
               class="nav-item {{ request()->routeIs('petugas.pengembalians.*') ? 'active' : '' }}">
                <span class="nav-icon">↩</span>
                Pantau Pengembalian
                @php $terlambat = \App\Models\Peminjaman::where('status','dipinjam')->where('tanggal_kembali_rencana','<',now()->toDateString())->count(); @endphp
                @if($terlambat > 0)
                    <span class="nav-counter">{{ $terlambat }}</span>
                @endif
            </a>

            <div class="nav-divider"></div>
            <div class="nav-group-label">Informasi</div>

            <!-- <a href="{{ route('petugas.alats.index') }}"
               class="nav-item {{ request()->routeIs('petugas.alats.*') ? 'active' : '' }}">
                <span class="nav-icon">🛠</span>
                Daftar Alat
            </a> -->

            <a href="{{ route('petugas.laporan.index') }}"
               class="nav-item {{ request()->routeIs('petugas.laporan.*') ? 'active' : '' }}">
                <span class="nav-icon">📊</span>
                Cetak Laporan
            </a>

        </nav>

        {{-- Footer --}}
        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="user-info">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role-tag">Petugas</div>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-link">
                    <span>⎋</span> Keluar
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
            <button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>

            {{-- Quick stats --}}
            <div class="topbar-stats">
                @php
                    $pendingCount   = \App\Models\Peminjaman::where('status','menunggu')->count();
                    $dipinjamCount  = \App\Models\Peminjaman::where('status','dipinjam')->count();
                    $terlambatCount = \App\Models\Peminjaman::where('status','dipinjam')
                        ->where('tanggal_kembali_rencana','<',now()->toDateString())->count();
                @endphp

                <a href="{{ route('petugas.peminjamans.index') }}" class="topbar-stat {{ $pendingCount > 0 ? 'urgent' : '' }}">
                    <span>⏳</span>
                    <span class="topbar-stat-num">{{ $pendingCount }}</span>
                    <span>Menunggu</span>
                </a>
                <a href="{{ route('petugas.pengembalians.index') }}" class="topbar-stat">
                    <span>📦</span>
                    <span class="topbar-stat-num">{{ $dipinjamCount }}</span>
                    <span>Dipinjam</span>
                </a>
                @if($terlambatCount > 0)
                    <a href="{{ route('petugas.pengembalians.index') }}" class="topbar-stat urgent">
                        <span>⚠️</span>
                        <span class="topbar-stat-num">{{ $terlambatCount }}</span>
                        <span>Terlambat</span>
                    </a>
                @endif
            </div>

            {{-- Right --}}
            <div class="topbar-right">
                <span class="topbar-time" id="topbarTime"></span>

                <a href="{{ route('petugas.laporan.index') }}" class="icon-btn" title="Cetak Laporan">
                    🖨
                </a>
                <a href="#" class="icon-btn" title="Notifikasi">
                    🔔
                    @if($pendingCount > 0 || $terlambatCount > 0)
                        <span class="badge-dot"></span>
                    @endif
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
            @if(session('warning'))
                <div class="alert alert-warning">⚠ {{ session('warning') }}</div>
            @endif
            @if(session('info'))
                <div class="alert alert-info">ℹ {{ session('info') }}</div>
            @endif

            {{-- Page header slot --}}
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
    // Live clock in topbar
    function updateClock() {
        const now = new Date();
        const opts = { weekday:'short', day:'numeric', month:'short', hour:'2-digit', minute:'2-digit' };
        const el = document.getElementById('topbarTime');
        if (el) el.textContent = now.toLocaleDateString('id-ID', opts);
    }
    updateClock();
    setInterval(updateClock, 30000);

    // Sidebar toggle
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

    // Auto-hide flash messages
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