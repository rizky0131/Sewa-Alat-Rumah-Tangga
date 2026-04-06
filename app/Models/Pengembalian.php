<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengembalian extends Model
{
    use HasFactory;

    protected $table = 'pengembalians';

    protected $fillable = [
        'peminjaman_id',
        'petugas_id',
        'tanggal_kembali_aktual',
        'keterlambatan_hari',
        'denda',
        'kondisi_kembali',
        'biaya_kerusakan',
        'total_tagihan',
        'catatan',
        'foto_bukti',
    ];

    protected $casts = [
        'tanggal_kembali_aktual' => 'date',
        'keterlambatan_hari'     => 'integer',
        'denda'                  => 'decimal:2',
        'biaya_kerusakan'        => 'decimal:2',
        'total_tagihan'          => 'decimal:2',
    ];

    // =========================================================
    //  BOOT — auto-calculate denda & update alat stock
    // =========================================================

    protected static function booted(): void
    {
        static::creating(function (Pengembalian $model) {
            $peminjaman = $model->peminjaman()->with('alat')->first();

            // 1. Hitung keterlambatan
            $rencana  = $peminjaman->tanggal_kembali_rencana;
            $aktual   = $model->tanggal_kembali_aktual;
            $terlambat = max(0, (int) $rencana->diffInDays($aktual, false) * -1);
            // diffInDays returns positive if aktual > rencana when signed=false,
            // so we check manually:
            $terlambat = $aktual->gt($rencana)
                ? (int) $rencana->diffInDays($aktual)
                : 0;

            $model->keterlambatan_hari = $terlambat;

            $model->denda = (float) $terlambat
                * (float) $peminjaman->alat->denda_per_hari
                * (int) $peminjaman->jumlah;

            $model->total_tagihan =
                (float) $model->denda +
                (float) ($model->biaya_kerusakan ?? 0);
        });

        static::created(function (Pengembalian $model) {
            // 4. Kembalikan stok alat
            $peminjaman = $model->peminjaman;
            $peminjaman->alat->tambahStok($peminjaman->jumlah);

            // 5. Update status peminjaman → dikembalikan
            $peminjaman->update(['status' => Peminjaman::STATUS_DIKEMBALIKAN]);
        });
    }

    // =========================================================
    //  RELATIONSHIPS
    // =========================================================

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    // =========================================================
    //  ACCESSORS
    // =========================================================

    /** Whether the item was returned in damaged condition. */
    public function getIsRusakAttribute(): bool
    {
        return in_array($this->kondisi_kembali, [
            'rusak_ringan',
            'rusak_sedang',
            'rusak_berat',
            'hilang',
        ]);
    }

    /** Whether the return was on time. */
    public function getIsTepatWaktuAttribute(): bool
    {
        return $this->keterlambatan_hari === 0;
    }
}
