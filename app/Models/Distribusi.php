<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Distribusi extends Model
{
    protected $table = 'distribusi';
    protected $primaryKey = 'distribusi_id';

    protected $fillable = ['sekolah_id', 'tanggal', 'menu', 'nama_sppg'];

    protected $casts = ['tanggal' => 'date'];

    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id', 'sekolah_id');
    }

    public function laporan(): HasMany
    {
        return $this->hasMany(Laporan::class, 'distribusi_id', 'distribusi_id');
    }
}