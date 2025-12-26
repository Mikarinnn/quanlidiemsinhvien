<?php
session_start();
include('connect.php');

if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

$msv_session = $_SESSION['username'];

$sql_hk = "SELECT DISTINCT hoc_ky FROM diem WHERE msv = '$msv_session' ORDER BY hoc_ky DESC";
$res_hk = mysqli_query($conn, $sql_hk);

$selected_hk = isset($_GET['hoc_ky']) ? mysqli_real_escape_string($conn, $_GET['hoc_ky']) : '';

$sql_diem = "SELECT hp.ten_hp, d.diem_so, d.hoc_ky 
             FROM diem d 
             JOIN hoc_phan hp ON d.ma_hp = hp.ma_hp 
             WHERE d.msv = '$msv_session'";

if ($selected_hk != '') {
    $sql_diem .= " AND d.hoc_ky = '$selected_hk'";
}
$result_diem = mysqli_query($conn, $sql_diem);

$tong_diem = 0; $so_mon = 0; $ds_diem = [];
while ($row = mysqli_fetch_assoc($result_diem)) {
    $ds_diem[] = $row;
    $tong_diem += $row['diem_so'];
    $so_mon++;
}
$diem_tb = ($so_mon > 0) ? round($tong_diem / $so_mon, 2) : 0;

$danh_gia = "Chưa có dữ liệu";
$color_class = "";
if ($so_mon > 0) {
    if ($diem_tb >= 8.5) { $danh_gia = "XUẤT SẮC"; $color_class = "rank-excellent"; }
    elseif ($diem_tb >= 8.0) { $danh_gia = "GIỎI"; $color_class = "rank-good"; }
    elseif ($diem_tb >= 6.5) { $danh_gia = "KHÁ"; $color_class = "rank-fair"; }
    elseif ($diem_tb >= 5.0) { $danh_gia = "TRUNG BÌNH"; $color_class = "rank-average"; }
    else { $danh_gia = "YẾU/KÉM"; $color_class = "rank-poor"; }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kết quả học tập theo học kỳ</title>
    <link rel="stylesheet" href="./style/index.css">
    <link rel="stylesheet" href="./style/ketqua.css">
</head>
<body>

<header class="header-sinhvien">
    <h1>Kết Quả Học Tập Online</h1>
    <a href="index.php" class="logout-btn">Quay lại trang chủ</a>
</header>

<div class="container">
    <div class="filter-area">
        <form method="GET" action="" id="filterForm">
            <label for="hoc_ky">Chọn học kỳ tra cứu: </label>
            <select name="hoc_ky" id="hoc_ky" onchange="document.getElementById('filterForm').submit()">
                <option value="">-- Tất cả học kỳ --</option>
                <?php mysqli_data_seek($res_hk, 0);?>
                <?php while($row_hk = mysqli_fetch_assoc($res_hk)): ?>
                    <option value="<?php echo $row_hk['hoc_ky']; ?>" <?php if($selected_hk == $row_hk['hoc_ky']) echo 'selected'; ?>>
                        Học kỳ <?php echo $row_hk['hoc_ky']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>
    </div>

    <div class="summary-grid">
        <div class="summary-card">
            <p class="label">Điểm trung bình (GPA)</p>
            <h2 class="value"><?php echo $diem_tb; ?></h2>
        </div>
        <div class="summary-card">
            <p class="label">Xếp loại học lực</p>
            <h2 class="value <?php echo $color_class; ?>"><?php echo $danh_gia; ?></h2>
        </div>
        <div class="summary-card">
            <p class="label">Học kỳ hiện tại</p>
            <h2 class="value"><?php echo $selected_hk ?: "Tất cả"; ?></h2>
        </div>
    </div>

    <div class="table-container">
        <h3>Bảng điểm chi tiết học phần</h3>
        <table class="result-table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên học phần</th>
                    <th>Học kỳ</th>
                    <th>Điểm số</th>
                    <th>Kết quả</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($so_mon > 0): ?>
                    <?php foreach ($ds_diem as $index => $item): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo $item['ten_hp']; ?></td>
                        <td><?php echo $item['hoc_ky']; ?></td>
                        <td style="font-weight: bold;"><?php echo $item['diem_so']; ?></td>
                        <td>
                            <?php if($item['diem_so'] >= 4): ?>
                                <span class="status-pass">Qua môn</span>
                            <?php else: ?>
                                <span class="status-fail">Học lại</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align:center; padding: 20px;">Không tìm thấy dữ liệu điểm cho học kỳ này.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>