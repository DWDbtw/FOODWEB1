<!-- START NAVBAR SECTION -->
<?php if (session_status() == PHP_SESSION_NONE) session_start(); ?>

    <header id="header" class="header-section">
        <div class="container">
            <nav class="navbar">
                <a href="index.php" class="navbar-brand">
                    <img src="Design/images/restaurant-logo.png" alt="Restaurant Logo" style="width: 150px;">
                </a>
                <div class="d-flex menu-wrap align-items-center">
                    <div class="mainmenu" id="mainmenu">
                        <ul class="nav">
                            <li><a href="index.php#home">ГЛАВНАЯ</a></li>
                            <li><a href="index.php#menus">МЕНЮ</a></li>
                            <!-- Replace Contacts with Authorization -->
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
                </div>
            </nav>
        </div>
    </header>

	<div class="header-height" style="height: 120px;"></div>

    <div class="site-content">

    <!-- END NAVBAR SECTION -->