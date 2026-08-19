<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BeritaAcara extends Model
{
    use HasFactory;

    protected $table = 'berita_acara';

    protected $fillable = [
        'paket_id',
        'nomor_ba',
        'tanggal_ba',
        'file_laporan',
        'verification_hash',
        'status',
    ];

    protected $casts = [
        'tanggal_ba' => 'date',
    ];

    public function paket(): BelongsTo
    {
        return $this->belongsTo(Paket::class, 'paket_id');
    }

    public function signatures(): HasMany
    {
        return $this->hasMany(Signature::class, 'berita_acara_id');
    }

    /**
     * Check if a signature for a role exists.
     */
    public function hasSignatureFrom(string $role): bool
    {
        return $this->signatures()->where('role_saat_ttd', $role)->exists();
    }

    /**
     * Get the PP signature.
     */
    public function ppSignature()
    {
        return $this->signatures()->where('role_saat_ttd', 'PP')->first();
    }

    /**
     * Get the PPK signature.
     */
    public function ppkSignature()
    {
        return $this->signatures()->where('role_saat_ttd', 'PPK')->first();
    }
}
