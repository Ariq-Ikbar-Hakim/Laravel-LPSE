<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogPaket extends Model
{
    use HasFactory;

    protected $table = 'log_paket';

    // Tabel log_paket hanya memiliki created_at, tidak ada updated_at
    const UPDATED_AT = null;

    protected $fillable = [
        'paket_id',
        'user_id',
        'aksi',
        'keterangan',
    ];

    public function paket(): BelongsTo
    {
        return $this->belongsTo(Paket::class, 'paket_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
