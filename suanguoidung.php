<?php
include 'db.php'; // Kết nối đến cơ sở dữ liệu
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

header('Content-Type: application/json'); // Đảm bảo phản hồi là JSON

// Kiểm tra nếu ID đã được gửi qua POST
if (isset($_POST['idnguoidung'])) {
    $idnguoidung = $_POST['idnguoidung'];
    $tennguoidung = $_POST['tennguoidung'] ?? '';
    $email = $_POST['email'] ?? '';
    $matkhau = $_POST['matkhau'] ?? '';
    $sodienthoai = $_POST['sodienthoai'] ?? '';
    $diachi = $_POST['diachi'] ?? '';
    $vaitro = $_POST['vaitro'] ?? '';

    // Kiểm tra nếu ID là số
    if (is_numeric($idnguoidung)) {
        // Cập nhật người dùng
        $stmt = $conn->prepare("UPDATE nguoidung SET tennguoidung = ?, email = ?, matkhau = ?, sodienthoai = ?, diachi = ?, vaitro = ? WHERE idnguoidung = ?");
        $stmt->bind_param("ssisssi", $tennguoidung, $email, $matkhau, $sodienthoai, $diachi, $vaitro, $idnguoidung);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Sửa người dùng thành công!"]);
        } else {
            echo json_encode(["message" => "Có lỗi xảy ra khi sửa người dùng."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["message" => "ID không hợp lệ."]);
    }
} else {
    echo json_encode(["message" => "ID không được cung cấp."]);
}

$conn->close();
?>
