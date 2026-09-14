<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf as DomPdf;
use Illuminate\Support\Facades\View;
use Spatie\Browsershot\Browsershot;

class PdfService
{
    /**
     * Generate PDF content from a Blade view using a selectable engine.
     *
     * @param  string  $template  Blade view name (e.g. 'pdf.berita_acara')
     * @param  array  $data  Data passed to the view
     * @param  string  $engine  Engine identifier: 'dompdf' or 'chromium'
     * @param  array  $options  Options for chromium (margins, footerHtml, etc)
     * @return string Raw PDF binary content
     */
    public function generate(string $template, array $data = [], string $engine = 'chromium', array $options = []): string
    {
        // Render view to HTML
        $html = View::make($template, $data)->render();
        // Fall back to Dompdf when Chromium is unavailable (for local/test environments).
        // Production Docker sets CHROME_PATH=/usr/bin/chromium.
        if (strtolower($engine) === 'chromium' && config('pdf.chrome_path') && ! is_file(config('pdf.chrome_path'))) {
            $engine = 'dompdf';
        }
        if (strtolower($engine) === 'chromium' && ! config('pdf.chrome_path') && app()->environment('testing')) {
            $engine = 'dompdf';
        }
        switch (strtolower($engine)) {
            case 'chromium':

                $browsershot = Browsershot::html($html)
                    ->format('A4')
                    ->showBackground();

                // If footer or header is provided, enable browser header and footer
                if (isset($options['footerHtml']) || isset($options['headerHtml'])) {
                    $browsershot->showBrowserHeaderAndFooter();

                    if (isset($options['headerHtml'])) {
                        $browsershot->headerHtml($options['headerHtml']);
                    } else {
                        $browsershot->headerHtml('<span></span>'); // Prevent default URL
                    }

                    if (isset($options['footerHtml'])) {
                        $browsershot->footerHtml($options['footerHtml']);
                    } else {
                        $browsershot->footerHtml('<span></span>'); // Prevent default URL
                    }
                }

                // Apply margins if specified, else default to 0
                if (isset($options['margins'])) {
                    $m = $options['margins'];
                    $browsershot->margins($m['top'] ?? 0, $m['right'] ?? 0, $m['bottom'] ?? 0, $m['left'] ?? 0);
                } else {
                    $browsershot->margins(0, 0, 0, 0);
                }

                // Set Node and NPM binaries explicitly since NVM isn't in PHP's PATH
                $nodePath = config('pdf.node_binary');
                $npmPath = config('pdf.npm_binary');

                if ($nodePath) {
                    $browsershot->setNodeBinary($nodePath);
                }
                if ($npmPath) {
                    $browsershot->setNpmBinary($npmPath);
                }

                if (config('pdf.chrome_path')) {
                    $browsershot->setChromePath(config('pdf.chrome_path'));
                }
                if (config('pdf.no_sandbox')) {
                    $browsershot->noSandbox();
                }

                return $browsershot->pdf();
            case 'dompdf':
            default:
                $pdf = DomPdf::loadHTML($html);
                $pdf->setPaper('a4', 'portrait');

                return $pdf->output();
        }
    }
}
