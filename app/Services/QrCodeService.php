<?php

namespace App\Services;

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeService
{
    public static function png(string $url): string
    {
        return (new PngWriter)->write(new QrCode(
            data: $url,
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 12,
        ))->getString();
    }
}
