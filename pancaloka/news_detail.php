<?php
session_start();
require 'config/config.php';

// Cek apakah ada ID berita yang dikirim
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: news.php");
    exit();
}

$news_id = $_GET['id'];

// Mengambil data berita spesifik berdasarkan ID
$stmt = $pdo->prepare("SELECT n.*, u.username FROM news n JOIN users u ON n.user_id = u.id WHERE n.id = ?");
$stmt->execute([$news_id]);
$news = $stmt->fetch();

// Jika berita tidak ditemukan di database
if (!$news) {
    die("<div style='text-align:center; margin-top:50px; font-family:sans-serif;'><h3>Berita tidak ditemukan.</h3><a href='news.php'>Kembali</a></div>");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($news['title']) ?> - Pancaloka</title>
    
    <?php include 'includes/navbar.php'; ?>

    <style>
        :root {
          --primary-green: #1B4938; --accent-terra: #E07A5F; --accent-gold: #F2CC8F; --bg-paper: #FAF6F0; --text-main: #333333;
        }
        body { background-color: var(--bg-paper) !important; color: var(--text-main); }
        
        .news-container {
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(27, 73, 56, 0.05);
            padding: 40px;
            margin-top: 20px;
        }
        .hero-img {
            width: 100%;
            height: 450px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .news-title { color: var(--primary-green); font-weight: 800; font-size: 2rem; line-height: 1.3; }
        .news-date-badge { background-color: var(--accent-gold); color: var(--primary-green); font-weight: bold; padding: 6px 15px; border-radius: 20px; font-size: 0.85rem; display: inline-block; }
        .news-content { font-size: 1.1rem; line-height: 1.8; color: #4F4F4F; white-space: pre-line; text-align: justify; }
    </style>
</head>
<body>

    <div class="container mt-4 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <a href="news.php" class="text-decoration-none d-inline-block fw-bold" style="color: var(--primary-green); transition: 0.2s;">
                    <i class="bi bi-arrow-left-circle-fill me-1"></i> Kembali ke Daftar Berita
                </a>

                <div class="news-container">
                    
                    <div class="text-center mb-4">
                        <span class="news-date-badge mb-3">
                            <i class="bi bi-tag-fill me-1"></i> <?= htmlspecialchars($news['category'] ?? 'Pengumuman') ?>
                        </span>
                        <h1 class="news-title mb-3"><?= htmlspecialchars($news['title']) ?></h1>
                        
                        <div class="text-muted d-flex justify-content-center align-items-center gap-3">
                            <span><i class="bi bi-calendar-event"></i> Diterbitkan: <b><?= date('d F Y', strtotime($news['created_at'])) ?></b></span>
                            <span>|</span>
                            <span><i class="bi bi-person-fill"></i> Penulis: <b><?= htmlspecialchars($news['username']) ?></b></span>
                        </div>
                    </div>

                    <?php if (!empty($news['image_url'])): ?>
                        <img src="<?= htmlspecialchars($news['image_url']) ?>" class="hero-img" alt="Cover Berita">
                    <?php else: ?>
                        <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=1200" class="hero-img" alt="Default Cover">
                    <?php endif; ?>

                    <div class="news-content">
                        <?= htmlspecialchars($news['content']) ?>
                    </div>
                    
                    <hr class="mt-5 mb-4" style="opacity: 0.1;">
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Bagikan kabar ini:</span>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-light rounded-circle" title="Bagikan ke WhatsApp"><i class="bi bi-whatsapp text-success"></i></button>
                            <button class="btn btn-sm btn-light rounded-circle" title="Bagikan ke Twitter/X"><i class="bi bi-twitter-x text-dark"></i></button>
                            <button class="btn btn-sm btn-light rounded-circle" title="Salin Tautan" onclick="navigator.clipboard.writeText(window.location.href); alert('Tautan disalin!');"><i class="bi bi-link-45deg text-secondary"></i></button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>

</body>
</html>