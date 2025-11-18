<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/functions.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    jsonResponse('error', 'Unauthorized');
}

$data = json_decode(file_get_contents('php://input'), true);
$product_id = $data['product_id'] ?? 0;
$rating = $data['rating'] ?? 0;
$ulasan = $data['ulasan'] ?? '';
$customer_id = $_SESSION['user_id'];

if ($product_id <= 0 || $rating < 1 || $rating > 5 || empty($ulasan)) {
    jsonResponse('error', 'Data tidak valid');
}

// Check if customer has purchased this product and order is completed
$purchase_query = "SELECT o.id FROM order_items oi 
                   JOIN orders o ON oi.order_id = o.id 
                   WHERE oi.product_id = ? AND o.customer_id = ? AND o.status = 'selesai'";
$stmt = $conn->prepare($purchase_query);
$stmt->bind_param("ii", $product_id, $customer_id);
$stmt->execute();

if ($stmt->get_result()->num_rows === 0) {
    jsonResponse('error', 'Anda hanya bisa memberi ulasan untuk produk yang telah dibeli dan pesanannya selesai');
}

// Check if already reviewed
$check_query = "SELECT id FROM reviews WHERE product_id = ? AND customer_id = ?";
$stmt = $conn->prepare($check_query);
$stmt->bind_param("ii", $product_id, $customer_id);
$stmt->execute();

if ($stmt->get_result()->num_rows > 0) {
    jsonResponse('error', 'Anda sudah memberikan ulasan untuk produk ini');
}

// Get the first completed order with this product
$order_query = "SELECT o.id FROM order_items oi 
                JOIN orders o ON oi.order_id = o.id 
                WHERE oi.product_id = ? AND o.customer_id = ? AND o.status = 'selesai'
                LIMIT 1";
$stmt = $conn->prepare($order_query);
$stmt->bind_param("ii", $product_id, $customer_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

// Insert review
$insert_query = "INSERT INTO reviews (product_id, customer_id, order_id, rating, ulasan) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($insert_query);
$stmt->bind_param("iiis", $product_id, $customer_id, $order['id'], $rating, $ulasan);

if ($stmt->execute()) {
    jsonResponse('success', 'Ulasan berhasil ditambahkan');
} else {
    jsonResponse('error', 'Terjadi kesalahan saat menyimpan ulasan');
}
?>
