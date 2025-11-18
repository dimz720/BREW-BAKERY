<?php
require_once __DIR__ . '/../../includes/auth-check.php';

checkCustomerAuth();

$customer = getCurrentCustomer();
$customer_id = $_SESSION['customer_id'];
$unread_notif = getUnreadNotifications($customer_id);
$cart_items = getCartItems($customer_id);
$cart_count = count($cart_items);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brew Bakery</title>
    <style>
      
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 0.8rem 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
        }

        .navbar-brand img {
            height: 40px;
            width: auto;
        }

        .navbar-nav {
            display: flex;
            gap: 1.5rem;
            align-items: center;
            list-style: none;
        }

        .nav-link {
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 0.8rem;
            border-radius: 4px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: var(--danger);
            color: white;
            padding: 0.2rem 0.4rem;
            border-radius: 50%;
            font-size: 0.7rem;
            font-weight: bold;
            min-width: 18px;
            text-align: center;
        }

        .nav-item {
            position: relative;
        }

        .dropdown {
            position: relative;
        }

        .dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .dropdown-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            min-width: 200px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            padding: 0.5rem 0;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.3s ease;
            z-index: 1001;
        }

        .dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            color: var(--dark);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: var(--primary);
        }

        .dropdown-divider {
            height: 1px;
            background-color: #e9ecef;
            margin: 0.5rem 0;
        }

        .hamburger {
            display: none;
            flex-direction: column;
            cursor: pointer;
            gap: 4px;
        }

        .hamburger span {
            width: 25px;
            height: 3px;
            background-color: white;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        @media (max-width: 768px) {
            .hamburger {
                display: flex;
            }

            .navbar-nav {
                position: fixed;
                top: 60px;
                left: 0;
                width: 100%;
                background: var(--primary);
                flex-direction: column;
                padding: 1rem 0;
                transform: translateY(-100%);
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }

            .navbar-nav.active {
                transform: translateY(0);
                opacity: 1;
                visibility: visible;
            }

            .nav-item {
                width: 100%;
                text-align: center;
            }

            .nav-link {
                padding: 0.8rem 1rem;
                justify-content: center;
            }

            .dropdown-menu {
                position: static;
                background: rgba(255, 255, 255, 0.1);
                box-shadow: none;
                opacity: 1;
                visibility: visible;
                transform: none;
                display: none;
                margin-top: 0.5rem;
            }

            .dropdown.active .dropdown-menu {
                display: block;
            }

            .dropdown-item {
                color: white;
                justify-content: center;
            }

            .dropdown-item:hover {
                background-color: rgba(255, 255, 255, 0.1);
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <a href="<?php echo CUSTOMER_URL; ?>dashboard.php" class="navbar-brand">
                <!-- Ganti dengan logo asli jika tersedia -->
                <span>🍞</span>
                <span>Brew Bakery</span>
            </a>

            <div class="hamburger" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <ul class="navbar-nav" id="navbarNav">
                <li class="nav-item">
                    <a href="<?php echo CUSTOMER_URL; ?>dashboard.php" class="nav-link">
                        <span>🏠</span>
                        <span>Beranda</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo CUSTOMER_URL; ?>shop.php" class="nav-link">
                        <span>🍞</span>
                        <span>Belanja</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo CUSTOMER_URL; ?>articles.php" class="nav-link">
                        <span>📰</span>
                        <span>Artikel</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo CUSTOMER_URL; ?>cart.php" class="nav-link">
                        <span>🛒</span>
                        <span>Keranjang</span>
                        <?php if ($cart_count > 0): ?>
                        <span class="badge"><?php echo $cart_count; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
              
                <!-- Dropdown Profil -->
                <li class="nav-item dropdown" id="profileDropdown">
                    <div class="dropdown-toggle">
                        <span>👤</span>
                        <span><?php echo htmlspecialchars($customer['nama']); ?></span>
                        <span>▼</span>
                    </div>
                    <div class="dropdown-menu">
                        <a href="<?php echo CUSTOMER_URL; ?>profile.php" class="dropdown-item">
                            <span>👤</span>
                            <span>Profil Saya</span>
                        </a>
                        <a href="<?php echo CUSTOMER_URL; ?>orders/" class="dropdown-item">
                            <span>📦</span>
                            <span>Riwayat Pesanan</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="<?php echo AUTH_URL; ?>logout.php" class="dropdown-item">
                            <span>🚪</span>
                            <span>Keluar</span>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <script>
        // Toggle menu mobile
        document.getElementById('hamburger').addEventListener('click', function() {
            document.getElementById('navbarNav').classList.toggle('active');
            this.classList.toggle('active');
        });

        // Toggle dropdown di mobile
        document.getElementById('profileDropdown').addEventListener('click', function(e) {
            if (window.innerWidth <= 768) {
                e.preventDefault();
                this.classList.toggle('active');
            }
        });

        // Auto-refresh unread badge setiap 5 detik
        setInterval(function() {
            fetch('<?php echo API_URL; ?>get-unread-messages.php')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('unread-badge');
                    if (data.count > 0) {
                        badge.textContent = data.count;
                        badge.style.display = 'inline-block';
                    } else {
                        badge.style.display = 'none';
                    }
                })
                .catch(error => console.error('Error:', error));
        }, 5000); // Refresh setiap 5 detik

        // Tutup menu saat klik di luar
        document.addEventListener('click', function(e) {
            const navbarNav = document.getElementById('navbarNav');
            const hamburger = document.getElementById('hamburger');
            
            if (!navbarNav.contains(e.target) && !hamburger.contains(e.target)) {
                navbarNav.classList.remove('active');
                hamburger.classList.remove('active');
            }
        });
    </script>
</body>
</html>