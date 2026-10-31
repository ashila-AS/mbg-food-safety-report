<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['nama', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke laporan yang dibuat oleh user.
     */
    public function laporanDibuat(): HasMany
    {
        return $this->hasMany(Laporan::class, 'user_id', 'user_id');
    }

    /**
     * Relasi ke laporan yang ditinjau oleh user.
     */
    public function laporanDitinjau(): HasMany
    {
        return $this->hasMany(Laporan::class, 'peninjau_id', 'user_id');
    }

    /**
     * Memeriksa apakah user memiliki role tertentu.
     */
    public function punyaRole(string $role): bool
    {
        return $this->role === $role;
    }
}