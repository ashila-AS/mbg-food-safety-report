<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatatanLaporan extends Model
{
    protected $table = 'catatan_laporan';
    protected $primaryKey = 'catatan_id';
    public $timestamps = false;

    protected $fillable = ['laporan_id', 'user_id', 'isi'];

    protected $casts = ['waktu' => 'datetime'];

    public function laporan(): BelongsTo
    {
        return $this->belongsTo(Laporan::class, 'laporan_id', 'laporan_id');
    }

    public function penulis(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}