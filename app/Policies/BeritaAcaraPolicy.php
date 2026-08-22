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

        // Jika sudah ditandatangani PPK, jangan bisa tanda tangan lagi (biar tidak dobel)
        if ($beritaAcara->hasSignatureFrom('PPK')) {
            return false;
        }

        // PPK tidak bisa tanda tangan sebelum PP menandatangani
        // Tapi jika status sudah selesai atau tanda_tangan_pertama, boleh.
        if ($beritaAcara->status !== 'tanda_tangan_pertama' && $beritaAcara->status !== 'selesai' && !$beritaAcara->hasSignatureFrom('PP')) {
            return false;
        }

        return true;
    }
}
