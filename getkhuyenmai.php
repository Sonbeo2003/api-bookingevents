<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include 'db.php'; // Kết nối đến database

$sql = "SELECT idkhuyenmai, tenkhuyenmai, mota, giatri, ngaybatdau, ngayketthuc, dieukien, trangthai FROM khuyenmai";
$result = $conn->query($sql);

$promotions = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $promotions[] = $row;
    }
    echo json_encode($promotions);
} else {
    echo json_encode(array("message" => "Không tìm thấy khuyến mãi nào"));
}

$conn->close();
?>
