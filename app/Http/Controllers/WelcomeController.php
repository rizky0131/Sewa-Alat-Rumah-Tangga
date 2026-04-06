<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Kategori;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WelcomeController extends Controller
{
    public function index(): View
    {
        // Featured alat — aktif, stok tersedia, sorted by most borrowed
        $alatUnggulan = Alat::with('kategori:id,nama,ikon')
            ->where('status', 'aktif')
            ->where('stok_tersedia', '>', 0)
            ->withCount('peminjamans')
            ->orderByDesc('peminjamans_count')
            ->take(8)
            ->get();

        // Kategoris with at least 1 active alat
        $kategoris = Kategori::where('is_aktif', true)
            ->withCount(['alats' => fn($q) => $q->where('status','aktif')->where('stok_tersedia','>',0)])
            ->having('alats_count', '>', 0)
            ->orderByDesc('alats_count')
            ->take(6)
            ->get();

        // Live stats
        $stats = [
            'total_alat'     => Alat::where('status','aktif')->count(),
            'total_kategori' => Kategori::where('is_aktif',true)->count(),
            'total_pinjam'   => Peminjaman::whereIn('status',['dipinjam','dikembalikan'])->count(),
            'tersedia'       => Alat::where('status','aktif')->where('stok_tersedia','>',0)->count(),
        ];

        return view('welcome', compact('alatUnggulan','kategoris','stats'));
    }
    public function dashboard(): View
    {
        // Featured alat — aktif, stok tersedia, sorted by most borrowed
        $alatUnggulan = Alat::with('kategori:id,nama,ikon')
            ->where('status', 'aktif')
            ->where('stok_tersedia', '>', 0)
            ->withCount('peminjamans')
            ->orderByDesc('peminjamans_count')
            ->take(8)
            ->get();

        // Kategoris with at least 1 active alat
        $kategoris = Kategori::where('is_aktif', true)
            ->withCount(['alats' => fn($q) => $q->where('status','aktif')->where('stok_tersedia','>',0)])
            ->having('alats_count', '>', 0)
            ->orderByDesc('alats_count')
            ->take(6)
            ->get();

        // Live stats
        $stats = [
            'total_alat'     => Alat::where('status','aktif')->count(),
            'total_kategori' => Kategori::where('is_aktif',true)->count(),
            'total_pinjam'   => Peminjaman::whereIn('status',['dipinjam','dikembalikan'])->count(),
            'tersedia'       => Alat::where('status','aktif')->where('stok_tersedia','>',0)->count(),
        ];

        return view('peminjam.dashboard', compact('alatUnggulan','kategoris','stats'));
    }

    public function katalog(Request $request): View
    {
        $query = Alat::with('kategori:id,nama,ikon')
            ->where('status','aktif')
            ->withCount('peminjamans');

        if ($kategori = $request->input('kategori')) {
            $query->whereHas('kategori', fn($q) => $q->where('slug', $kategori));
        }

        if ($search = $request->input('q')) {
            $query->where(fn($q) => $q
                ->where('nama', 'like', "%{$search}%")
                ->orWhere('merk', 'like', "%{$search}%")
                ->orWhere('kode', 'like', "%{$search}%")
            );
        }

        if ($request->input('tersedia')) {
            $query->where('stok_tersedia', '>', 0);
        }

        $sort = $request->input('sort', 'populer');
        match ($sort) {
            'harga_asc'  => $query->orderBy('harga_sewa_per_hari'),
            'harga_desc' => $query->orderByDesc('harga_sewa_per_hari'),
            'nama'       => $query->orderBy('nama'),
            default      => $query->orderByDesc('peminjamans_count'),
        };

        $alats     = $query->paginate(12)->withQueryString();
        $kategoris = Kategori::where('is_aktif',true)->withCount(['alats' => fn($q) => $q->where('status','aktif')])->orderByDesc('alats_count')->get();

        return view('katalog', compact('alats','kategoris','sort'));
    }
}