<?php

function scanDirRecursive($dir, &$results = []) {
    $files = scandir($dir);
    foreach ($files as $value) {
        $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
        if (!is_dir($path)) {
            if (preg_match('/\.blade\.php$/', $path)) {
                $results[] = $path;
            }
        } else if ($value != "." && $value != "..") {
            scanDirRecursive($path, $results);
        }
    }
    return $results;
}

$projectRoot = dirname(__DIR__);
$views = scanDirRecursive($projectRoot . '/resources/views');

$targetHash = "e5f40a7dd9fe12a24039353b0d2226fa"; // or maybe without extension

echo "Target Hash: $targetHash\n";
foreach ($views as $view) {
    // Laravel hashes the absolute path of the view file
    $hash = sha1($view);
    if ($hash === $targetHash) {
        echo "Match found (sha1): $view -> $hash.php\n";
    }
    // Also try MD5 just in case
    $md5 = md5($view);
    if ($md5 === $targetHash) {
        echo "Match found (md5): $view -> $md5.php\n";
    }
    
    // Also try relative path
    $relPath = str_replace($projectRoot . '/', '', $view);
    $relHash = sha1($relPath);
    if ($relHash === $targetHash) {
        echo "Match found (rel sha1): $view -> $relHash.php\n";
    }
}
echo "Search completed.\n";
