<?php
include 'db.php'; // Kết nối đến cơ sở dữ liệu
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

header('Content-Type: application/json'); // Đảm bảo phản hồi là JSON

// Kiểm tra nếu ID đã được gửi qua POST
if (isset($_POST['idtrungtam'])) {
    $idtrungtam = $_POST['idtrungtam'];
    $tentrungtam = $_POST['tentrungtam'] ?? '';
    $diachi = $_POST['diachi'] ?? '';
    $sodienthoai = $_POST['sodienthoai'] ?? '';
    $email = $_POST['email'] ?? '';
    $X_location = $_POST['X_location'] ?? 0.0;
    $Y_location = $_POST['Y_location'] ?? 0.0;
    $hinhanh = $_POST['hinhanh'] ?? '';
    $mota = $_POST['mota'] ?? '';

    // Tăng giá trị X_location thêm 1
    // $X_location += 1;

    // Kiểm tra nếu ID là số
    if (is_numeric($idtrungtam)) {
        // Cập nhật thông tin trung tâm
        $stmt = $conn->prepare("UPDATE trungtam SET tentrungtam = ?, diachi = ?, sodienthoai = ?, email = ?, X_location = ?, Y_location = ?, hinhanh = ?, mota = ? WHERE idtrungtam = ?");
        $stmt->bind_param("ssssddssi", $tentrungtam, $diachi, $sodienthoai, $email, $X_location, $Y_location, $hinhanh, $mota, $idtrungtam);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Sửa trung tâm thành công!"]);
        } else {
            echo json_encode(["message" => "Có lỗi xảy ra khi sửa trung tâm."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["message" => "ID trung tâm không hợp lệ."]);
    }
} else {
    echo json_encode(["message" => "ID trung tâm không được cung cấp."]);
}

$conn->close();
?>
