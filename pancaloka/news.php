<?php
session_start();
require 'config/config.php'; 

// Menangkap nilai pencarian dan filter dari URL
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Membangun query SQL secara dinamis
$query = "SELECT n.*, u.username FROM news n JOIN users u ON n.user_id = u.id WHERE 1=1";
$params = [];

if (!empty($search)) {
    $query .= " AND n.title LIKE ?";
    $params[] = "%$search%";
}
if (!empty($category)) {
    $query .= " AND n.category = ?";
    $params[] = $category;
}

$query .= " ORDER BY n.created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$news_items = $stmt->fetchAll();

// Mengambil daftar kategori unik untuk dropdown filter
$stmt_cat = $pdo->query("SELECT DISTINCT category FROM news WHERE category IS NOT NULL");
$categories = $stmt_cat->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita & Pengumuman - Pancaloka</title>
    
    <?php include 'includes/navbar.php'; ?>

    <style>
        :root {
          --primary-green: #1B4938; --accent-terra: #E07A5F; --accent-gold: #F2CC8F; --bg-paper: #FAF6F0; --text-main: #333333;
        }
        body { background-color: var(--bg-paper) !important; color: var(--text-main); }
        .section-title { color: var(--primary-green); font-weight: 700; position: relative; padding-bottom: 10px; }
        .section-title::after { content: ''; position: absolute; left: 0; bottom: 0; width: 60px; height: 4px; background-color: var(--accent-terra); border-radius: 2px; }
        
        .news-card { border: none; border-radius: 12px; overflow: hidden; background-color: #ffffff; box-shadow: 0 4px 10px rgba(27, 73, 56, 0.04); transition: transform 0.2s ease; }
        .news-card:hover { transform: translateX(5px); box-shadow: 0 6px 15px rgba(27, 73, 56, 0.08); }
        .news-img { height: 100%; min-height: 220px; width: 100%; object-fit: cover; }
        .news-title { color: var(--primary-green); font-weight: 700; text-decoration: none; font-size: 1.3rem; }
        .news-title:hover { color: var(--accent-terra); }
        .news-date-badge { background-color: var(--accent-gold); color: var(--primary-green); font-weight: bold; padding: 5px 15px; border-radius: 20px; font-size: 0.8rem; display: inline-block; margin-bottom: 10px; }
    </style>
</head>
<body>

    <div class="container mt-5">
        <div class="row mb-5 align-items-center">
            <div class="col-lg-6 mb-3 mb-lg-0">
                <h3 class="section-title">Kabar Pancaloka</h3>
                <p class="text-muted mt-2 mb-0">Ikuti terus pembaruan acara dan informasi penting komunitas.</p>
            </div>
            
            <div class="col-lg-6">
                <form method="GET" action="news.php" class="d-flex gap-2 justify-content-lg-end">
                    <select name="category" class="form-select w-auto shadow-sm" style="border-radius: 8px;">
                        <option value="">Semua Kategori</option>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat) ?>" <?= ($category == $cat) ? 'selected' : '' ?>><?= htmlspecialchars($cat) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" name="search" class="form-control shadow-sm" placeholder="Cari berita..." value="<?= htmlspecialchars($search) ?>" style="border-radius: 8px; max-width: 250px;">
                    <button type="submit" class="btn text-white shadow-sm" style="background-color: var(--primary-green); border-radius: 8px;">Cari</button>
                </form>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <?php if (count($news_items) > 0): ?>
                    <?php foreach ($news_items as $news): ?>
                        <div class="card news-card mb-4">
                            <div class="row g-0 h-100">
                                <div class="col-md-4">
                                    <?php if (!empty($news['image_url'])): ?>
                                        <img src="<?= htmlspecialchars($news['image_url']) ?>" class="news-img" alt="Foto Berita">
                                    <?php else: ?>
                                        <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=600" class="news-img" alt="Default Foto Berita">
                                    <?php endif; ?>
                                </div>
                                
                                <div class="col-md-8">
                                    <div class="card-body p-4 d-flex flex-column h-100">
                                        <div>
                                            <span class="news-date-badge">
                                                <i class="bi bi-megaphone-fill me-1"></i> <?= htmlspecialchars($news['category'] ?? 'Pengumuman') ?>
                                            </span>
                                            
                                            <h4 class="mb-2">
                                                <a href="news_detail.php?id=<?= $news['id'] ?>" class="news-title"><?= htmlspecialchars($news['title']) ?></a>
                                            </h4>
                                            
                                            <div class="text-muted small mb-3">
                                                <i class="bi bi-calendar-event"></i> <?= date('d F Y', strtotime($news['created_at'])) ?>
                                                <span class="mx-2">|</span>
                                                <i class="bi bi-person-fill"></i> <?= htmlspecialchars($news['username']) ?>
                                            </div>
                                            
                                            <p class="card-text text-secondary mb-3">
                                                <?= htmlspecialchars(substr($news['content'], 0, 180)) ?>...
                                            </p>
                                        </div>
                                        
                                        <div class="mt-auto">
                                            <a href="news_detail.php?id=<?= $news['id'] ?>" class="btn btn-sm btn-outline-success rounded-pill px-4 fw-bold" style="border-color: var(--primary-green); color: var(--primary-green);">Baca Rincian</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center p-5 bg-white rounded-3 shadow-sm">
                        <i class="bi bi-newspaper display-4 text-muted"></i>
                        <p class="text-muted mt-3 mb-0">Belum ada kabar atau pengumuman yang sesuai dengan pencarian Anda.</p>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
    <div class="mb-5"></div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>