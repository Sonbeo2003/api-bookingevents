<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Content-Type");
include '../db.php'; // Kết nối đến cơ sở dữ liệu

// Kiểm tra xem có ID trong body không
$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['id'])) {
    $id = intval($input['id']); // Lấy id từ body

    // Xóa dịch vụ trung tâm từ cơ sở dữ liệu
    $sql = "DELETE FROM dichvu_trungtam WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Trả về thông báo thành công
        echo json_encode(['status' => 'success', 'message' => 'Dịch vụ trung tâm đã được xóa thành công.']);
    } else {
        // Trả về thông báo lỗi
        echo json_encode(['status' => 'error', 'message' => 'Không thể xóa dịch vụ trung tâm.']);
    }

    $stmt->close();
} else {
    // Nếu không có ID
    echo json_encode(['status' => 'error', 'message' => 'ID không hợp lệ.']);
}

// Đóng kết nối cơ sở dữ liệu
$conn->close();
?>
