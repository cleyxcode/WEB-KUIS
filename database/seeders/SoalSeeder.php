<?php

namespace Database\Seeders;

use App\Models\MataPelajaran;
use App\Models\Soal;
use Illuminate\Database\Seeder;

class SoalSeeder extends Seeder
{
    public function run(): void
    {
        $ipa = MataPelajaran::where('jenis', 'IPA')->first();
        $ips = MataPelajaran::where('jenis', 'IPS')->first();

        // ── 10 Soal IPA ────────────────────────────────────────
        $soalIpa = [
            [
                'teks_soal'       => 'Organisme yang memakan produsen disebut sebagai...',
                'opsi_a'          => 'Produsen',
                'opsi_b'          => 'Konsumen Tingkat 1',
                'opsi_c'          => 'Pengurai',
                'opsi_d'          => 'Konsumen Tingkat 3',
                'jawaban_benar'   => 'b',
                'penjelasan'      => 'Konsumen tingkat 1 (herbivora) adalah organisme yang memakan produsen (tumbuhan) secara langsung dalam rantai makanan.',
                'tingkat_kesulitan' => 'mudah',
            ],
            [
                'teks_soal'       => 'Proses fotosintesis menghasilkan...',
                'opsi_a'          => 'Karbon dioksida dan air',
                'opsi_b'          => 'Glukosa dan oksigen',
                'opsi_c'          => 'Nitrogen dan hidrogen',
                'opsi_d'          => 'Amilum dan karbon',
                'jawaban_benar'   => 'b',
                'penjelasan'      => 'Fotosintesis mengubah CO₂ + H₂O + cahaya matahari menjadi glukosa (C₆H₁₂O₆) dan oksigen (O₂).',
                'tingkat_kesulitan' => 'mudah',
            ],
            [
                'teks_soal'       => 'Bagian sel yang berfungsi sebagai pusat kendali adalah...',
                'opsi_a'          => 'Mitokondria',
                'opsi_b'          => 'Ribosom',
                'opsi_c'          => 'Nukleus',
                'opsi_d'          => 'Vakuola',
                'jawaban_benar'   => 'c',
                'penjelasan'      => 'Nukleus (inti sel) mengandung DNA dan mengendalikan seluruh aktivitas sel termasuk pertumbuhan dan reproduksi.',
                'tingkat_kesulitan' => 'mudah',
            ],
            [
                'teks_soal'       => 'Gaya yang bekerja pada benda yang jatuh bebas ke bawah disebut...',
                'opsi_a'          => 'Gaya magnet',
                'opsi_b'          => 'Gaya gesek',
                'opsi_c'          => 'Gaya gravitasi',
                'opsi_d'          => 'Gaya listrik',
                'jawaban_benar'   => 'c',
                'penjelasan'      => 'Gaya gravitasi bumi menarik semua benda ke arah pusat bumi, menyebabkan benda jatuh bebas.',
                'tingkat_kesulitan' => 'mudah',
            ],
            [
                'teks_soal'       => 'Planet yang paling dekat dengan matahari adalah...',
                'opsi_a'          => 'Venus',
                'opsi_b'          => 'Bumi',
                'opsi_c'          => 'Mars',
                'opsi_d'          => 'Merkurius',
                'jawaban_benar'   => 'd',
                'penjelasan'      => 'Merkurius adalah planet pertama dalam tata surya yang paling dekat dengan matahari.',
                'tingkat_kesulitan' => 'mudah',
            ],
            [
                'teks_soal'       => 'Perubahan wujud dari cair menjadi gas disebut...',
                'opsi_a'          => 'Membeku',
                'opsi_b'          => 'Mencair',
                'opsi_c'          => 'Menguap',
                'opsi_d'          => 'Menyublim',
                'jawaban_benar'   => 'c',
                'penjelasan'      => 'Menguap (evaporasi) adalah proses perubahan wujud zat dari cair menjadi gas karena pengaruh panas.',
                'tingkat_kesulitan' => 'sedang',
            ],
            [
                'teks_soal'       => 'Lapisan atmosfer yang melindungi bumi dari radiasi ultraviolet adalah...',
                'opsi_a'          => 'Troposfer',
                'opsi_b'          => 'Stratosfer (lapisan ozon)',
                'opsi_c'          => 'Mesosfer',
                'opsi_d'          => 'Termosfer',
                'jawaban_benar'   => 'b',
                'penjelasan'      => 'Lapisan ozon berada di stratosfer (15-35 km) dan menyerap sebagian besar radiasi UV berbahaya dari matahari.',
                'tingkat_kesulitan' => 'sedang',
            ],
            [
                'teks_soal'       => 'Alat pernapasan pada ikan adalah...',
                'opsi_a'          => 'Paru-paru',
                'opsi_b'          => 'Insang',
                'opsi_c'          => 'Trakea',
                'opsi_d'          => 'Stomata',
                'jawaban_benar'   => 'b',
                'penjelasan'      => 'Ikan bernapas menggunakan insang yang menyerap oksigen terlarut dalam air dan mengeluarkan CO₂.',
                'tingkat_kesulitan' => 'mudah',
            ],
            [
                'teks_soal'       => 'Zat yang dapat menghantarkan listrik disebut...',
                'opsi_a'          => 'Isolator',
                'opsi_b'          => 'Konduktor',
                'opsi_c'          => 'Semikonduktor',
                'opsi_d'          => 'Kapasitor',
                'jawaban_benar'   => 'b',
                'penjelasan'      => 'Konduktor adalah bahan yang dapat menghantarkan arus listrik dengan baik, contohnya tembaga, besi, dan aluminium.',
                'tingkat_kesulitan' => 'mudah',
            ],
            [
                'teks_soal'       => 'Proses pemisahan campuran menggunakan perbedaan titik didih disebut...',
                'opsi_a'          => 'Filtrasi',
                'opsi_b'          => 'Kristalisasi',
                'opsi_c'          => 'Destilasi',
                'opsi_d'          => 'Evaporasi',
                'jawaban_benar'   => 'c',
                'penjelasan'      => 'Destilasi memisahkan campuran zat cair berdasarkan perbedaan titik didihnya melalui penguapan dan pengembunan.',
                'tingkat_kesulitan' => 'sulit',
            ],
        ];

        // ── 10 Soal IPS ────────────────────────────────────────
        $soalIps = [
            [
                'teks_soal'       => 'Ibu kota negara Indonesia adalah...',
                'opsi_a'          => 'Surabaya',
                'opsi_b'          => 'Bandung',
                'opsi_c'          => 'Jakarta',
                'opsi_d'          => 'Medan',
                'jawaban_benar'   => 'c',
                'penjelasan'      => 'Jakarta adalah ibu kota Indonesia, meskipun saat ini sedang dilakukan pemindahan ibu kota ke Nusantara di Kalimantan Timur.',
                'tingkat_kesulitan' => 'mudah',
            ],
            [
                'teks_soal'       => 'Proklamasi kemerdekaan Indonesia dibacakan pada tanggal...',
                'opsi_a'          => '17 Agustus 1945',
                'opsi_b'          => '20 Mei 1908',
                'opsi_c'          => '28 Oktober 1928',
                'opsi_d'          => '1 Juni 1945',
                'jawaban_benar'   => 'a',
                'penjelasan'      => 'Proklamasi kemerdekaan Indonesia dibacakan oleh Soekarno-Hatta pada 17 Agustus 1945 pukul 10.00 WIB di Jalan Pegangsaan Timur 56, Jakarta.',
                'tingkat_kesulitan' => 'mudah',
            ],
            [
                'teks_soal'       => 'Sungai terpanjang di Indonesia adalah...',
                'opsi_a'          => 'Sungai Musi',
                'opsi_b'          => 'Sungai Kapuas',
                'opsi_c'          => 'Sungai Mahakam',
                'opsi_d'          => 'Sungai Bengawan Solo',
                'jawaban_benar'   => 'b',
                'penjelasan'      => 'Sungai Kapuas di Kalimantan Barat adalah sungai terpanjang di Indonesia dengan panjang sekitar 1.143 km.',
                'tingkat_kesulitan' => 'sedang',
            ],
            [
                'teks_soal'       => 'Pancasila sebagai dasar negara Indonesia disahkan pada...',
                'opsi_a'          => '17 Agustus 1945',
                'opsi_b'          => '18 Agustus 1945',
                'opsi_c'          => '1 Juni 1945',
                'opsi_d'          => '22 Juni 1945',
                'jawaban_benar'   => 'b',
                'penjelasan'      => 'Pancasila disahkan sebagai dasar negara pada 18 Agustus 1945 oleh PPKI, sehari setelah proklamasi kemerdekaan.',
                'tingkat_kesulitan' => 'sedang',
            ],
            [
                'teks_soal'       => 'Kegiatan ekonomi yang mengolah bahan mentah menjadi barang jadi disebut...',
                'opsi_a'          => 'Kegiatan produksi primer',
                'opsi_b'          => 'Kegiatan produksi sekunder (industri)',
                'opsi_c'          => 'Kegiatan distribusi',
                'opsi_d'          => 'Kegiatan konsumsi',
                'jawaban_benar'   => 'b',
                'penjelasan'      => 'Kegiatan produksi sekunder (industri) mengolah bahan baku menjadi produk jadi yang siap dikonsumsi.',
                'tingkat_kesulitan' => 'sedang',
            ],
            [
                'teks_soal'       => 'Gunung berapi tertinggi di Indonesia adalah...',
                'opsi_a'          => 'Gunung Semeru',
                'opsi_b'          => 'Gunung Rinjani',
                'opsi_c'          => 'Gunung Kerinci',
                'opsi_d'          => 'Gunung Bromo',
                'jawaban_benar'   => 'c',
                'penjelasan'      => 'Gunung Kerinci di Sumatera Barat adalah gunung berapi tertinggi di Indonesia dengan ketinggian 3.805 mdpl.',
                'tingkat_kesulitan' => 'sulit',
            ],
            [
                'teks_soal'       => 'Badan yang bertugas membuat undang-undang di Indonesia adalah...',
                'opsi_a'          => 'Presiden',
                'opsi_b'          => 'Mahkamah Agung',
                'opsi_c'          => 'DPR (Dewan Perwakilan Rakyat)',
                'opsi_d'          => 'BPK',
                'jawaban_benar'   => 'c',
                'penjelasan'      => 'DPR memiliki fungsi legislasi yaitu membuat undang-undang bersama pemerintah (presiden).',
                'tingkat_kesulitan' => 'mudah',
            ],
            [
                'teks_soal'       => 'Pulau terbesar di Indonesia adalah...',
                'opsi_a'          => 'Jawa',
                'opsi_b'          => 'Sumatera',
                'opsi_c'          => 'Kalimantan',
                'opsi_d'          => 'Papua',
                'jawaban_benar'   => 'd',
                'penjelasan'      => 'Papua adalah pulau terbesar di Indonesia dengan luas sekitar 785.753 km², bahkan terbesar kedua di dunia.',
                'tingkat_kesulitan' => 'mudah',
            ],
            [
                'teks_soal'       => 'Valuta asing yang paling banyak digunakan dalam perdagangan internasional adalah...',
                'opsi_a'          => 'Euro',
                'opsi_b'          => 'Poundsterling',
                'opsi_c'          => 'Dolar Amerika Serikat (USD)',
                'opsi_d'          => 'Yen Jepang',
                'jawaban_benar'   => 'c',
                'penjelasan'      => 'Dolar AS (USD) adalah mata uang cadangan dunia dan paling dominan dalam transaksi perdagangan internasional.',
                'tingkat_kesulitan' => 'sedang',
            ],
            [
                'teks_soal'       => 'Peristiwa Sumpah Pemuda terjadi pada tanggal...',
                'opsi_a'          => '28 Oktober 1928',
                'opsi_b'          => '20 Mei 1908',
                'opsi_c'          => '17 Agustus 1945',
                'opsi_d'          => '10 November 1945',
                'jawaban_benar'   => 'a',
                'penjelasan'      => 'Sumpah Pemuda diikrarkan pada Kongres Pemuda II tanggal 28 Oktober 1928, menyatukan tekad satu nusa, satu bangsa, satu bahasa.',
                'tingkat_kesulitan' => 'mudah',
            ],
        ];

        $adminId = 1;

        foreach ($soalIpa as $data) {
            Soal::updateOrCreate(
                ['teks_soal' => $data['teks_soal']],
                array_merge($data, ['mata_pelajaran_id' => $ipa->id, 'user_id' => $adminId])
            );
        }

        foreach ($soalIps as $data) {
            Soal::updateOrCreate(
                ['teks_soal' => $data['teks_soal']],
                array_merge($data, ['mata_pelajaran_id' => $ips->id, 'user_id' => $adminId])
            );
        }

        $this->command->info('✅ SoalSeeder: 10 soal IPA + 10 soal IPS selesai.');
    }
}
