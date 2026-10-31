<?php

namespace Database\Seeders;

use App\Models\Sekolah;
use Illuminate\Database\Seeder;

class SekolahSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['SMP Harapan', 'Jl. Contoh No. 10, Kota Dummy'],
            ['SDN Cahaya 1', 'Jl. Contoh No. 22, Kota Dummy'],
            ['SMPN Nusantara 2', 'Jl. Contoh No. 35, Kota Dummy'],
            ['SMA Pelita', 'Jl. Contoh No. 48, Kota Dummy'],
        ];

        foreach ($data as [$nama, $alamat]) {
            Sekolah::create([
                'nama_sekolah' => $nama,
                'alamat' => $alamat,
            ]);
        }
    }
}