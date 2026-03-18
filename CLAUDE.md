# GAS-IPAS — Panel Guru (Filament v3)

## Stack
- Laravel 12 + Filament v3 + MySQL
- Guard: `guru` → model `App\Models\Guru`
- Path panel: `/guru`
- Warna primary: Teal
- Semua nama tabel pakai Bahasa Indonesia

---

## Tabel Database (13 tabel)

| Nama Tabel | Field | Keterangan |
|---|---|---|
| `guru` | id, username(UQ), nama, password, remember_token, terakhir_login, timestamps | Akun login Filament |
| `mata_pelajaran` | id, nama, jenis(IPA/IPS), ikon, warna, urutan, timestamps | Master mapel |
| `siswa` | id, nis(UQ), kode_siswa(UQ), nama_lengkap, nama_panggilan, kelas, jenis_kelamin(L/P), foto, aktif, total_poin, streak_sekarang, streak_terpanjang, terakhir_aktif, timestamps | Data siswa + gamifikasi |
| `materi` | id, mata_pelajaran_id(FK), guru_id(FK), judul, bab, deskripsi, konten(longtext), gambar_sampul, file_pdf, dipublikasi(bool), urutan, timestamps | Konten per bab |
| `soal` | id, mata_pelajaran_id(FK), guru_id(FK), teks_soal, gambar_soal, opsi_a, gambar_opsi_a, opsi_b, gambar_opsi_b, opsi_c, gambar_opsi_c, opsi_d, gambar_opsi_d, jawaban_benar(a/b/c/d), penjelasan, tingkat_kesulitan(mudah/sedang/sulit), timestamps | Bank soal |
| `kuis` | id, mata_pelajaran_id(FK), guru_id(FK), kode_kuis(UQ), judul, deskripsi, jumlah_soal, waktu_per_soal(detik), acak_soal(bool), acak_opsi(bool), tampilkan_penjelasan(bool), aktif(bool), mulai_pada, berakhir_pada, timestamps | Sesi kuis |
| `kuis_soal` | id, kuis_id(FK), soal_id(FK), urutan — unique(kuis_id, soal_id) | Pivot kuis ↔ soal |
| `percobaan_kuis` | id, kuis_id(FK), siswa_id(FK), urutan_acak(json), opsi_acak(json), nilai, jumlah_benar, jumlah_salah, total_soal, poin_diperoleh, waktu_pengerjaan(detik), status(berlangsung/selesai), dimulai_pada, diselesaikan_pada, timestamps | Hasil kuis siswa |
| `jawaban_percobaan` | id, percobaan_id(FK), soal_id(FK), jawaban_dipilih(a/b/c/d), benar(bool), waktu_menjawab(detik), poin_diperoleh, dijawab_pada | Jawaban per soal |
| `lencana` | id, kunci_lencana(UQ), nama, deskripsi, ikon, warna, jenis_kondisi(login/baca/kuis/nilai/streak/poin/peringkat), nilai_kondisi, timestamps | Definisi badge |
| `lencana_siswa` | id, siswa_id(FK), lencana_id(FK), diperoleh_pada — unique(siswa_id, lencana_id) | Badge milik siswa |
| `log_poin` | id, siswa_id(FK), poin, sumber(kuis/materi/streak/bonus), sumber_id, keterangan, timestamps | Riwayat poin |
| `riwayat_baca` | id, siswa_id(FK), materi_id(FK), dibaca_pada, poin_diperoleh(default 3) — unique(siswa_id, materi_id) | Materi dibaca siswa |

---

## Auth Guard (config/auth.php)

```
guards    → guru  (session, provider: guru)
            siswa (session, provider: siswa) ← untuk fitur siswa nanti
providers → guru  (eloquent, model: Guru)
            siswa (eloquent, model: Siswa)
passwords → guru  (provider: guru, table: password_reset_tokens)
```

---

## Filament Panel Provider (GuruPanelProvider)

