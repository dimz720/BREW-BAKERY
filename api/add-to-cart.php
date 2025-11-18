<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/functions.php';

session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['customer_id'])) {
    jsonResponse('error', 'Anda harus login terlebih dahulu');
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$customer_id = $_SESSION['customer_id'];
$product_id = isset($data['product_id']) ? (int)$data['product_id'] : 0;
$jumlah = isset($data['jumlah']) ? (int)$data['jumlah'] : 1;

if ($product_id <= 0 || $jumlah <= 0) {
    jsonResponse('error', 'Data tidak valid');
    exit;
}

// Check product exists
$product = getProductById($product_id);
if (!$product) {
    jsonResponse('error', 'Produk tidak ditemukan');
    exit;
}

// Check stock
if ($product['stok'] < $jumlah) {
    jsonResponse('error', 'Stok tidak cukup');
    exit;
}

// Check if product already in cart
$check_query = "SELECT id, jumlah FROM carts WHERE customer_id = ? AND product_id = ?";
$stmt = $conn->prepare($check_query);
$stmt->bind_param("ii", $customer_id, $product_id);
$stmt->execute();
$existing = $stmt->get_result()->fetch_assoc();

if ($existing) {
    // Update quantity
    $new_qty = $existing['jumlah'] + $jumlah;
    if ($new_qty > $product['stok']) {
        jsonResponse('error', 'Stok tidak cukup untuk jumlah yang diminta');
        exit;
    }
    
    $update_query = "UPDATE carts SET jumlah = ? WHERE customer_id = ? AND product_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("iii", $new_qty, $customer_id, $product_id);
    
    if ($stmt->execute()) {
        jsonResponse('success', 'Produk berhasil diupdate di keranjang');
    } else {
        jsonResponse('error', 'Gagal mengupdate keranjang');
    }
} else {
    // Insert new cart item
    $insert_query = "INSERT INTO carts (customer_id, product_id, jumlah) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("iii", $customer_id, $product_id, $jumlah);
    
    if ($stmt->execute()) {
        jsonResponse('success', 'Produk berhasil ditambahkan ke keranjang');
    } else {
        jsonResponse('error', 'Gagal menambahkan ke keranjang');
    }
}
?>
