<?php
// Kết nối đến cơ sở dữ liệu
include 'db_connect.php';

// Truy vấn để chọn tất cả các bộ phim và thể loại của chúng
$sql = "SELECT m.movie_id, m.title, m.description, m.release_year, m.image_url, m.created_at, m.updated_at, 
               GROUP_CONCAT(g.genre_name SEPARATOR ', ') AS genres, 
               GROUP_CONCAT(g.genre_id SEPARATOR ',') AS genre_ids
        FROM movies m
        JOIN movie_genres mg ON m.movie_id = mg.movie_id
        JOIN genres g ON mg.genre_id = g.genre_id
        GROUP BY m.movie_id, m.title, m.description, m.release_year, m.image_url, m.created_at, m.updated_at
        ORDER BY m.updated_at DESC";


// Truy vấn để lấy tất cả các thể loại (genres)
$genre_sql = "SELECT genre_id, genre_name FROM genres ORDER BY genre_name ASC";
$genre_result = mysqli_query($conn, $genre_sql);

// Lưu tất cả các thể loại vào một mảng
$genres = mysqli_fetch_all($genre_result, MYSQLI_ASSOC);

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Lấy tất cả các bộ phim dưới dạng mảng liên kết
$movies = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie List</title>
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

    <!-- Header Section Begin -->
    <!-- Include the header -->
    <?php include 'header.php'; ?>
    <!-- Header End -->


    <section class="hero">
        <div class="container">
            <div class="hero__slider owl-carousel">
                <div class="hero__items set-bg" data-setbg="img/hero/hero-1.jpg">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="hero__text">
                                <div class="label">Adventure</div>
                                <h2>Fate / Stay Night: Unlimited Blade Works</h2>
                                <p>After 30 days of travel across the world...</p>
                                <a href="#"><span>Watch Now</span> <i class="fa fa-angle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hero__items set-bg" data-setbg="img/hero/hero-1.jpg">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="hero__text">
                                <div class="label">Adventure</div>
                                <h2>Fate / Stay Night: Unlimited Blade Works</h2>
                                <p>After 30 days of travel across the world...</p>
                                <a href="#"><span>Watch Now</span> <i class="fa fa-angle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hero__items set-bg" data-setbg="img/hero/hero-1.jpg">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="hero__text">
                                <div class="label">Adventure</div>
                                <h2>Fate / Stay Night: Unlimited Blade Works</h2>
                                <p>After 30 days of travel across the world...</p>
                                <a href="#"><span>Watch Now</span> <i class="fa fa-angle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="trending__product">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="section-title">
                                    <h4>Trending Now</h4>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="btn__all">
                                    <a href="#" class="primary-btn">View All <span class="arrow_right"></span></a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <?php if (!empty($movies)): ?>
                                <?php foreach ($movies as $movie): ?>
                                    <div class="col-lg-4 col-md-6 col-sm-6">
                                        <div class="product__item">
                                            <div class="product__item__pic set-bg" data-setbg="<?= htmlspecialchars($movie['image_url']) ?>">
                                                <div class="ep"><?= htmlspecialchars($movie['release_year']) ?></div>
                                                <div class="view"><i class="fa fa-eye"></i> <?= rand(1000, 10000) ?></div>
                                            </div>
                                            <div class="product__item__text">
                                                <ul>
                                                    <?php
                                                    $genres = explode(', ', htmlspecialchars($movie['genres']));
                                                    $genre_ids = explode(',', $movie['genre_ids']);
                                                    foreach ($genres as $index => $genre) {
                                                        echo '<li><a href="categories.php?genre_id=' . $genre_ids[$index] . '">' . $genre . '</a></li>';
                                                    }
                                                    ?>
                                                </ul>
                                                <h5><a href="movie_detail.php?id=<?= $movie['movie_id'] ?>"><?= htmlspecialchars($movie['title']) ?></a></h5>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No movies found</p>
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
                                <?php if (!empty($movies)): ?>
                                    <?php foreach ($movies as $movie): ?>
                                        <div class="product__sidebar__view__item set-bg" data-setbg="<?= htmlspecialchars($movie['image_url']) ?>">
                                            <div class="ep"><?= htmlspecialchars($movie['release_year']) ?></div>
                                            <div class="view"><i class="fa fa-eye"></i> <?= rand(1000, 10000) ?></div>
                                            <h5><a href="movie_detail.php?id=<?= $movie['movie_id'] ?>"><?= htmlspecialchars($movie['title']) ?></a></h5>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>No movies found</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="product__sidebar__comment">
                            <div class="section-title">
                                <h5>New Comment</h5>
                            </div>
                            <!-- Bạn có thể thêm các mục comment động ở đây -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <!-- Include the footer -->
    <?php include 'footer.php'; ?>
    <!-- Footer Section End -->


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