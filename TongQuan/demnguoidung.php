<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include '../db.php';

// Truy vấn để đếm số lượng người dùng có vai trò là "người dùng"
$sql = "SELECT COUNT(*) as total_users FROM nguoidung WHERE vaitro = '0'";
$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    $total_users = $row['total_users'];

    // Trả về kết quả dưới dạng JSON
    echo json_encode([
        "status" => "success",
        "total_users" => $total_users
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Truy vấn thất bại: " . $conn->error
    ]);
}

// Đóng kết nối
$conn->close();
?>
