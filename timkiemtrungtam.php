<?php
include 'db.php'; // Kết nối đến cơ sở dữ liệu
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

header('Content-Type: application/json'); // Đảm bảo phản hồi là JSON

// Kiểm tra nếu có từ khóa tìm kiếm được gửi qua GET
if (isset($_GET['searchTerm'])) {
    $searchTerm = $_GET['searchTerm'];
    
    // Sử dụng prepared statement để tránh SQL injection
    $stmt = $conn->prepare("SELECT * FROM trungtam WHERE tentrungtam LIKE CONCAT('%', ?, '%')");
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    // Tạo một mảng để lưu kết quả tìm kiếm
    $centers = array();
    while ($row = $result->fetch_assoc()) {
        $centers[] = $row;
    }

    // Trả về kết quả dưới dạng JSON
    echo json_encode($centers);

    // Đóng statement
    $stmt->close();
} else {
    // Nếu không có từ khóa tìm kiếm, trả về một thông báo
    echo json_encode(["message" => "Không có từ khóa tìm kiếm."]);
}

// Đóng kết nối cơ sở dữ liệu
$conn->close();
?>
