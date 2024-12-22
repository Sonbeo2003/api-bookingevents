<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

include '../db.php';

// Nhận dữ liệu từ yêu cầu
$data = json_decode(file_get_contents("php://input"));

if (isset($data->idlichhen) && is_numeric($data->idlichhen)) {
    $idlichhen = intval($data->idlichhen);

    // Cập nhật trạng thái lịch hẹn
    $sql = "UPDATE lichhen SET trangthai = 3 WHERE idlichhen = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $idlichhen);
        
        if ($stmt->execute()) {
            // Lấy thông tin lịch hẹn vừa cập nhật
            $stmt->close();
            $stmt = $conn->prepare("SELECT * FROM lichhen WHERE idlichhen = ?");
            $stmt->bind_param("i", $idlichhen);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $updatedBooking = $result->fetch_assoc();
                echo json_encode(array("message" => "Đã thanh toán lịch hẹn.", "booking" => $updatedBooking));
            } else {
                echo json_encode(array("message" => "Lịch hẹn không tồn tại."));
            }
        } else {
            echo json_encode(array("message" => "Có lỗi khi cập nhật trạng thái lịch hẹn."));
        }
    } else {
        echo json_encode(array("message" => "Có lỗi trong quá trình chuẩn bị truy vấn."));
    }

    $stmt->close();
} else {
    echo json_encode(array("message" => "Dữ liệu đầu vào không hợp lệ."));
}

$conn->close();
?>
