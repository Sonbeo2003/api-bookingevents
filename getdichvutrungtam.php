<?php
header("Access-Control-Allow-Origin: *"); // Cho phép truy cập từ bất kỳ nguồn nào
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Cho phép các phương thức
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Cho phép các header cụ thể
header('Content-Type: application/json'); // Định dạng dữ liệu trả về là JSON
include 'db.php'; // File kết nối đến database

// Truy vấn để lấy tên dịch vụ và tên trung tâm
$sql = "
    SELECT 
        dvt.id AS id,
        dv.tendichvu, 
        tt.tentrungtam 
    FROM 
        dichvu_trungtam dvt 
    JOIN 
        dichvu dv ON dvt.iddichvu = dv.iddichvu  
    JOIN 
        trungtam tt ON dvt.idtrungtam = tt.idtrungtam";

$result = $conn->query($sql);

$services = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $services[] = array(
            "id" => $row['id'], 
            "tendichvu" => $row['tendichvu'], // Lấy tên dịch vụ
            "tentrungtam" => $row['tentrungtam'] // Lấy tên trung tâm
        );
    }
    echo json_encode($services); // Trả về dữ liệu dịch vụ dưới dạng JSON
} else {
    echo json_encode(array("message" => "No services found")); // Nếu không có dịch vụ nào
}

$conn->close(); // Đóng kết nối
?>