<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Peminjaman extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'peminjamans';

    // ─── Status constants ─────────────────────────────
    const STATUS_MENUNGGU     = 'menunggu';
    const STATUS_DISETUJUI    = 'disetujui';
    const STATUS_DITOLAK      = 'ditolak';
    const STATUS_DIPINJAM     = 'dipinjam';
    const STATUS_DIKEMBALIKAN = 'dikembalikan';

    protected $fillable = [
        'nomor_pinjam',
        'peminjam_id',
        'alat_id',
        'petugas_id',
        'jumlah',
        'tanggal_pinjam',
        'tanggal_kembali_rencana',
        'total_biaya',
        'tujuan_peminjaman',
        'status',
        'catatan_petugas',
        'disetujui_at',
    ];

    protected $casts = [
        'tanggal_pinjam'          => 'date',
        'tanggal_kembali_rencana' => 'date',
        'total_biaya'             => 'decimal:2',
        'jumlah'                  => 'integer',
        'disetujui_at'            => 'datetime',
    ];

    // =========================================================
    //  BOOT — auto-generate nomor_pinjam
    // =========================================================

    protected static function booted(): void
    {
        static::creating(function (Peminjaman $model) {
            if (empty($model->nomor_pinjam)) {
                $model->nomor_pinjam = self::generateNomor();
            }
        });
    }

    /**
     * Generate a unique transaction number: PNJ-YYYYMMDD-XXX
     */
    public static function generateNomor(): string
    {
        $today   = now()->format('Ymd');
        $prefix  = "PNJ-{$today}-";
        $last    = self::where('nomor_pinjam', 'like', "{$prefix}%")
                       ->orderByDesc('nomor_pinjam')
                       ->value('nomor_pinjam');

        $seq = $last ? ((int) substr($last, -3)) + 1 : 1;

        return $prefix . str_pad($seq, 3, '0', STR_PAD_LEFT);
    }

    // =========================================================
    //  RELATIONSHIPS
    // =========================================================

    public function peminjam(): BelongsTo
    {
        return $this->belongsTo(User::class, 'peminjam_id');
    }

    public function alat(): BelongsTo
    {
        return $this->belongsTo(Alat::class, 'alat_id');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function pengembalian(): HasOne
    {
        return $this->hasOne(Pengembalian::class, 'peminjaman_id');
    }

    // =========================================================
    //  SCOPES
    // =========================================================

    public function scopeMenunggu($query)
    {
        return $query->where('status', self::STATUS_MENUNGGU);
    }

    public function scopeAktif($query)
    {
        return $query->where('status', self::STATUS_DIPINJAM);
    }

    public function scopeTerlambat($query)
    {
        return $query->where('status', self::STATUS_DIPINJAM)
                     ->where('tanggal_kembali_rencana', '<', now()->toDateString());
    }

    public function scopeByPeminjam($query, int $userId)
    {
        return $query->where('peminjam_id', $userId);
    }

    // =========================================================
    //  ACCESSORS
    // =========================================================

    /** Duration in days (planned). */
    public function getDurasiHariAttribute(): int
    {
        return $this->tanggal_pinjam->diffInDays($this->tanggal_kembali_rencana);
    }

    /** Whether the item is overdue. */
    public function getIsTerlambatAttribute(): bool
    {
        return $this->status === self::STATUS_DIPINJAM
            && now()->isAfter($this->tanggal_kembali_rencana);
    }

    /** Number of overdue days (0 if on time). */
    public function getKeterlambatanHariAttribute(): int
    {
        if (! $this->is_terlambat) {
            return 0;
        }
        return (int) $this->tanggal_kembali_rencana->diffInDays(now());
    }

    // =========================================================
    //  BUSINESS ACTIONS
    // =========================================================

    /**
     * Approve the borrow request (called by petugas / admin).
     */
    public function setujui(User $petugas, ?string $catatan = null): void
    {
        $this->alat->kurangiStok($this->jumlah);

        $this->update([
            'status'          => self::STATUS_DISETUJUI,
            'petugas_id'      => $petugas->id,
            'catatan_petugas' => $catatan,
            'disetujui_at'    => now(),
        ]);
    }

    /**
     * Reject the borrow request (called by petugas / admin).
     */
    public function tolak(User $petugas, ?string $catatan = null): void
    {
        $this->update([
            'status'          => self::STATUS_DITOLAK,
            'petugas_id'      => $petugas->id,
            'catatan_petugas' => $catatan,
            'disetujui_at'    => now(),
        ]);
    }

    /**
     * Mark the item as actively borrowed (handed over).
     */
    public function tandaiDipinjam(): void
    {
        $this->update(['status' => self::STATUS_DIPINJAM]);
    }
}