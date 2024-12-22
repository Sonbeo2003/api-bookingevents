<?php
include 'db.php'; // Kết nối đến cơ sở dữ liệu
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header('Content-Type: application/json'); 

// Kiểm tra nếu ID khuyến mãi đã được gửi qua POST
if (isset($_POST['idkhuyenmai'])) {
    $idkhuyenmai = $_POST['idkhuyenmai'];
    $tenkhuyenmai = $_POST['tenkhuyenmai'] ?? '';
    $mota = $_POST['mota'] ?? '';
    $giatri = $_POST['giatri'] ?? 0;
    $dieukien = $_POST['dieukien'] ?? '';
    $ngaybatdau = $_POST['ngaybatdau'] ?? '';
    $ngayketthuc = $_POST['ngayketthuc'] ?? '';
    $trangthai = $_POST['trangthai'] ?? 0;

    // Kiểm tra nếu ID khuyến mãi là số
    if (is_numeric($idkhuyenmai)) {
        // Cập nhật thông tin khuyến mãi
        $stmt = $conn->prepare("UPDATE khuyenmai SET tenkhuyenmai = ?, mota = ?, giatri = ?, dieukien = ?, ngaybatdau = ?, ngayketthuc = ?, trangthai = ? WHERE idkhuyenmai = ?");
        $stmt->bind_param("ssissssi", $tenkhuyenmai, $mota, $giatri, $dieukien, $ngaybatdau, $ngayketthuc, $trangthai, $idkhuyenmai);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Sửa khuyến mãi thành công!"]);
        } else {
            echo json_encode(["message" => "Có lỗi xảy ra khi sửa khuyến mãi."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["message" => "ID khuyến mãi không hợp lệ."]);
    }
} else {
    echo json_encode(["message" => "ID khuyến mãi không được cung cấp."]);
}

$conn->close();
?>
