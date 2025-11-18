<?php
echo "<h1>Upload Configuration Test</h1>";
echo "<pre>";
echo "PHP Version: " . phpversion() . "\n";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "\n";
echo "post_max_size: " . ini_get('post_max_size') . "\n";
echo "memory_limit: " . ini_get('memory_limit') . "\n";
echo "max_execution_time: " . ini_get('max_execution_time') . "\n";
echo "</pre>";

// Convert ke bytes
echo "<h2>In Bytes</h2>";
echo "<pre>";
echo "upload_max_filesize: " . return_bytes(ini_get('upload_max_filesize')) . " bytes\n";
echo "post_max_size: " . return_bytes(ini_get('post_max_size')) . " bytes\n";
echo "</pre>";

function return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    switch($last) {
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }
    return $val;
}
?>
