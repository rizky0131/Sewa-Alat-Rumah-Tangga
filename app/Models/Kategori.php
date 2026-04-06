<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategoris';

    protected $fillable = [
        'nama',
        'slug',
        'ikon',
        'deskripsi',
        'is_aktif',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
    ];

    // =========================================================
    //  RELATIONSHIPS
    // =========================================================

    /**
     * All equipment that belongs to this category.
     */
    public function alats(): HasMany
    {
        return $this->hasMany(Alat::class, 'kategori_id');
    }

    // =========================================================
    //  SCOPES
    // =========================================================

    /** Only active categories. */
    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }

    // =========================================================
    //  ACCESSORS
    // =========================================================

    /** Total available stock across all equipment in this category. */
    public function getStokTersediaAttribute(): int
    {
        return $this->alats()->where('status', 'aktif')->sum('stok_tersedia');
    }
}