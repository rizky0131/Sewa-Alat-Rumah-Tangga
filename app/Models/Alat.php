<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alat extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'alats';

    protected $fillable = [
        'kategori_id',
        'kode',
        'nama',
        'deskripsi',
        'merk',
        'foto',
        'stok_total',
        'stok_tersedia',
        'harga_sewa_per_hari',
        'denda_per_hari',
        'kondisi',
        'status',
    ];

    protected $casts = [
        'stok_total'          => 'integer',
        'stok_tersedia'       => 'integer',
        'harga_sewa_per_hari' => 'decimal:2',
        'denda_per_hari'      => 'decimal:2',
    ];

    // =========================================================
    //  RELATIONSHIPS
    // =========================================================

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'alat_id');
    }

    // =========================================================
    //  SCOPES
    // =========================================================

    /** Only active & available equipment. */
    public function scopeTersedia($query)
    {
        return $query->where('status', 'aktif')
                     ->where('stok_tersedia', '>', 0);
    }

    /** Filter by category. */
    public function scopeByKategori($query, int $kategoriId)
    {
        return $query->where('kategori_id', $kategoriId);
    }

    // =========================================================
    //  ACCESSORS
    // =========================================================

    /**
     * Whether any unit is currently available to borrow.
     */
    public function getIsAvailableAttribute(): bool
    {
        return $this->status === 'aktif' && $this->stok_tersedia > 0;
    }

    /**
     * Calculate rental cost for a given number of days.
     */
    public function hitungBiaya(int $jumlahHari, int $jumlah = 1): float
    {
        return $this->harga_sewa_per_hari * $jumlahHari * $jumlah;
    }

    // =========================================================
    //  STOCK HELPERS (called from Peminjaman / Pengembalian)
    // =========================================================

    /**
     * Decrement available stock when a borrow is approved.
     * Throws an exception if stock is insufficient.
     */
    public function kurangiStok(int $jumlah = 1): void
    {
        if ($this->stok_tersedia < $jumlah) {
            throw new \RuntimeException("Stok alat '{$this->nama}' tidak mencukupi.");
        }
        $this->decrement('stok_tersedia', $jumlah);
    }

    /**
     * Increment available stock when an item is returned.
     */
    public function tambahStok(int $jumlah = 1): void
    {
        $this->increment('stok_tersedia', $jumlah);
    }
}