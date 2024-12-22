<?php
include 'db.php'; // Kết nối đến database
header("Access-Control-Allow-Origin: *"); // Cho phép mọi nguồn (thay * bằng localhost nếu cần giới hạn)
header("Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS"); // Các phương thức cho phép
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Các header cho phép
header('Content-Type: application/json'); // Đảm bảo header là JSON

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['idthucung'])) {
    $idthucung = $data['idthucung'];

    $query = "DELETE FROM thucung WHERE idthucung = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $idthucung);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Không thể xóa thú cưng."]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "ID thú cưng không hợp lệ."]);
}

$conn->close();

?>