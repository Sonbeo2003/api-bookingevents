<?php
include 'db.php'; // Kết nối đến cơ sở dữ liệu

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header('Content-Type: application/json'); // Đảm bảo phản hồi là JSON

// Khởi tạo mảng để lưu trữ kết quả
$results = [];

// Kiểm tra nếu có từ khóa tìm kiếm
if (isset($_GET['searchTerm'])) {
    $searchTerm = $_GET['searchTerm'];
    
    // Tìm kiếm dịch vụ
    $stmtServices = $conn->prepare("SELECT * FROM dichvu WHERE tendichvu LIKE CONCAT('%', ?, '%')");
    $stmtServices->bind_param("s", $searchTerm); // Gán biến cho tham số tìm kiếm
    $stmtServices->execute(); // Thực thi truy vấn
    $resultServices = $stmtServices->get_result(); // Lấy kết quả

    // Mảng để lưu trữ danh sách dịch vụ tìm được
    $services = array();
    while ($row = $resultServices->fetch_assoc()) {
        $services[] = $row; // Thêm dịch vụ vào mảng
    }
    $results['services'] = $services; // Thêm danh sách dịch vụ vào kết quả

    // Tìm kiếm trung tâm theo tên và địa chỉ
    $stmtCenters = $conn->prepare("SELECT * FROM trungtam WHERE tentrungtam LIKE CONCAT('%', ?, '%') OR diachi LIKE CONCAT('%', ?, '%')");
    $stmtCenters->bind_param("ss", $searchTerm, $searchTerm); // Gán biến cho tham số tìm kiếm
    $stmtCenters->execute(); // Thực thi truy vấn
    $resultCenters = $stmtCenters->get_result(); // Lấy kết quả

    // Mảng để lưu trữ danh sách trung tâm tìm được
    $centers = array();
    while ($row = $resultCenters->fetch_assoc()) {
        $centers[] = $row; // Thêm trung tâm vào mảng
    }
    $results['centers'] = $centers; // Thêm danh sách trung tâm vào kết quả

    // Trả về danh sách dịch vụ và trung tâm ở định dạng JSON
    echo json_encode($results);
    
    // Đóng prepared statement
    $stmtServices->close();
    $stmtCenters->close();
} else {
    // Trả về thông báo lỗi nếu không có từ khóa tìm kiếm
    echo json_encode(["message" => "Không có từ khóa tìm kiếm."]);
}

// Đóng kết nối cơ sở dữ liệu
$conn->close();
?>
