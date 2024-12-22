<?php
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization"); 
header('Content-Type: application/json'); 
include '../db.php';

// Truy vấn để lấy danh sách thanh toán
$sql = "SELECT * FROM thanhtoan";
$result = $conn->query($sql);

// Kiểm tra nếu có dữ liệu
if ($result->num_rows > 0) {
    // Lưu dữ liệu vào một mảng
    $payments = [];
    while($row = $result->fetch_assoc()) {
        $payments[] = $row;
    }

    // Trả về kết quả dưới dạng JSON
    echo json_encode($payments);
} else {
    // Trả về một mảng rỗng nếu không có dữ liệu
    echo json_encode([]);
}

// Đóng kết nối
$conn->close();
?>