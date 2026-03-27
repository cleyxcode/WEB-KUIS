<?php

namespace App\Services;

/**
 * Fisher-Yates Shuffle Algorithm
 *
 * Algoritma pengacakan Fisher-Yates (dikenal juga sebagai Knuth Shuffle)
 * adalah algoritma untuk menghasilkan permutasi acak dari sebuah array secara
 * seragam (unbiased), artinya setiap kemungkinan permutasi memiliki probabilitas
 * yang sama untuk muncul.
 *
 * Kompleksitas waktu : O(n)
 * Kompleksitas ruang : O(1) — pengacakan dilakukan in-place
 *
 * Referensi:
 * - Fisher, R. A. & Yates, F. (1938). Statistical tables for biological,
 *   agricultural and medical research.
 * - Knuth, D. E. (1969). The Art of Computer Programming, Vol. 2.
 */
class FisherYatesShuffleService
{
    /**
     * Mengacak elemen array menggunakan algoritma Fisher-Yates (Modern / Knuth version).
     *
     * Langkah algoritma:
     *  Untuk i = n-1 turun ke 1:
     *    1. Pilih indeks acak j, di mana 0 ≤ j ≤ i
     *    2. Tukar elemen[i] dengan elemen[j]
     *
     * Menggunakan random_int() (bukan rand()) agar hasil lebih kriptografis
     * dan distribusi pengacakan lebih seragam (unbiased).
     *
     * @param  array $array  Array yang akan diacak (tidak dimodifikasi, dikembalikan salinan baru)
     * @return array         Array baru dengan urutan elemen teracak
     */
    public static function acak(array $array): array
    {
        $n = count($array);

        // Indeks array di-reset agar numerik berurutan (0, 1, 2, ...)
        $array = array_values($array);

        // Iterasi dari elemen terakhir mundur ke elemen kedua
        for ($i = $n - 1; $i > 0; $i--) {
            // Pilih indeks acak j antara 0 dan i (inklusif)
            $j = random_int(0, $i);

            // Tukar elemen pada posisi i dan j (PHP destructuring swap)
            [$array[$i], $array[$j]] = [$array[$j], $array[$i]];
        }

        return $array;
    }

    /**
     * Mengacak urutan soal dalam kuis.
     *
     * Soal diambil dari bank soal kuis, kemudian diacak menggunakan Fisher-Yates.
     * Hasilnya di-slice sesuai jumlah soal yang dikonfigurasi guru.
     *
     * @param  array $soalIds     Array ID soal dari bank soal kuis
     * @param  int   $jumlahSoal Jumlah soal yang diambil setelah pengacakan
     * @return array              Array ID soal teracak, sudah di-slice
     */
    public static function acakSoal(array $soalIds, int $jumlahSoal): array
    {
        $teracak = self::acak($soalIds);

        // Ambil sejumlah $jumlahSoal pertama dari hasil pengacakan
        return array_slice($teracak, 0, $jumlahSoal);
    }

    /**
     * Mengacak urutan opsi jawaban (a, b, c, d) untuk setiap soal.
     *
     * Setiap soal mendapat urutan opsi yang berbeda sehingga posisi jawaban benar
     * tidak selalu sama antar siswa, meminimalisir potensi kecurangan.
     *
     * @param  array $soalIds  Array ID soal yang sudah teracak
     * @return array           Map [soal_id => ['c','a','d','b'], ...] (contoh)
     */
    public static function acakOpsiPerSoal(array $soalIds): array
    {
        $opsiAcak = [];

        foreach ($soalIds as $soalId) {
            // Setiap soal memiliki pengacakan opsi yang independen
            $opsiAcak[$soalId] = self::acak(['a', 'b', 'c', 'd']);
        }

        return $opsiAcak;
    }

    /**
     * Menghasilkan ringkasan proses pengacakan untuk keperluan transparansi / debugging.
     *
     * @param  array $urutanAsli   Urutan soal sebelum diacak
     * @param  array $urutanAcak   Urutan soal setelah diacak
     * @param  array $opsiAcak     Map opsi teracak per soal
     * @return array
     */
    public static function ringkasanPengacakan(
        array $urutanAsli,
        array $urutanAcak,
        array $opsiAcak
    ): array {
        return [
            'algoritma'        => 'Fisher-Yates Shuffle (Knuth)',
            'jumlah_soal_asli' => count($urutanAsli),
            'jumlah_soal_acak' => count($urutanAcak),
            'urutan_asli'      => $urutanAsli,
            'urutan_acak'      => $urutanAcak,
            'opsi_acak'        => $opsiAcak,
            'dibuat_pada'      => now()->toDateTimeString(),
        ];
    }
}
