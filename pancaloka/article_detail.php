<?php
session_start();
require 'config/config.php';

if (!isset($_GET['id'])) {
    header("Location: article.php");
    exit();
}
$article_id = $_GET['id'];

// 1. Ambil Data Artikel
$stmt = $pdo->prepare("SELECT a.*, u.username FROM articles a JOIN users u ON a.user_id = u.id WHERE a.id = ?");
$stmt->execute([$article_id]);
$article = $stmt->fetch();
if (!$article) { die("Artikel tidak ditemukan."); }

// 2. Proses Fitur Like (Toggle Like/Unlike)
if (isset($_POST['toggle_like']) && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    // Cek apakah sudah like
    $cek_like = $pdo->prepare("SELECT id FROM article_likes WHERE article_id = ? AND user_id = ?");
    $cek_like->execute([$article_id, $user_id]);
    
    if ($cek_like->fetch()) {
        // Jika sudah like, maka Hapus (Unlike)
        $pdo->prepare("DELETE FROM article_likes WHERE article_id = ? AND user_id = ?")->execute([$article_id, $user_id]);
    } else {
        // Jika belum, maka Tambah Like
        $pdo->prepare("INSERT INTO article_likes (article_id, user_id) VALUES (?, ?)")->execute([$article_id, $user_id]);
    }
    // Refresh agar perubahan tombol langsung terlihat
    header("Location: article_detail.php?id=" . $article_id . "#like-section");
    exit();
}

// 3. Proses Tambah Komentar / Balasan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment_content']) && isset($_SESSION['user_id'])) {
    $parent_id = !empty($_POST['parent_id']) ? $_POST['parent_id'] : null;
    $content = trim($_POST['comment_content']);
    
    $stmt = $pdo->prepare("INSERT INTO article_comments (article_id, user_id, parent_id, content) VALUES (?, ?, ?, ?)");
    $stmt->execute([$article_id, $_SESSION['user_id'], $parent_id, $content]);
    header("Location: article_detail.php?id=" . $article_id . "#comment-section");
    exit();
}

// Cek Status Like User Saat Ini (Untuk Tampilan Tombol)
$user_has_liked = false;
if (isset($_SESSION['user_id'])) {
    $stmt_like = $pdo->prepare("SELECT id FROM article_likes WHERE article_id = ? AND user_id = ?");
    $stmt_like->execute([$article_id, $_SESSION['user_id']]);
    $user_has_liked = $stmt_like->fetch() ? true : false;
}

// Hitung Total Like
$stmt_total_like = $pdo->prepare("SELECT COUNT(*) FROM article_likes WHERE article_id = ?");
$stmt_total_like->execute([$article_id]);
$total_likes = $stmt_total_like->fetchColumn();

// Ambil Semua Komentar (Induk & Balasan)
$stmt_c = $pdo->prepare("SELECT ac.*, u.username FROM article_comments ac JOIN users u ON ac.user_id = u.id WHERE article_id = ? ORDER BY ac.created_at ASC");
$stmt_c->execute([$article_id]);
$all_comments = $stmt_c->fetchAll();

