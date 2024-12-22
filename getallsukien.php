<?php
header("Access-Control-Allow-Origin: *"); // Cho phép truy cập từ bất kỳ nguồn nào
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Cho phép các phương thức
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Cho phép các header cụ thể
header('Content-Type: application/json'); // Định dạng dữ liệu trả về là JSON
include 'db.php'; // File kết nối database

// Truy vấn kết hợp JOIN để lấy tên người dùng và tên trung tâm
$sql = "
    SELECT 
        thucung.idthucung, 
        thucung.tensukien, 
        thucung.idnguoidung, 
        nguoidung.tennguoidung AS tennguoidung, 
        thucung.loaisukien, 
        thucung.trungtam, 
        trungtam.tentrungtam AS tentrungtam, 
        thucung.starttime, 
        thucung.endtime, 
        thucung.trangthai
    FROM 
        thucung
    LEFT JOIN nguoidung ON thucung.idnguoidung = nguoidung.idnguoidung
    LEFT JOIN trungtam ON thucung.trungtam = trungtam.idtrungtam
";

$result = $conn->query($sql);

$services = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
    echo json_encode($services);
} else {
    echo json_encode(array("message" => "No services found"));
}

$conn->close();
?>
