<?php

$file = 'storage/framework/views/e5f40a7dd9fe12a24039353b0d2226fa.php';
$path = __DIR__ . '/../' . $file;

if (file_exists($path)) {
    $content = file_get_contents($path);
    echo "Compiled view size: " . strlen($content) . "\n";
    echo "Is UTF-8: " . (mb_check_encoding($content, 'UTF-8') ? 'Yes' : 'No') . "\n";
    $printable = preg_replace('/[^\x20-\x7e\n\r\t]/', '?', $content);
    echo "Printable content:\n" . substr($printable, 0, 1000) . "\n";
} else {
    echo "File not found: $file\n";
}
