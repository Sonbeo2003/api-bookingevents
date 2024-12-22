<?php
header("Access-Control-Allow-Origin: *"); // Cho phép truy cập từ bất kỳ nguồn nào
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Cho phép các phương thức
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Cho phép các header cụ thể
header('Content-Type: application/json'); // Định dạng dữ liệu trả về là JSON
include 'db.php';

// Nhận ID người dùng từ client (thông qua POST hoặc GET)
$idnguoidung = isset($_GET['idnguoidung']) ? intval($_GET['idnguoidung']) : 0;

if ($idnguoidung === 0) {
    echo json_encode(["error" => "ID người dùng không hợp lệ."]);
    exit;
}

$sql = "SELECT idnguoidung, tennguoidung, diachi, sodienthoai, email, vaitro 
        FROM nguoidung 
        WHERE idnguoidung != ?";

// Chuẩn bị câu truy vấn để tránh SQL Injection
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idnguoidung); // Gán giá trị ID người dùng đang đăng nhập
$stmt->execute();
$result = $stmt->get_result();

$nguoidung = [];

// Lấy dữ liệu từ kết quả truy vấn
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $nguoidung[] = $row;
    }
}

// Trả về dữ liệu dưới dạng JSON
echo json_encode($nguoidung);

$stmt->close();
$conn->close();
