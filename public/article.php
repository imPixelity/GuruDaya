<?php
require_once '../config/config.php';
require_once '../handlers/article_handlers.php';
require_once '../handlers/comment_handlers.php';

$article_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get article
$article_result = getArticleById($article_id);
if ($article_result->num_rows === 0) {
    redirect('index.php');
}
$article = $article_result->fetch_assoc();

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isLoggedIn()) {
    if (isset($_POST['add_comment'])) {
        $message = sanitize($_POST['message']);
        if (!empty($message)) {
            addComment($article_id, $_SESSION['user_id'], $message);
            redirect("article.php?id=$article_id");
        }
    }
}

// Handle comment deletion
if (isset($_GET['delete_comment']) && (isLoggedIn() || isAdmin())) {
    $comment_id = (int)$_GET['delete_comment'];

    if (isAdmin()) {
        deleteComment($comment_id);
    } else {
        // Check if comment belongs to user
        global $conn;
        $check = $conn->prepare("SELECT user_id FROM comments WHERE id = ?");
        $check->bind_param("i", $comment_id);
        $check->execute();
        $result = $check->get_result();
        $comment = $result->fetch_assoc();

        if ($comment && $comment['user_id'] == $_SESSION['user_id']) {
            deleteComment($comment_id);
        }
    }
    redirect("article.php?id=$article_id");
}

// Get comments
$comments_result = getCommentsByArticle($article_id);
?>

<?php include '../includes/header.php'; ?>

<?php include '../includes/navbar.php'; ?>

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
                Anda harus <a href="../auth/login.php">login</a> untuk menambahkan komentar.
            </div>
        <?php endif; ?>

        <?php while ($comment = $comments_result->fetch_assoc()): ?>
            <div class="comment">
                <div class="comment-header">
                    <span class="comment-author">👤 <?= htmlspecialchars($comment['username']) ?></span>
                    <?php if (isAdmin() || (isLoggedIn() && $_SESSION['user_id'] == $comment['user_id'])): ?>
                        <button onclick="if(confirm('Hapus komentar ini?')) location.href='article.php?id=<?= $article_id ?>&delete_comment=<?= $comment['id'] ?>'" class="btn btn-delete">Hapus</button>
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

<?php include '../includes/footer.php'; ?>