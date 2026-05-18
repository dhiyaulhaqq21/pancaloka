<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
    /* Tema Dasar Website: Cozy Library */
    :root {
      --primary-green: #1B4938;
      --accent-terra: #E07A5F;
      --accent-gold: #F2CC8F;
      --bg-paper: #FAF6F0;
      --text-main: #333333;
      /* Mengambil warna ungu gelap dari logo Pancaloka untuk teks logo */
      --logo-color: #3b2333; 
    }

    .custom-navbar {
        padding: 10px 0;
        background-color: #ffffff;
        border-bottom: 2px solid var(--accent-gold);
    }
    
    /* Styling khusus untuk Logo dan Teks Pancaloka */
    .brand-social {
        color: var(--logo-color);
        font-weight: 800;
        font-size: 1.5rem;
        letter-spacing: 2px;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 12px; /* Jarak antara gambar logo dan teks */
        transition: opacity 0.3s ease;
    }
    
    .brand-social:hover {
        color: var(--logo-color);
        opacity: 0.8;
    }

    /* Ukuran gambar logo di navbar */
    .navbar-logo-img {
        height: 50px; /* Bisa diperbesar/diperkecil dengan mengubah angka ini */
        width: auto;
        object-fit: contain;
    }
    
    /* Styling khusus untuk Menu Navigasi */
    .nav-link-custom {
        color: var(--primary-green) !important;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 5px 0 !important;
        margin: 0 12px;
        text-transform: uppercase;
        transition: 0.3s;
    }
    
    .nav-link-custom:hover {
        color: var(--accent-terra) !important;
    }
    
    /* Styling menu yang sedang aktif */
    .nav-link-active {
        color: var(--accent-terra) !important;
        border-bottom: 3px solid var(--accent-terra);
    }
    
    /* Styling tombol Login */
    .login-btn {
        color: var(--primary-green);
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 5px;
        margin-left: 15px;
        transition: 0.3s;
    }
    
    .login-btn:hover {
        color: var(--accent-terra);
    }
</style>

<nav class="navbar navbar-expand-lg custom-navbar">
  <div class="container-fluid px-4 px-lg-5">
    
    <a class="navbar-brand brand-social" href="index.php">
        <img src="assets/img/logo-pancaloka.png" alt="Logo Pancaloka" class="navbar-logo-img">
        PANCALOKA
    </a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <?php
        // Mendapatkan nama file yang sedang aktif (contoh: /komunitas_baca/partnership.php)
        // Lalu mengambil hanya nama filenya saja menggunakan basename() (contoh: partnership.php)
        $current_page = basename($_SERVER['SCRIPT_NAME']);
        ?>

        <ul class="navbar-nav align-items-center">
            <li class="nav-item">
                <a class="nav-link nav-link-custom <?= ($current_page == 'index.php') ? 'nav-link-active' : '' ?>" href="index.php">HOME</a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link nav-link-custom <?= ($current_page == 'article.php') ? 'nav-link-active' : '' ?>" href="article.php">ARTICLE</a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link nav-link-custom <?= ($current_page == 'news.php') ? 'nav-link-active' : '' ?>" href="news.php">NEWS</a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link nav-link-custom <?= ($current_page == 'partnership.php') ? 'nav-link-active' : '' ?>" href="partnership.php">PARTNERSHIP</a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link nav-link-custom <?= ($current_page == 'community.php') ? 'nav-link-active' : '' ?>" href="community.php">COMMUNITY</a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link nav-link-custom <?= ($current_page == 'kolaborasi.php') ? 'nav-link-active' : '' ?>" href="kolaborasi.php">KOLABORASI</a>
            </li>
            
            <li class="nav-item mt-3 mt-lg-0 ms-lg-3">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <div class="dropdown">
                        <a class="login-btn dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-check-fill fs-5"></i> <?= htmlspecialchars($_SESSION['username']) ?>
                        </a>
                       <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                            <li><a class="dropdown-item text-dark fw-bold" href="profile.php"><i class="bi bi-person-bounding-box text-success me-2"></i> Profil Saya</a></li>
                            
                            <?php if($_SESSION['role'] === 'admin'): ?>
                                <li><a class="dropdown-item text-success fw-bold" href="admin_dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Panel Admin</a></li>
                            <?php endif; ?>
                            
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="login-btn">
                        <i class="bi bi-person-fill fs-5"></i> LOGIN
                    </a>
                <?php endif; ?>
            </li>
        </ul>
    </div>
  </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>