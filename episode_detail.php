<?php
// Kết nối đến cơ sở dữ liệu
include 'db_connect.php';

// Lấy episode_id từ URL
$episode_id = isset($_GET['episode_id']) ? intval($_GET['episode_id']) : 0;
$movie_id = isset($_GET['movie_id']) ? intval($_GET['movie_id']) : 0;

// Truy vấn để lấy thông tin chi tiết của tập phim
$sql = "SELECT episode_number, episode_title, episode_description, release_date, source
        FROM episodes
        WHERE episode_id = $episode_id AND movie_id = $movie_id";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Lấy thông tin tập phim
$episode = mysqli_fetch_assoc($result);

if (!$episode) {
    die("Episode not found");
}

// Truy vấn để lấy tất cả các tập của bộ phim
$sql_all_episodes = "SELECT episode_id, episode_number
                     FROM episodes
                     WHERE movie_id = $movie_id
                     ORDER BY episode_number ASC";

$result_all_episodes = mysqli_query($conn, $sql_all_episodes);

if (!$result_all_episodes) {
    die("Query failed: " . mysqli_error($conn));
}

$all_episodes = mysqli_fetch_all($result_all_episodes, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Anime Template">
    <meta name="keywords" content="Anime, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Episode <?= htmlspecialchars($episode['episode_number']) ?> - <?= htmlspecialchars($episode['episode_title']) ?></title>

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

    <style>
        .video-player {
            position: relative;
            padding-bottom: 56.25%;
            /* This keeps the aspect ratio of the video at 16:9 */
            height: 0;
            overflow: hidden;
            max-width: 100%;
            background-color: #000;
            /* Optional: Black background */
        }

        .video-player iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
            /* Remove border */
        }
    </style>
</head>

<body>


    <!-- Header Section Begin -->
    <!-- Include the header -->
    <?php include 'header.php'; ?>
    <!-- Header End -->


    <!-- Anime Section Begin -->
    <section class="anime-details spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="anime__video__player">
                        <div class="video-player">
                            <iframe src="<?= htmlspecialchars($episode['source']) ?>"
                                allowfullscreen
                                title="Video Player"
                                allow="web-share">
                            </iframe>
                        </div>

                    </div>

                    <div class="anime__details__episodes">
                        <div class="section-title">
                            <h5>Episodes</h5>
                        </div>
                        <div class="episode-list">
                            <?php foreach ($all_episodes as $ep): ?>
                                <a href="episode_detail.php?episode_id=<?= $ep['episode_id'] ?>&movie_id=<?= $movie_id ?>"
                                    class="episode-item <?= $ep['episode_id'] == $episode_id ? 'active' : '' ?>">
                                    <?= str_pad($ep['episode_number'], 2, '0', STR_PAD_LEFT) ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8">
                    <div class="anime__details__review">
                        <div class="section-title">
                            <h5>Reviews</h5>
                        </div>
                        <!-- Reviews content here -->
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
            </div>
        </div>
    </section>
    <!-- Anime Section End -->

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

<?php
// Đóng kết nối cơ sở dữ liệu
mysqli_close($conn);
?>