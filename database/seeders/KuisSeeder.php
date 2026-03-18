<?php

namespace Database\Seeders;

use App\Models\Kuis;
use App\Models\MataPelajaran;
use App\Models\Soal;
use Illuminate\Database\Seeder;

class KuisSeeder extends Seeder
{
    public function run(): void
    {
        $ipa    = MataPelajaran::where('jenis', 'IPA')->first();
        $ips    = MataPelajaran::where('jenis', 'IPS')->first();
        $adminId = 1;

        $kuisData = [
            // ── IPA ──────────────────────────────────────────────
            [
                'mata_pelajaran_id'    => $ipa->id,
                'user_id'              => $adminId,
                'judul'                => 'Ekosistem & Rantai Makanan',
                'deskripsi'            => 'Uji pemahamanmu tentang ekosistem, rantai makanan, dan peran makhluk hidup.',
                'jumlah_soal'          => 5,
                'waktu_per_soal'       => 30,
                'acak_soal'            => true,
                'acak_opsi'            => true,
                'tampilkan_penjelasan' => true,
                'aktif'                => true,
                'mulai_pada'           => null,
                'berakhir_pada'        => null,
                'mapel'                => 'IPA',
            ],
            [
                'mata_pelajaran_id'    => $ipa->id,
                'user_id'              => $adminId,
                'judul'                => 'Sel & Organ Tubuh',
                'deskripsi'            => 'Kenali bagian-bagian sel dan fungsi organ tubuh makhluk hidup.',
                'jumlah_soal'          => 5,
                'waktu_per_soal'       => 25,
                'acak_soal'            => true,
                'acak_opsi'            => false,
                'tampilkan_penjelasan' => true,
                'aktif'                => true,
                'mulai_pada'           => null,
                'berakhir_pada'        => now()->addDays(14),
                'mapel'                => 'IPA',
            ],
            [
                'mata_pelajaran_id'    => $ipa->id,
                'user_id'              => $adminId,
                'judul'                => 'Fisika Dasar: Gaya & Energi',
                'deskripsi'            => 'Pelajari konsep gaya, energi, dan perubahannya dalam kehidupan sehari-hari.',
                'jumlah_soal'          => 5,
                'waktu_per_soal'       => 35,
                'acak_soal'            => false,
                'acak_opsi'            => true,
                'tampilkan_penjelasan' => true,
                'aktif'                => true,
                'mulai_pada'           => null,
                'berakhir_pada'        => now()->addDays(7),
                'mapel'                => 'IPA',
            ],
            [
                'mata_pelajaran_id'    => $ipa->id,
                'user_id'              => $adminId,
                'judul'                => 'Tata Surya & Antariksa',
                'deskripsi'            => 'Jelajahi tata surya, planet-planet, dan fenomena alam semesta.',
                'jumlah_soal'          => 5,
                'waktu_per_soal'       => 30,
                'acak_soal'            => true,
                'acak_opsi'            => true,
                'tampilkan_penjelasan' => false,
                'aktif'                => false,
                'mulai_pada'           => now()->addDays(3),
                'berakhir_pada'        => now()->addDays(10),
                'mapel'                => 'IPA',
            ],
            [
                'mata_pelajaran_id'    => $ipa->id,
                'user_id'              => $adminId,
                'judul'                => 'Kimia Seru: Wujud & Campuran',
                'deskripsi'            => 'Pelajari perubahan wujud zat dan cara memisahkan campuran.',
                'jumlah_soal'          => 5,
                'waktu_per_soal'       => 40,
                'acak_soal'            => true,
                'acak_opsi'            => true,
                'tampilkan_penjelasan' => true,
                'aktif'                => true,
                'mulai_pada'           => null,
                'berakhir_pada'        => null,
                'mapel'                => 'IPA',
            ],
            // ── IPS ──────────────────────────────────────────────
            [
                'mata_pelajaran_id'    => $ips->id,
                'user_id'              => $adminId,
                'judul'                => 'Geografi Indonesia',
                'deskripsi'            => 'Kenali keindahan alam, pulau, sungai, dan gunung di Indonesia.',
                'jumlah_soal'          => 5,
                'waktu_per_soal'       => 30,
                'acak_soal'            => true,
                'acak_opsi'            => true,
                'tampilkan_penjelasan' => true,
                'aktif'                => true,
                'mulai_pada'           => null,
                'berakhir_pada'        => null,
                'mapel'                => 'IPS',
            ],
            [
                'mata_pelajaran_id'    => $ips->id,
                'user_id'              => $adminId,
                'judul'                => 'Sejarah Kemerdekaan Indonesia',
                'deskripsi'            => 'Uji pengetahuanmu tentang perjuangan dan proklamasi kemerdekaan Indonesia.',
                'jumlah_soal'          => 5,
                'waktu_per_soal'       => 30,
                'acak_soal'            => false,
                'acak_opsi'            => true,
                'tampilkan_penjelasan' => true,
                'aktif'                => true,
                'mulai_pada'           => null,
                'berakhir_pada'        => now()->addDays(21),
                'mapel'                => 'IPS',
            ],
            [
                'mata_pelajaran_id'    => $ips->id,
                'user_id'              => $adminId,
                'judul'                => 'Ekonomi & Kehidupan Sehari-hari',
                'deskripsi'            => 'Pelajari kegiatan ekonomi, produksi, distribusi, dan konsumsi.',
                'jumlah_soal'          => 5,
                'waktu_per_soal'       => 25,
                'acak_soal'            => true,
                'acak_opsi'            => false,
                'tampilkan_penjelasan' => true,
                'aktif'                => true,
                'mulai_pada'           => null,
                'berakhir_pada'        => null,
                'mapel'                => 'IPS',
            ],
            [
                'mata_pelajaran_id'    => $ips->id,
                'user_id'              => $adminId,
                'judul'                => 'Pancasila & Kewarganegaraan',
                'deskripsi'            => 'Pahami Pancasila, UUD 1945, dan lembaga negara Indonesia.',
                'jumlah_soal'          => 5,
                'waktu_per_soal'       => 30,
                'acak_soal'            => true,
                'acak_opsi'            => true,
                'tampilkan_penjelasan' => true,
                'aktif'                => true,
                'mulai_pada'           => null,
                'berakhir_pada'        => null,
                'mapel'                => 'IPS',
            ],
            [
                'mata_pelajaran_id'    => $ips->id,
                'user_id'              => $adminId,
                'judul'                => 'Sumpah Pemuda & Nasionalisme',
                'deskripsi'            => 'Kenali sejarah Sumpah Pemuda dan semangat persatuan bangsa.',
                'jumlah_soal'          => 5,
                'waktu_per_soal'       => 30,
                'acak_soal'            => true,
                'acak_opsi'            => true,
                'tampilkan_penjelasan' => false,
                'aktif'                => false,
                'mulai_pada'           => now()->addDays(5),
                'berakhir_pada'        => now()->addDays(15),
                'mapel'                => 'IPS',
            ],
        ];

        foreach ($kuisData as $data) {
            $mapel  = $data['mapel'];
            unset($data['mapel']);

            $kuis = Kuis::updateOrCreate(
                ['judul' => $data['judul']],
                $data
            );

            // Hubungkan soal sesuai mapel (5 soal random)
            $soalIds = Soal::where('mata_pelajaran_id', $data['mata_pelajaran_id'])
                ->inRandomOrder()
                ->limit($data['jumlah_soal'])
                ->pluck('id')
                ->toArray();

            $pivot = [];
            foreach ($soalIds as $urutan => $soalId) {
                $pivot[$soalId] = ['urutan' => $urutan + 1];
            }
            $kuis->soal()->sync($pivot);
        }

        $this->command->info('✅ KuisSeeder: 10 kuis (5 IPA + 5 IPS) + soal terhubung selesai.');
    }
}
