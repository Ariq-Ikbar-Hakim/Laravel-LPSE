<?php

$targetHex = "07c82800c803ed";
$targetBin = hex2bin($targetHex);

function searchDir($dir, $targetBin) {
    $files = scandir($dir);
    foreach ($files as $value) {
        $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
        if (!is_dir($path)) {
            if (filesize($path) > 0 && filesize($path) < 10000000) { // Limit to 10MB
                $content = file_get_contents($path);
                if (strpos($content, $targetBin) !== false) {
                    echo "Found target binary in: " . $path . "\n";
                }
            }
        } else if ($value != "." && $value != "..") {
            // Include storage/framework/views but skip other storage subdirs to be fast
            if ($value === 'storage') {
                searchDir($path . '/framework/views', $targetBin);
            } else if ($value !== 'vendor' && $value !== '.git' && $value !== 'node_modules') {
                searchDir($path, $targetBin);
            }
        }
    }
}

$projectRoot = dirname(__DIR__);
echo "Searching files including storage/framework/views for target binary pattern...\n";
searchDir($projectRoot, $targetBin);
echo "Search completed.\n";
