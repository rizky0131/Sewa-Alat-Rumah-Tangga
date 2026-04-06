<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PengembalianController extends Controller
{
    // ─────────────────────────────────────────────
    //  CREATE — form lapor pengembalian
    // ─────────────────────────────────────────────
    public function create(Request $request): View
    {
        // Pre-select peminjaman from ?peminjaman=id
        $peminjaman = null;
        if ($id = $request->input('peminjaman')) {
            $peminjaman = Peminjaman::with(['alat.kategori:id,nama,ikon'])
                ->where('peminjam_id', auth()->id())   // must be owned by this user
                ->where('status', Peminjaman::STATUS_DIPINJAM)
                ->whereDoesntHave('pengembalian')      // not already returned
                ->find($id);
        }

        // All active borrows by this user that can be returned
        $peminjamansAktif = Peminjaman::with(['alat:id,nama,kode,foto,harga_sewa_per_hari,denda_per_hari,kategori_id'])
            ->where('peminjam_id', auth()->id())
            ->where('status', Peminjaman::STATUS_DIPINJAM)
            ->whereDoesntHave('pengembalian')
            ->orderBy('tanggal_kembali_rencana')
            ->get();

        return view('peminjam.pengembalians.create', compact('peminjaman', 'peminjamansAktif'));
    }

    // ─────────────────────────────────────────────
    //  STORE — simpan laporan pengembalian
    // ─────────────────────────────────────────────
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'peminjaman_id'          => [
                'required',
                'exists:peminjamans,id',
                Rule::unique('pengembalians', 'peminjaman_id'),
            ],
            'tanggal_kembali_aktual' => ['required', 'date'],
            'kondisi_kembali'        => ['required', Rule::in(['baik','rusak_ringan','rusak_sedang','rusak_berat','hilang'])],
            'catatan'                => ['nullable', 'string', 'max:1000'],
            'foto_bukti'             => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ], [
            'peminjaman_id.required' => 'Pilih peminjaman yang ingin dilaporkan.',
            'peminjaman_id.unique'   => 'Peminjaman ini sudah memiliki laporan pengembalian.',
            'kondisi_kembali.required' => 'Kondisi alat wajib dipilih.',
        ]);

        // Verify it belongs to auth user and is active
        $peminjaman = Peminjaman::with('alat')
            ->where('peminjam_id', auth()->id())
            ->where('status', Peminjaman::STATUS_DIPINJAM)
            ->findOrFail($validated['peminjaman_id']);

        // biaya_kerusakan is always 0 when submitted by peminjam;
        // petugas will adjust if needed via the admin panel.
        $validated['biaya_kerusakan'] = 0;
        // petugas_id left null — will be filled by petugas when they process it

        if ($request->hasFile('foto_bukti')) {
            $validated['foto_bukti'] = $request->file('foto_bukti')
                ->store('pengembalians', 'public');
        }

        $pengembalian = Pengembalian::create($validated);

        LogAktivitas::catat(
            aksi: LogAktivitas::AKSI_CRUD_PENGEMBALIAN,
            modul: 'Pengembalian',
            deskripsi: "Peminjam melaporkan pengembalian: {$peminjaman->nomor_pinjam}",
            subject: $pengembalian,
        );

        return redirect()
            ->route('peminjam.peminjamans.index')
            ->with('success', "Pengembalian untuk {$peminjaman->nomor_pinjam} berhasil dilaporkan. Petugas akan memverifikasi alat Anda.");
    }
}