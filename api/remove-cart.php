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
$customer_id = $_SESSION['user_id'];

if ($cart_id <= 0) {
    jsonResponse('error', 'Data tidak valid');
}

// Check cart item belongs to customer
$query = "SELECT id FROM carts WHERE id = ? AND customer_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $cart_id, $customer_id);
$stmt->execute();

if ($stmt->get_result()->num_rows === 0) {
    jsonResponse('error', 'Item keranjang tidak ditemukan');
}

// Delete cart item
$delete_query = "DELETE FROM carts WHERE id = ?";
$stmt = $conn->prepare($delete_query);
$stmt->bind_param("i", $cart_id);
$stmt->execute();

jsonResponse('success', 'Item berhasil dihapus dari keranjang');
?>
