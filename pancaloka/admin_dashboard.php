<?php
session_start();
require 'config/config.php';

// GEMBOK KEAMANAN: Tendang jika belum login atau bukan admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Mengambil statistik ringkas untuk ditampilkan di dashboard
$stat_users = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();
$stat_articles = $pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn();
$stat_news = $pdo->query("SELECT COUNT(*) FROM news")->fetchColumn();
$stat_partners = $pdo->query("SELECT COUNT(*) FROM partnerships")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Pancaloka</title>
    <?php include 'includes/navbar.php'; ?>
    
    <style>
        body { background-color: #f4f6f9 !important; }
        .admin-header {
            background: linear-gradient(135deg, #1B4938 0%, #2b7056 100%);
            color: white;
            padding: 40px 0;
            border-radius: 0 0 30px 30px;
            margin-bottom: -50px;
        }
        .stat-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.2;
            position: absolute;
            right: 20px;
            bottom: 10px;
        }
        .menu-card {
            text-align: center;
            text-decoration: none;
            color: #333;
            border: none;
            border-radius: 12px;
            padding: 30px 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
            display: block;
            background-color: white;
            transition: 0.2s;
            border-top: 4px solid transparent;
        }
        .menu-card:hover {
            border-top-color: #E07A5F;
            box-shadow: 0 6px 20px rgba(224, 122, 95, 0.15);
            color: #1B4938;
        }
        .menu-card i { font-size: 3rem; color: #1B4938; margin-bottom: 15px; display: block; }
    </style>
</head>
<body>

    <div class="admin-header shadow-sm">
        <div class="container pb-5">
            <h2 class="fw-bold"><i class="bi bi-shield-lock-fill text-warning"></i> Pusat Kendali Pancaloka</h2>
            <p class="mb-0 opacity-75">Selamat datang kembali, Administrator <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>.</p>
        </div>
    </div>

    <div class="container position-relative" style="z-index: 5;">
        
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card stat-card p-4 border-start border-4 border-success h-100">
                    <h6 class="text-muted text-uppercase fw-bold mb-1">Total Anggota</h6>
                    <h3 class="mb-0 fw-bold"><?= $stat_users ?></h3>
                    <i class="bi bi-people-fill stat-icon text-success"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card p-4 border-start border-4 border-primary h-100">
                    <h6 class="text-muted text-uppercase fw-bold mb-1">Artikel Kajian</h6>
                    <h3 class="mb-0 fw-bold"><?= $stat_articles ?></h3>
                    <i class="bi bi-journal-text stat-icon text-primary"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card p-4 border-start border-4 border-warning h-100">
                    <h6 class="text-muted text-uppercase fw-bold mb-1">Berita & Acara</h6>
                    <h3 class="mb-0 fw-bold"><?= $stat_news ?></h3>
                    <i class="bi bi-megaphone-fill stat-icon text-warning"></i>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card p-4 border-start border-4 border-danger h-100">
                    <h6 class="text-muted text-uppercase fw-bold mb-1">Mitra Resmi</h6>
                    <h3 class="mb-0 fw-bold"><?= $stat_partners ?></h3>
                    <i class="bi bi-diagram-3-fill stat-icon text-danger"></i>
                </div>
            </div>
        </div>

        <h5 class="fw-bold mb-4" style="color: #1B4938;">Menu Manajemen Konten & Pengguna</h5>
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <a href="admin_articles.php" class="menu-card">
                    <i class="bi bi-pencil-square"></i>
                    <h5 class="fw-bold mb-0">Kelola Artikel</h5>
                    <small class="text-muted">Tulis, edit, dan hapus artikel kajian.</small>
                </a>
            </div>
            <div class="col-md-3">
                <a href="admin_news.php" class="menu-card">
                    <i class="bi bi-newspaper"></i>
                    <h5 class="fw-bold mb-0">Kelola Berita</h5>
                    <small class="text-muted">Siarkan pengumuman & kegiatan terbaru.</small>
                </a>
            </div>
            <div class="col-md-3">
                <a href="admin_partners.php" class="menu-card">
                    <i class="bi bi-building"></i>
                    <h5 class="fw-bold mb-0">Jejaring Mitra</h5>
                    <small class="text-muted">Kelola data institusi kerja sama.</small>
                </a>
            </div>
            <div class="col-md-3">
                <a href="admin_users.php" class="menu-card">
                    <i class="bi bi-people-fill" style="color: #E07A5F;"></i>
                    <h5 class="fw-bold mb-0">Kelola Anggota</h5>
                    <small class="text-muted">Atur hak akses dan data akun anggota.</small>
                </a>
            </div>
        </div>

    </div>

</body>
</html>