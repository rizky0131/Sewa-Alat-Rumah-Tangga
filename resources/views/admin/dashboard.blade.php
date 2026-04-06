<x-admin-layout title="Dashboard" breadcrumb="Dashboard">

    {{-- Page header slot --}}
    <x-slot name="header">
        <div>
            <h1 class="page-heading">Dashboard</h1>
            <p class="page-sub">Selamat datang, {{ auth()->user()->name }}. Berikut ringkasan hari ini.</p>
        </div>
        <a href="{{ route('admin.alats.create') }}" class="btn btn-primary">
            + Tambah Alat
        </a>
    </x-slot>

    {{-- Stat cards --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(37,99,235,0.15)">👥</div>
            <div class="stat-val">128</div>
            <div class="stat-label">Total User</div>
            <div class="stat-trend up">▲ +12 bulan ini</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(16,185,129,0.15)">🛠</div>
            <div class="stat-val">204</div>
            <div class="stat-label">Total Alat</div>
            <div class="stat-trend up">▲ 5 alat baru</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(212,168,67,0.15)">📋</div>
            <div class="stat-val">47</div>
            <div class="stat-label">Peminjaman Aktif</div>
            <div class="stat-trend">8 menunggu persetujuan</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(239,68,68,0.12)">⚠</div>
            <div class="stat-val">3</div>
            <div class="stat-label">Alat Terlambat</div>
            <div class="stat-trend down">▼ Perlu tindakan</div>
        </div>
    </div>

    {{-- Table card --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Peminjaman Terbaru</span>
            <a href="{{ route('admin.peminjamans.index') }}" class="btn btn-ghost btn-sm">Lihat Semua →</a>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Peminjam</th>
                    <th>Alat</th>
                    <th>Tanggal Pinjam</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Budi Santoso</td>
                    <td>Vacuum Cleaner</td>
                    <td>10 Mar 2025</td>
                    <td><span class="badge badge-amber">Menunggu</span></td>
                    <td>
                        <a href="#" class="btn btn-primary btn-sm">Setujui</a>
                        <a href="#" class="btn btn-danger btn-sm">Tolak</a>
                    </td>
                </tr>
                <tr>
                    <td>Siti Rahayu</td>
                    <td>Bor Listrik</td>
                    <td>09 Mar 2025</td>
                    <td><span class="badge badge-green">Aktif</span></td>
                    <td><a href="#" class="btn btn-ghost btn-sm">Detail</a></td>
                </tr>
            </tbody>
        </table>
    </div>

</x-admin-layout>


{{-- ─────────────────────────────────────────────────────────────── --}}
{{-- CONTOH PENGGUNAAN LAYOUT PETUGAS                                --}}
{{-- Simpan di: resources/views/petugas/dashboard.blade.php          --}}
{{-- ─────────────────────────────────────────────────────────────── --}}

