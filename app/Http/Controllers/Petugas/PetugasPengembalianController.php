<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PetugasPengembalianController extends Controller
{
    // ─────────────────────────────────────────────
    //  INDEX — pantau pengembalian
    // ─────────────────────────────────────────────
    public function index(Request $request): View
    {
        $tab = $request->input('tab', 'aktif'); // aktif | terlambat | selesai

        // ── Dipinjam (belum kembali) ─────────────
        $aktifQuery = Peminjaman::with([
            'peminjam:id,name,email',
            'alat:id,nama,kode,foto,denda_per_hari',
            'petugas:id,name',
        ])->where('status', Peminjaman::STATUS_DIPINJAM);

        // ── Sudah kembali ─────────────────────────
        $selesaiQuery = Pengembalian::with([
            'peminjaman.peminjam:id,name',
            'peminjaman.alat:id,nama,kode,foto',
            'petugas:id,name',
        ])->latest();

        // Search
        if ($search = $request->input('search')) {
            $aktifQuery->where(function ($q) use ($search) {
                $q->where('nomor_pinjam', 'like', "%{$search}%")
                  ->orWhereHas('peminjam', fn($u) => $u->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('alat', fn($a) => $a->where('nama', 'like', "%{$search}%"));
            });
            $selesaiQuery->whereHas('peminjaman', function ($q) use ($search) {
                $q->where('nomor_pinjam', 'like', "%{$search}%")
                  ->orWhereHas('peminjam', fn($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        // Tab filter
        if ($tab === 'terlambat') {
            $aktifQuery->where('tanggal_kembali_rencana', '<', now()->toDateString());
        }

        $aktif    = ($tab !== 'selesai') ? $aktifQuery->orderBy('tanggal_kembali_rencana')->paginate(12)->withQueryString() : collect();
        $selesai  = ($tab === 'selesai') ? $selesaiQuery->paginate(15)->withQueryString() : collect();

        $counts = [
            'aktif'     => Peminjaman::where('status', 'dipinjam')->count(),
            'terlambat' => Peminjaman::terlambat()->count(),
            'selesai'   => Pengembalian::count(),
            'hari_ini'  => Pengembalian::whereDate('created_at', today())->count(),
        ];

        return view('petugas.pengembalians.index', compact('aktif', 'selesai', 'counts', 'tab'));
    }

    // ─────────────────────────────────────────────
    //  SHOW — detail pengembalian atau detail pinjaman aktif
    // ─────────────────────────────────────────────
    public function show(Pengembalian $pengembalian): View
    {
        $pengembalian->load([
            'peminjaman.peminjam',
            'peminjaman.alat.kategori:id,nama',
            'petugas:id,name',
        ]);

        return view('petugas.pengembalians.show', compact('pengembalian'));
    }

    // ─────────────────────────────────────────────
    //  SHOW AKTIF — detail peminjaman yang belum kembali
    // ─────────────────────────────────────────────
    public function showAktif(Peminjaman $peminjaman): View
    {
        abort_unless($peminjaman->status === Peminjaman::STATUS_DIPINJAM, 404);

        $peminjaman->load([
            'peminjam',
            'alat.kategori:id,nama',
            'petugas:id,name',
        ]);

        return view('petugas.pengembalians.show-aktif', compact('peminjaman'));
    }

    // ─────────────────────────────────────────────
    //  STORE — proses pengembalian dari panel petugas
    // ─────────────────────────────────────────────
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'peminjaman_id'          => ['required', 'exists:peminjamans,id', Rule::unique('pengembalians', 'peminjaman_id')],
            'tanggal_kembali_aktual' => ['required', 'date'],
            'kondisi_kembali'        => ['required', Rule::in(['baik','rusak_ringan','rusak_sedang','rusak_berat','hilang'])],
            'biaya_kerusakan'        => ['required', 'numeric', 'min:0'],
            'catatan'                => ['nullable', 'string', 'max:1000'],
            'foto_bukti'             => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ], [
            'peminjaman_id.unique' => 'Peminjaman ini sudah memiliki data pengembalian.',
            'kondisi_kembali.required' => 'Kondisi alat wajib dipilih.',
        ]);

        $peminjaman = Peminjaman::with('alat')->findOrFail($validated['peminjaman_id']);

        if ($peminjaman->status !== Peminjaman::STATUS_DIPINJAM) {
            return back()->withInput()->with('error', 'Peminjaman tidak berstatus "Dipinjam".');
        }

        $validated['petugas_id'] = auth()->id();

        if ($request->hasFile('foto_bukti')) {
            $validated['foto_bukti'] = $request->file('foto_bukti')
                ->store('pengembalians', 'public');
        }

        $pengembalian = Pengembalian::create($validated);

        LogAktivitas::catat(
            aksi: LogAktivitas::AKSI_PANTAU_PENGEMBALIAN,
            modul: 'Pengembalian',
            deskripsi: "Pengembalian dicatat oleh petugas: {$peminjaman->nomor_pinjam}",
            subject: $pengembalian,
        );

        return redirect()
            ->route('petugas.pengembalians.show', $pengembalian)
            ->with('success', "Pengembalian {$peminjaman->nomor_pinjam} berhasil dicatat.");
    }
}