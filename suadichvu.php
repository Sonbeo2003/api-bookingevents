<?php
include 'db.php'; // Kết nối đến cơ sở dữ liệu
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

header('Content-Type: application/json'); // Đảm bảo phản hồi là JSON

// Kiểm tra nếu ID đã được gửi qua POST
if (isset($_POST['iddichvu'])) {
    $iddichvu = $_POST['iddichvu'];
    $tendichvu = $_POST['tendichvu'] ?? '';
    $mota = $_POST['mota'] ?? '';
    $gia = $_POST['gia'] ?? 0;
    $thoigianthuchien = $_POST['thoigianthuchien'] ?? '';
    $hinhanh = $_POST['hinhanh'] ?? '';

    // Kiểm tra nếu ID là số
    if (is_numeric($iddichvu)) {
        // Cập nhật dịch vụ
        $stmt = $conn->prepare("UPDATE dichvu SET tendichvu = ?, mota = ?, gia = ?, thoigianthuchien = ?, hinhanh = ? WHERE iddichvu = ?");
        $stmt->bind_param("ssissi", $tendichvu, $mota, $gia, $thoigianthuchien, $hinhanh, $iddichvu);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Sửa dịch vụ thành công!"]);
        } else {
            echo json_encode(["message" => "Có lỗi xảy ra khi sửa dịch vụ."]);
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
