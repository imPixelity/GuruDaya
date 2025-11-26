<?php
$is_logged_in = isLoggedIn();
$is_admin = isAdmin();
?>
<nav class="navbar">
    <div class="container">
        <div class="nav-brand">
            <img src="../assets/img/logo_pemweb_fix.png" alt="" class="logo">
            <h1>GuruDaya</h1>
        </div>

        <!-- Hamburger Menu Button -->
        <div class="hamburger spin" onclick="toggleMenu()">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <!-- Navigation Links -->
        <div class="nav-links" id="navLinks">
            <a href="../public/index.php" class="btn-home">Beranda</a>
            <?php if ($is_logged_in): ?>
                <?php if ($is_admin): ?>
                    <a href="../admin/admin.php" style="color: white;">Dashboard Admin</a>
                <?php endif; ?>
                <span class="user-greeting">Halo, <?= htmlspecialchars($_SESSION['username']) ?></span>
                <a href="../auth/logout.php" class="btn-logout">Keluar</a>
            <?php else: ?>
                <a href="../auth/login.php" class="btn-login">Masuk</a>
                <a href="../auth/register.php" class="btn-register">Daftar</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<script>
    function toggleMenu() {
        const hamburger = document.querySelector('.hamburger');
        const navLinks = document.querySelector('#navLinks');

        hamburger.classList.toggle('active');
        navLinks.classList.toggle('active');
    }

    // Close menu when a link is clicked
    document.querySelectorAll('.nav-links a').forEach(link => {
        link.addEventListener('click', () => {
            document.querySelector('.hamburger').classList.remove('active');
            document.querySelector('#navLinks').classList.remove('active');
        });
    });

    // Close menu when clicking outside
    document.addEventListener('click', (event) => {
        const navbar = document.querySelector('.navbar');
        const hamburger = document.querySelector('.hamburger');
        const navLinks = document.querySelector('#navLinks');

        if (!navbar.contains(event.target)) {
            hamburger.classList.remove('active');
            navLinks.classList.remove('active');
        }
    });
</script>