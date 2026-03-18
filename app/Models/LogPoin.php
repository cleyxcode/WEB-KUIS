<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogPoin extends Model
{
    protected $table = 'log_poin';

    protected $fillable = [
        'siswa_id', 'poin', 'sumber', 'sumber_id', 'keterangan',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }
}
