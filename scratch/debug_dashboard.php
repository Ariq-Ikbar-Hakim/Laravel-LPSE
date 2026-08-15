<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Models\User;

$user = User::where('status_aktif', 1)->first();
Auth::login($user);

$request = Illuminate\Http\Request::create('/dashboard', 'GET');
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle($request);

$content = $response->getContent();
echo "Response Length: " . strlen($content) . " bytes\n";

$decoded = @gzdecode($content);
if ($decoded !== false) {
    echo "Successfully GZ-decoded! Decoded Length: " . strlen($decoded) . " bytes\n";
    echo "Decoded Start:\n" . substr($decoded, 0, 300) . "\n";
} else {
    echo "GZ-decode failed.\n";
    echo "First 100 bytes (hex): " . bin2hex(substr($content, 0, 100)) . "\n";
}

$kernel->terminate($request, $response);