// Pisahkan Komentar Induk dan Balasan
$comments_induk = [];
$comments_balasan = [];
foreach ($all_comments as $c) {
    if ($c['parent_id'] == null) {
        $comments_induk[] = $c;
    } else {
        $comments_balasan[$c['parent_id']][] = $c;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($article['title']) ?> - Pancaloka</title>
    <?php include 'includes/navbar.php'; ?>
    <style>
        :root { --primary-green: #1B4938; --accent-terra: #E07A5F; --bg-paper: #FAF6F0; --text-main: #333333; }
        body { background-color: var(--bg-paper) !important; color: var(--text-main); }
        .hero-img { width: 100%; height: 400px; object-fit: cover; border-radius: 16px; margin-bottom: 30px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .content-box { background-color: #ffffff; border-radius: 16px; padding: 40px; box-shadow: 0 4px 15px rgba(27, 73, 56, 0.04); }
        
        /* Tombol Like Dinamis */
        .btn-like { border: 2px solid var(--accent-terra); color: var(--accent-terra); background: transparent; font-weight: bold; border-radius: 30px; padding: 8px 20px; transition: 0.3s; }
        .btn-like:hover { background-color: #fdf0ee; }
        .btn-liked { background-color: var(--accent-terra); color: white; border: 2px solid var(--accent-terra); font-weight: bold; border-radius: 30px; padding: 8px 20px; }
        .btn-liked:hover { background-color: #c95e46; color: white; border-color: #c95e46; }
        
        /* Komentar Bersarang */
        .comment-thread { margin-bottom: 20px; }
        .comment-main { background-color: var(--bg-paper); border-left: 4px solid var(--primary-green); padding: 15px; border-radius: 8px; }
        .comment-reply { background-color: #ffffff; border-left: 2px solid var(--accent-terra); padding: 10px 15px; margin-left: 40px; margin-top: 10px; border-radius: 8px; font-size: 0.9rem; }
        .reply-form { display: none; margin-left: 40px; margin-top: 10px; }
    </style>
</head>
<body>

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                
                <a href="article.php" class="text-decoration-none mb-3 d-inline-block" style="color: var(--primary-green);">
                    <i class="bi bi-arrow-left"></i> Kembali ke Daftar Artikel
                </a>

                <div class="content-box">
                    <span class="badge bg-warning text-dark mb-3"><?= htmlspecialchars($article['category']) ?></span>
                    <h1 class="fw-bold" style="color: var(--primary-green);"><?= htmlspecialchars($article['title']) ?></h1>
                    
                    <div class="text-muted mb-4 d-flex align-items-center gap-3">
                        <span><i class="bi bi-person-circle"></i> Oleh: <b><?= htmlspecialchars($article['username']) ?></b></span>
                        <span><i class="bi bi-calendar-event"></i> <?= date('d F Y', strtotime($article['created_at'])) ?></span>
                    </div>

                    <?php if (!empty($article['image_url'])): ?>
                        <img src="<?= htmlspecialchars($article['image_url']) ?>" class="hero-img" alt="Sampul Artikel">
                    <?php endif; ?>

                    <div style="font-size: 1.1rem; line-height: 1.8; text-align: justify; white-space: pre-line;">
                        <?= htmlspecialchars($article['content']) ?>
                    </div>

                    <hr class="my-5">

                    <div id="like-section" class="d-flex align-items-center justify-content-between mb-5">
                        <h5 class="mb-0 fw-bold">Bagaimana menurut Anda artikel ini?</h5>
                        <form method="POST" action="#like-section">
                            <input type="hidden" name="toggle_like" value="1">
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <?php if ($user_has_liked): ?>
                                    <button type="submit" class="btn btn-liked shadow-sm">
                                        <i class="bi bi-heart-fill"></i> Disukai (<?= $total_likes ?>)
                                    </button>
                                <?php else: ?>
                                    <button type="submit" class="btn btn-like">
                                        <i class="bi bi-heart"></i> Suka Artikel (<?= $total_likes ?>)
                                    </button>
                                <?php endif; ?>
                            <?php else: ?>
                                <button type="button" class="btn btn-like" onclick="alert('Silakan Login terlebih dahulu untuk memberikan Like.');">
                                    <i class="bi bi-heart"></i> Suka (<?= $total_likes ?>)
                                </button>
                            <?php endif; ?>
                        </form>
                    </div>

                    <div id="comment-section">
                        <h4 class="fw-bold mb-4" style="color: var(--primary-green);"><i class="bi bi-chat-right-text"></i> Ruang Diskusi (<?= count($all_comments) ?>)</h4>
                        
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <form method="POST" class="mb-5">
                                <div class="input-group">
                                    <textarea name="comment_content" class="form-control" rows="2" placeholder="Tulis pandangan Anda tentang kajian ini..." required></textarea>
                                    <button type="submit" class="btn text-white px-4" style="background-color: var(--primary-green);">Kirim Opini</button>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="alert alert-warning mb-5">Silakan <a href="login.php" class="fw-bold">Login</a> untuk ikut berdiskusi.</div>
                        <?php endif; ?>

                        <?php foreach ($comments_induk as $induk): ?>
                            <div class="comment-thread">
                                <div class="comment-main">
                                    <div class="d-flex justify-content-between mb-1">
                                        <strong class="text-success"><?= htmlspecialchars($induk['username']) ?></strong>
                                        <small class="text-muted"><?= date('d/m/Y H:i', strtotime($induk['created_at'])) ?></small>
                                    </div>
                                    <p class="mb-1"><?= htmlspecialchars($induk['content']) ?></p>
                                    
                                    <?php if(isset($_SESSION['user_id'])): ?>
                                        <button class="btn btn-sm btn-link text-decoration-none p-0" style="color: var(--accent-terra);" onclick="toggleReplyForm(<?= $induk['id'] ?>)">
                                            <i class="bi bi-reply-fill"></i> Balas
                                        </button>
                                    <?php endif; ?>
                                </div>

                                <div id="reply-form-<?= $induk['id'] ?>" class="reply-form">
                                    <form method="POST">
                                        <input type="hidden" name="parent_id" value="<?= $induk['id'] ?>">
                                        <div class="input-group input-group-sm">
                                            <input type="text" name="comment_content" class="form-control" placeholder="Tulis balasan untuk <?= htmlspecialchars($induk['username']) ?>..." required>
                                            <button type="submit" class="btn btn-secondary">Balas</button>
                                        </div>
                                    </form>
                                </div>

                                <?php if (isset($comments_balasan[$induk['id']])): ?>
                                    <?php foreach ($comments_balasan[$induk['id']] as $balasan): ?>
                                        <div class="comment-reply">
                                            <div class="d-flex justify-content-between mb-1">
                                                <strong style="color: var(--accent-terra);"><i class="bi bi-arrow-return-right"></i> <?= htmlspecialchars($balasan['username']) ?></strong>
                                                <small class="text-muted" style="font-size: 0.75rem;"><?= date('d/m H:i', strtotime($balasan['created_at'])) ?></small>
                                            </div>
                                            <p class="mb-0 text-dark"><?= htmlspecialchars($balasan['content']) ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleReplyForm(commentId) {
            var form = document.getElementById('reply-form-' + commentId);
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }
    </script>
    <?php include 'includes/footer.php'; ?>
</body>
</html>