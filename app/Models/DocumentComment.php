<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentComment extends Model
{
    use HasFactory;

    protected $table = 'document_comments';

    protected $fillable = [
        'paket_id',
        'lampiran_id',
        'user_id',
        'role_saat_komentar',
        'komentar',
    ];

    public function paket(): BelongsTo
    {
        return $this->belongsTo(Paket::class, 'paket_id');
    }

    public function lampiran(): BelongsTo
    {
        return $this->belongsTo(Lampiran::class, 'lampiran_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
