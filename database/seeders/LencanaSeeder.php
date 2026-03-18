<?php

namespace Database\Seeders;

use App\Models\Lencana;
use Illuminate\Database\Seeder;

class LencanaSeeder extends Seeder
{
    public function run(): void
    {
        $lencana = [
            ['kunci_lencana' => 'login_pertama',  'nama' => 'Penjelajah Baru',  'jenis_kondisi' => 'login',    'nilai_kondisi' => 1,   'ikon' => '🌟'],
            ['kunci_lencana' => 'baca_3_materi',  'nama' => 'Pembaca Rajin',    'jenis_kondisi' => 'baca',     'nilai_kondisi' => 3,   'ikon' => '📚'],
            ['kunci_lencana' => 'ikut_kuis',      'nama' => 'Peserta Aktif',    'jenis_kondisi' => 'kuis',     'nilai_kondisi' => 1,   'ikon' => '✏️'],
            ['kunci_lencana' => 'nilai_sempurna', 'nama' => 'Nilai Sempurna',   'jenis_kondisi' => 'nilai',    'nilai_kondisi' => 100, 'ikon' => '💯'],
            ['kunci_lencana' => 'streak_3_hari',  'nama' => 'Streak 3 Hari',    'jenis_kondisi' => 'streak',   'nilai_kondisi' => 3,   'ikon' => '🔥'],
            ['kunci_lencana' => 'streak_7_hari',  'nama' => 'Streak 7 Hari',    'jenis_kondisi' => 'streak',   'nilai_kondisi' => 7,   'ikon' => '⚡'],
            ['kunci_lencana' => 'poin_500',       'nama' => 'Poin Master',      'jenis_kondisi' => 'poin',     'nilai_kondisi' => 500, 'ikon' => '💎'],
            ['kunci_lencana' => 'pakar_ipa',      'nama' => 'Pakar IPA',        'jenis_kondisi' => 'kuis',     'nilai_kondisi' => 4,   'ikon' => '🔬'],
            ['kunci_lencana' => 'pakar_ips',      'nama' => 'Pakar IPS',        'jenis_kondisi' => 'kuis',     'nilai_kondisi' => 4,   'ikon' => '🌏'],
            ['kunci_lencana' => 'juara_kelas',    'nama' => 'Juara Kelas',      'jenis_kondisi' => 'peringkat', 'nilai_kondisi' => 1,   'ikon' => '🏆'],
        ];

        foreach ($lencana as $data) {
            Lencana::updateOrCreate(
                ['kunci_lencana' => $data['kunci_lencana']],
                array_merge($data, ['warna' => 'teal'])
            );
        }

        $this->command->info('✅ LencanaSeeder: 10 lencana selesai.');
    }
}
