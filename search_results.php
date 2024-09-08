<?php
// Kết nối đến cơ sở dữ liệu
include 'db_connect.php';

// Lấy từ khóa tìm kiếm từ URL
$search_keyword = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

if (empty($search_keyword)) {
    die("No search keyword provided.");
}

// Truy vấn để tìm các bộ phim khớp với từ khóa tìm kiếm
$sql = "SELECT m.movie_id, m.title, m.description, m.release_year, m.image_url, 
               GROUP_CONCAT(g.genre_name SEPARATOR ', ') AS genres
        FROM movies m
        JOIN movie_genres mg ON m.movie_id = mg.movie_id
        JOIN genres g ON mg.genre_id = g.genre_id
        WHERE m.title LIKE '%$search_keyword%'
        GROUP BY m.movie_id, m.title, m.description, m.release_year, m.image_url
        ORDER BY m.updated_at DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Lấy tất cả các bộ phim phù hợp dưới dạng mảng liên kết
$movies = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results for <?= htmlspecialchars($search_keyword) ?></title>
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
    <?php include 'header.php'; ?>
    <!-- Header End -->


    <section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h4>Search Results for "<?= htmlspecialchars($search_keyword) ?>"</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php if (!empty($movies)): ?>
                    <?php foreach ($movies as $movie): ?>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="product__item">
                                <div class="product__item__pic set-bg" data-setbg="<?= htmlspecialchars($movie['image_url']) ?>">
                                    <div class="ep"><?= htmlspecialchars($movie['release_year']) ?></div>
                                    <div class="view"><i class="fa fa-eye"></i> <?= rand(1000, 10000) ?></div>
                                </div>
                                <div class="product__item__text">
                                    <ul>
                                        <li><?= htmlspecialchars($movie['genres']) ?></li>
                                    </ul>
                                    <h5><a href="movie_detail.php?id=<?= $movie['movie_id'] ?>"><?= htmlspecialchars($movie['title']) ?></a></h5>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No movies found matching the search criteria.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

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
            </div>
        </div>
    </footer>
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