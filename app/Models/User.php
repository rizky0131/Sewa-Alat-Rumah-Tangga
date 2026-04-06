<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // ─── Constants ────────────────────────────────────
    const ROLE_ADMIN    = 'admin';
    const ROLE_PETUGAS  = 'petugas';
    const ROLE_PEMINJAM = 'peminjam';

    // ─── Mass-assignable ──────────────────────────────
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'no_hp',
    ];

    // ─── Hidden from serialisation ────────────────────
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // ─── Casts ────────────────────────────────────────
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // =========================================================
    //  ROLE HELPERS
    // =========================================================

    /** Check if the user is an admin. */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /** Check if the user is a petugas (staff). */
    public function isPetugas(): bool
    {
        return $this->role === self::ROLE_PETUGAS;
    }

    /** Check if the user is a peminjam (borrower). */
    public function isPeminjam(): bool
    {
        return $this->role === self::ROLE_PEMINJAM;
    }

    /** Check if the user has admin OR petugas privilege. */
    public function isStaff(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_PETUGAS]);
    }

    // =========================================================
    //  RELATIONSHIPS
    // =========================================================

    /**
     * Peminjaman records where this user is the BORROWER.
     */
    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'peminjam_id');
    }

    /**
     * Peminjaman records approved/rejected by this user (as petugas).
     */
    public function peminjamansDisetujui(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'petugas_id');
    }

    /**
     * Pengembalian records recorded by this user (as petugas).
     */
    public function pengembalians(): HasMany
    {
        return $this->hasMany(Pengembalian::class, 'petugas_id');
    }

    /**
     * Activity log entries by this user.
     */
    public function logAktivitas(): HasMany
    {
        return $this->hasMany(LogAktivitas::class);
    }
}