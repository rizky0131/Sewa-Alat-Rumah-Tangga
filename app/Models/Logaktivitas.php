<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';

    // Log is immutable — no updates allowed
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'aksi',
        'modul',
        'deskripsi',
        'data_lama',
        'data_baru',
        'subject_id',
        'subject_type',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'data_lama'  => 'array',
        'data_baru'  => 'array',
        'created_at' => 'datetime',
    ];

    // ─── Aksi constants (maps to feature table) ───────
    const AKSI_LOGIN                = 'login';
    const AKSI_LOGOUT               = 'logout';
    const AKSI_CRUD_USER            = 'crud_user';
    const AKSI_CRUD_ALAT            = 'crud_alat';
    const AKSI_CRUD_KATEGORI        = 'crud_kategori';
    const AKSI_CRUD_PEMINJAMAN      = 'crud_peminjaman';
    const AKSI_CRUD_PENGEMBALIAN    = 'crud_pengembalian';
    const AKSI_SETUJUI_PEMINJAMAN   = 'setujui_peminjaman';
    const AKSI_TOLAK_PEMINJAMAN     = 'tolak_peminjaman';
    const AKSI_PANTAU_PENGEMBALIAN  = 'pantau_pengembalian';
    const AKSI_CETAK_LAPORAN        = 'cetak_laporan';

    // =========================================================
    //  RELATIONSHIPS
    // =========================================================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** The model that was acted upon (Peminjaman, Alat, User, etc.). */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    // =========================================================
    //  FACTORY METHOD
    // =========================================================

    /**
     * Convenience method to record an activity from anywhere.
     *
     * Usage:
     *   LogAktivitas::catat(
     *       aksi: LogAktivitas::AKSI_SETUJUI_PEMINJAMAN,
     *       modul: 'Peminjaman',
     *       deskripsi: "Peminjaman #{$peminjaman->nomor_pinjam} disetujui",
     *       subject: $peminjaman,
     *   );
     */
    public static function catat(
        string  $aksi,
        string  $modul,
        ?string $deskripsi   = null,
        ?Model  $subject     = null,
        ?array  $dataLama    = null,
        ?array  $dataBaru    = null,
    ): self {
        return self::create([
            'user_id'      => auth()->id(),
            'aksi'         => $aksi,
            'modul'        => $modul,
            'deskripsi'    => $deskripsi,
            'data_lama'    => $dataLama,
            'data_baru'    => $dataBaru,
            'subject_id'   => $subject?->getKey(),
            'subject_type' => $subject ? get_class($subject) : null,
            'ip_address'   => request()->ip(),
            'user_agent'   => request()->userAgent(),
            'created_at'   => now(),
        ]);
    }

    // =========================================================
    //  SCOPES
    // =========================================================

    public function scopeByAksi($query, string $aksi)
    {
        return $query->where('aksi', $aksi);
    }

    public function scopeByModul($query, string $modul)
    {
        return $query->where('modul', $modul);
    }

    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }
}