<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserObserver
{
    /**
     * Handle the User "updating" event.
     */
    public function updating(User $user): void
    {
        if ($user->isDirty('jabatan_aktif')) {
            $oldRole = $user->getOriginal('jabatan_aktif');
            $newRole = $user->jabatan_aktif;

            DB::table('user_role_history')->insert([
                'user_id' => $user->id,
                'jabatan_lama' => $oldRole,
                'jabatan_baru' => $newRole,
                'diubah_oleh' => Auth::id() ?? 1, // Fallback ke 1 (Admin) jika dipicu dari seeder / testing CLI
                'created_at' => now(),
            ]);
        }
    }
}
