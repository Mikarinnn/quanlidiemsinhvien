<?php
session_start();
include('connect.php');
if ($_SESSION['role'] != 'daotao') { exit(); }

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    
    $sql = "DELETE FROM sinhvien WHERE msv = '$id'";
    
    if (mysqli_query($conn, $sql)) {
    
        header("Location: quanlysinhvien.php");
    } else {
        echo "Lỗi khi xóa dữ liệu.";
    }
}
?>