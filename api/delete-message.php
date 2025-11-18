<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/functions.php';

session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['customer_id']) && !isset($_SESSION['admin_id'])) {
    jsonResponse('error', 'Unauthorized');
}

$message_id = isset($_POST['message_id']) ? (int)$_POST['message_id'] : 0;

if ($message_id <= 0) {
    jsonResponse('error', 'Message ID tidak valid');
}

// Get message
$msg_query = "SELECT * FROM messages WHERE id = ?";
$stmt = $GLOBALS['conn']->prepare($msg_query);
$stmt->bind_param("i", $message_id);
$stmt->execute();
$message = $stmt->get_result()->fetch_assoc();

if (!$message) {
    jsonResponse('error', 'Pesan tidak ditemukan');
}

// Check authorization - hanya pengirim yang bisa hapus
$is_customer = isset($_SESSION['customer_id']);
$current_user_id = $is_customer ? $_SESSION['customer_id'] : $_SESSION['admin_id'];

// Verify ownership
if ($is_customer && $message['from_user_id'] != $current_user_id) {
    jsonResponse('error', 'Anda tidak bisa menghapus pesan orang lain');
}

if (!$is_customer && $message['receiver_type'] != 'customer') {
    jsonResponse('error', 'Anda tidak bisa menghapus pesan ini');
}

// Delete message
$delete_query = "DELETE FROM messages WHERE id = ?";
$stmt = $GLOBALS['conn']->prepare($delete_query);
$stmt->bind_param("i", $message_id);

if ($stmt->execute()) {
    jsonResponse('success', 'Pesan berhasil dihapus');
} else {
    jsonResponse('error', 'Gagal menghapus pesan');
}
?>
