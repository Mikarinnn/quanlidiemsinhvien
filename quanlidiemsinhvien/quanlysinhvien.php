<?php
session_start();
include('connect.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'daotao') {
    header("Location: index.php");
    exit();
}

$search = "";
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}

$sql = "SELECT * FROM sinhvien WHERE ho_ten LIKE '%$search%' OR msv LIKE '%$search%'";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý sinh viên</title>
    <link rel="stylesheet" href="./style/index.css"> 
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; vertical-align: middle; }
        th { background-color: #c0392b; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        
        .img-avatar {
            width: 50px;
            height: 50px;
            object-fit: cover; 
            border-radius: 50%; 
            border: 1px solid #ddd;
            display: block;
        }

        .btn { padding: 5px 10px; text-decoration: none; border-radius: 4px; color: white; font-size: 14px; }
        .btn-add { background: #27ae60; margin-bottom: 10px; display: inline-block; }
        .btn-edit { background: #2980b9; }
        .btn-delete { background: #e74c3c; }
    </style>
</head>
<body>

<header class="header-daotao">
    <h1>Quản Lý Danh Sách Sinh Viên</h1>
    <a href="index.php" class="logout-btn">Quay lại trang chủ</a>
</header>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <a href="themsv.php" class="btn btn-add">+ Thêm sinh viên mới</a>
        
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Tìm tên hoặc MSV..." value="<?php echo htmlspecialchars($search); ?>" style="padding: 8px; width: 250px;">
            <button type="submit" style="padding: 8px 15px; cursor: pointer;">Tìm kiếm</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>MSV</th>
                <th>Ảnh</th>
                <th>Họ tên</th>
                <th>Lớp</th>
                <th>Ngày sinh</th>
                <th>Chuyên ngành</th>
                <th>Chức năng</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><strong><?php echo $row['msv']; ?></strong></td>
                <td>
                    <?php 
                        $hinh_anh = (!empty($row['anh_dai_dien'])) ? "img/" . $row['anh_dai_dien'] : "img/default.png";
                    ?>
                    <img src="<?php echo $hinh_anh; ?>" alt="Avatar" class="img-avatar";">
                </td>
                <td><?php echo $row['ho_ten']; ?></td>
                <td><?php echo $row['lop']; ?></td>
                <td><?php echo date('d/m/Y', strtotime($row['ngay_sinh'])); ?></td>
                <td><?php echo $row['lop_chuyen_nganh']; ?></td>
                <td>
                    <a href="suasv.php?id=<?php echo $row['msv']; ?>" class="btn btn-edit">Sửa</a>
                    <a href="xoasv.php?id=<?php echo $row['msv']; ?>" 
                       class="btn btn-delete" 
                       onclick="return confirm('Bạn có chắc chắn muốn xóa sinh viên này?')">Xóa</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>