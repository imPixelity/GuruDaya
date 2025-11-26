<?php
require_once '../config/config.php';
require_once '../handlers/article_handlers.php';
require_once '../handlers/statistic_handlers.php';

// Get search parameter
$search = sanitize($_GET['search'] ?? '');

// Get articles based on search
if (!empty($search)) {
    $articles_result = searchArticles($search);
} else {
    $articles_result = getAllArticles();
}

// Get statistics
$total_articles = getTotalArticles();
$total_users = getTotalUsers();
$total_comments = getTotalComments();
$featured_article = getFeaturedArticle();
?>

<?php include '../includes/header.php'; ?>

<?php include '../includes/navbar.php'; ?>

<!-- Hero Section -->
<header class="hero">
    <div class="container">
        <h2>Edukasi Energi Terbarukan untuk Masa Depan Berkelanjutan</h2>
        <p>Pelajari tentang energi surya, angin, air, dan biomassa untuk Indonesia yang lebih hijau</p>
    </div>
</header>

<main class="container">
    <!-- Statistics Section -->
    <section class="stats-container">
        <div class="stat-card">
            <h3><?= $total_articles ?></h3>
            <p>📚 Total Artikel</p>
        </div>
        <div class="stat-card">
            <h3><?= $total_users ?></h3>
            <p>👥 Pengguna Aktif</p>
        </div>
        <div class="stat-card">
            <h3><?= $total_comments ?></h3>
            <p>💬 Total Diskusi</p>
        </div>
    </section>

    <!-- Featured Article Section -->
    <?php if ($featured_article): ?>
        <section class="featured-section">
            <div class="container">
                <div class="featured-content">
                    <div class="featured-text">
                        <div class="featured-badge">⭐ Artikel Unggulan</div>
                        <h2><?= htmlspecialchars($featured_article['title']) ?></h2>
                        <p><?= htmlspecialchars(substr($featured_article['content'], 0, 200)) ?>...</p>
                        <div class="featured-meta">
                            <span>📝 <?= htmlspecialchars($featured_article['author']) ?></span>
                            <span>💬 <?= $featured_article['comment_count'] ?> Diskusi</span>
                        </div>
                        <a href="article.php?id=<?= $featured_article['id'] ?>" class="featured-btn">Baca Selengkapnya →</a>
                    </div>
                    <img class="featured-logo" src="../assets/img/energy.png" alt="">
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Search Section -->
    <section class=" search-section">
        <h3 style="text-align: center; margin-bottom: 20px; color: #2c3e50;">🔍 Cari Artikel</h3>
        <form method="GET" action="">
            <div class="search-container">
                <input type="text" name="search" placeholder="Cari artikel tentang energi terbarukan..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit">Cari</button>
            </div>
        </form>
        <?php if (!empty($search)): ?>
            <div class="clear-search">
                <a href="index.php">← Hapus pencarian</a>
            </div>
        <?php endif; ?>
    </section>

    <!-- Articles Section -->
    <section class="articles-section">
        <?php if (!empty($search)): ?>
            <h2 class="section-title">Hasil Pencarian: "<?= htmlspecialchars($search) ?>"</h2>
        <?php else: ?>
            <h2 class="section-title">📖 Artikel Terbaru</h2>
        <?php endif; ?>

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
            <div class="no-results">
                <h3>😔 Tidak ada artikel ditemukan</h3>
                <p>
                    <?php if (!empty($search)): ?>
                        Coba cari dengan kata kunci yang berbeda
                    <?php else: ?>
                        Belum ada artikel tersedia. Silakan kembali lagi nanti!
                    <?php endif; ?>
                </p>
                <?php if (!empty($search)): ?>
                    <a href="index.php" style="color: #3498db; text-decoration: none; margin-top: 20px; display: inline-block;">← Kembali ke halaman utama</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php include '../includes/footer.php'; ?>