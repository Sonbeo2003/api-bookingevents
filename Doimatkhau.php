<?php
include 'db.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Kết quả trả về mặc định
$response = ["success" => false, "message" => ""];

// Lấy dữ liệu JSON từ yêu cầu
$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Kiểm tra đầu vào
    if (!isset($data['idnguoidung'], $data['oldPassword'], $data['newPassword'])) {
        $response['message'] = "Thiếu thông tin yêu cầu.";
        echo json_encode($response);
        exit;
    }

    $idnguoidung = $data['idnguoidung'];
    $oldPassword = $data['oldPassword'];
    $newPassword = $data['newPassword'];

    // Kiểm tra người dùng có tồn tại không
    $stmt = $conn->prepare("SELECT matkhau FROM nguoidung WHERE idnguoidung = ?");
    $stmt->bind_param("i", $idnguoidung);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $response['message'] = "Người dùng không tồn tại.";
        echo json_encode($response);
        exit;
    }

    $user = $result->fetch_assoc();

    // Kiểm tra mật khẩu cũ
    // Không cần mã hóa mật khẩu, kiểm tra trực tiếp
    if ($oldPassword !== $user['matkhau']) {
        $response['message'] = "Mật khẩu cũ không chính xác.";
        echo json_encode($response);
        exit;
    }

    // Không sử dụng mã hóa cho mật khẩu mới
    $newPassword = $newPassword;  

    // Cập nhật mật khẩu mới (không mã hóa)
    $updateStmt = $conn->prepare("UPDATE nguoidung SET matkhau = ? WHERE idnguoidung = ?");
    $updateStmt->bind_param("si", $newPassword, $idnguoidung);

    if ($updateStmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Đổi mật khẩu thành công.";
    } else {
        $response['message'] = "Lỗi khi cập nhật mật khẩu.";
    }

    $updateStmt->close();
    $stmt->close();
} else {
    $response['message'] = "Yêu cầu không hợp lệ.";
}

// Trả về JSON
echo json_encode($response);
$conn->close();
