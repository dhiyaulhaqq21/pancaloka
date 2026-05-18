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

// Inisialisasi variabel formulir
$name = '';
$description = '';
$logo_url = '';
$website_url = '#';
$partner_id = '';

// DETEKSI MODE EDIT
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $is_edit = true;
    $partner_id = $_GET['id'];
    
    $stmt_load = $pdo->prepare("SELECT * FROM partnerships WHERE id = ?");
    $stmt_load->execute([$partner_id]);
    $p_data = $stmt_load->fetch();
    
    if ($p_data) {
        $name        = $p_data['name'];
        $description = $p_data['description'];
        $logo_url    = $p_data['logo_url'];
        $website_url = $p_data['website_url'];
    } else {
        die("<div class='container mt-5 alert alert-danger'>Data mitra tidak ditemukan.</div>");
    }
}

// PROSES MENYIMPAN DATA (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name        = trim($_POST['name']);
    $description = trim($_POST['description']);
    $logo_url    = trim($_POST['logo_url']);
    $website_url = trim($_POST['website_url']);

    if (empty($name) || empty($description)) {
        $error = "Nama instansi/mitra dan deskripsi profil kerja sama wajib diisi.";
    } else {
        if ($is_edit) {
            // EKSEKUSI EDIT (UPDATE)
            $stmt_save = $pdo->prepare("UPDATE partnerships SET name = ?, description = ?, logo_url = ?, website_url = ? WHERE id = ?");
            $sukses = $stmt_save->execute([$name, $description, $logo_url, $website_url, $partner_id]);
        } else {
            // EKSEKUSI TAMBAH (INSERT)
            $stmt_save = $pdo->prepare("INSERT INTO partnerships (name, description, logo_url, website_url) VALUES (?, ?, ?, ?)");
            $sukses = $stmt_save->execute([$name, $description, $logo_url, $website_url]);
        }

        if ($sukses) {
            header("Location: admin_partners.php?status=success");
            exit();
        } else {
            $error = "Gagal memproses data kemitraan akibat gangguan internal database.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $is_edit ? 'Edit Profil Mitra' : 'Daftarkan Mitra Baru' ?> - Pancaloka Admin</title>
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
                
                <a href="admin_partners.php" class="text-decoration-none small text-success fw-bold d-inline-block mb-3">
                    <i class="bi bi-arrow-left"></i> Batal & Kembali ke Daftar
                </a>

                <div class="form-container">
                    <h3 class="fw-bold mb-4" style="color: #1B4938;">
                        <i class="bi <?= $is_edit ? 'bi-pencil-fill' : 'bi-patch-check-fill' ?>"></i> 
                        <?= $is_edit ? 'Ubah Profil Hubungan Kerja Sama' : 'Daftarkan Rekan Aliansi Strategis Baru' ?>
                    </h3>

                    <?php if ($error): ?>
                        <div class="alert alert-danger p-2 text-center" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i> <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold text-secondary">Nama Institusi / Lembaga Mitra</label>
                            <input type="text" class="form-control form-control-lg" id="name" name="name" 
                                   placeholder="Misal: Penerbit Gramedia, Perpustakaan Daerah, dll." 
                                   value="<?= htmlspecialchars($name) ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="logo_url" class="form-label fw-bold text-secondary">URL Gambar Logo Lembaga</label>
                                <input type="url" class="form-control" id="logo_url" name="logo_url" 
                                       placeholder="https://images.unsplash.com/... atau kosongkan" 
                                       value="<?= htmlspecialchars($logo_url) ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="website_url" class="form-label fw-bold text-secondary">Situs Web / Media Sosial Resmi</label>
                                <input type="text" class="form-control" id="website_url" name="website_url" 
                                       placeholder="https://penerbitaksara.com atau masukkan #" 
                                       value="<?= htmlspecialchars($website_url) ?>">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold text-secondary">Deskripsi & Peran Bentuk Kerja Sama</label>
                            <textarea class="form-control" id="description" name="description" rows="6" 
                                      placeholder="Jelaskan profil singkat mitra serta kontribusi atau dukungannya terhadap ekosistem literasi Pancaloka..." 
                                      required><?= htmlspecialchars($description) ?></textarea>
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="admin_partners.php" class="btn btn-light px-4 rounded-pill">Batal</a>
                            <button type="submit" class="btn btn-save px-5 rounded-pill shadow-sm">
                                <i class="bi bi-cloud-check-fill me-1"></i> <?= $is_edit ? 'Simpan Perubahan' : 'Daftarkan Mitra' ?>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

</body>
</html>