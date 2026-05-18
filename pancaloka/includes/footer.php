<?php
// File: includes/footer.php
?>

<style>
    /* Tambahan Style Khusus Footer Pancaloka */
    .footer-custom {
        background-color: #1B4938;
        color: #FAF6F0;
        border-top: 4px solid #E07A5F; /* Batas atas warna terakota */
    }
    .footer-custom a {
        color: #FAF6F0;
        opacity: 0.8;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .footer-custom a:hover {
        color: #F2CC8F !important; /* Warna emas saat di-hover */
        opacity: 1;
        padding-left: 5px;
    }
    .footer-social-icon {
        font-size: 1.25rem;
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: rgba(250, 246, 240, 0.1);
        color: #FAF6F0 !important;
        transition: 0.3s;
    }
    .footer-social-icon:hover {
        background-color: #E07A5F;
        transform: translateY(-3px);
        padding-left: 0 !important; /* Mencegah bug padding-left teks */
    }
</style>

<footer class="footer-custom pt-5 pb-3 mt-5">
    <div class="container">
        <div class="row g-4">
            
            <div class="col-md-6 col-lg-4">
                <div class="d-flex align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-uppercase" style="color: #F2CC8F; letter-spacing: 1px;">
                        <i class="bi bi-book-half me-2"></i>Pancaloka
                    </h5>
                </div>
                <p class="small opacity-75 lh-lg" style="text-align: justify;">
                    Wadah kolektif yang bergerak di bidang penguatan literasi, penyediaan ruang alternatif diskusi, merawat nalar kritis, serta berkomitmen membangun peradaban lewat aksara yang inklusif.
                </p>
            </div>

            <div class="col-6 col-lg-2 offset-lg-1">
                <h6 class="fw-bold mb-3 text-uppercase small" style="color: #F2CC8F; letter-spacing: 1px;">Navigasi</h6>
                <ul class="list-unstyled small d-flex flex-column gap-2">
                    <li><a href="index.php"><i class="bi bi-chevron-right small me-1"></i> Beranda</a></li>
                    <li><a href="article.php"><i class="bi bi-chevron-right small me-1"></i> Artikel Kajian</a></li>
                    <li><a href="news.php"><i class="bi bi-chevron-right small me-1"></i> Kabar Berita</a></li>
                    <li><a href="partnership.php"><i class="bi bi-chevron-right small me-1"></i> Kemitraan</a></li>
                </ul>
            </div>

            <div class="col-6 col-lg-2">
                <h6 class="fw-bold mb-3 text-uppercase small" style="color: #F2CC8F; letter-spacing: 1px;">Komunitas</h6>
                <ul class="list-unstyled small d-flex flex-column gap-2">
                    <li><a href="community.php"><i class="bi bi-chevron-right small me-1"></i> Struktur</a></li>
                    <li><a href="kolaborasi.php"><i class="bi bi-chevron-right small me-1"></i> Ruang Kolaborasi</a></li>
                    <li><a href="profile.php"><i class="bi bi-chevron-right small me-1"></i> Profil Saya</a></li>
                </ul>
            </div>

            <div class="col-md-6 col-lg-3">
                <h6 class="fw-bold mb-3 text-uppercase small" style="color: #F2CC8F; letter-spacing: 1px;">Hubungi Kami</h6>
                <ul class="list-unstyled small d-flex flex-column gap-2 opacity-75 mb-3">
                    <li class="d-flex align-items-start gap-2">
                        <i class="bi bi-geo-alt-fill text-warning"></i>
                        <span>Lokasi Alamat</span>
                    </li>
                    <li class="d-flex align-items-center gap-2">
                        <i class="bi bi-envelope-fill text-warning"></i>
                        <span>pancaloka@gmail.com</span>
                    </li>
                </ul>
                
                <div class="d-flex gap-2 mt-2">
                    <a href="#" class="footer-social-icon" title="YouTube"><i class="bi bi-whatsapp"></i></a>
                    <a href="#" class="footer-social-icon" title="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="footer-social-icon" title="Twitter/X"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="footer-social-icon" title="YouTube"><i class="bi bi-youtube"></i></a>
                </div>
            </div>

        </div>

        <hr class="my-4 opacity-25" style="border-top: 1px solid #FAF6F0;">

        <div class="row align-items-center small opacity-75">
            <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                &copy; <?php echo date('Y'); ?> <span class="fw-bold" style="color: #F2CC8F;">Pancaloka</span>. Seluruh Hak Cipta Dilindungi.
            </div>
            <div class="col-md-6 text-center text-md-end style-font-secondary" style="font-size: 0.8rem;">
                Membangun Peradaban Lewat Aksara
            </div>
        </div>
    </div>
</footer>