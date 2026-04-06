<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminPengembalianController extends Controller
{
    // ─────────────────────────────────────────────
    //  INDEX
    // ─────────────────────────────────────────────
    public function index(Request $request): View
    {
        $query = Pengembalian::with([
            'peminjaman.peminjam:id,name,email',
            'peminjaman.alat:id,nama,kode,foto',
            'petugas:id,name',
        ])->latest();

        if ($search = $request->input('search')) {
            $query->whereHas('peminjaman', function ($q) use ($search) {
                $q->where('nomor_pinjam', 'like', "%{$search}%")
                  ->orWhereHas('peminjam', fn($u) => $u->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('alat',     fn($a) => $a->where('nama', 'like', "%{$search}%"));
            });
        }

        if ($kondisi = $request->input('kondisi')) {
            $query->where('kondisi_kembali', $kondisi);
        }

        if ($request->input('terlambat')) {
            $query->where('keterlambatan_hari', '>', 0);
        }

        if ($request->input('rusak')) {
            $query->whereIn('kondisi_kembali', ['rusak_ringan','rusak_sedang','rusak_berat','hilang']);
        }

        $pengembalians = $query->paginate(15)->withQueryString();

        $stats = [
            'total'       => Pengembalian::count(),
            'tepat_waktu' => Pengembalian::where('keterlambatan_hari', 0)->count(),
            'terlambat'   => Pengembalian::where('keterlambatan_hari', '>', 0)->count(),
            'rusak'       => Pengembalian::whereIn('kondisi_kembali', ['rusak_ringan','rusak_sedang','rusak_berat','hilang'])->count(),
            'total_denda' => Pengembalian::sum('total_tagihan'),
        ];

        return view('admin.pengembalians.index', compact('pengembalians', 'stats'));
    }

    // ─────────────────────────────────────────────
    //  CREATE  — form proses pengembalian
    // ─────────────────────────────────────────────
    public function create(Request $request): View
    {
        // Pre-select peminjaman from query string (e.g. from show page)
        $peminjaman = null;
        if ($id = $request->input('peminjaman')) {
            $peminjaman = Peminjaman::with(['peminjam:id,name,email', 'alat.kategori:id,nama,ikon'])
                ->where('status', Peminjaman::STATUS_DIPINJAM)
                ->find($id);
        }

        // All active borrows that haven't been returned yet
        $peminjamansAktif = Peminjaman::with(['peminjam:id,name','alat:id,nama,kode'])
            ->where('status', Peminjaman::STATUS_DIPINJAM)
            ->orderBy('tanggal_kembali_rencana')
            ->get();

        return view('admin.pengembalians.create', compact('peminjaman', 'peminjamansAktif'));
    }

    // ─────────────────────────────────────────────
    //  STORE
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
            'peminjaman_id.unique'  => 'Peminjaman ini sudah memiliki data pengembalian.',
            'kondisi_kembali.required' => 'Kondisi kembali wajib dipilih.',
            'foto_bukti.max'        => 'Foto bukti maksimal 3 MB.',
        ]);

        // Verify peminjaman is in 'dipinjam' status
        $peminjaman = Peminjaman::with('alat')->findOrFail($validated['peminjaman_id']);
        if ($peminjaman->status !== Peminjaman::STATUS_DIPINJAM) {
            return back()->withInput()
                ->with('error', 'Peminjaman ini belum berstatus "Dipinjam".');
        }

        $validated['petugas_id'] = auth()->id();

        if ($request->hasFile('foto_bukti')) {
            $validated['foto_bukti'] = $request->file('foto_bukti')->store('pengembalians', 'public');
        }

        $pengembalian = Pengembalian::create($validated);

        LogAktivitas::catat(
            aksi: LogAktivitas::AKSI_CRUD_PENGEMBALIAN,
            modul: 'Pengembalian',
            deskripsi: "Pengembalian diproses: {$peminjaman->nomor_pinjam}",
            subject: $pengembalian,
            dataBaru: $pengembalian->only('kondisi_kembali', 'keterlambatan_hari', 'denda', 'total_tagihan'),
        );

        return redirect()
            ->route('admin.pengembalians.show', $pengembalian)
            ->with('success', "Pengembalian untuk {$peminjaman->nomor_pinjam} berhasil diproses.");
    }

    // ─────────────────────────────────────────────
    //  SHOW
    // ─────────────────────────────────────────────
    public function show(Pengembalian $pengembalian): View
    {
        $pengembalian->load([
            'peminjaman.peminjam',
            'peminjaman.alat.kategori:id,nama,ikon',
            'petugas:id,name',
        ]);

        return view('admin.pengembalians.show', compact('pengembalian'));
    }

    // ─────────────────────────────────────────────
    //  EDIT
    // ─────────────────────────────────────────────
    public function edit(Pengembalian $pengembalian): View
    {
        $pengembalian->load([
            'peminjaman.peminjam:id,name,email',
            'peminjaman.alat:id,nama,kode,foto,denda_per_hari,kategori_id',
        ]);

        return view('admin.pengembalians.edit', compact('pengembalian'));
    }

    // ─────────────────────────────────────────────
    //  UPDATE  — only notes, kondisi, biaya_kerusakan, foto
    // ─────────────────────────────────────────────
    public function update(Request $request, Pengembalian $pengembalian): RedirectResponse
    {
        $validated = $request->validate([
            'kondisi_kembali'  => ['required', Rule::in(['baik','rusak_ringan','rusak_sedang','rusak_berat','hilang'])],
            'biaya_kerusakan'  => ['required', 'numeric', 'min:0'],
            'catatan'          => ['nullable', 'string', 'max:1000'],
            'foto_bukti'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ]);

        $before = $pengembalian->only('kondisi_kembali','biaya_kerusakan','catatan');

        // Recalculate total_tagihan if biaya_kerusakan changed
        $validated['total_tagihan'] =
            (float) $pengembalian->denda +
            (float) ($validated['biaya_kerusakan'] ?? 0);

        // Handle photo
        if ($request->hasFile('foto_bukti')) {
            if ($pengembalian->foto_bukti) {
                Storage::disk('public')->delete($pengembalian->foto_bukti);
            }
            $validated['foto_bukti'] = $request->file('foto_bukti')->store('pengembalians', 'public');
        }

        if ($request->boolean('hapus_foto') && $pengembalian->foto_bukti) {
            Storage::disk('public')->delete($pengembalian->foto_bukti);
            $validated['foto_bukti'] = null;
        }

        $pengembalian->update($validated);

        LogAktivitas::catat(
            aksi: LogAktivitas::AKSI_CRUD_PENGEMBALIAN,
            modul: 'Pengembalian',
            deskripsi: "Data pengembalian diperbarui: {$pengembalian->peminjaman->nomor_pinjam}",
            subject: $pengembalian,
            dataLama: $before,
            dataBaru: $pengembalian->only('kondisi_kembali','biaya_kerusakan','total_tagihan'),
        );

        return redirect()
            ->route('admin.pengembalians.show', $pengembalian)
            ->with('success', 'Data pengembalian berhasil diperbarui.');
    }

    // ─────────────────────────────────────────────
    //  HITUNG (AJAX)  — preview denda sebelum simpan
    // ─────────────────────────────────────────────
    public function hitung(Request $request)
    {
        $peminjaman = Peminjaman::with('alat')->findOrFail($request->input('peminjaman_id'));
        $aktual     = \Carbon\Carbon::parse($request->input('tanggal_kembali_aktual'));
        $rencana    = $peminjaman->tanggal_kembali_rencana;

        $terlambat = $aktual->gt($rencana) ? (int) $rencana->diffInDays($aktual) : 0;
        $denda     = $terlambat * $peminjaman->alat->denda_per_hari * $peminjaman->jumlah;
        $total = (float)$denda + (float)$request->input('biaya_kerusakan', 0);

        return response()->json([
            'keterlambatan_hari' => $terlambat,
            'denda'              => $denda,
            'total_tagihan'      => $total,
        ]);
    }
}