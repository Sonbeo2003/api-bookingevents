<?php
// Kết nối với cơ sở dữ liệu
include 'db.php'; // Bao gồm file kết nối đến database
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

// Kiểm tra phương thức yêu cầu có phải POST hay không
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ yêu cầu POST
    $data = json_decode(file_get_contents('php://input'), true);

    // Kiểm tra dữ liệu
    if (
        isset($data['tenkhuyenmai']) && isset($data['mota']) && 
        isset($data['giatri']) && isset($data['dieukien']) && 
        isset($data['ngaybatdau']) && isset($data['ngayketthuc']) && 
        isset($data['trangthai'])
    ) {
        // Lấy giá trị từ dữ liệu gửi lên
        $tenkhuyenmai = $data['tenkhuyenmai'];
        $mota = $data['mota'];
        $giatri = $data['giatri'];
        $dieukien = $data['dieukien'];
        $ngaybatdau = $data['ngaybatdau'];
        $ngayketthuc = $data['ngayketthuc'];
        $trangthai = $data['trangthai'];

        // Chuẩn bị câu lệnh SQL để thêm khuyến mãi
        $query = "INSERT INTO khuyenmai (tenkhuyenmai, mota, giatri, dieukien, ngaybatdau, ngayketthuc, trangthai)
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        if ($stmt = $conn->prepare($query)) {
            // Gán giá trị vào câu lệnh SQL
            $stmt->bind_param("ssissss", $tenkhuyenmai, $mota, $giatri, $dieukien, $ngaybatdau, $ngayketthuc, $trangthai);

            // Thực thi câu lệnh
            if ($stmt->execute()) {
                echo json_encode(['message' => 'Khuyến mãi được thêm thành công']);
            } else {
                echo json_encode(['message' => 'Thêm khuyến mãi thất bại']);
            }

            $stmt->close();
        } else {
            echo json_encode(['message' => 'Lỗi chuẩn bị truy vấn SQL']);
        }
    } else {
        echo json_encode(['message' => 'Dữ liệu không đầy đủ']);
    }
} else {
    echo json_encode(['message' => 'Phương thức không hợp lệ']);
}

$conn->close();
?>
