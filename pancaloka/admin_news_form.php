<?php
session_start();
require 'config/config.php';

// GEMBOK KEAMANAN: Tendang jika bukan admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$error = '';
$is_edit = false;

// Inisialisasi variabel kosong
$title = '';
$category = 'Pengumuman'; // Default value
$image_url = '';
$content = '';
$news_id = '';

// DETEKSI MODE EDIT
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $is_edit = true;
    $news_id = $_GET['id'];
    
    $stmt_load = $pdo->prepare("SELECT * FROM news WHERE id = ?");
    $stmt_load->execute([$news_id]);
    $news_data = $stmt_load->fetch();
    
    if ($news_data) {
        $title     = $news_data['title'];
        $category  = $news_data['category'];
        $image_url = $news_data['image_url'];
        $content   = $news_data['content'];
    } else {
        die("<div class='container mt-5 alert alert-danger'>Berita tidak ditemukan.</div>");
    }
}

// PROSES SIMPAN DATA (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title     = trim($_POST['title']);
    $category  = trim($_POST['category']);
    $image_url = trim($_POST['image_url']);
    $content   = trim($_POST['content']);
    $user_id   = $_SESSION['user_id'];

    if (empty($title) || empty($content)) {
        $error = "Judul berita dan deskripsi isi tidak boleh kosong.";
    } else {
        if ($is_edit) {
            // EKSEKUSI UPDATE
            $stmt_save = $pdo->prepare("UPDATE news SET title = ?, category = ?, image_url = ?, content = ? WHERE id = ?");
            $sukses = $stmt_save->execute([$title, $category, $image_url, $content, $news_id]);
        } else {
            // EKSEKUSI INSERT BARU
            $stmt_save = $pdo->prepare("INSERT INTO news (user_id, title, category, image_url, content) VALUES (?, ?, ?, ?, ?)");
            $sukses = $stmt_save->execute([$user_id, $title, $category, $image_url, $content]);
        }

        if ($sukses) {
            header("Location: admin_news.php?status=success");
            exit();
        } else {
            $error = "Terjadi gangguan sistem, gagal memproses siaran berita.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $is_edit ? 'Edit Kabar Berita' : 'Siarkan Berita Baru' ?> - Pancaloka Admin</title>
    <?php include 'includes/navbar.php'; ?>
    <style>
        body { background-color: #f4f6f9 !important; }
        .form-container {
            background-color: white;
            border-radius: 16px;
            padding: 35px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        }
        .btn-save {
            background-color: #1B4938;
            color: white;
            font-weight: bold;
        }
        .btn-save:hover {
            background-color: #E07A5F;
            color: white;
        }
    </style>
</head>
<body>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                
                <a href="admin_news.php" class="text-decoration-none small text-success fw-bold d-inline-block mb-3">
                    <i class="bi bi-arrow-left"></i> Batal & Kembali ke Daftar
                </a>

                <div class="form-container">
                    <h3 class="fw-bold mb-4" style="color: #1B4938;">
                        <i class="bi <?= $is_edit ? 'bi-pencil-fill' : 'bi-megaphone-fill' ?>"></i> 
                        <?= $is_edit ? 'Ubah Informasi Berita' : 'Siarkan Pengumuman Komunitas Baru' ?>
                    </h3>

                    <?php if ($error): ?>
                        <div class="alert alert-danger p-2 text-center" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i> <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold text-secondary">Judul Pengumuman / Berita</label>
                            <input type="text" class="form-control form-control-lg" id="title" name="title" 
                                   placeholder="Ketikkan judul pengumuman resmi..." 
                                   value="<?= htmlspecialchars($title) ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label fw-bold text-secondary">Kategori Informasi</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="Pengumuman" <?= ($category == 'Pengumuman') ? 'selected' : '' ?>>Pengumuman Resmi</option>
                                    <option value="Kegiatan" <?= ($category == 'Kegiatan') ? 'selected' : '' ?>>Kegiatan / Kopdar</option>
                                    <option value="Donasi" <?= ($category == 'Donasi') ? 'selected' : '' ?>>Aksi Sosial / Donasi</option>
                                    <option value="Kabar Anggota" <?= ($category == 'Kabar Anggota') ? 'selected' : '' ?>>Kabar Anggota</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="image_url" class="form-label fw-bold text-secondary">URL Dokumentasi Gambar Banner</label>
                                <input type="url" class="form-control" id="image_url" name="image_url" 
                                       placeholder="https://images.unsplash.com/... atau kosongkan" 
                                       value="<?= htmlspecialchars($image_url) ?>">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="content" class="form-label fw-bold text-secondary">Isi Pengumuman Lengkap</label>
                            <textarea class="form-control" id="content" name="content" rows="10" 
                                      placeholder="Tulis detail rincian acara, waktu, tempat, atau informasi penting lainnya di sini..." 
                                      required><?= htmlspecialchars($content) ?></textarea>
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="admin_news.php" class="btn btn-light px-4 rounded-pill">Batal</a>
                            <button type="submit" class="btn btn-save px-5 rounded-pill shadow-sm">
                                <i class="bi bi-send-check-fill me-1"></i> <?= $is_edit ? 'Perbarui Siaran' : 'Siarkan Sekarang' ?>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

</body>
</html>