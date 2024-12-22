<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include '../db.php';

$sql = "SELECT COUNT(*) as total_service FROM dichvu";
$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    $total_service = $row['total_service'];

    // Trả về kết quả dưới dạng JSON
    echo json_encode([
        "status" => "success",
        "total_service" => $total_service
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
