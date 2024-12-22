<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include 'db.php'; // Kết nối đến cơ sở dữ liệu

// Lấy dữ liệu từ client
$data = json_decode(file_get_contents("php://input"));

// Kiểm tra nếu dữ liệu không rỗng
if (!empty($data->idnguoidung) && !empty($data->idthucung) && !empty($data->idtrungtam) && !empty($data->ngayhen) && !empty($data->thoigianhen) && !empty($data->dichvu)) {
    // Bắt đầu giao dịch
    mysqli_begin_transaction($conn);

    try {
        // Lưu lịch hẹn
        $query = "INSERT INTO lichhen (idnguoidung, idthucung, idtrungtam, ngayhen, thoigianhen, trangthai, ngaytao)
                  VALUES (?, ?, ?, ?, ?, 'pending', NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiiss", $data->idnguoidung, $data->idthucung, $data->idtrungtam, $data->ngayhen, $data->thoigianhen);
        $stmt->execute();
        
        // Lấy idlichhen vừa tạo
        $idlichhen = $conn->insert_id;

        // Lưu vào bảng trung gian lichhen_dichvu
        foreach ($data->dichvu as $iddichvu) {
            $query_dichvu = "INSERT INTO lichhen_dichvu (idlichhen, iddichvu) VALUES (?, ?)";
            $stmt_dichvu = $conn->prepare($query_dichvu);
            $stmt_dichvu->bind_param("ii", $idlichhen, $iddichvu);
            $stmt_dichvu->execute();
        }

        // Commit giao dịch
        mysqli_commit($conn);
        echo json_encode(["message" => "Lịch hẹn đã được lưu thành công!"]);

    } catch (Exception $e) {
        // Rollback nếu có lỗi
        mysqli_rollback($conn);
        echo json_encode(["message" => "Có lỗi xảy ra khi lưu lịch hẹn."]);
    }

} else {
    echo json_encode(["message" => "Dữ liệu không đầy đủ!"]);
}
?>
