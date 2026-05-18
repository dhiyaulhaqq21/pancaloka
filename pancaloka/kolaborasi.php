<?php
session_start();
// Memanggil koneksi database
require 'config/config.php'; 

// Mengambil semua data kolaborasi dari database
$stmt = $pdo->query("SELECT * FROM collaborations ORDER BY status ASC, created_at DESC");
$collaborations = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruang Kolaborasi - Pancaloka</title>
    
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

        /* Styling Kartu Kolaborasi (Papan Pengumuman) */
        .collab-card {
            border: 1px solid rgba(27, 73, 56, 0.1);
            border-radius: 12px;
            background-color: #ffffff;
            box-shadow: 4px 4px 0px rgba(27, 73, 56, 0.05); /* Shadow padat bergaya retro/kertas */
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            height: 100%;
            position: relative;
        }

        .collab-card:hover {
            transform: translateY(-3px) translateX(-3px);
            box-shadow: 8px 8px 0px rgba(224, 122, 95, 0.2); /* Berubah warna saat dihover */
        }

        .status-badge {
            position: absolute;
            top: -12px;
            right: 20px;
            padding: 6px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .status-terbuka {
            background-color: var(--accent-gold);
            color: var(--primary-green);
        }

        .status-selesai {
            background-color: #e9ecef;
            color: #6c757d;
        }

        .contact-box {
            background-color: var(--bg-paper);
            border-left: 3px solid var(--primary-green);
            padding: 10px 15px;
            border-radius: 4px;
            font-size: 0.85rem;
        }
        
        /* Tombol Buat Kolaborasi Baru */
        .btn-create-collab {
            background-color: var(--primary-green);
            color: white;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            transition: 0.3s;
        }
        .btn-create-collab:hover {
            background-color: var(--accent-terra);
            color: white;
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        <div class="row mb-4 align-items-center">
            <div class="col-md-8">
                <h3 class="section-title">Papan Kolaborasi</h3>
                <p class="text-muted mt-2">Mari ciptakan karya bersama! Temukan proyek komunitas yang sedang berjalan atau ajak anggota lain mewujudkan ide literasimu.</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <button class="btn btn-create-collab px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalKolaborasi">
                        <i class="bi bi-plus-circle me-2"></i> Buat Ajakan Baru
                    </button>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-secondary px-4 py-2">
                        <i class="bi bi-lock-fill me-1"></i> Login untuk Membuat Ajakan
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="row g-4">
            <?php if (count($collaborations) > 0): ?>
                <?php foreach ($collaborations as $collab): ?>
                    <div class="col-md-6">
                        <div class="card collab-card p-4">
                            
                            <?php if ($collab['status'] == 'Terbuka'): ?>
                                <span class="status-badge status-terbuka"><i class="bi bi-door-open-fill"></i> Terbuka</span>
                            <?php else: ?>
                                <span class="status-badge status-selesai"><i class="bi bi-check-circle-fill"></i> Selesai</span>
                            <?php endif; ?>

                            <h4 class="fw-bold" style="color: var(--primary-green); margin-top: 10px;">
                                <?= htmlspecialchars($collab['title']) ?>
                            </h4>
                            <p class="text-muted small mb-3">
                                <i class="bi bi-calendar-plus"></i> Diposting: <?= date('d M Y', strtotime($collab['created_at'])) ?>
                            </p>
                            
                            <p class="card-text mb-4" style="line-height: 1.6;">
                                <?= nl2br(htmlspecialchars($collab['description'])) ?>
                            </p>
                            
                            <div class="mt-auto contact-box">
                                <span class="fw-bold d-block text-secondary mb-1">Hubungi Inisiator:</span>
                                <i class="bi bi-person-lines-fill me-2" style="color: var(--accent-terra);"></i> 
                                <?= htmlspecialchars($collab['contact_person']) ?>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center p-5 bg-white rounded-3">
                    <p class="text-muted">Belum ada ajakan kolaborasi saat ini.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="modal fade" id="modalKolaborasi" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none;">
          <div class="modal-header" style="background-color: var(--primary-green); color: white; border-radius: 16px 16px 0 0;">
            <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i> Ajukan Proyek Kolaborasi</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4">
            <div class="alert alert-warning small">
                Fitur pengiriman form ini sedang dalam tahap pengembangan. Hubungi Admin untuk memposting ajakan Anda secara manual.
            </div>
            <form>
                <div class="mb-3">
                    <label class="form-label fw-bold">Judul Proyek</label>
                    <input type="text" class="form-control" placeholder="Misal: Mencari Relawan Event">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Deskripsi Ajakan</label>
                    <textarea class="form-control" rows="4" placeholder="Ceritakan detail kolaborasi yang Anda inginkan..."></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Kontak Anda</label>
                    <input type="text" class="form-control" placeholder="No. WA atau ID Instagram">
                </div>
                <button type="button" class="btn w-100 btn-create-collab mt-2" data-bs-dismiss="modal">Simpan Draf</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="mb-5"></div>
    <?php include 'includes/footer.php'; ?>

</body>
</html>