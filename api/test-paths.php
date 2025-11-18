<?php
require_once __DIR__ . '/../config/constants.php';

echo "<h1>Path Configuration Test</h1>";
echo "<pre>";
echo "PRODUCT_IMG_DIR: " . PRODUCT_IMG_DIR . "\n";
echo "ARTICLE_IMG_DIR: " . ARTICLE_IMG_DIR . "\n";
echo "PROFILE_IMG_DIR: " . PROFILE_IMG_DIR . "\n";
echo "PAYMENT_PROOF_DIR: " . PAYMENT_PROOF_DIR . "\n";
echo "</pre>";

// Cek apakah folder ada
echo "<h2>Folder Status</h2>";
$folders = [
    'PRODUCT_IMG_DIR' => PRODUCT_IMG_DIR,
    'ARTICLE_IMG_DIR' => ARTICLE_IMG_DIR,
    'PROFILE_IMG_DIR' => PROFILE_IMG_DIR,
    'PAYMENT_PROOF_DIR' => PAYMENT_PROOF_DIR,
];

foreach ($folders as $name => $path) {
    $realPath = __DIR__ . '/../' . ltrim($path, '/');
    $exists = is_dir($realPath) ? '✓ EXISTS' : '✗ NOT FOUND';
    echo "$name: $exists <br>";
    echo "Real path: $realPath <br><br>";
}
?>
