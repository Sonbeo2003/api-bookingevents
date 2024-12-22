<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header('Content-Type: application/json');
include '../db.php';

// Hàm kiểm tra định dạng ngày
function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

if (isset($_GET['startDate']) && isset($_GET['endDate'])) {
    $startDate = $_GET['startDate'];
    $endDate = $_GET['endDate'];

    // Kiểm tra ngày có hợp lệ không
    if (validateDate($startDate) && validateDate($endDate)) {
        // Chuẩn bị câu truy vấn kết hợp nhiều bảng
        $query = "
            SELECT 
                l.idlichhen, 
                l.idnguoidung, 
                l.idthucung, 
                l.idtrungtam, 
                l.ngayhen, 
                l.ngaytao, 
                l.thoigianhen, 
                CAST(l.trangthai AS CHAR) AS trangthai, 
                u.tennguoidung, 
                t.tentrungtam, 
                p.tenthucung, 
                GROUP_CONCAT(s.tendichvu) AS tendichvu
            FROM lichhen l
            LEFT JOIN nguoidung u ON l.idnguoidung = u.idnguoidung
            LEFT JOIN trungtam t ON l.idtrungtam = t.idtrungtam
            LEFT JOIN thucung p ON l.idthucung = p.idthucung
            LEFT JOIN lichhen_dichvu ld ON l.idlichhen = ld.idlichhen
            LEFT JOIN dichvu s ON ld.iddichvu = s.iddichvu
            WHERE l.ngayhen BETWEEN ? AND ?
            GROUP BY l.idlichhen
        ";

        if ($stmt = $conn->prepare($query)) {
            // Bind tham số và thực thi câu truy vấn
            $stmt->bind_param('ss', $startDate, $endDate);
            $stmt->execute();
            $result = $stmt->get_result();

            // Lấy dữ liệu
            $lichhen = [];
            while ($row = $result->fetch_assoc()) {
                $lichhen[] = $row;
            }

            // Kiểm tra kết quả
            if (empty($lichhen)) {
                echo json_encode(['success' => false, 'message' => 'Không có lịch hẹn nào trong khoảng thời gian này']);
            } else {
                echo json_encode(['success' => true, 'lichhen' => $lichhen]);
            }

            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi khi chuẩn bị câu truy vấn']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Ngày không hợp lệ']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Thiếu tham số ngày']);
}

$conn->close();
?>
