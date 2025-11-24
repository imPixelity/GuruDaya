<?php
require_once 'config.php';

if (!isAdmin()) {
    redirect('index.php');
}

$article_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil artikel
$stmt = $conn->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->bind_param("i", $article_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    redirect('admin.php');
}

$article = $result->fetch_assoc();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title']);
    $content = sanitize($_POST['content']);

    if (empty($title) || empty($content)) {
        $error = 'Judul dan konten harus diisi!';
    } else {
        $stmt = $conn->prepare("UPDATE articles SET title = ?, content = ? WHERE id = ?");
        $stmt->bind_param("ssi", $title, $content, $article_id);

        if ($stmt->execute()) {
            $success = 'Artikel berhasil diperbarui!';
            $article['title'] = $title;
            $article['content'] = $content;
        } else {
            $error = 'Gagal memperbarui artikel!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Artikel - GuruDaya</title>
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
                <a href="admin.php">Dashboard Admin</a>
                <a href="logout.php" class="btn-logout">Keluar</a>
            </div>
        </div>
    </nav>

    <div class="form-container" style="max-width: 800px;">
        <h2>Edit Artikel</h2>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= $error ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <?= $success ?>
                <a href="article.php?id=<?= $article_id ?>">Lihat artikel</a>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Judul Artikel</label>
                <input type="text" name="title" value="<?= htmlspecialchars($article['title']) ?>" required>
            </div>

            <div class="form-group">
                <label>Konten Artikel</label>
                <textarea name="content" required style="min-height: 300px;"><?= htmlspecialchars($article['content']) ?></textarea>
            </div>

            <button type="submit" class="btn-submit">Perbarui Artikel</button>
        </form>

        <div class="text-center">
            <a href="admin.php">← Kembali ke Dashboard</a>
        </div>
    </div>
</body>

</html>