<?php
$is_logged_in = isLoggedIn();
$is_admin = isAdmin();
?>
<nav class="navbar">
    <div class="container">
        <div class="nav-brand">
            <span class="logo">🌱</span>
            <h1>GuruDaya</h1>
        </div>
        <div class="nav-links">
            <a href="../public/index.php" class="btn-home">Beranda</a>
            <?php if ($is_logged_in): ?>
                <?php if ($is_admin): ?>
                    <a href="../admin/admin.php" style="color: white;">Dashboard Admin</a>
                <?php endif; ?>
                <span>Halo, <?= htmlspecialchars($_SESSION['username']) ?></span>
                <a href="../auth/logout.php" class="btn-logout">Keluar</a>
            <?php else: ?>
                <a href="../auth/login.php" class="btn-login">Masuk</a>
                <a href="../auth/register.php" class="btn-register">Daftar</a>
            <?php endif; ?>
        </div>
    </div>
</nav>