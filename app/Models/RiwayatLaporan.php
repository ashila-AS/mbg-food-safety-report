<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatLaporan extends Model
{
    protected $table = 'riwayat_laporan';
    protected $primaryKey = 'riwayat_id';
    public $timestamps = false;

    protected $fillable = ['laporan_id', 'diubah_oleh', 'status_lama', 'status_baru'];

    protected $casts = ['waktu' => 'datetime'];

    public function laporan(): BelongsTo
    {
        return $this->belongsTo(Laporan::class, 'laporan_id', 'laporan_id');
    }

    public function pengubah(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diubah_oleh', 'user_id');
    }
}