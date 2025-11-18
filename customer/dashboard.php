<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth-check.php';

checkCustomerAuth();

$customer_id = $_SESSION['customer_id'];
$customer = getCustomerById($customer_id);

// Get recent orders
$query = "SELECT o.*, COUNT(oi.id) as item_count FROM orders o 
          LEFT JOIN order_items oi ON o.id = oi.order_id
          WHERE o.customer_id = ?
          GROUP BY o.id
          ORDER BY o.created_at DESC LIMIT 3";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$recent_orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get notifications
$query_notif = "SELECT * FROM notifications WHERE customer_id = ? ORDER BY created_at DESC LIMIT 5";
$stmt = $conn->prepare($query_notif);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$notifications = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get cart items count
$cart_items = getCartItems($customer_id);
$cart_count = count($cart_items);
$cart_total = getCartTotal($customer_id);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Brew Bakery</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <style>
        .customer-layout {
            min-height: calc(100vh - 70px);
            background-color: var(--light);
            padding: 2rem 0;
        }
        .container-full {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        .welcome-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 2rem;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
        }
        .welcome-section h1 {
            color: white;
            margin-bottom: 0.5rem;
            font-size: 2rem;
        }
        .welcome-section p {
            opacity: 0.9;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .quick-action {
            background: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s;
        }
        .quick-action:hover {
            transform: translateY(-5px);
        }
        .quick-action h3 {
            margin-bottom: 1rem;
            color: var(--dark);
        }
        .quick-action .icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .quick-action p {
            color: #666;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        .quick-action a {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background-color: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 0.3rem;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        .quick-action a:hover {
            background-color: var(--dark);
        }
        .recent-section {
            background: white;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }
        .recent-section h3 {
            margin-bottom: 1rem;
            color: var(--dark);
        }
        .order-item {
            padding: 1rem;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .order-info {
            flex: 1;
            min-width: 200px;
        }
        .order-info .no {
            font-weight: 600;
            color: var(--dark);
        }
        .order-info .date {
            font-size: 0.9rem;
            color: #666;
        }
        .order-summary {
            display: flex;
            gap: 2rem;
            align-items: center;
            flex-wrap: wrap;
        }
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 0.3rem;
            font-size: 0.85rem;
            font-weight: 500;
            background-color: #cfe2ff;
            color: #084298;
        }
        .order-total {
            font-weight: 600;
            color: var(--primary);
        }
        .notification-item {
            padding: 1rem;
            border-left: 4px solid var(--primary);
            background-color: var(--light);
            margin-bottom: 0.5rem;
            border-radius: 0.3rem;
        }
        .notification-item.unread {
            background-color: #fff9e6;
            border-left-color: #ffc107;
        }
        .notification-title {
            font-weight: 600;
            color: var(--dark);
        }
        .notification-msg {
            font-size: 0.9rem;
            color: #666;
            margin-top: 0.25rem;
        }
        .notification-time {
            font-size: 0.8rem;
            color: #999;
            margin-top: 0.5rem;
        }
        .two-column {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
        }
        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #666;
        }
        .empty-state p {
            margin-bottom: 1rem;
        }
        @media (max-width: 768px) {
            .two-column {
                grid-template-columns: 1fr;
            }
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            .order-item {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/includes/navbar.php'; ?>
    
    <div class="customer-layout">
        <div class="container-full">
            <div class="welcome-section">
                <h1>Selamat Datang, <?php echo htmlspecialchars($customer['nama']); ?>! 👋</h1>
                <p>Nikmati pengalaman belanja roti dan pastry terbaik di Brew Bakery</p>
            </div>

            <div class="dashboard-grid">
                <div class="quick-action">
                    <div class="icon">🛒</div>
                    <h3>Belanja Sekarang</h3>
                    <p>Jelajahi koleksi roti dan pastry kami yang lezat</p>
                    <a href="<?php echo CUSTOMER_URL; ?>shop.php">Mulai Belanja</a>
                </div>
                <div class="quick-action">
                    <div class="icon">📦</div>
                    <h3>Pesanan Saya</h3>
                    <p>Lihat status dan riwayat pesanan Anda</p>
                    <a href="<?php echo CUSTOMER_URL; ?>orders/">Lihat Pesanan</a>
                </div>
                <div class="quick-action">
                    <div class="icon">👤</div>
                    <h3>Profil Saya</h3>
                    <p>Kelola informasi pribadi dan alamat pengiriman</p>
                    <a href="<?php echo CUSTOMER_URL; ?>profile.php">Edit Profil</a>
                </div>
                <div class="quick-action">
                    <div class="icon">🛍️</div>
                    <h3>Keranjang Belanja</h3>
                    <p><?php echo $cart_count; ?> item | <?php echo formatCurrency($cart_total); ?></p>
                    <a href="<?php echo CUSTOMER_URL; ?>cart.php">Lihat Keranjang</a>
                </div>
            </div>

            <div class="two-column">
                <div>
                    <div class="recent-section">
                        <h3>📦 Pesanan Terbaru</h3>
                        <?php if (count($recent_orders) > 0): ?>
                            <?php foreach ($recent_orders as $order): ?>
                            <div class="order-item">
                                <div class="order-info">
                                    <div class="no"><?php echo htmlspecialchars($order['no_pesanan']); ?></div>
                                    <div class="date"><?php echo formatDate($order['created_at']); ?></div>
                                </div>
                                <div class="order-summary">
                                    <span class="status-badge">
                                        <?php echo ORDER_STATUS[$order['status']] ?? $order['status']; ?>
                                    </span>
                                    <span class="order-total"><?php echo formatCurrency($order['total_bayar']); ?></span>
                                    <a href="<?php echo CUSTOMER_URL; ?>orders/detail.php?id=<?php echo $order['id']; ?>" style="color: var(--primary); text-decoration: none; font-weight: 600;">Detail →</a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <div class="empty-state">
                                <a href="<?php echo CUSTOMER_URL; ?>orders/" style="color: var(--primary); text-decoration: none; font-weight: 600;">Lihat Semua Pesanan →</a>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <p>Belum ada pesanan</p>
                                <a href="<?php echo CUSTOMER_URL; ?>shop.php" style="color: var(--primary); text-decoration: none; font-weight: 600;">Mulai belanja sekarang</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <div class="recent-section">
                        <h3>🔔 Notifikasi</h3>
                        <?php if (count($notifications) > 0): ?>
                            <?php foreach ($notifications as $notif): ?>
                            <div class="notification-item <?php echo !$notif['dibaca'] ? 'unread' : ''; ?>">
                                <div class="notification-title"><?php echo htmlspecialchars($notif['judul']); ?></div>
                                <div class="notification-msg"><?php echo htmlspecialchars($notif['pesan']); ?></div>
                                <div class="notification-time"><?php echo formatDate($notif['created_at']); ?></div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <p>Tidak ada notifikasi baru</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
