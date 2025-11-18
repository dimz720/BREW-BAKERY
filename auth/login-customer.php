<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/functions.php';

session_start();

if (isset($_SESSION['customer_id'])) {
    redirect(CUSTOMER_URL . 'dashboard.php');
}

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Email dan password harus diisi!';
    } else {
        $query = "SELECT * FROM customers WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $customer = $result->fetch_assoc();

        if ($customer && verifyPassword($password, $customer['password'])) {
            $_SESSION['customer_id'] = $customer['id'];
            $_SESSION['customer_email'] = $customer['email'];
            $_SESSION['customer_nama'] = $customer['nama'];
            redirect(CUSTOMER_URL . 'dashboard.php');
        } else {
            $error = 'Email atau password salah!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Customer - Brew Bakery</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--dark) 0%, var(--primary) 100%);
            padding: 1rem;
        }
        .login-card {
            width: 100%;
            max-width: 450px;
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-header h1 {
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        .login-header p {
            color: var(--primary);
            font-weight: 600;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid var(--primary);
            border-radius: 0.5rem;
            font-size: 1rem;
            box-sizing: border-box;
        }
        .btn-login {
            width: 100%;
            padding: 1rem;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-login:hover {
            background-color: var(--dark);
        }
        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #666;
        }
        .login-footer a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>🍞 Brew Bakery</h1>
                <p>Masuk Sebagai Customer</p>
            </div>

            <?php if ($error): ?>
                <div class="alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn-login">Masuk Sekarang</button>
            </form>

            <div class="login-footer">
                <p>Belum punya akun? <a href="<?php echo AUTH_URL; ?>register-customer.php">Daftar di sini</a></p>
                <p><a href="<?php echo BASE_URL; ?>">Kembali ke Beranda</a></p>
                <p><a href="<?php echo AUTH_URL; ?>login-admin.php">Masuk sebagai Admin</a></p>
            </div>
        </div>
    </div>
</body>
</html>
