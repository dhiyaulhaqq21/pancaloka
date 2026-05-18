<?php
session_start();
require 'config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if (isset($_GET['delete_id'])) {
    $stmt_del = $pdo->prepare("DELETE FROM partnerships WHERE id = ?");
    $stmt_del->execute([$_GET['delete_id']]);
    header("Location: admin_partners.php?status=deleted");
    exit();
}

// --- LOGIKA PENCARIAN & PAGINATION ---
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

$count_query = "SELECT COUNT(*) FROM partnerships WHERE name LIKE ?";
$stmt_count = $pdo->prepare($count_query);
$stmt_count->execute(["%$search%"]);
$total_data = $stmt_count->fetchColumn();
$total_pages = ceil($total_data / $limit);

$query = "SELECT * FROM partnerships WHERE name LIKE ? 
          ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$stmt = $pdo->prepare($query);
$stmt->execute(["%$search%"]);
$partners = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Mitra - Pancaloka Admin</title>
    <?php include 'includes/navbar.php'; ?>
    <style>
        :root { --primary-green: #1B4938; --accent-terra: #E07A5F; }
        body { background-color: #f4f6f9 !important; }
        .table-container { background-color: white; border-radius: 16px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); }
        .table th { background-color: var(--primary-green) !important; color: white !important; }
        .partner-mini-logo { width: 45px; height: 45px; object-fit: cover; border-radius: 50%; }
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
                <h3 class="fw-bold mt-1" style="color: var(--primary-green);">Manajemen Kemitraan</h3>
            </div>
            <div class="col-md-7 d-flex gap-2 justify-content-md-end align-items-center">
                <form method="GET" class="d-flex gap-2 m-0">
                    <input type="text" name="search" class="form-control shadow-sm" placeholder="Cari nama mitra..." value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="btn text-white" style="background-color: var(--primary-green);">Cari</button>
                </form>
                <a href="admin_partners_form.php" class="btn text-white rounded-pill px-4 shadow-sm" style="background-color: var(--primary-green); font-weight:bold;"><i class="bi bi-patch-check-fill"></i> Daftarkan Baru</a>
            </div>
        </div>

        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="10%">Logo</th>
                            <th width="30%">Nama Institusi</th>
                            <th width="40%">Deskripsi Singkat</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($partners) > 0): ?>
                            <?php $no = $offset + 1; foreach ($partners as $ptnr): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><img src="<?= htmlspecialchars($ptnr['logo_url'] ?? 'https://via.placeholder.com/150') ?>" class="partner-mini-logo"></td>
                                    <td><span class="fw-bold text-dark"><?= htmlspecialchars($ptnr['name']) ?></span></td>
                                    <td><small class="text-secondary"><?= htmlspecialchars(substr($ptnr['description'], 0, 90)) ?>...</small></td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="admin_partners_form.php?id=<?= $ptnr['id'] ?>" class="btn btn-warning text-white"><i class="bi bi-pencil-fill"></i></a>
                                            <a href="admin_partners.php?delete_id=<?= $ptnr['id'] ?>" class="btn btn-danger" onclick="return confirm('Hapus mitra ini?');"><i class="bi bi-trash-fill"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center p-4 text-muted">Data mitra tidak ditemukan.</td></tr>
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
                                    <a class="page-link" href="admin_partners.php?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <li class="page-item ms-2">
                                    <a class="page-link text-primary fw-bold" href="admin_partners.php?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>">Berikutnya &gt;</a>
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