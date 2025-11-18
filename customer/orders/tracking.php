<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth-check.php';

checkCustomerAuth();

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$customer_id = $_SESSION['customer_id'];  // ← FIX: user_id → customer_id

if ($order_id <= 0) {
    redirect(CUSTOMER_URL . 'orders/');
}

$order = getOrderById($order_id);
if (!$order || $order['customer_id'] !== $customer_id) {
    redirect(CUSTOMER_URL . 'orders/');
}

// Define tracking steps
$tracking_steps = [
    'menunggu_bukti' => ['label' => 'Menunggu Bukti Bayar', 'icon' => '⏳', 'description' => 'Silakan upload bukti pembayaran'],
    'menunggu_verifikasi' => ['label' => 'Menunggu Verifikasi', 'icon' => '⏳', 'description' => 'Admin sedang memverifikasi pembayaran Anda'],
    'diterima' => ['label' => 'Pesanan Diterima', 'icon' => '✓', 'description' => 'Pembayaran Anda telah diterima'],
    'siap_kirim' => ['label' => 'Siap Dikirim', 'icon' => '📦', 'description' => 'Pesanan Anda sedang dikemas dan siap dikirim'],
    'selesai' => ['label' => 'Pesanan Selesai', 'icon' => '✓', 'description' => 'Pesanan telah sampai ke tangan Anda'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking Pesanan - Brew Bakery</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <style>
        .tracking-layout {
            min-height: calc(100vh - 70px);
            background-color: var(--light);
            padding: 2rem 0;
        }
        .tracking-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        .tracking-card {
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }
        .tracking-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .tracking-header h1 {
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        .tracking-header p {
            color: #666;
        }
        .timeline {
            position: relative;
            padding: 2rem 0;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 50%;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #ddd;
            transform: translateX(-50%);
        }
        .timeline-item {
            margin-bottom: 2rem;
            position: relative;
        }
        .timeline-item:last-child {
            margin-bottom: 0;
        }
        .timeline-item.active::before {
            background: var(--primary);
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 50%;
            top: 0;
            width: 20px;
            height: 20px;
            background: white;
            border: 3px solid #ddd;
            border-radius: 50%;
            transform: translateX(-50%);
            z-index: 1;
        }
        .timeline-item.active::before {
            background: var(--primary);
            border-color: var(--primary);
            box-shadow: 0 0 0 8px rgba(139, 111, 71, 0.1);
        }
        .timeline-content {
            margin-left: 50%;
            padding-left: 2rem;
        }
        .timeline-item:nth-child(odd) .timeline-content {
            margin-left: 0;
            padding-left: 0;
            padding-right: 2rem;
            margin-right: 50%;
            text-align: right;
        }
        .timeline-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        .timeline-label {
            font-weight: 600;
            color: var(--dark);
            font-size: 1.1rem;
            margin-bottom: 0.3rem;
        }
        .timeline-description {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
        }
        .timeline-date {
            color: #999;
            font-size: 0.85rem;
        }
        .timeline-item.active .timeline-label {
            color: var(--primary);
        }
        .timeline-item.active .timeline-description {
            color: var(--dark);
            font-weight: 500;
        }
        .timeline-item.completed .timeline-label {
            color: #28a745;
        }
        .timeline-item.completed .timeline-description {
            color: #28a745;
        }
        .order-info {
            background-color: var(--light);
            padding: 1.5rem;
            border-radius: 0.5rem;
            border-left: 4px solid var(--primary);
            margin-top: 2rem;
        }
        .order-info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
        }
        .order-info-item:last-child {
            margin-bottom: 0;
        }
        .order-info-label {
            font-weight: 600;
            color: var(--dark);
        }
        .order-info-value {
            color: #666;
        }
        .btn-back {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background-color: var(--secondary);
            color: white;
            text-decoration: none;
            border-radius: 0.3rem;
            margin-top: 1.5rem;
            transition: background-color 0.3s;
        }
        .btn-back:hover {
            background-color: var(--primary);
        }
        @media (max-width: 768px) {
            .timeline::before {
                left: 10px;
            }
            .timeline-item::before {
                left: 10px;
            }
            .timeline-content {
                margin-left: 0;
                padding-left: 3rem;
                margin-right: 0;
                text-align: left;
            }
            .timeline-item:nth-child(odd) .timeline-content {
                margin-left: 0;
                padding-left: 3rem;
                padding-right: 0;
                margin-right: 0;
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../includes/navbar.php'; ?>
    
    <div class="tracking-layout">
        <div class="tracking-container">
            <div class="tracking-card">
                <div class="tracking-header">
                    <h1>📍 Tracking Pesanan</h1>
                    <p><?php echo htmlspecialchars($order['no_pesanan']); ?></p>
                </div>

                <div class="timeline">
                    <?php 
                    $order_statuses = ['menunggu_bukti', 'menunggu_verifikasi', 'diterima', 'siap_kirim', 'selesai'];
                    $current_status_index = array_search($order['status'], $order_statuses);
                    
                    foreach ($order_statuses as $index => $status): 
                        $step = $tracking_steps[$status];
                        $is_active = ($index === $current_status_index);
                        $is_completed = ($index < $current_status_index);
                        $is_rejected = ($order['status'] === 'ditolak' && $status !== 'menunggu_bukti');
                    ?>
                    <div class="timeline-item <?php echo $is_active ? 'active' : ($is_completed && !$is_rejected ? 'completed' : ''); ?>">
                        <div class="timeline-content">
                            <div class="timeline-icon"><?php echo $step['icon']; ?></div>
                            <div class="timeline-label"><?php echo $step['label']; ?></div>
                            <div class="timeline-description"><?php echo $step['description']; ?></div>
                            <?php if ($status === $order['status']): ?>
                                <div class="timeline-date"><?php echo formatDate($order['updated_at']); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <?php if ($order['status'] === 'ditolak'): ?>
                    <div class="timeline-item" style="margin-top: 2rem;">
                        <div class="timeline-content" style="text-align: center;">
                            <div class="timeline-icon" style="font-size: 3rem; color: #dc3545;">✕</div>
                            <div class="timeline-label" style="color: #dc3545;">Pesanan Ditolak</div>
                            <div class="timeline-description" style="color: #dc3545;">
                                <?php echo htmlspecialchars($order['alasan_penolakan'] ?? 'Pesanan Anda telah ditolak oleh admin.'); ?>
                            </div>
                            <div style="margin-top: 1rem;">
                                <a href="<?php echo CUSTOMER_URL; ?>shop.php" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Belanja Lagi</a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="order-info">
                    <h3 style="margin-top: 0; color: var(--primary);">📦 Detail Pesanan</h3>
                    <div class="order-info-item">
                        <span class="order-info-label">No. Pesanan:</span>
                        <span class="order-info-value"><?php echo htmlspecialchars($order['no_pesanan']); ?></span>
                    </div>
                    <div class="order-info-item">
                        <span class="order-info-label">Status:</span>
                        <span class="order-info-value"><?php echo ORDER_STATUS[$order['status']]; ?></span>
                    </div>
                    <div class="order-info-item">
                        <span class="order-info-label">Total Pembayaran:</span>
                        <span class="order-info-value" style="color: var(--primary); font-weight: 600;"><?php echo formatCurrency($order['total_bayar']); ?></span>
                    </div>
                    <div class="order-info-item">
                        <span class="order-info-label">Wilayah Pengiriman:</span>
                        <span class="order-info-value"><?php echo htmlspecialchars($order['wilayah']); ?></span>
                    </div>
                    <div class="order-info-item">
                        <span class="order-info-label">Tanggal Pesanan:</span>
                        <span class="order-info-value"><?php echo formatDate($order['created_at']); ?></span>
                    </div>
                </div>

                <a href="<?php echo CUSTOMER_URL; ?>orders/detail.php?id=<?php echo $order_id; ?>" class="btn-back">← Lihat Detail Lengkap</a>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>
</body>
</html>
