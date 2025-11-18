<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/functions.php';

session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['customer_id']) && !isset($_SESSION['admin_id'])) {
    jsonResponse('error', 'Unauthorized');
}

$from_user_id = $_SESSION['customer_id'] ?? $_SESSION['admin_id'];
$is_customer = isset($_SESSION['customer_id']);

if ($is_customer) {
    // Customer kirim ke admin - cari admin pertama
    $admin_query = "SELECT id FROM admins LIMIT 1";
    $admin = $GLOBALS['conn']->query($admin_query)->fetch_assoc();
    
    if (!$admin) {
        jsonResponse('error', 'Admin tidak ditemukan');
    }
    
    $to_user_id = $admin['id'];
    $receiver_type = 'admin';
} else {
    // Admin kirim ke customer
    $to_user_id = isset($_POST['to_user_id']) ? (int)$_POST['to_user_id'] : 0;
    $receiver_type = 'customer';
    
    if ($to_user_id <= 0) {
        jsonResponse('error', 'Customer tidak ditemukan');
    }
}

$pesan = sanitize($_POST['pesan'] ?? '');

if (empty($pesan)) {
    jsonResponse('error', 'Pesan tidak boleh kosong');
}

$insert_query = "INSERT INTO messages (from_user_id, to_user_id, pesan, receiver_type) VALUES (?, ?, ?, ?)";
$stmt = $GLOBALS['conn']->prepare($insert_query);

if (!$stmt) {
    jsonResponse('error', 'Database error: ' . $GLOBALS['conn']->error);
}

$stmt->bind_param("iiss", $from_user_id, $to_user_id, $pesan, $receiver_type);

if ($stmt->execute()) {
    jsonResponse('success', 'Pesan berhasil dikirim');
} else {
    jsonResponse('error', 'Gagal mengirim pesan: ' . $stmt->error);
}
?>
