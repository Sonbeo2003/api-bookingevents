<?php
// Kết nối với cơ sở dữ liệu
include 'db.php'; // Bao gồm file kết nối đến database
header("Access-Control-Allow-Origin: *"); // Cho phép mọi nguồn (thay * bằng localhost nếu cần giới hạn)
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS"); // Các phương thức cho phép
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

// Kiểm tra phương thức yêu cầu có phải DELETE hay không
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Lấy dữ liệu từ yêu cầu DELETE
    $data = json_decode(file_get_contents('php://input'), true);

    // Kiểm tra dữ liệu
    if (isset($data['idkhuyenmai'])) {
        // Lấy giá trị từ dữ liệu gửi lên
        $idkhuyenmai = $data['idkhuyenmai'];

        // Chuẩn bị câu lệnh SQL để xóa khuyến mãi
        $query = "DELETE FROM khuyenmai WHERE idkhuyenmai = ?";

        if ($stmt = $conn->prepare($query)) {
            // Gán giá trị vào câu lệnh SQL
            $stmt->bind_param("i", $idkhuyenmai);

            // Thực thi câu lệnh
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo json_encode(['message' => 'Xóa khuyến mãi thành công']);
                } else {
                    echo json_encode(['message' => 'Không tìm thấy khuyến mãi để xóa']);
                }
            } else {
                echo json_encode(['message' => 'Xóa khuyến mãi thất bại']);
            }

            $stmt->close();
        } else {
            echo json_encode(['message' => 'Lỗi chuẩn bị truy vấn SQL']);
        }
    } else {
        echo json_encode(['message' => 'Dữ liệu không đầy đủ']);
    }
} else {
    echo json_encode(['message' => 'Phương thức không hợp lệ']);
}

$conn->close();
?>
