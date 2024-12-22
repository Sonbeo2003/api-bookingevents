<?php
// Kết nối đến cơ sở dữ liệu
include 'db.php';

// Cài đặt kiểu phản hồi JSON
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

// Tạo câu truy vấn để lấy danh sách người dùng
$sql = "SELECT idnguoidung, tennguoidung, email, sodienthoai, diachi, vaitro FROM nguoidung";
$result = mysqli_query($conn, $sql);

// Tạo một mảng để lưu danh sách người dùng
$users = [];

// Lặp qua các dòng dữ liệu và lưu vào mảng
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

// Trả về dữ liệu dạng JSON
echo json_encode($users);

// Đóng kết nối cơ sở dữ liệu
mysqli_close($conn);
?>
