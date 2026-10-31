<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Laporan extends Model
{
    public const DIKIRIM = 'Dikirim';
    public const DITINJAU = 'Ditinjau';
    public const DIKEMBALIKAN = 'Dikembalikan';
    public const DITANDAI = 'Ditandai untuk diteruskan';
    public const DITERUSKAN = 'Diteruskan';

    protected $table = 'laporan';
    protected $primaryKey = 'laporan_id';

    protected $fillable = [
        'distribusi_id',
        'waktu_kejadian',
        'deskripsi',
        'gejala',
        'jumlah_terdampak',
        'path_bukti',
    ];

    protected $casts = ['waktu_kejadian' => 'datetime'];

    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function peninjau(): BelongsTo
    {
        return $this->belongsTo(User::class, 'peninjau_id', 'user_id');
    }

    public function distribusi(): BelongsTo
    {
        return $this->belongsTo(Distribusi::class, 'distribusi_id', 'distribusi_id');
    }

    public function catatan(): HasMany
    {
        return $this->hasMany(CatatanLaporan::class, 'laporan_id', 'laporan_id');
    }

    public function penerusan(): HasMany
    {
        return $this->hasMany(Penerusan::class, 'laporan_id', 'laporan_id');
    }

    public function riwayat(): HasMany
    {
        return $this->hasMany(RiwayatLaporan::class, 'laporan_id', 'laporan_id');
    }
}