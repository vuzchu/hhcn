<?php
// Kết nối đến cơ sở dữ liệu
include 'db_connect.php';
session_start(); // Bắt đầu session

// Lấy movie_id từ URL
$movie_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Truy vấn để lấy thông tin chi tiết của bộ phim
$sql = "SELECT m.movie_id, m.title, m.description, m.release_year, m.image_url, GROUP_CONCAT(g.genre_name SEPARATOR ', ') AS genres
        FROM movies m
        JOIN movie_genres mg ON m.movie_id = mg.movie_id
        JOIN genres g ON mg.genre_id = g.genre_id
        WHERE m.movie_id = $movie_id
        GROUP BY m.movie_id, m.title, m.description, m.release_year, m.image_url";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Lấy thông tin phim
$movie = mysqli_fetch_assoc($result);

if (!$movie) {
    die("Movie not found");
}

// Truy vấn để lấy tập đầu tiên
$sql_first_episode = "SELECT episode_id FROM episodes WHERE movie_id = $movie_id ORDER BY episode_number ASC LIMIT 1";
$result_first_episode = mysqli_query($conn, $sql_first_episode);
$first_episode = mysqli_fetch_assoc($result_first_episode);

// Truy vấn để lấy các bộ anime mới cập nhật (dựa trên update_date)
$sql_related_anime = "SELECT movie_id, title, image_url 
                      FROM movies 
                      ORDER BY updated_at DESC 
                      LIMIT 4";
$result_related_anime = mysqli_query($conn, $sql_related_anime);
$related_anime = mysqli_fetch_all($result_related_anime, MYSQLI_ASSOC);

// Kiểm tra xem người dùng đã đăng nhập chưa và nếu đăng nhập thì kiểm tra họ đã follow chưa
$is_followed = false;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $check_follow_sql = "SELECT * FROM follow_movie WHERE user_id = $user_id AND movie_id = $movie_id";
    $check_follow_result = mysqli_query($conn, $check_follow_sql);
    $is_followed = mysqli_num_rows($check_follow_result) > 0;
}

// Xử lý khi người dùng bấm nút Follow
if (isset($_POST['follow'])) {
    if (!isset($_SESSION['user_id'])) {
        // Chuyển hướng đến trang login nếu chưa đăng nhập
        header("Location: login.php");
        exit();
    } else {
        // Nếu đã đăng nhập, thực hiện logic Follow
        if (!$is_followed) {
            // Thêm vào bảng follow_movie
            $insert_follow_sql = "INSERT INTO follow_movie (user_id, movie_id) VALUES ($user_id, $movie_id)";
            if (mysqli_query($conn, $insert_follow_sql)) {
                $is_followed = true;
                $follow_message = "Movie successfully followed!";
            } else {
                $follow_message = "Error: " . mysqli_error($conn);
            }
        } else {
            $follow_message = "You have already followed this movie.";
        }
    }
}
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
    <!-- Header Section Begin -->
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
                            <div class="anime__details__rating">
                                <div class="rating">
                                    <a href="#"><i class="fa fa-star"></i></a>
                                    <a href="#"><i class="fa fa-star"></i></a>
                                    <a href="#"><i class="fa fa-star"></i></a>
                                    <a href="#"><i class="fa fa-star"></i></a>
                                    <a href="#"><i class="fa fa-star-half-o"></i></a>
                                </div>
                                <span>1.029 Votes</span>
                            </div>
                            <p>Every human inhabiting the world of Alcia is branded by a “Count” or a number written on their body...</p>
                            <div class="anime__details__widget">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <ul>
                                            <li><span>Type:</span> TV Series</li>
                                            <li><span>Studios:</span> Lerche</li>
                                            <li><span>Date aired:</span> Oct 02, 2019 to ?</li>
                                            <li><span>Status:</span> Airing</li>
                                            <li><span>Genre:</span> <?= htmlspecialchars($movie['genres']) ?></li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <ul>
                                            <li><span>Scores:</span> 7.31 / 1,515</li>
                                            <li><span>Rating:</span> 8.5 / 161 times</li>
                                            <li><span>Duration:</span> 24 min/ep</li>
                                            <li><span>Quality:</span> HD</li>
                                            <li><span>Views:</span> 131,541</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="anime__details__btn">
                                <form method="POST" action="">
                                    <button type="submit" name="follow" class="follow-btn">
                                        <i class="fa fa-heart-o"></i> <?= $is_followed ? 'Followed' : 'Follow' ?>
                                    </button>
                                </form>
                                <a href="episode_detail.php?episode_id=<?= $first_episode['episode_id'] ?>&movie_id=<?= $movie_id ?>" class="watch-btn">
                                    <span>Watch Now</span> <i class="fa fa-angle-right"></i>
                                </a>
                            </div>
                            <?php if (isset($follow_message)): ?>
                                <div class="alert alert-info"><?= $follow_message ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-lg-8 col-md-8">
                    <div class="anime__details__review">
                        <div class="section-title">
                            <h5>Reviews</h5>
                        </div>
                        <div class="anime__review__item">
                            <div class="anime__review__item__pic">
                                <img src="img/anime/review-1.jpg" alt="">
                            </div>
                            <div class="anime__review__item__text">
                                <h6>Chris Curry - <span>1 Hour ago</span></h6>
                                <p>whachikan Just noticed that someone categorized this as belonging to the genre "demons" LOL</p>
                            </div>
                        </div>
                    </div>
                    <div class="anime__details__form">
                        <div class="section-title">
                            <h5>Your Comment</h5>
                        </div>
                        <form action="#">
                            <textarea placeholder="Your Comment"></textarea>
                            <button type="submit"><i class="fa fa-location-arrow"></i> Review</button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="anime__details__sidebar">
                        <div class="section-title">
                            <h5>New Releases</h5>
                        </div>
                        <?php foreach ($related_anime as $anime): ?>
                            <div class="product__sidebar__view__item set-bg" data-setbg="<?= htmlspecialchars($anime['image_url']) ?>">
                                <h5><a href="movie_detail.php?id=<?= $anime['movie_id'] ?>"><?= htmlspecialchars($anime['title']) ?></a></h5>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
    </section>
    <!-- Anime Section End -->

    <!-- Footer Section Begin -->
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