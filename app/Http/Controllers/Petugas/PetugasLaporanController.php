<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\LogAktivitas;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PetugasLaporanController extends Controller
{
    // ─────────────────────────────────────────────
    //  INDEX — pilih laporan yang ingin dicetak
    // ─────────────────────────────────────────────
    public function index(): View
    {
        // Summary stats for the overview cards
        $stats = [
            'total_peminjaman'    => Peminjaman::count(),
            'aktif'               => Peminjaman::where('status', 'dipinjam')->count(),
            'terlambat'           => Peminjaman::terlambat()->count(),
            'total_pengembalian'  => Pengembalian::count(),
            'total_denda'         => Pengembalian::sum('total_tagihan'),
            'total_alat'          => Alat::count(),
            'total_peminjam'      => User::where('role', 'peminjam')->count(),
            'bulan_ini_pinjam'    => Peminjaman::whereMonth('created_at', now()->month)
                                               ->whereYear('created_at', now()->year)->count(),
            'bulan_ini_kembali'   => Pengembalian::whereMonth('created_at', now()->month)
                                                  ->whereYear('created_at', now()->year)->count(),
        ];

        return view('petugas.laporan.index', compact('stats'));
    }

    // ─────────────────────────────────────────────
    //  PEMINJAMAN — laporan daftar peminjaman
    // ─────────────────────────────────────────────
    public function peminjaman(Request $request): View
    {
        $dari   = $request->input('dari',  now()->startOfMonth()->toDateString());
        $sampai = $request->input('sampai', now()->toDateString());
        $status = $request->input('status', 'semua');

        $query = Peminjaman::with([
            'peminjam:id,name,email',
            'alat:id,nama,kode',
            'petugas:id,name',
        ])
        ->whereBetween('created_at', [
            Carbon::parse($dari)->startOfDay(),
            Carbon::parse($sampai)->endOfDay(),
        ])
        ->latest();

        if ($status !== 'semua') {
            $query->where('status', $status);
        }

        $peminjamans = $query->get();

        $ringkasan = [
            'total'       => $peminjamans->count(),
            'menunggu'    => $peminjamans->where('status','menunggu')->count(),
            'disetujui'   => $peminjamans->where('status','disetujui')->count(),
            'dipinjam'    => $peminjamans->where('status','dipinjam')->count(),
            'dikembalikan'=> $peminjamans->where('status','dikembalikan')->count(),
            'ditolak'     => $peminjamans->where('status','ditolak')->count(),
            'total_biaya' => $peminjamans->sum('total_biaya'),
        ];

        LogAktivitas::catat(
            aksi: LogAktivitas::AKSI_CETAK_LAPORAN,
            modul: 'Laporan',
            deskripsi: "Cetak laporan peminjaman {$dari} s/d {$sampai}",
        );

        return view('petugas.laporan.peminjaman', compact('peminjamans','ringkasan','dari','sampai','status'));
    }

    // ─────────────────────────────────────────────
    //  PENGEMBALIAN — laporan pengembalian & denda
    // ─────────────────────────────────────────────
    public function pengembalian(Request $request): View
    {
        $dari   = $request->input('dari',  now()->startOfMonth()->toDateString());
        $sampai = $request->input('sampai', now()->toDateString());

        $pengembalians = Pengembalian::with([
            'peminjaman.peminjam:id,name',
            'peminjaman.alat:id,nama,kode',
            'petugas:id,name',
        ])
        ->whereBetween('created_at', [
            Carbon::parse($dari)->startOfDay(),
            Carbon::parse($sampai)->endOfDay(),
        ])
        ->latest()
        ->get();

        $ringkasan = [
            'total'        => $pengembalians->count(),
            'tepat_waktu'  => $pengembalians->filter(fn($p) => $p->is_tepat_waktu)->count(),
            'terlambat'    => $pengembalians->filter(fn($p) => !$p->is_tepat_waktu)->count(),
            'kondisi_ok'   => $pengembalians->where('kondisi_kembali','baik')->count(),
            'kondisi_rusak'=> $pengembalians->filter(fn($p) => $p->is_rusak)->count(),
            'total_denda'  => $pengembalians->sum('denda'),
            'total_kerusakan' => $pengembalians->sum('biaya_kerusakan'),
            'total_tagihan'=> $pengembalians->sum('total_tagihan'),
        ];

        LogAktivitas::catat(
            aksi: LogAktivitas::AKSI_CETAK_LAPORAN,
            modul: 'Laporan',
            deskripsi: "Cetak laporan pengembalian {$dari} s/d {$sampai}",
        );

        return view('petugas.laporan.pengembalian', compact('pengembalians','ringkasan','dari','sampai'));
    }

    // ─────────────────────────────────────────────
    //  ALAT — laporan inventaris & penggunaan alat
    // ─────────────────────────────────────────────
    public function alat(Request $request): View
    {
        $dari   = $request->input('dari',  now()->startOfMonth()->toDateString());
        $sampai = $request->input('sampai', now()->toDateString());

        $alats = Alat::with(['kategori:id,nama', 'peminjamans' => function ($q) use ($dari, $sampai) {
            $q->whereBetween('created_at', [
                Carbon::parse($dari)->startOfDay(),
                Carbon::parse($sampai)->endOfDay(),
            ]);
        }])
        ->withCount(['peminjamans as total_dipinjam' => function ($q) use ($dari, $sampai) {
            $q->whereBetween('created_at', [
                Carbon::parse($dari)->startOfDay(),
                Carbon::parse($sampai)->endOfDay(),
            ]);
        }])
        ->orderByDesc('total_dipinjam')
        ->get();

        $ringkasan = [
            'total_alat'     => $alats->count(),
            'aktif'          => $alats->where('status','aktif')->count(),
            'nonaktif'       => $alats->where('status','nonaktif')->count(),
            'baik'           => $alats->where('kondisi','baik')->count(),
            'bermasalah'     => $alats->whereNotIn('kondisi',['baik'])->count(),
            'total_stok'     => $alats->sum('stok_total'),
            'stok_tersedia'  => $alats->sum('stok_tersedia'),
            'total_peminjaman'=> $alats->sum('total_dipinjam'),
        ];

        LogAktivitas::catat(
            aksi: LogAktivitas::AKSI_CETAK_LAPORAN,
            modul: 'Laporan',
            deskripsi: "Cetak laporan inventaris alat {$dari} s/d {$sampai}",
        );

        return view('petugas.laporan.alat', compact('alats','ringkasan','dari','sampai'));
    }

    // ─────────────────────────────────────────────
    //  REKAP BULANAN — ringkasan per bulan
    // ─────────────────────────────────────────────
    public function rekap(Request $request): View
    {
        $tahun = (int) $request->input('tahun', now()->year);

        $bulanData = collect(range(1, 12))->map(function ($bulan) use ($tahun) {
            $pinjam   = Peminjaman::whereYear('created_at', $tahun)->whereMonth('created_at', $bulan)->count();
            $kembali  = Pengembalian::whereYear('created_at', $tahun)->whereMonth('created_at', $bulan)->count();
            $denda    = Pengembalian::whereYear('created_at', $tahun)->whereMonth('created_at', $bulan)->sum('total_tagihan');
            $terlambat= Pengembalian::whereYear('created_at', $tahun)->whereMonth('created_at', $bulan)->where('keterlambatan_hari','>',0)->count();

            return compact('bulan','pinjam','kembali','denda','terlambat');
        });

        $ringkasan = [
            'total_pinjam'   => $bulanData->sum('pinjam'),
            'total_kembali'  => $bulanData->sum('kembali'),
            'total_denda'    => $bulanData->sum('denda'),
            'total_terlambat'=> $bulanData->sum('terlambat'),
            'bulan_tersibuk' => $bulanData->sortByDesc('pinjam')->first(),
        ];

        $tahunTersedia = collect(range(now()->year - 3, now()->year))->reverse()->values();

        LogAktivitas::catat(
            aksi: LogAktivitas::AKSI_CETAK_LAPORAN,
            modul: 'Laporan',
            deskripsi: "Cetak rekap bulanan tahun {$tahun}",
        );

        return view('petugas.laporan.rekap', compact('bulanData','ringkasan','tahun','tahunTersedia'));
    }
}