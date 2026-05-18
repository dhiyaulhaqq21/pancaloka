<?php
session_start();
require 'config/config.php';

$error = '';
$success = '';

// Proses form jika ada request POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // 1. Validasi Input Kosong
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Semua kolom wajib diisi.";
    } 
    // 2. Validasi Format Email
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    }
    // 3. Validasi Kecocokan Password
    elseif ($password !== $confirm_password) {
        $error = "Konfirmasi password tidak cocok.";
    } 
    else {
        // 4. Cek apakah username atau email sudah terdaftar
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        
        if ($stmt->fetch()) {
            $error = "Username atau Email sudah digunakan. Silakan gunakan yang lain.";
        } else {
            // 5. Enkripsi (Hash) Password untuk keamanan
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // 6. Masukkan data user baru ke database (termasuk kolom email)
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$username, $email, $hashed_password])) {
                $success = "Pendaftaran berhasil! Akun Anda telah dibuat.";
            } else {
                $error = "Terjadi kesalahan pada sistem. Silakan coba lagi.";
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
    <title>Daftar Anggota - Pancaloka</title>
    
    <?php include 'includes/navbar.php'; ?>

    <style>
        /* Menggunakan tema Cozy Library */
        body {
            background-color: #FAF6F0 !important;
        }
        .register-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(27, 73, 56, 0.08);
            background-color: #ffffff;
        }
        .register-header {
            background-color: #1B4938;
            color: #F2CC8F;
            border-radius: 16px 16px 0 0;
            padding: 20px;
            text-align: center;
        }
        .btn-custom {
            background-color: #1B4938;
            color: white;
            font-weight: 600;
            border: none;
        }
        .btn-custom:hover {
            background-color: #E07A5F;
            color: white;
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card register-card">
                    <div class="register-header">
                        <h4 class="mb-0 fw-bold"><i class="bi bi-person-plus-fill"></i> Bergabung Bersama Kami</h4>
                    </div>
                    <div class="card-body p-4">
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger p-2 text-center" role="alert">
                                <i class="bi bi-exclamation-triangle-fill"></i> <?= $error ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                            <div class="alert alert-success p-3 text-center" role="alert">
                                <i class="bi bi-check-circle-fill"></i> <?= $success ?><br><br>
                                <a href="login.php" class="btn btn-sm btn-success px-4">Lanjut Login</a>
                            </div>
                        <?php else: ?>
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label for="username" class="form-label fw-bold text-secondary">Pilih Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                        <input type="text" class="form-control" id="username" name="username" placeholder="Contoh: budi123" required autofocus>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label fw-bold text-secondary">Alamat Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Contoh: budi@gmail.com" required>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password" class="form-label fw-bold text-secondary">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="confirm_password" class="form-label fw-bold text-secondary">Ulangi Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-lock-fill"></i></span>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-custom w-100 py-2 mb-3">Daftar Sekarang</button>
                                
                                <div class="text-center mt-3">
                                    <small class="text-muted">Sudah menjadi anggota?</small><br>
                                    <a href="login.php" class="text-decoration-none fw-bold" style="color: #E07A5F;">Masuk di sini</a>
                                </div>
                            </form>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>