<?php
session_start();
// Memanggil koneksi database
require 'config/config.php'; 

// Mengambil semua data mitra dari database
$stmt = $pdo->query("SELECT * FROM partnerships ORDER BY created_at DESC");
$partners = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kemitraan - Pancaloka</title>
    
    <?php include 'includes/navbar.php'; ?>

    <style>
        /* Tema Warna Cozy Library */
        :root {
          --primary-green: #1B4938;
          --accent-terra: #E07A5F;
          --accent-gold: #F2CC8F;
          --bg-paper: #FAF6F0;
          --text-main: #333333;
        }

        body {
            background-color: var(--bg-paper) !important;
            color: var(--text-main);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .section-title {
            color: var(--primary-green);
            font-weight: 700;
            position: relative;
            padding-bottom: 10px;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 60px;
            height: 4px;
            background-color: var(--accent-terra);
            border-radius: 2px;
        }

        /* Styling Kartu Mitra */
        .partner-card {
            border: none;
            border-radius: 16px;
            background-color: #ffffff;
            box-shadow: 0 4px 15px rgba(27, 73, 56, 0.05);
            transition: transform 0.3s ease;
            text-align: center;
            height: 100%;
        }

        .partner-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(27, 73, 56, 0.1);
        }

        .partner-logo {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            margin: 0 auto;
            border: 4px solid var(--bg-paper);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .partner-name {
            color: var(--primary-green);
            font-weight: 700;
            margin-top: 15px;
        }

        /* Styling Bagian Call-to-Action */
        .cta-section {
            background-color: var(--primary-green);
            color: white;
            border-radius: 16px;
            padding: 40px;
            margin-top: 60px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(27, 73, 56, 0.2);
        }

        .btn-cta {
            background-color: var(--accent-gold);
            color: var(--primary-green);
            font-weight: 700;
            border: none;
            padding: 12px 30px;
            border-radius: 30px;
            transition: 0.3s;
        }

        .btn-cta:hover {
            background-color: var(--accent-terra);
            color: white;
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        
        <div class="row mb-5 text-center">
            <div class="col-lg-8 mx-auto">
                <h2 class="fw-bold" style="color: var(--primary-green);">Jejaring Kemitraan Pancaloka</h2>
                <p class="text-muted mt-3">Kami tidak berjalan sendirian. Terima kasih kepada para mitra yang terus mendukung visi kami dalam memajukan literasi dan menciptakan ruang diskusi yang inklusif.</p>
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            <?php if (count($partners) > 0): ?>
                <?php foreach ($partners as $partner): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card partner-card p-4">
                            <?php if (!empty($partner['logo_url'])): ?>
                                <img src="<?= htmlspecialchars($partner['logo_url']) ?>" class="partner-logo mb-3" alt="Logo <?= htmlspecialchars($partner['name']) ?>">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/150?text=Logo" class="partner-logo mb-3" alt="Logo Default">
                            <?php endif; ?>
                            
                            <h5 class="partner-name"><?= htmlspecialchars($partner['name']) ?></h5>
                            <p class="text-muted small mb-4">
                                <?= htmlspecialchars($partner['description']) ?>
                            </p>
                            
                            <div class="mt-auto">
                                <a href="<?= htmlspecialchars($partner['website_url']) ?>" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-4">
                                    <i class="bi bi-link-45deg"></i> Kunjungi Web
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="text-muted">Belum ada data kemitraan yang ditampilkan.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="cta-section">
                    <h3 class="mb-3 fw-bold" style="color: var(--accent-gold);">Tertarik Menjadi Bagian dari Perjalanan Kami?</h3>
                    <p class="mb-4" style="opacity: 0.9;">
                        Apakah Anda penerbit, pengelola perpustakaan, organisasi nonprofit, atau merek yang peduli pada dunia pendidikan dan literasi? Mari berkolaborasi menciptakan ekosistem baca yang lebih luas.
                    </p>
                    <a href="mailto:kerjasama@pancaloka.id" class="btn btn-cta">
                        <i class="bi bi-envelope-paper-heart"></i> Ajukan Proposal Kerja Sama
                    </a>
                </div>
            </div>
        </div>

    </div>

    <div class="mb-5"></div>
    <?php include 'includes/footer.php'; ?>

</body>
</html>