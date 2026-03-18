<?php

namespace Database\Seeders;

use App\Models\MataPelajaran;
use Illuminate\Database\Seeder;

class MataPelajaranSeeder extends Seeder
{
    public function run(): void
    {
        MataPelajaran::updateOrCreate(['nama' => 'IPA'], [
            'jenis'  => 'IPA',
            'ikon'   => '🔬',
            'warna'  => 'teal',
            'urutan' => 1,
        ]);

        MataPelajaran::updateOrCreate(['nama' => 'IPS'], [
            'jenis'  => 'IPS',
            'ikon'   => '🌏',
            'warna'  => 'blue',
            'urutan' => 2,
        ]);

        $this->command->info('✅ MataPelajaranSeeder: IPA + IPS selesai.');
    }
}
