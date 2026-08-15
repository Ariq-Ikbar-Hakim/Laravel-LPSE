<?php

function scanDirRecursive($dir, &$results = []) {
    $files = scandir($dir);
    foreach ($files as $key => $value) {
        $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
        if (!is_dir($path)) {
            if (preg_match('/\.php$/', $path)) {
                $results[] = $path;
            }
        } else if ($value != "." && $value != "..") {
            scanDirRecursive($path, $results);
        }
    }
    return $results;
}

$projectRoot = dirname(__DIR__);
$dirs = [
    $projectRoot . '/app',
    $projectRoot . '/routes',
    $projectRoot . '/resources/views',
];

$allFiles = [];
foreach ($dirs as $dir) {
    if (is_dir($dir)) {
        scanDirRecursive($dir, $allFiles);
    }
}

echo "Scanning " . count($allFiles) . " PHP files...\n";

foreach ($allFiles as $file) {
    $content = file_get_contents($file);
    $relPath = str_replace($projectRoot . '/', '', $file);
    
    // Check BOM
    if (strpos($content, "\xef\xbb\xbf") === 0) {
        echo "BOM detected in $relPath\n";
    }
    
    // Check leading whitespace for normal PHP files
    if (preg_match('/^\s+<\?php/', $content) && !str_contains($file, '.blade.php')) {
        echo "Leading whitespace detected in $relPath\n";
    }
    
    // Check if it starts with <?php at all (except blade templates)
    if (!str_contains($file, '.blade.php')) {
        if (strpos($content, '<?php') !== 0 && strpos($content, "\xef\xbb\xbf<?php") !== 0) {
            echo "Does not start with <?php: $relPath (Starts with: " . substr(bin2hex($content), 0, 10) . ")\n";
        }
    }
}
echo "Scan completed.\n";
