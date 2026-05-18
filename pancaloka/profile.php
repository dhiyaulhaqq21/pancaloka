<?php
session_start();
require 'config/config.php';

// GEMBOK KEAMANAN: Tendang jika belum login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// PROSES SIMPAN PERUBAHAN PROFIL (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $full_name = trim($_POST['full_name']);
    $email     = trim($_POST['email']);
    
    if (empty($email)) {
        $error = "Alamat email wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format alamat email tidak valid.";
    } else {
        // 1. Cek duplikasi email
        $stmt_cek = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt_cek->execute([$email, $user_id]);
        
        if ($stmt_cek->fetch()) {
            $error = "Alamat email tersebut sudah digunakan oleh akun lain.";
        } else {
            // 2. Ambil data user lama untuk mengecek foto lama
            $stmt_old = $pdo->prepare("SELECT profile_picture FROM users WHERE id = ?");
            $stmt_old->execute([$user_id]);
            $old_pic = $stmt_old->fetchColumn() ?: 'default-avatar.png';
            $new_pic_name = $old_pic; // Default memakai foto lama

            // 3. Logika Unggah Foto Profil (Jika ada berkas yang dipilih)
            if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
                $file_name = $_FILES['profile_pic']['name'];
                $file_size = $_FILES['profile_pic']['size'];
                $file_tmp  = $_FILES['profile_pic']['tmp_name'];
                
                // Ambil ekstensi file
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $allowed_extensions = ['jpg', 'jpeg', 'png'];
                
                if (!in_array($file_ext, $allowed_extensions)) {
                    $error = "Format foto harus JPG, JPEG, atau PNG.";
                } elseif ($file_size > 2 * 1024 * 1024) { // Batasan 2 Megabytes
                    $error = "Ukuran file foto terlalu besar! Maksimal adalah 2MB.";
                } else {
                    // Acak nama file baru agar tidak bentrok di server
                    $new_pic_name = "user_" . $user_id . "_" . time() . "." . $file_ext;
                    $upload_dir = "uploads/profile_pics/";
                    
                    // Buat folder otomatis jika belum ada
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }
                    
                    // Pindahkan file dari temporary ke folder upload
                    if (move_uploaded_file($file_tmp, $upload_dir . $new_pic_name)) {
                        // Hapus foto lama dari server jika bukan foto default
                        if ($old_pic !== 'default-avatar.png' && file_exists($upload_dir . $old_pic)) {
                            unlink($upload_dir . $old_pic);
                        }
                    } else {
                        $error = "Gagal mengunggah gambar ke server.";
                    }
                }
            }

            // Jika tidak ada error validasi gambar, eksekusi pembaruan ke Database
            if (empty($error)) {
                $stmt_update = $pdo->prepare("UPDATE users SET full_name = ?, email = ?, profile_picture = ? WHERE id = ?");
                if ($stmt_update->execute([$full_name, $email, $new_pic_name, $user_id])) {
                    $success = "Profil Anda berhasil diperbarui.";
                } else {
                    $error = "Terjadi kegagalan sistem database saat menyimpan perubahan.";
                }
            }
        }
    }
}

// AMBIL DATA USER TERBARU UNTUK DITAMPILKAN DI FORM
$stmt_user = $pdo->prepare("SELECT username, full_name, email, role, profile_picture, created_at FROM users WHERE id = ?");
$stmt_user->execute([$user_id]);
$user_data = $stmt_user->fetch();

// Atur lokasi gambar profil (fallback jika file terhapus atau kosong)
$avatar_path = "uploads/profile_pics/" . ($user_data['profile_picture'] ?: 'default-avatar.png');
if (!file_exists($avatar_path) || empty($user_data['profile_picture'])) {
    $avatar_image = "https://cdn-icons-png.flaticon.com/512/149/149071.png"; // Fallback URL avatar default
} else {
    $avatar_image = $avatar_path;
}

