<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sekolah extends Model
{
    protected $table = 'sekolah';
    protected $primaryKey = 'sekolah_id';

    protected $fillable = ['nama_sekolah', 'alamat'];

    public function distribusi(): HasMany
    {
        return $this->hasMany(Distribusi::class, 'sekolah_id', 'sekolah_id');
    }
}