```
id                : guru
path              : /guru
login             : aktif
authGuard         : guru
authPasswordBroker: guru
brandName         : GAS-IPAS
colors primary    : Teal
navigationGroups  : Konten Pembelajaran | Bank Soal & Kuis | Data Siswa | Laporan
discoverResources : app/Filament/Guru/Resources
discoverPages     : app/Filament/Guru/Pages
discoverWidgets   : app/Filament/Guru/Widgets
widgets           : StatistikOverviewWidget, GrafikNilaiWidget
```

---

## Resources (6 resource)

### 1. MateriResource
```
navigationGroup : Konten Pembelajaran
model           : Materi

FORM:
- Select mata_pelajaran_id (required)
- TextInput judul (required)
- TextInput bab (numeric)
- Textarea deskripsi
- RichEditor konten (bold, italic, h2, h3, bulletList, orderedList, link)
- FileUpload gambar_sampul (image, dir: materi/sampul)
- FileUpload file_pdf (pdf only, max 10MB, dir: materi/pdf)
- TextInput urutan (numeric)
- Toggle dipublikasi

TABLE:
- ImageColumn gambar_sampul (circular)
- Badge jenis mapel (IPA=success / IPS=info)
- judul (searchable)
- bab (sortable)
- riwayatBaca_count → suffix 'x'
- IconColumn file_pdf (boolean)
- ToggleColumn dipublikasi
- updated_at

FILTER  : mata_pelajaran_id, dipublikasi (ternary)
ACTIONS : View, Edit, Delete
EXTRA   : guru_id auto-isi dari auth('guru')->id() saat create
```

---

### 2. SoalResource
```
navigationGroup : Bank Soal & Kuis
model           : Soal

FORM:
- Select mata_pelajaran_id (required)
- Select tingkat_kesulitan (mudah/sedang/sulit)
- Textarea teks_soal (required)
- FileUpload gambar_soal (opsional)
- Fieldset Opsi A : Textarea opsi_a + FileUpload gambar_opsi_a
- Fieldset Opsi B : Textarea opsi_b + FileUpload gambar_opsi_b
- Fieldset Opsi C : Textarea opsi_c + FileUpload gambar_opsi_c
- Fieldset Opsi D : Textarea opsi_d + FileUpload gambar_opsi_d
- Radio jawaban_benar inline (a / b / c / d)
- Textarea penjelasan (opsional)

TABLE:
- id
- Badge jenis mapel
- teks_soal (limit 80, searchable)
- Badge jawaban_benar (success, uppercase)
- Badge tingkat_kesulitan (mudah=success / sedang=warning / sulit=danger)
- IconColumn gambar_soal (boolean)
- created_at

FILTER  : mata_pelajaran_id, tingkat_kesulitan
ACTIONS : View, Edit, Delete
EXTRA   : guru_id auto-isi dari auth('guru')->id()
```

---

### 3. KuisResource
```
navigationGroup : Bank Soal & Kuis
model           : Kuis

FORM:
- Select mata_pelajaran_id (required, live/reactive)
- TextInput judul (required)
- Textarea deskripsi
- CheckboxList soal → load soal berdasarkan mata_pelajaran_id yang dipilih (reactive)
  label format: "[KUNCI] teks soal..." — searchable, bulkToggleable
- TextInput jumlah_soal (numeric, default 10)
- TextInput waktu_per_soal (detik, default 30, min 5)
- Toggle acak_soal (default true, hint: Fisher-Yates Shuffle)
- Toggle acak_opsi (default true)
- Toggle tampilkan_penjelasan (default true)
- DateTimePicker mulai_pada (nullable)
- DateTimePicker berakhir_pada (nullable, after: mulai_pada)
- Toggle aktif (default false)

TABLE:
- kode_kuis (badge primary, copyable)
- judul (limit 40, searchable)
- Badge jenis mapel
- soal_count → suffix ' soal'
- waktu_per_soal → suffix ' dtk'
- percobaanKuis_count → label 'Peserta'
- ToggleColumn aktif
- mulai_pada (placeholder: Tidak dibatasi)

FILTER  : mata_pelajaran_id, aktif (ternary)
ACTIONS : View, Edit, Delete
ACTION TAMBAHAN: toggle_aktif (Aktifkan/Nonaktifkan — ikon & warna berubah sesuai status)
EXTRA   : kode_kuis auto-generate 6 karakter uppercase unik di Model boot()
          guru_id auto-isi dari auth('guru')->id()
```

