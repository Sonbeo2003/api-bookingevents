<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include 'db.php'; // Kết nối cơ sở dữ liệu

// Lấy dữ liệu từ yêu cầu
$data = json_decode(file_get_contents("php://input"), true);

// Kiểm tra dữ liệu đầu vào
if (isset($data['tensukien'], $data['idnguoidung'], $data['loaisukien'], $data['trungtam'], $data['starttime'], $data['endtime'], $data['trangthai'])) {
    
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

        // Chèn dữ liệu vào bảng `thucung`
        $insertQuery = "INSERT INTO thucung (tensukien, idnguoidung, loaisukien, trungtam, starttime, endtime, trangthai) 
                        VALUES ('$tensukien','$idnguoidung', '$loaisukien', '$trungtam', '$starttime', '$endtime', '$trangthai')";

        if (mysqli_query($conn, $insertQuery)) {
            echo json_encode(["success" => true, "message" => "Thêm sự kiện thành công"]);
        } else {
            echo json_encode(["success" => false, "message" => "Lỗi khi thêm sự kiện: " . mysqli_error($conn)]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "ID người dùng hoặc trung tâm không hợp lệ"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Thiếu dữ liệu đầu vào"]);
}

// Đóng kết nối
mysqli_close($conn);
?>
