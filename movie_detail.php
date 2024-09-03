<?php
// Kết nối đến cơ sở dữ liệu
include 'db_connect.php';

// Lấy movie_id từ URL
$movie_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Truy vấn để lấy thông tin chi tiết của bộ phim
$sql = "SELECT m.movie_id, m.title, m.description, m.release_year, m.image_url, m.created_at, m.updated_at, GROUP_CONCAT(g.genre_name SEPARATOR ', ') AS genres
        FROM movies m
        JOIN movie_genres mg ON m.movie_id = mg.movie_id
        JOIN genres g ON mg.genre_id = g.genre_id
        WHERE m.movie_id = $movie_id
        GROUP BY m.movie_id, m.title, m.description, m.release_year, m.image_url, m.created_at, m.updated_at";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Lấy thông tin phim
$movie = mysqli_fetch_assoc($result);

if (!$movie) {
    die("Movie not found");
}

// Truy vấn để lấy danh sách các tập phim của bộ phim
$sql_episodes = "SELECT episode_id, episode_number, episode_title, episode_description, release_date, source
                 FROM episodes
                 WHERE movie_id = $movie_id
                 ORDER BY episode_number ASC";

$result_episodes = mysqli_query($conn, $sql_episodes);

if (!$result_episodes) {
    die("Query failed: " . mysqli_error($conn));
}

// Lấy tất cả các tập phim
$episodes = mysqli_fetch_all($result_episodes, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Anime Template">
    <meta name="keywords" content="Anime, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= htmlspecialchars($movie['title']) ?> | Anime</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

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


    <!-- Anime Section Begin -->
    <section class="anime-details spad">
        <div class="container">
            <div class="anime__details__content">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="anime__details__pic set-bg" data-setbg="<?= htmlspecialchars($movie['image_url']) ?>">
                            <div class="comment"><i class="fa fa-comments"></i> 11</div>
                            <div class="view"><i class="fa fa-eye"></i> 9141</div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="anime__details__text">
                            <div class="anime__details__title">
                                <h3><?= htmlspecialchars($movie['title']) ?></h3>
                                <span><?= htmlspecialchars($movie['description']) ?></span>
                            </div>
                            <div class="anime__details__widget">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <ul>
                                            <?php if (!empty($movie['release_year'])): ?>
                                                <li><span>Date aired:</span> <?= htmlspecialchars($movie['release_year']) ?></li>
                                            <?php endif; ?>
                                            <?php if (!empty($movie['genres'])): ?>
                                                <li><span>Genre:</span> <?= htmlspecialchars($movie['genres']) ?></li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <ul>
                                            <?php if (!empty($movie['created_at'])): ?>
                                                <li><span>Created at:</span> <?= htmlspecialchars($movie['created_at']) ?></li>
                                            <?php endif; ?>
                                            <?php if (!empty($movie['updated_at'])): ?>
                                                <li><span>Updated at:</span> <?= htmlspecialchars($movie['updated_at']) ?></li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                                <!-- Episode List -->
                                <div class="anime__details__episodes">
                                    <h4>Episodes</h4>
                                    <div class="episode-list">
                                        <?php foreach ($episodes as $episode): ?>
                                            <a href="episode_detail.php?episode_id=<?= $episode['episode_id'] ?>&movie_id=<?= $movie_id ?>"
                                                class="episode-item<?= ($current_episode_id == $episode['episode_id']) ? ' active' : '' ?>">
                                                <?= str_pad(htmlspecialchars($episode['episode_number']), 2, '0', STR_PAD_LEFT) ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="anime__details__btn">
                                    <a href="#" class="follow-btn"><i class="fa fa-heart-o"></i> Follow</a>
                                    <a href="#" class="watch-btn"><span>Watch Now</span> <i
                                            class="fa fa-angle-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="anime__details__review">
                            <div class="section-title">
                                <h5>Episodes</h5>
                            </div>
                            <?php if (!empty($episodes)): ?>
                                <?php foreach ($episodes as $episode): ?>
                                    <div class="anime__review__item">
                                        <div class="anime__review__item__pic">
                                            <img src="img/anime/review-1.jpg" alt="">
                                        </div>
                                        <div class="anime__review__item__text">
                                            <h6>
                                                <a href="episode_detail.php?episode_id=<?= $episode['episode_id'] ?>&movie_id=<?= $movie_id ?>">
                                                    Episode <?= htmlspecialchars($episode['episode_number']) ?>: <?= htmlspecialchars($episode['episode_title']) ?>
                                                </a>
                                            </h6>
                                            <p><?= htmlspecialchars($episode['episode_description']) ?></p>
                                            <p><strong>Release date:</strong> <?= htmlspecialchars($episode['release_date']) ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No episodes available for this movie.</p>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
    <!-- Anime Section End -->

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