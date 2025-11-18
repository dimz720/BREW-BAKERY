<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/functions.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    jsonResponse('error', 'Unauthorized');
}

$data = json_decode(file_get_contents('php://input'), true);
$cart_id = $data['cart_id'] ?? 0;
$jumlah = $data['jumlah'] ?? 0;
$customer_id = $_SESSION['user_id'];

if ($cart_id <= 0 || $jumlah < 0) {
    jsonResponse('error', 'Data tidak valid');
}

// Check cart item belongs to customer
$query = "SELECT c.*, p.stok FROM carts c 
          JOIN products p ON c.product_id = p.id 
          WHERE c.id = ? AND c.customer_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $cart_id, $customer_id);
$stmt->execute();
$cart_item = $stmt->get_result()->fetch_assoc();

if (!$cart_item) {
    jsonResponse('error', 'Item keranjang tidak ditemukan');
}

if ($jumlah > $cart_item['stok']) {
    jsonResponse('error', 'Stok tidak mencukupi');
}

if ($jumlah === 0) {
    // Delete cart item
    $delete_query = "DELETE FROM carts WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
} else {
    // Update quantity
    $update_query = "UPDATE carts SET jumlah = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ii", $jumlah, $cart_id);
    $stmt->execute();
}

jsonResponse('success', 'Keranjang berhasil diperbarui');
?>
