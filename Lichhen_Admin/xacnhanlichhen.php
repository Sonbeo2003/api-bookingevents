<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');
include '../db.php';

// Nhận dữ liệu từ yêu cầu
$data = json_decode(file_get_contents("php://input"));

if (isset($data->idlichhen)) {
    $idlichhen = $data->idlichhen;

    // Truy vấn SQL để cập nhật trạng thái lịch hẹn
    $sql = "UPDATE lichhen SET trangthai = 1 WHERE idlichhen = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idlichhen);

    if ($stmt->execute()) {
        // Nếu cập nhật thành công, lấy thông tin lịch hẹn đã cập nhật để trả về
        $stmt->close();
        $stmt = $conn->prepare("SELECT * FROM lichhen WHERE idlichhen = ?");
        $stmt->bind_param("i", $idlichhen);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $updatedBooking = $result->fetch_assoc();
            echo json_encode(array("message" => "Đã xác nhận lịch hẹn thành công.", "booking" => $updatedBooking));
        } else {
            echo json_encode(array("message" => "Lịch hẹn không tồn tại."));
        }
    } else {
        echo json_encode(array("message" => "Có lỗi khi xác nhận lịch hẹn."));
    }

    $stmt->close();
} else {
    echo json_encode(array("message" => "Yêu cầu không hợp lệ."));
}

$conn->close();
?>
