<?php

namespace App\Policies;

use App\Models\Paket;
use App\Models\User;

class PaketPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Paket $paket): bool
    {
        if ($user->jabatan_aktif === 'admin') {
            return true;
        }

        if ($user->jabatan_aktif === 'PPK') {
            return $paket->ppk_id === $user->id;
        }

        if ($user->jabatan_aktif === 'PP') {
            return $paket->pp_id === $user->id && $paket->status !== 'draft';
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Paket $paket): bool
    {
        if ($user->jabatan_aktif === 'PPK') {
            return $paket->ppk_id === $user->id && in_array($paket->status, ['draft', 'perlu_revisi']);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Paket $paket): bool
    {
        if ($user->jabatan_aktif === 'PPK') {
            return $paket->ppk_id === $user->id && $paket->status === 'draft';
        }

        return false;
    }
}
