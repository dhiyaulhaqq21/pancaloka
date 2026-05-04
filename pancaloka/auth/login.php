<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Pancaloka</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #4e73df, #1cc88a);
            height: 100vh;
        }
        .login-card {
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
    <div class="card login-card shadow p-4" style="width: 400px;">

        <div class="text-center mb-4">
            <div class="logo">Pancaloka</div>
            <small class="text-muted">Komunitas Baca</small>
        </div>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="proses_login.php" method="POST">

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" onclick="togglePassword()">
                <label class="form-check-label">Lihat Password</label>
            </div>

            <script>
            function togglePassword() {
                let pass = document.querySelector('input[name="password"]');
                pass.type = pass.type === "password" ? "text" : "password";
            }
            </script>

            <!-- CAPTCHA -->
            <div class="mb-3">
                <label class="form-label">Verifikasi</label><br>
                <img src="captcha.php" id="captchaImg" class="mb-2 border rounded">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="refreshCaptcha()">Refresh</button>

                    <script>
                    function refreshCaptcha() {
                        document.getElementById("captchaImg").src = "captcha.php?" + Date.now();
                    }
                    </script>
                <input type="text" name="captcha" class="form-control" placeholder="Jawaban CAPTCHA" required>
            </div>

            <!-- Button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>

        </form>

        <div class="text-center mt-3">
            <small>Belum punya akun? <a href="register.php">Daftar</a></small>
        </div>

    </div>
</div>

</body>
</html>