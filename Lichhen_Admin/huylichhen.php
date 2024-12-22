<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json'); // Đảm bảo Content-Type là JSON

include '../db.php'; // Kết nối đến cơ sở dữ liệu

// Lấy dữ liệu từ request
$data = json_decode(file_get_contents("php://input"), true);

// Kiểm tra nếu dữ liệu có đầy đủ idlichhen và reason (tên tham số sửa lại)
if (isset($data['idlichhen']) && isset($data['reason'])) {
    $idlichhen = $data['idlichhen'];
    $cancelReason = $data['reason']; // Lấy lý do từ tham số "reason"

    // Kiểm tra nếu lý do hủy có giá trị
    if (empty($cancelReason)) {
        echo json_encode(["error" => "Lý do hủy không thể rỗng."]);
        exit;
    }

    // Cập nhật trạng thái của lịch hẹn thành 'Đã hủy' (trạng thái = 4) và lưu lý do hủy vào CSDL
    $query = "UPDATE lichhen SET trangthai = 4, lydohuy = ? WHERE idlichhen = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $cancelReason, $idlichhen);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Lịch hẹn đã được hủy thành công."]);
    } else {
        echo json_encode(["error" => "Có lỗi xảy ra khi hủy lịch hẹn."]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Dữ liệu không đầy đủ."]);
}

$conn->close();
?>
