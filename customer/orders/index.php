<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth-check.php';

checkCustomerAuth();

$customer_id = $_SESSION['customer_id'];
$status_filter = isset($_GET['status']) ? sanitize($_GET['status']) : '';

// Build query
$where = "WHERE o.customer_id = ?";
$params = [$customer_id];
$types = "i";

if (!empty($status_filter) && array_key_exists($status_filter, ORDER_STATUS)) {
    $where .= " AND o.status = ?";
    $params[] = $status_filter;
    $types .= "s";
}

$query = "SELECT o.*, COUNT(oi.id) as item_count FROM orders o 
          LEFT JOIN order_items oi ON o.id = oi.order_id
          $where 
          GROUP BY o.id
          ORDER BY o.created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - Brew Bakery</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <style>
        .orders-layout {
            min-height: calc(100vh - 70px);
            background-color: var(--light);
            padding: 2rem 0;
        }
        .orders-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        .filter-section {
            background: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .filter-btn {
            padding: 0.5rem 1rem;
            border: 2px solid #ddd;
            background-color: white;
            color: var(--dark);
            border-radius: 0.3rem;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            font-weight: 500;
        }
        .filter-btn:hover,
        .filter-btn.active {
            border-color: var(--primary);
            background-color: var(--primary);
            color: white;
        }
        .orders-list {
            display: grid;
            gap: 1.5rem;
        }
        .order-card {
            background: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-left: 4px solid var(--primary);
        }
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .order-no {
            font-weight: 600;
            color: var(--dark);
            font-size: 1.1rem;
        }
        .order-date {
            color: #666;
            font-size: 0.9rem;
        }
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 0.3rem;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .status-menunggu_bukti {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-menunggu_verifikasi {
            background-color: #cfe2ff;
            color: #084298;
        }
        .status-diterima {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        .status-ditolak {
            background-color: #f8d7da;
            color: #721c24;
        }
        .status-siap_kirim {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .status-selesai {
            background-color: #d4edda;
            color: #155724;
        }
        .order-body {
            display: grid;
            grid-template-columns: 1fr 1fr auto;
            gap: 2rem;
            margin-bottom: 1rem;
        }
        .order-items {
            font-size: 0.95rem;
        }
        .order-items-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }
        .order-item {
            color: #666;
            margin-bottom: 0.3rem;
        }
        .order-total {
            text-align: right;
        }
        .order-total-label {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        .order-total-amount {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary);
        }
        .order-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .btn-detail {
            padding: 0.5rem 1rem;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 0.3rem;
            text-decoration: none;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.3s;
        }
        .btn-detail:hover {
            background-color: var(--dark);
        }
        .btn-cancel {
            padding: 0.5rem 1rem;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 0.3rem;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.3s;
        }
        .btn-cancel:hover {
            background-color: #c82333;
        }
        .empty-state {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        @media (max-width: 768px) {
            .order-body {
                grid-template-columns: 1fr;
            }
            .order-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .order-total {
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../includes/navbar.php'; ?>
    
    <div class="orders-layout">
        <div class="orders-container">
            <h1>📦 Riwayat Pesanan Saya</h1>

            <div class="filter-section">
                <a href="<?php echo CUSTOMER_URL; ?>orders/" class="filter-btn <?php echo empty($status_filter) ? 'active' : ''; ?>">Semua</a>
                <?php foreach (ORDER_STATUS as $status => $label): ?>
                <a href="?status=<?php echo $status; ?>" class="filter-btn <?php echo $status_filter === $status ? 'active' : ''; ?>">
                    <?php echo $label; ?>
                </a>
                <?php endforeach; ?>
            </div>

            <?php if (count($orders) > 0): ?>
                <div class="orders-list">
                    <?php foreach ($orders as $order): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div>
                                <div class="order-no"><?php echo htmlspecialchars($order['no_pesanan']); ?></div>
                                <div class="order-date"><?php echo formatDate($order['created_at']); ?></div>
                            </div>
                            <span class="status-badge status-<?php echo str_replace('_', '-', $order['status']); ?>">
                                <?php echo ORDER_STATUS[$order['status']]; ?>
                            </span>
                        </div>
                        <div class="order-body">
                            <div class="order-items">
                                <div class="order-items-title"><?php echo $order['item_count']; ?> Produk</div>
                                <div class="order-item"><?php echo htmlspecialchars($order['wilayah']); ?></div>
                            </div>
                            <div>
                                <div style="color: #666; margin-bottom: 0.5rem;">Pengiriman ke:</div>
                                <div style="font-size: 0.85rem; color: #666;">
                                    <?php echo htmlspecialchars(substr($order['alamat_lengkap'], 0, 50)); ?>...
                                </div>
                            </div>
                            <div class="order-total">
                                <div class="order-total-label">Total Bayar</div>
                                <div class="order-total-amount"><?php echo formatCurrency($order['total_bayar']); ?></div>
                            </div>
                        </div>
                        <div class="order-actions">
                            <a href="<?php echo CUSTOMER_URL; ?>orders/detail.php?id=<?php echo $order['id']; ?>" class="btn-detail">Lihat Detail</a>
                            <?php if ($order['status'] !== 'selesai' && $order['status'] !== 'ditolak'): ?>
                                <a href="<?php echo CUSTOMER_URL; ?>orders/tracking.php?id=<?php echo $order['id']; ?>" class="btn-detail" style="background-color: var(--secondary);">Tracking</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">📦</div>
                    <h2>Belum Ada Pesanan</h2>
                    <p>Anda belum melakukan pesanan apapun.</p>
                    <a href="<?php echo CUSTOMER_URL; ?>shop.php" class="btn btn-primary" style="margin-top: 1rem;">Mulai Belanja Sekarang</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>
</body>
</html>
