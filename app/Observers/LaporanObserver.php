<?php

namespace App\Observers;

use App\Models\Laporan;
use App\Models\RiwayatLaporan;
use Illuminate\Support\Facades\Auth;

class LaporanObserver
{
    public function created(Laporan $laporan): void
    {
        RiwayatLaporan::create([
            'laporan_id' => $laporan->laporan_id,
            'diubah_oleh' => Auth::id() ?? $laporan->user_id,
            'status_lama' => null,
            'status_baru' => $laporan->status,
        ]);
    }

    public function updated(Laporan $laporan): void
    {
        if (! $laporan->wasChanged('status')) {
            return;
        }

        RiwayatLaporan::create([
            'laporan_id' => $laporan->laporan_id,
            'diubah_oleh' => Auth::id() ?? $laporan->user_id,
            'status_lama' => $laporan->getOriginal('status'),
            'status_baru' => $laporan->status,
        ]);
    }
}