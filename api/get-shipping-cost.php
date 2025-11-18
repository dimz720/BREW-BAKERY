<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/functions.php';

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    jsonResponse('error', 'Unauthorized');
}

$data = json_decode(file_get_contents('php://input'), true);
$wilayah = $data['wilayah'] ?? '';

if (empty($wilayah)) {
    jsonResponse('error', 'Wilayah harus dipilih');
}

$query = "SELECT ongkir FROM shipping_costs WHERE wilayah = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $wilayah);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if (!$result) {
    jsonResponse('error', 'Wilayah tidak tersedia');
}

jsonResponse('success', 'Ongkir ditemukan', ['ongkir' => $result['ongkir']]);
?>
