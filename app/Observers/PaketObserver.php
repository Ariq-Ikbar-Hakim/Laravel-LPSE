<?php

namespace App\Observers;

use App\Models\Paket;
use App\Models\LogPaket;
use Illuminate\Support\Facades\Auth;

class PaketObserver
{
    /**
     * Handle the Paket "created" event.
     */
    public function created(Paket $paket): void
    {
        LogPaket::create([
            'paket_id' => $paket->id,
            'user_id' => Auth::id() ?? $paket->ppk_id ?? $paket->pp_id ?? 1,
            'aksi' => 'DRAFT',
            'keterangan' => 'Paket pengadaan baru dibuat sebagai draft.',
        ]);
    }

    /**
     * Handle the Paket "updating" event.
     */
    public function updating(Paket $paket): void
    {
        if ($paket->isDirty('status')) {
            $oldStatus = $paket->getOriginal('status');
            $newStatus = $paket->status;

            LogPaket::create([
                'paket_id' => $paket->id,
                'user_id' => Auth::id() ?? $paket->ppk_id ?? $paket->pp_id ?? 1,
                'aksi' => strtoupper($newStatus),
                'keterangan' => "Status paket diubah dari '{$oldStatus}' menjadi '{$newStatus}'.",
            ]);


            // Jika status dirubah ke perlu_revisi, hapus seluruh signatures BA dan kembalikan status BA ke draft
            if ($newStatus === 'perlu_revisi') {
                $ba = \App\Models\BeritaAcara::where('paket_id', $paket->id)->first();
                if ($ba) {
                    // Hapus data signatures
                    $ba->signatures()->delete();
                    
                    // Hapus file fisik PDF lama
                    if ($ba->file_laporan && \Illuminate\Support\Facades\Storage::disk('public')->exists($ba->file_laporan)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($ba->file_laporan);
                    }
                    
                    $ba->update([
                        'status' => 'draft',
                        'file_laporan' => null,
                    ]);
                }
            }
        }
    }
}
