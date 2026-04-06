<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PetugasPeminjamanController extends Controller
{
    public function index(Request $request): View
    {
        // Active tab: menunggu (default) | dipinjam | semua
        $tab = $request->input('tab', 'menunggu');

        $query = Peminjaman::with([
            'peminjam:id,name,email',
            'alat:id,nama,kode,foto,harga_sewa_per_hari,denda_per_hari',
        ]);

        match ($tab) {
            'dipinjam' => $query->where('status', Peminjaman::STATUS_DIPINJAM),
            'disetujui'=> $query->where('status', Peminjaman::STATUS_DISETUJUI),
            'semua'    => $query->whereIn('status', ['menunggu','disetujui','dipinjam','ditolak']),
            default    => $query->where('status', Peminjaman::STATUS_MENUNGGU),
        };

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_pinjam', 'like', "%{$search}%")
                  ->orWhereHas('peminjam', fn($u) => $u->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('alat',     fn($a) => $a->where('nama', 'like', "%{$search}%"));
            });
        }

        $peminjamans = $query->latest()->paginate(12)->withQueryString();

        $counts = [
            'menunggu'  => Peminjaman::where('status', 'menunggu')->count(),
            'disetujui' => Peminjaman::where('status', 'disetujui')->count(),
            'dipinjam'  => Peminjaman::where('status', 'dipinjam')->count(),
            'terlambat' => Peminjaman::terlambat()->count(),
        ];

        return view('petugas.peminjamans.index', compact('peminjamans', 'counts', 'tab'));
    }

    public function show(Peminjaman $peminjaman): View
    {
        $peminjaman->load([
            'peminjam',
            'alat.kategori:id,nama',
            'petugas:id,name',
            'pengembalian',
        ]);

        return view('petugas.peminjamans.show', compact('peminjaman'));
    }

    public function setujui(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status !== Peminjaman::STATUS_MENUNGGU) {
            return back()->with('error', 'Hanya peminjaman berstatus "Menunggu" yang dapat disetujui.');
        }

        try {
            $peminjaman->setujui(auth()->user(), $request->input('catatan'));
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        LogAktivitas::catat(
            aksi: LogAktivitas::AKSI_SETUJUI_PEMINJAMAN,
            modul: 'Peminjaman',
            deskripsi: "Peminjaman disetujui oleh petugas: {$peminjaman->nomor_pinjam}",
            subject: $peminjaman,
        );

        return back()->with('success', "Peminjaman {$peminjaman->nomor_pinjam} berhasil disetujui.");
    }

    public function tolak(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status !== Peminjaman::STATUS_MENUNGGU) {
            return back()->with('error', 'Hanya peminjaman berstatus "Menunggu" yang dapat ditolak.');
        }

        $request->validate([
            'catatan' => ['required', 'string', 'max:500'],
        ], [
            'catatan.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $peminjaman->tolak(auth()->user(), $request->input('catatan'));

        LogAktivitas::catat(
            aksi: LogAktivitas::AKSI_TOLAK_PEMINJAMAN,
            modul: 'Peminjaman',
            deskripsi: "Peminjaman ditolak oleh petugas: {$peminjaman->nomor_pinjam}",
            subject: $peminjaman,
        );

        return back()->with('success', "Peminjaman {$peminjaman->nomor_pinjam} telah ditolak.");
    }

    public function tandaiDipinjam(Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status !== Peminjaman::STATUS_DISETUJUI) {
            return back()->with('error', 'Hanya peminjaman yang sudah disetujui yang dapat ditandai dipinjam.');
        }

        $peminjaman->tandaiDipinjam();

        LogAktivitas::catat(
            aksi: LogAktivitas::AKSI_SETUJUI_PEMINJAMAN,
            modul: 'Peminjaman',
            deskripsi: "Alat diserahkan ke peminjam: {$peminjaman->nomor_pinjam}",
            subject: $peminjaman,
        );

        return back()->with('success', "Alat telah ditandai sebagai dipinjam.");
    }
}