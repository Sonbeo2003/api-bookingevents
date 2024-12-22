<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include 'db.php'; // Kết nối đến cơ sở dữ liệu

// Lấy dữ liệu từ yêu cầu
$data = json_decode(file_get_contents("php://input"));

if (isset($data->tennguoidung) && isset($data->email) && isset($data->matkhau) && isset($data->sodienthoai) && isset($data->diachi) && isset($data->vaitro)) {
    $tennguoidung = $conn->real_escape_string($data->tennguoidung);
    $email = $conn->real_escape_string($data->email);
    $matkhau = $conn->real_escape_string($data->matkhau);
    $sodienthoai = $conn->real_escape_string($data->sodienthoai);
    $diachi = $conn->real_escape_string($data->diachi);
    $vaitro = $conn->real_escape_string($data->vaitro);

    // Thêm người dùng vào cơ sở dữ liệu
    $stmt = $conn->prepare("INSERT INTO nguoidung (tennguoidung, email, matkhau, sodienthoai, diachi, vaitro) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $tennguoidung, $email, $matkhau, $sodienthoai, $diachi, $vaitro);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Thêm thành công."]);
    } else {
        echo json_encode(["error" => "Lỗi khi thêm người dùng: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Thiếu thông tin."]);
}

$conn->close();
?>
