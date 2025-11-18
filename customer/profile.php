<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth-check.php';

checkCustomerAuth();

$customer_id = $_SESSION['customer_id'];
$customer = getCustomerById($customer_id);

// Initialize variables dengan nilai default
$nama = $customer['nama'] ?? '';
$email = $customer['email'] ?? '';
$no_hp = $customer['no_hp'] ?? '';
$alamat = $customer['alamat'] ?? '';
$foto_profil = $customer['foto_profil'] ?? '';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = sanitize($_POST['nama'] ?? '');
    $no_hp = sanitize($_POST['no_hp'] ?? '');
    $alamat = sanitize($_POST['alamat'] ?? '');

    if (empty($nama)) {
        $error = 'Nama harus diisi!';
    } else {
        // Handle profile picture upload
        $new_foto_profil = $foto_profil;
        if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] === UPLOAD_ERR_OK) {
            if (!empty($foto_profil)) {
                deleteImage($foto_profil, PROFILE_IMG_DIR);
            }
            $new_foto_profil = uploadImage($_FILES['foto_profil'], PROFILE_IMG_DIR);
            if (!$new_foto_profil) {
                $error = 'Format foto tidak didukung atau ukuran terlalu besar!';
            }
        }

        if (!$error) {
            $update_query = "UPDATE customers SET nama = ?, no_hp = ?, alamat = ?, foto_profil = ? WHERE id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("ssssi", $nama, $no_hp, $alamat, $new_foto_profil, $customer_id);

            if ($stmt->execute()) {
                $success = 'Profil berhasil diperbarui!';
                $_SESSION['customer_nama'] = $nama;
                $customer = getCustomerById($customer_id);
                $foto_profil = $customer['foto_profil'] ?? '';
            } else {
                $error = 'Terjadi kesalahan saat memperbarui profil!';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Brew Bakery</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <style>
        .profile-layout {
            min-height: calc(100vh - 70px);
            background-color: var(--light);
            padding: 2rem 0;
        }
        .profile-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        .profile-card {
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .profile-card h1 {
            color: var(--primary);
            margin-bottom: 2rem;
        }
        .profile-photo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .profile-photo img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--primary);
        }
        .profile-photo .placeholder {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background-color: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            margin: 0 auto;
            border: 4px solid var(--primary);
        }
        .form-section {
            margin-bottom: 2rem;
        }
        .form-section h3 {
            margin-bottom: 1rem;
            color: var(--dark);
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 0.3rem;
            box-sizing: border-box;
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
        .image-upload {
            border: 2px dashed var(--primary);
            border-radius: 0.5rem;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            background-color: rgba(139, 111, 71, 0.05);
            transition: all 0.3s;
        }
        .image-upload:hover {
            border-color: var(--dark);
            background-color: rgba(139, 111, 71, 0.1);
        }
        .image-upload input {
            display: none;
        }
        .form-actions {
            display: flex;
            gap: 1rem;
        }
        .btn-submit {
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
        .btn-submit:hover {
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
        }
        .btn-cancel:hover {
            background-color: var(--primary);
        }
        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/includes/navbar.php'; ?>
    
    <div class="profile-layout">
        <div class="profile-container">
            <div class="profile-card">
                <h1>👤 Profil Saya</h1>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <div class="profile-photo">
                        <?php if (!empty($foto_profil)): ?>
                            <img src="<?php echo PROFILE_IMG_URL . htmlspecialchars($foto_profil); ?>" alt="Foto Profil">
                        <?php else: ?>
                            <div class="placeholder">👤</div>
                        <?php endif; ?>
                    </div>

                    <div class="form-section">
                        <h3>Informasi Pribadi</h3>
                        
                        <div class="form-group">
                            <label for="nama">Nama Lengkap *</label>
                            <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($nama); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email (Tidak bisa diubah)</label>
                            <input type="email" id="email" value="<?php echo htmlspecialchars($email); ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label for="no_hp">Nomor HP</label>
                            <input type="tel" id="no_hp" name="no_hp" value="<?php echo htmlspecialchars($no_hp); ?>" placeholder="082xxxxxxxxx">
                        </div>

                        <div class="form-group">
                            <label for="alamat">Alamat Lengkap</label>
                            <textarea id="alamat" name="alamat" placeholder="Jl. ..., Kelurahan, Kecamatan, Kota, Provinsi, Kode Pos"><?php echo htmlspecialchars($alamat); ?></textarea>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Foto Profil</h3>
                        <div class="form-group">
                            <div class="image-upload" onclick="document.getElementById('foto_profil').click()">
                                <p>📷 Klik untuk upload atau drag file di sini</p>
                                <p style="color: #666; font-size: 0.9rem;">Format: JPG, PNG, GIF (max 5MB)</p>
                            </div>
                            <input type="file" id="foto_profil" name="foto_profil" accept="image/*" onchange="previewImage(this)">
                            <div id="imagePreview"></div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-submit">💾 Simpan Perubahan</button>
                        <a href="<?php echo CUSTOMER_URL; ?>dashboard.php" class="btn-cancel">← Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '200px';
                    img.style.marginTop = '1rem';
                    img.style.borderRadius = '0.3rem';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
