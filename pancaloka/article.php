<?php
session_start();
require 'config/config.php'; 

// Menangkap nilai pencarian dan filter dari URL
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Membangun query SQL secara dinamis berdasarkan pencarian/filter
$query = "SELECT a.*, u.username, 
          (SELECT COUNT(*) FROM article_comments WHERE article_id = a.id) as comment_count,
          (SELECT COUNT(*) FROM article_likes WHERE article_id = a.id) as like_count 
          FROM articles a JOIN users u ON a.user_id = u.id WHERE 1=1";
$params = [];

if (!empty($search)) {
    $query .= " AND a.title LIKE ?";
    $params[] = "%$search%";
}
if (!empty($category)) {
    $query .= " AND a.category = ?";
    $params[] = $category;
}

$query .= " ORDER BY a.created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$articles = $stmt->fetchAll();

// Mengambil daftar kategori unik untuk dropdown filter
$stmt_cat = $pdo->query("SELECT DISTINCT category FROM articles WHERE category IS NOT NULL");
$categories = $stmt_cat->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikel & Kajian - Pancaloka</title>
    <?php include 'includes/navbar.php'; ?>
    <style>
        :root {
          --primary-green: #1B4938; --accent-terra: #E07A5F; --accent-gold: #F2CC8F; --bg-paper: #FAF6F0; --text-main: #333333;
        }
        body { background-color: var(--bg-paper) !important; color: var(--text-main); }
        .article-card {
            border: none; border-radius: 16px; background-color: #ffffff;
            box-shadow: 0 4px 15px rgba(27, 73, 56, 0.05); transition: transform 0.3s ease; height: 100%; text-decoration: none; color: inherit; display: block;
        }
        .article-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(27, 73, 56, 0.1); color: inherit; }
        .article-img { height: 200px; width: 100%; object-fit: cover; border-radius: 16px 16px 0 0; }
        .article-title { color: var(--primary-green); font-weight: 700; font-size: 1.2rem; }
        .badge-category { background-color: var(--accent-gold); color: var(--primary-green); font-size: 0.8rem; padding: 4px 10px; border-radius: 20px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h3 class="fw-bold" style="color: var(--primary-green);">Kajian & Artikel Ilmiah</h3>
                <p class="text-muted">Jelajahi berbagai sudut pandang literasi dari anggota Pancaloka.</p>
            </div>
            <div class="col-md-6">
                <form method="GET" action="article.php" class="d-flex gap-2">
                    <select name="category" class="form-select w-auto">
                        <option value="">Semua Kategori</option>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat) ?>" <?= ($category == $cat) ? 'selected' : '' ?>><?= htmlspecialchars($cat) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" name="search" class="form-control" placeholder="Cari judul artikel..." value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="btn text-white" style="background-color: var(--primary-green);">Cari</button>
                </form>
            </div>
        </div>

        <div class="row g-4">
            <?php if (count($articles) > 0): ?>
                <?php foreach ($articles as $artikel): ?>
                    <div class="col-md-6 col-lg-4">
                        <a href="article_detail.php?id=<?= $artikel['id'] ?>" class="article-card">
                            <?php if (!empty($artikel['image_url'])): ?>
                                <img src="<?= htmlspecialchars($artikel['image_url']) ?>" class="article-img" alt="Sampul">
                            <?php else: ?>
                                <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?q=80&w=600" class="article-img" alt="Default">
                            <?php endif; ?>
                            <div class="card-body p-4">
                                <span class="badge-category mb-2 d-inline-block"><?= htmlspecialchars($artikel['category']) ?></span>
                                <h5 class="article-title mb-2"><?= htmlspecialchars($artikel['title']) ?></h5>
                                <div class="text-muted small mb-3">
                                    <i class="bi bi-person"></i> <?= htmlspecialchars($artikel['username']) ?> &nbsp;|&nbsp; 
                                    <i class="bi bi-calendar3"></i> <?= date('d M Y', strtotime($artikel['created_at'])) ?>
                                </div>
                                <p class="card-text text-secondary small">
                                    <?= htmlspecialchars(substr($artikel['content'], 0, 120)) ?>...
                                </p>
                                <div class="d-flex gap-3 text-muted small fw-bold mt-3">
                                    <span><i class="bi bi-heart-fill" style="color: var(--accent-terra);"></i> <?= $artikel['like_count'] ?></span>
                                    <span><i class="bi bi-chat-fill text-success"></i> <?= $artikel['comment_count'] ?></span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center p-5 bg-white rounded-3">
                    <p class="text-muted mb-0">Artikel yang Anda cari tidak ditemukan.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="mb-5"></div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>