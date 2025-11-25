<?php
require_once 'config.php';

if (!isAdmin()) {
    redirect('index.php');
}

// Handle delete article
if (isset($_GET['delete']) && $_GET['delete']) {
    $article_id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM articles WHERE id = ?");
    $stmt->bind_param("i", $article_id);
    $stmt->execute();
    redirect('admin.php');
}

// Ambil semua artikel
$articles_query = "SELECT a.*, u.username as author FROM articles a JOIN users u ON a.author_id = u.id ORDER BY a.id DESC";
$articles_result = $conn->query($articles_query);

// Hitung statistik
$stats_users = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
$stats_articles = $conn->query("SELECT COUNT(*) as total FROM articles")->fetch_assoc()['total'];
$stats_comments = $conn->query("SELECT COUNT(*) as total FROM comments")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - GuruDaya</title>
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
                <a href="index.php" class="btn-home">Beranda</a>
                <span>Halo, <?= htmlspecialchars($_SESSION['username']) ?></span>
                <a href="logout.php" class="btn-logout">Keluar</a>
            </div>
        </div>
    </nav>

    <main class="container dashboard">
        <div class="dashboard-header">
            <h2>Dashboard Admin</h2>
            <p>Kelola artikel dan konten energi terbarukan</p>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 30px;">
                <div style="background: #3498db; color: white; padding: 20px; border-radius: 8px; text-align: center;">
                    <h3 style="font-size: 2.5rem; margin-bottom: 5px;"><?= $stats_articles ?></h3>
                    <p>Total Artikel</p>
                </div>
                <div style="background: #2ecc71; color: white; padding: 20px; border-radius: 8px; text-align: center;">
                    <h3 style="font-size: 2.5rem; margin-bottom: 5px;"><?= $stats_comments ?></h3>
                    <p>Total Komentar</p>
                </div>
                <div style="background: #9b59b6; color: white; padding: 20px; border-radius: 8px; text-align: center;">
                    <h3 style="font-size: 2.5rem; margin-bottom: 5px;"><?= $stats_users ?></h3>
                    <p>Total Pengguna</p>
                </div>
            </div>

            <div class="admin-actions">
                <a href="create_article.php" class="btn-primary">➕ Buat Artikel Baru</a>
            </div>
        </div>

        <div class="articles-table">
            <h3>Kelola Artikel</h3>

            <?php if ($articles_result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($article = $articles_result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $article['id'] ?></td>
                                <td><?= htmlspecialchars($article['title']) ?></td>
                                <td><?= htmlspecialchars($article['author']) ?></td>
                                <td>
                                    <a href="article.php?id=<?= $article['id'] ?>" class="btn btn-read">Lihat</a>
                                    <a href="edit_article.php?id=<?= $article['id'] ?>" class="btn btn-edit">Edit</a>
                                    <button onclick="if(confirm('Hapus artikel ini?')) location.href='admin.php?delete=<?= $article['id'] ?>'" class="btn btn-delete">Hapus</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align: center; padding: 40px; color: #7f8c8d;">Belum ada artikel.</p>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 GuruDaya - Edukasi Energi Terbarukan Indonesia</p>
        </div>
    </footer>
</body>

</html>