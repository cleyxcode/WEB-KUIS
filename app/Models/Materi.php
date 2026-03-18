<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Materi extends Model
{
    protected $table = 'materi';

    protected $fillable = [
        'mata_pelajaran_id', 'user_id', 'judul', 'bab',
        'deskripsi', 'konten', 'gambar_sampul', 'file_pdf',
        'dipublikasi', 'urutan',
    ];

    protected $casts = [
        'dipublikasi' => 'boolean',
    ];

    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function riwayatBaca(): HasMany
    {
        return $this->hasMany(RiwayatBaca::class);
    }
}
