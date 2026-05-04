<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register Pancaloka</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #4e73df, #1cc88a);
            height: 100vh;
        }
        .card-custom {
            border-radius: 15px;
        }
        .logo {
            font-weight: bold;
            font-size: 24px;
            color: #4e73df;
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card card-custom shadow p-4" style="width: 420px;">

        <div class="text-center mb-3">
            <div class="logo">Pancaloka</div>
            <small class="text-muted">Buat akun baru</small>
        </div>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <form action="proses_register.php" method="POST">

            <!-- Nama -->
            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" placeholder="Nama lengkap" required>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
            </div>

            <!-- Konfirmasi Password -->
            <div class="mb-3">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="confirm_password" class="form-control" placeholder="Ulangi password" required>
            </div>

            <!-- Show Password -->
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" onclick="togglePassword()">
                <label class="form-check-label">Lihat Password</label>
            </div>

            <!-- Button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-success">Daftar</button>
            </div>

        </form>

        <div class="text-center mt-3">
            <small>Sudah punya akun? <a href="login.php">Login</a></small>
        </div>

    </div>
</div>

<script>
function togglePassword() {
    let pass = document.querySelector('input[name="password"]');
    let confirm = document.querySelector('input[name="confirm_password"]');

    pass.type = pass.type === "password" ? "text" : "password";
    confirm.type = confirm.type === "password" ? "text" : "password";
}
</script>

</body>
</html>