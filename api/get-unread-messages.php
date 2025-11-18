<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';

session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['customer_id'])) {
    jsonResponse('error', 'Unauthorized');
}

$customer_id = $_SESSION['customer_id'];

// Get unread messages count - PERBAIKAN: Query yang benar
// Pesan dari admin yang belum dibaca
$query = "SELECT COUNT(*) as count FROM messages 
          WHERE to_user_id = ? AND receiver_type = 'customer' AND dibaca = FALSE";

$stmt = $GLOBALS['conn']->prepare($query);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

echo json_encode([
    'count' => $result['count'] ?? 0
]);
?>