---

### 4. SiswaResource
```
navigationGroup : Data Siswa
model           : Siswa

FORM:
- TextInput nama_lengkap (required)
- TextInput nama_panggilan (required, hint: digunakan untuk login)
- TextInput nis (required, unique ignore record)
- TextInput kelas (required, default 'V')
- Select jenis_kelamin (L=Laki-laki / P=Perempuan)
- TextInput kode_siswa (required, unique ignore record)
  + suffixAction tombol "Generate" → auto-buat 6 karakter uppercase unik
- Toggle aktif (default true)
- TextInput total_poin (disabled, read only)
- TextInput streak_sekarang (disabled, read only)

TABLE:
- nis (searchable, sortable)
- nama_lengkap (searchable, sortable)
- nama_panggilan (searchable)
- kelas
- Badge jenis_kelamin (L=info / P=pink)
- kode_siswa (badge gray, copyable)
- total_poin (numeric, sortable)
- streak_sekarang → suffix ' hari'
- IconColumn aktif (boolean)

FILTER  : aktif (ternary), jenis_kelamin
ACTIONS : View, Edit, Delete
ACTION TAMBAHAN: reset_kode → generate ulang kode_siswa baru (dengan konfirmasi)
RELATION MANAGER: PercobaanKuisRelationManager (riwayat kuis siswa)
PAGES   : Index, Create, Edit, View
```

---

### 5. MataPelajaranResource
```
navigationGroup : Konten Pembelajaran
model           : MataPelajaran

FORM:
- TextInput nama (required)
- Select jenis (IPA / IPS)
- TextInput ikon (emoji)
- Select warna (teal/blue/green/amber/purple)
- TextInput urutan (numeric)

TABLE:
- ikon
- nama (searchable)
- Badge jenis (IPA=success / IPS=info)
- urutan (sortable)
- materi_count → label 'Materi'
- soal_count   → label 'Soal'

ACTIONS : Edit, Delete
```

---

### 6. LencanaResource
```
navigationGroup : Data Siswa
model           : Lencana

FORM:
- TextInput kunci_lencana (required, unique ignore record)
- TextInput nama (required)
- Textarea deskripsi
- TextInput ikon (emoji, default 🏅)
- Select warna (green/blue/purple/amber/teal/coral)
- Select jenis_kondisi (login/baca/kuis/nilai/streak/poin/peringkat)
- TextInput nilai_kondisi (numeric, hint: angka syarat terpenuhi)

TABLE:
- ikon
- nama (searchable)
- Badge jenis_kondisi
- nilai_kondisi → label 'Syarat'
- siswa_count via lencana_siswa → label 'Diraih'

ACTIONS : Edit, Delete
```

---

## Relation Manager

```
Resource : SiswaResource
Nama     : PercobaanKuisRelationManager
Relasi   : percobaanKuis

TABLE:
- kuis.judul (limit 40)
- nilai → badge (≥80=success / ≥60=warning / <60=danger), suffix '/100'
- jumlah_benar
- total_soal
- poin_diperoleh
- Badge status (selesai=success / berlangsung=warning)
- diselesaikan_pada

DEFAULT SORT: diselesaikan_pada desc
```

---

## Widgets Dashboard

### StatistikOverviewWidget (StatsOverviewWidget)
```
Stats (6 kartu):
1. Total Siswa aktif            icon: user-group           color: success
2. Total Materi dipublikasi     icon: book-open            color: info
3. Bank Soal (semua)            icon: question-mark-circle color: warning
4. Kuis Aktif                   icon: clipboard-document   color: primary
5. Total Percobaan selesai      icon: check-circle         color: success
6. Rata-rata Nilai semua kuis   icon: chart-bar            color: teal
```

### GrafikNilaiWidget (ChartWidget — line)
```
Heading : Aktivitas Kuis (7 Hari Terakhir)
Data    : hitung percobaan_kuis selesai per hari, 7 hari ke belakang
Color   : #0F6E56
```

