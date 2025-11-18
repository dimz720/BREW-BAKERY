<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/functions.php';

echo "<h1>Upload Test</h1>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto'])) {
    $filename = uploadImage($_FILES['foto'], PRODUCT_IMG_DIR);
    
    if ($filename) {
        echo "<p style='color: green;'><strong>✓ Upload berhasil!</strong></p>";
        echo "<p>Filename: $filename</p>";
        echo "<p>Full path: " . PRODUCT_IMG_DIR . $filename . "</p>";
        echo "<img src='" . PRODUCT_IMG_DIR . $filename . "' style='max-width: 300px;'>";
    } else {
        echo "<p style='color: red;'><strong>✗ Upload gagal!</strong></p>";
    }
} else {
?>
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="foto" accept="image/*" required>
    <button type="submit">Upload Test</button>
</form>
<?php
}
?>
