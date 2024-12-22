<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include '../db.php';

$sql = "SELECT COUNT(*) as total_booking FROM lichhen";
$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    $total_booking = $row['total_booking'];

    // Trả về kết quả dưới dạng JSON
    echo json_encode([
        "status" => "success",
        "total_booking" => $total_booking
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
