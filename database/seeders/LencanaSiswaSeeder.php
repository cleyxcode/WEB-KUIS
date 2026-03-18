<?php

namespace Database\Seeders;

use App\Models\Lencana;
use App\Models\Siswa;
use Illuminate\Database\Seeder;

class LencanaSiswaSeeder extends Seeder
{
    public function run(): void
    {
        $siswas  = Siswa::all();
        $lencana = Lencana::all();

        if ($siswas->isEmpty() || $lencana->isEmpty()) {
            $this->command->warn('⚠️  LencanaSiswaSeeder: siswa atau lencana belum ada.');
            return;
        }

        // Distribusi lencana per siswa berdasarkan total poin
        $distribusi = [
            // [nis, kunci-kunci lencana yang diraih]
            '2024001' => ['login_pertama', 'ikut_kuis', 'nilai_sempurna', 'streak_3_hari', 'streak_7_hari', 'poin_500', 'pakar_ipa', 'juara_kelas'],
            '2024002' => ['login_pertama', 'ikut_kuis', 'nilai_sempurna', 'streak_3_hari', 'streak_7_hari', 'poin_500', 'pakar_ipa'],
            '2024003' => ['login_pertama', 'ikut_kuis', 'streak_3_hari', 'poin_500', 'pakar_ips'],
            '2024004' => ['login_pertama', 'ikut_kuis', 'streak_3_hari', 'poin_500'],
            '2024005' => ['login_pertama', 'ikut_kuis', 'streak_3_hari'],
            '2024006' => ['login_pertama', 'ikut_kuis', 'streak_3_hari', 'pakar_ips'],
            '2024007' => ['login_pertama', 'ikut_kuis', 'streak_3_hari'],
            '2024008' => ['login_pertama', 'ikut_kuis'],
            '2024009' => ['login_pertama', 'ikut_kuis'],
            '2024010' => ['login_pertama'],
        ];

        foreach ($distribusi as $nis => $kunciList) {
            $siswa = $siswas->firstWhere('nis', $nis);
            if (!$siswa) continue;

            foreach ($kunciList as $kunci) {
                $len = $lencana->firstWhere('kunci_lencana', $kunci);
                if (!$len) continue;

                $siswa->lencana()->syncWithoutDetaching([
                    $len->id => ['diperoleh_pada' => now()->subDays(rand(1, 30))],
                ]);
            }
        }

        $this->command->info('✅ LencanaSiswaSeeder: distribusi lencana ke 10 siswa selesai.');
    }
}
