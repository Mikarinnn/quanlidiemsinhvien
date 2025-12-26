<?php
session_start();
include('connect.php');


if (!isset($_SESSION['role']) || $_SESSION['role'] != 'daotao') { 
    header("Location: index.php");
    exit(); 
}


if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $res = mysqli_query($conn, "SELECT * FROM sinhvien WHERE msv = '$id'");
    $row = mysqli_fetch_assoc($res);
    
    if (!$row) {
        echo "Không tìm thấy sinh viên!";
        exit();
    }
}


if (isset($_POST['update'])) {
    $ho_ten = mysqli_real_escape_string($conn, $_POST['ho_ten']);
    $lop = mysqli_real_escape_string($conn, $_POST['lop']);
    $ngay_sinh = $_POST['ngay_sinh']; // Định dạng YYYY-MM-DD
    $lop_chuyen_nganh = mysqli_real_escape_string($conn, $_POST['lop_chuyen_nganh']);
    
    $update_sql = "UPDATE sinhvien 
                   SET ho_ten='$ho_ten', 
                       lop='$lop', 
                       ngay_sinh='$ngay_sinh', 
                       lop_chuyen_nganh='$lop_chuyen_nganh' 
                   WHERE msv='$id'";
    
    if (mysqli_query($conn, $update_sql)) {
        echo "<script>alert('Cập nhật thành công!'); window.location='quanlysinhvien.php';</script>";
    } else {
        echo "Lỗi cập nhật: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh sửa sinh viên</title>
    <link rel="stylesheet" href="./style/suasv.css">
</head>
<body>

<div class="form-edit">
    <h2>Chỉnh sửa thông tin sinh viên</h2>
    <form method="POST">
        <label>Họ và tên:</label>
        <input type="text" name="ho_ten" value="<?php echo $row['ho_ten']; ?>" required>
        
        <label>Lớp:</label>
        <input type="text" name="lop" value="<?php echo $row['lop']; ?>" required>
        
        <label>Ngày sinh:</label>
        <input type="date" name="ngay_sinh" value="<?php echo $row['ngay_sinh']; ?>" required>
        
        <label>Chuyên ngành:</label>
        <input type="text" name="lop_chuyen_nganh" value="<?php echo $row['lop_chuyen_nganh']; ?>" required>
        
        <button type="submit" name="update" class="btn-submit">Cập nhật thông tin</button>
        <p style="text-align: center;"><a href="quanlysinhvien.php">Hủy bỏ</a></p>
    </form>
</div>

</body>
</html>