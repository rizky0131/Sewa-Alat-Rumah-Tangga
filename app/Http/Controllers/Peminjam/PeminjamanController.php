<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\LogAktivitas;
use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PeminjamanController extends Controller
{
    // ─────────────────────────────────────────────
    //  INDEX — riwayat peminjaman milik user ini
    // ─────────────────────────────────────────────
    public function index(Request $request): View
    {
        $query = Peminjaman::with(['alat.kategori:id,nama,ikon', 'petugas:id,name'])
            ->where('peminjam_id', auth()->id())
            ->latest();

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $peminjamans = $query->paginate(10)->withQueryString();

        $counts = [
            'semua'       => Peminjaman::where('peminjam_id', auth()->id())->count(),
            'menunggu'    => Peminjaman::where('peminjam_id', auth()->id())->where('status','menunggu')->count(),
            'dipinjam'    => Peminjaman::where('peminjam_id', auth()->id())->where('status','dipinjam')->count(),
            'dikembalikan'=> Peminjaman::where('peminjam_id', auth()->id())->where('status','dikembalikan')->count(),
        ];

        return view('peminjam.peminjamans.index', compact('peminjamans','counts'));
    }

    // ─────────────────────────────────────────────
    //  CREATE — form pengajuan sewa
    // ─────────────────────────────────────────────
    public function create(Request $request): View
    {
        // Pre-load alat if ?alat=id passed from katalog/welcome
        $alat = null;
        if ($alatId = $request->input('alat')) {
            $alat = Alat::with('kategori:id,nama,ikon')
                ->where('status', 'aktif')
                ->where('stok_tersedia', '>', 0)
                ->find($alatId);
        }

        // All available alat for the dropdown
        $alats = Alat::with('kategori:id,nama')
            ->where('status', 'aktif')
            ->where('stok_tersedia', '>', 0)
            ->orderBy('nama')
            ->get(['id','nama','kode','harga_sewa_per_hari','denda_per_hari','stok_tersedia','stok_total','foto','kategori_id']);

        return view('peminjam.peminjamans.create', compact('alat', 'alats'));
    }

    // ─────────────────────────────────────────────
    //  STORE — simpan pengajuan
    // ─────────────────────────────────────────────
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'alat_id'                => ['required', 'exists:alats,id'],
            'jumlah'                 => ['required', 'integer', 'min:1'],
            'tanggal_pinjam'         => ['required', 'date', 'after_or_equal:today'],
            'tanggal_kembali_rencana'=> ['required', 'date', 'after:tanggal_pinjam'],
            'tujuan_peminjaman'      => ['required', 'string', 'min:10', 'max:500'],
            'no_hp'                  => ['required', 'string', 'max:20'],
        ], [
            'alat_id.required'               => 'Pilih alat yang ingin dipinjam.',
            'jumlah.min'                     => 'Jumlah minimal 1 unit.',
            'tanggal_pinjam.after_or_equal'  => 'Tanggal pinjam tidak boleh lampau.',
            'tanggal_kembali_rencana.after'  => 'Tanggal kembali harus setelah tanggal pinjam.',
            'tujuan_peminjaman.min'          => 'Tujuan minimal 10 karakter.',
            'no_hp.required'                 => 'Nomor HP wajib diisi.',
        ]);

        $alat = Alat::findOrFail($validated['alat_id']);

        // Check stok
        if ($alat->stok_tersedia < $validated['jumlah']) {
            return back()->withInput()
                ->with('error', "Stok {$alat->nama} tidak mencukupi. Tersedia: {$alat->stok_tersedia} unit.");
        }

        // Simpan/update no_hp ke profil user  ← tambah blok ini
        $noHp = '+62' . ltrim($validated['no_hp'], '0');
        auth()->user()->update(['no_hp' => $noHp]);

        // Hitung total biaya
        $tglPinjam  = \Carbon\Carbon::parse($validated['tanggal_pinjam']);
        $tglKembali = \Carbon\Carbon::parse($validated['tanggal_kembali_rencana']);
        $durasi     = $tglPinjam->diffInDays($tglKembali);
        $totalBiaya = $alat->harga_sewa_per_hari * $validated['jumlah'] * $durasi;

        $peminjaman = Peminjaman::create([
            'peminjam_id'            => auth()->id(),
            'alat_id'                => $validated['alat_id'],
            'jumlah'                 => $validated['jumlah'],
            'tanggal_pinjam'         => $validated['tanggal_pinjam'],
            'tanggal_kembali_rencana'=> $validated['tanggal_kembali_rencana'],
            'total_biaya'            => $totalBiaya,
            'tujuan_peminjaman'      => $validated['tujuan_peminjaman'],
            'status'                 => Peminjaman::STATUS_MENUNGGU,
        ]);

        LogAktivitas::catat(
            aksi: LogAktivitas::AKSI_CRUD_PEMINJAMAN,
            modul: 'Peminjaman',
            deskripsi: "Pengajuan sewa baru: {$peminjaman->nomor_pinjam} — {$alat->nama}",
            subject: $peminjaman,
        );

        return redirect()
            ->route('peminjam.peminjamans.index')
            ->with('success', "Pengajuan {$peminjaman->nomor_pinjam} berhasil dikirim! Tunggu konfirmasi petugas.");
    }

    // ─────────────────────────────────────────────
    //  DESTROY — batalkan pengajuan (hanya menunggu)
    // ─────────────────────────────────────────────
    public function destroy(Peminjaman $peminjaman): RedirectResponse
    {
        // Pastikan milik user ini
        abort_unless($peminjaman->peminjam_id === auth()->id(), 403);

        if ($peminjaman->status !== Peminjaman::STATUS_MENUNGGU) {
            return back()->with('error', 'Hanya pengajuan berstatus "Menunggu" yang bisa dibatalkan.');
        }

        $nomor = $peminjaman->nomor_pinjam;
        $peminjaman->delete();

        return redirect()
            ->route('peminjam.peminjamans.index')
            ->with('success', "Pengajuan {$nomor} berhasil dibatalkan.");
    }
}