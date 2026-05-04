<?php
require '../middleware/auth.php';

if($_SESSION['role'] != 'staf'){
    die("Akses ditolak!");
}
?>

<h2>Dashboard Staff</h2>
<p>Selamat datang, <?= $_SESSION['nama']; ?></p>
<a href="../auth/logout.php">Logout</a>