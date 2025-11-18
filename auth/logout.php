<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/functions.php';

session_start();
session_destroy();

// Redirect ke halaman login
if (isset($_SESSION['admin_id'])) {
    redirect(AUTH_URL . 'login-admin.php');
} elseif (isset($_SESSION['customer_id'])) {
    redirect(AUTH_URL . 'login-customer.php');
} else {
    redirect(BASE_URL);
}
?>
