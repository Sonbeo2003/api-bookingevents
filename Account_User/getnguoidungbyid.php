<?php
header("Access-Control-Allow-Origin: *"); // Cho phép truy cập từ bất kỳ nguồn nào
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Cho phép các phương thức
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Cho phép các header cụ thể
header('Content-Type: application/json'); // Định dạng dữ liệu trả về là JSON
include '../db.php';

// Lấy idnguoidung từ URL
if (!isset($_GET['idnguoidung'])) {
    http_response_code(400);
    echo json_encode(["error" => "Thiếu tham số idnguoidung"]);
    exit;
}

$idnguoidung = intval($_GET['idnguoidung']);

// Truy vấn cơ sở dữ liệu
$sql = "SELECT * FROM nguoidung WHERE idnguoidung = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idnguoidung);
$stmt->execute();
$result = $stmt->get_result();

// Kiểm tra kết quả
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    http_response_code(200);
    echo json_encode($user);
} else {
    http_response_code(404);
    echo json_encode(["error" => "Không tìm thấy người dùng"]);
}

// Đóng kết nối
$stmt->close();
$conn->close();
?>