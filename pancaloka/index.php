<?php
session_start();
require 'config/config.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pancaloka - Membangun Peradaban Lewat Aksara</title>
    
    <?php include 'includes/navbar.php'; ?>

    <style>
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

        /* Carousel Scaling */
        .carousel-item img {
            height: 500px;
            object-fit: cover;
            filter: brightness(0.6);
        }
        .carousel-caption {
            bottom: 25%;
        }

        .section-title {
            color: var(--primary-green);
            font-weight: 700;
            position: relative;
            padding-bottom: 12px;
            display: inline-block;
        }
        .section-title::after {
            content: '';
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            bottom: 0;
            width: 60px;
            height: 4px;
            background-color: var(--accent-terra);
            border-radius: 2px;
        }

        .feature-box {
            background: #ffffff;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(27, 73, 56, 0.03);
            border-bottom: 4px solid var(--primary-green);
            transition: transform 0.3s;
        }
        .feature-box:hover {
            transform: translateY(-5px);
        }
        .feature-icon {
            font-size: 2.5rem;
            color: var(--accent-terra);
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <div id="heroCarousel" class="carousel slide shadow-sm" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=1200&h=500&fit=crop" class="d-block w-100" alt="Banner 1">
                <div class="carousel-caption">
                    <h1 class="display-4 fw-bold" style="color: var(--accent-gold);">Selamat Datang di Pancaloka</h1>
                    <p class="fs-5">Wadah kolektif penggerak literasi dan ruang merawat nalar kritis.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?q=80&w=1200&h=500&fit=crop" class="d-block w-100" alt="Banner 2">
                <div class="carousel-caption">
                    <h1 class="display-4 fw-bold" style="color: var(--accent-gold);">Membaca, Mengkaji, Menulis</h1>
                    <p class="fs-5">Merajut kembali tradisi diskusi ilmiah dan kultural yang inklusif.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container my-5 py-3">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="section-title mb-3">Mengenal Pancaloka</h2>
                <p class="text-muted mt-3">Pancaloka lahir sebagai respon atas pentingnya ruang-ruang alternatif dialektika di masyarakat. Kami berkomitmen menyebarkan virus literasi demi terciptanya tatanan peradaban masyarakat yang cerdas dan humanis.</p>
            </div>
        </div>

        <div class="row g-4 text-center justify-content-center">
            <div class="col-md-4">
                <div class="feature-box">
                    <div class="feature-icon"><i class="bi bi-book"></i></div>
                    <h5 class="fw-bold" style="color: var(--primary-green);">Ruang Edukasi</h5>
                    <p class="text-secondary small mb-0">Mengembangkan minat baca melalui kurasi artikel literasi, bedah buku rutin, dan penyediaan akses pustaka bergerak.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <div class="feature-icon"><i class="bi bi-chat-quote"></i></div>
                    <h5 class="fw-bold" style="color: var(--primary-green);">Forum Dialektika</h5>
                    <p class="text-secondary small mb-0">Menyediakan ruang diskusi interaktif yang bebas namun terukur bagi para pengkaji, akademisi, dan masyarakat umum.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <div class="feature-icon"><i class="bi bi-bezier2"></i></div>
                    <h5 class="fw-bold" style="color: var(--primary-green);">Jejaring Kolaborasi</h5>
                    <p class="text-secondary small mb-0">Membuka kesempatan kemitraan kreatif dengan pegiat literasi, penerbit, NGO, demi dampak sosial yang masif.</p>
                </div>
            </div>
        </div>
    </div>
    <!-- donasi -->
    <?php include 'includes/donasi.php'; ?>

    <div class="py-5 text-white shadow-sm" style="background-color: var(--primary-green); border-radius: 30px 30px 0 0;">
        <div class="container text-center">
            <div class="row g-4">
                <div class="col-6 col-md-3">
                    <h2 class="display-5 fw-bold" style="color: var(--accent-gold);">500+</h2>
                    <p class="small mb-0 opacity-75">Anggota Aktif</p>
                </div>
                <div class="col-6 col-md-3">
                    <h2 class="display-5 fw-bold" style="color: var(--accent-gold);">50+</h2>
                    <p class="small mb-0 opacity-75">Diskusi Buku</p>
                </div>
                <div class="col-6 col-md-3">
                    <h2 class="display-5 fw-bold" style="color: var(--accent-gold);">1,200+</h2>
                    <p class="small mb-0 opacity-75">Buku Didonasikan</p>
                </div>
                <div class="col-6 col-md-3">
                    <h2 class="display-5 fw-bold" style="color: var(--accent-gold);">10+</h2>
                    <p class="small mb-0 opacity-75">Mitra Strategis</p>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>

</body>
</html>