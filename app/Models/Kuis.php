<?php

namespace App\Models;

use App\Services\FisherYatesShuffleService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Kuis extends Model
{
    protected $table = 'kuis';

    protected $fillable = [
        'mata_pelajaran_id', 'user_id', 'kode_kuis', 'judul', 'deskripsi',
        'jumlah_soal', 'waktu_per_soal', 'acak_soal', 'acak_opsi',
        'tampilkan_penjelasan', 'aktif', 'mulai_pada', 'berakhir_pada',
    ];

    protected function casts(): array
    {
        return [
            'acak_soal'            => 'boolean',
            'acak_opsi'            => 'boolean',
            'tampilkan_penjelasan' => 'boolean',
            'aktif'                => 'boolean',
            'mulai_pada'           => 'datetime',
            'berakhir_pada'        => 'datetime',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Kuis $kuis) {
            if (empty($kuis->kode_kuis)) {
                do {
                    $kode = strtoupper(Str::random(6));
                } while (self::where('kode_kuis', $kode)->exists());

                $kuis->kode_kuis = $kode;
            }
        });
    }

    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function soal(): BelongsToMany
    {
        return $this->belongsToMany(Soal::class, 'kuis_soal')->withPivot('urutan');
    }

    public function percobaanKuis(): HasMany
    {
        return $this->hasMany(PercobaanKuis::class);
    }

    /**
     * Menghasilkan urutan soal dan pengacakan opsi untuk satu sesi percobaan kuis.
     *
     * Menggunakan Fisher-Yates Shuffle agar setiap siswa mendapat
     * kombinasi soal dan posisi opsi yang berbeda-beda.
     *
     * @return array{soal_ids: array, opsi_acak: array}
     */
    public function buatPengacakan(): array
    {
        $semuaSoalIds = $this->soal()->pluck('soal.id')->toArray();

        $soalIds = $this->acak_soal
            ? FisherYatesShuffleService::acakSoal($semuaSoalIds, $this->jumlah_soal)
            : array_slice($semuaSoalIds, 0, $this->jumlah_soal);

        $opsiAcak = $this->acak_opsi
            ? FisherYatesShuffleService::acakOpsiPerSoal($soalIds)
            : [];

        return [
            'soal_ids'  => $soalIds,
            'opsi_acak' => $opsiAcak,
        ];
    }
}
