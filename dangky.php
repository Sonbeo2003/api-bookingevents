<?php
include "db.php"; // Đảm bảo tệp này bao gồm kết nối cơ sở dữ liệu

header('Content-Type: application/json');

// Lấy dữ liệu từ yêu cầu POST
$data = json_decode(file_get_contents("php://input"));

// Kiểm tra và làm sạch dữ liệu đầu vào
$tennguoidung = isset($data->tennguoidung) ? $conn->real_escape_string($data->tennguoidung) : '';
$email = isset($data->email) ? $conn->real_escape_string($data->email) : '';
$matkhau = isset($data->matkhau) ? $conn->real_escape_string($data->matkhau) : '';
$sodienthoai = isset($data->sodienthoai) ? $conn->real_escape_string($data->sodienthoai) : '';
$diachi = isset($data->diachi) ? $conn->real_escape_string($data->diachi) : '';

// Kiểm tra nếu các trường bắt buộc bị thiếu
if (empty($tennguoidung) || empty($email) || empty($matkhau) || empty($sodienthoai) || empty($diachi)) {
    echo json_encode([
        'success' => false,
        'message' => "Vui lòng nhập đầy đủ thông tin.",
    ]);
    exit;
}

// Kiểm tra xem email đã tồn tại chưa
$query = "SELECT * FROM `nguoidung` WHERE `email` = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $stmt->close();
    echo json_encode([
        'success' => false,
        'message' => "Email đã tồn tại.",
    ]);
} else {
    // Không mã hóa mật khẩu, lưu trực tiếp vào cơ sở dữ liệu
    $query = "INSERT INTO `nguoidung`(`tennguoidung`, `email`, `matkhau`, `sodienthoai`, `diachi`) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $tennguoidung, $email, $matkhau, $sodienthoai, $diachi);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => "Thành công.",
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => "Không thành công.",
        ]);
    }

    $stmt->close();
}

$conn->close();
?>
