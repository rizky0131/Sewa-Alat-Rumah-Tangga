<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\View\View;

class PetugasDashboardController extends Controller
{
    public function index(): View
    {
        // ── Antrian & status counts ───────────────────
        $counts = [
            'menunggu'          => Peminjaman::where('status', 'menunggu')->count(),
            'disetujui'         => Peminjaman::where('status', 'disetujui')->count(),
            'dipinjam'          => Peminjaman::where('status', 'dipinjam')->count(),
            'terlambat'         => Peminjaman::where('status', 'dipinjam')
                                    ->where('tanggal_kembali_rencana', '<', now()->toDateString())
                                    ->count(),
            'kembali_hari_ini'  => Pengembalian::whereDate('created_at', today())->count(),
            'kembali_minggu'    => Pengembalian::whereBetween('created_at', [
                                    now()->startOfWeek(), now(),
                                  ])->count(),
            'denda_minggu'      => Pengembalian::whereBetween('created_at', [
                                    now()->startOfWeek(), now(),
                                  ])->sum('total_tagihan'),
        ];

        // ── Antrian menunggu persetujuan ─────────────
        $pendingList = Peminjaman::with(['peminjam:id,name','alat:id,nama,kode'])
            ->where('status', Peminjaman::STATUS_MENUNGGU)
            ->latest()
            ->take(5)
            ->get();

        // ── Disetujui ────────────────────────────────
        $disetujuiList = Peminjaman::with(['peminjam:id,name','alat:id,nama,kode'])
            ->where('status', Peminjaman::STATUS_DISETUJUI)
            ->latest()
            ->take(5)
            ->get();

        // ── Terlambat ────────────────────────────────
        $terlambatList = Peminjaman::with(['peminjam:id,name','alat:id,nama,kode'])
            ->where('status', Peminjaman::STATUS_DIPINJAM)
            ->where('tanggal_kembali_rencana', '<', now()->toDateString())
            ->orderBy('tanggal_kembali_rencana')
            ->take(5)
            ->get();

        // ── Pengembalian hari ini ────────────────────
        $pengembalianHariIni = Pengembalian::with([
                'peminjaman.peminjam:id,name',
                'peminjaman.alat:id,nama',
            ])
            ->whereDate('created_at', today())
            ->latest()
            ->take(5)
            ->get();

        // ===============================
        // ✅ TAMBAHAN: CHART 7 HARI
        // ===============================
        $chartLabels  = [];
        $chartPinjam  = [];
        $chartKembali = [];

        for ($i = 6; $i >= 0; $i--) {
            $tgl = now()->subDays($i);

            $chartLabels[]  = $tgl->isoFormat('ddd D/M');
            $chartPinjam[]  = Peminjaman::whereDate('created_at', $tgl)->count();
            $chartKembali[] = Pengembalian::whereDate('created_at', $tgl)->count();
        }

        return view('petugas.dashboard', compact(
            'counts',
            'pendingList',
            'disetujuiList',
            'terlambatList',
            'pengembalianHariIni',
            'chartLabels',
            'chartPinjam',
            'chartKembali'
        ));
    }
}