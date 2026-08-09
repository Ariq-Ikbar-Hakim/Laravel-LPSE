<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Signature extends Model
{
    use HasFactory;

    protected $table = 'signatures';

    protected $fillable = [
        'berita_acara_id',
        'user_id',
        'role_saat_ttd',
        'urutan',
        'qr_code_path',
        'hash_dokumen',
        'ip_address',
        'signed_at',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
    ];

    public function beritaAcara(): BelongsTo
    {
        return $this->belongsTo(BeritaAcara::class, 'berita_acara_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
