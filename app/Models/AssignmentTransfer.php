<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssignmentTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'paket_id',
        'dari_user_id',
        'ke_user_id',
        'tipe_transfer',
        'status',
        'alasan',
        'catatan_admin',
        'disetujui_oleh',
    ];

    /**
     * Relasi ke Paket.
     */
    public function paket(): BelongsTo
    {
        return $this->belongsTo(Paket::class);
    }

    /**
     * Relasi ke User Pengaju (Dari).
     */
    public function dariUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dari_user_id');
    }

    /**
     * Relasi ke User Penerima (Ke).
     */
    public function keUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ke_user_id');
    }

    /**
     * Relasi ke Admin yang memproses (Disetujui Oleh).
     */
    public function disetujuiOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }
}
