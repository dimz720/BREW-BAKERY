<?php
define('BASE_URL', 'http://localhost/brew-bakery/');
define('ADMIN_URL', BASE_URL . 'admin/');
define('CUSTOMER_URL', BASE_URL . 'customer/');
define('AUTH_URL', BASE_URL . 'auth/');
define('API_URL', BASE_URL . 'api/');

// ============================================
// URL CONSTANTS (untuk <img src="">)
// ============================================
define('PRODUCT_IMG_URL', BASE_URL . 'uploads/products/');
define('ARTICLE_IMG_URL', BASE_URL . 'uploads/articles/');
define('PROFILE_IMG_URL', BASE_URL . 'uploads/profiles/');
define('PAYMENT_PROOF_URL', BASE_URL . 'uploads/payment-proofs/');

// ============================================
// PATH CONSTANTS (untuk uploadImage())
// ============================================
define('PRODUCT_IMG_DIR', 'products/');
define('ARTICLE_IMG_DIR', 'articles/');
define('PROFILE_IMG_DIR', 'profiles/');
define('PAYMENT_PROOF_DIR', 'payment-proofs/');

define('ALLOWED_IMG_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
define('MAX_IMG_SIZE', 5 * 1024 * 1024);
define('SESSION_TIMEOUT', 3600);

define('ORDER_STATUS', [
    'menunggu_bukti' => 'Menunggu Bukti Pembayaran',
    'menunggu_verifikasi' => 'Menunggu Verifikasi',
    'diterima' => 'Pembayaran Diterima',
    'ditolak' => 'Pembayaran Ditolak',
    'siap_kirim' => 'Siap Dikirim',
    'selesai' => 'Selesai'
]);

define('COLOR_PRIMARY', '#8B6F47');
define('COLOR_SECONDARY', '#D4A574');
define('COLOR_ACCENT', '#F5E6D3');
define('COLOR_DARK', '#5C4A35');
define('COLOR_LIGHT', '#FDF7F1');
?>