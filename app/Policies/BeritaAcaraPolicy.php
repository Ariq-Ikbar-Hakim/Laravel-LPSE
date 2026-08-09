<?php

namespace App\Policies;

use App\Models\BeritaAcara;
use App\Models\User;

class BeritaAcaraPolicy
{
    /**
     * Determine whether the user can view the Berita Acara.
     */
    public function view(User $user, BeritaAcara $beritaAcara): bool
    {
        if ($user->jabatan_aktif === 'admin') {
            return true;
        }

        $paket = $beritaAcara->paket;

        if ($user->jabatan_aktif === 'PP') {
            return $paket->pp_id === $user->id;
        }

        if ($user->jabatan_aktif === 'PPK') {
            return $paket->ppk_id === null || $paket->ppk_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can sign as PP.
     */
    public function signAsPp(User $user, BeritaAcara $beritaAcara): bool
    {
        if ($user->jabatan_aktif !== 'PP') {
            return false;
        }

        $paket = $beritaAcara->paket;

        // Harus merupakan PP yang ditugaskan ke paket ini
        if ($paket->pp_id !== $user->id) {
            return false;
        }

        // PP hanya bisa tanda tangan jika belum tanda tangan (status BA: draft)
        return $beritaAcara->status === 'draft' && !$beritaAcara->hasSignatureFrom('PP');
    }

    /**
     * Determine whether the user can sign as PPK.
     */
    public function signAsPpk(User $user, BeritaAcara $beritaAcara): bool
    {
        if ($user->jabatan_aktif !== 'PPK') {
            return false;
        }

        $paket = $beritaAcara->paket;

        // Harus merupakan PPK yang membuat paket (atau jika bypass/manual, semua PPK aktif bisa tanda tangan)
        if ($paket->ppk_id !== null && $paket->ppk_id !== $user->id) {
            return false;
        }

        // PPK tidak bisa tanda tangan sebelum PP menandatangani
        if ($beritaAcara->status !== 'tanda_tangan_pertama' || !$beritaAcara->hasSignatureFrom('PP')) {
            return false;
        }

        // Khusus jalur Manual (bypass PP, ppk_id === null): 
        // PPK diblokir jika belum ada minimal satu lampiran dengan status validasi 'disetujui'
        if ($paket->ppk_id === null) {
            $hasApprovedLampiran = $paket->lampiran()->where('status_validasi', 'disetujui')->exists();
            if (!$hasApprovedLampiran) {
                return false;
            }
        }

        // Dan PPK belum menandatangani
        return !$beritaAcara->hasSignatureFrom('PPK');
    }
}
