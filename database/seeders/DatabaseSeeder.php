<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Menjalankan semua seeder GAS-IPAS...');

        $this->call([
            UserSeeder::class,          // 10 user (1 admin + 9 user)
            MataPelajaranSeeder::class, // 2 mapel: IPA + IPS
            LencanaSeeder::class,       // 10 lencana
            SiswaSeeder::class,         // 10 siswa
            MateriSeeder::class,        // 5 materi IPA + 5 materi IPS
            SoalSeeder::class,          // 10 soal IPA + 10 soal IPS
            KuisSeeder::class,          // 10 kuis (5 IPA + 5 IPS) + kuis_soal
            LencanaSiswaSeeder::class,  // distribusi lencana ke siswa
            LogPoinSeeder::class,       // 10 log poin per siswa
        ]);

        $this->command->newLine();
        $this->command->info('✅ Semua seeder selesai!');
        $this->command->table(
            ['Tabel', 'Jumlah Data'],
            [
                ['users',         '10 (1 admin + 9 user)'],
                ['mata_pelajaran','2 (IPA + IPS)'],
                ['lencana',       '10'],
                ['siswa',         '10'],
                ['materi',        '10 (5 IPA + 5 IPS)'],
                ['soal',          '20 (10 IPA + 10 IPS)'],
                ['kuis',          '10 (5 IPA + 5 IPS)'],
                ['kuis_soal',     '50 (5 soal × 10 kuis)'],
                ['lencana_siswa', '~50'],
                ['log_poin',      '100 (10 × 10 siswa)'],
            ]
        );
    }
}
