<?php
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization"); 
header('Content-Type: application/json'); 
include '../db.php';

$idnguoidung = $_GET['idnguoidung'];

if (!$idnguoidung) {
    echo json_encode(["error" => "Thiếu tham số idnguoidung."]);
    exit;
}

$query = "
    SELECT 
        lichhen.idlichhen,
        lichhen.ngayhen,
        lichhen.thoigianhen,
        lichhen.trangthai,
        trungtam.tentrungtam AS tentrungtam,
        thucung.tensukien AS tensukien,
        GROUP_CONCAT(dichvu.tendichvu SEPARATOR ', ') AS dichvu
    FROM lichhen
    LEFT JOIN trungtam ON lichhen.idtrungtam = trungtam.idtrungtam
    LEFT JOIN thucung ON lichhen.idthucung = thucung.idthucung
    LEFT JOIN lichhen_dichvu ON lichhen.idlichhen = lichhen_dichvu.idlichhen
    LEFT JOIN dichvu ON lichhen_dichvu.iddichvu = dichvu.iddichvu
    WHERE lichhen.idnguoidung = ? AND lichhen.trangthai = 2
    GROUP BY lichhen.idlichhen
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $idnguoidung);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($data);
} else {
    echo json_encode(["error" => "Lỗi khi truy vấn dữ liệu."]);
}

$stmt->close();
$conn->close();
?>