---

## Custom Pages

### LaporanNilai
```
navigationGroup : Laporan
navigationIcon  : heroicon-o-chart-bar
Implementasi   : HasTable + InteractsWithTable
Query          : percobaan_kuis where status=selesai, with siswa + kuis.mataPelajaran

TABLE:
- siswa.nama_lengkap (searchable)
- siswa.nis (searchable)
- kuis.judul (limit 40)
- Badge jenis mapel
- nilai → badge (≥80=success / ≥60=warning / <60=danger), sortable
- jumlah_benar
- total_soal
- poin_diperoleh (sortable)
- waktu_pengerjaan (format mm:ss)
- diselesaikan_pada (sortable)

FILTER       : SelectFilter kuis_id
DEFAULT SORT : diselesaikan_pada desc
```

### PapanPeringkat
```
navigationGroup : Laporan
navigationIcon  : heroicon-o-trophy
Query          : siswa aktif, withCount lencana, orderByDesc total_poin, limit 50
Tampilan       : tabel blade manual di dalam view Filament

KOLOM:
- Peringkat: 🥇🥈🥉 untuk top 3, angka untuk sisanya
- nama_lengkap + nis (sub-text)
- kelas
- total_poin (badge teal)
- streak_sekarang → "🔥 N hari"
- lencana_count  → "🏅 N"
```

---

## Seeders

```
GuruSeeder          → username: admin | password: password123
MataPelajaranSeeder → IPA (🔬 teal urutan 1) dan IPS (🌏 blue urutan 2)
LencanaSeeder       → 10 lencana:

  kunci_lencana    nama             jenis_kondisi  nilai_kondisi
  login_pertama    Penjelajah Baru  login          1
  baca_3_materi    Pembaca Rajin    baca           3
  ikut_kuis        Peserta Aktif    kuis           1
  nilai_sempurna   Nilai Sempurna   nilai          100
  streak_3_hari    Streak 3 Hari    streak         3
  streak_7_hari    Streak 7 Hari    streak         7
  poin_500         Poin Master      poin           500
  pakar_ipa        Pakar IPA        kuis           4
  pakar_ips        Pakar IPS        kuis           4
  juara_kelas      Juara Kelas      peringkat      1
```

---

## Hal Tambahan yang Wajib Dikerjakan

```
1. config/auth.php       → tambahkan guard guru + siswa (siswa untuk fitur berikutnya)
2. Model Kuis boot()     → auto-generate kode_kuis 6 karakter uppercase unik saat creating
3. Semua Resource        → mutateFormDataBeforeCreate() → auto-isi guru_id dari auth('guru')->id()
4. php artisan storage:link → agar file upload bisa diakses publik
5. .env                  → FILESYSTEM_DISK=public, pastikan DB_* sudah benar
6. bootstrap/providers.php → pastikan GuruPanelProvider terdaftar
```

---

## Perintah Akhir

```bash
php artisan migrate:fresh --seed
php artisan storage:link
php artisan config:cache
php artisan serve
```

Akses panel di: **http://localhost:8000/guru**
Login: `admin` / `password123`

---

## Checklist

- [ ] 13 migration selesai dan berhasil
- [ ] 12 model ada di app/Models/ dengan relasi lengkap
- [ ] config/auth.php sudah ada guard guru + siswa
- [ ] GuruPanelProvider terdaftar di bootstrap/providers.php
- [ ] Panel /guru bisa diakses dan login berhasil
- [ ] CRUD Materi (upload gambar + PDF berfungsi)
- [ ] CRUD Soal (gambar per opsi berfungsi)
- [ ] CRUD Kuis (soal reaktif by mapel, kode auto-generate, toggle aktif)
- [ ] CRUD Siswa (generate kode, reset kode, tab riwayat kuis)
- [ ] Dashboard: 6 stat card + grafik 7 hari tampil
- [ ] Halaman Laporan Nilai tampil
- [ ] Papan Peringkat tampil dengan ranking siswa