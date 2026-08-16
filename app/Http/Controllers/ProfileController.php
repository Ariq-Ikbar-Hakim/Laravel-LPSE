<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        $validated = $request->validated();
        // Hapus foto_profil dari data validated agar tidak menimpa foto profil lama dengan null
        unset($validated['foto_profil']);
        
        $user->fill($validated);

        if ($request->input('remove_photo') == 1) {
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
                $user->foto_profil = null;
            }
        }

        if ($request->hasFile('foto_profil')) {
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            $path = $request->file('foto_profil')->store('avatars', 'public');
            $user->foto_profil = $path;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Tampilkan form pengajuan reset password untuk user yang login.
     */
    public function requestReset(Request $request): View
    {
        return view('profile.request-reset', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Simpan pengajuan reset password.
     */
    public function storeRequestReset(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        $user->update([
            'reset_requested_at' => now(),
        ]);

        return redirect()->back()->with([
            'status' => 'success_request',
            'requested_user_nip' => $user->nip,
            'requested_user_nama' => $user->nama,
            'requested_user_email' => $user->email,
        ]);
    }
}
