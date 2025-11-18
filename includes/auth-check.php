<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/functions.php';

// CHECK ADMIN LOGIN
function checkAdminAuth() {
    if (!session_id()) {
        session_start();
    }
    
    if (!isset($_SESSION['admin_id'])) {
        redirect(AUTH_URL . 'login-admin.php');
    }
}

// CHECK CUSTOMER LOGIN
function checkCustomerAuth() {
    if (!session_id()) {
        session_start();
    }
    
    if (!isset($_SESSION['customer_id'])) {
        redirect(AUTH_URL . 'login-customer.php');
    }
}

// CHECK ADMIN LOGGED IN
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

// CHECK CUSTOMER LOGGED IN
function isCustomerLoggedIn() {
    return isset($_SESSION['customer_id']);
}

// GET CURRENT ADMIN
function getCurrentAdmin() {
    if (isAdminLoggedIn()) {
        return [
            'id' => $_SESSION['admin_id'],
            'nama' => $_SESSION['admin_nama'] ?? '',
            'email' => $_SESSION['admin_email'] ?? ''
        ];
    }
    return null;
}

// GET CURRENT CUSTOMER
function getCurrentCustomer() {
    if (isCustomerLoggedIn()) {
        return [
            'id' => $_SESSION['customer_id'],
            'nama' => $_SESSION['customer_nama'] ?? '',
            'email' => $_SESSION['customer_email'] ?? ''
        ];
    }
    return null;
}
?>
