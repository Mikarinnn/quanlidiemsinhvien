<?php
session_start();
include('connect.php');

if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

$msv_session = $_SESSION['username']; 

$sql_sv = "SELECT * FROM sinhvien WHERE msv = '$msv_session'";
$result_sv = mysqli_query($conn, $sql_sv);
$sv = mysqli_fetch_assoc($result_sv);

if (!$sv) {
    die("Dữ liệu sinh viên không tồn tại.");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông tin cá nhân sinh viên</title>
    <link rel="stylesheet" href="./style/index.css">
    <link rel="stylesheet" href="./style/ttsv.css">
</head>
<body>

<header class="header-sinhvien">
    <h1>Hồ Sơ Sinh Viên</h1>
    <a href="index.php" class="logout-btn">Quay lại trang chủ</a>
</header>

<div class="container">
    <div class="profile-card">
        <div class="profile-header">
            <div class="avatar-container">
                <?php 
                    $hinh_anh = (!empty($sv['anh_dai_dien'])) ? "img/" . $sv['anh_dai_dien'] : "img/3.png";
                ?>
                <img src="<?php echo $hinh_anh; ?>" alt="Avatar" class="profile-avatar" onerror="this.src='img/default.png';">
            </div>
            <h2><?php echo $sv['ho_ten']; ?></h2>
            <p>Mã sinh viên: <strong><?php echo $sv['msv']; ?></strong></p>
        </div>

        <div class="profile-info">
            <div class="info-item"><strong>Chuyên ngành:</strong> <?php echo $sv['lop_chuyen_nganh']; ?></div>
            <div class="info-item"><strong>Lớp:</strong> <?php echo $sv['lop']; ?></div>
            <div class="info-item"><strong>Ngày sinh:</strong> <?php echo date('d/m/Y', strtotime($sv['ngay_sinh'])); ?></div>
        </div>
    </div>
</div>

</body>
</html>