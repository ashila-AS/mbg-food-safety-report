<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penerusan extends Model
{
    protected $table = 'penerusan';
    protected $primaryKey = 'penerusan_id';
    public $timestamps = false;

    protected $fillable = ['laporan_id', 'dicatat_oleh', 'pihak', 'keterangan'];

    protected $casts = ['waktu' => 'datetime'];

    public function laporan(): BelongsTo
    {
        return $this->belongsTo(Laporan::class, 'laporan_id', 'laporan_id');
    }

    public function pencatat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh', 'user_id');
    }
}