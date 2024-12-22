<?php
include 'db.php'; // Kết nối đến cơ sở dữ liệu
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

header('Content-Type: application/json'); // Đảm bảo phản hồi là JSON

// Kiểm tra nếu có từ khóa tìm kiếm
if (isset($_GET['searchTerm'])) {
    $searchTerm = $_GET['searchTerm'];
    
    // Sử dụng prepared statement để tránh SQL injection
    $stmt = $conn->prepare("SELECT * FROM dichvu WHERE tendichvu LIKE CONCAT('%', ?, '%')");
    $stmt->bind_param("s", $searchTerm); // Gán biến cho tham số tìm kiếm
    $stmt->execute(); // Thực thi truy vấn
    $result = $stmt->get_result(); // Lấy kết quả

    // Mảng để lưu trữ danh sách dịch vụ tìm được
    $services = array();
    while ($row = $result->fetch_assoc()) {
        $services[] = $row; // Thêm dịch vụ vào mảng
    }

    // Trả về danh sách dịch vụ ở định dạng JSON
    echo json_encode($services);
    
    // Đóng prepared statement
    $stmt->close();
} else {
    // Trả về thông báo lỗi nếu không có từ khóa tìm kiếm
    echo json_encode(["message" => "Không có từ khóa tìm kiếm."]);
}

// Đóng kết nối cơ sở dữ liệu
$conn->close();
?>
