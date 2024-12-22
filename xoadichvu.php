<?php
include 'db.php'; // Kết nối đến database
header("Access-Control-Allow-Origin: *"); // Cho phép mọi nguồn (thay * bằng localhost nếu cần giới hạn)
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS"); // Các phương thức cho phép
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Các header cho phép
header('Content-Type: application/json'); // Đảm bảo header là JSON

// Kiểm tra xem ID đã được gửi qua GET hay chưa
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Kiểm tra nếu ID là số
    if (is_numeric($id)) {
        // Xóa dịch vụ
        $stmt = $conn->prepare("DELETE FROM dichvu WHERE iddichvu = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo json_encode(["message" => "Xóa dịch vụ thành công!"]);
        } else {
            echo json_encode(["message" => "Có lỗi xảy ra khi xóa dịch vụ."]);
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
