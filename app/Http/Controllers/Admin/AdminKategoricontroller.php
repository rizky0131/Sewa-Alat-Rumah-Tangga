<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminKategoriController extends Controller
{
    // ─────────────────────────────────────────────
    //  INDEX
    // ─────────────────────────────────────────────
    public function index(Request $request): View
    {
        $query = Kategori::withCount([
            'alats',
            'alats as alat_aktif_count'    => fn($q) => $q->where('status', 'aktif'),
            'alats as alat_nonaktif_count'  => fn($q) => $q->where('status', 'nonaktif'),
        ])->withSum('alats as total_stok', 'stok_tersedia');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($request->input('status') === 'aktif') {
            $query->where('is_aktif', true);
        } elseif ($request->input('status') === 'nonaktif') {
            $query->where('is_aktif', false);
        }

        $kategoris = $query->latest()->paginate(12)->withQueryString();

        $stats = [
            'total'    => Kategori::count(),
            'aktif'    => Kategori::where('is_aktif', true)->count(),
            'nonaktif' => Kategori::where('is_aktif', false)->count(),
            'total_alat' => \App\Models\Alat::count(),
        ];

        return view('admin.kategoris.index', compact('kategoris', 'stats'));
    }

    // ─────────────────────────────────────────────
    //  CREATE
    // ─────────────────────────────────────────────
    public function create(): View
    {
        $iconOptions = $this->iconOptions();
        return view('admin.kategoris.create', compact('iconOptions'));
    }

    // ─────────────────────────────────────────────
    //  STORE
    // ─────────────────────────────────────────────
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama'      => ['required', 'string', 'max:100'],
            'slug'      => ['nullable', 'string', 'max:120', 'unique:kategoris,slug', 'regex:/^[a-z0-9\-]+$/'],
            'ikon'      => ['nullable', 'string', 'max:20'],
            'deskripsi' => ['nullable', 'string', 'max:500'],
            'is_aktif'  => ['boolean'],
        ], [
            'nama.required'   => 'Nama kategori wajib diisi.',
            'nama.max'        => 'Nama kategori maksimal 100 karakter.',
            'slug.unique'     => 'Slug sudah digunakan kategori lain.',
            'slug.regex'      => 'Slug hanya boleh huruf kecil, angka, dan tanda hubung.',
        ]);

        // Auto-generate slug if empty
        $validated['slug']     = $validated['slug'] ?? Str::slug($validated['nama']);
        $validated['is_aktif'] = $request->boolean('is_aktif', true);

        $kategori = Kategori::create($validated);

        LogAktivitas::catat(
            aksi: LogAktivitas::AKSI_CRUD_KATEGORI,
            modul: 'Kategori',
            deskripsi: "Kategori baru ditambahkan: {$kategori->nama}",
            subject: $kategori,
            dataBaru: $kategori->only('nama', 'slug', 'is_aktif'),
        );

        return redirect()
            ->route('admin.kategoris.index')
            ->with('success', "Kategori \"{$kategori->nama}\" berhasil ditambahkan.");
    }

    // ─────────────────────────────────────────────
    //  SHOW
    // ─────────────────────────────────────────────
    public function show(Kategori $kategori): View
    {
        $kategori->loadCount([
            'alats',
            'alats as alat_aktif_count'    => fn($q) => $q->where('status', 'aktif'),
            'alats as alat_tersedia_count' => fn($q) => $q->where('stok_tersedia', '>', 0),
        ]);

        $alats = $kategori->alats()
            ->withCount('peminjamans')
            ->orderByDesc('peminjamans_count')
            ->paginate(8);

        return view('admin.kategoris.show', compact('kategori', 'alats'));
    }

    // ─────────────────────────────────────────────
    //  EDIT
    // ─────────────────────────────────────────────
    public function edit(Kategori $kategori): View
    {
        $iconOptions = $this->iconOptions();
        return view('admin.kategoris.edit', compact('kategori', 'iconOptions'));
    }

    // ─────────────────────────────────────────────
    //  UPDATE
    // ─────────────────────────────────────────────
    public function update(Request $request, Kategori $kategori): RedirectResponse
    {
        $validated = $request->validate([
            'nama'      => ['required', 'string', 'max:100'],
            'slug'      => ['nullable', 'string', 'max:120', Rule::unique('kategoris', 'slug')->ignore($kategori->id), 'regex:/^[a-z0-9\-]+$/'],
            'ikon'      => ['nullable', 'string', 'max:20'],
            'deskripsi' => ['nullable', 'string', 'max:500'],
            'is_aktif'  => ['boolean'],
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'slug.unique'   => 'Slug sudah digunakan kategori lain.',
            'slug.regex'    => 'Slug hanya boleh huruf kecil, angka, dan tanda hubung.',
        ]);

        $before = $kategori->only('nama', 'slug', 'ikon', 'deskripsi', 'is_aktif');

        $validated['slug']     = $validated['slug'] ?? Str::slug($validated['nama']);
        $validated['is_aktif'] = $request->boolean('is_aktif', false);

        $kategori->update($validated);

        LogAktivitas::catat(
            aksi: LogAktivitas::AKSI_CRUD_KATEGORI,
            modul: 'Kategori',
            deskripsi: "Kategori diperbarui: {$kategori->nama}",
            subject: $kategori,
            dataLama: $before,
            dataBaru: $kategori->only('nama', 'slug', 'ikon', 'deskripsi', 'is_aktif'),
        );

        return redirect()
            ->route('admin.kategoris.show', $kategori)
            ->with('success', "Kategori \"{$kategori->nama}\" berhasil diperbarui.");
    }

    // ─────────────────────────────────────────────
    //  DESTROY
    // ─────────────────────────────────────────────
    public function destroy(Kategori $kategori): RedirectResponse
    {
        $jumlahAlat = $kategori->alats()->count();
        if ($jumlahAlat > 0) {
            return back()->with('error', "Kategori \"{$kategori->nama}\" tidak bisa dihapus karena masih memiliki {$jumlahAlat} alat.");
        }

        $nama = $kategori->nama;

        LogAktivitas::catat(
            aksi: LogAktivitas::AKSI_CRUD_KATEGORI,
            modul: 'Kategori',
            deskripsi: "Kategori dihapus: {$nama}",
            dataLama: $kategori->only('nama', 'slug'),
        );

        $kategori->delete();

        return redirect()
            ->route('admin.kategoris.index')
            ->with('success', "Kategori \"{$nama}\" berhasil dihapus.");
    }

    // ─────────────────────────────────────────────
    //  AJAX — auto-generate slug
    // ─────────────────────────────────────────────
    public function generateSlug(Request $request)
    {
        return response()->json(['slug' => Str::slug($request->input('nama', ''))]);
    }

    // ─────────────────────────────────────────────
    //  PRIVATE — icon options list
    // ─────────────────────────────────────────────
    private function iconOptions(): array
    {
        return [
            '🍳' => 'Dapur / Masak',
            '🧹' => 'Kebersihan',
            '🔧' => 'Perkakas',
            '💡' => 'Elektronik',
            '🌿' => 'Taman / Kebun',
            '🚿' => 'Kamar Mandi',
            '🛏' => 'Kamar Tidur',
            '🪑' => 'Furnitur',
            '🎨' => 'Seni / Dekorasi',
            '📦' => 'Penyimpanan',
            '🔌' => 'Listrik',
            '🪣' => 'Perawatan Rumah',
            '🏠' => 'Umum',
            '⚙️' => 'Mesin',
            '🔨' => 'Konstruksi',
            '🧰' => 'Perlengkapan',
        ];
    }
}