<?php
session_start();
require 'config/config.php';

$error = '';

// Proses form jika ada metode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $captcha_answer = trim($_POST['captcha']);

    // 1. Validasi CAPTCHA langsung lanjut ke cek database jika benar
    if (empty($captcha_answer) || $captcha_answer != $_SESSION['captcha_answer']) {
        $error = "Jawaban CAPTCHA salah. Silakan coba lagi.";
    } else {
        // 2. Cari pengguna berdasarkan Username ATAU Email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();

        // 3. Validasi Password
        // 3. Validasi Password dan Hak Akses (Role)
        if ($user) {
            if (password_verify($password, $user['password']) || $password === $user['password']) {
                // Simpan data ke session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role']; // <-- SIMPAN ROLE DI SINI
                
                // Cek Role untuk mengarahkan ke halaman yang tepat
                if ($_SESSION['role'] === 'admin') {
                    header("Location: admin_dashboard.php"); // Lempar ke panel admin
                } else {
                    header("Location: index.php"); // Lempar ke halaman utama
                }
                exit();
            } else {
                $error = "Password yang Anda masukkan salah.";
            }
        } else {
            $error = "Username atau alamat Email tidak ditemukan.";
        }
    }
}

// Generate angka acak baru untuk CAPTCHA sesi ini
$num1 = rand(1, 9);
$num2 = rand(1, 9);
$_SESSION['captcha_answer'] = $num1 + $num2;
$captcha_text = "Berapa hasil dari $num1 + $num2 ?";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Anggota - Pancaloka</title>
    
    <?php include 'includes/navbar.php'; ?>

    <style>
        /* Menggunakan tema Cozy Library */
        body {
            background-color: #FAF6F0 !important;
        }
        .login-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(27, 73, 56, 0.08);
            background-color: #ffffff;
        }
        .login-header {
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
        .captcha-box {
            background-color: #e9ecef;
            border: 1px dashed #6c757d;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            font-weight: bold;
            font-size: 1.1rem;
            color: #1B4938;
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card login-card">
                    <div class="login-header">
                        <h4 class="mb-0 fw-bold"><i class="bi bi-door-open-fill"></i> Masuk Ruang Baca</h4>
                    </div>
                    <div class="card-body p-4">
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger p-2 text-center" role="alert">
                                <i class="bi bi-exclamation-triangle-fill"></i> <?= $error ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="username" class="form-label fw-bold text-secondary">Username atau Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan nama akun atau email" required autofocus>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="password" class="form-label fw-bold text-secondary">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password Anda" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-secondary">Verifikasi Keamanan</label>
                                <div class="captcha-box mb-2">
                                    <?= $captcha_text ?>
                                </div>
                                <input type="number" class="form-control text-center" name="captcha" placeholder="Ketikkan jawaban angka di sini" required>
                            </div>

                            <button type="submit" class="btn btn-custom w-100 py-2 mb-3">Masuk Sekarang</button>
                            
                            <div class="text-center mt-3">
                                <small class="text-muted">Belum terdaftar sebagai anggota?</small><br>
                                <a href="register.php" class="text-decoration-none fw-bold" style="color: #E07A5F;">Daftar Akun Baru</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>