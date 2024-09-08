<?php
// Kết nối đến cơ sở dữ liệu
include 'db_connect.php';

session_start(); // Khởi động session
$user_email = $_SESSION['email'] ?? null; // Lấy email từ session

// Nếu người dùng chưa đăng nhập, chuyển hướng về trang đăng nhập
if (!$user_email) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

// Truy vấn thông tin người dùng
$sql = "SELECT * FROM users WHERE email = '$user_email'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    die("Không tìm thấy thông tin người dùng.");
}

// Xử lý khi form chỉnh sửa được gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullName = mysqli_real_escape_string($conn, $_POST['fullName']);

    // Cập nhật tên đầy đủ của người dùng
    $update_sql = "UPDATE users SET fullName = '$fullName' WHERE email = '$user_email'";
    if (mysqli_query($conn, $update_sql)) {
        $success = "Cập nhật thông tin thành công!";
        // Cập nhật lại thông tin người dùng sau khi thay đổi
        $user['fullName'] = $fullName;
    } else {
        $error = "Có lỗi xảy ra. Vui lòng thử lại.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
    <meta name="description" content="Anime Template">
    <meta name="keywords" content="Anime, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

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

    <!-- Signup Section Begin -->
    <section class="signup spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="login__form">
                        <h3>View Profile</h3>

                        <!-- Hiển thị thông báo lỗi hoặc thành công -->
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php elseif (!empty($success)): ?>
                            <div class="alert alert-success"><?= $success ?></div>
                        <?php endif; ?>

                        <!-- Hiển thị thông tin người dùng -->
                        <form action="viewprofile.php" method="POST">
                            <div class="input__item">
                                <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                                <span class="icon_mail"></span>
                            </div>
                            <div class="input__item">
                                <input type="text" name="fullName" value="<?= htmlspecialchars($user['fullName']) ?>" required>
                                <span class="icon_profile"></span>
                            </div>
                            <button type="submit" class="site-btn">Update Full Name</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Signup Section End -->

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