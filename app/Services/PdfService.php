<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf as DomPdf;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\View;

class PdfService
{
    /**
     * Generate PDF content from a Blade view using a selectable engine.
     *
     * @param string $template Blade view name (e.g. 'pdf.berita_acara')
     * @param array $data Data passed to the view
     * @param string $engine Engine identifier: 'dompdf' or 'chromium'
     * @return string Raw PDF binary content
     */
    public static function generate(string $template, array $data = [], string $engine = 'chromium'): string
    {
        // Render view to HTML
        $html = View::make($template, $data)->render();
        switch (strtolower($engine)) {
            case 'chromium':
                $tempPath = storage_path('app/temp_' . uniqid() . '.pdf');
                
                $browsershot = Browsershot::html($html)
                    ->format('A4')
                    ->showBackground()
                    ->margin(0);

                // Set Node and NPM binaries explicitly since NVM isn't in PHP's PATH
                $nodePath = env('NODE_BINARY_PATH', '/home/ariq/.nvm/versions/node/v24.19.0/bin/node');
                $npmPath = env('NPM_BINARY_PATH', '/home/ariq/.nvm/versions/node/v24.19.0/bin/npm');
                
                if (file_exists($nodePath)) {
                    $browsershot->setNodeBinary($nodePath);
                }
                if (file_exists($npmPath)) {
                    $browsershot->setNpmBinary($npmPath);
                }

                $browsershot->save($tempPath);

                $pdfContent = file_get_contents($tempPath);
                @unlink($tempPath);
                return $pdfContent;
            case 'dompdf':
            default:
                $pdf = DomPdf::loadHTML($html);
                $pdf->setPaper('a4', 'portrait');
                return $pdf->output();
        }
    }
}
