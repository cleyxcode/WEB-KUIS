<?php

namespace Database\Seeders;

use App\Models\Materi;
use App\Models\MataPelajaran;
use App\Models\User;
use Illuminate\Database\Seeder;

class MateriSeeder extends Seeder
{
    public function run(): void
    {
        $ipa    = MataPelajaran::where('jenis', 'IPA')->first();
        $ips    = MataPelajaran::where('jenis', 'IPS')->first();
        $admin  = User::where('role', 'admin')->first() ?? User::first();

        if (! $ipa || ! $ips || ! $admin) {
            $this->command->warn('⚠️  MateriSeeder: pastikan MataPelajaranSeeder & UserSeeder sudah dijalankan dulu.');
            return;
        }

        $materiIPA = [
            [
                'bab'       => 'Bab 1',
                'judul'     => 'Ekosistem & Rantai Makanan',
                'deskripsi' => 'Hubungan antara makhluk hidup dengan lingkungan dan bagaimana energi mengalir melalui rantai makanan.',
                'konten'    => '<h2>🌿 Apa itu Ekosistem?</h2>
<p>Ekosistem adalah sebuah sistem ekologi yang terbentuk oleh hubungan timbal balik tak terpisahkan antara makhluk hidup dengan lingkungannya. Ini mencakup komponen biotik (hidup) dan abiotik (tak hidup).</p>
<h2>🦁 Rantai Makanan</h2>
<p>Perjalanan makan dan dimakan antara makhluk hidup dengan urutan tertentu. Dimulai dari produsen hingga pengurai.</p>
<ul>
<li><strong>Produsen</strong> — tumbuhan hijau yang menghasilkan makanan sendiri lewat fotosintesis</li>
<li><strong>Konsumen I</strong> — hewan herbivora yang memakan produsen (contoh: belalang, ulat)</li>
<li><strong>Konsumen II</strong> — hewan karnivora yang memakan konsumen I (contoh: katak, tikus)</li>
<li><strong>Konsumen III</strong> — predator puncak (contoh: elang, ular)</li>
<li><strong>Pengurai</strong> — bakteri & jamur yang mengurai sisa makhluk hidup menjadi zat hara</li>
</ul>
<blockquote>Setiap makhluk hidup memiliki peran penting. Jika satu mata rantai terputus, keseimbangan ekosistem akan terganggu!</blockquote>',
                'urutan'    => 1,
            ],
            [
                'bab'       => 'Bab 2',
                'judul'     => 'Organ Tubuh Manusia & Fungsinya',
                'deskripsi' => 'Mengenal sistem organ dalam tubuh manusia mulai dari sistem pernapasan, pencernaan, hingga peredaran darah.',
                'konten'    => '<h2>🫁 Sistem Pernapasan</h2>
<p>Pernapasan adalah proses menghirup oksigen (O₂) dan mengeluarkan karbon dioksida (CO₂). Organ utamanya adalah paru-paru, hidung, dan tenggorokan.</p>
<h2>❤️ Sistem Peredaran Darah</h2>
<p>Jantung memompa darah ke seluruh tubuh melalui pembuluh darah. Darah membawa oksigen dan nutrisi ke sel-sel tubuh.</p>
<ul>
<li><strong>Jantung</strong> — pompa utama darah, berdetak ±72 kali per menit</li>
<li><strong>Arteri</strong> — pembuluh yang membawa darah dari jantung</li>
<li><strong>Vena</strong> — pembuluh yang membawa darah kembali ke jantung</li>
</ul>
<h2>🍎 Sistem Pencernaan</h2>
<p>Makanan dicerna mulai dari mulut → kerongkongan → lambung → usus halus → usus besar → anus.</p>',
                'urutan'    => 2,
            ],
            [
                'bab'       => 'Bab 3',
                'judul'     => 'Cahaya & Sifat-sifatnya',
                'deskripsi' => 'Memahami sifat cahaya seperti pemantulan, pembiasan, dan bagaimana cahaya membentuk bayangan.',
                'konten'    => '<h2>💡 Sifat-Sifat Cahaya</h2>
<p>Cahaya adalah gelombang elektromagnetik yang dapat merambat tanpa medium. Cahaya memiliki beberapa sifat unik:</p>
<ul>
<li><strong>Merambat lurus</strong> — cahaya merambat dalam garis lurus</li>
<li><strong>Dapat dipantulkan</strong> — cermin memantulkan cahaya sesuai sudut datang</li>
<li><strong>Dapat dibiaskan</strong> — cahaya berbelok saat melewati medium berbeda (air, kaca)</li>
<li><strong>Dapat diuraikan</strong> — prisma menguraikan cahaya putih menjadi spektrum warna pelangi</li>
</ul>
<blockquote>Pelangi terjadi karena cahaya matahari dibiaskan dan diuraikan oleh tetes-tetes air hujan di udara!</blockquote>',
                'urutan'    => 3,
            ],
            [
                'bab'       => 'Bab 4',
                'judul'     => 'Gaya & Gerak Benda',
                'deskripsi' => 'Pengertian gaya, jenis-jenis gaya, dan bagaimana gaya mempengaruhi gerak suatu benda.',
                'konten'    => '<h2>🏋️ Apa itu Gaya?</h2>
<p>Gaya adalah tarikan atau dorongan yang diberikan pada suatu benda. Gaya dapat mengubah bentuk, arah, dan kecepatan benda.</p>
<h2>📋 Jenis-Jenis Gaya</h2>
<ul>
<li><strong>Gaya otot</strong> — gaya yang dihasilkan oleh otot manusia/hewan</li>
<li><strong>Gaya gravitasi</strong> — gaya tarik bumi terhadap semua benda</li>
<li><strong>Gaya magnet</strong> — gaya tarik/tolak antara benda magnetik</li>
<li><strong>Gaya gesek</strong> — gaya yang menghambat gerak benda pada permukaan</li>
<li><strong>Gaya pegas</strong> — gaya yang dihasilkan pegas yang diregangkan</li>
</ul>
<p>Satuan gaya dalam SI adalah <strong>Newton (N)</strong>, diambil dari nama ilmuwan Isaac Newton.</p>',
                'urutan'    => 4,
            ],
            [
                'bab'       => 'Bab 5',
                'judul'     => 'Tumbuhan & Proses Fotosintesis',
                'deskripsi' => 'Bagaimana tumbuhan membuat makanan sendiri melalui proses fotosintesis menggunakan cahaya matahari.',
                'konten'    => '<h2>🌱 Fotosintesis</h2>
<p>Fotosintesis adalah proses pembuatan makanan oleh tumbuhan menggunakan cahaya matahari, air, dan karbon dioksida.</p>
<h2>⚗️ Rumus Fotosintesis</h2>
<p><strong>6CO₂ + 6H₂O + cahaya → C₆H₁₂O₆ + 6O₂</strong></p>
<p>Artinya: karbon dioksida + air + cahaya matahari menghasilkan gula (glukosa) + oksigen.</p>
<h2>🍃 Bagian Tumbuhan</h2>
<ul>
<li><strong>Daun</strong> — tempat utama fotosintesis, mengandung klorofil</li>
<li><strong>Akar</strong> — menyerap air dan mineral dari tanah</li>
<li><strong>Batang</strong> — saluran air dan nutrisi ke seluruh bagian tumbuhan</li>
<li><strong>Bunga</strong> — alat reproduksi tumbuhan</li>
</ul>
<blockquote>Tumbuhan adalah produsen di alam. Tanpa fotosintesis, tidak ada kehidupan di bumi!</blockquote>',
                'urutan'    => 5,
            ],
        ];

        $materiIPS = [
            [
                'bab'       => 'Bab 1',
                'judul'     => 'Kondisi Geografis Indonesia',
                'deskripsi' => 'Letak geografis Indonesia sebagai negara kepulauan, kondisi alam, dan pengaruhnya terhadap kehidupan masyarakat.',
                'konten'    => '<h2>🗺️ Letak Geografis Indonesia</h2>
<p>Indonesia terletak di antara dua benua (Asia dan Australia) dan dua samudra (Pasifik dan Hindia). Indonesia adalah negara kepulauan terbesar di dunia dengan lebih dari 17.000 pulau.</p>
<h2>⛰️ Bentang Alam Indonesia</h2>
<ul>
<li><strong>Pegunungan</strong> — Indonesia memiliki banyak gunung berapi aktif (ring of fire)</li>
<li><strong>Dataran Rendah</strong> — banyak dimanfaatkan untuk pertanian dan permukiman</li>
<li><strong>Perairan</strong> — laut, sungai, dan danau yang kaya sumber daya alam</li>
</ul>
<blockquote>Indonesia dilintasi garis khatulistiwa sehingga memiliki iklim tropis dengan dua musim: hujan dan kemarau.</blockquote>',
                'urutan'    => 1,
            ],
            [
                'bab'       => 'Bab 2',
                'judul'     => 'Keragaman Budaya Indonesia',
                'deskripsi' => 'Indonesia memiliki ratusan suku, bahasa, dan tradisi yang menjadi kekayaan budaya bangsa.',
                'konten'    => '<h2>🎭 Keanekaragaman Budaya</h2>
<p>Indonesia memiliki lebih dari 300 suku bangsa dengan bahasa dan budaya yang berbeda-beda. Keberagaman ini adalah kekuatan bangsa Indonesia.</p>
<h2>🎨 Bentuk Kebudayaan</h2>
<ul>
<li><strong>Bahasa daerah</strong> — lebih dari 700 bahasa daerah di seluruh Indonesia</li>
<li><strong>Pakaian adat</strong> — setiap daerah memiliki pakaian tradisional khas</li>
<li><strong>Tarian daerah</strong> — Tari Saman, Tari Kecak, Tari Pendet, dll</li>
<li><strong>Rumah adat</strong> — Joglo (Jawa), Gadang (Sumbar), Tongkonan (Toraja)</li>
<li><strong>Makanan khas</strong> — rendang, soto, nasi goreng, gado-gado</li>
</ul>
<blockquote>Bhinneka Tunggal Ika — Berbeda-beda tetapi tetap satu jua!</blockquote>',
                'urutan'    => 2,
            ],
            [
                'bab'       => 'Bab 3',
                'judul'     => 'Sumber Daya Alam Indonesia',
                'deskripsi' => 'Mengenal kekayaan sumber daya alam Indonesia yang melimpah dan cara memanfaatkannya secara bijak.',
                'konten'    => '<h2>⛏️ Sumber Daya Alam (SDA)</h2>
<p>SDA adalah segala sesuatu yang berasal dari alam dan dapat dimanfaatkan oleh manusia untuk memenuhi kebutuhan hidup.</p>
<h2>📋 Jenis SDA</h2>
<ul>
<li><strong>SDA Dapat Diperbaharui</strong> — hutan, air, tanah, hewan ternak, hasil pertanian</li>
<li><strong>SDA Tidak Dapat Diperbaharui</strong> — minyak bumi, batu bara, gas alam, emas, tembaga</li>
</ul>
<h2>🌳 Cara Menjaga SDA</h2>
<ul>
<li>Tidak menebang hutan sembarangan (reboisasi)</li>
<li>Menghemat penggunaan air bersih</li>
<li>Menggunakan energi terbarukan (matahari, angin)</li>
<li>Daur ulang sampah untuk mengurangi limbah</li>
</ul>',
                'urutan'    => 3,
            ],
            [
                'bab'       => 'Bab 4',
                'judul'     => 'Kegiatan Ekonomi Masyarakat',
                'deskripsi' => 'Jenis-jenis kegiatan ekonomi seperti produksi, distribusi, dan konsumsi beserta contohnya dalam kehidupan sehari-hari.',
                'konten'    => '<h2>💼 Kegiatan Ekonomi</h2>
<p>Kegiatan ekonomi adalah semua aktivitas manusia yang bertujuan untuk memenuhi kebutuhan hidup.</p>
<h2>🔄 Tiga Kegiatan Ekonomi Utama</h2>
<ul>
<li><strong>Produksi</strong> — kegiatan membuat/menghasilkan barang atau jasa. Contoh: petani menanam padi, pabrik membuat sepatu</li>
<li><strong>Distribusi</strong> — kegiatan menyalurkan barang dari produsen ke konsumen. Contoh: pedagang, ekspedisi, toko online</li>
<li><strong>Konsumsi</strong> — kegiatan menggunakan/menghabiskan barang atau jasa. Contoh: makan, berpakaian, naik angkutan</li>
</ul>
<blockquote>Setiap orang pasti melakukan kegiatan ekonomi setiap harinya, baik sebagai produsen maupun konsumen!</blockquote>',
                'urutan'    => 4,
            ],
            [
                'bab'       => 'Bab 5',
                'judul'     => 'Sejarah & Peristiwa Kemerdekaan Indonesia',
                'deskripsi' => 'Perjalanan bangsa Indonesia menuju kemerdekaan pada 17 Agustus 1945 dan tokoh-tokoh pejuang yang berjasa.',
                'konten'    => '<h2>🇮🇩 Proklamasi Kemerdekaan</h2>
<p>Indonesia merdeka pada tanggal <strong>17 Agustus 1945</strong>. Proklamasi dibacakan oleh <strong>Ir. Soekarno</strong> didampingi <strong>Drs. Mohammad Hatta</strong> di Jalan Pegangsaan Timur No. 56, Jakarta.</p>
<h2>⚔️ Perjuangan Menuju Merdeka</h2>
<ul>
<li>Indonesia dijajah Belanda selama ±350 tahun</li>
<li>Kemudian dijajah Jepang selama 3,5 tahun (1942–1945)</li>
<li>Para pahlawan berjuang dengan senjata dan diplomasi</li>
</ul>
<h2>🌟 Tokoh-Tokoh Pahlawan</h2>
<ul>
<li><strong>Ir. Soekarno</strong> — Proklamator & Presiden RI pertama</li>
<li><strong>Drs. Mohammad Hatta</strong> — Proklamator & Wakil Presiden RI pertama</li>
<li><strong>Cut Nyak Dien</strong> — Pahlawan wanita dari Aceh</li>
<li><strong>Pangeran Diponegoro</strong> — Memimpin Perang Jawa 1825–1830</li>
</ul>
<blockquote>Kemerdekaan Indonesia bukan hadiah, melainkan hasil perjuangan dan pengorbanan para pahlawan bangsa.</blockquote>',
                'urutan'    => 5,
            ],
        ];

        $urutan = 1;
        foreach ($materiIPA as $data) {
            Materi::updateOrCreate(
                ['judul' => $data['judul'], 'mata_pelajaran_id' => $ipa->id],
                array_merge($data, [
                    'mata_pelajaran_id' => $ipa->id,
                    'user_id'           => $admin->id,
                    'dipublikasi'       => true,
                ])
            );
        }

        foreach ($materiIPS as $data) {
            Materi::updateOrCreate(
                ['judul' => $data['judul'], 'mata_pelajaran_id' => $ips->id],
                array_merge($data, [
                    'mata_pelajaran_id' => $ips->id,
                    'user_id'           => $admin->id,
                    'dipublikasi'       => true,
                ])
            );
        }

        $this->command->info('✅ MateriSeeder: 5 materi IPA + 5 materi IPS selesai.');
    }
}
