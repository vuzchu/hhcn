<?php
// Start session only if it hasn't been started already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Assuming that db_connect.php is already included in the main file before including header.php

// Truy vấn để lấy tất cả các thể loại (genres)
$genre_sql = "SELECT genre_id, genre_name FROM genres ORDER BY genre_name ASC";
$genre_result = mysqli_query($conn, $genre_sql);

// Lưu tất cả các thể loại vào một mảng
$genres = mysqli_fetch_all($genre_result, MYSQLI_ASSOC);
?>

<header class="header">
    <div class="container">
        <div class="row">
            <div class="col-lg-2">
                <div class="header__logo">
                    <a href="index.php">
                        <img src="img/logo.png" alt="">
                    </a>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="header__nav">
                    <nav class="header__menu mobile-menu">
                        <ul>
                            <li class="active"><a href="index.php">Homepage</a></li>
                            <li><a href="./categories.php">Categories <span class="arrow_carrot-down"></span></a>
                                <ul class="dropdown">
                                    <?php if (!empty($genres)): ?>
                                        <?php foreach ($genres as $genre): ?>
                                            <li><a href="categories.php?genre_id=<?= $genre['genre_id'] ?>"><?= htmlspecialchars($genre['genre_name']) ?></a></li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li><a href="#">No Genres Available</a></li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                            <li><a href="./blog.html">Our Blog</a></li>
                            <li><a href="#">Contacts</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="header__right">
                    <a href="#" class="search-switch"><span class="icon_search"></span></a>
                    <?php if (isset($_SESSION['email'])): ?>
                        <div class="dropdown user-dropdown">
                            <a href="#" class="dropdown-toggle">
                                <span class="icon_profile"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="profile.php">View Profile</a></li>
                                <li><a href="favorite_movies.php">Favorite Movies</a></li>
                                <li><a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="login.php"><span class="icon_profile"></span></a>
                    <?php endif; ?>
                </div>
            </div>

        </div>
        <div id="mobile-menu-wrap"></div>
    </div>
</header>

<!-- Search model Begin -->
<div class="search-model">
    <div class="h-100 d-flex align-items-center justify-content-center">
        <div class="search-close-switch"><i class="icon_close"></i></div>
        <form class="search-model-form" action="search_results.php" method="GET">
            <input type="text" id="search-input" name="search" placeholder="Search by movie title..." value="">
        </form>
    </div>
</div>
<!-- Search model end -->

<style>
    .user-dropdown {
        position: relative;
    }

    .user-dropdown .dropdown-menu {
        display: none;
        position: absolute;
        right: 0;
        background-color: #fff;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        padding: 10px 0;
        list-style: none;
        z-index: 1000;
        min-width: 150px;
    }

    .user-dropdown:hover .dropdown-menu {
        display: block;
    }

    .user-dropdown .dropdown-menu li {
        padding: 10px 20px;
    }

    .user-dropdown .dropdown-menu li a {
        color: #333;
        text-decoration: none;
    }

    .user-dropdown .dropdown-menu li a:hover {
        color: #ff6b6b;
    }
</style>