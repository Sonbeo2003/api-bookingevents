<?php
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization"); 
header('Content-Type: application/json'); 
include '../db.php';

// Lấy dữ liệu JSON từ yêu cầu PUT
$data = json_decode(file_get_contents("php://input"));

// Kiểm tra xem dữ liệu đã được gửi chưa
if (isset($data->idnguoidung)) {
    // Lấy thông tin người dùng từ dữ liệu
    $idnguoidung = $data->idnguoidung;
    $tennguoidung = $data->tennguoidung;
    $email = $data->email;
    $sodienthoai = $data->sodienthoai;
    $diachi = $data->diachi;

    // Chuẩn bị câu lệnh SQL để cập nhật thông tin người dùng
    $sql = "UPDATE nguoidung SET 
                tennguoidung = ?, 
                email = ?, 
                sodienthoai = ?, 
                diachi = ? 
            WHERE idnguoidung = ?";

    // Chuẩn bị câu lệnh
    if ($stmt = $conn->prepare($sql)) {
        // Liên kết tham số với câu lệnh
        $stmt->bind_param("ssssi", $tennguoidung, $email, $sodienthoai, $diachi, $idnguoidung);

        // Thực thi câu lệnh
        if ($stmt->execute()) {
            // Trả về phản hồi thành công
            echo json_encode(["status" => "success", "message" => "Cập nhật thông tin người dùng thành công."]);
        } else {
            // Trả về lỗi nếu có
            echo json_encode(["status" => "error", "message" => "Lỗi khi cập nhật thông tin."]);
        }

        // Đóng câu lệnh
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Lỗi chuẩn bị câu lệnh."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Thiếu idnguoidung."]);
}

// Đóng kết nối cơ sở dữ liệu
$conn->close();
?>