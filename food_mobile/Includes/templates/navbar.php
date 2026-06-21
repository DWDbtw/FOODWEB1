<!-- START NAVBAR SECTION -->
<?php if (session_status() == PHP_SESSION_NONE) session_start(); ?>

    <header id="header" class="header-section">
        <div class="container">
            <nav class="navbar" style="display:flex;justify-content:space-between;align-items:center;">
                <a href="index.php" class="navbar-brand">
                    <img src="Design/images/restaurant-logo.png" alt="Restaurant Logo" style="width: 150px;">
                </a>
                <div class="d-flex menu-wrap align-items-center">
                    <!-- Desktop menu -->
                    <div class="mainmenu" id="mainmenu">
                        <ul class="nav">
                            <li><a href="index.php#home">ГЛАВНАЯ</a></li>
                            <li><a href="index.php#menus">МЕНЮ</a></li>
                            <?php if (!isset($_SESSION['user_id'])): ?>
                                <li><a href="login.php">АВТОРИЗАЦИЯ</a></li>
                            <?php endif; ?>
                            <li><a href="order_food.php">КОРЗИНА <span id="cart-count" class="badge badge-light" style="display:none">0</span></a></li>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <li><a href="user_profile.php">МОЙ АККАУНТ</a></li>
                                <li><a href="logout.php" style="color:#ffdddd;">ВЫЙТИ</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <!-- Hamburger button (mobile) -->
                    <button class="hamburger-btn" id="hamburger-btn" aria-label="Меню">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </nav>
        </div>
    </header>

    <!-- Mobile overlay menu -->
    <div class="mobile-nav-overlay" id="mobile-nav-overlay">
        <ul>
            <li><a href="index.php#home" class="mobile-nav-link">ГЛАВНАЯ</a></li>
            <li><a href="index.php#menus" class="mobile-nav-link">МЕНЮ</a></li>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <li><a href="login.php" class="mobile-nav-link">АВТОРИЗАЦИЯ</a></li>
            <?php endif; ?>
            <li><a href="order_food.php" class="mobile-nav-link">КОРЗИНА <span id="cart-count-mobile" class="badge badge-light" style="display:none">0</span></a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="user_profile.php" class="mobile-nav-link">МОЙ АККАУНТ</a></li>
                <li><a href="logout.php" class="mobile-nav-link" style="color:#ffdddd;">ВЫЙТИ</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <script>
    (function() {
        var btn = document.getElementById('hamburger-btn');
        var overlay = document.getElementById('mobile-nav-overlay');
        if (!btn || !overlay) return;

        btn.addEventListener('click', function() {
            btn.classList.toggle('open');
            overlay.classList.toggle('open');
            document.body.style.overflow = overlay.classList.contains('open') ? 'hidden' : '';
        });

        // Close on nav link click
        var links = overlay.querySelectorAll('.mobile-nav-link');
        links.forEach(function(link) {
            link.addEventListener('click', function() {
                btn.classList.remove('open');
                overlay.classList.remove('open');
                document.body.style.overflow = '';
            });
        });
    })();
    </script>

	<div class="header-height" style="height: 120px;"></div>

    <div class="site-content">

    <!-- END NAVBAR SECTION -->
