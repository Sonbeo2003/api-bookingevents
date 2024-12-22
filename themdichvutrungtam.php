<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include 'db.php'; // Kết nối đến cơ sở dữ liệu

// Lấy dữ liệu từ yêu cầu
$data = json_decode(file_get_contents("php://input"));

if (isset($data->iddichvu) && isset($data->idtrungtam)) {
    $iddichvu = $conn->real_escape_string($data->iddichvu);
    $idtrungtam = $conn->real_escape_string($data->idtrungtam);

    // Kiểm tra xem dịch vụ đã tồn tại trong trung tâm chưa
    $checkQuery = "SELECT * FROM dichvu_trungtam WHERE iddichvu = ? AND idtrungtam = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ii", $iddichvu, $idtrungtam);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Dịch vụ đã tồn tại trong trung tâm
        echo json_encode(["error" => "Dịch vụ đã có trong trung tâm này."]);
    } else {
        // Thêm dịch vụ vào trung tâm
        $insertQuery = "INSERT INTO dichvu_trungtam (iddichvu, idtrungtam) VALUES (?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ii", $iddichvu, $idtrungtam);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Dịch vụ đã được thêm thành công."]);
        } else {
            echo json_encode(["error" => "Lỗi khi thêm dịch vụ: " . $stmt->error]);
        }
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Thiếu thông tin."]);
}

$conn->close();
?>
