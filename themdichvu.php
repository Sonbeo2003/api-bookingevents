<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include 'db.php'; // Kết nối đến cơ sở dữ liệu

// Lấy dữ liệu từ yêu cầu
$data = json_decode(file_get_contents("php://input"));

if (isset($data->tendichvu) && isset($data->mota) && isset($data->gia) && isset($data->thoigianthuchien) && isset($data->hinhanh)) {
    $tendichvu = $conn->real_escape_string($data->tendichvu);
    $mota = $conn->real_escape_string($data->mota);
    $gia = $conn->real_escape_string($data->gia);
    $thoigianthuchien = $conn->real_escape_string($data->thoigianthuchien);
    $hinhanh = $conn->real_escape_string($data->hinhanh);

    // Thêm dịch vụ vào cơ sở dữ liệu
    $stmt = $conn->prepare("INSERT INTO dichvu (tendichvu, mota, gia, thoigianthuchien, hinhanh) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $tendichvu, $mota, $gia, $thoigianthuchien, $hinhanh);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Dịch vụ đã được thêm thành công."]);
    } else {
        echo json_encode(["error" => "Lỗi khi thêm dịch vụ: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Thiếu thông tin."]);
}

$conn->close();
?>
