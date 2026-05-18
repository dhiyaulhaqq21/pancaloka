<?php
session_start();
require 'config/config.php';

// GEMBOK KEAMANAN: Tendang jika bukan admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$current_admin_id = $_SESSION['user_id'];
// Menangkap nilai pencarian dari URL
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// 1. PROSES UPDATE ROLE (TOGGLE USER <-> ADMIN)
if (isset($_GET['toggle_id']) && isset($_GET['current_role'])) {
    $target_id = $_GET['toggle_id'];
    $current_role = $_GET['current_role'];
    
    if ($target_id == $current_admin_id) {
        header("Location: admin_users.php?status=self_lock&search=" . urlencode($search));
        exit();
    }
    
    $new_role = ($current_role === 'admin') ? 'user' : 'admin';
    
    $stmt_role = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt_role->execute([$new_role, $target_id]);
    
    header("Location: admin_users.php?status=role_updated&search=" . urlencode($search));
    exit();
}

// 2. PROSES HAPUS ANGGOTA (DELETE)
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    if ($delete_id == $current_admin_id) {
        header("Location: admin_users.php?status=self_delete&search=" . urlencode($search));
        exit();
    }
    
    $stmt_del = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt_del->execute([$delete_id]);
    
    header("Location: admin_users.php?status=deleted&search=" . urlencode($search));
    exit();
}

// 3. AMBIL DATA ANGGOTA BERDASARKAN PENCARIAN (READ)
// Query dikondisikan secara dinamis menggunakan LIKE untuk username atau email
$query = "SELECT id, username, email, role, created_at FROM users WHERE 1=1";
$params = [];

if (!empty($search)) {
    $query .= " AND (username LIKE ? OR email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$query .= " ORDER BY role ASC, created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$users_list = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna - Pancaloka Admin</title>
    <?php include 'includes/navbar.php'; ?>
    
    <style>
        :root {
          --primary-green: #1B4938;
          --accent-terra: #E07A5F;
          --accent-gold: #F2CC8F;
        }
        body { background-color: #f4f6f9 !important; }
        .table-container {
            background-color: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        }
        .table th {
            background-color: var(--primary-green) !important;
            color: white !important;
            font-weight: 600;
        }
        .badge-admin { background-color: var(--accent-terra); color: white; font-weight: bold; }
        .badge-user { background-color: var(--primary-green); color: white; }
        .search-box {
            border-radius: 8px;
            border: 1px solid #ced4da;
        }
        .search-box:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.25rem rgba(27, 73, 56, 0.25);
        }
    </style>
</head>
<body>

    <div class="container my-5">
        
        <div class="row align-items-center mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
                <a href="admin_dashboard.php" class="text-decoration-none small text-success fw-bold">
                    <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                </a>
                <h3 class="fw-bold mt-2" style="color: var(--primary-green);">Manajemen Data Anggota</h3>
            </div>
            
            <div class="col-md-6">
                <form method="GET" action="admin_users.php" class="d-flex gap-2 justify-content-md-end">
                    <div class="input-group shadow-sm" style="max-width: 350px;">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control search-box border-start-0" placeholder="Cari nama atau email..." value="<?= htmlspecialchars($search) ?>">
                    </div>
                    <button type="submit" class="btn text-white px-4 shadow-sm" style="background-color: var(--primary-green); border-radius: 8px;">Cari</button>
                    <?php if (!empty($search)): ?>
                        <a href="admin_users.php" class="btn btn-light border shadow-sm" style="border-radius: 8px;" title="Reset Pencarian">Reset</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <?php if (isset($_GET['status'])): ?>
            <?php if ($_GET['status'] == 'role_updated'): ?>
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> Tingkat hak akses (role) anggota berhasil diperbarui.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php elseif ($_GET['status'] == 'deleted'): ?>
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="bi bi-person-x-fill me-2"></i> Akun pengguna telah dihapus secara permanen dari pangkalan data.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php elseif ($_GET['status'] == 'self_lock' || $_GET['status'] == 'self_delete'): ?>
                <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Sistem Memblokir Aksi:</strong> Anda tidak diperbolehkan menghapus atau menurunkan pangkat akun Anda sendiri yang sedang aktif digunakan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="25%">Nama Anggota (Username)</th>
                            <th width="30%">Alamat Email</th>
                            <th width="15%">Hak Akses (Role)</th>
                            <th width="15%">Tanggal Bergabung</th>
                            <th width="10%" class="text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($users_list) > 0): ?>
                            <?php $no = 1; foreach ($users_list as $row_user): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-person-circle fs-4 text-secondary"></i>
                                            <span class="fw-bold text-dark">
                                                <?= htmlspecialchars($row_user['username']) ?>
                                                <?= ($row_user['id'] == $current_admin_id) ? '<small class="text-muted">(Anda)</small>' : '' ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($row_user['email'] ?? '-') ?></td>
                                    <td>
                                        <span class="badge rounded-pill px-3 py-1 <?= ($row_user['role'] === 'admin') ? 'badge-admin' : 'badge-user' ?>">
                                            <?= strtoupper($row_user['role']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-secondary">
                                            <?= date('d M Y', strtotime($row_user['created_at'])) ?>
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($row_user['id'] != $current_admin_id): ?>
                                            <div class="btn-group btn-group-sm shadow-sm" style="border-radius: 8px; overflow: hidden;">
                                                <a href="admin_users.php?toggle_id=<?= $row_user['id'] ?>&current_role=<?= $row_user['role'] ?>&search=<?= urlencode($search) ?>" 
                                                   class="btn <?= ($row_user['role'] === 'admin') ? 'btn-secondary' : 'btn-success' ?>" 
                                                   title="<?= ($row_user['role'] === 'admin') ? 'Turunkan ke User biasa' : 'Naikkan Pangkat jadi Admin' ?>"
                                                   onclick="return confirm('Apakah Anda yakin ingin mengubah hak akses akun <?= htmlspecialchars($row_user['username']) ?>?');">
                                                    <i class="bi bi-arrow-down-up"></i>
                                                </a>
                                                <a href="admin_users.php?delete_id=<?= $row_user['id'] ?>&search=<?= urlencode($search) ?>" 
                                                   class="btn btn-danger" 
                                                   title="Hapus Pengguna Permanen"
                                                   onclick="return confirm('Peringatan Krusial! Menghapus pengguna <?= htmlspecialchars($row_user['username']) ?> akan menghapus seluruh data komentar dan suka miliknya di sistem. Lanjutkan?');">
                                                    <i class="bi bi-person-dash-fill"></i>
                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted small fst-italic"><i class="bi bi-lock-fill"></i> Terkunci</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center p-4 text-muted">
                                    <i class="bi bi-person-x display-6 d-block mb-2"></i>
                                    Anggota dengan nama atau email "<strong><?= htmlspecialchars($search) ?></strong>" tidak ditemukan.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    
    <div class="mb-5"></div>

</body>
</html>