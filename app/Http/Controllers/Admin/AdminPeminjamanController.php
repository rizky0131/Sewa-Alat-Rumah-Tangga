<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\LogAktivitas;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\WhatsappService;
use Illuminate\View\View;

class AdminPeminjamanController extends Controller
{
    // ─────────────────────────────────────────────
    //  INDEX
    // ─────────────────────────────────────────────
    public function index(Request $request): View
    {
        $query = Peminjaman::with([
            'peminjam:id,name,email,no_hp',
            'alat:id,nama,kode,foto',
            'petugas:id,name',
        ])->latest();

        // Filters
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_pinjam', 'like', "%{$search}%")
                    ->orWhereHas('peminjam', fn($u) => $u->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('alat',     fn($a) => $a->where('nama', 'like', "%{$search}%"));
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($peminjam = $request->input('peminjam')) {
            $query->where('peminjam_id', $peminjam);
        }

        if ($alat = $request->input('alat')) {
            $query->where('alat_id', $alat);
        }

        if ($request->input('terlambat')) {
            $query->terlambat();
        }

        $peminjamans = $query->paginate(15)->withQueryString();

        $stats = [
            'total'        => Peminjaman::count(),
            'menunggu'     => Peminjaman::where('status', 'menunggu')->count(),
            'dipinjam'     => Peminjaman::where('status', 'dipinjam')->count(),
            'terlambat'    => Peminjaman::terlambat()->count(),
            'dikembalikan' => Peminjaman::where('status', 'dikembalikan')->count(),
        ];

        return view('admin.peminjamans.index', compact('peminjamans', 'stats'));
    }

    // ─────────────────────────────────────────────
    //  CREATE  —  Admin membuat peminjaman manual
    // ─────────────────────────────────────────────
    public function create(): View
    {
        $peminjams = User::where('role', User::ROLE_PEMINJAM)->orderBy('name')->get(['id', 'name', 'email']);
        $alats     = Alat::tersedia()->with('kategori:id,nama,ikon')->orderBy('nama')->get(['id', 'nama', 'kode', 'foto', 'harga_sewa_per_hari', 'denda_per_hari', 'stok_tersedia', 'kategori_id']);

        return view('admin.peminjamans.create', compact('peminjams', 'alats'));
    }

    // ─────────────────────────────────────────────
    //  STORE
    // ─────────────────────────────────────────────
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'peminjam_id'             => ['required', 'exists:users,id'],
            'alat_id'                 => ['required', 'exists:alats,id'],
            'jumlah'                  => ['required', 'integer', 'min:1'],
            'tanggal_pinjam'          => ['required', 'date'],
            'tanggal_kembali_rencana' => ['required', 'date', 'after_or_equal:tanggal_pinjam'],
            'tujuan_peminjaman'       => ['nullable', 'string', 'max:500'],
            'status'                  => ['required', Rule::in(['menunggu', 'disetujui', 'dipinjam'])],
            'catatan_petugas'         => ['nullable', 'string', 'max:500'],
        ], [
            'peminjam_id.required'  => 'Peminjam wajib dipilih.',
            'alat_id.required'      => 'Alat wajib dipilih.',
            'jumlah.min'            => 'Jumlah minimal 1.',
            'tanggal_kembali_rencana.after_or_equal' => 'Tanggal kembali harus sesudah tanggal pinjam.',
        ]);

        $alat = Alat::findOrFail($validated['alat_id']);

        if ($alat->stok_tersedia < $validated['jumlah']) {
            return back()->withInput()
                ->with('error', "Stok alat \"{$alat->nama}\" tidak mencukupi. Tersedia: {$alat->stok_tersedia} unit.");
        }

        // Calculate total biaya
        $durasi = \Carbon\Carbon::parse($validated['tanggal_pinjam'])
            ->diffInDays(\Carbon\Carbon::parse($validated['tanggal_kembali_rencana']));
        $validated['total_biaya'] = $alat->hitungBiaya($durasi, $validated['jumlah']);

        // If admin directly sets status to disetujui/dipinjam, reduce stock
        if (in_array($validated['status'], ['disetujui', 'dipinjam'])) {
            $alat->kurangiStok($validated['jumlah']);
            $validated['petugas_id']  = auth()->id();
            $validated['disetujui_at'] = now();
        }

        $peminjaman = Peminjaman::create($validated);

        LogAktivitas::catat(
            aksi: LogAktivitas::AKSI_CRUD_PEMINJAMAN,
            modul: 'Peminjaman',
            deskripsi: "Peminjaman dibuat oleh admin: {$peminjaman->nomor_pinjam}",
            subject: $peminjaman,
            dataBaru: $peminjaman->only('nomor_pinjam', 'status', 'total_biaya'),
        );

        return redirect()
            ->route('admin.peminjamans.show', $peminjaman)
            ->with('success', "Peminjaman {$peminjaman->nomor_pinjam} berhasil dibuat.");
    }

    // ─────────────────────────────────────────────
    //  SHOW
    // ─────────────────────────────────────────────
    public function show(Peminjaman $peminjaman): View
    {
        $peminjaman->load([
            'peminjam',
            'alat.kategori:id,nama,ikon',
            'petugas:id,name',
            'pengembalian',
        ]);

        return view('admin.peminjamans.show', compact('peminjaman'));
    }

    // ─────────────────────────────────────────────
    //  EDIT
    // ─────────────────────────────────────────────
    public function edit(Peminjaman $peminjaman): View
    {
        // Only allow editing if still menunggu or disetujui
        if (in_array($peminjaman->status, ['dikembalikan'])) {
            return redirect()
                ->route('admin.peminjamans.show', $peminjaman)
                ->with('error', 'Peminjaman yang sudah dikembalikan tidak dapat diedit.');
        }

        $peminjaman->load(['peminjam', 'alat.kategori:id,nama,ikon', 'petugas:id,name']);

        return view('admin.peminjamans.edit', compact('peminjaman'));
    }

    // ─────────────────────────────────────────────
    //  UPDATE
    // ─────────────────────────────────────────────
    public function update(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        $validated = $request->validate([
            'tanggal_kembali_rencana' => ['required', 'date', 'after_or_equal:' . $peminjaman->tanggal_pinjam->toDateString()],
            'tujuan_peminjaman'       => ['nullable', 'string', 'max:500'],
            'catatan_petugas'         => ['nullable', 'string', 'max:500'],
        ]);

        $before = $peminjaman->only('tanggal_kembali_rencana', 'catatan_petugas');

        // Recalculate biaya if tanggal changed
        $durasi = $peminjaman->tanggal_pinjam->diffInDays($validated['tanggal_kembali_rencana']);
        $validated['total_biaya'] = $peminjaman->alat->hitungBiaya($durasi, $peminjaman->jumlah);

        $peminjaman->update($validated);

        LogAktivitas::catat(
            aksi: LogAktivitas::AKSI_CRUD_PEMINJAMAN,
            modul: 'Peminjaman',
            deskripsi: "Peminjaman diperbarui: {$peminjaman->nomor_pinjam}",
            subject: $peminjaman,
            dataLama: $before,
            dataBaru: $peminjaman->only('tanggal_kembali_rencana', 'total_biaya'),
        );

        return redirect()
            ->route('admin.peminjamans.show', $peminjaman)
            ->with('success', "Peminjaman {$peminjaman->nomor_pinjam} berhasil diperbarui.");
    }

    // ─────────────────────────────────────────────
    //  SETUJUI  —  Approve
    // ─────────────────────────────────────────────


    public function setujui(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status !== Peminjaman::STATUS_MENUNGGU) {
            return back()->with('error', 'Hanya peminjaman dengan status "Menunggu" yang dapat disetujui.');
        }

        try {
            $peminjaman->setujui(auth()->user(), $request->input('catatan'));
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        LogAktivitas::catat(
            aksi: LogAktivitas::AKSI_SETUJUI_PEMINJAMAN,
            modul: 'Peminjaman',
            deskripsi: "Peminjaman disetujui: {$peminjaman->nomor_pinjam}",
            subject: $peminjaman,
        );

        // ── Kirim WA notifikasi ───────────────────────
        $peminjaman->load(['peminjam', 'alat']);
        $peminjam = $peminjaman->peminjam;
        if ($peminjam?->no_hp) {
            $pesan = "✅ *Halo, {$peminjam->name}!*\n\n"
                . "Pengajuan sewa alat Anda telah *DISETUJUI* 🎉\n\n"
                . "📋 *Detail Peminjaman:*\n"
                . "• No. Transaksi  : {$peminjaman->nomor_pinjam}\n"
                . "• Alat           : {$peminjaman->alat->nama}\n"
                . "• Jumlah         : {$peminjaman->jumlah} unit\n"
                . "• Tanggal Pinjam : {$peminjaman->tanggal_pinjam->format('d M Y')}\n"
                . "• Rencana Kembali: {$peminjaman->tanggal_kembali_rencana->format('d M Y')}\n"
                . "• Total Biaya    : Rp " . number_format($peminjaman->total_biaya, 0, ',', '.') . "\n\n"
                . "Silakan ambil alat di Toko kami sesuai tanggal yang ditentukan.\n\n"
                . "_SewaAlat — Terima kasih_ 🙏";

            WhatsappService::send($peminjam->no_hp, $pesan);
        }
        // ─────────────────────────────────────────────

        return back()->with('success', "Peminjaman {$peminjaman->nomor_pinjam} berhasil disetujui.");
    }

    public function tolak(Request $request, Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status !== Peminjaman::STATUS_MENUNGGU) {
            return back()->with('error', 'Hanya peminjaman dengan status "Menunggu" yang dapat ditolak.');
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
            deskripsi: "Peminjaman ditolak: {$peminjaman->nomor_pinjam}",
            subject: $peminjaman,
        );

        // ── Kirim WA notifikasi ───────────────────────
        $peminjaman->load(['peminjam', 'alat']);
        $peminjam = $peminjaman->peminjam;
        if ($peminjam?->no_hp) {
            $pesan = "❌ *Halo, {$peminjam->name}.*\n\n"
                . "Maaf, pengajuan sewa alat Anda *DITOLAK*.\n\n"
                . "📋 *Detail:*\n"
                . "• No. Transaksi : {$peminjaman->nomor_pinjam}\n"
                . "• Alat          : {$peminjaman->alat->nama}\n"
                . "• Alasan        : {$request->input('catatan')}\n\n"
                . "Silakan hubungi petugas jika ada pertanyaan.\n\n"
                . "_SewaAlat_ 🙏";

            WhatsappService::send($peminjam->no_hp, $pesan);
        }
        // ─────────────────────────────────────────────

        return back()->with('success', "Peminjaman {$peminjaman->nomor_pinjam} telah ditolak.");
    }

    // ─────────────────────────────────────────────
    //  TANDAI DIPINJAM  —  Hand over
    // ─────────────────────────────────────────────
    public function tandaiDipinjam(Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status !== Peminjaman::STATUS_DISETUJUI) {
            return back()->with('error', 'Hanya peminjaman yang sudah disetujui yang dapat ditandai sebagai dipinjam.');
        }

        $peminjaman->tandaiDipinjam();

        LogAktivitas::catat(
            aksi: LogAktivitas::AKSI_CRUD_PEMINJAMAN,
            modul: 'Peminjaman',
            deskripsi: "Alat diserahkan ke peminjam: {$peminjaman->nomor_pinjam}",
            subject: $peminjaman,
        );

        return back()->with('success', "Alat telah ditandai sebagai dipinjam.");
    }

    // ─────────────────────────────────────────────
    //  DESTROY  —  soft delete
    // ─────────────────────────────────────────────
    public function destroy(Peminjaman $peminjaman): RedirectResponse
    {
        if (in_array($peminjaman->status, ['disetujui', 'dipinjam'])) {
            return back()->with('error', 'Peminjaman aktif tidak dapat dihapus.');
        }

        $nomor = $peminjaman->nomor_pinjam;

        LogAktivitas::catat(
            aksi: LogAktivitas::AKSI_CRUD_PEMINJAMAN,
            modul: 'Peminjaman',
            deskripsi: "Peminjaman dihapus: {$nomor}",
            dataLama: $peminjaman->only('nomor_pinjam', 'status'),
        );

        $peminjaman->delete();

        return redirect()
            ->route('admin.peminjamans.index')
            ->with('success', "Peminjaman {$nomor} berhasil dihapus.");
    }
}
