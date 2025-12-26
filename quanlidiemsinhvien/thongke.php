<?php
session_start();
include('connect.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'daotao') {
    header("Location: index.php");
    exit();
}

$sql_hk = "SELECT DISTINCT hoc_ky FROM diem ORDER BY hoc_ky DESC";
$result_hk = mysqli_query($conn, $sql_hk);

$selected_hk = isset($_GET['hoc_ky']) ? mysqli_real_escape_string($conn, $_GET['hoc_ky']) : '';
$selected_msv = isset($_GET['msv']) ? mysqli_real_escape_string($conn, trim($_GET['msv'])) : '';

$student_label = $selected_msv; 
if ($selected_msv != '') {
    $sql_info = "SELECT ho_ten, lop FROM sinhvien WHERE msv = '$selected_msv'";
    $res_info = mysqli_query($conn, $sql_info);
    if (mysqli_num_rows($res_info) > 0) {
        $row_info = mysqli_fetch_assoc($res_info);
        $student_label = $row_info['ho_ten'] . " - " . $row_info['lop'];
    }
}

$sql_stat = "SELECT 
            SUM(CASE WHEN diem_so >= 8.0 THEN 1 ELSE 0 END) as gioi,
            SUM(CASE WHEN diem_so >= 6.5 AND diem_so < 8.0 THEN 1 ELSE 0 END) as kha,
            SUM(CASE WHEN diem_so >= 5.0 AND diem_so < 6.5 THEN 1 ELSE 0 END) as trung_binh,
            SUM(CASE WHEN diem_so < 5.0 THEN 1 ELSE 0 END) as yeu
        FROM diem";

$conditions = [];
if ($selected_hk != '') {
    $conditions[] = "hoc_ky = '$selected_hk'";
}
if ($selected_msv != '') {
    $conditions[] = "msv LIKE '%$selected_msv%'";
}
if (count($conditions) > 0) {
    $sql_stat .= " WHERE " . implode(' AND ', $conditions);
}

$result_stat = mysqli_query($conn, $sql_stat);
$data = mysqli_fetch_assoc($result_stat);

$chart_data = [
    $data['gioi'] ?? 0,
    $data['kha'] ?? 0,
    $data['trung_binh'] ?? 0,
    $data['yeu'] ?? 0
];

$chart_title = "Thống kê học lực";
if ($selected_msv != '') {
    $chart_title .= " của sinh viên $student_label";
} else {
    $chart_title .= " toàn trường";
}
if ($selected_hk != '') {
    $chart_title .= " - Học kỳ $selected_hk";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thống kê học lực sinh viên</title>
    <link rel="stylesheet" href="./style/index.css">
    <link rel="stylesheet" href="./style/thongke.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<header class="header-daotao">
    <h1>Thống Kê & Báo Cáo</h1>
    <a href="index.php" class="logout-btn">Quay lại trang chủ</a>
</header>

<div class="container">

    <div class="filter-section">
        <form method="GET" action="" style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap; justify-content: center;">
            <div class="filter-group">
                <label for="hoc_ky">Học kỳ:</label>
                <select name="hoc_ky" id="hoc_ky">
                    <option value="">-- Tất cả --</option>
                    <?php 
                    mysqli_data_seek($result_hk, 0);
                    while($row = mysqli_fetch_assoc($result_hk)): 
                    ?>
                        <option value="<?php echo $row['hoc_ky']; ?>" 
                            <?php if($selected_hk == $row['hoc_ky']) echo 'selected'; ?>>
                            <?php echo $row['hoc_ky']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="filter-group">
                <label for="msv">Mã SV:</label>
                <input type="text" name="msv" id="msv" 
                       placeholder="Nhập MSV..." 
                       value="<?php echo htmlspecialchars($selected_msv); ?>">
            </div>

            <button type="submit" class="btn-filter">Xem thống kê</button>
            
            <?php if($selected_hk != '' || $selected_msv != ''): ?>
                <a href="thongke.php" class="clear-filter">Xóa bộ lọc</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="chart-container">
        <h2><?php echo $chart_title; ?></h2>
        
        <?php if (array_sum($chart_data) > 0): ?>
            <canvas id="myPieChart"></canvas>
            
            <?php if($selected_msv != ''): ?>
                <p class="chart-note">
                    Biểu đồ thể hiện tỷ lệ các điểm học phần của sinh viên:<br>
                    <b><?php echo htmlspecialchars($student_label); ?></b>
                </p>
            <?php endif; ?>

        <?php else: ?>
            <div class="no-data">
                <p class="error">Không tìm thấy dữ liệu phù hợp.</p>
                <p class="hint">Vui lòng kiểm tra lại MSV hoặc Học kỳ đã chọn.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    window.chartDataPoints = <?php echo json_encode($chart_data); ?>;
</script>

<script src="./js/thongke.js"></script>

</body>
</html>