<?php

namespace App\Console\Commands;

use App\Models\BeritaAcara;
use App\Services\PdfService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RepairBeritaAcaraPdf extends Command
{
    protected $signature = 'ba:repair-pdf {id : ID Berita Acara}';

    protected $description = 'Memulihkan PDF BA selesai yang belum pernah berhasil dibuat';

    public function handle(PdfService $pdf): int
    {
        $path = null;
        try {
            DB::transaction(function () use ($pdf, &$path) {
                $ba = BeritaAcara::whereKey($this->argument('id'))->lockForUpdate()->firstOrFail();
                if ($ba->status !== 'selesai' || ! $ba->hasSignatureFrom('PP') || ! $ba->hasSignatureFrom('PPK')) {
                    throw new \RuntimeException('BA harus sudah ditandatangani PP dan PPK.');
                }
                // Jangan mengubah hash dokumen yang pernah diterbitkan: pulihkan dari backup.
                if ($ba->file_laporan || $ba->signatures()->whereNotNull('hash_dokumen')->exists()) {
                    throw new \RuntimeException('Dokumen pernah diterbitkan. Pulihkan file asli dari backup agar hash tetap cocok.');
                }
                $content = $pdf->generate('pdf.berita_acara', [
                    'beritaAcara' => $ba, 'paket' => $ba->paket, 'signatures' => $ba->signatures,
                ], 'chromium', ['margins' => ['top' => 25, 'right' => 20, 'bottom' => 30, 'left' => 20]]);
                $path = 'berita-acara/BA_Paket_'.$ba->paket_id.'_'.Str::uuid().'.pdf';
                if (! Storage::disk('public')->put($path, $content)) {
                    throw new \RuntimeException('PDF gagal disimpan.');
                }
                $ba->update(['file_laporan' => $path]);
                $ba->signatures()->update(['hash_dokumen' => hash('sha256', $content)]);
                activity()->performedOn($ba)->log('PDF_BA_DIPULIHKAN');
            });
        } catch (\Throwable $exception) {
            if ($path) {
                Storage::disk('public')->delete($path);
            }
            report($exception);
            $this->error($exception->getMessage());

            return self::FAILURE;
        }
        $this->info('PDF berhasil dipulihkan tanpa mengubah tanda tangan.');

        return self::SUCCESS;
    }
}
