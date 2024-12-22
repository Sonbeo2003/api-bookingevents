<?php
header("Access-Control-Allow-Origin: *"); // Cho phép truy cập từ bất kỳ nguồn nào
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Cho phép các phương thức
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Cho phép các header cụ thể
header('Content-Type: application/json'); // Định dạng dữ liệu trả về là JSON
include 'db.php'; 

$sql = "SELECT idtrungtam, tentrungtam, diachi, sodienthoai, email, X_location, Y_location, hinhanh, mota FROM trungtam";
$result = $conn->query($sql);

$centers = [];

if ($result->num_rows > 0) {
    // Lấy dữ liệu từ kết quả truy vấn
    while($row = $result->fetch_assoc()) {
        $centers[] = $row;
    }
}

// Trả về dữ liệu dưới dạng JSON
header('Content-Type: application/json');
echo json_encode($centers);

$conn->close();
?>