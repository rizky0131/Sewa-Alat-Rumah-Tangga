<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminAlatController extends Controller
{
    // ─────────────────────────────────────────────
    //  INDEX
    // ─────────────────────────────────────────────
    public function index(Request $request): View
    {
        $query = Alat::with('kategori:id,nama,ikon')
            ->withCount('peminjamans')
            ->withCount(['peminjamans as peminjaman_aktif_count' => fn($q) =>
                $q->whereIn('status', ['disetujui', 'dipinjam'])
            ]);

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama',  'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%")
                  ->orWhere('merk', 'like', "%{$search}%");
            });
        }

        // Filter: kategori
        if ($kat = $request->input('kategori')) {
            $query->where('kategori_id', $kat);
        }

        // Filter: status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Filter: kondisi
        if ($kondisi = $request->input('kondisi')) {
            $query->where('kondisi', $kondisi);
        }

        // Filter: tersedia
        if ($request->input('tersedia')) {
            $query->where('stok_tersedia', '>', 0);
        }

        $alats = $query->latest()->paginate(12)->withQueryString();

        $kategoris = Kategori::aktif()->orderBy('nama')->get(['id', 'nama', 'ikon']);

        $stats = [
            'total'     => Alat::count(),
            'aktif'     => Alat::where('status', 'aktif')->count(),
            'tersedia'  => Alat::where('stok_tersedia', '>', 0)->count(),
            'habis'     => Alat::where('stok_tersedia', 0)->count(),
            'rusak'     => Alat::whereIn('kondisi', ['rusak_ringan', 'rusak_berat', 'perbaikan'])->count(),
        ];

        return view('admin.alats.index', compact('alats', 'kategoris', 'stats'));
    }

    // ─────────────────────────────────────────────
    //  CREATE
    // ─────────────────────────────────────────────
    public function create(Request $request): View
    {
        $kategoris = Kategori::aktif()->orderBy('nama')->get(['id', 'nama', 'ikon']);
        $selectedKategori = $request->input('kategori');

        // Auto-generate next kode
        $last  = Alat::withTrashed()->orderByDesc('id')->value('kode');
        $nextNum = $last
            ? (intval(substr($last, 4)) + 1)   // strip "ALT-"
            : 1;
        $suggestedKode = 'ALT-' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);

        return view('admin.alats.create', compact('kategoris', 'selectedKategori', 'suggestedKode'));
    }

    // ─────────────────────────────────────────────
    //  STORE
    // ─────────────────────────────────────────────
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kategori_id'          => ['required', 'exists:kategoris,id'],
            'kode'                 => ['required', 'string', 'max:30', 'unique:alats,kode', 'regex:/^[A-Za-z0-9\-]+$/'],
            'nama'                 => ['required', 'string', 'max:200'],
            'deskripsi'            => ['nullable', 'string', 'max:2000'],
            'merk'                 => ['nullable', 'string', 'max:100'],
            'foto'                 => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'stok_total'           => ['required', 'integer', 'min:1'],
            'stok_tersedia'        => ['required', 'integer', 'min:0', 'lte:stok_total'],
            'harga_sewa_per_hari'  => ['required', 'numeric', 'min:0'],
            'denda_per_hari'       => ['required', 'numeric', 'min:0'],
            'kondisi'              => ['required', Rule::in(['baik', 'rusak_ringan', 'rusak_berat', 'perbaikan'])],
            'status'               => ['required', Rule::in(['aktif', 'nonaktif'])],
        ], [
            'kategori_id.required'  => 'Kategori wajib dipilih.',
            'kode.required'         => 'Kode alat wajib diisi.',
            'kode.unique'           => 'Kode alat sudah digunakan.',
            'kode.regex'            => 'Kode hanya boleh huruf, angka, dan tanda hubung.',
            'nama.required'         => 'Nama alat wajib diisi.',
            'stok_total.min'        => 'Stok total minimal 1.',
            'stok_tersedia.lte'     => 'Stok tersedia tidak boleh melebihi stok total.',
            'harga_sewa_per_hari.min' => 'Harga sewa tidak boleh negatif.',
            'foto.image'            => 'File harus berupa gambar.',
            'foto.max'              => 'Ukuran foto maksimal 2 MB.',
        ]);

        // Handle photo upload
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('alats', 'public');
        }

        $alat = Alat::create($validated);

        LogAktivitas::catat(
            aksi: LogAktivitas::AKSI_CRUD_ALAT,
            modul: 'Alat',
            deskripsi: "Alat baru ditambahkan: {$alat->nama} ({$alat->kode})",
            subject: $alat,
            dataBaru: $alat->only('kode', 'nama', 'stok_total', 'harga_sewa_per_hari'),
        );

        return redirect()
            ->route('admin.alats.show', $alat)
            ->with('success', "Alat \"{$alat->nama}\" berhasil ditambahkan.");
    }

    // ─────────────────────────────────────────────
    //  SHOW
    // ─────────────────────────────────────────────
    public function show(Alat $alat): View
    {
        $alat->load('kategori:id,nama,ikon,slug');
        $alat->loadCount([
            'peminjamans',
            'peminjamans as peminjaman_aktif_count'   => fn($q) => $q->whereIn('status', ['disetujui', 'dipinjam']),
            'peminjamans as peminjaman_selesai_count' => fn($q) => $q->where('status', 'dikembalikan'),
        ]);

        $riwayat = $alat->peminjamans()
            ->with('peminjam:id,name,email')
            ->latest()
            ->take(10)
            ->get();

        // Related alats in same category
        $related = Alat::where('kategori_id', $alat->kategori_id)
            ->where('id', '!=', $alat->id)
            ->limit(4)->get(['id', 'nama', 'kode', 'foto', 'stok_tersedia', 'status']);

        return view('admin.alats.show', compact('alat', 'riwayat', 'related'));
    }

    // ─────────────────────────────────────────────
    //  EDIT
    // ─────────────────────────────────────────────
    public function edit(Alat $alat): View
    {
        $kategoris = Kategori::aktif()->orderBy('nama')->get(['id', 'nama', 'ikon']);
        return view('admin.alats.edit', compact('alat', 'kategoris'));
    }

    // ─────────────────────────────────────────────
    //  UPDATE
    // ─────────────────────────────────────────────
    public function update(Request $request, Alat $alat): RedirectResponse
    {
        $validated = $request->validate([
            'kategori_id'         => ['required', 'exists:kategoris,id'],
            'kode'                => ['required', 'string', 'max:30', Rule::unique('alats', 'kode')->ignore($alat->id), 'regex:/^[A-Za-z0-9\-]+$/'],
            'nama'                => ['required', 'string', 'max:200'],
            'deskripsi'           => ['nullable', 'string', 'max:2000'],
            'merk'                => ['nullable', 'string', 'max:100'],
            'foto'                => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'stok_total'          => ['required', 'integer', 'min:1'],
            'stok_tersedia'       => ['required', 'integer', 'min:0', 'lte:stok_total'],
            'harga_sewa_per_hari' => ['required', 'numeric', 'min:0'],
            'denda_per_hari'      => ['required', 'numeric', 'min:0'],
            'kondisi'             => ['required', Rule::in(['baik', 'rusak_ringan', 'rusak_berat', 'perbaikan'])],
            'status'              => ['required', Rule::in(['aktif', 'nonaktif'])],
        ], [
            'kode.unique'        => 'Kode sudah digunakan alat lain.',
            'stok_tersedia.lte'  => 'Stok tersedia tidak boleh melebihi stok total.',
            'foto.max'           => 'Ukuran foto maksimal 2 MB.',
        ]);

        $before = $alat->only('kode', 'nama', 'stok_total', 'stok_tersedia', 'harga_sewa_per_hari', 'status');

        // Handle photo upload / deletion
        if ($request->hasFile('foto')) {
            if ($alat->foto) Storage::disk('public')->delete($alat->foto);
            $validated['foto'] = $request->file('foto')->store('alats', 'public');
        }

        if ($request->boolean('hapus_foto') && $alat->foto) {
            Storage::disk('public')->delete($alat->foto);
            $validated['foto'] = null;
        }

        $alat->update($validated);

        LogAktivitas::catat(
            aksi: LogAktivitas::AKSI_CRUD_ALAT,
            modul: 'Alat',
            deskripsi: "Data alat diperbarui: {$alat->nama}",
            subject: $alat,
            dataLama: $before,
            dataBaru: $alat->only('kode', 'nama', 'stok_total', 'stok_tersedia', 'harga_sewa_per_hari', 'status'),
        );

        return redirect()
            ->route('admin.alats.show', $alat)
            ->with('success', "Alat \"{$alat->nama}\" berhasil diperbarui.");
    }

    // ─────────────────────────────────────────────
    //  DESTROY  — soft delete
    // ─────────────────────────────────────────────
    public function destroy(Alat $alat): RedirectResponse
    {
        $aktif = $alat->peminjamans()->whereIn('status', ['disetujui', 'dipinjam'])->count();
        if ($aktif > 0) {
            return back()->with('error', "Alat \"{$alat->nama}\" masih memiliki {$aktif} peminjaman aktif.");
        }

        $nama = $alat->nama;

        LogAktivitas::catat(
            aksi: LogAktivitas::AKSI_CRUD_ALAT,
            modul: 'Alat',
            deskripsi: "Alat dihapus (soft): {$nama}",
            dataLama: $alat->only('kode', 'nama'),
        );

        $alat->delete();

        return redirect()
            ->route('admin.alats.index')
            ->with('success', "Alat \"{$nama}\" berhasil dihapus.");
    }
}