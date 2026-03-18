<?php

namespace Database\Seeders;

use App\Models\Siswa;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        $siswas = [
            ['nis' => '2024001', 'nama_lengkap' => 'Andi Pratama',     'nama_panggilan' => 'Andi',   'kelas' => 'V', 'jenis_kelamin' => 'L', 'total_poin' => 2450, 'streak_sekarang' => 7,  'streak_terpanjang' => 10],
            ['nis' => '2024002', 'nama_lengkap' => 'Sari Dewi',        'nama_panggilan' => 'Sari',   'kelas' => 'V', 'jenis_kelamin' => 'P', 'total_poin' => 1980, 'streak_sekarang' => 5,  'streak_terpanjang' => 8],
            ['nis' => '2024003', 'nama_lengkap' => 'Budi Cahyono',     'nama_panggilan' => 'Budi',   'kelas' => 'V', 'jenis_kelamin' => 'L', 'total_poin' => 1750, 'streak_sekarang' => 3,  'streak_terpanjang' => 7],
            ['nis' => '2024004', 'nama_lengkap' => 'Rina Marlina',     'nama_panggilan' => 'Rina',   'kelas' => 'V', 'jenis_kelamin' => 'P', 'total_poin' => 1620, 'streak_sekarang' => 4,  'streak_terpanjang' => 6],
            ['nis' => '2024005', 'nama_lengkap' => 'Doni Saputra',     'nama_panggilan' => 'Doni',   'kelas' => 'V', 'jenis_kelamin' => 'L', 'total_poin' => 1580, 'streak_sekarang' => 2,  'streak_terpanjang' => 5],
            ['nis' => '2024006', 'nama_lengkap' => 'Maya Anggraini',   'nama_panggilan' => 'Maya',   'kelas' => 'V', 'jenis_kelamin' => 'P', 'total_poin' => 1530, 'streak_sekarang' => 6,  'streak_terpanjang' => 9],
            ['nis' => '2024007', 'nama_lengkap' => 'Dinda Safitri',    'nama_panggilan' => 'Dinda',  'kelas' => 'V', 'jenis_kelamin' => 'P', 'total_poin' => 1250, 'streak_sekarang' => 5,  'streak_terpanjang' => 7],
            ['nis' => '2024008', 'nama_lengkap' => 'Fajar Nugroho',    'nama_panggilan' => 'Fajar',  'kelas' => 'V', 'jenis_kelamin' => 'L', 'total_poin' => 1180, 'streak_sekarang' => 1,  'streak_terpanjang' => 4],
            ['nis' => '2024009', 'nama_lengkap' => 'Lina Agustina',    'nama_panggilan' => 'Lina',   'kelas' => 'V', 'jenis_kelamin' => 'P', 'total_poin' => 1120, 'streak_sekarang' => 3,  'streak_terpanjang' => 5],
            ['nis' => '2024010', 'nama_lengkap' => 'Eko Wahyudi',      'nama_panggilan' => 'Eko',    'kelas' => 'V', 'jenis_kelamin' => 'L', 'total_poin' => 1050, 'streak_sekarang' => 2,  'streak_terpanjang' => 3],
        ];

        foreach ($siswas as $i => $data) {
            Siswa::updateOrCreate(
                ['nis' => $data['nis']],
                array_merge($data, [
                    'kode_siswa'    => strtoupper(substr(md5($data['nis']), 0, 6)),
                    'aktif'         => true,
                    'terakhir_aktif' => now()->subDays(rand(0, 7)),
                ])
            );
        }

        $this->command->info('✅ SiswaSeeder: 10 siswa selesai.');
    }
}
