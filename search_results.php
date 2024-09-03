<?php
// Kết nối đến cơ sở dữ liệu
include 'db_connect.php';

// Lấy từ khóa tìm kiếm từ URL
$search_keyword = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

if (empty($search_keyword)) {
    die("No search keyword provided.");
}

// Truy vấn để tìm các bộ phim khớp với từ khóa tìm kiếm
$sql = "SELECT m.movie_id, m.title, m.description, m.release_year, m.image_url, GROUP_CONCAT(g.genre_name SEPARATOR ', ') AS genres, GROUP_CONCAT(g.genre_id SEPARATOR ',') AS genre_ids
        FROM movies m
        JOIN movie_genres mg ON m.movie_id = mg.movie_id
        JOIN genres g ON mg.genre_id = g.genre_id
        WHERE m.title LIKE '%$search_keyword%'
        GROUP BY m.movie_id, m.title, m.description, m.release_year, m.image_url";

$result = mysqli_query($conn, $sql);


// Truy vấn để lấy tất cả các thể loại (genres)
$genre_sql = "SELECT genre_id, genre_name FROM genres ORDER BY genre_name ASC";
$genre_result = mysqli_query($conn, $genre_sql);

// Lưu tất cả các thể loại vào một mảng
$genres = mysqli_fetch_all($genre_result, MYSQLI_ASSOC);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Lấy tất cả các bộ phim phù hợp dưới dạng mảng liên kết
$movies = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Anime Template">
    <meta name="keywords" content="Anime, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Anime | Template</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="css/plyr.css" type="text/css">
    <link rel="stylesheet" href="css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Header Section Begin -->
    <!-- Include the header -->
    <?php include 'header.php'; ?>
    <!-- Header End -->

    <!-- Product Section Begin -->
    <section class="product-page spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="product__page__content">
                        <div class="product__page__title">
                            <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-6">
                                    <div class="section-title">
                                        <h4>Search Results</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row movie-container">
                            <?php if (!empty($movies)): ?>
                                <?php foreach ($movies as $movie): ?>
                                    <div class="col-lg-4 col-md-6 col-sm-6">
                                        <div class="product__item movie-card">
                                            <div class="product__item__pic set-bg" data-setbg="<?= htmlspecialchars($movie['image_url']) ?>">
                                                <img src="<?= htmlspecialchars($movie['image_url']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
                                            </div>
                                            <div class="product__item__text">
                                                <ul>
                                                    <li><?= htmlspecialchars($movie['release_year']) ?></li>
                                                    <li><?= htmlspecialchars($movie['genres']) ?></li>
                                                </ul>
                                                <h5><a href="movie_detail.php?id=<?= $movie['movie_id'] ?>"><?= htmlspecialchars($movie['title']) ?></a></h5>
                                                <p><?= htmlspecialchars($movie['description']) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No movies found matching the search criteria.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-8">
                    <div class="product__sidebar">
                        <div class="product__sidebar__view">
                            <div class="section-title">
                                <h5>Top Views</h5>
                            </div>
                            <ul class="filter__controls">
                                <li class="active" data-filter="*">Day</li>
                                <li data-filter=".week">Week</li>
                                <li data-filter=".month">Month</li>
                                <li data-filter=".years">Years</li>
                            </ul>
                            <div class="filter__gallery">
                                <!-- Example Top Viewed Movies -->
                                <div class="product__sidebar__view__item set-bg mix day years"
                                    data-setbg="img/sidebar/tv-1.jpg">
                                    <div class="ep">18 / ?</div>
                                    <div class="view"><i class="fa fa-eye"></i> 9141</div>
                                    <h5><a href="#">Boruto: Naruto next generations</a></h5>
                                </div>
                                <div class="product__sidebar__view__item set-bg mix month week"
                                    data-setbg="img/sidebar/tv-2.jpg">
                                    <div class="ep">18 / ?</div>
                                    <div class="view"><i class="fa fa-eye"></i> 9141</div>
                                    <h5><a href="#">The Seven Deadly Sins: Wrath of the Gods</a></h5>
                                </div>
                                <!-- Add more top viewed items as needed -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Section End -->

    <!-- Footer Section Begin -->
    <footer class="footer">
        <div class="page-up">
            <a href="#" id="scrollToTopButton"><span class="arrow_carrot-up"></span></a>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="footer__logo">
                        <a href="./index.html"><img src="img/logo.png" alt=""></a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="footer__nav">
                        <ul>
                            <li class="active"><a href="./index.html">Homepage</a></li>
                            <li><a href="./categories.html">Categories</a></li>
                            <li><a href="./blog.html">Our Blog</a></li>
                            <li><a href="#">Contacts</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3">
                    <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        Copyright &copy;<script>
                            document.write(new Date().getFullYear());
                        </script> All rights reserved | This template is made with <i class="fa fa-heart"
                            aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>

                </div>
            </div>
        </div>
    </footer>
    <!-- Footer Section End -->

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

    <!-- Js Plugins -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/player.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <script src="js/mixitup.min.js"></script>
    <script src="js/jquery.slicknav.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>

</body>

</html>