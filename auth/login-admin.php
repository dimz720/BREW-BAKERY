<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/functions.php';

session_start();

if (isset($_SESSION['admin_id'])) {
    redirect(ADMIN_URL . 'dashboard.php');
}

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Email dan password harus diisi!';
    } else {
        $query = "SELECT * FROM admins WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();

        // Debug mode - hapus komentar untuk melihat detail error
        /*
        echo "<pre>";
        echo "Email: " . $email . "\n";
        echo "Password: " . $password . "\n";
        echo "Admin found: " . ($admin ? 'Yes' : 'No') . "\n";
        if ($admin) {
            echo "Admin ID: " . $admin['id'] . "\n";
            echo "Admin Name: " . $admin['nama'] . "\n";
            echo "Password Hash: " . $admin['password'] . "\n";
            echo "Password Verify: " . (verifyPassword($password, $admin['password']) ? 'True' : 'False') . "\n";
        }
        echo "</pre>";
        die();
        */

        if ($admin && verifyPassword($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_email'] = $admin['email'];
            $_SESSION['admin_nama'] = $admin['nama'];
            redirect(ADMIN_URL . 'dashboard.php');
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
    <title>Login Admin - Brew Bakery</title>
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
            transition: background-color 0.3s;
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
                <p>Admin Panel</p>
            </div>

            <?php if ($error): ?>
                <div class="alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email Admin</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn-login">Masuk ke Admin Panel</button>
            </form>

            <div class="login-footer">
                <p><a href="<?php echo BASE_URL; ?>">Kembali ke Beranda</a></p>
                <p><a href="<?php echo AUTH_URL; ?>login-customer.php">Masuk sebagai Customer</a></p>
            </div>
        </div>
    </div>
</body>
</html>