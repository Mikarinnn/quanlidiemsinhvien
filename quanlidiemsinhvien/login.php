<?php
session_start();
include('connect.php');

$error_message = "";

if (isset($_POST['username']) && isset($_POST['password'])) {
    $tenDangNhap = mysqli_real_escape_string($conn, $_POST['username']);
    $matKhau = $_POST['password']; 

    $sql = "SELECT * FROM users WHERE username = '$tenDangNhap' AND password = '$matKhau'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; 
        
        header('Location: index.php');
        exit();
    } else {
        $error_message = "Sai thông tin đăng nhập";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống quản lý điểm sinh viên</title>
    <link rel="stylesheet" href="./style/login.css">
</head>
<body>
    <form action="login.php" method="post">
        <h1>ĐĂNG NHẬP</h1>
        <div class="login">
            <div class="user">
                <input type="text" name="username" placeholder="Nhập mã sinh viên/giảng viên" required>
            </div>
            <div class="pass">
                <input type="password" name="password" placeholder="Mật khẩu" required>
            </div>
            <div class="button">
                <input type="submit" name="action" value="Login">
            </div>
            
            <?php if ($error_message != ""): ?>
                <div class="warning" style="color: red; text-align: center; margin-top: 10px;">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
        </div>
    </form>    
</body>
</html>