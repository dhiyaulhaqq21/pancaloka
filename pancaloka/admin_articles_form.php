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

// Inisialisasi variabel input data agar tidak memicu error undefined variable
$title = '';
$category = '';
$image_url = '';
$content = '';
$article_id = '';

// DETEKSI MODE EDIT: Jika ada parameter 'id' di URL, berarti admin mau mengubah data lama
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $is_edit = true;
    $article_id = $_GET['id'];
    
    // Ambil data artikel yang mau diedit dari database
    $stmt_load = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt_load->execute([$article_id]);
    $art_data = $stmt_load->fetch();
    
    if ($art_data) {
        $title     = $art_data['title'];
        $category  = $art_data['category'];
        $image_url = $art_data['image_url'];
        $content   = $art_data['content'];
    } else {
        die("<div class='container mt-5 alert alert-danger'>Artikel tidak ditemukan di database.</div>");
    }
}

// PROSES SUBMIT FORM (CREATE & UPDATE)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title     = trim($_POST['title']);
    $category  = trim($_POST['category']);
    $image_url = trim($_POST['image_url']);
    $content   = trim($_POST['content']);
    $user_id   = $_SESSION['user_id']; // ID admin yang sedang login saat ini

    // Validasi inputan esensial
    if (empty($title) || empty($content)) {
        $error = "Judul artikel dan konten utama tidak boleh kosong.";
    } else {
        if ($is_edit) {
            // MODE UPDATE DATA (EDIT)
            $stmt_save = $pdo->prepare("UPDATE articles SET title = ?, category = ?, image_url = ?, content = ? WHERE id = ?");
            $sukses = $stmt_save->execute([$title, $category, $image_url, $content, $article_id]);
        } else {
            // MODE INSERT DATA (BARU)
            $stmt_save = $pdo->prepare("INSERT INTO articles (user_id, title, category, image_url, content) VALUES (?, ?, ?, ?, ?)");
            $sukses = $stmt_save->execute([$user_id, $title, $category, $image_url, $content]);
        }

        if ($sukses) {
            // Redirect kembali ke tabel utama manajemen dengan indikator sukses
            header("Location: admin_articles.php?status=success");
            exit();
        } else {
            $error = "Terjadi kesalahan sistem, gagal menyimpan artikel.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $is_edit ? 'Edit Artikel' : 'Tulis Artikel Baru' ?> - Pancaloka Admin</title>
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
                
                <a href="admin_articles.php" class="text-decoration-none small text-success fw-bold d-inline-block mb-3">
                    <i class="bi bi-arrow-left"></i> Batal & Kembali ke Daftar
                </a>

                <div class="form-container">
                    <h3 class="fw-bold mb-4" style="color: #1B4938;">
                        <i class="bi <?= $is_edit ? 'bi-pencil-square' : 'bi-journal-plus' ?>"></i> 
                        <?= $is_edit ? 'Modifikasi Artikel Konten' : 'Tulis Materi Kajian Baru' ?>
                    </h3>

                    <?php if ($error): ?>
                        <div class="alert alert-danger p-2 text-center" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i> <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold text-secondary">Judul Artikel</label>
                            <input type="text" class="form-control form-control-lg" id="title" name="title" 
                                   placeholder="Ketikkan judul kajian yang menarik..." 
                                   value="<?= htmlspecialchars($title) ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label fw-bold text-secondary">Kategori Kajian</label>
                                <input type="text" class="form-control" id="category" name="category" 
                                       placeholder="Contoh: Sastra, Opini, Tips & Trik" 
                                       value="<?= htmlspecialchars($category) ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="image_url" class="form-label fw-bold text-secondary">Tautan URL Gambar Sampul</label>
                                <input type="url" class="form-control" id="image_url" name="image_url" 
                                       placeholder="https://images.unsplash.com/... atau kosongkan" 
                                       value="<?= htmlspecialchars($image_url) ?>">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="content" class="form-label fw-bold text-secondary">Isi Konten Artikel</label>
                            <textarea class="form-control" id="content" name="content" rows="12" 
                                      placeholder="Tulis gagasan literasi, ulasan, atau esai penuh Anda di sini..." 
                                      required><?= htmlspecialchars($content) ?></textarea>
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="admin_articles.php" class="btn btn-light px-4 rounded-pill">Batal</a>
                            <button type="submit" class="btn btn-save px-5 rounded-pill shadow-sm">
                                <i class="bi bi-cloud-arrow-up-fill me-1"></i> <?= $is_edit ? 'Simpan Perubahan' : 'Terbitkan Artikel' ?>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

</body>
</html>