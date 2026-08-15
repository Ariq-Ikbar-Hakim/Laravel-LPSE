<?php

$files = [
    'resources/views/profile/partials/update-profile-information-form.blade.php',
    'resources/views/profile/edit.blade.php',
    'resources/views/berita-acara/index.blade.php',
];

foreach ($files as $file) {
    $path = __DIR__ . '/../' . $file;
    if (!file_exists($path)) {
        echo "File does not exist: $file\n";
        continue;
    }
    $content = file_get_contents($path);
    echo "$file: Length = " . strlen($content) . " bytes, Encoding = " . (mb_check_encoding($content, 'UTF-8') ? 'UTF-8' : 'Not UTF-8') . "\n";
}
