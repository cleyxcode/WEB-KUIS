<?php

namespace Database\Seeders;

use App\Models\LogPoin;
use App\Models\Siswa;
use Illuminate\Database\Seeder;

class LogPoinSeeder extends Seeder
{
    public function run(): void
    {
        $siswas = Siswa::all();

        foreach ($siswas as $siswa) {
            // Buat 10 log poin per siswa
            $logs = [
                ['poin' => 80,  'sumber' => 'kuis',   'keterangan' => 'Kuis: Ekosistem & Rantai Makanan'],
                ['poin' => 60,  'sumber' => 'kuis',   'keterangan' => 'Kuis: Sel & Organ Tubuh'],
                ['poin' => 100, 'sumber' => 'kuis',   'keterangan' => 'Kuis: Geografi Indonesia'],
                ['poin' => 50,  'sumber' => 'kuis',   'keterangan' => 'Kuis: Sejarah Kemerdekaan'],
                ['poin' => 70,  'sumber' => 'kuis',   'keterangan' => 'Kuis: Pancasila & Kewarganegaraan'],
                ['poin' => 30,  'sumber' => 'streak', 'keterangan' => 'Bonus streak 3 hari berturut-turut'],
                ['poin' => 50,  'sumber' => 'streak', 'keterangan' => 'Bonus streak 7 hari berturut-turut'],
                ['poin' => 20,  'sumber' => 'bonus',  'keterangan' => 'Bonus login pertama'],
                ['poin' => 40,  'sumber' => 'kuis',   'keterangan' => 'Kuis: Fisika Dasar: Gaya & Energi'],
                ['poin' => 55,  'sumber' => 'kuis',   'keterangan' => 'Kuis: Ekonomi & Kehidupan Sehari-hari'],
            ];

            foreach ($logs as $i => $log) {
                LogPoin::create([
                    'siswa_id'   => $siswa->id,
                    'poin'       => $log['poin'],
                    'sumber'     => $log['sumber'],
                    'sumber_id'  => null,
                    'keterangan' => $log['keterangan'],
                    'created_at' => now()->subDays(rand(1, 30))->subHours($i),
                    'updated_at' => now()->subDays(rand(1, 30)),
                ]);
            }
        }

        $this->command->info('✅ LogPoinSeeder: 10 log poin × 10 siswa = 100 log selesai.');
    }
}
