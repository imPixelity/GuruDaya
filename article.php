<?php
require_once 'config.php';

$article_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil artikel
$stmt = $conn->prepare("SELECT a.*, u.username as author FROM articles a JOIN users u ON a.author_id = u.id WHERE a.id = ?");
$stmt->bind_param("i", $article_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    redirect('index.php');
}

$article = $result->fetch_assoc();

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isLoggedIn()) {
    if (isset($_POST['add_comment'])) {
        $message = sanitize($_POST['message']);

        if (!empty($message)) {
            $stmt = $conn->prepare("INSERT INTO comments (article_id, user_id, message) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $article_id, $_SESSION['user_id'], $message);
            $stmt->execute();
            redirect("article.php?id=$article_id");
        }
    }
}

// Handle comment deletion
if (isset($_GET['delete_comment']) && (isLoggedIn() || isAdmin())) {
    $comment_id = (int)$_GET['delete_comment'];

    if (isAdmin()) {
        $delete_stmt = $conn->prepare("DELETE FROM comments WHERE id = ?");
        $delete_stmt->bind_param("i", $comment_id);
    } else {
        $delete_stmt = $conn->prepare("DELETE FROM comments WHERE id = ? AND user_id = ?");
        $delete_stmt->bind_param("ii", $comment_id, $_SESSION['user_id']);
    }

    $delete_stmt->execute();
    redirect("article.php?id=$article_id");
}

// Ambil komentar
$comments_query = "SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE c.article_id = ? ORDER BY c.id DESC";
$comments_stmt = $conn->prepare($comments_query);
$comments_stmt->bind_param("i", $article_id);
$comments_stmt->execute();
$comments_result = $comments_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['title']) ?> - GuruDaya</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <span class="logo">🌱</span>
                <h1>GuruDaya</h1>
            </div>
            <div class="nav-links">
                <a href="index.php">Beranda</a>
                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                        <a href="admin.php">Dashboard Admin</a>
                    <?php endif; ?>
                    <span>Halo, <?= htmlspecialchars($_SESSION['username']) ?></span>
                    <a href="logout.php" class="btn-logout">Keluar</a>
                <?php else: ?>
                    <a href="login.php" class="btn-login">Masuk</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="container">
        <div class="article-detail">
            <h1><?= htmlspecialchars($article['title']) ?></h1>
            <div class="meta">
                📝 Ditulis oleh: <strong><?= htmlspecialchars($article['author']) ?></strong>
            </div>
            <div class="content"><?= htmlspecialchars($article['content']) ?></div>
        </div>

        <div class="comments-section">
            <h3>💬 Komentar (<?= $comments_result->num_rows ?>)</h3>

            <?php if (isLoggedIn()): ?>
                <form method="POST" class="form-container" style="margin-bottom: 30px;">
                    <div class="form-group">
                        <label>Tambah Komentar</label>
                        <textarea name="message" required placeholder="Tulis komentar Anda..."></textarea>
                    </div>
                    <button type="submit" name="add_comment" class="btn-submit">Kirim Komentar</button>
                </form>
            <?php else: ?>
                <div class="alert alert-error">
                    Anda harus <a href="login.php">login</a> untuk menambahkan komentar.
                </div>
            <?php endif; ?>

            <?php while ($comment = $comments_result->fetch_assoc()): ?>
                <div class="comment">
                    <div class="comment-header">
                        <span class="comment-author">👤 <?= htmlspecialchars($comment['username']) ?></span>
                        <?php if (isAdmin() || (isLoggedIn() && $_SESSION['user_id'] == $comment['user_id'])): ?>
                            <button onclick="if(confirm('Hapus komentar ini?')) location.href='article.php?id=<?= $article_id ?>&delete_comment=<?= $comment['id'] ?>'" class="btn-delete">Hapus</button>
                        <?php endif; ?>
                    </div>
                    <div class="comment-message"><?= htmlspecialchars(trim($comment['message'])) ?></div>
                </div>
            <?php endwhile; ?>

            <?php if ($comments_result->num_rows === 0): ?>
                <p style="text-align: center; color: #7f8c8d;">Belum ada komentar. Jadilah yang pertama berkomentar!</p>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 GuruDaya - Edukasi Energi Terbarukan Indonesia</p>
        </div>
    </footer>
</body>

</html>