<?php

use App\Http\Controllers\Peminjam\PeminjamanController;
use App\Http\Controllers\Petugas\PetugasDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminAlatController;
use App\Http\Controllers\Admin\AdminKategoriController;
use App\Http\Controllers\Admin\AdminPeminjamanController;
use App\Http\Controllers\Admin\AdminPengembalianController;
use App\Http\Controllers\Admin\AdminLogAktivitasController;
use App\Http\Controllers\Petugas\PetugasLaporanController;
use App\Http\Controllers\Petugas\PetugasPeminjamanController;
use App\Http\Controllers\Petugas\PetugasPengembalianController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use App\Models\User;



Route::get('/', [WelcomeController::class, 'index'])->name('home');
Route::get('/katalog', [WelcomeController::class, 'katalog'])->name('katalog');

// ── ADMIN ONLY ──────────────────────────────────────────────────
Route::middleware(['auth', 'role:' . User::ROLE_ADMIN])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');

    // CRUD User
    Route::resource('users', AdminUserController::class);

    // CRUD Alat
    Route::resource('alats', AdminAlatController::class);

    // CRUD Kategori
    Route::resource('kategoris', AdminKategoriController::class);

    // CRUD Peminjaman
    Route::resource('peminjamans', AdminPeminjamanController::class);
    Route::post(
        'peminjamans/{peminjaman}/setujui',
        [AdminPeminjamanController::class, 'setujui']
    )
        ->name('peminjamans.setujui');
    Route::post('peminjamans/{peminjaman}/tandai-dipinjam',
        [AdminPeminjamanController::class, 'tandaiDipinjam']
    )->name('peminjamans.tandaiDipinjam');
    Route::post(
        'peminjamans/{peminjaman}/tolak',
        [AdminPeminjamanController::class, 'tolak']
    )
        ->name('peminjamans.tolak');
    Route::post(
        'peminjamans/{peminjaman}/tandai',
        [AdminPeminjamanController::class, 'tandaiDipinjam']
    )
        ->name('peminjamans.tandai');

    // CRUD Pengembalian
    Route::resource('pengembalians', AdminPengembalianController::class);
    Route::get('pengembalians/hitung', [AdminPengembalianController::class, 'hitung'])->name('pengembalians.hitung');

    // Log Aktivitas (read-only)
    Route::resource('log-aktivitas', AdminLogAktivitasController::class)
        ->only(['index', 'show', 'create', 'store']);
    // Route::get('/log-aktivitas', [AdminLogAktivitasController::class, 'index'])->name('log.index');
    Route::post('log-aktivitas/purge', [AdminLogAktivitasController::class, 'purge'])
        ->name('log-aktivitas.purge');
});

// ── PETUGAS ─────────────────────────────────────────────────────
Route::middleware(['auth', 'role:' . User::ROLE_PETUGAS . ',' . User::ROLE_ADMIN])
    ->prefix('petugas')->name('petugas.')->group(function () {

        // Route::get('/dashboard', fn() => view('petugas.dashboard'))->name('dashboard');
        Route::get('/dashboard', [PetugasDashboardController::class, 'index'])->name('dashboard');

        // Setujui / tolak peminjaman
        Route::get('/peminjamans',              [PetugasPeminjamanController::class, 'index'])->name('peminjamans.index');
        Route::get('/peminjamans/{peminjaman}', [PetugasPeminjamanController::class, 'show'])
            ->name('peminjamans.show');
        Route::patch('/peminjamans/{peminjaman}/setujui', [PetugasPeminjamanController::class, 'setujui'])->name('peminjamans.setujui');
        Route::patch('/peminjamans/{peminjaman}/tolak',   [PetugasPeminjamanController::class, 'tolak'])->name('peminjamans.tolak');

        Route::patch('/peminjamans/{peminjaman}/tandai', [PetugasPeminjamanController::class, 'tandaiDipinjam'])
            ->name('peminjamans.tandai');

        // Pantau & catat pengembalian
        Route::get('/pengembalians',                     [PetugasPengembalianController::class, 'index'])->name('pengembalians.index');
        Route::get('/pengembalians/aktif/{peminjaman}', [PetugasPengembalianController::class, 'showAktif'])->name('pengembalians.aktif');
        Route::get('/pengembalians/create/{peminjaman}', [PetugasPengembalianController::class, 'create'])->name('pengembalians.create');
        Route::get('/pengembalians/{pengembalian}', [PetugasPengembalianController::class, 'show'])->name('pengembalians.show');
        Route::post('/pengembalians',                    [PetugasPengembalianController::class, 'store'])->name('pengembalians.store');

        // Cetak laporan
        Route::get('/laporan',                           [PetugasLaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/peminjaman',  [PetugasLaporanController::class, 'peminjaman'])->name('laporan.peminjaman');
        Route::get('/laporan/pengembalian', [PetugasLaporanController::class, 'pengembalian'])->name('laporan.pengembalian');
        Route::get('/laporan/alat',        [PetugasLaporanController::class, 'alat'])->name('laporan.alat');
        Route::get('/laporan/rekap',       [PetugasLaporanController::class, 'rekap'])->name('laporan.rekap');
        Route::get('/laporan/export-pdf',                [PetugasLaporanController::class, 'exportPdf'])->name('laporan.pdf');
        Route::get('/laporan/export-excel',              [PetugasLaporanController::class, 'exportExcel'])->name('laporan.excel');

        // Lihat daftar alat (read-only)
        Route::get('/alats',                             [\App\Http\Controllers\Petugas\AlatController::class, 'index'])->name('alats.index');
    });

// ── PEMINJAM ─────────────────────────────────────────────────────
Route::middleware(['auth', 'role:' . User::ROLE_PEMINJAM])->prefix('sewa')->name('peminjam.')->group(function () {

   Route::get('/dashboard', [WelcomeController::class, 'dashboard'])
    ->name('dashboard');

    // Lihat daftar alat
    Route::get('/alats',                             [\App\Http\Controllers\Peminjam\AlatController::class, 'index'])->name('alats.index');
    Route::get('/alats/{alat}',                      [\App\Http\Controllers\Peminjam\AlatController::class, 'show'])->name('alats.show');

    // Ajukan peminjaman
    Route::get('/peminjamans/create',                [PeminjamanController::class, 'create'])->name('peminjamans.create');
    Route::post('/peminjamans',                      [PeminjamanController::class, 'store'])->name('peminjamans.store');
    Route::get('/peminjamans',                       [PeminjamanController::class, 'index'])->name('peminjamans.index');
    Route::delete('/peminjamans/{peminjaman}',        [PeminjamanController::class, 'destroy'])->name('peminjamans.destroy');

    // Kembalikan alat
    Route::get('/pengembalians/create',              [\App\Http\Controllers\Peminjam\PengembalianController::class, 'create'])->name('pengembalians.create');
    Route::post('/pengembalians',                    [\App\Http\Controllers\Peminjam\PengembalianController::class, 'store'])->name('pengembalians.store');
});

// ── Redirect /dashboard based on role ────────────────────────────
Route::middleware('auth')->get('/dashboard', function () {
    return match (auth()->user()->role) {
        User::ROLE_ADMIN    => redirect()->route('admin.dashboard'),
        User::ROLE_PETUGAS  => redirect()->route('petugas.dashboard'),
        default             => redirect()->route('peminjam.dashboard'),
    };
})->name('dashboard');




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
