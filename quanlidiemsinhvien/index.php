<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];
$username = $_SESSION['username'];
// Xác định class CSS dựa trên role
$headerClass = ($role == 'daotao') ? 'header-daotao' : 'header-sinhvien';
$roleName = ($role == 'daotao') ? 'Phòng Đào Tạo' : 'Sinh Viên';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hệ thống QLDSV - Trang chủ</title>
    <link rel="stylesheet" href="./style/index.css">
</head>
<body>

<header class="<?php echo $headerClass; ?>">
    <div>
        <h1>Hệ Thống QLDSV</h1>
        <p>Quyền hạn: <strong><?php echo $roleName; ?></strong></p>
    </div>
    <a href="logout.php" class="logout-btn">Đăng xuất</a>
</header>

<div class="container">
    <h2>Danh mục</h2>
    
    <div class="dashboard-grid">
        <?php if ($role == 'daotao'): ?>
            <a href="quanlysinhvien.php" class="card">
                <span class="icon">👥</span>
                <h3>Quản lý sinh viên</h3>
                <p>Thêm, sửa, xóa và danh sách sinh viên</p>
            </a>
            <a href="thongke.php" class="card">
                <span class="icon">📈</span>
                <h3>Thống kê học lực</h3>
                <p>Xem biểu đồ tỉ lệ Giỏi, Khá, Trung bình</p>
            </a>
            <a href="timkiemsinhvien.php" class="card">
                <span class="icon">🔍</span>
                <h3>Tìm kiếm</h3>
                <p>Tìm kiếm MSV, Lớp, Học phần</p>
            </a>
        <?php else: ?>
            <a href="thongtinsv.php" class="card">
                <span class="icon">🆔</span>
                <h3>Thông tin cá nhân</h3>
                <p>Xem chi tiết hồ sơ sinh viên</p>
            </a>
            <a href="ketqua.php" class="card">
                <span class="icon">🎓</span>
                <h3>Kết quả học tập</h3>
                <p>Xem điểm các học phần và xếp loại</p>
            </a>
        <?php endif; ?>
    </div>
</div>

</body>
</html>