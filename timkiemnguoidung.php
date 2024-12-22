<?php
include 'db.php'; 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header('Content-Type: application/json'); // Đảm bảo phản hồi là JSON

// Kiểm tra nếu có từ khóa tìm kiếm và idnguoidung
if (isset($_GET['searchTerm']) && isset($_GET['idnguoidung'])) {
    $searchTerm = $_GET['searchTerm'];
    $idnguoidung = $_GET['idnguoidung']; // ID người dùng đang đăng nhập

    // Sử dụng prepared statement để tránh SQL injection
    $stmt = $conn->prepare("SELECT * FROM nguoidung WHERE tennguoidung LIKE CONCAT('%', ?, '%') AND idnguoidung != ?");
    $stmt->bind_param("si", $searchTerm, $idnguoidung); // "si" vì $searchTerm là string và $idnguoidung là integer
    $stmt->execute();
    $result = $stmt->get_result();

    $users = array();
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    echo json_encode($users); // Trả về danh sách người dùng tìm được
    $stmt->close();
} else {
    echo json_encode(["message" => "Không có từ khóa tìm kiếm hoặc idnguoidung."]);
}

$conn->close();
?>
