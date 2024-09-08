<?php
// Kết nối đến cơ sở dữ liệu
include 'db_connect.php';

$error = '';
$success = '';

// Xử lý khi form đăng ký được gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $fullName = mysqli_real_escape_string($conn, $_POST['fullName']);

    // Kiểm tra xem mật khẩu và xác nhận mật khẩu có khớp không
    if ($password !== $confirm_password) {
        $error = "Mật khẩu và xác nhận mật khẩu không khớp.";
    } else {
        // Kiểm tra xem email đã tồn tại chưa
        $check_email_sql = "SELECT * FROM users WHERE email = '$email'";
        $check_email_result = mysqli_query($conn, $check_email_sql);

        if (mysqli_num_rows($check_email_result) > 0) {
            $error = "Email đã tồn tại.";
        } else {
            // Thêm người dùng mới vào cơ sở dữ liệu với thông tin đầy đủ
            $insert_user_sql = "INSERT INTO users (email, password, fullName) VALUES ('$email', '$password', '$fullName')";
            if (mysqli_query($conn, $insert_user_sql)) {
                $success = "Đăng ký thành công!";
            } else {
                $error = "Có lỗi xảy ra. Vui lòng thử lại.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
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
                        <h3>Sign Up</h3>

                        <!-- Hiển thị thông báo lỗi hoặc thành công -->
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php elseif (!empty($success)): ?>
                            <div class="alert alert-success"><?= $success ?></div>
                        <?php endif; ?>

                        <form action="signup.php" method="POST">
                            <div class="input__item">
                                <input type="email" name="email" placeholder="Email address" required>
                                <span class="icon_mail"></span>
                            </div>
                            <div class="input__item">
                                <input type="text" name="fullName" placeholder="Full Name" required>
                                <span class="icon_profile"></span>
                            </div>
                            <div class="input__item">
                                <input type="password" name="password" placeholder="Password" required>
                                <span class="icon_lock"></span>
                            </div>
                            <div class="input__item">
                                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                                <span class="icon_lock"></span>
                            </div>
                            <button type="submit" class="site-btn">Sign Up Now</button>
                        </form>
                        <h5>Already have an account? <a href="login.php">Log In!</a></h5>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="login__social__links">
                        <h3>Login With:</h3>
                        <ul>
                            <li><a href="#" class="facebook"><i class="fa fa-facebook"></i> Sign in With Facebook</a></li>
                            <li><a href="#" class="google"><i class="fa fa-google"></i> Sign in With Google</a></li>
                            <li><a href="#" class="twitter"><i class="fa fa-twitter"></i> Sign in With Twitter</a></li>
                        </ul>
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