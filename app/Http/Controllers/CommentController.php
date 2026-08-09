<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\DocumentComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, Paket $paket)
    {
        Gate::authorize('view', $paket);

        $request->validate([
            'komentar' => ['required', 'string', 'max:2000'],
            'lampiran_id' => ['nullable', 'exists:lampiran,id'],
        ]);

        DocumentComment::create([
            'paket_id' => $paket->id,
            'lampiran_id' => $request->lampiran_id,
            'user_id' => Auth::id(),
            'role_saat_komentar' => Auth::user()->jabatan_aktif,
            'komentar' => $request->komentar,
        ]);

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan.');
    }
}
