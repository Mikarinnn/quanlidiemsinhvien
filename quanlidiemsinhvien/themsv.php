<?php
session_start();
include('connect.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'daotao') { 
    header("Location: index.php"); 
    exit(); 
}

if (isset($_POST['add'])) {
    $msv = mysqli_real_escape_string($conn, $_POST['msv']);
    $ho_ten = mysqli_real_escape_string($conn, $_POST['ho_ten']);
    $lop = mysqli_real_escape_string($conn, $_POST['lop']);
    $ngay_sinh = $_POST['ngay_sinh'];
    $chuyen_nganh = mysqli_real_escape_string($conn, $_POST['lop_chuyen_nganh']);

    // Mặc định ảnh nếu không upload thành công
    $anh_save_db = "default.png"; 

    // --- XỬ LÝ ẢNH ---
    if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == 0) {
        $target_dir = "img/";
        
        // Tạo tên file duy nhất bằng cách nối MSV với tên file gốc
        $file_name = $msv . "_" . basename($_FILES["fileToUpload"]["name"]);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Kiểm tra định dạng file
        $allow_types = array('jpg', 'png', 'jpeg', 'gif');
        if (in_array($imageFileType, $allow_types)) {
            // Kiểm tra kích thước (dưới 5MB)
            if ($_FILES["fileToUpload"]["size"] < 5000000) {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    $anh_save_db = $file_name; // Lưu tên file để tí nữa Insert vào DB
                }
            } else {
                echo "<script>alert('Lỗi: File quá lớn!');</script>";
            }
        } else {
            echo "<script>alert('Lỗi: Chỉ nhận file ảnh JPG, PNG, JPEG, GIF!');</script>";
        }
    }

    $check = mysqli_query($conn, "SELECT msv FROM sinhvien WHERE msv = '$msv'");
    if(mysqli_num_rows($check) > 0) {
        echo "<script>alert('Lỗi: Mã sinh viên này đã tồn tại!'); window.history.back();</script>";
        exit();
    }

    $sql = "INSERT INTO sinhvien (msv, ho_ten, lop, ngay_sinh, lop_chuyen_nganh, anh_dai_dien) 
            VALUES ('$msv', '$ho_ten', '$lop', '$ngay_sinh', '$chuyen_nganh', '$anh_save_db')";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Thêm sinh viên thành công!'); window.location='quanlysinhvien.php';</script>";
    } else {
        echo "Lỗi SQL: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Sinh Viên</title>
    <link rel="stylesheet" href="./style/themsv.css">
</head>
<body>

<div class="form-container">
    <h2>Thêm Sinh Viên Mới</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Mã sinh viên</label>
            <input type="text" name="msv" placeholder="Ví dụ: SV001" required>
        </div>
        
        <div class="form-group">
            <label>Họ và tên</label>
            <input type="text" name="ho_ten" placeholder="Nhập tên đầy đủ" required>
        </div>

        <div class="form-group">
            <label>Ảnh đại diện</label>
            <input name="fileToUpload" type="file" required/>
        </div>

        <div class="form-group">
            <label>Lớp học</label>
            <input type="text" name="lop" placeholder="Ví dụ: 64CNTT1" required>
        </div>
        
        <div class="form-group">
            <label>Ngày sinh</label>
            <input type="date" name="ngay_sinh" required>
        </div>
        
        <div class="form-group">
            <label>Chuyên ngành</label>
            <input type="text" name="lop_chuyen_nganh" placeholder="Nhập chuyên ngành" required>
        </div>
        
        <button type="submit" name="add">Lưu sinh viên</button>
        <a href="quanlysinhvien.php" class="back-link">← Quay lại danh sách</a>
    </form>
</div>

</body>
</html>