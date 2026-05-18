<?php
session_start();
require 'config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if (isset($_GET['delete_id'])) {
    $stmt_del = $pdo->prepare("DELETE FROM news WHERE id = ?");
    $stmt_del->execute([$_GET['delete_id']]);
    header("Location: admin_news.php?status=deleted");
    exit();
}

// --- LOGIKA PENCARIAN & PAGINATION ---
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

$count_query = "SELECT COUNT(*) FROM news WHERE title LIKE ?";
$stmt_count = $pdo->prepare($count_query);
$stmt_count->execute(["%$search%"]);
$total_data = $stmt_count->fetchColumn();
$total_pages = ceil($total_data / $limit);

$query = "SELECT n.id, n.title, n.category, n.created_at, u.username 
          FROM news n JOIN users u ON n.user_id = u.id 
          WHERE n.title LIKE ? 
          ORDER BY n.created_at DESC LIMIT $limit OFFSET $offset";
$stmt = $pdo->prepare($query);
$stmt->execute(["%$search%"]);
$news_items = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Berita - Pancaloka Admin</title>
    <?php include 'includes/navbar.php'; ?>
    <style>
        :root { --primary-green: #1B4938; --accent-terra: #E07A5F; --accent-gold: #F2CC8F; }
        body { background-color: #f4f6f9 !important; }
        .table-container { background-color: white; border-radius: 16px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); }
        .table th { background-color: var(--primary-green) !important; color: white !important; }
        .google-pagination .page-link { color: #4285F4; border: none; font-weight: bold; font-size: 1.1rem; }
        .google-pagination .page-item.active .page-link { background: transparent; color: #EA4335; font-size: 1.4rem; }
        .google-logo-text { font-family: 'Product Sans', Arial, sans-serif; font-weight: bold; font-size: 1.5rem; letter-spacing: -1px; }
    </style>
</head>
<body>
    <div class="container my-5">
        <div class="row align-items-center mb-4">
            <div class="col-md-5">
                <a href="admin_dashboard.php" class="text-decoration-none small text-success fw-bold"><i class="bi bi-arrow-left"></i> Dashboard</a>
                <h3 class="fw-bold mt-1" style="color: var(--primary-green);">Manajemen Berita</h3>
            </div>
            <div class="col-md-7 d-flex gap-2 justify-content-md-end align-items-center">
                <form method="GET" class="d-flex gap-2 m-0">
                    <input type="text" name="search" class="form-control shadow-sm" placeholder="Cari berita..." value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="btn text-white" style="background-color: var(--primary-green);">Cari</button>
                </form>
                <a href="admin_news_form.php" class="btn text-white rounded-pill px-4 shadow-sm" style="background-color: var(--primary-green); font-weight:bold;"><i class="bi bi-megaphone-fill text-warning"></i> Siarkan Baru</a>
            </div>
        </div>

        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="45%">Judul Berita</th>
                            <th width="20%">Kategori</th>
                            <th width="15%">Penerbit</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($news_items) > 0): ?>
                            <?php $no = $offset + 1; foreach ($news_items as $news): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <span class="fw-bold text-dark"><?= htmlspecialchars($news['title']) ?></span><br>
                                        <small class="text-muted"><i class="bi bi-calendar-event"></i> <?= date('d F Y', strtotime($news['created_at'])) ?></small>
                                    </td>
                                    <td><span class="badge text-dark" style="background-color: var(--accent-gold);"><?= htmlspecialchars($news['category'] ?? 'Pengumuman') ?></span></td>
                                    <td><?= htmlspecialchars($news['username']) ?></td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="admin_news_form.php?id=<?= $news['id'] ?>" class="btn btn-warning text-white"><i class="bi bi-pencil-fill"></i></a>
                                            <a href="admin_news.php?delete_id=<?= $news['id'] ?>" class="btn btn-danger" onclick="return confirm('Hapus berita ini?');"><i class="bi bi-trash-fill"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center p-4 text-muted">Data berita tidak ditemukan.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($total_pages > 1): ?>
                <div class="d-flex justify-content-center align-items-center mt-4 google-pagination">
                    <span class="text-primary fw-bold google-logo-text me-2">G</span>
                    <nav>
                        <ul class="pagination m-0 align-items-center">
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <span class="fw-bold google-logo-text" style="color: <?= ($i == $page) ? '#EA4335' : '#FBBC05' ?>;">o</span>
                            <?php endfor; ?>
                            <span class="text-success fw-bold google-logo-text me-3">gle</span>

                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                    <a class="page-link" href="admin_news.php?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <li class="page-item ms-2">
                                    <a class="page-link text-primary fw-bold" href="admin_news.php?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>">Berikutnya &gt;</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>