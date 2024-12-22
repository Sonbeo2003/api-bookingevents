<?php
include 'db.php'; // Kết nối đến cơ sở dữ liệu

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header('Content-Type: application/json'); // Đảm bảo phản hồi là JSON

// Lấy dữ liệu từ request
$data = json_decode(file_get_contents('php://input'), true);

// Kiểm tra dữ liệu đầu vào
if (isset($data['idthucung'], $data['tensukien'], $data['idnguoidung'], $data['loaisukien'], $data['trungtam'], $data['starttime'], $data['endtime'], $data['trangthai'])) {
    // Lấy dữ liệu từ yêu cầu
    $idthucung = $data['idthucung'];
    $tensukien = $data['tensukien'];
    $idnguoidung = $data['idnguoidung'];
    $loaisukien = $data['loaisukien'];
    $trungtam = $data['trungtam'];
    $starttime = $data['starttime'];
    $endtime = $data['endtime'];
    $trangthai = $data['trangthai'];

    // Lấy tên người dùng theo ID
    $getUserQuery = "SELECT tennguoidung FROM nguoidung WHERE idnguoidung = '$idnguoidung'";
    $userResult = mysqli_query($conn, $getUserQuery);

    // Lấy tên trung tâm theo ID
    $getCenterQuery = "SELECT tentrungtam FROM trungtam WHERE idtrungtam = '$trungtam'";
    $centerResult = mysqli_query($conn, $getCenterQuery);

    if (mysqli_num_rows($userResult) > 0 && mysqli_num_rows($centerResult) > 0) {
        $userRow = mysqli_fetch_assoc($userResult);
        $centerRow = mysqli_fetch_assoc($centerResult);

        $tennguoidung = $userRow['tennguoidung'];
        $tentrungtam = $centerRow['tentrungtam'];

        // Cập nhật thông tin sự kiện
        $updateQuery = "UPDATE thucung 
                        SET tensukien = ?, idnguoidung = ?, loaisukien = ?, trungtam = ?, starttime = ?, endtime = ?, trangthai = ? 
                        WHERE idthucung = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("sssssssi", $tensukien, $idnguoidung, $loaisukien, $trungtam, $starttime, $endtime, $trangthai, $idthucung);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Cập nhật sự kiện thành công"]);
        } else {
            echo json_encode(["success" => false, "message" => "Lỗi khi cập nhật sự kiện: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "ID người dùng hoặc trung tâm không hợp lệ"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Thiếu dữ liệu đầu vào"]);
}

// Đóng kết nối CSDL
mysqli_close($conn);
?>
