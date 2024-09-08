<?php
// Kết nối đến cơ sở dữ liệu
include 'db_connect.php';
session_start();

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Truy vấn để lấy danh sách các bộ phim mà người dùng đã follow
$sql_followed_movies = "SELECT m.movie_id, m.title, m.image_url
                        FROM movies m
                        JOIN follow_movie f ON m.movie_id = f.movie_id
                        WHERE f.user_id = $user_id";
$result_followed_movies = mysqli_query($conn, $sql_followed_movies);
$followed_movies = mysqli_fetch_all($result_followed_movies, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Followed Movies</title>
    <!-- Css Styles -->
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
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
                        <h4>Your Followed Movies</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php if (!empty($followed_movies)): ?>
                    <?php foreach ($followed_movies as $movie): ?>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="product__item">
                                <div class="product__item__pic set-bg" data-setbg="<?= htmlspecialchars($movie['image_url']) ?>">
                                    <div class="view"><i class="fa fa-eye"></i> <?= rand(1000, 10000) ?></div>
                                </div>
                                <div class="product__item__text">
                                    <h5><a href="movie_detail.php?id=<?= $movie['movie_id'] ?>"><?= htmlspecialchars($movie['title']) ?></a></h5>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>You have not followed any movies yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer Section Begin -->
    <?php include 'footer.php'; ?>
    <!-- Footer Section End -->

    <!-- Js Plugins -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>

</body>

</html>