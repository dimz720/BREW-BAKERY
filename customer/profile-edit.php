<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth-check.php';

checkCustomerAuth();

$customer_id = $_SESSION['user_id'];
$customer = getUserById($customer_id);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = sanitize($_POST['nama'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $no_hp = sanitize($_POST['no_hp'] ?? '');
    $alamat = sanitize($_POST['alamat'] ?? '');
    
    if (empty($nama) || empty($email) || empty($no_hp)) {
        $error = 'Nama, email, dan nomor HP harus diisi!';
    } else {
        // Check if email already exists (different user)
        $check_query = "SELECT id FROM users WHERE email = ? AND id != ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("si", $email, $customer_id);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows > 0) {
            $error = 'Email sudah digunakan oleh pengguna lain!';
        } else {
            $update_query = "UPDATE users SET nama = ?, email = ?, no_hp = ?, alamat = ? WHERE id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("ssssi", $nama, $email, $no_hp, $alamat, $customer_id);
            
            if ($stmt->execute()) {
                $_SESSION['nama'] = $nama;
                $_SESSION['email'] = $email;
                $success = 'Profil berhasil diperbarui!';
                $customer = getUserById($customer_id);
            } else {
                $error = 'Terjadi kesalahan saat memperbarui profil!';
            }
        }
    }
}

// Handle photo upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto_profil'])) {
    if ($_FILES['foto_profil']['error'] === UPLOAD_ERR_OK) {
        // Delete old photo if exists
        if ($customer['foto_profil']) {
            deleteImage($customer['foto_profil'], PROFILE_IMG_DIR);
        }
        
        $filename = uploadImage($_FILES['foto_profil'], PROFILE_IMG_DIR);
        if ($filename) {
            $update_photo = "UPDATE users SET foto_profil = ? WHERE id = ?";
            $stmt = $conn->prepare($update_photo);
            $stmt->bind_param("si", $filename, $customer_id);
            $stmt->execute();
            $customer = getUserById($customer_id);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - Brew Bakery</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <style>
        .profile-layout {
            min-height: calc(100vh - 70px);
            background-color: var(--light);
            padding: 2rem 0;
        }
        .profile-container {
            max-width: 700px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        .profile-card {
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .profile-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .profile-photo-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 2rem;
        }
        .profile-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            overflow: hidden;
            border: 3px solid var(--primary);
        }
        .profile-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .photo-upload-label {
            display: inline-block;
            padding: 0.5rem 1rem;
            background-color: var(--secondary);
            color: white;
            border-radius: 0.3rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .photo-upload-label:hover {
            background-color: var(--primary);
        }
        #foto_profil {
            display: none;
        }
        .form-section {
            margin-bottom: 2rem;
        }
        .form-section h3 {
            margin-bottom: 1rem;
            color: var(--primary);
            border-bottom: 2px solid var(--primary);
            padding-bottom: 0.5rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--dark);
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 0.3rem;
            font-family: inherit;
            font-size: 1rem;
        }
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(139, 111, 71, 0.1);
        }
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        .btn-save {
            flex: 1;
            padding: 1rem;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 0.3rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-save:hover {
            background-color: var(--dark);
        }
        .btn-cancel {
            flex: 1;
            padding: 1rem;
            background-color: var(--secondary);
            color: white;
            border: none;
            border-radius: 0.3rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.3s;
        }
        .btn-cancel:hover {
            background-color: var(--primary);
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/includes/navbar.php'; ?>
    
    <div class="profile-layout">
        <div class="profile-container">
            <div class="profile-card">
                <div class="profile-header">
                    <h1>👤 Edit Profil</h1>
                    <p>Perbarui informasi pribadi Anda</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <div class="profile-photo-section">
                    <div class="profile-photo">
                        <?php if ($customer['foto_profil']): ?>
                            <img src="<?php echo PROFILE_IMG_URL . $customer['foto_profil']; ?>" alt="<?php echo htmlspecialchars($customer['nama']); ?>" id="photoPreview">
                        <?php else: ?>
                            <span style="font-size: 3rem;">👤</span>
                        <?php endif; ?>
                    </div>
                    <form method="POST" enctype="multipart/form-data" style="width: 100%; max-width: 300px;">
                        <label for="foto_profil" class="photo-upload-label">Ubah Foto Profil</label>
                        <input type="file" id="foto_profil" name="foto_profil" accept="image/*" onchange="previewPhoto(event); this.form.submit();">
                    </form>
                </div>

                <form method="POST" action="">
                    <div class="form-section">
                        <h3>Informasi Pribadi</h3>
                        <div class="form-group">
                            <label for="nama">Nama Lengkap</label>
                            <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($customer['nama']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($customer['email']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="no_hp">Nomor HP</label>
                            <input type="tel" id="no_hp" name="no_hp" value="<?php echo htmlspecialchars($customer['no_hp']); ?>" required>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Alamat</h3>
                        <div class="form-group">
                            <label for="alamat">Alamat Lengkap</label>
                            <textarea id="alamat" name="alamat"><?php echo htmlspecialchars($customer['alamat']); ?></textarea>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-save">💾 Simpan Perubahan</button>
                        <a href="<?php echo CUSTOMER_URL; ?>profile.php" class="btn-cancel">← Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewPhoto(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('photoPreview');
                    if (preview) {
                        preview.src = e.target.result;
                    } else {
                        const photoDiv = document.querySelector('.profile-photo');
                        photoDiv.innerHTML = '<img id="photoPreview" src="' + e.target.result + '" alt="Preview">';
                    }
                };
                reader.readAsDataURL(file);
            }
        }
    </script>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
