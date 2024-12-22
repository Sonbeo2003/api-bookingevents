<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include 'db.php'; // Kết nối đến cơ sở dữ liệu

// Lấy dữ liệu từ yêu cầu
$data = json_decode(file_get_contents("php://input"));

if (isset($data->tentrungtam) && isset($data->diachi) && isset($data->sodienthoai) && isset($data->email) && isset($data->x_location) && isset($data->y_location) && isset($data->hinhanh) && isset($data->mota)) {
    $tentrungtam = $conn->real_escape_string($data->tentrungtam);
    $diachi = $conn->real_escape_string($data->diachi);
    $sodienthoai = $conn->real_escape_string($data->sodienthoai);
    $email = $conn->real_escape_string($data->email);
    $x_location = $conn->real_escape_string($data->x_location);
    $y_location = $conn->real_escape_string($data->y_location);
    $hinhanh = $conn->real_escape_string($data->hinhanh);
    $mota = $conn->real_escape_string($data->mota);

    // Thêm trung tâm vào cơ sở dữ liệu
    $stmt = $conn->prepare("INSERT INTO trungtam (tentrungtam, diachi, sodienthoai, email, X_location, Y_location, hinhanh, mota) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssddss", $tentrungtam, $diachi, $sodienthoai, $email, $x_location, $y_location, $hinhanh, $mota);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Trung tâm đã được thêm thành công."]);
    } else {
        echo json_encode(["error" => "Lỗi khi thêm trung tâm: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Thiếu thông tin."]);
}

$conn->close();
?>
