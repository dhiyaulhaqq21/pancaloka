# Pancaloka - Platform Digital Kolektif Literasi & Dialektika Ilmiah

**Pancaloka** adalah sebuah platform sistem informasi berbasis web yang dirancang sebagai ruang alternatif digital untuk mengonsolidasikan gerakan literasi, menyebarkan publikasi esai kritis, serta mengelola tata kelola organisasi secara inklusif. Aplikasi ini dibangun menggunakan arsitektur **PHP Native** murni dengan lapisan abstraksi basis data berbasis **PDO (PHP Data Objects)** demi menjamin performa kueri yang optimal serta ketahanan siber yang solid.

Antarmuka dikembangkan dengan mengadopsi prinsip ergonomi membaca (*Cozy Library Experience*) menggunakan perpaduan warna hijau pinus (`#1B4938`), krem kertas kertas (`#FAF6F0`), dan aksen terakota (`#E07A5F`) untuk meminimalisir kelelahan mata pengguna saat membaca konten teks panjang.

---

## Fitur Utama Sistem

### 1. Otentikasi & Manajemen Akun
* **Dual-Identity Sign-In:** Mengizinkan proses masuk (*log-in*) menggunakan kombinasi data `username` maupun alamat `email` secara hibrida.
* **Kriptografi Kredensial:** Seluruh kata sandi diamanan menggunakan algoritma hashing satu arah BCRYPT (`password_hash`).
* **Bot Mitigation:** Form login diperkuat dengan mekanisme *Session-Based Math CAPTCHA* dinamis untuk menangkal serangan otomatis (*automated brute-force attacks*).
* **Manajemen Profil Mandiri:** Fasilitas bagi anggota untuk mengubah nama lengkap, validasi email anti-duplikasi, serta pengunggahan berkas gambar pasfoto dengan proteksi *server-side validation* (ekstensi JPG/PNG, batas ukuran file maks 2MB, dan *auto-cleanup* file residu lama di penyimpanan server).

### 2. Repositori Publikasi & Diskusi Relasional
* **Rak Artikel & Berita:** Katalog konten esai ilmiah, pengumuman, dan siaran kegiatan organisasi yang dilengkapi kueri pencarian kata kunci judul (*live search*) serta penyaringan kategori dinamis.
* **Stateful Interaction System:** Sistem tombol *Like* interaktif yang status visualnya (*filled/outline state*) sinkron secara *real-time* dengan kondisi database pengguna terkait.
* **Komentar Bersarang (Nested Comments):** Sistem umpan balik forum diskusi berundak yang memanfaatkan konsep relasi mandiri (*self-referencing relationship* via `parent_id`) pada basis data relasional untuk merekam alur dialektika secara runut.

### 3. Struktur Organisasi & Modul Donasi
* **Bagan Pohon CSS Dinamis:** Visualisasi struktur kepengurusan hierarkis yang dibangun murni menggunakan manipulasi *CSS Pseudo-element* (`::before` dan `::after`), menghasilkan layout adaptif yang responsif tanpa ketergantungan aset gambar statis.
* **Saluran Kontribusi Publik:** Integrasi Bootstrap 5 Modal Pop-up yang memuat fitur *auto-copy* nomor rekening bank instan via JavaScript Clipboard API serta penayangan QRIS dinamis untuk mempermudah transaksi donatur.

### 4. Pusat Kendali Admin Panel (CMS Dashboard)
* **Role-Based Access Control (RBAC):** Proteksi hak akses ketat berbasis peran level akun ('admin' dan 'user') yang memblokir celah kerentanan *Unauthenticated Privilege Escalation*.
* **Real-time Aggregation Metrics:** Dasbor khusus menyajikan ringkasan statistik pertumbuhan jumlah data secara aktual menggunakan fungsi agregat SQL `COUNT`.
* **Data Privacy Compliance:** Pelaporan data rekor seluruh anggota tanpa memaparkan string *hash password* demi mematuhi standar privasi keamanan informasi.
* **Google-Style Pagination:** Optimasi visualisasi tabel data besar menggunakan limitasi kueri dan offset parsial (5 baris data per halaman). Indeks halaman bertambah secara dinamis menyerupai Google Search dengan tetap mempertahankan kata kunci kueri pada parameter URL (*state retention*).

