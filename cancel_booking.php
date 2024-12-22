<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header('Content-Type: application/json'); 

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Đọc dữ liệu JSON từ request body
    $data = json_decode(file_get_contents("php://input"), true);

    // Lấy các tham số từ JSON
    $idlichhen = isset($data['idlichhen']) ? $data['idlichhen'] : null;
    $idnguoidung = isset($data['idnguoidung']) ? $data['idnguoidung'] : null;
    $lydohuy = isset($data['lydohuy']) ? $data['lydohuy'] : null;

    // In ra dữ liệu nhận được để kiểm tra
    error_log("idlichhen: $idlichhen, idnguoidung: $idnguoidung, lydohuy: $lydohuy");

    // Kiểm tra các tham số cần thiết
    if ($idlichhen && $idnguoidung && $lydohuy) {
        // Cập nhật trạng thái lịch hẹn thành "Đã hủy"
        $query = "UPDATE lichhen SET trangthai = 4, lydohuy = ? WHERE idlichhen = ? AND idnguoidung = ?";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("sii", $lydohuy, $idlichhen, $idnguoidung);
            if ($stmt->execute()) {
                // Nếu cập nhật thành công
                echo json_encode(['success' => true, 'message' => 'Hủy lịch hẹn thành công']);
            } else {
                // Nếu có lỗi trong quá trình thực thi câu lệnh SQL
                echo json_encode(['success' => false, 'message' => 'Có lỗi khi hủy lịch hẹn.']);
            }
            $stmt->close();
        } else {
            // Nếu không thể chuẩn bị câu lệnh SQL
            echo json_encode(['success' => false, 'message' => 'Lỗi trong quá trình chuẩn bị câu lệnh.']);
        }
    } else {
        // Nếu thiếu tham số
        echo json_encode(['success' => false, 'message' => 'Thiếu thông tin cần thiết.']);
    }
} else {
    // Phương thức không được hỗ trợ
    echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ.']);
}
?>