// AMBIL DATA RIWAYAT INTERAKSI (LIKE & KOMENTAR)
$stmt_likes = $pdo->prepare("SELECT a.id, a.title, a.category FROM article_likes al 
                             JOIN articles a ON al.article_id = a.id 
                             WHERE al.user_id = ? ORDER BY al.created_at DESC");
$stmt_likes->execute([$user_id]);
$liked_articles = $stmt_likes->fetchAll();

$stmt_comments = $pdo->prepare("SELECT DISTINCT a.id, a.title, a.category FROM article_comments ac 
                                JOIN articles a ON ac.article_id = a.id 
                                WHERE ac.user_id = ? ORDER BY ac.created_at DESC");
$stmt_comments->execute([$user_id]);
$commented_articles = $stmt_comments->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Pancaloka</title>
    <?php include 'includes/navbar.php'; ?>
    <style>
        :root {
          --primary-green: #1B4938; --accent-terra: #E07A5F; --accent-gold: #F2CC8F; --bg-paper: #FAF6F0;
        }
        body { background-color: var(--bg-paper) !important; }
        .profile-card { border: none; border-radius: 16px; background-color: white; box-shadow: 0 4px 15px rgba(27, 73, 56, 0.04); }
        .avatar-container { position: relative; width: 110px; height: 110px; margin: 0 auto 15px auto; }
        .avatar-large { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 3px solid var(--accent-gold); }
        .badge-role { font-size: 0.75rem; padding: 4px 12px; border-radius: 20px; font-weight: bold; }
        .nav-tabs .nav-link { color: #6c757d; font-weight: 600; border: none; }
        .nav-tabs .nav-link.active { color: var(--primary-green) !important; border-bottom: 3px solid var(--primary-green) !important; background: transparent; }
        .history-item { text-decoration: none; color: #333; display: block; padding: 12px 15px; background: var(--bg-paper); border-radius: 8px; margin-bottom: 10px; transition: 0.2s; border-left: 3px solid transparent; }
        .history-item:hover { border-left-color: var(--accent-terra); background: #fdfaf6; color: var(--primary-green); transform: translateX(3px); }
    </style>
</head>
<body>

    <div class="container my-5">
        
        <?php if ($success): ?>
            <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> <?= $success ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            
            <div class="col-lg-5">
                <div class="card profile-card p-4">
                    <div class="text-center">
                        <div class="avatar-container">
                            <img src="<?= $avatar_image ?>" class="avatar-large" alt="Foto Profil">
                        </div>
                        <h4 class="fw-bold mb-1" style="color: var(--primary-green);"><?= htmlspecialchars($user_data['username']) ?></h4>
                        <div class="mb-2">
                            <span class="badge badge-role <?= ($user_data['role'] === 'admin') ? 'bg-danger' : 'bg-success' ?>">
                                <?= strtoupper($user_data['role']) ?> MEMBER
                            </span>
                        </div>
                        <p class="text-muted small mb-3"><i class="bi bi-calendar-check"></i> Anggota Sejak: <?= date('d M Y', strtotime($user_data['created_at'])) ?></p>
                    </div>
                    
                    <hr class="opacity-25 my-3">
                    
                    <form method="POST" action="" enctype="multipart/form-data" class="mt-2">
                        <h6 class="fw-bold text-secondary mb-3"><i class="bi bi-pencil-square"></i> Perbarui Informasi Diri</h6>
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Nama Lengkap</label>
                            <input type="text" name="full_name" class="form-control" 
                                   placeholder="Masukkan nama asli lengkap Anda..." 
                                   value="<?= htmlspecialchars($user_data['full_name'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Alamat Email</label>
                            <input type="email" name="email" class="form-control" 
                                   value="<?= htmlspecialchars($user_data['email'] ?? '') ?>" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted">Ganti Foto Profil (Maks 2MB, JPG/PNG)</label>
                            <input type="file" name="profile_pic" class="form-control" accept=".jpg, .jpeg, .png">
                        </div>

                        <button type="submit" name="update_profile" class="btn text-white fw-bold py-2 w-100 shadow-sm" style="background-color: var(--primary-green); border-radius: 8px;">
                            <i class="bi bi-cloud-arrow-up-fill me-1"></i> Simpan Perubahan Profil
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card profile-card p-4 h-100">
                    <h5 class="fw-bold mb-4" style="color: var(--primary-green);"><i class="bi bi-clock-history"></i> Jejak Aktivitas Literasi</h5>
                    
                    <ul class="nav nav-tabs mb-4" id="profileTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="likes-tab" data-bs-toggle="tab" data-bs-target="#likes-pane" type="button" role="tab">
                                <i class="bi bi-heart-fill text-danger me-1"></i> Disukai (<?= count($liked_articles) ?>)
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="comments-tab" data-bs-toggle="tab" data-bs-target="#comments-pane" type="button" role="tab">
                                <i class="bi bi-chat-text-fill text-success me-1"></i> Dikomentari (<?= count($commented_articles) ?>)
                            </button>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="profileTabContent">
                        <div class="tab-pane fade show active" id="likes-pane" role="tabpanel" aria-labelledby="likes-tab">
                            <?php if (count($liked_articles) > 0): ?>
                                <?php foreach ($liked_articles as $art_like): ?>
                                    <a href="article_detail.php?id=<?= $art_like['id'] ?>" class="history-item">
                                        <span class="badge bg-warning text-dark float-end small"><?= htmlspecialchars($art_like['category']) ?></span>
                                        <h6 class="fw-bold mb-0 text-truncate" style="max-width: 80%;"><i class="bi bi-file-earmark-text text-muted me-1"></i> <?= htmlspecialchars($art_like['title']) ?></h6>
                                    </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center p-4 text-muted small"><i class="bi bi-heart display-6 d-block mb-2 text-opacity-25"></i> Anda belum pernah menyukai artikel mana pun.</div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="tab-pane fade" id="comments-pane" role="tabpanel" aria-labelledby="comments-tab">
                            <?php if (count($commented_articles) > 0): ?>
                                <?php foreach ($commented_articles as $art_com): ?>
                                    <a href="article_detail.php?id=<?= $art_com['id'] ?>" class="history-item">
                                        <span class="badge bg-warning text-dark float-end small"><?= htmlspecialchars($art_com['category']) ?></span>
                                        <h6 class="fw-bold mb-0 text-truncate" style="max-width: 80%;"><i class="bi bi-chat-left-dots text-muted me-1"></i> <?= htmlspecialchars($art_com['title']) ?></h6>
                                    </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center p-4 text-muted small"><i class="bi bi-chat-square d-block display-6 mb-2"></i> Anda belum pernah meninggalkan argumen atau komentar.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="mb-5"></div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>