<?php
session_start();
// Halaman profil statis organisasi, tidak wajib menggunakan query database untuk data pengurusnya
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Komunitas Kami - Pancaloka</title>
    
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

        /* Styling Bagian Tentang Kami */
        .about-box {
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(27, 73, 56, 0.04);
            padding: 40px;
        }

        /* Styling Kartu Struktur Organisasi */
        .org-card {
            border: none;
            border-radius: 12px;
            background-color: #ffffff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            text-align: center;
            border-top: 4px solid var(--primary-green); /* Garis penanda di atas kartu */
        }

        .org-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(27, 73, 56, 0.08);
        }

        /* Kartu Khusus untuk Tingkat Atas (Penasehat / Pendiri) */
        .org-card-top {
            border-top: 4px solid var(--accent-terra);
        }

        /* Kartu Khusus untuk Pimpinan Core (Kordinator / Sek / Ben) */
        .org-card-core {
            border-top: 4px solid var(--accent-gold);
        }

        .org-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: var(--bg-paper);
            color: var(--primary-green);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px auto;
            font-size: 2rem;
            border: 2px solid rgba(27, 73, 56, 0.1);
        }

        .role-title {
            color: var(--primary-green);
            font-weight: 700;
            font-size: 1.05rem;
            margin-bottom: 5px;
        }

        .member-name {
            color: #6c757d;
            font-size: 0.9rem;
            font-style: italic;
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        
        <div class="row mb-5 text-center">
            <div class="col-12">
                <h2 class="section-title mb-4">Tentang Pancaloka</h2>
            </div>
            <div class="col-lg-10 mx-auto mt-2">
                <div class="about-box text-start">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center mb-4 mb-md-0">
                            <img src="assets/img/logo-pancaloka.png" alt="Logo Pancaloka" class="img-fluid" style="max-height: 150px;">
                        </div>
                        <div class="col-md-9">
                            <h4 class="fw-bold mb-3" style="color: var(--primary-green);">Membangun Peradaban Lewat Aksara</h4>
                            <p class="text-secondary" style="line-height: 1.7;">
                                <strong>Pancaloka</strong> adalah sebuah komunitas kolektif yang bergerak di bidang literasi, pengembangan minat baca, dan ruang diskusi inklusif. Kami percaya bahwa buku adalah jendela pembebasan berpikir, dan melalui komunitas ini, kami berikhtiar menyediakan ruang alternatif bagi siapa saja yang ingin bertukar ide, merawat nalar kritis, serta berkontribusi nyata bagi penyebaran virus literasi di masyarakat.
                            </p>
                            <p class="text-secondary mb-0" style="line-height: 1.7;">
                                Bersama para anggota, relawan, dan mitra strategis, Pancaloka aktif menyelenggarakan berbagai kegiatan mulai dari bedah buku harian, program donasi pustaka bergerak, hingga kolaborasi kreatif antar-komunitas.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row mb-4 text-center">
            <div class="col-12">
                <h2 class="section-title mb-3">Struktur Organisasi</h2>
                <p class="text-muted">Nakhoda dan Jajaran Pengurus Organisasi Pancaloka</p>
            </div>
        </div>

        <div class="row g-4 justify-content-center mb-4">
            <div class="col-md-5 col-lg-4">
                <div class="card org-card org-card-top p-4">
                    <div class="org-avatar">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div class="role-title">Dewan Penasehat</div>
                    <div class="member-name">Dr. A. Syatori, M. Si</div>
                </div>
            </div>
            <div class="col-md-5 col-lg-4">
                <div class="card org-card org-card-top p-4">
                    <div class="org-avatar">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <div class="role-title">Dewan Pendiri</div>
                    <div class="member-name">
                            Ahmad Nasrudin • Febriyan Hasby A<br>
                            M. Faiz Ibrahim • M. Rifqi Jallabi<br>
                            M. Rifqi Rizqon R
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 justify-content-center mb-5">
            <div class="col-md-4 col-lg-3">
                <div class="card org-card org-card-core p-4">
                    <div class="org-avatar">
                        <i class="bi bi-person-workspace"></i>
                    </div>
                    <div class="role-title">Kordinator</div>
                    <div class="member-name">Isma Maulana</div>
                </div>
            </div>
            <div class="col-md-4 col-lg-3">
                <div class="card org-card org-card-core p-4">
                    <div class="org-avatar">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <div class="role-title">Sekretaris</div>
                    <div class="member-name">Siti Nur Afifah</div>
                </div>
            </div>
            <div class="col-md-4 col-lg-3">
                <div class="card org-card org-card-core p-4">
                    <div class="org-avatar">
                        <i class="bi bi-cash-coin"></i>
                    </div>
                    <div class="role-title">Bendahara</div>
                    <div class="member-name">Mila Sumiati</div>
                </div>
            </div>
        </div>

        <div class="row mb-3 text-center">
            <div class="col-12">
                <h5 class="fw-bold text-secondary uppercase" style="letter-spacing: 2px;">Divisi Bidang Kerja</h5>
                <hr class="mx-auto" style="width: 100px; border-top: 2px solid var(--accent-gold); opacity: 1;">
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card org-card p-4">
                    <div class="org-avatar">
                        <i class="bi bi-book-half"></i>
                    </div>
                    <div class="role-title">Bid. Pengembangan Kajian & Literasi</div>
                    <div class="member-name">Azmi Dzakwan Muzhaffar<br>
                                            Fina Khoirunnisa<br>
                                            Ahmad Nashif Irfani<br>
                                            Ruswandi<br>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card org-card p-4">
                    <div class="org-avatar">
                        <i class="bi bi-lightbulb"></i>
                    </div>
                    <div class="role-title">Bid. Ekonomi Kreatif</div>
                    <div class="member-name">Diani Ardilia Fitriani<br>
                                            M. Lutfi Fauzan<br>
                                            Idris Sandriawan<br>
                                            M. Syafei Noer Hasan<br>
                                            Ahmad Fathoni<br>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card org-card p-4">
                    <div class="org-avatar">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="role-title">Bid. Kemitraan & Relasi</div>
                    <div class="member-name">Mugy Rahayu<br>
                                            Sella Febyanti<br><br><br><br>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card org-card p-4">
                    <div class="org-avatar">
                        <i class="bi bi-camera-reels"></i>
                    </div>
                    <div class="role-title">Bid. Media & Publikasi</div>
                    <div class="member-name">I Made Fandika<br>
                                            M. Dhiyaul Haque<br>
                                            M. Wildan Hilmi<br>
                                            M. Raka Zaidan Ali<br><br>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="mb-5"></div>
    <!-- donasi -->
    <?php include 'includes/donasi.php'; ?>
    <?php include 'includes/footer.php'; ?>

</body>
</html>