---

## Spesifikasi Teknologi (Tech Stack)

* **Language:** PHP 7.4 / 8.x (Native Backend Engine)
* **Database:** MySQL / MariaDB (Lapisan Konektivitas PDO Driver dengan *Prepared Statements*)
* **Frontend Design:** HTML5, CSS3 (Manipulation Custom Styles), Bootstrap v5 (Responsive Framework)
* **Scripting Language:** Vanilla JavaScript (DOM Manipulation & Web API Integrations)
* **Icons:** Bootstrap Icons Suite

---

## Panduan Instalasi Lokal (Local Deployment)

Ikuti langkah-langkah berikut untuk menjalankan projek Pancaloka di lingkungan komputer lokal Anda:

### Prasyarat (Prerequisites)
* Web Server bundle (rekomendasi: **XAMPP** dengan versi PHP minimal 7.4 atau di atasnya).
* Aplikasi Git terinstal.

### Langkah-Langkah:
1.  **Kloning Repositori:**
    Buka terminal atau Git Bash, masuk ke direktori server lokal Anda (`htdocs`), lalu jalankan perintah:
    ```bash
    git clone [https://github.com/username-anda/pancaloka.git](https://github.com/username-anda/pancaloka.git)
    ```
2.  **Konfigurasi Basis Data:**
    * Aktifkan panel kontrol XAMPP (jalankan modul Apache dan MySQL).
    * Buka browser dan akses halaman **`http://localhost/phpmyadmin/`**.
    * Buat sebuah database baru dengan nama: **`kumpulbaca_db`**.
    * Masuk ke tab *Import*, pilih berkas SQL database Anda, lalu klik *Go/Import*.
3.  **Sinkronisasi Koneksi:**
    Pastikan file konfigurasi database Anda di folder proyek **`config/config.php`** sudah sesuai dengan pengaturan server lokal Anda:
    ```php
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'kumpulbaca_db');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    ```
4.  **Uji Coba Sistem:**
    Buka browser Anda dan akses tautan:
    ```text
    http://localhost/pancaloka/
    ```

---

## 📂 Struktur Direktori Utama

```text
pancaloka/
│
├── config/
│   └── config.php          # Koneksi Basis Data Terpusat via PDO Connection
│
├── includes/
│   ├── navbar.php          # Komponen Navigasi Atas Dinamis (Beda Hak Akses)
│   └── footer.php          # Komponen Kaki Halaman Bertema Cozy Library
│
├── uploads/
│   └── profile_pics/       # Repositori Penyimpanan Fisik Berkas Foto Profil
│
├── admin_dashboard.php     # Panel Kendali Statistik & CRUD Utama Admin
├── admin_articles.php      # Manajemen Data Artikel & Google-Style Pagination
├── admin_news.php          # Manajemen Kabar Siaran Berita Komunitas
├── admin_partners.php      # Pengelolaan Data Aliansi Mitra Kerja Sama
├── admin_users.php         # Manajemen Hak Akses, Pencarian Anggota & Proteksi Akun
│
├── article.php             # Katalog Artikel Umum Beserta Form Pencarian & Filter
├── article_detail.php      # Halaman Detail Esai, Sistem Like & Forum Komentar Bersarang
├── news.php                # Katalog Berita Aktual Komunitas
├── news_detail.php         # Halaman Rincian Siaran Maklumat & Tombol Share
├── community.php           # Bagan Hierarki Pengurus & Jendela Pop-Up Donasi
├── profile.php             # Pengaturan Akun, Upload Gambar & Riwayat Interaksi User
│
├── login.php               # Form Masuk Akun & Proteksi CAPTCHA Matematika Sesi
├── register.php            # Form Pendaftaran Kredensial Anggota Baru
└── logout.php              # Destruksi Sesi Global Pengguna
