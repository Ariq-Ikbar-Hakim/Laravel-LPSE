<?php

namespace App\Http\Controllers;

use App\Models\BeritaAcara;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /**
     * Display public verification details.
     */
    public function verify($hash)
    {
        $beritaAcara = BeritaAcara::where('verification_hash', $hash)
            ->with(['paket.ppk', 'paket.pp', 'signatures.user'])
            ->firstOrFail();

        return view('verify', compact('beritaAcara'));
    }

    /**
     * Verify uploaded PDF file integrity.
     */
    public function verifyFile(Request $request, $hash)
    {
        $beritaAcara = BeritaAcara::where('verification_hash', $hash)->firstOrFail();

        $request->validate([
            'uploaded_pdf' => ['required', 'file', 'mimes:pdf', 'max:20480'], // Max 20MB
        ]);

        $uploadedHash = hash_file('sha256', $request->file('uploaded_pdf')->path());
        $registeredHash = $beritaAcara->signatures()->value('hash_dokumen');

        $isValid = ($uploadedHash === $registeredHash);

        return redirect()->back()->with([
            'file_verified' => true,
            'is_valid' => $isValid,
            'uploaded_hash' => $uploadedHash,
            'registered_hash' => $registeredHash,
        ]);
    }
}
