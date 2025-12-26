<?php
session_start();
include('connect.php');

if (!isset($_SESSION['role'])) { header("Location: login.php"); exit(); }

$result = null;
$search_query = "";

if (isset($_POST['do_search'])) {
    $keyword = mysqli_real_escape_string($conn, $_POST['keyword']);
    $search_query = $keyword;

    $sql = "SELECT DISTINCT sv.msv, sv.ho_ten, sv.lop, sv.lop_chuyen_nganh, hp.ten_hp, d.diem_so
            FROM sinhvien sv
            LEFT JOIN diem d ON sv.msv = d.msv
            LEFT JOIN hoc_phan hp ON d.ma_hp = hp.ma_hp
            WHERE sv.msv = '$keyword' 
               OR sv.lop_chuyen_nganh LIKE '%$keyword%' 
               OR hp.ma_hp = '$keyword'";
    
    $result = mysqli_query($conn, $sql);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tìm kiếm sinh viên chuyên sâu</title>
    <link rel="stylesheet" href="./style/index.css">
    <style>
        .search-area { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .search-area h2 { color: #2c3e50; margin-top: 0; }
        .input-group { display: flex; gap: 10px; }
        .input-group input { flex: 1; padding: 12px; border: 1px solid #ddd; border-radius: 5px; }
        .btn-search { background: #2980b9; color: white; border: none; padding: 12px 25px; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .btn-search:hover { background: #3498db; }
        
        table { width: 100%; border-collapse: collapse; background: white; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background: #34495e; color: white; }
        .badge { padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; background: #ecf0f1; }
    </style>
</head>
<body>

<header class="<?php echo ($_SESSION['role'] == 'daotao') ? 'header-daotao' : 'header-sinhvien'; ?>">
    <h1>Tra cứu thông tin học tập</h1>
    <a href="index.php" class="logout-btn">Quay lại trang chủ</a>
</header>

<div class="container">
    <div class="search-area">
        <h2>Bộ lọc tìm kiếm</h2>
        <form method="POST">
            <div class="input-group">
                <input type="text" name="keyword" placeholder="Nhập MSV, Mã học phần (VD: PHP01) hoặc Chuyên ngành..." value="<?php echo $search_query; ?>" required>
                <button type="submit" name="do_search" class="btn-search">Tìm kiếm ngay</button>
            </div>
        </form>
    </div>

    <?php if ($result): ?>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>MSV</th>
                        <th>Họ tên</th>
                        <th>Chuyên ngành</th>
                        <th>Học phần</th>
                        <th>Điểm số</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><strong><?php echo $row['msv']; ?></strong></td>
                        <td><?php echo $row['ho_ten']; ?></td>
                        <td><span class="badge"><?php echo $row['lop_chuyen_nganh']; ?></span></td>
                        <td><?php echo $row['ten_hp'] ?? 'Chưa có điểm'; ?></td>
                        <td><?php echo ($row['diem_so'] !== null) ? $row['diem_so'] : '-'; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center; color: #e74c3c;">Không tìm thấy dữ liệu phù hợp với từ khóa: <strong><?php echo $search_query; ?></strong></p>
        <?php endif; ?>
    <?php endif; ?>
</div>

</body>
</html>