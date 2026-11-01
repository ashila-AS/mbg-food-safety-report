<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SekolahSeeder::class,
            DistribusiSeeder::class,
            UserSeeder::class,
            LaporanSeeder::class,
        ]);
    }
}