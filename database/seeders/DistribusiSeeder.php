<?php

namespace Database\Seeders;

use App\Models\Distribusi;
use App\Models\Sekolah;
use Illuminate\Database\Seeder;

class DistribusiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'SMP Harapan' => [
                ['2026-10-26', 'Nasi, ayam goreng, sayur bening, jeruk'],
                ['2026-10-27', 'Nasi, telur balado, tumis kangkung, pisang'],
                ['2026-10-28', 'Nasi, ikan goreng, sayur sop, apel'],
            ],
            'SDN Cahaya 1' => [
                ['2026-10-26', 'Nasi, tempe orek, capcay, jeruk'],
                ['2026-10-27', 'Nasi, ayam suwir, sayur bayam, pisang'],
            ],
            'SMPN Nusantara 2' => [
                ['2026-10-26', 'Nasi, daging semur, buncis wortel, apel'],
                ['2026-10-28', 'Nasi, telur dadar, sayur lodeh, jeruk'],
            ],
            'SMA Pelita' => [
                ['2026-10-27', 'Nasi, ikan bakar, lalapan, pisang'],
                ['2026-10-28', 'Nasi, ayam geprek, sayur asem, apel'],
            ],
        ];

        foreach ($data as $namaSekolah => $items) {
            $sekolah = Sekolah::where('nama_sekolah', $namaSekolah)->firstOrFail();

            foreach ($items as [$tanggal, $menu]) {
                Distribusi::create([
                    'sekolah_id' => $sekolah->sekolah_id,
                    'tanggal' => $tanggal,
                    'menu' => $menu,
                    'nama_sppg' => 'SPPG Dummy ' . $sekolah->sekolah_id,
                ]);
            }
        }
    }
}