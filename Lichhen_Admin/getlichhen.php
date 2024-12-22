<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');
include '../db.php';

// Truy vấn SQL để lấy thông tin lịch hẹn cùng với tên thú cưng, tên người dùng và tên trung tâm
$sql = "SELECT 
            lichhen.idlichhen, 
            lichhen.idnguoidung, 
            lichhen.idthucung, 
            lichhen.idtrungtam, 
            lichhen.ngayhen, 
            lichhen.thoigianhen, 
            lichhen.trangthai, 
            lichhen.ngaytao,
            GROUP_CONCAT(dichvu.tendichvu) AS tendichvu,
            nguoidung.tennguoidung,  -- Thêm tên người dùng
            thucung.tensukien,      -- Thêm tên thú cưng
            trungtam.tentrungtam     -- Thêm tên trung tâm
        FROM lichhen
        JOIN lichhen_dichvu ON lichhen.idlichhen = lichhen_dichvu.idlichhen
        JOIN dichvu ON lichhen_dichvu.iddichvu = dichvu.iddichvu
        JOIN nguoidung ON lichhen.idnguoidung = nguoidung.idnguoidung  -- Kết hợp bảng người dùng
        JOIN thucung ON lichhen.idthucung = thucung.idthucung          -- Kết hợp bảng thú cưng
        JOIN trungtam ON lichhen.idtrungtam = trungtam.idtrungtam    -- Kết hợp bảng trung tâm
        GROUP BY lichhen.idlichhen";

$result = $conn->query($sql);

$appointments = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }
    echo json_encode($appointments);
} else {
    echo json_encode(array("message" => "No appointments found"));
}

$conn->close();
?>
