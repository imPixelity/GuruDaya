<?php
require_once 'config.php';

$articles_query = "SELECT a.*, u.username as author 
                   FROM articles a 
                   JOIN users u ON a.author_id = u.id 
                   ORDER BY a.id DESC";
$articles_result = $conn->query($articles_query);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GuruDaya - Portal Energi Terbarukan</title>
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
                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                        <a href="admin.php" style="color: white;">Dashboard Admin</a>
                    <?php endif; ?>
                    <span>Halo, <?= htmlspecialchars($_SESSION['username']) ?></span>
                    <a href="logout.php" class="btn-logout">Keluar</a>
                <?php else: ?>
                    <a href="login.php" class="btn-login">Masuk</a>
                    <a href="register.php" class="btn-register">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <header class="hero">
        <div class="container">
            <h2>Edukasi Energi Terbarukan untuk Masa Depan Berkelanjutan</h2>
            <p>Pelajari tentang energi surya, angin, air, dan biomassa untuk Indonesia yang lebih hijau</p>
        </div>
    </header>

    <main class="container">
        <section class="articles-section">
            <h2>Artikel Terbaru</h2>

            <?php if ($articles_result->num_rows > 0): ?>
                <div class="articles-grid">
                    <?php while ($article = $articles_result->fetch_assoc()): ?>
                        <div class="article-card">
                            <div class="article-top">
                                <h3><?= htmlspecialchars($article['title']) ?></h3>
                                <p class="article-preview">
                                    <?= htmlspecialchars(substr($article['content'], 0, 150)) ?>...
                                </p>
                            </div>
                            <div class="article-bottom">
                                <div class="article-meta">
                                    <span style="color: black;">📝 <?= htmlspecialchars($article['author']) ?></span>
                                </div>
                                <a href="article.php?id=<?= $article['id'] ?>" class="btn btn-read btn-detail">Baca Selengkapnya</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="no-articles">Belum ada artikel tersedia.</p>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 GuruDaya - Edukasi Energi Terbarukan Indonesia</p>
        </div>
    </footer>
</body>

</html>