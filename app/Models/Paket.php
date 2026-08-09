<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paket extends Model
{
    use HasFactory;

    protected $table = 'paket';

    protected $fillable = [
        'ppk_id',
        'pp_id',
        'kode_rup',
        'nama_paket',
        'pagu',
        'status',
        'dilihat_admin_at',
        'metode',
        'sumber_dana',
        'jenis',
    ];

    protected $casts = [
        'dilihat_admin_at' => 'datetime',
        'pagu' => 'decimal:2',
    ];

    public function ppk(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ppk_id');
    }

    public function pp(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pp_id');
    }

    public function lampiran(): HasMany
    {
        return $this->hasMany(Lampiran::class, 'paket_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(DocumentComment::class, 'paket_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(LogPaket::class, 'paket_id');
    }

    public function beritaAcara(): HasMany
    {
        return $this->hasMany(BeritaAcara::class, 'paket_id');
    }
}
